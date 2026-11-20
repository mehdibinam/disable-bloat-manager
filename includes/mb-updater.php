<?php
if (!defined('ABSPATH')) exit;

/**
 * به‌روزرسانی از گیت‌هاب (Releases).
 * - بررسی نسخه فقط در پس‌زمینه (کران وردپرس یا درخواست ایجکس) و با زمان انتظار کوتاه انجام می‌شود.
 * - نتیجه (حتی شکست) کش می‌شود؛ پس اگر اتصال به گیت‌هاب قطع باشد سایت یا پیشخوان کند نمی‌شود.
 */
function mb_repo_slug() {
    $p = trim((string) wp_parse_url(MB_REPO, PHP_URL_PATH), '/');
    return $p !== '' ? $p : '';
}
function mb_plugin_basename() { return plugin_basename(MB_FILE); }

function mb_update_cached() {
    $d = get_site_transient('mb_update_info');
    return is_array($d) ? $d : null;
}

/** آخرین نسخه را از گیت‌هاب می‌گیرد و کش می‌کند */
function mb_update_fetch($force = false) {
    $c = mb_update_cached();
    if ($c && !$force) return $c;
    $slug = mb_repo_slug();
    $info = ['checked' => time(), 'version' => '', 'package' => '', 'url' => MB_REPO, 'ok' => false, 'notes' => ''];
    if ($slug !== '') {
        $GLOBALS['mb_updating'] = true;
        $r = wp_remote_get('https://api.github.com/repos/' . $slug . '/releases/latest', [
            'timeout' => 5,
            'headers' => ['Accept' => 'application/vnd.github+json', 'User-Agent' => 'MB-Updater'],
        ]);
        unset($GLOBALS['mb_updating']);
        if (!is_wp_error($r) && wp_remote_retrieve_response_code($r) === 200) {
            $j = json_decode(wp_remote_retrieve_body($r), true);
            if (is_array($j) && !empty($j['tag_name'])) {
                $pkg = '';
                foreach ((isset($j['assets']) ? (array) $j['assets'] : []) as $a) {
                    if (isset($a['name'], $a['browser_download_url']) && substr($a['name'], -4) === '.zip') { $pkg = $a['browser_download_url']; break; }
                }
                if ($pkg === '' && !empty($j['zipball_url'])) $pkg = $j['zipball_url'];
                $info = [
                    'checked' => time(), 'version' => ltrim((string) $j['tag_name'], 'vV'), 'package' => $pkg, 'ok' => $pkg !== '',
                    'url' => !empty($j['html_url']) ? $j['html_url'] : MB_REPO,
                    'notes' => mb_substr(wp_strip_all_tags(isset($j['body']) ? (string) $j['body'] : ''), 0, 400),
                ];
            }
        }
    }
    set_site_transient('mb_update_info', $info, $info['ok'] ? 6 * HOUR_IN_SECONDS : HOUR_IN_SECONDS);
    return $info;
}

/** اگر نسخه‌ی جدیدتری در کش باشد، اطلاعاتش را برمی‌گرداند */
function mb_update_available() {
    $i = mb_update_cached();
    return ($i && !empty($i['ok']) && $i['version'] !== '' && version_compare($i['version'], MB_VERSION, '>')) ? $i : null;
}

function mb_update_link() {
    if (!current_user_can('update_plugins')) return '';
    $b = mb_plugin_basename();
    return wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin=' . rawurlencode($b)), 'upgrade-plugin_' . $b);
}

function mb_update_state() {
    $i = mb_update_available();
    $c = mb_update_cached();
    return [
        'available' => (bool) $i,
        'version'   => $i ? $i['version'] : '',
        'url'       => $i ? mb_update_link() : '',
        'notes'     => $i ? $i['notes'] : '',
        'stale'     => !$c || (time() - (int) $c['checked']) > HOUR_IN_SECONDS,
    ];
}

function mb_update_response($i) {
    return (object) [
        'id' => 'github.com/' . mb_repo_slug(), 'slug' => 'disable-bloat-manager', 'plugin' => mb_plugin_basename(),
        'new_version' => $i['version'], 'url' => $i['url'], 'package' => $i['package'],
        'icons' => [], 'banners' => [], 'tested' => get_bloginfo('version'), 'requires_php' => '7.4',
    ];
}

// نمایش نسخه‌ی جدید در فهرست به‌روزرسانی‌های وردپرس (فقط از کش؛ بدون درخواست شبکه)
add_filter('site_transient_update_plugins', function ($t) {
    if (!is_object($t)) return $t;
    $i = mb_update_available();
    if ($i) {
        if (!isset($t->response) || !is_array($t->response)) $t->response = [];
        $t->response[mb_plugin_basename()] = mb_update_response($i);
    }
    return $t;
});

// هر بار که وردپرس خودش به‌روزرسانی‌ها را بررسی می‌کند، کش گیت‌هاب هم تازه می‌شود
add_filter('pre_set_site_transient_update_plugins', function ($t) {
    if (is_object($t) && mb_repo_slug() !== '') mb_update_fetch(true);
    return $t;
});

// بسته‌ی گیت‌هاب پوشه‌ی دیگری دارد (مثلاً user-repo-hash)؛ آن را به disable-bloat-manager برمی‌گردانیم
add_filter('upgrader_source_selection', function ($source, $remote, $upgrader, $extra = []) {
    if (empty($extra['plugin']) || $extra['plugin'] !== mb_plugin_basename()) return $source;
    global $wp_filesystem;
    $main = 'disable-bloat-manager.php';
    $dir = untrailingslashit($source);
    if (!file_exists($dir . '/' . $main)) {
        foreach ((array) glob($dir . '/*', GLOB_ONLYDIR) as $sub) {
            if (file_exists($sub . '/' . $main)) { $dir = $sub; break; }
        }
    }
    if (!file_exists($dir . '/' . $main)) return new WP_Error('mb_bad_package', 'بسته‌ی به‌روزرسانی معتبر نیست.');
    $want = trailingslashit(dirname($dir)) . 'disable-bloat-manager/';
    if (trailingslashit($dir) === $want) return $want;
    if ($wp_filesystem && $wp_filesystem->move($dir, $want, true)) return $want;
    return new WP_Error('mb_move_failed', 'تغییر نام پوشه‌ی به‌روزرسانی انجام نشد.');
}, 10, 4);

// اجازه‌ی اتصال به گیت‌هاب برای خود به‌روزرسانی، حتی در حالت «فهرست سفید» یا محدودیت زمان انتظار
add_filter('upgrader_pre_download', function ($r, $package) {
    if (is_string($package) && mb_repo_slug() !== '' && (strpos($package, mb_repo_slug()) !== false || strpos($package, 'githubusercontent.com') !== false)) $GLOBALS['mb_updating'] = true;
    return $r;
}, 1, 2);

add_action('wp_ajax_mb_update_check', function () {
    mb_ajax_guard();
    mb_update_fetch(true);
    wp_send_json_success(['update' => mb_update_state()]);
});
