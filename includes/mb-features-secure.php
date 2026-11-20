<?php
if (!defined('ABSPATH')) exit;

/* ───────────── ابزارهای مشترک ───────────── */
function mb_lines($s) {
    return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $s))));
}
function mb_ip_match($ip, $entry) {
    if (strpos($entry, '/') === false) return $ip === $entry;
    list($net, $bits) = explode('/', $entry, 2);
    $a = @inet_pton($ip); $b = @inet_pton($net); $bits = (int) $bits;
    if ($a === false || $b === false || strlen($a) !== strlen($b)) return false;
    $bytes = intdiv($bits, 8); $rem = $bits % 8;
    if ($bytes && strncmp($a, $b, $bytes) !== 0) return false;
    if ($rem) {
        $mask = (0xFF << (8 - $rem)) & 0xFF;
        return (ord($a[$bytes]) & $mask) === (ord($b[$bytes]) & $mask);
    }
    return true;
}
function mb_ip_in($ip, $lines) {
    foreach ($lines as $l) if (mb_ip_match($ip, $l)) return true;
    return false;
}
function mb_client_ip($src) {
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    $map = ['cloudflare' => 'HTTP_CF_CONNECTING_IP', 'xreal' => 'HTTP_X_REAL_IP', 'xff' => 'HTTP_X_FORWARDED_FOR'];
    if (isset($map[$src]) && !empty($_SERVER[$map[$src]])) {
        $cand = trim(explode(',', $_SERVER[$map[$src]])[0]);
        if (filter_var($cand, FILTER_VALIDATE_IP)) $ip = $cand;
    }
    return $ip;
}
function mb_host_listed($host, $list) {
    $host = strtolower($host);
    foreach ($list as $d) {
        $d = strtolower(ltrim($d, '.'));
        if ($d === '') continue;
        if ($host === $d || (strlen($host) > strlen($d) && substr($host, -strlen($d) - 1) === '.' . $d)) return true;
    }
    return false;
}
function mb_deny($code, $msg) {
    if (!headers_sent()) { status_header($code); nocache_headers(); header('Content-Type: text/plain; charset=utf-8'); }
    echo $msg;
    exit;
}

/* ───────────── اعمال امنیت و شبکه ───────────── */
function mb_apply_secure($o, $on) {

    /* سخت‌سازی */
    if ($on('sec_xmlrpc')) {
        add_filter('xmlrpc_enabled', '__return_false');
        add_filter('pre_option_enable_xmlrpc', '__return_zero');
        add_filter('wp_headers', function ($h) { unset($h['X-Pingback'], $h['x-pingback']); return $h; });
        add_action('init', function () {
            if (basename(isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '') === 'xmlrpc.php') mb_deny(403, 'XML-RPC غیرفعال است.');
        });
    }
    if ($on('sec_user_enum')) {
        add_action('init', function () {
            if (!is_admin() && !is_user_logged_in() && isset($_SERVER['QUERY_STRING']) && preg_match('/(^|&)author(=|%3d)/i', $_SERVER['QUERY_STRING'])) mb_deny(403, 'دسترسی مجاز نیست.');
        });
        add_filter('rest_endpoints', function ($e) {
            if (!is_user_logged_in()) { unset($e['/wp/v2/users'], $e['/wp/v2/users/(?P<id>[\d]+)']); }
            return $e;
        });
        add_filter('oembed_response_data', function ($d) { unset($d['author_name'], $d['author_url']); return $d; });
        add_filter('wp_sitemaps_add_provider', function ($p, $name) { return $name === 'users' ? false : $p; }, 10, 2);
    }
    if ($on('sec_author_pages')) {
        add_action('template_redirect', function () { if (is_author()) { wp_safe_redirect(home_url('/'), 301); exit; } }, 1);
        add_filter('wp_sitemaps_add_provider', function ($p, $name) { return $name === 'users' ? false : $p; }, 10, 2);
    }
    if ($on('sec_file_editor') && !defined('DISALLOW_FILE_EDIT')) define('DISALLOW_FILE_EDIT', true);
    if ($on('sec_app_passwords')) add_filter('wp_is_application_passwords_available', '__return_false');
    if ($on('sec_rest_batch')) {
        add_filter('rest_pre_dispatch', function ($r, $server, $request) {
            if (!is_user_logged_in() && strpos($request->get_route(), '/batch/v1') === 0) return new WP_Error('rest_forbidden', 'دسترسی مجاز نیست.', ['status' => 401]);
            return $r;
        }, 10, 3);
    }
    if ($o['sec_rest_access'] !== 'everyone') {
        $mode = $o['sec_rest_access'];
        $pub = ['contact-form-7', 'wc/store'];
        add_filter('rest_pre_dispatch', function ($r, $server, $request) use ($mode, $pub) {
            $ns = trim($request->get_route(), '/');
            foreach ($pub as $p) if ($p !== '' && ($ns === $p || strpos($ns, $p . '/') === 0)) return $r;
            $ok = $mode === 'logged_in' ? is_user_logged_in() : current_user_can('edit_posts');
            return $ok ? $r : new WP_Error('rest_forbidden', 'دسترسی به REST API محدود شده است.', ['status' => 401]);
        }, 5, 3);
    }
    if ($on('unregister_tags')) add_action('init', function () { unregister_taxonomy_for_object_type('post_tag', 'post'); }, 100);

    /* هدرها */
    if ($on('sec_headers') || $on('sec_hsts')) {
        add_action('send_headers', function () use ($on) {
            $sent = array_map('strtolower', array_map(function ($h) { return explode(':', $h)[0]; }, headers_list()));
            $set = function ($n, $v) use ($sent) { if (!in_array(strtolower($n), $sent, true)) header($n . ': ' . $v); };
            if ($on('sec_headers')) {
                $set('X-Content-Type-Options', 'nosniff');
                $set('X-Frame-Options', 'SAMEORIGIN');
                $set('Referrer-Policy', 'strict-origin-when-cross-origin');
                $set('Permissions-Policy', 'camera=(), microphone=()');
                $set('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');
            }
            if ($on('sec_hsts') && is_ssl()) $set('Strict-Transport-Security', 'max-age=31536000');
        });
    }

    /* ورود */
    $allow = [];
    $ip = mb_client_ip('remote_addr');
    if ($on('login_limit')) {
        $tries = (int) $o['login_limit_tries']; $win = (int) $o['login_limit_window'] * 60; $lock = (int) $o['login_limit_lock'] * 60;
        $k = 'mb_ll_' . md5($ip); $lk = 'mb_lk_' . md5($ip);
        add_filter('authenticate', function ($user) use ($lk, $ip, $allow) {
            if ($ip && !mb_ip_in($ip, $allow) && get_transient($lk)) return new WP_Error('mb_locked', 'تعداد تلاش‌های ناموفق زیاد بود. چند دقیقه‌ی دیگر دوباره تلاش کنید.');
            return $user;
        }, 1);
        add_action('wp_login_failed', function () use ($k, $lk, $tries, $win, $lock, $ip, $allow) {
            if (!$ip || mb_ip_in($ip, $allow)) return;
            $d = get_transient($k);
            if (!is_array($d) || $d['t'] < time() - $win) $d = ['t' => time(), 'c' => 0];
            $d['c']++;
            set_transient($k, $d, $win);
            if ($d['c'] >= $tries) { set_transient($lk, 1, $lock); delete_transient($k); }
        });
        add_action('wp_login', function () use ($k) { delete_transient($k); });
    }
    if ($on('login_generic_error')) add_filter('login_errors', function () { return 'نام کاربری یا رمز عبور درست نیست.'; });
    $slug = preg_replace('/[^a-z0-9\-_]/i', '', (string) $o['login_slug_val']);
    if ($on('login_slug') && $slug !== '' && !is_multisite() && !defined('MB_DISABLE_LOGIN_URL')) mb_login_slug($slug);

    /* فایروال */
    mb_firewall($o, $on, $ip, $allow);
}

function mb_login_slug($slug) {
    $rewrite = function ($url) use ($slug) {
        return preg_replace('#wp-login\.php#', $slug, $url, 1);
    };
    add_filter('site_url', function ($url, $path) use ($rewrite) { return ($path && strpos($path, 'wp-login.php') === 0) ? $rewrite($url) : $url; }, 10, 2);
    add_filter('network_site_url', function ($url, $path) use ($rewrite) { return ($path && strpos($path, 'wp-login.php') === 0) ? $rewrite($url) : $url; }, 10, 2);
    add_filter('wp_redirect', function ($loc) use ($rewrite) { return strpos($loc, 'wp-login.php') !== false ? $rewrite($loc) : $loc; }, 10, 1);
    add_action('plugins_loaded', function () use ($slug) {
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $path = trim((string) wp_parse_url($uri, PHP_URL_PATH), '/');
        $home = trim((string) wp_parse_url(home_url(), PHP_URL_PATH), '/');
        if ($home !== '' && strpos($path, $home) === 0) $path = trim(substr($path, strlen($home)), '/');
        if ($path === $slug) {
            $GLOBALS['pagenow'] = 'wp-login.php';
            if (!defined('MB_LOGIN_ROUTE')) define('MB_LOGIN_ROUTE', true);
            $_SERVER['SCRIPT_NAME'] = '/wp-login.php';
            require_once ABSPATH . 'wp-login.php';
            exit;
        }
    }, 9999);
    add_action('init', function () {
        if (defined('MB_LOGIN_ROUTE') || is_user_logged_in()) return;
        $script = basename(isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '');
        if ($script === 'wp-login.php') {
            $act = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
            if (in_array($act, ['postpass', 'logout'], true)) return;
            mb_deny(404, 'صفحه پیدا نشد.');
        }
        if (is_admin() && !wp_doing_ajax() && !in_array($script, ['admin-post.php', 'admin-ajax.php', 'load-scripts.php', 'load-styles.php'], true)) mb_deny(404, 'صفحه پیدا نشد.');
    }, 1);
}

function mb_firewall($o, $on, $ip, $allow) {
    $any = $on('fw_signatures') || $on('fw_uploads') || $on('fw_scanners') || $on('fw_same_origin') || $on('fw_methods') || $on('fw_rate');
    if (!$any) return;
    add_action('init', function () use ($o, $on, $ip, $allow) {
        if (wp_doing_cron() || (defined('WP_CLI') && WP_CLI) || !$ip) return;
        if (mb_ip_in($ip, $allow)) return;
        if (current_user_can('manage_options')) return;
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $path = (string) wp_parse_url($uri, PHP_URL_PATH);
        $method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
        $is_rest = strpos($path, '/wp-json/') !== false || isset($_GET['rest_route']);

        if ($on('fw_scanners')) {
            $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
            if ($ua === '' || preg_match('/sqlmap|nikto|nuclei|wpscan|acunetix|nessus|masscan|dirbuster|havij|netsparker|zgrab|nmap|openvas|w3af/i', $ua)) mb_deny(403, 'دسترسی مجاز نیست.');
        }
        if ($on('fw_methods')) {
            $ok = ['GET', 'POST', 'HEAD', 'OPTIONS'];
            if ($is_rest) $ok = array_merge($ok, ['PUT', 'PATCH', 'DELETE']);
            if (!in_array($method, $ok, true)) mb_deny(405, 'متد مجاز نیست.');
        }
        if ($on('fw_same_origin') && $method === 'POST' && preg_match('#/(wp-comments-post|wp-login)\.php$#', $path)) {
            $site = wp_parse_url(home_url(), PHP_URL_HOST);
            $src = !empty($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : (!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
            if ($src !== '' && wp_parse_url($src, PHP_URL_HOST) !== $site) mb_deny(403, 'درخواست از مبدأ دیگر پذیرفته نمی‌شود.');
        }
        if ($on('fw_rate') && !is_user_logged_in()) {
            $max = (int) $o['fw_rate_max']; $win = (int) $o['fw_rate_window']; $blk = (int) $o['fw_rate_block'];
            $bk = 'mb_rb_' . md5($ip);
            if (get_transient($bk)) { header('Retry-After: ' . $blk); mb_deny(429, 'درخواست‌های شما زیاد است. کمی بعد دوباره تلاش کنید.'); }
            $k = 'mb_rl_' . md5($ip);
            $d = get_transient($k);
            if (!is_array($d) || $d['t'] < time() - $win) $d = ['t' => time(), 'c' => 0];
            $d['c']++;
            set_transient($k, $d, $win * 2);
            if ($d['c'] > $max) { set_transient($bk, 1, $blk); header('Retry-After: ' . $blk); mb_deny(429, 'درخواست‌های شما زیاد است. کمی بعد دوباره تلاش کنید.'); }
        }
        if ($on('fw_uploads') && !empty($_FILES)) {
            foreach ($_FILES as $file) {
                $names = (array) (isset($file['name']) ? $file['name'] : []);
                $tmps = (array) (isset($file['tmp_name']) ? $file['tmp_name'] : []);
                array_walk_recursive($names, function ($n) {
                    if (is_string($n) && preg_match('/\.(php\d?|phtml|phar|pl|py|cgi|sh|asp|aspx|jsp|htaccess|htpasswd)(\.|$)/i', $n)) mb_deny(403, 'نوع فایل مجاز نیست.');
                });
                array_walk_recursive($tmps, function ($t) {
                    if (is_string($t) && $t !== '' && is_uploaded_file($t)) {
                        $head = (string) @file_get_contents($t, false, null, 0, 2048);
                        if (stripos($head, '<?php') !== false) mb_deny(403, 'فایل حاوی کد مجاز نیست.');
                    }
                });
            }
        }
        if ($on('fw_signatures') && !current_user_can('edit_posts')) {
            $bad = '#(<\s*script\b|javascript\s*:|\bon(?:error|load|mouseover)\s*=|union\s+(?:all\s+)?select|information_schema|\bsleep\s*\(\s*\d|\bbenchmark\s*\(|/etc/passwd|\.\./\.\./|\bor\s+1\s*=\s*1\b|<\?php)#i';
            $scan = function ($v) use (&$scan, $bad) {
                if (is_array($v)) { foreach ($v as $x) $scan($x); return; }
                if (is_string($v) && $v !== '' && preg_match($bad, rawurldecode($v))) mb_deny(403, 'درخواست مشکوک مسدود شد.');
            };
            $scan($_GET); $scan($_POST);
        }
    }, 1);
}

/* ───────────── شبکه و دامنه‌ها ───────────── */
function mb_net_blocked($url, $o) {
    if (!empty($GLOBALS['mb_updating'])) return false; // خود به‌روزرسانی افزونه از گیت‌هاب
    $host = strtolower((string) wp_parse_url($url, PHP_URL_HOST));
    if ($host === '' || $host === 'localhost' || filter_var($host, FILTER_VALIDATE_IP) && !filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) return false;
    $site = strtolower((string) wp_parse_url(home_url(), PHP_URL_HOST));
    if ($host === $site || mb_host_listed($host, [$site])) return false;
    if (!empty($o['net_mixpanel']) && mb_host_listed($host, ['mixpanel.com', 'mxpnl.com'])) return true;
    if ($o['net_mode'] === 'blacklist') return mb_host_listed($host, mb_lines($o['net_blacklist']));
    if ($o['net_mode'] === 'whitelist') return !mb_host_listed($host, mb_lines($o['net_whitelist']));
    return false;
}

function mb_apply_net($o, $on) {
    $active = $o['net_mode'] !== 'off' || $on('net_mixpanel');
    if ($on('net_learn')) mb_learn_hook();
    if ($active) {
        add_filter('pre_http_request', function ($pre, $args, $url) use ($o) {
            return mb_net_blocked($url, $o) ? new WP_Error('mb_net_blocked', 'درخواست توسط Disable Bloat Manager مسدود شد.') : $pre;
        }, 1, 3);
    }
    if ($on('net_timeout')) {
        $t = (int) $o['net_timeout_sec'];
        add_filter('http_request_args', function ($a) use ($t) {
            if (!empty($GLOBALS['mb_updating'])) return $a;
            if (!isset($a['timeout']) || $a['timeout'] > $t) $a['timeout'] = $t;
            return $a;
        }, 99);
    }
    if ($on('net_telemetry')) {
        add_filter('pre_http_request', function ($pre, $args, $url) {
            $p = wp_parse_url($url);
            $h = isset($p['host']) ? $p['host'] : '';
            if (in_array($h, ['stats.wordpress.org', 'planet.wordpress.org'], true) || ($h === 'wordpress.org' && !empty($p['path']) && strpos($p['path'], '/news/feed') === 0) || ($h === 'api.wordpress.org' && !empty($p['path']) && strpos($p['path'], '/events/') === 0)) {
                return new WP_Error('mb_net_blocked', 'درخواست توسط Disable Bloat Manager مسدود شد.');
            }
            return $pre;
        }, 1, 3);
        add_action('wp_dashboard_setup', function () { remove_meta_box('dashboard_primary', 'dashboard', 'side'); remove_meta_box('dashboard_secondary', 'dashboard', 'side'); });
    }
    if ($on('net_updates_off')) {
        add_filter('pre_http_request', function ($pre, $args, $url) {
            $p = wp_parse_url($url);
            if (!empty($p['host']) && $p['host'] === 'api.wordpress.org' && !empty($p['path']) && preg_match('#^/(core/version-check|plugins/update-check|themes/update-check|core/browse-happy|translations)#', $p['path'])) {
                return new WP_Error('mb_net_blocked', 'بررسی به‌روزرسانی توسط Disable Bloat Manager متوقف شد.');
            }
            return $pre;
        }, 1, 3);
    }
    if ($active && $on('net_block_assets')) {
        add_filter('style_loader_src', function ($src) use ($o) { return ($src && mb_net_blocked($src, $o)) ? false : $src; }, 1);
        add_filter('script_loader_src', function ($src) use ($o) { return ($src && mb_net_blocked($src, $o)) ? false : $src; }, 1);
        $start = function () use ($o) {
            ob_start(function ($html) use ($o) {
                if (!is_string($html) || stripos($html, '<html') === false) return $html;
                $html = preg_replace_callback('#<link\b[^>]*\bhref=["\']([^"\']+)["\'][^>]*>#i', function ($m) use ($o) { return mb_net_blocked(html_entity_decode($m[1]), $o) ? '' : $m[0]; }, $html);
                $html = preg_replace_callback('#<script\b[^>]*\bsrc=["\']([^"\']+)["\'][^>]*>\s*</script>#i', function ($m) use ($o) { return mb_net_blocked(html_entity_decode($m[1]), $o) ? '' : $m[0]; }, $html);
                $html = preg_replace_callback('#<iframe\b[^>]*\bsrc=["\']([^"\']+)["\'][^>]*>\s*</iframe>#i', function ($m) use ($o) { return mb_net_blocked(html_entity_decode($m[1]), $o) ? '' : $m[0]; }, $html);
                return $html;
            });
        };
        add_action('template_redirect', function () use ($start) { if (!is_feed() && !is_embed()) $start(); }, 4);
        add_action('admin_init', function () use ($start) { if (!wp_doing_ajax() && !(defined('DOING_CRON') && DOING_CRON)) $start(); }, 0);
        add_action('login_init', $start, 0);
    }
    if ($active && $on('net_block_runtime')) {
        $blocked = $o['net_mode'] === 'whitelist' ? ['mode' => 'white', 'list' => mb_lines($o['net_whitelist'])] : ['mode' => 'black', 'list' => array_merge(mb_lines($o['net_blacklist']), $on('net_mixpanel') ? ['mixpanel.com', 'mxpnl.com'] : [])];
        $js = '<script>(function(c){var site=location.hostname.toLowerCase();function listed(h){h=h.toLowerCase();return c.list.some(function(d){d=d.toLowerCase();return h===d||h.slice(-d.length-1)==="."+d})}'
            . 'function bad(u){try{var x=new URL(u,location.href);if(!/^https?:$/.test(x.protocol))return false;var h=x.hostname.toLowerCase();if(h===site||h.slice(-site.length-1)==="."+site||h==="localhost")return false;return c.mode==="white"?!listed(h):listed(h)}catch(e){return false}}'
            . 'if(window.fetch){var f=window.fetch;window.fetch=function(i){var u=typeof i==="string"?i:(i&&i.url);return bad(u)?Promise.reject(new TypeError("blocked")):f.apply(this,arguments)}}'
            . 'var o=XMLHttpRequest.prototype.open;XMLHttpRequest.prototype.open=function(m,u){if(bad(u)){this.send=function(){};return}return o.apply(this,arguments)};'
            . 'new MutationObserver(function(ms){ms.forEach(function(m){m.addedNodes.forEach(function(n){if(n.nodeType!==1)return;var s=n.src||n.href;if(s&&/^(SCRIPT|LINK|IFRAME|IMG)$/.test(n.tagName)&&bad(s))n.remove()})})}).observe(document.documentElement,{childList:true,subtree:true});'
            . '})(' . wp_json_encode($blocked) . ');</script>';
        foreach (['wp_head', 'admin_head', 'login_head'] as $h) add_action($h, function () use ($js) { echo $js; }, 0);
    }
}

/* ───────────── یادگیری خودکار دامنه‌ها ───────────── */
function mb_learned_list() {
    $o = mb_get_options();
    $cur = get_option('mb_net_learned', []);
    if (!is_array($cur)) return [];
    $black = mb_lines($o['net_blacklist']);
    $out = [];
    foreach ($cur as $h => $c) {
        if (mb_host_listed($h, $black)) continue;
        $out[] = ['host' => $h, 'count' => (int) $c];
        if (count($out) >= 60) break;
    }
    return $out;
}

function mb_learn_collect($html) {
    if (!is_string($html) || stripos($html, '<html') === false) return;
    if (!preg_match_all('#(?:src|href|data-src|data-href|action)\s*=\s*["\'](?:https?:)?//([^/"\'?\\#\s]+)|url\(\s*["\']?(?:https?:)?//([^/"\')\s]+)|@import\s+["\'](?:https?:)?//([^/"\']+)#i', $html, $m)) return;
    $site = strtolower((string) wp_parse_url(home_url(), PHP_URL_HOST));
    $seen = [];
    foreach (array_merge($m[1], $m[2], $m[3]) as $h) {
        $h = strtolower(preg_replace('/:\d+$/', '', (string) $h));
        if ($h === '' || strpos($h, '.') === false || $h === $site || mb_host_listed($h, [$site]) || filter_var($h, FILTER_VALIDATE_IP)) continue;
        $seen[$h] = 1;
    }
    if (!$seen) return;
    $cur = get_option('mb_net_learned', []);
    if (!is_array($cur)) $cur = [];
    foreach ($seen as $h => $_) $cur[$h] = (isset($cur[$h]) ? (int) $cur[$h] : 0) + 1;
    arsort($cur);
    update_option('mb_net_learned', array_slice($cur, 0, 200, true), false);
}

/** از بخش کوچکی از بازدیدهای عادی نمونه می‌گیرد؛ پس فشار قابل‌توجهی به سرور نمی‌آورد */
function mb_learn_hook() {
    $start = function () {
        if (isset($_GET['page']) && $_GET['page'] === MB_SLUG) return;
        if (mt_rand(1, 12) !== 1) return;
        ob_start(function ($html) { mb_learn_collect($html); return $html; });
    };
    add_action('template_redirect', function () use ($start) { if (!is_feed() && !is_embed() && !is_robots()) $start(); }, 5);
    add_action('admin_init', function () use ($start) { if (!wp_doing_ajax() && !(defined('DOING_CRON') && DOING_CRON)) $start(); }, 0);
}

add_action('wp_ajax_mb_learned', function () {
    mb_ajax_guard();
    $op = isset($_POST['op']) ? sanitize_key(wp_unslash($_POST['op'])) : '';
    $cur = get_option('mb_net_learned', []);
    if (!is_array($cur)) $cur = [];
    if ($op === 'clear') $cur = [];
    if ($op === 'remove') { $h = isset($_POST['host']) ? strtolower(sanitize_text_field(wp_unslash($_POST['host']))) : ''; unset($cur[$h]); }
    update_option('mb_net_learned', $cur, false);
    wp_send_json_success(['learned' => mb_learned_list()]);
});

/* ───────────── مخفی‌سازی منوها ───────────── */
function mb_clean_title($t) {
    $t = preg_replace('#<span\b[^>]*class="[^"]*(?:update-plugins|awaiting-mod|plugin-count|pending-count|count-\d+|ab-icon|dashicons)[^"]*"[^>]*>.*?</span>#is', '', (string) $t);
    $t = html_entity_decode(wp_strip_all_tags($t), ENT_QUOTES, 'UTF-8');
    return trim(preg_replace('/\s+/u', ' ', $t));
}

/** نام فارسی برای موارد شناخته‌شده‌ی نوار بالا؛ در غیر این‌صورت عنوان افزونه یا شناسه */
function mb_bar_label($id, $title) {
    $map = [
        'wp-logo' => 'لوگوی وردپرس', 'site-name' => 'نام سایت', 'updates' => 'به‌روزرسانی‌ها', 'comments' => 'دیدگاه‌ها', 'new-content' => 'جدید (+)',
        'my-account' => 'حساب کاربری', 'search' => 'جستجو', 'customize' => 'سفارشی‌سازی', 'edit' => 'ویرایش', 'view' => 'مشاهده', 'archive' => 'مشاهده‌ی بایگانی',
        'preview' => 'پیش‌نمایش', 'menu-toggle' => 'دکمه‌ی منو (موبایل)', 'command-palette' => 'جستجوی سریع (Ctrl+K)', 'wp-command-palette' => 'جستجوی سریع (Ctrl+K)',
        'about' => 'درباره‌ی وردپرس', 'wp-logo-external' => 'لینک‌های وردپرس', 'wporg' => 'WordPress.org', 'documentation' => 'مستندات', 'learn' => 'آموزش', 'support-forums' => 'انجمن پشتیبانی', 'feedback' => 'بازخورد',
        'user-actions' => 'کارهای حساب', 'user-info' => 'اطلاعات کاربر', 'edit-profile' => 'ویرایش پروفایل', 'logout' => 'خروج', 'dashboard' => 'پیشخوان', 'appearance' => 'نمایش', 'themes' => 'قالب‌ها', 'widgets' => 'ابزارک‌ها', 'menus' => 'فهرست‌ها',
    ];
    if (isset($map[$id])) return $map[$id];
    $t = mb_clean_title($title);
    return $t !== '' ? $t : $id;
}

function mb_apply_menus($o) {
    $hidden = array_filter((array) $o['menus_hide']);
    $bar = array_filter((array) $o['menus_bar']);
    $on_page = is_admin() && isset($_GET['page']) && $_GET['page'] === MB_SLUG;

    if ($on_page) {
        add_action('admin_menu', function () {
            global $menu, $submenu;
            $out = [];
            $count = [];
            foreach ((array) $menu as $m) {
                $slug = isset($m[2]) ? $m[2] : '';
                if ($slug === '' || $slug === MB_SLUG || (isset($m[4]) && strpos((string) $m[4], 'wp-menu-separator') !== false)) continue;
                $title = mb_clean_title(isset($m[0]) ? $m[0] : '');
                if ($title === '') $title = $slug;
                $kids = [];
                foreach ((array) (isset($submenu[$slug]) ? $submenu[$slug] : []) as $s) {
                    if (!isset($s[2]) || $s[2] === MB_SLUG) continue;
                    $st = mb_clean_title(isset($s[0]) ? $s[0] : '');
                    $kids[] = ['key' => 's:' . $slug . '|' . $s[2], 'title' => $st !== '' ? $st : $s[2]];
                }
                $out[] = ['key' => 'm:' . $slug, 'title' => $title, 'children' => $kids, 'locked' => $slug === 'options-general.php', 'slug' => $slug];
                $count[$title] = isset($count[$title]) ? $count[$title] + 1 : 1;
            }
            foreach ($out as &$row) {
                if ($count[$row['title']] > 1) $row['title'] .= ' · ' . mb_substr($row['slug'], 0, 28);
                unset($row['slug']);
            }
            $GLOBALS['mb_menu_snapshot'] = $out;
        }, 99998);

        // موارد نوار بالا را در دو مرحله جمع می‌کنیم تا موارد دیرهنگام و موارد مخفی‌شده هم در فهرست بمانند
        $GLOBALS['mb_bar_nodes'] = [];
        $gather = function ($b) {
            foreach ((array) $b->get_nodes() as $n) {
                if (!isset($GLOBALS['mb_bar_nodes'][$n->id])) $GLOBALS['mb_bar_nodes'][$n->id] = ['p' => (string) ($n->parent ? $n->parent : ''), 't' => $n->title];
            }
        };
        add_action('admin_bar_menu', $gather, 99998);
        add_action('wp_before_admin_bar_render', function () use ($gather) { global $wp_admin_bar; if ($wp_admin_bar) $gather($wp_admin_bar); }, 1);
    }
    if ($hidden) {
        add_action('admin_menu', function () use ($hidden) {
            foreach ($hidden as $k) {
                if ($k === 'm:options-general.php' || strpos($k, MB_SLUG) !== false) continue; // تنظیمات و منوی خود افزونه قابل مخفی‌شدن نیست
                if (strpos($k, 'm:') === 0) remove_menu_page(substr($k, 2));
                elseif (strpos($k, 's:') === 0) {
                    $parts = explode('|', substr($k, 2), 2);
                    if (count($parts) === 2) remove_submenu_page($parts[0], $parts[1]);
                }
            }
        }, 99999);
    }
    if ($bar) {
        add_action('admin_bar_menu', function ($b) use ($bar) { mb_bar_hide($b, $bar); }, 99999);
        add_action('wp_before_admin_bar_render', function () use ($bar) {
            global $wp_admin_bar;
            if ($wp_admin_bar) mb_bar_hide($wp_admin_bar, $bar);
        }, 99999);
    }
}

/** مورد انتخاب‌شده و همه‌ی زیرمجموعه‌هایش را از نوار بالا برمی‌دارد */
function mb_bar_hide($bar, $keys) {
    $nodes = (array) $bar->get_nodes();
    foreach ((array) $keys as $k) {
        if (strpos($k, 'b:') !== 0) continue;
        $id = substr($k, 2);
        $all = [];
        $stack = [$id];
        while ($stack) {
            $cur = array_pop($stack);
            foreach ($nodes as $n) {
                if ((string) $n->parent === (string) $cur && !in_array($n->id, $all, true)) { $all[] = $n->id; $stack[] = $n->id; }
            }
        }
        foreach ($all as $cid) $bar->remove_node($cid);
        $bar->remove_node($id);
    }
}

/** درخت موارد نوار بالا برای نمایش در پنل */
function mb_bar_tree() {
    $nodes = isset($GLOBALS['mb_bar_nodes']) ? (array) $GLOBALS['mb_bar_nodes'] : [];
    $kids = [];
    foreach ($nodes as $id => $n) $kids[$n['p']][] = $id;
    $collect = function ($id) use (&$collect, &$kids) {
        $r = [];
        foreach (isset($kids[$id]) ? $kids[$id] : [] as $c) { $r[] = $c; foreach ($collect($c) as $d) $r[] = $d; }
        return $r;
    };
    $roots = [];
    foreach ($nodes as $id => $n) {
        if (($n['p'] === '' && !in_array($id, ['top-secondary', 'root'], true)) || $n['p'] === 'top-secondary') $roots[] = $id;
    }
    $out = [];
    foreach ($roots as $id) {
        $children = [];
        foreach ($collect($id) as $cid) {
            $title = $nodes[$cid]['t'];
            $lbl = mb_bar_label($cid, $title);
            if ($lbl === $cid && empty($title)) continue; // گره‌ی گروهی بدون عنوان
            $children[] = ['key' => 'b:' . $cid, 'title' => $lbl];
        }
        $out[] = ['key' => 'b:' . $id, 'title' => mb_bar_label($id, $nodes[$id]['t']), 'children' => $children];
    }
    // موارد سمت بازدیدکننده که در پیشخوان دیده نمی‌شوند
    foreach (['customize', 'edit', 'view', 'archive', 'preview', 'search'] as $id) {
        if (!isset($nodes[$id])) $out[] = ['key' => 'b:' . $id, 'title' => mb_bar_label($id, '') . ' (فقط سمت سایت)', 'children' => []];
    }
    return $out;
}
