<?php
if (!defined('ABSPATH')) exit;

/* ───────────── آمار تأثیر (شمارنده‌های سبک) ───────────── */
function mb_stat_inc($k, $n = 1) {
    static $hooked = false;
    if (!$hooked) { $hooked = true; add_action('shutdown', 'mb_stats_flush'); }
    $GLOBALS['mb_stat_buf'][$k] = (isset($GLOBALS['mb_stat_buf'][$k]) ? $GLOBALS['mb_stat_buf'][$k] : 0) + $n;
    return null;
}
function mb_stats_flush() {
    if (empty($GLOBALS['mb_stat_buf'])) return;
    $s = get_option('mb_stats', []);
    if (!is_array($s)) $s = [];
    $d = gmdate('Y-m-d');
    foreach ($GLOBALS['mb_stat_buf'] as $k => $n) {
        $s['d'][$d][$k] = (isset($s['d'][$d][$k]) ? (int) $s['d'][$d][$k] : 0) + (int) $n;
    }
    if (!empty($s['d'])) { ksort($s['d']); $s['d'] = array_slice($s['d'], -30, null, true); }
    $GLOBALS['mb_stat_buf'] = [];
    update_option('mb_stats', $s, false);
}
/** مجموع ۳۰ روز اخیر و مجموع هر روز در ۱۴ روز اخیر */
function mb_stats_summary() {
    $s = get_option('mb_stats', []);
    $d = is_array($s) && !empty($s['d']) ? $s['d'] : [];
    $tot = ['http' => 0, 'fw' => 0, 'login' => 0, 'spam' => 0, 'assets' => 0];
    foreach ($d as $day) foreach ($tot as $k => $_) $tot[$k] += isset($day[$k]) ? (int) $day[$k] : 0;
    $days = [];
    for ($i = 13; $i >= 0; $i--) {
        $key = gmdate('Y-m-d', time() - $i * DAY_IN_SECONDS);
        $days[] = ['d' => $key, 'n' => isset($d[$key]) ? array_sum(array_map('intval', $d[$key])) : 0];
    }
    return ['totals' => $tot, 'days' => $days];
}

/* ───────────── رنگ‌ها (برای پالت پنل مدیریت) ───────────── */
function mb_hex_rgb($hex) {
    $hex = ltrim($hex, '#');
    if (strlen($hex) === 3) $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    return [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];
}
function mb_shade($hex, $pct) { // pct مثبت: روشن‌تر، منفی: تیره‌تر
    $rgb = mb_hex_rgb($hex);
    foreach ($rgb as &$c) { $c = (int) max(0, min(255, round($pct >= 0 ? $c + (255 - $c) * $pct / 100 : $c * (100 + $pct) / 100))); }
    return sprintf('#%02x%02x%02x', $rgb[0], $rgb[1], $rgb[2]);
}
function mb_contrast($hex) {
    list($r, $g, $b) = mb_hex_rgb($hex);
    return (0.299 * $r + 0.587 * $g + 0.114 * $b) > 150 ? '#1d2327' : '#ffffff';
}
function mb_palette_css($o) {
    $c1 = $o['palette_c1']; $c2 = $o['palette_c2']; $c3 = $o['palette_c3']; $c4 = $o['palette_c4'];
    $t1 = mb_contrast($c1); $t2 = mb_contrast($c2); $t3 = mb_contrast($c3);
    $rgb2 = implode(',', mb_hex_rgb($c2));
    return ":root{--wp-admin-theme-color:$c2;--wp-admin-theme-color--rgb:$rgb2;--wp-admin-theme-color-darker-10:" . mb_shade($c2, -10) . ";--wp-admin-theme-color-darker-20:" . mb_shade($c2, -20) . ";}
a{color:$c4}a:hover,a:active,a:focus{color:" . mb_shade($c4, -20) . "}
#adminmenu,#adminmenuback,#adminmenuwrap{background:$c1}
#adminmenu a,#adminmenu div.wp-menu-image:before{color:$t1}
#adminmenu li.menu-top:hover,#adminmenu li.opensub>a.menu-top,#adminmenu li>a.menu-top:focus{background:" . mb_shade($c1, 12) . ";color:$t1}
#adminmenu li.current a.menu-top,#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,#adminmenu li.wp-has-current-submenu .wp-submenu .wp-submenu-head{background:$c2;color:$t2}
#adminmenu li.current a.menu-top div.wp-menu-image:before,#adminmenu li.wp-has-current-submenu a div.wp-menu-image:before{color:$t2}
#adminmenu .wp-submenu,#adminmenu .wp-has-current-submenu .wp-submenu,#adminmenu a.wp-has-current-submenu:focus+.wp-submenu{background:" . mb_shade($c1, -25) . "}
#adminmenu .wp-submenu a{color:" . mb_shade($t1, $t1 === '#ffffff' ? -25 : 25) . "}
#adminmenu .wp-submenu li.current a,#adminmenu .wp-submenu a:hover,#adminmenu .wp-submenu a:focus{color:$c4}
#adminmenu .awaiting-mod,#adminmenu .update-plugins,#wpadminbar #wp-admin-bar-comments .ab-icon:before{background:$c3;color:$t3}
#wpadminbar{background:$c1;color:$t1}#wpadminbar .ab-item,#wpadminbar a.ab-item,#wpadminbar>#wp-toolbar span.ab-label,#wpadminbar>#wp-toolbar span.noticon{color:$t1}
#wpadminbar .ab-top-menu>li:hover>.ab-item,#wpadminbar .ab-top-menu>li.hover>.ab-item,#wpadminbar .ab-top-menu>li>.ab-item:focus{background:" . mb_shade($c1, 12) . ";color:$c4}
.wp-core-ui .button-primary{background:$c2;border-color:$c2;color:$t2}.wp-core-ui .button-primary:hover{background:" . mb_shade($c2, -12) . ";border-color:" . mb_shade($c2, -12) . ";color:$t2}
input[type=checkbox]:checked::before{color:$c2}.wp-core-ui .button,.wp-core-ui .button-secondary{color:$c2;border-color:$c2}";
}

/* ───────────── اعتبارسنجی موبایل ایران ───────────── */
function mb_ir_mobile($v) {
    $v = strtr(trim((string) $v), ['۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9', '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9']);
    $v = preg_replace('/[^0-9+]/', '', $v);
    $v = preg_replace('/^(\+98|0098|98)/', '0', $v);
    if (preg_match('/^9[0-9]{9}$/', $v)) $v = '0' . $v;
    return preg_match('/^09[0-9]{9}$/', $v) ? $v : '';
}

/* ───────────── درون‌ریزی/تکثیر/برون‌بری: ابزارها ───────────── */
function mb_wxr_cdata($s) { return '<![CDATA[' . str_replace(']]>', ']]]]><![CDATA[>', (string) $s) . ']]>'; }

function mb_apply_extra($o, $on) {
    $types = function ($key) use ($o) { return array_values(array_filter((array) $o[$key], 'post_type_exists')); };

    /* ── تصاویر و بارگذاری ── */
    if ($on('lazy_load_off')) { add_filter('wp_lazy_loading_enabled', '__return_false', 99); add_filter('wp_img_tag_add_loading_attr', '__return_false', 99); }
    if ($on('speculative_off')) add_filter('wp_speculation_rules_configuration', '__return_null');
    if ($on('srcset_off')) { add_filter('wp_calculate_image_srcset', '__return_false', 99); add_filter('max_srcset_image_width', function () { return 1; }, 99); }
    if ($on('wp_cron_off') && !defined('DISABLE_WP_CRON')) define('DISABLE_WP_CRON', true);
    if ($on('login_bg') && $o['login_bg_url'] !== '') {
        $bg = esc_url($o['login_bg_url']);
        add_action('login_enqueue_scripts', function () use ($bg) {
            echo '<style>body.login{background:#111 url("' . $bg . '") center/cover no-repeat fixed}.login form{box-shadow:0 8px 30px rgba(0,0,0,.35)}.login #backtoblog a,.login #nav a{color:#fff;text-shadow:0 1px 2px rgba(0,0,0,.6)}</style>';
        });
    }
    if ($on('elementor_search_param')) {
        add_action('template_redirect', function () {
            if (!is_admin() && isset($_GET['e_search_props']) && (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] === 'GET')) {
                wp_safe_redirect(remove_query_arg('e_search_props'), 301);
                exit;
            }
        }, 1);
    }
    if ($on('cf7_smart')) {
        $needed = function () {
            $p = get_post();
            if (!$p) return false;
            if (has_shortcode($p->post_content, 'contact-form-7') || strpos($p->post_content, 'contact-form-7/contact-form-selector') !== false) return true;
            $e = get_post_meta($p->ID, '_elementor_data', true);
            return is_string($e) && (strpos($e, 'contact-form-7') !== false || strpos($e, 'wpcf7') !== false);
        };
        add_filter('wpcf7_load_js', function ($l) use ($needed) { return $needed(); }, 99);
        add_filter('wpcf7_load_css', function ($l) use ($needed) { return $needed(); }, 99);
    }

    /* ── ضد اسپم دیدگاه ── */
    if ($on('comment_url_field')) {
        add_filter('comment_form_default_fields', function ($f) { unset($f['url']); return $f; });
        add_filter('preprocess_comment', function ($d) { $d['comment_author_url'] = ''; return $d; }, 1);
    }
    if ($on('comment_honeypot')) {
        $field = function () {
            echo '<p class="mb-hp" style="position:absolute;left:-9999px;top:-9999px" aria-hidden="true"><label>لطفاً این کادر را خالی بگذارید <input type="text" name="mb_hp" value="" tabindex="-1" autocomplete="off"></label></p><input type="hidden" name="mb_t" value=""><script>(function(){var f=document.querySelector("input[name=mb_t]");if(f)f.value=Date.now()})();</script>';
        };
        add_action('comment_form_after_fields', $field);
        add_action('comment_form_logged_in_after', $field);
        add_filter('preprocess_comment', function ($d) {
            if (current_user_can('moderate_comments')) return $d;
            $t = isset($_POST['mb_t']) ? (int) $_POST['mb_t'] : 0;
            if (!empty($_POST['mb_hp']) || ($t > 0 && (microtime(true) * 1000 - $t) < 3000)) {
                mb_stat_inc('spam');
                wp_die('دیدگاه شما رد شد.', 'خطا', ['response' => 403, 'back_link' => true]);
            }
            return $d;
        }, 2);
    }
    if ($on('comment_persian_name')) {
        add_filter('preprocess_comment', function ($d) {
            if (is_user_logged_in() || $d['comment_type'] === 'pingback' || $d['comment_type'] === 'trackback') return $d;
            if (!preg_match('/^[\x{0600}-\x{06FF}\x{200c}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}\s]+$/u', (string) $d['comment_author'])) {
                mb_stat_inc('spam');
                wp_die('لطفاً نام خود را با حروف فارسی بنویسید.', 'خطا', ['response' => 400, 'back_link' => true]);
            }
            return $d;
        }, 3);
    }

    /* ── ویرایشگر: بلوک‌ها ── */
    if (!empty($o['blocks_disabled'])) {
        $dis = (array) $o['blocks_disabled'];
        add_filter('allowed_block_types_all', function ($allowed) use ($dis) {
            $base = $allowed === true ? array_keys(WP_Block_Type_Registry::get_instance()->get_all_registered()) : (array) $allowed;
            return array_values(array_diff($base, $dis));
        }, 99);
    }

    /* ── ووکامرس ── */
    if ($on('wc_default_styles_off')) add_filter('woocommerce_enqueue_styles', '__return_empty_array');
    if ($on('wc_hide_sku')) add_filter('wc_product_sku_enabled', function ($e) { return is_admin() ? $e : false; });
    if ($on('wc_product_tags_off')) {
        add_action('init', function () { unregister_taxonomy_for_object_type('product_tag', 'product'); }, 100);
        add_action('admin_menu', function () { remove_submenu_page('edit.php?post_type=product', 'edit-tags.php?taxonomy=product_tag&post_type=product'); }, 999);
    }
    if ($on('wc_coupons')) {
        add_filter('woocommerce_coupons_enabled', '__return_false');
        add_action('admin_menu', function () {
            remove_submenu_page('woocommerce', 'edit.php?post_type=shop_coupon');
            remove_submenu_page('woocommerce-marketing', 'edit.php?post_type=shop_coupon');
        }, 9999);
    }
    if ($on('wc_checkout_fields')) {
        $rm = (array) $o['wc_checkout_fields_pick'];
        add_filter('woocommerce_checkout_fields', function ($fields) use ($rm) {
            foreach ($fields as $sec => $list) foreach ($rm as $k) { if (isset($fields[$sec][$k])) unset($fields[$sec][$k]); }
            return $fields;
        }, 99);
    }
    if ($on('wc_no_payment')) add_filter('woocommerce_cart_needs_payment', '__return_false');

    /* ── منوی مربوط به امکان‌های خاموش‌شده خودکار پنهان می‌شود ── */
    $hide = [];
    if ($on('elementor_ai')) $hide[] = [['elementor', 'edit.php?post_type=elementor_library'], '#(^|[^a-z])ai([^a-z]|$)#i'];
    if ($on('elementor_mcp')) $hide[] = [['elementor', 'edit.php?post_type=elementor_library'], '#mcp|agent#i'];
    if ($on('jetpack_blaze')) $hide[] = [['jetpack'], '#blaze#i'];
    if ($hide) {
        add_action('admin_menu', function () use ($hide) {
            global $submenu;
            foreach ($hide as $h) {
                foreach ($h[0] as $parent) {
                    foreach ((array) (isset($submenu[$parent]) ? $submenu[$parent] : []) as $it) {
                        if (!isset($it[2])) continue;
                        if (preg_match($h[1], $it[2]) || preg_match($h[1], mb_clean_title(isset($it[0]) ? $it[0] : ''))) remove_submenu_page($parent, $it[2]);
                    }
                }
            }
        }, 99999);
    }

    /* ── امکانات ویژه ── */
    if ($on('page_categories')) add_action('init', function () { register_taxonomy_for_object_type('category', 'page'); });

    if ($on('editor_height')) {
        $h = (int) $o['editor_height_px'];
        add_filter('tiny_mce_before_init', function ($i) use ($h) { $i['height'] = $h; return $i; });
        add_filter('wp_editor_settings', function ($s) use ($h) { $s['editor_height'] = $h; return $s; });
        add_action('admin_head', function () use ($h) { echo '<style>#wp-content-editor-container .wp-editor-area{min-height:' . $h . 'px}</style>'; });
    }

    if ($on('thumb_column')) {
        foreach ($types('thumb_column_types') as $t) {
            $cf = $t === 'page' ? 'manage_pages_columns' : ($t === 'post' ? 'manage_posts_columns' : "manage_{$t}_posts_columns");
            $af = $t === 'page' ? 'manage_pages_custom_column' : ($t === 'post' ? 'manage_posts_custom_column' : "manage_{$t}_posts_custom_column");
            add_filter($cf, function ($cols) {
                if (isset($cols['thumb'])) return $cols;
                $new = [];
                foreach ($cols as $k => $v) { $new[$k] = $v; if ($k === 'cb') $new['mb_thumb'] = 'تصویر'; }
                return isset($new['mb_thumb']) ? $new : array_merge(['mb_thumb' => 'تصویر'], $cols);
            });
            add_action($af, function ($col, $id) { if ($col === 'mb_thumb') echo has_post_thumbnail($id) ? get_the_post_thumbnail($id, [50, 50], ['style' => 'width:50px;height:50px;object-fit:cover;border-radius:6px']) : '—'; }, 10, 2);
        }
        add_action('admin_head', function () { echo '<style>.column-mb_thumb{width:64px}</style>'; });
    }

    if ($on('dup_posts')) {
        $dt = $types('dup_posts_types');
        $link = function ($actions, $post) use ($dt) {
            if (in_array($post->post_type, $dt, true) && current_user_can('edit_post', $post->ID)) {
                $url = wp_nonce_url(admin_url('admin.php?action=mb_dup_post&post=' . (int) $post->ID), 'mb_dup_' . (int) $post->ID);
                $actions['mb_dup'] = '<a href="' . esc_url($url) . '">تکثیر</a>';
            }
            return $actions;
        };
        add_filter('post_row_actions', $link, 10, 2);
        add_filter('page_row_actions', $link, 10, 2);
        add_action('admin_action_mb_dup_post', function () use ($dt) {
            $id = isset($_GET['post']) ? absint($_GET['post']) : 0;
            check_admin_referer('mb_dup_' . $id);
            $p = get_post($id);
            if (!$p || !in_array($p->post_type, $dt, true) || !current_user_can('edit_post', $id)) wp_die('اجازه‌ی این کار را ندارید.', '', ['response' => 403]);
            $new = wp_insert_post(wp_slash([
                'post_title' => $p->post_title . ' (کپی)', 'post_content' => $p->post_content, 'post_excerpt' => $p->post_excerpt, 'post_status' => 'draft',
                'post_type' => $p->post_type, 'post_parent' => $p->post_parent, 'menu_order' => $p->menu_order, 'comment_status' => $p->comment_status,
                'ping_status' => $p->ping_status, 'post_author' => get_current_user_id(),
            ]), true);
            if (is_wp_error($new)) wp_die(esc_html($new->get_error_message()));
            foreach (get_object_taxonomies($p->post_type) as $tax) wp_set_object_terms($new, wp_get_object_terms($id, $tax, ['fields' => 'ids']), $tax);
            foreach (get_post_meta($id) as $k => $vals) {
                if (in_array($k, ['_edit_lock', '_edit_last', '_wp_old_slug', '_wp_old_date', '_elementor_css', '_elementor_element_cache'], true)) continue;
                foreach ($vals as $v) add_post_meta($new, $k, wp_slash(maybe_unserialize($v)));
            }
            wp_safe_redirect(admin_url('edit.php?post_type=' . $p->post_type . '&mb_dup=1'));
            exit;
        });
        add_action('admin_notices', function () { if (isset($_GET['mb_dup'])) echo '<div class="notice notice-success is-dismissible mb-keep"><p>کپی به‌صورت پیش‌نویس ساخته شد.</p></div>'; });
    }

    if ($on('dup_menus')) {
        add_action('admin_post_mb_dup_menu', function () {
            $mid = isset($_GET['menu']) ? absint($_GET['menu']) : 0;
            check_admin_referer('mb_dup_menu_' . $mid);
            if (!current_user_can('edit_theme_options')) wp_die('اجازه‌ی این کار را ندارید.', '', ['response' => 403]);
            $menu = wp_get_nav_menu_object($mid);
            if (!$menu) wp_die('منو پیدا نشد.');
            $new = wp_create_nav_menu($menu->name . ' (کپی)');
            if (is_wp_error($new)) wp_die(esc_html($new->get_error_message()));
            $map = [];
            $items = wp_get_nav_menu_items($mid, ['post_status' => 'any']);
            foreach ((array) $items as $it) {
                $map[$it->db_id] = wp_update_nav_menu_item($new, 0, [
                    'menu-item-object-id' => $it->object_id, 'menu-item-object' => $it->object, 'menu-item-parent-id' => 0, 'menu-item-position' => $it->menu_order,
                    'menu-item-type' => $it->type, 'menu-item-title' => $it->title, 'menu-item-url' => $it->url, 'menu-item-description' => $it->description,
                    'menu-item-attr-title' => $it->attr_title, 'menu-item-target' => $it->target, 'menu-item-classes' => implode(' ', (array) $it->classes),
                    'menu-item-xfn' => $it->xfn, 'menu-item-status' => 'publish',
                ]);
            }
            foreach ((array) $items as $it) {
                if ($it->menu_item_parent && isset($map[$it->menu_item_parent], $map[$it->db_id]) && !is_wp_error($map[$it->db_id])) update_post_meta($map[$it->db_id], '_menu_item_menu_item_parent', (int) $map[$it->menu_item_parent]);
            }
            wp_safe_redirect(admin_url('nav-menus.php?action=edit&menu=' . (int) $new));
            exit;
        });
        add_action('admin_footer-nav-menus.php', function () {
            $mid = isset($_REQUEST['menu']) ? absint($_REQUEST['menu']) : (int) get_user_option('nav_menu_recently_edited');
            if (!$mid) return;
            $url = wp_nonce_url(admin_url('admin-post.php?action=mb_dup_menu&menu=' . $mid), 'mb_dup_menu_' . $mid);
            echo '<script>jQuery(function($){var b=$(".publishing-action").first();if(b.length)b.prepend(\'<a class="button mb-dup-menu" style="margin-left:6px" href="' . esc_url($url) . '">تکثیر منو</a>\');});</script>';
        });
    }

    if ($on('elementor_tinymce')) {
        add_filter('mce_buttons_2', function ($b) { $b[] = 'mb_elementor_listbox'; return $b; });
        add_filter('tiny_mce_before_init', function ($i) {
            $prev = !empty($i['setup']) ? $i['setup'] : 'null';
            $i['setup'] = 'function(ed){var p=' . $prev . ';if(typeof p==="function")p(ed);if(window.mbTinymceSetup)window.mbTinymceSetup(ed);}';
            return $i;
        });
        add_action('admin_print_footer_scripts', function () {
            if (!current_user_can('edit_posts')) return;
            $items = [];
            foreach (get_posts(['post_type' => 'elementor_library', 'posts_per_page' => 100, 'post_status' => 'publish']) as $t) {
                $items[] = ['text' => $t->post_title, 'value' => '[elementor-template id="' . (int) $t->ID . '"]'];
            }
            if (!$items) $items[] = ['text' => 'هیچ قالبی پیدا نشد', 'value' => ''];
            echo '<script>window.mbTinymceSetup=function(ed){ed.addButton("mb_elementor_listbox",{type:"listbox",text:"قالب المنتور",icon:"template",tooltip:"درج قالب المنتور",values:' . wp_json_encode($items) . ',onselect:function(){if(this.value()){ed.insertContent(this.value());}}});};</script>';
        }, 20);
    }

    if ($on('ajax_publish')) {
        $pt = $types('ajax_publish_types');
        add_action('wp_ajax_mb_ajax_publish', function () use ($pt) {
            $id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
            $form = isset($_POST['form_data']) ? wp_unslash($_POST['form_data']) : '';
            $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
            if (!$id || $form === '') wp_send_json_error(['message' => 'اطلاعات نوشته کامل نیست.']);
            if (!wp_verify_nonce($nonce, 'update-post_' . $id) && !wp_verify_nonce($nonce, 'add-post')) wp_send_json_error(['message' => 'نشست شما منقضی شده است. صفحه را یک‌بار رفرش کنید.'], 403);
            $post = get_post($id);
            if (!$post || !in_array($post->post_type, $pt, true)) wp_send_json_error(['message' => 'این نوع محتوا پشتیبانی نمی‌شود.']);
            if (!current_user_can('edit_post', $id)) wp_send_json_error(['message' => 'اجازه‌ی ویرایش این مورد را ندارید.'], 403);
            parse_str($form, $data);
            $orig = $_POST;
            $_POST = wp_slash($data);
            $_POST['post_ID'] = $id;
            if (current_user_can('publish_post', $id)) $_POST['publish'] = 'Publish';
            if (!function_exists('edit_post')) require_once ABSPATH . 'wp-admin/includes/post.php';
            $res = edit_post();
            $_POST = $orig;
            if (is_wp_error($res) || !$res) wp_send_json_error(['message' => is_wp_error($res) ? $res->get_error_message() : 'ذخیره انجام نشد.'], 500);
            $p = get_post($id);
            wp_send_json_success(['message' => 'ذخیره شد.', 'edit_link' => get_edit_post_link($id, 'raw'), 'view_link' => get_permalink($id), 'status' => $p->post_status, 'post_id' => $id]);
        });
        add_action('admin_enqueue_scripts', function ($hook) use ($pt) {
            if (!in_array($hook, ['post.php', 'post-new.php'], true)) return;
            $s = get_current_screen();
            if (!$s || !in_array($s->post_type, $pt, true) || (method_exists($s, 'is_block_editor') && $s->is_block_editor())) return;
            $js = 'jQuery(function($){var busy=false;$(document).on("click","#publish",function(e){e.preventDefault();if(busy)return;var $b=$(this),$f=$("#post"),id=$("#post_ID").val();if(!id){alert("شناسه پیدا نشد. صفحه را رفرش کنید.");return}'
                . 'if(window.tinyMCE)tinyMCE.triggerSave();busy=true;var old=$b.val();$b.val("در حال ذخیره...").prop("disabled",true);'
                . '$.post(ajaxurl,{action:"mb_ajax_publish",post_id:id,nonce:$("[name=_wpnonce]").val(),form_data:$f.serialize()}).done(function(r){busy=false;if(!r.success){alert((r.data&&r.data.message)||"خطا در ذخیره‌سازی.");$b.val(old).prop("disabled",false);return}'
                . '$("#message").remove();$("#wpbody-content").prepend(\'<div id="message" class="notice notice-success is-dismissible mb-keep"><p><strong>\'+r.data.message+\'</strong> <a href="\'+r.data.view_link+\'" target="_blank">مشاهده</a></p></div>\');'
                . 'if(r.data.edit_link&&history.replaceState)history.replaceState({},document.title,r.data.edit_link);if(r.data.edit_link)$f.attr("action",r.data.edit_link);'
                . '$b.val(r.data.status==="publish"?"به‌روزرسانی":old).prop("disabled",false).css({background:"#00a32a",borderColor:"#00a32a"});setTimeout(function(){$b.css({background:"",borderColor:""})},1200);'
                . '}).fail(function(){busy=false;alert("ارتباط با سرور برقرار نشد.");$b.val(old).prop("disabled",false)});});});';
            wp_add_inline_script('jquery', $js);
        });
    }

    if ($on('post_export')) {
        $et = $types('post_export_types');
        $link = function ($actions, $post) use ($et) {
            if (in_array($post->post_type, $et, true) && current_user_can('export') && current_user_can('edit_post', $post->ID)) {
                $url = wp_nonce_url(admin_url('admin.php?action=mb_export_post&post=' . (int) $post->ID), 'mb_exp_' . (int) $post->ID);
                $actions['mb_export'] = '<a href="' . esc_url($url) . '">برون‌بری XML</a>';
            }
            return $actions;
        };
        add_filter('post_row_actions', $link, 10, 2);
        add_filter('page_row_actions', $link, 10, 2);
        add_action('admin_action_mb_export_post', function () use ($et) {
            $id = isset($_GET['post']) ? absint($_GET['post']) : 0;
            check_admin_referer('mb_exp_' . $id);
            $p = get_post($id);
            if (!$p || !in_array($p->post_type, $et, true) || !current_user_can('export') || !current_user_can('edit_post', $id)) wp_die('اجازه‌ی این کار را ندارید.', '', ['response' => 403]);
            global $wpdb;
            $a = get_userdata($p->post_author);
            $x = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n" . '<rss version="2.0" xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:wp="http://wordpress.org/export/1.2/">' . "\n<channel>\n";
            $x .= '<title>' . esc_html(get_bloginfo('name')) . "</title>\n<link>" . esc_url(home_url()) . "</link>\n<description>" . esc_html(get_bloginfo('description')) . "</description>\n<pubDate>" . gmdate('D, d M Y H:i:s +0000') . "</pubDate>\n<language>" . esc_html(get_bloginfo('language')) . "</language>\n<wp:wxr_version>1.2</wp:wxr_version>\n<wp:base_site_url>" . esc_url(network_home_url()) . "</wp:base_site_url>\n<wp:base_blog_url>" . esc_url(home_url()) . "</wp:base_blog_url>\n";
            if ($a) $x .= '<wp:author><wp:author_id>' . (int) $a->ID . '</wp:author_id><wp:author_login>' . mb_wxr_cdata($a->user_login) . '</wp:author_login><wp:author_email>' . mb_wxr_cdata($a->user_email) . '</wp:author_email><wp:author_display_name>' . mb_wxr_cdata($a->display_name) . "</wp:author_display_name></wp:author>\n";
            $x .= "<item>\n<title>" . esc_html($p->post_title) . "</title>\n<link>" . esc_url(get_permalink($id)) . "</link>\n<pubDate>" . esc_html(mysql2date('D, d M Y H:i:s +0000', $p->post_date_gmt, false)) . "</pubDate>\n<dc:creator>" . mb_wxr_cdata($a ? $a->user_login : '') . "</dc:creator>\n<guid isPermaLink=\"false\">" . esc_url(get_the_guid($id)) . "</guid>\n<description></description>\n<content:encoded>" . mb_wxr_cdata($p->post_content) . "</content:encoded>\n<excerpt:encoded>" . mb_wxr_cdata($p->post_excerpt) . "</excerpt:encoded>\n";
            $x .= '<wp:post_id>' . $id . "</wp:post_id>\n<wp:post_date>" . mb_wxr_cdata($p->post_date) . "</wp:post_date>\n<wp:post_date_gmt>" . mb_wxr_cdata($p->post_date_gmt) . "</wp:post_date_gmt>\n<wp:post_modified>" . mb_wxr_cdata($p->post_modified) . "</wp:post_modified>\n<wp:post_modified_gmt>" . mb_wxr_cdata($p->post_modified_gmt) . "</wp:post_modified_gmt>\n<wp:comment_status>" . mb_wxr_cdata($p->comment_status) . "</wp:comment_status>\n<wp:ping_status>" . mb_wxr_cdata($p->ping_status) . "</wp:ping_status>\n<wp:post_name>" . mb_wxr_cdata($p->post_name) . "</wp:post_name>\n<wp:status>" . mb_wxr_cdata($p->post_status) . "</wp:status>\n<wp:post_parent>" . (int) $p->post_parent . "</wp:post_parent>\n<wp:menu_order>" . (int) $p->menu_order . "</wp:menu_order>\n<wp:post_type>" . mb_wxr_cdata($p->post_type) . "</wp:post_type>\n<wp:post_password>" . mb_wxr_cdata($p->post_password) . "</wp:post_password>\n<wp:is_sticky>" . (is_sticky($id) ? 1 : 0) . "</wp:is_sticky>\n";
            foreach (get_object_taxonomies($p->post_type) as $tax) {
                foreach ((array) wp_get_object_terms($id, $tax) as $t) $x .= '<category domain="' . esc_attr($tax) . '" nicename="' . esc_attr($t->slug) . '">' . mb_wxr_cdata($t->name) . "</category>\n";
            }
            $metas = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = %d", $id));
            foreach ((array) $metas as $m) {
                if ($m->meta_key === '_edit_lock') continue;
                $x .= '<wp:postmeta><wp:meta_key>' . mb_wxr_cdata($m->meta_key) . '</wp:meta_key><wp:meta_value>' . mb_wxr_cdata($m->meta_value) . "</wp:meta_value></wp:postmeta>\n";
            }
            $x .= "</item>\n</channel>\n</rss>\n";
            nocache_headers();
            header('Content-Type: application/xml; charset=UTF-8');
            header('Content-Disposition: attachment; filename="' . sanitize_file_name(($p->post_name ? $p->post_name : 'post-' . $id) . '-' . gmdate('Y-m-d') . '.xml') . '"');
            echo $x;
            exit;
        });
    }

    if ($o['media_mode'] === 'infinite') add_filter('media_library_infinite_scrolling', '__return_true');
    if ($o['media_mode'] === 'loadmore') add_filter('media_library_infinite_scrolling', '__return_false');
    if ($o['media_mode'] !== 'default') { $mpp = (int) $o['media_per_page']; add_filter('upload_per_page', function () use ($mpp) { return $mpp; }); }

    if ($on('vazir_front')) {
        $sel = trim(preg_replace('/[^a-zA-Z0-9\s,.#*:\[\]=\-_>+~"\'()]/', '', (string) $o['vazir_selectors']));
        if ($sel === '') $sel = 'body';
        add_action('wp_head', function () use ($sel) {
            $u = MB_URL . 'assets/fonts/';
            echo '<link rel="preload" href="' . esc_url($u . 'Vazirmatn-Regular.woff2') . '" as="font" type="font/woff2" crossorigin>';
            echo '<style id="mb-vazir">@font-face{font-family:"MB Vazirmatn";font-weight:400;font-display:swap;src:url("' . esc_url($u . 'Vazirmatn-Regular.woff2') . '") format("woff2")}@font-face{font-family:"MB Vazirmatn";font-weight:500;font-display:swap;src:url("' . esc_url($u . 'Vazirmatn-Medium.woff2') . '") format("woff2")}@font-face{font-family:"MB Vazirmatn";font-weight:700;font-display:swap;src:url("' . esc_url($u . 'Vazirmatn-Bold.woff2') . '") format("woff2")}' . $sel . '{font-family:"MB Vazirmatn",Tahoma,sans-serif!important}</style>';
        }, 5);
    }

    if ($on('reading_resume')) {
        add_action('wp_footer', function () {
            if (!is_singular('post')) return;
            echo '<script>(function(){try{var k="mb-read-"+location.pathname,s=parseInt(localStorage.getItem(k)||"0",10);if(!location.hash&&s>200)window.scrollTo({top:s,behavior:"instant"});var t;window.addEventListener("scroll",function(){clearTimeout(t);t=setTimeout(function(){localStorage.setItem(k,String(Math.round(window.scrollY)))},300)},{passive:true})}catch(e){}})();</script>';
        });
    }

    if ($on('copy_credit')) {
        $cfg = ['t' => (string) $o['copy_credit_text'], 'min' => (int) $o['copy_credit_min'], 'site' => get_bloginfo('name')];
        add_action('wp_footer', function () use ($cfg) {
            echo '<script>(function(c){document.addEventListener("copy",function(e){var s=window.getSelection();if(!s||!s.rangeCount)return;var txt=s.toString();if(txt.trim().length<c.min)return;var a=document.activeElement;if(a&&/INPUT|TEXTAREA/.test(a.tagName))return;'
                . 'var line=c.t.replace("{title}",document.title).replace("{url}",location.href).replace("{site}",c.site);var d=document.createElement("div");d.appendChild(s.getRangeAt(0).cloneContents());'
                . 'var esc=function(x){var p=document.createElement("p");p.textContent=x;return p.innerHTML};e.clipboardData.setData("text/html",d.innerHTML+"<br><br>"+esc(line));e.clipboardData.setData("text/plain",txt+"\n\n"+line);e.preventDefault()})})(' . wp_json_encode($cfg) . ');</script>';
        }, 999);
    }

    if ($on('elementor_clickable')) {
        $ctl = function ($el) {
            $el->start_controls_section('mb_click_section', ['label' => 'لینک کامل (مدیریت کارایی)', 'tab' => \Elementor\Controls_Manager::TAB_ADVANCED]);
            $el->add_control('mb_click_link', ['label' => 'لینک', 'type' => \Elementor\Controls_Manager::URL, 'dynamic' => ['active' => true], 'placeholder' => 'https://']);
            $el->end_controls_section();
        };
        foreach (['section', 'column', 'container'] as $type) {
            add_action("elementor/element/$type/section_advanced/after_section_end", $ctl, 10, 1);
            add_action("elementor/frontend/$type/before_render", function ($el) {
                $l = $el->get_settings_for_display('mb_click_link');
                if (empty($l['url'])) return;
                $el->add_render_attribute('_wrapper', 'class', 'mb-clickable');
                $el->add_render_attribute('_wrapper', 'data-mb-href', esc_url($l['url']));
                if (!empty($l['is_external'])) $el->add_render_attribute('_wrapper', 'data-mb-blank', '1');
            });
        }
        add_action('wp_footer', function () {
            echo '<style>.mb-clickable{cursor:pointer}</style><script>(function(){function go(el,ev){var u=el.getAttribute("data-mb-href");if(!u)return;if(ev&&(ev.ctrlKey||ev.metaKey||el.getAttribute("data-mb-blank")))window.open(u,"_blank","noopener");else location.href=u}'
                . 'document.addEventListener("click",function(e){var el=e.target.closest(".mb-clickable");if(!el||e.target.closest("a,button,input,select,textarea,label,summary,[role=button]"))return;go(el,e)});'
                . 'document.querySelectorAll(".mb-clickable").forEach(function(el){el.setAttribute("role","link");el.setAttribute("tabindex","0");el.addEventListener("keydown",function(e){if(e.key==="Enter"&&e.target===el)go(el,e)})})})();</script>';
        });
    }

    if ($on('iran_phone_elementor')) {
        add_action('elementor_pro/forms/validation', function ($record, $ajax) {
            foreach ((array) $record->get('fields') as $id => $f) {
                if (!isset($f['type']) || $f['type'] !== 'tel' || trim((string) $f['value']) === '') continue;
                if (mb_ir_mobile($f['value']) === '') $ajax->add_error($id, 'لطفاً شماره‌ی موبایل معتبر (مثل ۰۹۱۲۳۴۵۶۷۸۹) وارد کنید.');
            }
        }, 10, 2);
        add_action('wp_footer', function () {
            echo '<script>document.addEventListener("DOMContentLoaded",function(){document.querySelectorAll(\'.elementor-field-type-tel input[type="tel"]\').forEach(function(i){i.maxLength=11;i.addEventListener("input",function(){this.value=this.value.replace(/[۰-۹]/g,function(d){return "۰۱۲۳۴۵۶۷۸۹".indexOf(d)}).replace(/[^0-9]/g,"").substring(0,11)})})});</script>';
        });
    }
    if ($on('iran_phone_gf')) {
        add_filter('gform_field_validation', function ($result, $value, $form, $field) {
            if ($field->type === 'phone' && trim((string) $value) !== '' && mb_ir_mobile($value) === '') {
                $result['is_valid'] = false;
                $result['message'] = 'لطفاً شماره‌ی موبایل معتبر (مثل ۰۹۱۲۳۴۵۶۷۸۹) وارد کنید.';
            }
            return $result;
        }, 10, 4);
        add_filter('gform_pre_validation', function ($form) {
            foreach ($form['fields'] as $f) {
                if ($f->type === 'phone' && isset($_POST['input_' . $f->id])) $_POST['input_' . $f->id] = mb_ir_mobile($_POST['input_' . $f->id]) ?: $_POST['input_' . $f->id];
            }
            return $form;
        });
    }

    if ($on('admin_palette')) {
        add_action('admin_init', function () use ($o) {
            $v = substr(md5($o['palette_c1'] . $o['palette_c2'] . $o['palette_c3'] . $o['palette_c4']), 0, 8);
            wp_admin_css_color('mb_custom', 'پالت من', admin_url('admin-ajax.php?action=mb_colors_css&v=' . $v), [$o['palette_c1'], $o['palette_c2'], $o['palette_c3'], $o['palette_c4']], ['base' => '#fff', 'focus' => '#fff', 'current' => '#fff']);
        });
        add_action('wp_ajax_mb_colors_css', function () use ($o) {
            nocache_headers();
            header('Content-Type: text/css; charset=utf-8');
            header('Cache-Control: private, max-age=86400');
            echo mb_palette_css($o);
            exit;
        });
    }

    if ($on('plugin_logo_column')) {
        add_filter('manage_plugins_columns', function ($c) {
            $n = [];
            foreach ($c as $k => $v) { if ($k === 'name') $n['mb_logo'] = ''; $n[$k] = $v; }
            return isset($n['mb_logo']) ? $n : array_merge(['mb_logo' => ''], $c);
        });
        add_action('manage_plugins_custom_column', function ($col, $file, $data) {
            if ($col !== 'mb_logo') return;
            static $icons = null;
            if ($icons === null) {
                $icons = [];
                $t = get_site_transient('update_plugins');
                foreach (['response', 'no_update'] as $k) {
                    foreach ((array) (is_object($t) && isset($t->$k) ? $t->$k : []) as $f => $info) {
                        $i = isset($info->icons) ? (array) $info->icons : [];
                        $u = isset($i['svg']) ? $i['svg'] : (isset($i['2x']) ? $i['2x'] : (isset($i['1x']) ? $i['1x'] : ''));
                        if ($u) $icons[$f] = $u;
                    }
                }
            }
            $name = isset($data['Name']) ? $data['Name'] : $file;
            if (isset($icons[$file])) echo '<img src="' . esc_url($icons[$file]) . '" alt="" width="40" height="40" style="border-radius:8px;display:block">';
            else echo '<span style="display:grid;place-items:center;width:40px;height:40px;border-radius:8px;background:#124A53;color:#fff;font-weight:700">' . esc_html(function_exists('mb_substr') ? mb_substr(wp_strip_all_tags($name), 0, 1) : substr($name, 0, 1)) . '</span>';
        }, 10, 3);
        add_action('admin_head-plugins.php', function () { echo '<style>.plugins .column-mb_logo{width:56px;padding-right:8px}</style>'; });
    }

    if (!isset($o['mb_dash_widget']) || $on('mb_dash_widget')) {
        add_action('wp_dashboard_setup', function () {
            if (!current_user_can('manage_options')) return;
            wp_add_dashboard_widget('mb_dash', 'مدیریت کارایی وردپرس', 'mb_render_dash_widget');
        });
    }
}

/** ویجت داشبورد */
function mb_render_dash_widget() {
    $feats = mb_features();
    $o = mb_get_options();
    $active = 0;
    foreach ($feats as $f) {
        $t = isset($f['type']) ? $f['type'] : 'toggle';
        $v = $o[$f['id']];
        $on = $t === 'select' ? ($v !== $f['default']) : (in_array($t, ['checks', 'tree'], true) ? !empty($v) : (in_array($t, ['text', 'textarea', 'learned'], true) ? ($t !== 'learned' && $v !== '') : !empty($v)));
        if ($on) $active++;
    }
    $total = count($feats);
    $s = mb_stats_summary();
    $max = max(1, max(array_map(function ($d) { return $d['n']; }, $s['days'])));
    $labels = ['http' => 'درخواست خروجی مسدودشده', 'fw' => 'درخواست مشکوک مسدودشده', 'login' => 'ورود ناموفقِ قفل‌شده', 'spam' => 'دیدگاه اسپم ردشده', 'assets' => 'فایل خارجی حذف‌شده'];
    echo '<div class="mb-dw" dir="rtl" style="font-family:inherit"><p style="margin:0 0 8px"><strong>' . (int) $active . '</strong> امکان از ' . (int) $total . ' امکان فعال است.</p>';
    echo '<div style="height:8px;background:#e2e8ec;border-radius:99px;overflow:hidden;margin-bottom:12px"><i style="display:block;height:100%;width:' . (int) round($active / max(1, $total) * 100) . '%;background:#124A53"></i></div>';
    echo '<table style="width:100%;border-collapse:collapse">';
    foreach ($labels as $k => $l) echo '<tr><td style="padding:3px 0;color:#50575e">' . esc_html($l) . '</td><td style="text-align:left;font-weight:600">' . number_format_i18n($s['totals'][$k]) . '</td></tr>';
    echo '</table><p style="margin:10px 0 4px;color:#787c82;font-size:12px">رویدادهای مسدودشده در ۱۴ روز اخیر</p><div style="display:flex;align-items:flex-end;gap:3px;height:46px">';
    foreach ($s['days'] as $d) echo '<i title="' . esc_attr($d['d'] . ': ' . $d['n']) . '" style="flex:1;border-radius:3px 3px 0 0;background:' . ($d['n'] ? '#124A53' : '#e2e8ec') . ';height:' . max(4, (int) round($d['n'] / $max * 46)) . 'px"></i>';
    echo '</div>';
    $u = function_exists('mb_update_available') ? mb_update_available() : null;
    if ($u) echo '<p style="margin:12px 0 0;padding:8px 10px;background:#fff3d6;border-radius:8px">نسخه‌ی <strong>' . esc_html($u['version']) . '</strong> آماده‌ی به‌روزرسانی است. <a href="' . esc_url(mb_update_link()) . '">به‌روزرسانی</a></p>';
    echo '<p style="margin:12px 0 0"><a class="button button-primary" href="' . esc_url(admin_url('options-general.php?page=' . MB_SLUG)) . '">باز کردن تنظیمات</a></p></div>';
}
