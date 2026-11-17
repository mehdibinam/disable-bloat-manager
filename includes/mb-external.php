<?php
if (!defined('ABSPATH')) exit;

/**
 * افزونه‌های شناخته‌شده که ممکن است همان کاری را بکنند که امکان‌های ما می‌کنند.
 * mode=array: همه‌ی تنظیمات در یک گزینه‌ی آرایه‌ای؛ mode=single: هر تنظیم یک گزینه (با prefix)؛ mode=always: فعال‌بودن خود افزونه کافی است.
 */
function mb_external_map() {
    return [
        ['plugin' => ['perfmatters/perfmatters.php'], 'label' => 'Perfmatters', 'mode' => 'array', 'option' => 'perfmatters_options', 'map' => [
            'emojis' => 'disable_emojis', 'dashicons' => 'disable_dashicons', 'embeds' => 'disable_embeds', 'sec_xmlrpc' => 'disable_xmlrpc',
            'rsd' => 'remove_rsd_link', 'jquery_migrate' => 'remove_jquery_migrate', 'generator' => 'hide_wp_version', 'shortlink' => 'remove_shortlink',
            'disable_feeds' => 'disable_rss_feeds', 'feed_links' => 'remove_feed_links', 'self_pingbacks' => 'disable_self_pingbacks',
            'sec_rest_access' => 'disable_rest_api', 'rest_links' => 'remove_rest_api_links', 'google_maps' => 'disable_google_maps',
            'password_meter' => 'disable_password_strength_meter', 'disable_comments' => 'disable_comments', 'comment_urls' => 'remove_comment_urls',
            'favicon_blank' => 'blank_favicon', 'global_styles' => 'remove_global_styles', 'separate_block_styles' => 'separate_block_styles',
            'heartbeat' => 'disable_heartbeat', 'revisions' => 'limit_post_revisions', 'autosave' => 'autosave_interval', 'login_slug' => 'login_url',
            'wc_scripts' => 'disable_woocommerce_scripts', 'wc_cart_fragments' => 'disable_woocommerce_cart_fragmentation',
            'wc_status_widget' => 'disable_woocommerce_status', 'wc_widgets' => 'disable_woocommerce_widgets',
        ]],
        ['plugin' => ['disable-dashboard-for-woocommerce/disable-dashboard-for-woocommerce.php', 'disable-dashboard-for-woocommerce-pro/disable-dashboard-for-woocommerce.php'], 'label' => 'Disable Bloat for WP & Woo', 'mode' => 'single', 'prefix' => 'wcbloat_', 'map' => [
            'emojis' => 'remove_emoji_scripts', 'embeds' => 'disable_wp_embed', 'jquery_migrate' => 'disable_jquery_migrate', 'dashicons' => 'disable_dashicons',
            'dns_prefetch' => 'remove_dns_prefetch', 'generator' => 'wp_meta_generator_disable', 'feed_links' => 'remove_rss_links', 'disable_feeds' => 'disable_all_feeds',
            'feed_generator' => 'remove_feed_generator_tag', 'wlw' => 'disable_wlw_link', 'rsd' => 'disable_rsd_link', 'shortlink' => 'remove_shortlink',
            'remove_ver' => 'remove_script_style_ver', 'password_meter' => 'password_meter_disable', 'comment_scripts' => 'load_comment_scripts_when_needed',
            'comment_autolink' => 'prevent_linking_url_comments', 'update_nag' => 'wp_update_nag_disable', 'autosave_off' => 'autosave_disable',
            'dashboard_widgets' => 'wp_dashboard_widgets_disable', 'admin_bar_logo' => 'w_logo_disable', 'admin_footer_text' => 'wp_footer_disable',
            'login_logo' => 'hide_wp_logo_on_login_page', 'login_logo_url' => 'wp_logo_url_disable', 'login_logo_title' => 'wp_logo_title',
            'login_lang_switcher' => 'wp_language_select_disable', 'disable_gutenberg' => 'disable_gutenberg', 'widget_block_editor' => 'disable_widget_block_editor',
            'welcome_guide' => 'autoclose_welcome_guide', 'block_directory' => 'disable_block_directory', 'default_patterns' => 'disable_default_block_patterns',
            'fullscreen_mode' => 'disable_fullscreen_editor_mode', 'template_editor' => 'disable_template_editor', 'widgets_all' => 'wp_sidebar_widgets_disable',
            'auto_update_themes' => 'themes_auto_update_disable', 'auto_update_plugins' => 'plugins_auto_update_disable', 'core_updates_off' => 'wp_core_update_disable',
            'sec_file_editor' => 'file_editor_disable', 'revisions' => 'post_revisions_disable', 'sec_app_passwords' => 'app_passwords_disable',
            'sec_xmlrpc' => 'xml_rpc_disable', 'heartbeat' => 'wp_heartbeat_disable', 'sec_rest_access' => 'wp_rest_api_disable',
            'wc_admin' => 'admin_disable', 'wc_marketing' => 'marketing_disable', 'wc_helper' => 'wc_helper_disable', 'wc_marketplace' => 'wc_marketplace',
            'wc_addons_submenu' => 'remove_addon_submenu', 'wc_payment_providers' => 'hide_payment_providers', 'wc_guide_emails' => 'woo_merchant_email_notifications',
            'wc_mobile_footer' => 'hide_woo_mobile_footer_text', 'wc_status_widget' => 'wc_status_meta_box_disable', 'wc_setup_widget' => 'disable_admin_dashboard_setup_widget',
            'wc_widgets' => 'wc_widgets_disable', 'wc_scripts' => 'wc_scripts_disable', 'wc_cart_fragments' => 'wc_fragmentation_disable', 'wc_stripe' => 'wc_stripe_scripts_disable',
            'jetpack_promos' => 'jetpack_installation_disable', 'jetpack_blaze' => 'jetpack_blaze_disable', 'elementor_widget' => 'elementor_widget_disable',
            'elementor_google_fonts' => 'elementor_google_fonts', 'skyverge_menu' => 'wc_skyverge_disable', 'yoast_premium' => 'yoast_premium_disable',
            'yoast_admin_bar' => 'yoast_admin_bar_disable', 'yoast_comments' => 'yoast_html_comments_disable', 'yoast_widget' => 'yoast_widget_disable',
            'cf7_assets' => 'cf7_disable', 'updraftplus_bar' => 'updraftplus_menubar_disable', 'acf_menu' => 'acf_hide_menu', 'wpml_meta' => 'wpml_remove_meta',
            'wpdesk_widgets' => 'wpdesk_disable_dashboard_widget', 'flexible_shipping_menu' => 'flexible_shipping_remove_menu',
        ]],
        ['plugin' => ['wp-rocket/wp-rocket.php'], 'label' => 'WP Rocket', 'mode' => 'array', 'option' => 'wp_rocket_settings', 'map' => [
            'emojis' => 'emoji', 'embeds' => 'embeds', 'heartbeat' => 'control_heartbeat', 'fonts_local' => 'host_fonts_locally',
        ]],
        ['plugin' => ['autoptimize/autoptimize.php'], 'label' => 'Autoptimize', 'mode' => 'single', 'prefix' => '', 'map' => ['fonts_remove' => 'autoptimize_googlefont_nogooglefont']],
        ['plugin' => ['disable-comments/disable-comments.php'], 'label' => 'Disable Comments', 'mode' => 'always', 'ids' => ['disable_comments']],
        ['plugin' => ['classic-editor/classic-editor.php'], 'label' => 'Classic Editor', 'mode' => 'always', 'ids' => ['disable_gutenberg']],
        ['plugin' => ['disable-xml-rpc/disable-xml-rpc.php'], 'label' => 'Disable XML-RPC', 'mode' => 'always', 'ids' => ['sec_xmlrpc']],
        ['plugin' => ['disable-emojis/disable-emojis.php'], 'label' => 'Disable Emojis', 'mode' => 'always', 'ids' => ['emojis']],
        ['plugin' => ['heartbeat-control/heartbeat-control.php'], 'label' => 'Heartbeat Control', 'mode' => 'always', 'ids' => ['heartbeat']],
        ['plugin' => ['limit-login-attempts-reloaded/limit-login-attempts-reloaded.php'], 'label' => 'Limit Login Attempts Reloaded', 'mode' => 'always', 'ids' => ['login_limit']],
        ['plugin' => ['wps-hide-login/wps-hide-login.php'], 'label' => 'WPS Hide Login', 'mode' => 'always', 'ids' => ['login_slug']],
        ['plugin' => ['wordfence/wordfence.php'], 'label' => 'Wordfence', 'mode' => 'always', 'ids' => ['fw_signatures', 'fw_scanners', 'fw_rate', 'login_limit', 'sec_xmlrpc']],
        ['plugin' => ['all-in-one-wp-security-and-firewall/wp-security.php'], 'label' => 'All In One WP Security', 'mode' => 'always', 'ids' => ['fw_signatures', 'login_limit', 'login_slug', 'sec_xmlrpc']],
    ];
}

function mb_truthy($v) {
    if (is_array($v)) return !empty($v);
    return !in_array($v, ['', '0', 0, false, null, 'no', 'off', 'false'], true);
}

/** نقشه‌ی «شناسه‌ی امکان ما» => «نام افزونه‌هایی که همان را اجرا می‌کنند» */
function mb_external_conflicts() {
    $out = [];
    foreach (mb_external_map() as $src) {
        $active = false;
        foreach ((array) $src['plugin'] as $file) { if (mb_plugin_active($file)) { $active = true; break; } }
        if (!$active) continue;
        $hit = [];
        if ($src['mode'] === 'always') {
            $hit = $src['ids'];
        } elseif ($src['mode'] === 'array') {
            $opt = get_option($src['option'], []);
            if (is_array($opt)) foreach ($src['map'] as $ours => $key) if (isset($opt[$key]) && mb_truthy($opt[$key])) $hit[] = $ours;
        } else {
            foreach ($src['map'] as $ours => $key) if (mb_truthy(get_option($src['prefix'] . $key, ''))) $hit[] = $ours;
        }
        foreach ($hit as $id) {
            if (!isset($out[$id])) $out[$id] = [];
            if (!in_array($src['label'], $out[$id], true)) $out[$id][] = $src['label'];
        }
    }
    return $out;
}
