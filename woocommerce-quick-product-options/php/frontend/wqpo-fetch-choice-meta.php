<?php defined( 'ABSPATH' ) || exit;

// Get the saved choice information.
$wqpo_ckeys = apply_filters('wqpo_modify_choice_keys', ['name', 'price', 'sort', 'sku', 'image', 'tag', 'showtag', 'hidetag', 'min', 'max', 'step', 'description', 'fillhex', 'borderhex', 'enablemodifier']);
foreach ($wqpo_ckeys as $wqpo_ckey) {
    ${'choice_' . $wqpo_ckey . 's'} = get_post_meta($product_id, 'wqpo_o' . $option_count . '_c' . $wqpo_ckey .'s', true);
}

$this_type = $option_types[$option_count];
$inputvisibility = (($this_type == 'radio' || $this_type == 'swatches-radio' || $this_type == 'swatches-checkbox') && $option_showinputs[$option_count] == 'off') ? ' wqpo-hideinputs' : '';