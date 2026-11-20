<?php
if (!defined('ABSPATH')) exit;

function mb_admin_hook() { return 'settings_page_' . MB_SLUG; }

add_action('admin_menu', function () {
    add_options_page('مدیریت کارایی وردپرس', 'مدیریت کارایی', 'manage_options', MB_SLUG, 'mb_render_page');
});

// در صفحه‌ی خود افزونه، اعلان‌های بقیه‌ی افزونه‌ها نمایش داده نمی‌شود
add_action('current_screen', function ($screen) {
    if ($screen && $screen->id === mb_admin_hook()) {
        foreach (['admin_notices', 'all_admin_notices', 'user_admin_notices', 'network_admin_notices'] as $h) {
            remove_all_actions($h);
        }
    }
});

add_filter('admin_body_class', function ($c) {
    return (isset($_GET['page']) && $_GET['page'] === MB_SLUG) ? $c . ' mb-page' : $c;
});

add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== mb_admin_hook()) return;
    wp_enqueue_style('mb-admin', MB_URL . 'assets/css/mb-admin.css', [], MB_VERSION);
    wp_enqueue_script('mb-admin', MB_URL . 'assets/js/mb-admin.js', [], MB_VERSION, true);

    $features = [];
    foreach (mb_features() as $f) {
        $f['type'] = isset($f['type']) ? $f['type'] : 'toggle';
        if (!empty($f['options_cb'])) { $f['options'] = mb_option_choices($f); unset($f['options_cb']); }
        if (!empty($f['fields'])) {
            foreach ($f['fields'] as $i => $fld) {
                if (!empty($fld['options_cb'])) { $f['fields'][$i]['options'] = mb_option_choices($fld); unset($f['fields'][$i]['options_cb']); }
            }
        }
        $f['available'] = true;
        if (!empty($f['requires']['plugin'])) {
            $f['available'] = mb_plugin_active($f['requires']['plugin']);
        }
        $features[] = $f;
    }

    wp_localize_script('mb-admin', 'mbData', [
        'ajax'     => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('mb_nonce'),
        'version'  => MB_VERSION,
        'groups'   => mb_groups(),
        'features' => $features,
        'values'   => mb_get_options(),
        'repo'     => MB_REPO,
        'external' => mb_external_conflicts(),
        'learned'  => mb_learned_list(),
        'hiddenNotices' => mb_hidden_notices_list(),
        'update'   => mb_update_state(),
        'site'     => [
            'wp'      => get_bloginfo('version'),
            'php'     => PHP_VERSION,
            'theme'   => wp_get_theme()->get('Name'),
            'plugins' => count((array) get_option('active_plugins', [])),
            'woo'     => mb_plugin_active('woocommerce/woocommerce.php'),
            'elementor' => mb_plugin_active('elementor/elementor.php'),
        ],
    ]);
});

// فهرست منوها بعد از ساخته‌شدن منو و نوار بالا، قبل از اجرای اسکریپت صفحه چاپ می‌شود
add_action('admin_print_footer_scripts', function () {
    if (!isset($_GET['page']) || $_GET['page'] !== MB_SLUG) return;
    $d = [
        'menus' => isset($GLOBALS['mb_menu_snapshot']) ? $GLOBALS['mb_menu_snapshot'] : [],
        'bar'   => mb_bar_tree(),
    ];
    echo '<script>window.mbMenus=' . wp_json_encode($d) . ';</script>';
}, 5);

function mb_render_page() {
    if (!current_user_can('manage_options')) return;
    echo '<div class="wrap mb-wrap"><div id="mb-app" class="mb-app" dir="rtl">';
    echo '<noscript><p style="padding:24px">برای استفاده از این صفحه باید جاوااسکریپت مرورگر فعال باشد.</p></noscript>';
    echo '</div></div>';
}

/* ───── ذخیره و بازنشانی (ایجکس) ───── */
function mb_ajax_guard() {
    if (!current_user_can('manage_options')) wp_send_json_error(['message' => 'دسترسی ندارید.'], 403);
    check_ajax_referer('mb_nonce', 'nonce');
}

add_action('wp_ajax_mb_save', function () {
    mb_ajax_guard();
    $key = isset($_POST['key']) ? sanitize_key(wp_unslash($_POST['key'])) : '';
    $val = isset($_POST['value']) ? wp_unslash($_POST['value']) : '';
    if (!mb_save_option($key, $val)) wp_send_json_error(['message' => 'گزینه‌ی نامعتبر.'], 400);
    wp_send_json_success(['values' => mb_get_options()]);
});

add_action('wp_ajax_mb_recommended', function () {
    mb_ajax_guard();
    mb_apply_recommended();
    wp_send_json_success(['values' => mb_get_options()]);
});

add_action('wp_ajax_mb_reset', function () {
    mb_ajax_guard();
    mb_reset_options();
    wp_send_json_success(['values' => mb_get_options()]);
});

add_action('wp_ajax_mb_bulk', function () {
    mb_ajax_guard();
    $cat = isset($_POST['cat']) ? sanitize_key(wp_unslash($_POST['cat'])) : '';
    $val = empty($_POST['value']) ? 0 : 1;
    $opts = mb_get_options();
    foreach (mb_features() as $f) {
        if ($f['cat'] !== $cat) continue;
        if ((isset($f['type']) ? $f['type'] : 'toggle') !== 'toggle') continue;
        if (isset($f['bulk']) && $f['bulk'] === false) continue;
        if (!empty($f['requires']['plugin']) && !mb_plugin_active($f['requires']['plugin'])) continue;
        $opts[$f['id']] = $val;
    }
    update_option(MB_OPT, $opts);
    wp_send_json_success(['values' => mb_get_options()]);
});

/** اعلان‌هایی که کاربر فعلی مخفی کرده (برای بازگردانی تکی) */
function mb_hidden_notices_list() {
    $h = get_user_meta(get_current_user_id(), 'mb_hidden_notices', true);
    if (!is_array($h)) return [];
    $out = [];
    foreach ($h as $k => $v) {
        $out[] = ['hash' => (string) $k, 'text' => is_array($v) && isset($v['x']) ? (string) $v['x'] : '', 'time' => is_array($v) && isset($v['t']) ? (int) $v['t'] : (int) $v];
    }
    usort($out, function ($a, $b) { return $b['time'] <=> $a['time']; });
    return $out;
}

add_action('wp_ajax_mb_notice_show', function () {
    mb_ajax_guard();
    $uid = get_current_user_id();
    $h = get_user_meta($uid, 'mb_hidden_notices', true);
    if (!is_array($h)) $h = [];
    $hash = isset($_POST['hash']) ? sanitize_key(wp_unslash($_POST['hash'])) : '';
    if ($hash === 'all') $h = []; else unset($h[$hash]);
    update_user_meta($uid, 'mb_hidden_notices', $h);
    wp_send_json_success(['hidden' => mb_hidden_notices_list()]);
});

add_action('wp_ajax_mb_import', function () {
    mb_ajax_guard();
    $raw = isset($_POST['data']) ? wp_unslash($_POST['data']) : '';
    $d = json_decode($raw, true);
    if (!is_array($d) || !isset($d['mb_settings']) || !is_array($d['mb_settings'])) wp_send_json_error(['message' => 'فایل معتبر نیست.'], 400);
    $opts = mb_get_options();
    $n = 0;
    foreach ($d['mb_settings'] as $k => $v) {
        $k = sanitize_key($k);
        $c = mb_sanitize_value($k, $v);
        if ($c === null) continue;
        $opts[$k] = $c;
        $n++;
    }
    update_option(MB_OPT, $opts);
    wp_send_json_success(['values' => mb_get_options(), 'count' => $n]);
});
