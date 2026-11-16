<?php
if (!defined('ABSPATH')) exit;

function bloat_manager_apply(){ $opts = bloat_manager_get_options();

// ============ Helpers ============
function dbloat_active($key) {
    $o = bloat_manager_get_options();
    return !empty($o[$key]);
}

// ============ 1. FRONTEND ============
if (!empty($opts['emojis'])) {
    add_action('init', function () {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_action('wp_enqueue_scripts', 'wp_enqueue_emoji_styles');
        remove_action('embed_head', 'print_emoji_detection_script');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        add_filter('tiny_mce_plugins', function ($p) { return is_array($p) ? array_diff($p, ['wpemoji']) : []; });
        add_filter('emoji_svg_url', function ($u) { return is_admin() ? $u : false; });
        add_filter('wp_resource_hints', function ($urls, $rel) {
            if ('dns-prefetch' === $rel) {
                $emoji = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
                $urls = array_diff($urls, [$emoji]);
            }
            return $urls;
        }, 10, 2);
    });
}
if (!empty($opts['dashicons'])) {
    add_action('wp_enqueue_scripts', function () {
        if (!is_admin() && !is_user_logged_in()) {
            wp_dequeue_style('dashicons');
            wp_deregister_style('dashicons');
        }
    });
}
if (!empty($opts['embeds'])) {
    add_action('init', function () {
        global $wp;
        if (isset($wp->public_query_vars)) $wp->public_query_vars = array_diff($wp->public_query_vars, ['embed']);
        add_filter('embed_oembed_discover', '__return_false');
        remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
        remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result', 10);
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_discovery_links', 4);
        remove_action('wp_head', 'wp_oembed_add_host_js');
        remove_action('embed_head', 'enqueue_embed_scripts');
        add_filter('tiny_mce_plugins', function ($p) { return is_array($p) ? array_diff($p, ['wpembed']) : []; });
        add_filter('rewrite_rules_array', function ($rules) {
            foreach ($rules as $k => $v) if (strpos($v, 'embed=true') !== false) unset($rules[$k]);
            return $rules;
        });
        wp_deregister_script('wp-embed');
    }, 9999);
}
if (!empty($opts['jquery_migrate'])) {
    add_action('wp_default_scripts', function ($scripts) {
        if (!is_admin() && isset($scripts->registered['jquery'])) {
            $s = $scripts->registered['jquery'];
            if (!empty($s->deps)) $s->deps = array_diff($s->deps, ['jquery-migrate']);
        }
    });
}
if (!empty($opts['remove_ver'])) {
    add_filter('style_loader_src', function ($src) { return $src ? remove_query_arg('ver', $src) : $src; }, 9999);
    add_filter('script_loader_src', function ($src) { return $src ? remove_query_arg('ver', $src) : $src; }, 9999);
}
if (!empty($opts['block_library'])) {
    add_action('wp_enqueue_scripts', function () {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-blocks-style');
    }, 100);
}
if (!empty($opts['global_styles'])) {
    add_action('after_setup_theme', function () {
        remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
        remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
    });
}
if (!empty($opts['dns_prefetch'])) {
    add_action('init', function () { remove_action('wp_head', 'wp_resource_hints', 2); }, 99);
}
if (!empty($opts['xml_sitemap'])) {
    add_filter('wp_sitemaps_enabled', '__return_false');
}
if (!empty($opts['shortlink'])) {
    remove_action('wp_head', 'wp_shortlink_wp_head', 10);
    remove_action('template_redirect', 'wp_shortlink_header', 11);
}
if (!empty($opts['rsd'])) remove_action('wp_head', 'rsd_link');
if (!empty($opts['wlw'])) remove_action('wp_head', 'wlwmanifest_link');
if (!empty($opts['generator'])) {
    remove_action('wp_head', 'wp_generator');
    add_filter('the_generator', '__return_empty_string');
}
if (!empty($opts['feed_links'])) {
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
}
if (!empty($opts['disable_feeds'])) {
    add_action('do_feed', function () { wp_die('فیدها غیرفعال است - لطفا به صفحه اصلی بروید.', '', ['response' => 410]); }, 1);
    add_action('do_feed_rdf', function () { wp_die('فیدها غیرفعال است.', '', ['response' => 410]); }, 1);
    add_action('do_feed_rss', function () { wp_die('فیدها غیرفعال است.', '', ['response' => 410]); }, 1);
    add_action('do_feed_rss2', function () { wp_die('فیدها غیرفعال است.', '', ['response' => 410]); }, 1);
    add_action('do_feed_atom', function () { wp_die('فیدها غیرفعال است.', '', ['response' => 410]); }, 1);
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
}
if (!empty($opts['adjacent_posts'])) {
    remove_action('wp_head', 'adjacent_posts_rel_link', 10);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
}
if (!empty($opts['self_pingbacks'])) {
    add_action('pre_ping', function (&$links) {
        $home = get_option('home');
        foreach ($links as $k => $v) if (strpos($v, $home) === 0) unset($links[$k]);
    });
}
if (!empty($opts['capital_p'])) {
    foreach (['the_content', 'the_title', 'wp_title', 'comment_text'] as $f) {
        $p = has_filter($f, 'capital_P_dangit');
        if ($p !== false) remove_filter($f, 'capital_P_dangit', $p);
    }
}
if (!empty($opts['favicon_blank'])) {
    add_action('wp_head', function () {
        echo '<link rel="icon" href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQEAYAAABPYyMiAAAABmJLR0T///////8JWPfcAAAACXBIWXMAAABIAAAASABGyWs+AAAAF0lEQVRIx2NgGAWjYBSMglEwCkbBSAcACBAAAeaR9cIAAAAASUVORK5CYII=" />' . "\n";
    });
}
if (!empty($opts['password_meter'])) {
    add_action('wp_enqueue_scripts', function () { wp_dequeue_script('zxcvbn-async'); wp_deregister_script('zxcvbn-async'); }, 100);
    add_action('admin_enqueue_scripts', function () { wp_dequeue_script('zxcvbn-async'); }, 100);
}
if (!empty($opts['comment_urls'])) {
    add_filter('get_comment_author_link', function ($link) { return preg_replace('/<a[^>]*>(.*)<\/a>/', '$1', $link); });
}
if (!empty($opts['comment_scripts'])) {
    add_action('wp_enqueue_scripts', function () {
        if (is_singular() && comments_open() && get_option('thread_comments')) return;
        wp_dequeue_script('comment-reply');
    }, 100);
}
if (!empty($opts['disable_comments'])) {
    add_action('admin_init', function () {
        foreach (get_post_types() as $pt) {
            if (post_type_supports($pt, 'comments')) { remove_post_type_support($pt, 'comments'); remove_post_type_support($pt, 'trackbacks'); }
        }
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    });
    add_filter('comments_open', '__return_false', 20, 2);
    add_filter('pings_open', '__return_false', 20, 2);
    add_filter('comments_array', '__return_empty_array', 10, 2);
    add_action('admin_menu', function () { remove_menu_page('edit-comments.php'); remove_submenu_page('options-general.php', 'options-discussion.php'); });
    add_action('wp_before_admin_bar_render', function () { global $wp_admin_bar; if ($wp_admin_bar) $wp_admin_bar->remove_menu('comments'); }, 99);
    add_action('init', function () { if (is_admin_bar_showing()) remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60); });
}
if (!empty($opts['pdf_thumbnails'])) {
    add_filter('fallback_intermediate_image_sizes', '__return_empty_array');
}
if (!empty($opts['view_transitions'])) {
    add_action('admin_enqueue_scripts', function () { wp_dequeue_style('wp-view-transitions-admin'); wp_deregister_style('wp-view-transitions-admin'); });
}
if (!empty($opts['command_palette'])) {
    add_action('admin_enqueue_scripts', function () { wp_dequeue_script('wp-core-commands'); wp_deregister_script('wp-core-commands'); });
    add_action('admin_bar_menu', function ($bar) { $bar->remove_node('command-palette'); }, 999);
    add_action('enqueue_block_editor_assets', function () {
        wp_add_inline_style('wp-components', '.editor-header__center .editor-document-bar__shortcut{display:none!important}');
    });
}
if (!empty($opts['gutenberg_assets'])) {
    add_action('wp_enqueue_scripts', function () { wp_dequeue_style('wp-block-library'); wp_dequeue_style('wp-block-library-theme'); }, 100);
}

// ============ 2. ADMIN ============
if (!empty($opts['dashboard_widgets'])) {
    add_action('wp_dashboard_setup', function () {
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
        remove_meta_box('dashboard_primary', 'dashboard', 'side');
        remove_meta_box('dashboard_secondary', 'dashboard', 'side');
        remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
        remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
        remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
        remove_meta_box('dashboard_php_nag', 'dashboard', 'normal');
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        remove_meta_box('woocommerce_dashboard_status', 'dashboard', 'normal');
        remove_meta_box('wc_admin_dashboard_setup', 'dashboard', 'normal');
        remove_action('welcome_panel', 'wp_welcome_panel');
    });
}
if (!empty($opts['help_tabs'])) {
    add_action('admin_head', function () { $s = get_current_screen(); if ($s) $s->remove_help_tabs(); });
}
if (!empty($opts['screen_options'])) {
    add_filter('screen_options_show_screen', '__return_false');
}
if (!empty($opts['admin_bar_logo'])) {
    add_action('wp_before_admin_bar_render', function () { global $wp_admin_bar; if ($wp_admin_bar) $wp_admin_bar->remove_menu('wp-logo'); }, 0);
}
if (!empty($opts['admin_footer_text'])) {
    add_filter('admin_footer_text', '__return_empty_string', 99);
}
if (!empty($opts['site_health'])) {
    add_filter('site_status_tests', function () { return ['direct' => [], 'async' => []]; });
    add_action('wp_loaded', function () { remove_action('wp_site_health_scheduled_check', 'wp_site_health_scheduled_check'); wp_clear_scheduled_hook('wp_site_health_scheduled_check'); });
    add_filter('rest_endpoints', function ($e) { foreach ($e as $r => $v) if (strpos($r, '/wp-site-health/') === 0) unset($e[$r]); return $e; });
    add_filter('wp_fatal_error_handler_enabled', '__return_false');
    add_action('admin_menu', function () { remove_submenu_page('tools.php', 'site-health.php'); }, 1);
    add_filter('user_has_cap', function ($caps) { if (isset($caps['view_site_health_checks'])) $caps['view_site_health_checks'] = false; return $caps; }, 999, 1);
}
if (!empty($opts['import_export'])) {
    add_action('admin_menu', function () { remove_submenu_page('tools.php', 'export.php'); remove_submenu_page('tools.php', 'import.php'); }, 1);
    add_action('admin_init', function () { global $pagenow; if ($pagenow === 'export.php' || $pagenow === 'import.php') wp_die('این قابلیت غیرفعال است.', 'غیرفعال', ['response' => 403]); }, 1);
}
if (!empty($opts['update_nag'])) {
    add_action('admin_head', function () { if (!current_user_can('update_core')) remove_action('admin_notices', 'update_nag', 3); });
}
if (!empty($opts['heartbeat_disable'])) {
    add_action('init', function () { wp_deregister_script('heartbeat'); }, 1);
} elseif (!empty($opts['heartbeat_frequency']) && intval($opts['heartbeat_frequency']) !== 15) {
    add_filter('heartbeat_settings', function ($s) {
        $o = bloat_manager_get_options();
        $v = intval($o['heartbeat_frequency']);
        if ($v < 5) $v = 5;
        if ($v > 86400) $v = 86400;
        $s['interval'] = $v;
        return $s;
    });
}
if (!empty($opts['revisions_limit'])) {
    add_filter('wp_revisions_to_keep', function ($num, $post) {
        $o = bloat_manager_get_options();
        $v = intval($o['revisions_num']);
        if ($v < 0) $v = 0;
        if ($v > 99) $v = 99;
        return $v;
    }, 10, 2);
}
if (!empty($opts['autosave_interval'])) {
    add_action('admin_init', function () {
        $o = bloat_manager_get_options();
        $v = intval($o['autosave_seconds']);
        if ($v < 30) $v = 30;
        echo '<script>if(typeof wp!=="undefined"&&wp.autosave)wp.autosave.interval=' . ($v) . ';</script>';
    });
    if (!defined('AUTOSAVE_INTERVAL')) define('AUTOSAVE_INTERVAL', intval($opts['autosave_seconds']));
}
if (!empty($opts['app_passwords'])) add_filter('wp_is_application_passwords_available', '__return_false');
if (!empty($opts['file_editor']) && !defined('DISALLOW_FILE_EDIT')) define('DISALLOW_FILE_EDIT', true);
if (!empty($opts['login_logo'])) {
    add_action('login_enqueue_scripts', function () { echo '<style>#login h1 a{display:none!important}</style>'; });
}
if (!empty($opts['login_logo_url'])) add_filter('login_headerurl', function () { return home_url('/'); });
if (!empty($opts['login_logo_title'])) add_filter('login_headertext', function () { return get_bloginfo('name'); });
if (!empty($opts['login_lang_switcher'])) add_filter('login_display_language_dropdown', '__return_false');
if (!empty($opts['try_gutenberg_nag'])) add_filter('try_gutenberg_panel', '__return_false');
if (!empty($opts['admin_email_check'])) add_filter('admin_email_check_interval', '__return_zero');
if (!empty($opts['privacy_nag'])) {
    remove_action('admin_init', 'wp_schedule_delete_old_privacy_export_files');
    remove_action('wp_privacy_delete_old_export_files', 'wp_privacy_delete_old_export_files');
    add_filter('pre_option_wp_page_for_privacy_policy', '__return_zero');
}
if (!empty($opts['browser_nag'])) add_filter('dashboard_browser_nag_class', '__return_empty_string');

// plugin suggestions / popular tags on add plugins screen
if (!empty($opts['plugin_suggestions'])) {
    add_filter('plugins_api_results', function ($res, $action, $args) {
        if (!empty($res->info['page']) && $res->info['page'] === 1 && empty($args->search)) { $res->plugins = []; $res->info['results'] = 0; }
        return $res;
    }, 10, 3);
    add_filter('install_plugins_tabs', function ($tabs) { unset($tabs['featured'], $tabs['popular'], $tabs['recommended'], $tabs['favorites']); return $tabs; });
}
if (!empty($opts['plugin_popular_tags'])) {
    add_action('install_plugins_pre_plugin-information', function () {
        add_filter('install_plugins_tabs', function ($t) { return $t; });
    });
    // hide popular tags box via css
    add_action('admin_head', function () {
        $screen = get_current_screen();
        if ($screen && $screen->id === 'plugin-install') echo '<style>.popular-tags,.wp-filter{display:none!important}</style>';
    });
}

// admin bar intelligent items
if (!empty($opts['admin_bar_items']) && is_array($opts['admin_bar_items'])) {
    $hidden = $opts['admin_bar_items'];
    add_action('wp_before_admin_bar_render', function () use ($hidden) {
        global $wp_admin_bar;
        if (!$wp_admin_bar) return;
        foreach ($hidden as $id => $v) if ($v) $wp_admin_bar->remove_menu($id);
    }, 999);
    // also remove parent nodes detection cleanup: store available items
    add_action('wp_before_admin_bar_render', function () {
        global $wp_admin_bar;
        if (!$wp_admin_bar || !is_admin()) return;
        $nodes = [];
        foreach ($wp_admin_bar->get_nodes() as $n) if (empty($n->parent) && !empty($n->title)) $nodes[$n->id] = strip_tags($n->title);
        // we don't save here, just for JS; actual save happens via settings page scan
    }, 1);
}

// sidebar widgets selective disable
if (!empty($opts['sidebar_widgets']) && is_array($opts['sidebar_widgets'])) {
    $disabled = $opts['sidebar_widgets'];
    add_action('widgets_init', function () use ($disabled) {
        foreach ($disabled as $cls => $v) if ($v) @unregister_widget($cls);
    }, 100);
}

// ============ 3. WOOCOMMERCE ============
if (function_exists('WC') || class_exists('WooCommerce')) {
    if (!empty($opts['wc_admin'])) {
        add_filter('woocommerce_admin_disabled', '__return_true');
        add_filter('woocommerce_admin_features', function ($f) { return []; });
    }
    if (!empty($opts['wc_marketing'])) {
        add_filter('woocommerce_marketing_menu_items', '__return_empty_array');
        add_action('admin_menu', function () { remove_submenu_page('woocommerce', 'wc-admin&path=/marketing'); }, 99);
    }
    if (!empty($opts['wc_marketplace'])) add_filter('woocommerce_allow_marketplace_suggestions', '__return_false', 999);
    if (!empty($opts['wc_addons_submenu'])) {
        add_action('admin_menu', function () {
            remove_submenu_page('woocommerce', 'wc-addons');
            remove_submenu_page('woocommerce', 'wc-admin&path=/extensions');
        }, 999);
    }
    if (!empty($opts['wc_status_widget'])) add_action('wp_dashboard_setup', function () { remove_meta_box('woocommerce_dashboard_status', 'dashboard', 'normal'); });
    if (!empty($opts['wc_widgets'])) {
        add_action('widgets_init', function () {
            $ws = ['WC_Widget_Products','WC_Widget_Product_Categories','WC_Widget_Product_Tag_Cloud','WC_Widget_Cart','WC_Widget_Layered_Nav','WC_Widget_Layered_Nav_Filters','WC_Widget_Price_Filter','WC_Widget_Product_Search','WC_Widget_Recently_Viewed','WC_Widget_Recent_Reviews','WC_Widget_Top_Rated_Products','WC_Widget_Rating_Filter'];
            foreach ($ws as $w) @unregister_widget($w);
        }, 99);
    }
    if (!empty($opts['wc_blocks_frontend'])) {
        add_action('wp_enqueue_scripts', function () {
            $styles = ['wc-blocks-vendors-style','wc-all-blocks-style','wc-blocks-style','wc-block-editor','wc-blocks-packages-style','wc-blocks-style-all-products','wc-blocks-style-cart','wc-blocks-style-checkout'];
            foreach ($styles as $s) wp_deregister_style($s);
        }, 999);
    }
    if (!empty($opts['wc_blocks_backend'])) add_action('wp_enqueue_scripts', function () { wp_deregister_style('wc-block-editor'); });
    if (!empty($opts['wc_scripts'])) {
        add_action('wp_enqueue_scripts', function () {
            if (function_exists('is_woocommerce') && !is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page()) {
                wp_dequeue_style('woocommerce-general'); wp_dequeue_style('woocommerce-layout'); wp_dequeue_script('wc-cart-fragments');
            }
        }, 99);
    }
    if (!empty($opts['wc_cart_fragments'])) add_action('wp_enqueue_scripts', function () { wp_dequeue_script('wc-cart-fragments'); }, 99);
    if (!empty($opts['wc_stripe'])) add_filter('wc_stripe_hide_payment_request_on_product_page', '__return_true');
    if (!empty($opts['wc_mobile_footer'])) {
        add_action('woocommerce_email', function ($mailer) { remove_action('woocommerce_email_footer', [$mailer->emails['WC_Email_New_Order'], 'mobile_messaging'], 9); });
    }
    if (!empty($opts['wc_payment_providers'])) {
        add_action('admin_head', function () { echo '<script>jQuery(function($){var el=document.querySelector("#settings-other-payment-methods"); if(el) el.closest("tr").style.display="none";});</script>'; });
    }
    if (!empty($opts['wc_skyverge'])) add_action('admin_menu', function () { remove_menu_page('skyverge'); }, 99);
    if (!empty($opts['wc_recommended_shipping'])) add_action('admin_head', function () { echo '<style>#woocommerce-recommended-shipping{display:none!important}</style>'; });
}

// ============ 4. BLOCK EDITOR ============
if (!empty($opts['disable_gutenberg'])) {
    add_filter('use_block_editor_for_post', '__return_false', 10);
    add_filter('use_block_editor_for_post_type', '__return_false', 10);
}
if (!empty($opts['widget_block_editor'])) {
    remove_theme_support('widgets-block-editor');
}
if (!empty($opts['welcome_guide'])) {
    add_action('admin_init', function () {
        // disable welcome guide via user meta filter
        add_filter('get_user_metadata', function ($val, $uid, $key) { if ($key === 'wp_persisted_preferences') return [json_encode(['core/edit-post' => ['welcomeGuide' => false]] )]; return $val; }, 10, 3);
    });
}
if (!empty($opts['block_directory'])) {
    remove_action('enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets');
}
if (!empty($opts['default_patterns'])) {
    remove_theme_support('core-block-patterns');
}
if (!empty($opts['fullscreen_mode'])) {
    add_action('enqueue_block_editor_assets', function () { wp_add_inline_script('wp-edit-post', 'window._wpLoadBlockEditor&&wp.data.dispatch("core/edit-post").toggleFeature("fullscreenMode",false);'); });
}
if (!empty($opts['template_editor'])) {
    remove_theme_support('block-templates');
}
if (!empty($opts['remote_patterns'])) add_filter('should_load_remote_block_patterns', '__return_false');

// ============ 5. THIRD PARTY ============
if (!empty($opts['jetpack_nag'])) {
    add_filter('jetpack_show_promotions', '__return_false');
    add_action('admin_init', function () { remove_action('admin_notices', 'jetpack_display_warning'); });
}
if (!empty($opts['jetpack_promo'])) add_filter('jetpack_just_in_time_msgs', '__return_false');
if (!empty($opts['jetpack_blaze'])) add_filter('jetpack_blaze_enabled', '__return_false');
if (!empty($opts['elementor_widget'])) add_action('wp_dashboard_setup', function () { remove_meta_box('e-dashboard-overview', 'dashboard', 'normal'); }, 40);
if (!empty($opts['elementor_google_fonts'])) add_filter('elementor/frontend/print_google_fonts', '__return_false');
if (!empty($opts['yoast_premium'])) add_filter('wpseo_show_premium_upgrade_admin_notice', '__return_false');
if (!empty($opts['yoast_admin_bar'])) add_filter('wpseo_enable_admin_bar_menu', '__return_false');
if (!empty($opts['yoast_comments'])) add_action('template_redirect', function () { if (class_exists('WPSEO_Frontend')) remove_action('wp_head', [WPSEO_Frontend::get_instance(), 'debug_mark'], 2); }, 99);
if (!empty($opts['yoast_widget'])) add_action('wp_dashboard_setup', function () { remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'normal'); });
if (!empty($opts['cf7_assets'])) { add_filter('wpcf7_load_js', '__return_false'); add_filter('wpcf7_load_css', '__return_false'); }
if (!empty($opts['acf_menu'])) add_filter('acf/settings/show_admin', '__return_false');
if (!empty($opts['wpml_meta'])) add_action('wp_head', function () { remove_action('wp_head', 'wpml_generate_meta'); }, 1);
if (!empty($opts['updraftplus_bar']) && !defined('UPDRAFTPLUS_ADMINBAR_DISABLE')) define('UPDRAFTPLUS_ADMINBAR_DISABLE', true);

// ============ 6. AI ============
if (!empty($opts['wp_ai'])) add_filter('wp_supports_ai', '__return_false', 999);
if (!empty($opts['elementor_ai'])) {
    add_filter('elementor/ai/is_enabled', '__return_false');
    add_filter('elementor/experiments/feature-flags', function ($f) { if (isset($f['ai'])) $f['ai'] = false; return $f; });
}
if (!empty($opts['rankmath_ai'])) {
    add_filter('rank_math/ai/enabled', '__return_false');
    add_filter('rank_math/content_ai/enabled', '__return_false');
}
if (!empty($opts['yoast_ai'])) {
    add_filter('Yoast\WP\SEO\AI_Generate\enabled', '__return_false');
    add_filter('wpseo_ai_generator_enabled', '__return_false');
}
if (!empty($opts['jetpack_ai'])) add_filter('jetpack_ai_enabled', '__return_false');
if (!empty($opts['bertha_ai'])) add_filter('bertha_ai_enabled', '__return_false');

// ============ 7. PRIVACY / SECURITY ============
if (!empty($opts['xmlrpc'])) {
    add_filter('xmlrpc_enabled', '__return_false');
    add_filter('pre_option_enable_xmlrpc', '__return_zero');
    add_filter('pings_open', '__return_false', 9999);
    add_filter('wp_headers', function ($h) { unset($h['X-Pingback'], $h['x-pingback']); return $h; });
    add_action('init', function () { if (basename($_SERVER['SCRIPT_FILENAME'] ?? '') === 'xmlrpc.php') { status_header(403); exit; } });
}
if (!empty($opts['rest_api'])) {
    add_filter('rest_authentication_errors', function ($res) {
        if (!empty($res)) return $res;
        if (is_user_logged_in()) return $res;
        return new WP_Error('rest_disabled', 'REST API غیرفعال است.', ['status' => 401]);
    }, 99);
}
if (!empty($opts['rest_api_links'])) {
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('template_redirect', 'rest_output_link_header', 11);
    remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
}
if (!empty($opts['user_enumeration'])) {
    add_action('init', function () {
        if (!is_admin() && isset($_SERVER['QUERY_STRING']) && preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])) die();
    });
    add_filter('redirect_canonical', function ($r, $req) { if (preg_match('/\?author=([0-9]*)/i', $req)) die(); return $r; }, 10, 2);
}
if (!empty($opts['author_archives'])) {
    add_action('template_redirect', function () { if (is_author()) { wp_redirect(home_url('/'), 301); exit; } }, 1);
    add_filter('wp_sitemaps_post_type_archive', function ($p) { return $p; });
    add_filter('wp_sitemaps_register_providers', function ($pr) { unset($pr['users']); return $pr; });
}
if (!empty($opts['unregister_tags'])) add_action('init', function () { unregister_taxonomy_for_object_type('post_tag', 'post'); });
if (!empty($opts['force_gd'])) add_filter('wp_image_editors', function ($e) { $gd = 'WP_Image_Editor_GD'; $e = array_diff($e, [$gd]); array_unshift($e, $gd); return $e; });
if (!empty($opts['core_privacy'])) {
    add_filter('map_meta_cap', function ($caps, $cap) { if (in_array($cap, ['manage_privacy_options', 'export_others_personal_data', 'erase_others_personal_data'])) $caps[] = 'do_not_allow'; return $caps; }, 10, 2);
}
if (!empty($opts['auto_update_themes'])) add_filter('auto_update_theme', '__return_false');
if (!empty($opts['auto_update_plugins'])) add_filter('auto_update_plugin', '__return_false');
if (!empty($opts['auto_update_core'])) {
    add_filter('allow_minor_auto_core_updates', '__return_false');
    add_filter('allow_major_auto_core_updates', '__return_false');
    add_filter('auto_update_core', '__return_false');
}
if (!empty($opts['empty_trash']) && !defined('EMPTY_TRASH_DAYS')) define('EMPTY_TRASH_DAYS', 7);

// admin notices - per-notice hide like Disable Admin Notices
$mode = $opts['admin_notices_mode'] ?? 'not_hide';
if($mode==='all'){
    add_action('admin_head', function(){ echo '<style>.notice:not(.bm-keep),.updated:not(.bm-keep),.error:not(.bm-keep){display:none!important}</style>'; });
} elseif($mode==='only_selected' || $mode==='compact_panel'){
    add_action('admin_footer', function(){
        $hidden = get_user_meta(get_current_user_id(),'bloat_manager_hidden_notices',true);
        if(!is_array($hidden)) $hidden=[];
        $hidden_json = wp_json_encode(array_keys($hidden));
        echo '<script>window.BM_HIDDEN='. $hidden_json .';</script>';
        echo '<style>.bm-hide-btn{font-size:11px;padding:2px 8px;border-radius:6px;border:1px solid #d5d9e3;background:#fff;cursor:pointer;margin-right:6px}.bm-hide-btn:hover{border-color:#d63638;color:#d63638}</style>';
        // inject buttons via JS
        echo "<script>document.addEventListener('DOMContentLoaded',function(){
            document.querySelectorAll('.notice,.updated,.error').forEach(function(el,i){
                if(el.classList.contains('bm-keep')||el.closest('#bm-form')) return;
                var hash='n'+(el.textContent||'').length+'_'+i;
                if(window.BM_HIDDEN && window.BM_HIDDEN.includes(hash)){ el.style.display='none'; return; }
                var bar=document.createElement('div'); bar.style.marginTop='6px';
                var b1=document.createElement('button'); b1.type='button'; b1.className='bm-hide-btn'; b1.textContent='مخفی'; b1.onclick=function(){ el.style.display='none'; fetch(ajaxurl,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'action=bm_hide_notice&hash='+encodeURIComponent(hash)+'&_wpnonce='+encodeURIComponent('".wp_create_nonce('bm_hide')."')}); };
                var b2=document.createElement('button'); b2.type='button'; b2.className='bm-hide-btn'; b2.textContent='مخفی همیشگی'; b2.onclick=function(){ el.style.display='none'; fetch(ajaxurl,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'action=bm_hide_notice&hash='+encodeURIComponent(hash)+'&permanent=1&_wpnonce='+encodeURIComponent('".wp_create_nonce('bm_hide')."')}); };
                bar.appendChild(b1); bar.appendChild(b2); el.appendChild(bar);
            });
        });</script>";
    });
    add_action('wp_ajax_bm_hide_notice', function(){
        check_ajax_referer('bm_hide');
        $hash=sanitize_key($_POST['hash']??'');
        if(!$hash) wp_die();
        $uid=get_current_user_id();
        $hidden=get_user_meta($uid,'bloat_manager_hidden_notices',true);
        if(!is_array($hidden)) $hidden=[];
        $hidden[$hash]=time();
        update_user_meta($uid,'bloat_manager_hidden_notices',$hidden);
        wp_send_json_success();
    });
}

// remove WP version from head already handled via generator, but also script ver
}
add_action('plugins_loaded','bloat_manager_apply',20);
