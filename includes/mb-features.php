<?php
if (!defined('ABSPATH')) exit;

/** اعمال گزینه‌های فعال روی وردپرس */
function mb_apply_features() {
    $o  = mb_get_options();
    $on = function ($k) use ($o) { return !empty($o[$k]); };

    // ───── سمت بازدیدکننده ─────
    if ($on('emojis')) {
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
    if ($on('rsd'))       remove_action('wp_head', 'rsd_link');
    if ($on('wlw'))       remove_action('wp_head', 'wlwmanifest_link');
    if ($on('shortlink')) {
        remove_action('wp_head', 'wp_shortlink_wp_head', 10);
        remove_action('template_redirect', 'wp_shortlink_header', 11);
    }
    if ($on('generator')) {
        remove_action('wp_head', 'wp_generator');
        add_filter('the_generator', '__return_empty_string');
    }
    if ($on('adjacent_posts')) {
        remove_action('wp_head', 'adjacent_posts_rel_link', 10);
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
    }
    if ($on('dns_prefetch')) {
        add_action('init', function () { remove_action('wp_head', 'wp_resource_hints', 2); }, 99);
    }
    if ($on('favicon_blank')) {
        add_action('wp_head', function () {
            if (function_exists('has_site_icon') && has_site_icon()) return;
            echo '<link rel="icon" href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQEAYAAABPYyMiAAAABmJLR0T///////8JWPfcAAAACXBIWXMAAABIAAAASABGyWs+AAAAF0lEQVRIx2NgGAWjYBSMglEwCkbBSAcACBAAAeaR9cIAAAAASUVORK5CYII=" />' . "\n";
        });
    }

    if ($on('embeds')) {
        add_action('init', function () {
            global $wp;
            if (isset($wp->public_query_vars)) $wp->public_query_vars = array_diff($wp->public_query_vars, ['embed']);
            add_filter('embed_oembed_discover', '__return_false');
            remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
            remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result', 10);
            remove_action('wp_head', 'wp_oembed_add_discovery_links');
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
    if ($on('dashicons')) {
        add_action('wp_enqueue_scripts', function () {
            if (!is_admin() && !is_user_logged_in()) {
                wp_dequeue_style('dashicons');
                wp_deregister_style('dashicons');
            }
        });
    }
    if ($on('jquery_migrate')) {
        add_action('wp_default_scripts', function ($scripts) {
            if (!is_admin() && isset($scripts->registered['jquery'])) {
                $s = $scripts->registered['jquery'];
                if (!empty($s->deps)) $s->deps = array_diff($s->deps, ['jquery-migrate']);
            }
        });
    }
    if ($on('block_library')) {
        add_action('wp_enqueue_scripts', function () {
            wp_dequeue_style('wp-block-library');
            wp_dequeue_style('wp-block-library-theme');
            wp_dequeue_style('wc-blocks-style');
        }, 100);
    }
    if ($on('global_styles')) {
        add_action('after_setup_theme', function () {
            remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
            remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
        });
    }
    if ($on('password_meter')) {
        add_action('wp_enqueue_scripts', function () { wp_dequeue_script('zxcvbn-async'); wp_deregister_script('zxcvbn-async'); }, 100);
        add_action('admin_enqueue_scripts', function () { wp_dequeue_script('zxcvbn-async'); }, 100);
    }

    if ($on('feed_links')) {
        remove_action('wp_head', 'feed_links', 2);
        remove_action('wp_head', 'feed_links_extra', 3);
    }
    if ($on('disable_feeds')) {
        $msg = 'فیدها غیرفعال است. لطفاً به صفحه‌ی اصلی بروید.';
        foreach (['do_feed', 'do_feed_rdf', 'do_feed_rss', 'do_feed_rss2', 'do_feed_atom'] as $hook) {
            add_action($hook, function () use ($msg) { wp_die($msg, '', ['response' => 410]); }, 1);
        }
        remove_action('wp_head', 'feed_links', 2);
        remove_action('wp_head', 'feed_links_extra', 3);
    }
    if ($on('xml_sitemap')) add_filter('wp_sitemaps_enabled', '__return_false');

    if ($on('remove_ver')) {
        add_filter('style_loader_src',  function ($src) { return $src ? remove_query_arg('ver', $src) : $src; }, 9999);
        add_filter('script_loader_src', function ($src) { return $src ? remove_query_arg('ver', $src) : $src; }, 9999);
    }
    if ($on('capital_p')) {
        $map = ['the_content' => ['the_content'], 'the_title' => ['the_title'], 'page_title' => ['wp_title', 'document_title'], 'comment_text' => ['comment_text'], 'widgets' => ['widget_text_content', 'widget_block_content']];
        foreach ((array) $o['capital_p_pick'] as $k) {
            foreach (isset($map[$k]) ? $map[$k] : [] as $filter) {
                $p = has_filter($filter, 'capital_P_dangit');
                if ($p !== false) remove_filter($filter, 'capital_P_dangit', $p);
            }
        }
    }

    // ───── دیدگاه‌ها ─────
    if ($on('comment_urls')) {
        add_filter('get_comment_author_link', function ($link) { return preg_replace('/<a[^>]*>(.*)<\/a>/', '$1', $link); });
    }
    if ($on('comment_scripts')) {
        add_action('wp_enqueue_scripts', function () {
            if (is_singular() && comments_open() && get_option('thread_comments')) return;
            wp_dequeue_script('comment-reply');
        }, 100);
    }
    if ($on('self_pingbacks')) {
        add_action('pre_ping', function (&$links) {
            $home = get_option('home');
            foreach ($links as $k => $v) if (strpos($v, $home) === 0) unset($links[$k]);
        });
    }
    if ($on('disable_comments')) {
        add_action('admin_init', function () {
            foreach (get_post_types() as $pt) {
                if (post_type_supports($pt, 'comments')) {
                    remove_post_type_support($pt, 'comments');
                    remove_post_type_support($pt, 'trackbacks');
                }
            }
            remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        });
        add_filter('comments_open', '__return_false', 20, 2);
        add_filter('pings_open', '__return_false', 20, 2);
        add_filter('comments_array', '__return_empty_array', 10, 2);
        add_action('admin_menu', function () {
            remove_menu_page('edit-comments.php');
            remove_submenu_page('options-general.php', 'options-discussion.php');
        });
        add_action('wp_before_admin_bar_render', function () {
            global $wp_admin_bar;
            if ($wp_admin_bar) $wp_admin_bar->remove_menu('comments');
        }, 99);
        add_action('init', function () {
            if (is_admin_bar_showing()) remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
        });
    }

    // ───── پیشخوان ─────
    $hb = $o['heartbeat'];
    if ($hb === 'off' || $hb === 'editor') {
        add_action('init', function () use ($hb) {
            global $pagenow;
            if ($hb === 'editor' && in_array($pagenow, ['post.php', 'post-new.php'], true)) return;
            wp_deregister_script('heartbeat');
        }, 1);
    } elseif ($hb === 'slow') {
        $freq = max(15, min(120, (int) $o['heartbeat_freq']));
        add_filter('heartbeat_settings', function ($settings) use ($freq) {
            $settings['interval'] = $freq;
            return $settings;
        });
    }
    if ($on('dashboard_widgets')) {
        $pick = (array) $o['dashboard_widgets_pick'];
        add_action('wp_dashboard_setup', function () use ($pick) {
            foreach ($pick as $id) {
                if ($id === 'welcome_panel') { remove_action('welcome_panel', 'wp_welcome_panel'); continue; }
                foreach (['normal', 'side', 'column3', 'column4'] as $ctx) remove_meta_box($id, 'dashboard', $ctx);
            }
        }, 99);
    }
    if ($on('view_transitions')) {
        add_action('admin_enqueue_scripts', function () {
            wp_dequeue_style('wp-view-transitions-admin');
            wp_deregister_style('wp-view-transitions-admin');
        });
    }
    if ($on('command_palette')) {
        add_action('admin_enqueue_scripts', function () {
            wp_dequeue_script('wp-core-commands');
            wp_deregister_script('wp-core-commands');
        });
        add_action('admin_bar_menu', function ($bar) { $bar->remove_node('command-palette'); }, 999);
        add_action('enqueue_block_editor_assets', function () {
            wp_add_inline_style('wp-components', '.editor-header__center .editor-document-bar__shortcut{display:none!important}');
        });
    }
    if ($on('revisions')) {
        $num = (int) $o['revisions_num'];
        add_filter('wp_revisions_to_keep', function () use ($num) { return $num; }, 10, 0);
    }
    if ($on('autosave')) {
        $sec = (int) $o['autosave_seconds'];
        add_filter('autosave_interval', function () use ($sec) { return $sec; });
    }
    if ($on('pdf_thumbnails')) {
        add_filter('fallback_intermediate_image_sizes', '__return_empty_array');
    }

    mb_apply_more($o, $on);
}
add_action('plugins_loaded', 'mb_apply_features', 1);
