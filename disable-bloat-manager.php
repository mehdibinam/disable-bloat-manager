<?php
/**
 * Plugin Name: Disable Bloat Manager
 * Description: مجموعه‌ی دسته‌بندی‌شده برای غیرفعال‌کردن بخش‌های اضافه‌ی وردپرس، ووکامرس و افزونه‌ها.
 * Version: 2.0.0
 * Author: Mehdi Binam
 * Author URI: https://t.me/Mehdibinam
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: GPLv2 or later
 * License URI: 
 * Text Domain: disable-bloat-manager
 */
if (!defined('ABSPATH')) exit;

define('MB_VERSION', '2.0.0');
define('MB_FILE', __FILE__);
define('MB_DIR', plugin_dir_path(__FILE__));
define('MB_URL', plugin_dir_url(__FILE__));
define('MB_OPT', 'mb_options');
define('MB_SLUG', 'disable-bloat-manager');

require_once MB_DIR . 'includes/mb-registry.php';
require_once MB_DIR . 'includes/mb-registry-more.php';
require_once MB_DIR . 'includes/mb-registry-secure.php';
require_once MB_DIR . 'includes/mb-options.php';
require_once MB_DIR . 'includes/mb-features.php';
require_once MB_DIR . 'includes/mb-features-more.php';
require_once MB_DIR . 'includes/mb-features-secure.php';
require_once MB_DIR . 'includes/mb-admin.php';

register_activation_hook(__FILE__, function () {
    if (get_option(MB_OPT, null) === null) {
        add_option(MB_OPT, [], '', 'yes');
    }
});

// setting
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
    $url = admin_url('admin.php?page=' . MB_SLUG);
    array_unshift($links, '<a href="' . esc_url($url) . '">تنظیمات</a>');
    return $links;
});
