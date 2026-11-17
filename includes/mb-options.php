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

/** گزینه‌های یک امکان از نوع checks (ثابت یا پویا) */
function mb_option_choices($def) {
    if (!empty($def['options_cb']) && function_exists($def['options_cb'])) return call_user_func($def['options_cb']);
    return isset($def['options']) ? $def['options'] : [];
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
    if ($type === 'text') return sanitize_text_field((string) $value);
    if ($type === 'textarea') {
        $lines = preg_split('/\r\n|\r|\n/', (string) $value);
        $out = [];
        foreach ($lines as $l) {
            $l = trim(sanitize_text_field($l));
            if ($l !== '') $out[$l] = $l;
        }
        return implode("\n", array_slice(array_values($out), 0, 5000));
    }
    if ($type === 'checks' || $type === 'tree') {
        $arr = is_array($value) ? $value : json_decode((string) $value, true);
        if (!is_array($arr)) $arr = [];
        $arr = array_map('sanitize_text_field', array_map('strval', $arr));
        if ($type === 'checks') $arr = array_values(array_intersect($arr, array_keys(mb_option_choices($def))));
        else $arr = array_values(array_unique(array_slice($arr, 0, 2000)));
        return $arr;
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
