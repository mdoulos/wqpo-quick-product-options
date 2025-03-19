<?php defined( 'ABSPATH' ) || exit;

// Get the saved setting information.
$wqpo_skeys = apply_filters('wqpo_modify_setting_keys', ['skulabel', 'skulabelsuffix', 'skusuffix', 'itemizeprefix', 'itemizesuffix', 'itemize', 'replace', 'type']);
foreach ($wqpo_skeys as $wqpo_skey) {
    ${'setting_' . $wqpo_skey} = get_post_meta($product_id, 'wqpo_s' . $wqpo_skey, true);
}


// Get the saved option information.
$wqpo_okeys = apply_filters('wqpo_modify_option_keys', ['name', 'type', 'sort', 'classname', 'prefix', 'suffix', 'maxlength', 'required', 'showinput', 'usedescription', 'selectfirst', 'priceperchar', 'flatrate', 'showtext']);
foreach ($wqpo_okeys as $wqpo_okey) {
    ${'option_' . $wqpo_okey .'s'} = get_post_meta($product_id, 'wqpo_o' . $wqpo_okey . 's', true);
}