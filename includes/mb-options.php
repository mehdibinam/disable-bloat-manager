<?php
if (!defined('ABSPATH')) exit;

/** فهرست همه‌ی گزینه‌ها (امکان‌ها و فیلدهای داخل آن‌ها) به‌صورت id => تعریف */
function mb_option_index() {
    static $index = null;
    if ($index !== null) return $index;
    $index = [];
    foreach (mb_features() as $f) {
        $index[$f['id']] = $f;
        if (!empty($f['fields'])) {
            foreach ($f['fields'] as $field) {
                $field['parent'] = $f['id'];
                $index[$field['id']] = $field;
            }
        }
    }
    return $index;
}

function mb_defaults() {
    $d = [];
    foreach (mb_option_index() as $id => $def) {
        $d[$id] = isset($def['default']) ? $def['default'] : 0;
    }
    return $d;
}

function mb_get_options() {
    $saved = get_option(MB_OPT, []);
    if (!is_array($saved)) $saved = [];
    return array_merge(mb_defaults(), array_intersect_key($saved, mb_defaults()));
}

/** پاک‌سازی یک مقدار بر اساس نوع گزینه؛ اگر کلید ناشناخته باشد null برمی‌گرداند */
function mb_sanitize_value($id, $value) {
    $index = mb_option_index();
    if (!isset($index[$id])) return null;
    $def = $index[$id];
    $type = isset($def['type']) ? $def['type'] : 'toggle';
    if ($type === 'select') {
        $value = (string) $value;
        return isset($def['options'][$value]) ? $value : $def['default'];
    }
    if ($type === 'number') {
        $min = isset($def['min']) ? (int) $def['min'] : 0;
        $max = isset($def['max']) ? (int) $def['max'] : 100000;
        return max($min, min($max, (int) $value));
    }
    return empty($value) ? 0 : 1;
}

function mb_save_option($id, $value) {
    $clean = mb_sanitize_value($id, $value);
    if ($clean === null) return false;
    $opts = mb_get_options();
    $opts[$id] = $clean;
    update_option(MB_OPT, $opts);
    return true;
}

function mb_reset_options() {
    update_option(MB_OPT, mb_defaults());
}

/** روشن‌کردن گزینه‌های پیشنهادی، بدون دست‌زدن به بقیه */
function mb_apply_recommended() {
    $opts = mb_get_options();
    foreach (mb_features() as $f) {
        if (isset($f['rec'])) $opts[$f['id']] = $f['rec'];
    }
    update_option(MB_OPT, $opts);
}

/** وضعیت نیازمندی‌ها (مثلاً نصب‌بودن ووکامرس) */
function mb_plugin_active($file) {
    if (!function_exists('is_plugin_active')) require_once ABSPATH . 'wp-admin/includes/plugin.php';
    return is_plugin_active($file);
}
