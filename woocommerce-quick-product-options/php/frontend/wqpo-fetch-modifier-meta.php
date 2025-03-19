<?php defined( 'ABSPATH' ) || exit;

// Get the saved modifier information.
$wqpo_mkeys = apply_filters('wqpo_modify_modifier_keys', ['tag', 'type', 'adjustment', 'multiplier', 'enableddefault']);
foreach ($wqpo_mkeys as $wqpo_mkey) {
    ${'modifier_' . $wqpo_mkey .'s'} = get_post_meta($product_id, 'wqpo_m' . $wqpo_mkey . 's', true);
}

$mod_tags = [];
$modifier_tag_array = $modifier_tags ? array_map('trim', array_map('strtolower', $modifier_tags)) : [];

if ($total_modifier_count) {
    for ($modifier_count = 0; $modifier_count < $total_modifier_count; $modifier_count++) {
        $modifier_tag = isset($modifier_tags[$modifier_count]) ? $modifier_tags[$modifier_count] : '';
        if ($modifier_tag == '') { 
            continue;
        } else {
            $mod_tags[] = $modifier_tag;
        }
    }
}