<?php
if (!defined('ABSPATH')) exit;

function mb_admin_hook() { return 'toplevel_page_' . MB_SLUG; }

add_action('admin_menu', function () {
    add_menu_page('Disable Bloat Manager', 'Disable Bloat', 'manage_options', MB_SLUG, 'mb_render_page', 'dashicons-performance', 81);
});

// در صفحه‌ی خود افزونه، اعلان‌های بقیه‌ی افزونه‌ها نمایش داده نمی‌شود
add_action('current_screen', function ($screen) {
    if ($screen && $screen->id === mb_admin_hook()) {
        foreach (['admin_notices', 'all_admin_notices', 'user_admin_notices', 'network_admin_notices'] as $h) {
            remove_all_actions($h);
        }
    }
});

add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== mb_admin_hook()) return;
    wp_enqueue_style('mb-admin', MB_URL . 'assets/css/mb-admin.css', [], MB_VERSION);
    wp_enqueue_script('mb-admin', MB_URL . 'assets/js/mb-admin.js', [], MB_VERSION, true);

    $features = [];
    foreach (mb_features() as $f) {
        $f['type'] = isset($f['type']) ? $f['type'] : 'toggle';
        if (!empty($f['options_cb'])) { $f['options'] = mb_option_choices($f); unset($f['options_cb']); }
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
        'bar'   => isset($GLOBALS['mb_bar_snapshot']) ? $GLOBALS['mb_bar_snapshot'] : [],
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
