<?php
if (!defined('ABSPATH')) exit;

/** اعمال امکان‌های بخش‌های جدید */
function mb_apply_more($o, $on) {

    /* ───── سمت بازدیدکننده ───── */
    if ($on('rest_links')) {
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('template_redirect', 'rest_output_link_header', 11);
        remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
    }
    if ($on('feed_generator')) {
        foreach (['rss2_head', 'commentsrss2_head', 'rss_head', 'rdf_header', 'atom_head', 'comments_atom_head', 'opml_head', 'app_head'] as $h) {
            remove_action($h, 'the_generator');
        }
    }
    if ($on('separate_block_styles')) add_filter('should_load_separate_core_block_assets', '__return_true');
    if ($on('google_maps')) {
        $ex = array_filter(array_map('trim', explode(',', (string) $o['google_maps_exclude'])));
        add_action('template_redirect', function () use ($ex) {
            foreach ($ex as $e) {
                if (($e === 'front' && is_front_page()) || ($e === 'blog' && is_home()) || (ctype_digit($e) && (int) $e === (int) get_queried_object_id())) return;
            }
            ob_start(function ($html) {
                return preg_replace('#<script[^>]+src=["\'][^"\']*(maps\.googleapis\.com|maps\.google\.com|maps-api-v3)[^"\']*["\'][^>]*>\s*</script>#i', '', $html);
            });
        }, 2);
    }

    /* ───── دیدگاه‌ها ───── */
    if (!empty($o['comment_post_types'])) {
        $types = (array) $o['comment_post_types'];
        add_filter('comments_open', function ($open, $id) use ($types) { return in_array(get_post_type($id), $types, true) ? false : $open; }, 20, 2);
        add_filter('pings_open', function ($open, $id) use ($types) { return in_array(get_post_type($id), $types, true) ? false : $open; }, 20, 2);
        add_action('init', function () use ($types) {
            foreach ($types as $t) { remove_post_type_support($t, 'comments'); remove_post_type_support($t, 'trackbacks'); }
        }, 100);
    }
    if ($on('close_comments')) {
        $d = (int) $o['close_comments_days'];
        add_filter('pre_option_close_comments_for_old_posts', '__return_true');
        add_filter('pre_option_close_comments_days_old', function () use ($d) { return $d; });
    }
    if ($on('paginate_comments')) {
        $n = (int) $o['paginate_comments_num'];
        add_filter('pre_option_page_comments', '__return_true');
        add_filter('pre_option_comments_per_page', function () use ($n) { return $n; });
    }
    if ($on('default_ping_status')) {
        add_filter('pre_option_default_ping_status', function () { return 'closed'; });
        add_filter('pre_option_default_pingback_flag', '__return_zero');
        add_filter('pings_open', '__return_false', 30);
    }
    if ($on('comment_autolink')) remove_filter('comment_text', 'make_clickable', 9);
    if ($on('gravatar')) {
        $custom = trim((string) $o['gravatar_url']);
        $svg = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 96"><rect width="96" height="96" fill="#d5dce2"/><circle cx="48" cy="38" r="16" fill="#9aa8b5"/><path d="M16 88c3-20 17-28 32-28s29 8 32 28z" fill="#9aa8b5"/></svg>');
        add_filter('get_avatar_url', function () use ($custom, $svg) { return $custom !== '' ? esc_url($custom) : $svg; }, 99);
        add_filter('pre_get_avatar_data', function ($args) use ($custom, $svg) { $args['url'] = $custom !== '' ? esc_url($custom) : $svg; $args['found_avatar'] = true; return $args; }, 99);
    }
    $sp = (int) $o['spam_cleaner'];
    add_action('mb_spam_cleanup', function () use ($sp) {
        if ($sp < 1) return;
        $ids = get_comments(['status' => 'spam', 'number' => 200, 'fields' => 'ids', 'date_query' => [['before' => $sp . ' days ago']]]);
        foreach ($ids as $id) wp_delete_comment($id, true);
    });
    if (is_admin() || wp_doing_cron()) {
        add_action('init', function () use ($sp) {
            $next = wp_next_scheduled('mb_spam_cleanup');
            if ($sp > 0 && !$next) wp_schedule_event(time() + 300, 'daily', 'mb_spam_cleanup');
            if ($sp < 1 && $next) wp_clear_scheduled_hook('mb_spam_cleanup');
        });
    }

    /* ───── پیشخوان ───── */
    if ($on('admin_bar_logo')) {
        add_action('wp_before_admin_bar_render', function () { global $wp_admin_bar; if ($wp_admin_bar) $wp_admin_bar->remove_menu('wp-logo'); }, 0);
    }
    if ($on('howdy')) {
        add_action('admin_bar_menu', function ($bar) {
            if (!$bar->get_node('my-account')) return;
            $u = wp_get_current_user();
            $bar->add_node(['id' => 'my-account', 'title' => esc_html($u->display_name) . get_avatar($u->ID, 28)]);
        }, 25);
    }
    if ($on('admin_footer_text')) {
        add_filter('admin_footer_text', '__return_empty_string', 99);
        add_filter('update_footer', '__return_empty_string', 99);
    }
    if ($on('screen_options')) add_filter('screen_options_show_screen', '__return_false');
    if ($on('help_tabs')) add_action('admin_head', function () { $s = get_current_screen(); if ($s) $s->remove_help_tabs(); });
    if ($on('hide_admin_bar')) add_filter('show_admin_bar', function ($s) { return current_user_can('manage_options') ? $s : false; });

    $mode = $o['admin_notices'];
    if ($mode === 'all' || $mode === 'non_admin') {
        add_action('admin_head', function () use ($mode) {
            if ($mode === 'non_admin' && current_user_can('manage_options')) return;
            echo '<style>.notice:not(.mb-keep),.updated:not(.mb-keep),.error:not(.mb-keep),.update-nag{display:none!important}</style>';
        });
    } elseif ($mode === 'selected') {
        add_action('admin_footer', function () {
            $hidden = get_user_meta(get_current_user_id(), 'mb_hidden_notices', true);
            $hidden = is_array($hidden) ? array_keys($hidden) : [];
            $cfg = wp_json_encode(['hidden' => $hidden, 'ajax' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('mb_hide_notice')]);
            echo '<style>.mb-hide-btn{font:12px inherit;padding:2px 10px;border-radius:6px;border:1px solid #c3c4c7;background:#fff;cursor:pointer;margin-left:6px}</style>';
            echo '<script>(function(c){function h(s){var x=5381,i;for(i=0;i<s.length;i++)x=((x<<5)+x+s.charCodeAt(i))|0;return "n"+(x>>>0).toString(36)}'
               . 'document.querySelectorAll(".notice,.updated,.error,.update-nag").forEach(function(el){if(el.classList.contains("mb-keep")||el.closest(".mb-app"))return;'
               . 'var tx=(el.textContent||"").replace(/\s+/g," ").trim();var k=h(tx.slice(0,200));if(c.hidden.indexOf(k)>-1){el.style.display="none";return}'
               . 'var bar=document.createElement("p"),b1=document.createElement("button"),b2=document.createElement("button");'
               . 'b1.type=b2.type="button";b1.className=b2.className="mb-hide-btn";b1.textContent="مخفی";b2.textContent="مخفی همیشگی";'
               . 'b1.onclick=function(){el.style.display="none"};'
               . 'b2.onclick=function(){el.style.display="none";var f=new FormData();f.append("action","mb_hide_notice");f.append("nonce",c.nonce);f.append("hash",k);f.append("text",tx.slice(0,160));fetch(c.ajax,{method:"POST",credentials:"same-origin",body:f})};'
               . 'bar.appendChild(b1);bar.appendChild(b2);el.appendChild(bar)})})(' . $cfg . ');</script>';
        });
    }
    if ($on('update_nag')) {
        add_action('admin_head', function () {
            if (!current_user_can('update_core')) { remove_action('admin_notices', 'update_nag', 3); remove_action('admin_notices', 'maintenance_nag', 10); }
        }, 1);
    }
    if ($on('admin_email_check')) add_filter('admin_email_check_interval', '__return_zero');
    if ($on('privacy_nag')) {
        add_action('admin_init', function () {
            if (!class_exists('WP_Privacy_Policy_Content')) return;
            remove_action('admin_init', ['WP_Privacy_Policy_Content', 'text_change_check'], 100);
            remove_action('edit_form_after_title', ['WP_Privacy_Policy_Content', 'notice']);
            remove_action('admin_init', ['WP_Privacy_Policy_Content', 'add_suggested_content'], 1);
        }, 0);
    }
    if ($on('browser_nag')) {
        $ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        add_filter('pre_site_transient_browser_' . md5($ua), '__return_false');
    }
    if ($on('try_gutenberg_nag')) add_filter('try_gutenberg_panel', '__return_false');
    if ($on('plugin_suggestions')) {
        add_filter('plugins_api_result', function ($res, $action, $args) {
            if ($action === 'query_plugins' && empty($args->search) && empty($args->author) && empty($args->tag) && empty($args->browse_force)) {
                if (isset($res->plugins) && !empty($args->browse) && in_array($args->browse, ['featured', 'popular', 'recommended'], true)) $res->plugins = [];
            }
            return $res;
        }, 10, 3);
        add_filter('install_plugins_tabs', function ($tabs) { unset($tabs['featured'], $tabs['popular'], $tabs['recommended']); return $tabs; });
    }
    if ($on('plugin_popular_tags')) {
        add_action('admin_head', function () {
            $s = get_current_screen();
            if ($s && $s->id === 'plugin-install') echo '<style>.plugin-install-tab-popular+.wp-filter,#plugin-install-tags,.popular-tags{display:none!important}</style>';
        });
    }
    if ($on('login_logo')) add_action('login_enqueue_scripts', function () { echo '<style>#login h1 a{display:none!important}</style>'; });
    if ($on('login_logo_url')) add_filter('login_headerurl', function () { return home_url('/'); });
    if ($on('login_logo_title')) add_filter('login_headertext', function () { return get_bloginfo('name'); });
    if ($on('login_lang_switcher')) add_filter('login_display_language_dropdown', '__return_false');
    if ($on('autosave_off')) add_action('admin_init', function () { wp_deregister_script('autosave'); });
    if ($on('trash_days')) {
        $days = (int) $o['trash_days_num'];
        add_action('wp_scheduled_delete', function () use ($days) {
            global $wpdb;
            $cut = time() - $days * DAY_IN_SECONDS;
            $ids = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_trash_meta_time' AND meta_value < %d LIMIT 200", $cut));
            foreach ((array) $ids as $id) wp_delete_post((int) $id);
        }, 5);
    }
    if ($on('force_gd')) {
        add_filter('wp_image_editors', function ($e) { $gd = 'WP_Image_Editor_GD'; $e = array_diff($e, [$gd]); array_unshift($e, $gd); return $e; });
    }
    if ($on('site_health')) {
        add_filter('site_status_tests', function () { return ['direct' => [], 'async' => []]; });
        add_action('wp_loaded', function () { remove_action('wp_site_health_scheduled_check', 'wp_site_health_scheduled_check'); wp_clear_scheduled_hook('wp_site_health_scheduled_check'); });
        add_filter('rest_endpoints', function ($e) { foreach ($e as $r => $v) if (strpos($r, '/wp-site-health/') === 0) unset($e[$r]); return $e; });
        add_action('admin_menu', function () { remove_submenu_page('tools.php', 'site-health.php'); }, 1);
        add_filter('user_has_cap', function ($caps) { if (isset($caps['view_site_health_checks'])) $caps['view_site_health_checks'] = false; return $caps; }, 999);
        add_action('wp_dashboard_setup', function () { remove_meta_box('dashboard_site_health', 'dashboard', 'normal'); });
    }
    if ($on('import_export')) {
        add_action('admin_menu', function () { remove_submenu_page('tools.php', 'export.php'); remove_submenu_page('tools.php', 'import.php'); }, 1);
        add_action('admin_init', function () { global $pagenow; if ($pagenow === 'export.php' || $pagenow === 'import.php') wp_die('این بخش با افزونه‌ی Disable Bloat Manager غیرفعال شده است.', '', ['response' => 403]); });
    }
    if ($on('core_privacy')) {
        add_filter('map_meta_cap', function ($caps, $cap) {
            return in_array($cap, ['manage_privacy_options', 'export_others_personal_data', 'erase_others_personal_data'], true) ? ['do_not_allow'] : $caps;
        }, 10, 2);
    }

    /* ───── ویرایشگر بلوک ───── */
    if ($on('disable_gutenberg')) {
        add_filter('use_block_editor_for_post', '__return_false', 100);
        add_filter('use_block_editor_for_post_type', '__return_false', 100);
    }
    if ($on('widget_block_editor')) {
        add_filter('gutenberg_use_widgets_block_editor', '__return_false', 100);
        add_filter('use_widgets_block_editor', '__return_false');
        add_action('after_setup_theme', function () { remove_theme_support('widgets-block-editor'); });
    }
    if ($on('welcome_guide') || $on('fullscreen_mode')) {
        $js = '';
        if ($on('welcome_guide')) $js .= "wp.data.dispatch('core/preferences').set('core/edit-post','welcomeGuide',false);wp.data.dispatch('core/preferences').set('core/edit-site','welcomeGuide',false);";
        if ($on('fullscreen_mode')) $js .= "wp.data.dispatch('core/preferences').set('core/edit-post','fullscreenMode',false);";
        add_action('enqueue_block_editor_assets', function () use ($js) {
            wp_add_inline_script('wp-edit-post', 'wp.domReady(function(){try{' . $js . '}catch(e){}});');
        });
    }
    if ($on('block_directory')) remove_action('enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets');
    if ($on('default_patterns')) add_action('after_setup_theme', function () { remove_theme_support('core-block-patterns'); });
    if ($on('remote_patterns')) add_filter('should_load_remote_block_patterns', '__return_false');
    if ($on('template_editor')) add_action('after_setup_theme', function () { remove_theme_support('block-templates'); }, 20);

    /* ───── ابزارک‌ها ───── */
    if ($on('widgets_all') || !empty($o['widgets_pick'])) {
        $list = $on('widgets_all') ? array_keys(mb_widget_options()) : (array) $o['widgets_pick'];
        add_action('widgets_init', function () use ($list) { foreach ($list as $w) unregister_widget($w); }, 100);
    }
    if ($on('widget_support')) {
        add_action('after_setup_theme', function () { remove_theme_support('widgets'); remove_theme_support('widgets-block-editor'); }, 20);
        add_action('admin_menu', function () { remove_submenu_page('themes.php', 'widgets.php'); }, 999);
        add_filter('sidebars_widgets', '__return_empty_array', 99);
    }

    /* ───── به‌روزرسانی‌ها ───── */
    if ($on('auto_update_themes')) add_filter('auto_update_theme', '__return_false');
    if ($on('auto_update_plugins')) add_filter('auto_update_plugin', '__return_false');
    if ($on('auto_update_core')) {
        add_filter('allow_minor_auto_core_updates', '__return_false');
        add_filter('allow_major_auto_core_updates', '__return_false');
        add_filter('auto_update_core', '__return_false');
    }
    if ($on('core_updates_off')) {
        add_filter('pre_site_transient_update_core', function () { global $wp_version; return (object) ['last_checked' => time(), 'version_checked' => $wp_version, 'updates' => []]; });
        remove_action('init', 'wp_version_check');
        remove_action('admin_init', '_maybe_update_core');
        remove_action('wp_version_check', 'wp_version_check');
    }

    mb_apply_woo($o, $on);
    mb_apply_third($o, $on);
    mb_apply_fonts($o, $on);
    mb_apply_secure($o, $on);
    mb_apply_net($o, $on);
    mb_apply_menus($o);
}

/* ───── ثبت اعلان مخفی‌شده ───── */
add_action('wp_ajax_mb_hide_notice', function () {
    if (!current_user_can('read')) wp_send_json_error([], 403);
    check_ajax_referer('mb_hide_notice', 'nonce');
    $hash = isset($_POST['hash']) ? sanitize_key(wp_unslash($_POST['hash'])) : '';
    if ($hash === '') wp_send_json_error([], 400);
    $uid = get_current_user_id();
    $hidden = get_user_meta($uid, 'mb_hidden_notices', true);
    if (!is_array($hidden)) $hidden = [];
    $text = isset($_POST['text']) ? sanitize_text_field(wp_unslash($_POST['text'])) : '';
    $hidden[$hash] = ['t' => time(), 'x' => mb_substr($text, 0, 160)];
    update_user_meta($uid, 'mb_hidden_notices', array_slice($hidden, -300, null, true));
    wp_send_json_success();
});

/* ───── ووکامرس ───── */
function mb_apply_woo($o, $on) {
    $woo_pages = function () {
        return function_exists('is_woocommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page() || is_product() || is_product_category() || is_shop());
    };
    if ($on('wc_admin')) {
        add_filter('woocommerce_admin_disabled', '__return_true');
        add_filter('woocommerce_admin_features', '__return_empty_array', 90);
        add_action('admin_head', function () { echo '<style>.woocommerce-layout__header{display:none}</style>'; });
    }
    if ($on('wc_home')) {
        add_action('admin_menu', function () { remove_submenu_page('woocommerce', 'wc-admin'); }, 999);
        add_action('admin_init', function () {
            if (isset($_GET['page']) && $_GET['page'] === 'wc-admin' && empty($_GET['path'])) {
                $hpos = class_exists('\Automattic\WooCommerce\Utilities\OrderUtil') && \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
                wp_safe_redirect(admin_url($hpos ? 'admin.php?page=wc-orders' : 'edit.php?post_type=shop_order'));
                exit;
            }
        });
    }
    if ($on('wc_marketing')) {
        add_filter('woocommerce_marketing_menu_items', '__return_empty_array');
        add_filter('woocommerce_admin_features', function ($f) { $k = array_search('marketing', (array) $f, true); if ($k !== false) unset($f[$k]); return $f; });
        add_action('admin_menu', function () { remove_submenu_page('woocommerce', 'wc-admin&path=/marketing'); remove_menu_page('woocommerce-marketing'); }, 999);
    }
    if ($on('wc_helper')) add_filter('woocommerce_helper_suppress_admin_notices', '__return_true');
    if ($on('wc_marketplace')) add_filter('woocommerce_allow_marketplace_suggestions', '__return_false', 999);
    if ($on('wc_addons_submenu')) {
        add_action('admin_menu', function () { remove_submenu_page('woocommerce', 'wc-addons'); remove_submenu_page('woocommerce', 'wc-admin&path=/extensions'); remove_submenu_page('woocommerce', 'wc-addons&section=helper'); }, 999);
    }
    if ($on('wc_shipping_reco')) add_filter('pre_option_woocommerce_settings_shipping_recommendations_hidden', function () { return 'yes'; });
    if ($on('wc_payment_reco')) add_filter('pre_option_woocommerce_setting_payments_recommendations_hidden', function () { return 'yes'; });
    if ($on('wc_payment_providers')) {
        add_action('admin_head', function () {
            echo '<script>jQuery(function(){var e=document.querySelector("#settings-other-payment-methods");if(e&&e.parentElement&&e.parentElement.parentElement)e.parentElement.parentElement.style.display="none"});</script>';
        });
    }
    if ($on('wc_status_widget')) add_action('wp_dashboard_setup', function () { remove_meta_box('woocommerce_dashboard_status', 'dashboard', 'normal'); });
    if ($on('wc_setup_widget')) add_action('wp_dashboard_setup', function () { remove_meta_box('wc_admin_dashboard_setup', 'dashboard', 'normal'); }, 40);
    if ($on('wc_product_editor')) add_filter('pre_option_woocommerce_feature_product_block_editor_enabled', function () { return 'no'; });
    if ($on('wc_blocks_backend')) {
        add_action('admin_enqueue_scripts', function () { wp_deregister_style('wc-block-editor'); wp_deregister_style('wc-blocks-style'); }, 999);
    }
    if ($on('wc_guide_emails')) add_filter('pre_option_woocommerce_merchant_email_notifications', function () { return 'no'; });
    if ($on('wc_mobile_footer')) {
        add_action('woocommerce_email', function ($mailer) {
            if (isset($mailer->emails['WC_Email_New_Order'])) remove_action('woocommerce_email_footer', [$mailer->emails['WC_Email_New_Order'], 'mobile_messaging'], 9);
        });
    }
    if ($on('wc_scripts')) {
        add_action('wp_enqueue_scripts', function () use ($woo_pages) {
            if (!function_exists('is_woocommerce') || $woo_pages()) return;
            foreach (['woocommerce-general', 'woocommerce-layout', 'woocommerce-smallscreen', 'woocommerce_frontend_styles', 'woocommerce_fancybox_styles', 'woocommerce_chosen_styles', 'woocommerce_prettyPhoto_css'] as $h) wp_dequeue_style($h);
            foreach (['wc_price_slider', 'wc-single-product', 'wc-add-to-cart', 'wc-checkout', 'wc-add-to-cart-variation', 'wc-cart', 'wc-chosen', 'woocommerce', 'prettyPhoto', 'prettyPhoto-init', 'jquery-blockui', 'jquery-placeholder', 'fancybox', 'jqueryui'] as $h) wp_dequeue_script($h);
        }, 99);
    }
    if ($on('wc_cart_fragments')) add_action('wp_enqueue_scripts', function () { wp_dequeue_script('wc-cart-fragments'); }, 99);
    if ($on('wc_blocks_frontend')) {
        add_action('wp_enqueue_scripts', function () {
            foreach (['wc-blocks-vendors-style', 'wc-all-blocks-style', 'wc-blocks-style', 'wc-blocks-packages-style', 'wc-blocks-style-all-products'] as $s) wp_deregister_style($s);
        }, 999);
    }
    if ($on('wc_widgets')) {
        add_action('widgets_init', function () {
            foreach (['WC_Widget_Products', 'WC_Widget_Product_Categories', 'WC_Widget_Product_Tag_Cloud', 'WC_Widget_Cart', 'WC_Widget_Layered_Nav', 'WC_Widget_Layered_Nav_Filters', 'WC_Widget_Price_Filter', 'WC_Widget_Product_Search', 'WC_Widget_Recently_Viewed', 'WC_Widget_Recent_Reviews', 'WC_Widget_Top_Rated_Products', 'WC_Widget_Rating_Filter'] as $w) unregister_widget($w);
        }, 99);
    }
    if ($on('wc_reviews')) {
        add_filter('pre_option_woocommerce_enable_reviews', function () { return 'no'; });
        add_filter('comments_open', function ($open, $id) { return get_post_type($id) === 'product' ? false : $open; }, 20, 2);
        add_filter('woocommerce_product_tabs', function ($t) { unset($t['reviews']); return $t; }, 99);
    }
    if ($on('wc_password_meter')) {
        add_action('wp_print_scripts', function () { wp_dequeue_script('wc-password-strength-meter'); }, 100);
    }
    if ($on('wc_stripe')) {
        add_action('wp_enqueue_scripts', function () {
            if (!function_exists('is_product') || is_product() || is_cart() || is_checkout() || is_account_page()) return;
            foreach (['stripe', 'wc-stripe-payment-request', 'wc-stripe-express-checkout', 'wc_stripe_payment_request', 'wc-stripe-blocks-integration'] as $h) { wp_dequeue_script($h); wp_dequeue_style($h); }
        }, 99);
    }
}

/* ───── افزونه‌های دیگر و هوش مصنوعی ───── */
function mb_apply_third($o, $on) {
    if ($on('jetpack_promos')) {
        add_filter('jetpack_just_in_time_msgs', '__return_false', 20);
        add_filter('jetpack_show_promotions', '__return_false', 20);
        add_action('admin_init', function () { remove_action('admin_notices', 'jetpack_display_warning'); });
    }
    if ($on('jetpack_blaze')) add_filter('jetpack_blaze_enabled', '__return_false');
    if ($on('elementor_widget')) add_action('wp_dashboard_setup', function () { remove_meta_box('e-dashboard-overview', 'dashboard', 'normal'); }, 40);
    if ($on('elementor_home')) {
        add_action('admin_menu', function () { remove_submenu_page('elementor', 'elementor-home'); }, 999);
        add_action('admin_init', function () {
            if (isset($_GET['page']) && $_GET['page'] === 'elementor-home') { wp_safe_redirect(admin_url('edit.php?post_type=elementor_library')); exit; }
        });
    }
    if ($on('elementor_connect')) {
        add_filter('pre_http_request', function ($pre, $args, $url) {
            $h = wp_parse_url($url, PHP_URL_HOST);
            if ($h && preg_match('/(^|\.)(my|library|assets|api)\.elementor\.com$/i', $h)) return new WP_Error('mb_blocked', 'درخواست توسط Disable Bloat Manager مسدود شد.');
            return $pre;
        }, 10, 3);
    }
    if ($on('yoast_premium')) {
        add_filter('wpseo_show_premium_upgrade_admin_notice', '__return_false');
        add_action('admin_menu', function () { remove_submenu_page('wpseo_dashboard', 'wpseo_licenses'); }, 999);
    }
    if ($on('yoast_admin_bar')) add_filter('wpseo_enable_admin_bar_menu', '__return_false');
    if ($on('yoast_comments')) add_filter('wpseo_debug_markers', '__return_false');
    if ($on('yoast_widget')) add_action('wp_dashboard_setup', function () { remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'normal'); });
    if ($on('cf7_assets')) { add_filter('wpcf7_load_js', '__return_false'); add_filter('wpcf7_load_css', '__return_false'); }
    if ($on('acf_menu')) add_filter('acf/settings/show_admin', '__return_false');
    if ($on('wpml_meta')) {
        add_action('wp_head', function () { if (isset($GLOBALS['sitepress'])) remove_action('wp_head', [$GLOBALS['sitepress'], 'meta_generator_tag']); }, 0);
    }
    if ($on('updraftplus_bar') && !defined('UPDRAFTPLUS_ADMINBAR_DISABLE')) define('UPDRAFTPLUS_ADMINBAR_DISABLE', true);
    if ($on('skyverge_menu')) {
        add_action('admin_menu', function () { remove_menu_page('skyverge'); }, 99);
        add_action('admin_enqueue_scripts', function () { wp_dequeue_style('sv-wordpress-plugin-admin-menus'); }, 20);
    }
    if ($on('wpdesk_widgets')) {
        add_action('wp_dashboard_setup', function () {
            foreach (['flexible-checkout-fields', 'flexible-invoices', 'wpdesk_ltv_dashboard_widget', 'flexible-product-fields'] as $m) remove_meta_box($m, 'dashboard', 'normal');
        }, 20);
    }
    if ($on('flexible_shipping_menu')) add_action('admin_menu', function () { remove_submenu_page('woocommerce', 'octolize-shipping-extensions'); }, 999);

    /* هوش مصنوعی */
    if ($on('wp_ai')) {
        add_filter('wp_supports_ai', '__return_false', 999);
        add_action('admin_menu', function () {
            global $submenu;
            foreach (['options-general.php', 'tools.php'] as $parent) {
                if (empty($submenu[$parent])) continue;
                foreach ($submenu[$parent] as $item) {
                    if (isset($item[2]) && stripos($item[2], 'connectors') !== false) remove_submenu_page($parent, $item[2]);
                }
            }
        }, 9999);
        add_action('admin_init', function () {
            if (isset($_GET['page']) && stripos((string) $_GET['page'], 'connectors') !== false) wp_die('این بخش با افزونه‌ی Disable Bloat Manager غیرفعال شده است.', '', ['response' => 403]);
        });
    }
    if ($on('elementor_ai')) {
        add_filter('elementor/ai/is_enabled', '__return_false');
        add_filter('elementor/experiments/feature-flags', function ($f) { if (isset($f['ai'])) $f['ai'] = false; return $f; });
    }
    if ($on('elementor_mcp')) {
        add_filter('elementor/mcp/is_enabled', '__return_false');
        add_filter('elementor/mcp/enabled', '__return_false');
        add_filter('rest_endpoints', function ($e) {
            foreach ($e as $r => $v) if (strpos($r, '/elementor/v1/mcp') === 0 || strpos($r, '/mcp/') === 0) unset($e[$r]);
            return $e;
        });
    }
    if ($on('rankmath_ai')) { add_filter('rank_math/ai/enabled', '__return_false'); add_filter('rank_math/content_ai/enabled', '__return_false'); }
    if ($on('yoast_ai')) { add_filter('Yoast\\WP\\SEO\\AI_Generate\\enabled', '__return_false'); add_filter('wpseo_ai_generator_enabled', '__return_false'); }
    if ($on('jetpack_ai')) add_filter('jetpack_ai_enabled', '__return_false');
}

/* ───── فونت‌ها ───── */
function mb_apply_fonts($o, $on) {
    if ($on('elementor_google_fonts')) add_filter('elementor/frontend/print_google_fonts', '__return_false');
    if (!($on('fonts_remove') || $on('fonts_local') || $on('fonts_swap') || $on('fa_async'))) return;
    add_action('template_redirect', function () use ($on) {
        if (is_feed() || is_embed() || (function_exists('wp_is_json_request') && wp_is_json_request())) return;
        ob_start(function ($html) use ($on) { return mb_fonts_filter($html, $on); });
    }, 3);
}

function mb_fonts_filter($html, $on) {
    if (!is_string($html) || stripos($html, '<html') === false) return $html;
    $gf = 'fonts\.googleapis\.com|fonts\.gstatic\.com';
    if ($on('fonts_remove')) {
        $html = preg_replace('#<link\b[^>]*(' . $gf . ')[^>]*>#i', '', $html);
        $html = preg_replace('#@import\s+(?:url\()?[\'"]?[^;\)]*fonts\.googleapis\.com[^;\)]*[\'"]?\)?\s*;?#i', '', $html);
    } else {
        if ($on('fonts_local')) {
            $html = preg_replace_callback('#(<link\b[^>]*href=)(["\'])(https?:)?(//fonts\.googleapis\.com/[^"\']+)\2#i', function ($m) use ($on) {
                $url = html_entity_decode(($m[3] ?: 'https:') . $m[4]);
                $local = mb_font_localize($url, $on('fonts_swap'));
                return $m[1] . $m[2] . ($local ? esc_url($local) : $m[3] . $m[4]) . $m[2];
            }, $html);
        } elseif ($on('fonts_swap')) {
            $html = preg_replace_callback('#(<link\b[^>]*href=)(["\'])((?:https?:)?//fonts\.googleapis\.com/[^"\']+)\2#i', function ($m) {
                return (stripos($m[3], 'display=') === false) ? $m[1] . $m[2] . $m[3] . (strpos($m[3], '?') === false ? '?' : '&amp;') . 'display=swap' . $m[2] : $m[0];
            }, $html);
        }
        if ($on('fonts_swap')) {
            $html = preg_replace_callback('#<style\b[^>]*>.*?</style>#is', function ($m) {
                return stripos($m[0], '@font-face') !== false && stripos($m[0], 'font-display') === false ? preg_replace('#@font-face\s*\{#i', '@font-face{font-display:swap;', $m[0]) : $m[0];
            }, $html);
        }
    }
    if ($on('fa_async')) {
        $html = preg_replace_callback('#<link\b[^>]*rel=["\']stylesheet["\'][^>]*href=["\'][^"\']*(font-?awesome|fontawesome)[^"\']*["\'][^>]*>#i', function ($m) {
            $tag = $m[0];
            $pre = preg_replace('#rel=["\']stylesheet["\']#i', 'rel="preload" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"', $tag, 1);
            return $pre . '<noscript>' . $tag . '</noscript>';
        }, $html);
    }
    return $html;
}

/** دانلود یک‌بارِ CSS و فایل‌های فونت گوگل و برگرداندن آدرس محلی */
function mb_font_localize($url, $swap) {
    if ($swap && stripos($url, 'display=') === false) $url .= (strpos($url, '?') === false ? '?' : '&') . 'display=swap';
    $key = md5($url);
    $up = wp_upload_dir();
    $dir = trailingslashit($up['basedir']) . 'mb-fonts/';
    $base = trailingslashit($up['baseurl']) . 'mb-fonts/';
    if (file_exists($dir . $key . '.css')) return $base . $key . '.css';
    if (get_transient('mb_fontfail_' . $key)) return false;
    $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36';
    $res = wp_remote_get($url, ['timeout' => 6, 'user-agent' => $ua]);
    if (is_wp_error($res) || wp_remote_retrieve_response_code($res) !== 200) { set_transient('mb_fontfail_' . $key, 1, HOUR_IN_SECONDS); return false; }
    $css = wp_remote_retrieve_body($res);
    if (!wp_mkdir_p($dir)) return false;
    preg_match_all('#url\((["\']?)(https://fonts\.gstatic\.com/[^)"\']+)\1\)#i', $css, $m);
    foreach (array_unique($m[2]) as $src) {
        $ext = pathinfo(wp_parse_url($src, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'woff2';
        $name = md5($src) . '.' . preg_replace('/[^a-z0-9]/i', '', $ext);
        if (!file_exists($dir . $name)) {
            $f = wp_remote_get($src, ['timeout' => 8, 'user-agent' => $ua]);
            if (is_wp_error($f) || wp_remote_retrieve_response_code($f) !== 200) { set_transient('mb_fontfail_' . $key, 1, HOUR_IN_SECONDS); return false; }
            file_put_contents($dir . $name, wp_remote_retrieve_body($f));
        }
        $css = str_replace($src, $base . $name, $css);
    }
    file_put_contents($dir . $key . '.css', $css);
    return $base . $key . '.css';
}
