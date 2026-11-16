<?php
/**
 * Plugin Name: Bloat Manager
 * Plugin URI: https://t.me/Mehdibinam
 * Description: افزونه جامع غیرفعال‌سازی بلوت وردپرس، ووکامرس و افزونه‌ها — تمام قابلیت‌های 6 افزونه معروف در یک افزونه سبک با رابط perfmatters + تب‌های AJAX و تولتیپ.
 * Version: 1.0.1
 * Author: Mehdi Binam
 * Author URI: https://t.me/Mehdibinam
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Requires Plugins:
 * Network: false
 * License: Proprietary - All Rights Reserved (see LICENSE)
 * License URI: https://github.com/Mehdibinam/disable-bloat-manager/blob/main/LICENSE
 * Text Domain: bloat-manager
 * Domain Path: /languages
 */
if (!defined('ABSPATH')) exit;

define('BLOAT_MANAGER_VERSION', '1.0.1');
define('BLOAT_MANAGER_FILE', __FILE__);
define('BLOAT_MANAGER_DIR', plugin_dir_path(__FILE__));
define('BLOAT_MANAGER_URL', plugin_dir_url(__FILE__));
define('BLOAT_MANAGER_OPT', 'bloat_manager_options');

// defaults
function bloat_manager_defaults() {
    return [
        // frontend
        'emojis' => 0,
        'dashicons' => 0,
        'embeds' => 0,
        'jquery_migrate' => 0,
        'remove_ver' => 0,
        'block_library' => 0,
        'global_styles' => 0,
        'dns_prefetch' => 0,
        'xml_sitemap' => 0,
        'shortlink' => 0,
        'rsd' => 0,
        'wlw' => 0,
        'generator' => 0,
        'feed_links' => 0,
        'disable_feeds' => 0,
        'adjacent_posts' => 0,
        'self_pingbacks' => 0,
        'capital_p' => 0,
        'favicon_blank' => 0,
        'password_meter' => 0,
        'comment_urls' => 0,
        'comment_scripts' => 0,
        'disable_comments' => 0,
        'pdf_thumbnails' => 0,
        'view_transitions' => 0,
        'command_palette' => 0,
        'gutenberg_assets' => 0,
        // admin
        'dashboard_widgets' => 0,
        'help_tabs' => 0,
        'screen_options' => 0,
        'admin_bar_logo' => 0,
        'admin_footer_text' => 0,
        'site_health' => 0,
        'import_export' => 0,
        'update_nag' => 0,
        'heartbeat_disable' => 0,
        'heartbeat_frequency' => 15,
        'revisions_limit' => 0,
        'revisions_num' => 5,
        'autosave_interval' => 0,
        'autosave_seconds' => 300,
        'app_passwords' => 0,
        'file_editor' => 0,
        'login_logo' => 0,
        'login_logo_url' => 0,
        'login_logo_title' => 0,
        'login_lang_switcher' => 0,
        'try_gutenberg_nag' => 0,
        'admin_email_check' => 0,
        'privacy_nag' => 0,
        'browser_nag' => 0,
        // woocommerce
        'wc_admin' => 0,
        'wc_marketing' => 0,
        'wc_marketplace' => 0,
        'wc_addons_submenu' => 0,
        'wc_status_widget' => 0,
        'wc_widgets' => 0,
        'wc_blocks_frontend' => 0,
        'wc_blocks_backend' => 0,
        'wc_scripts' => 0,
        'wc_cart_fragments' => 0,
        'wc_stripe' => 0,
        'wc_mobile_footer' => 0,
        'wc_payment_providers' => 0,
        'wc_skyverge' => 0,
        'wc_recommended_shipping' => 0,
        // block editor
        'disable_gutenberg' => 0,
        'widget_block_editor' => 0,
        'welcome_guide' => 0,
        'block_directory' => 0,
        'default_patterns' => 0,
        'fullscreen_mode' => 0,
        'template_editor' => 0,
        'remote_patterns' => 0,
        // third party
        'jetpack_nag' => 0,
        'jetpack_promo' => 0,
        'jetpack_blaze' => 0,
        'elementor_widget' => 0,
        'elementor_google_fonts' => 0,
        'yoast_premium' => 0,
        'yoast_admin_bar' => 0,
        'yoast_comments' => 0,
        'yoast_widget' => 0,
        'cf7_assets' => 0,
        'acf_menu' => 0,
        'wpml_meta' => 0,
        'updraftplus_bar' => 0,
        // ai
        'wp_ai' => 0,
        'elementor_ai' => 0,
        'rankmath_ai' => 0,
        'yoast_ai' => 0,
        'jetpack_ai' => 0,
        'bertha_ai' => 0,
        // privacy / security
        'xmlrpc' => 0,
        'rest_api' => 0, // 0 off, 1 disable for guests
        'rest_api_links' => 0,
        'user_enumeration' => 0,
        'author_archives' => 0,
        'unregister_tags' => 0,
        'force_gd' => 0,
        'core_privacy' => 0,
        'auto_update_themes' => 0,
        'auto_update_plugins' => 0,
        'auto_update_core' => 0,
        'empty_trash' => 0,
        // extra
        'plugin_suggestions' => 0,
        'plugin_popular_tags' => 0,
        'sidebar_widgets' => [],
        'admin_bar_items' => [],
        'admin_notices_mode' => 'not_hide', // not_hide / only_selected / all / compact_panel
    ];
}

function bloat_manager_get_options() {
    $defaults = bloat_manager_defaults();
    $opts = get_option(BLOAT_MANAGER_OPT, []);
    if (!is_array($opts)) $opts = [];
    return array_merge($defaults, $opts);
}

require_once BLOAT_MANAGER_DIR . 'includes/functions.php';
require_once BLOAT_MANAGER_DIR . 'includes/settings.php';

add_action('plugins_loaded', function () {
    load_plugin_textdomain('bloat-manager', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

register_activation_hook(__FILE__, function () {
    $existing = get_option(BLOAT_MANAGER_OPT, null);
    if ($existing === null) {
        add_option(BLOAT_MANAGER_OPT, []);
    }
});
