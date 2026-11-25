<?php
/**
 * پاک‌سازی هنگام حذف افزونه (نه هنگام غیرفعال‌سازی).
 */
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

delete_option('mb_options');
delete_option('mb_net_learned');
delete_option('mb_stats');
delete_site_transient('mb_update_info');

global $wpdb;
$like1 = $wpdb->esc_like('_transient_mb_') . '%';
$like2 = $wpdb->esc_like('_transient_timeout_mb_') . '%';
$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s", $like1, $like2));

// اعلان‌های مخفی‌شده‌ی همه‌ی کاربران
delete_metadata('user', 0, 'mb_hidden_notices', '', true);

// کار زمان‌بندی‌شده‌ی پاک‌سازی اسپم
wp_clear_scheduled_hook('mb_spam_cleanup');

// فونت‌های گوگلی که روی هاست ذخیره شده بود
$up = wp_upload_dir();
$dir = trailingslashit($up['basedir']) . 'mb-fonts';
if (is_dir($dir)) {
    foreach ((array) glob($dir . '/*') as $f) { if (is_file($f)) @unlink($f); }
    @rmdir($dir);
}
