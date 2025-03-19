<?php defined( 'ABSPATH' ) || exit;

global $post;
$post_id = $post->ID;

// Get the saved setting information.
$wqpo_skeys = apply_filters('wqpo_modify_setting_keys', ['skulabel', 'skulabelsuffix', 'skusuffix', 'itemizeprefix', 'itemizesuffix', 'itemize', 'replace', 'type']);
foreach ($wqpo_skeys as $wqpo_skey) {
    ${'setting_' . $wqpo_skey} = get_post_meta($post_id, 'wqpo_s' . $wqpo_skey, true);
}
$setting_type = isset($setting_type) ? $setting_type : 'normal';
?>
<?php do_action('wqpo_before_admin_setting_fields', $post_id); ?>
<div class="wqpo-admin-field wqpo-admin-sskulabel">
    <label for="wqpo_sskulabel">SKU Label</label>
    <input type="text" name="wqpo_sskulabel" placeholder="PN" value="<?php echo esc_attr( !empty($setting_skulabel) || isset($setting_skulabel) ? $setting_skulabel : 'PN' ); ?>">
</div>
<div class="wqpo-admin-field wqpo-admin-sskulabelsuffix">
    <label for="wqpo_sskulabelsuffix">SKU Label Suffix</label>
    <input type="text" name="wqpo_sskulabelsuffix" placeholder=":" value="<?php echo esc_attr( !empty($setting_skulabelsuffix) || isset($setting_skulabelsuffix) ? $setting_skulabelsuffix : ':' ); ?>">
</div>
<div class="wqpo-admin-field wqpo-admin-sskusuffix">
    <label for="wqpo_sskusuffix">SKU Suffix</label>
    <input type="text" name="wqpo_sskusuffix" placeholder="," value="<?php echo esc_attr( !empty($setting_skusuffix) || isset($setting_skusuffix) ? $setting_skusuffix : ',' ); ?>">
</div>
<div class="wqpo-admin-field wqpo-admin-sitemizeprefix">
    <label for="wqpo_sitemizeprefix">Itemization Prefix</label>
    <input type="text" name="wqpo_sitemizeprefix" placeholder=" (" value="<?php echo esc_attr( !empty($setting_itemizeprefix) || isset($setting_itemizeprefix) ? $setting_itemizeprefix : ' (' ); ?>">
</div>
<div class="wqpo-admin-field wqpo-admin-sitemizesuffix">
    <label for="wqpo_sitemizesuffix">Itemization Suffix</label>
    <input type="text" name="wqpo_sitemizesuffix" placeholder=")" value="<?php echo esc_attr( !empty($setting_itemizesuffix) || isset($setting_itemizesuffix) ? $setting_itemizesuffix : ')' ); ?>">
</div>
<?php do_action('wqpo_after_admin_setting_text_fields', $post_id); ?>
<div class="wqpo-admin-field wqpo-admin-sitemize">
    <label for="wqpo_sitemize">Itemize Prices in Cart</label>
    <input type="checkbox" name="wqpo_sitemize" <?php checked($setting_itemize, 'on'); ?>>
</div>
<div class="wqpo-admin-field wqpo-admin-sreplace">
    <label for="wqpo_sreplace">Replace Product Price</label>
    <input type="checkbox" name="wqpo_sreplace" <?php checked($setting_replace, 'on'); ?>>
</div>
<?php do_action('wqpo_after_admin_setting_checkbox_fields', $post_id); ?>
<div class="wqpo-admin-field wqpo-admin-stype">
    <label for="wqpo_stype">Field Size</label>
    <select name="wqpo_stype">
        <option value="normal" <?php selected($setting_type, 'normal'); ?>>Normal</option>
        <option value="small" <?php selected($setting_type, 'small'); ?>>Small</option>
    </select>
</div>
<?php do_action('wqpo_after_admin_setting_fields', $post_id); ?>
