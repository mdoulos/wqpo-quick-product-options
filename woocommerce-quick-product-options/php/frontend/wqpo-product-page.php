<?php defined( 'ABSPATH' ) || exit;

global $product;
$product_id = $product->get_id();
if ( ! $product->is_purchasable() ) { return; }

// Retrieve the Total Number of Options
$total_option_count = get_post_meta($product_id, "wqpo-option-count", true);
$total_option_count = intval($total_option_count);
if ($total_option_count == 0) { return; } // If there are no options, stop here.

// Get the saved option information.
require(__DIR__ . '/wqpo-fetch-option-meta.php');
$replace_price = $setting_replace == 'on' ? ' wqpo-replace-price' : '';
$setting_type_class = isset($setting_type) && $setting_type !== 'normal' ? ' wqpo-' . $setting_type  . '-format': '';

// Retrieve the Total Number of Modifiers
$total_modifier_count = get_post_meta($product_id, "wqpo-modifier-count", true);
$total_modifier_count = intval($total_modifier_count);

// Get the saved modifier information.
require(__DIR__ . '/wqpo-fetch-modifier-meta.php');
?>
<section class="wqpo-product-options<?= $replace_price . $setting_type_class ?>">
    <?php
    // For each option, output the option HTML.
    for ($option_count = 0; $option_count < $total_option_count; $option_count++) { 
        $total_choice_count = get_post_meta($product_id, 'wqpo_o' . $option_count . '_choice_count', true);
        $total_choice_count = intval($total_choice_count);
        if ($total_choice_count == 0) { continue; } // If there are no choices, skip this option.
        $is_required = $option_requireds[$option_count] == 'on' ? ' required' : '';
        $option_class_array = $option_classnames[$option_count] ? array_map('trim', array_map('strtolower', explode(',', $option_classnames[$option_count]))) : [];
        $mod_tags = array_map('trim', array_map('strtolower', $mod_tags));
        $option_modifiers = $mod_tags ? array_intersect($option_class_array, $mod_tags) : [];        
        $option_modifiers_string = $option_modifiers ? 'data-modifiers="' . implode(',', $option_modifiers) . '" ' : '';

        // Get the saved choice information.
        require(__DIR__ . '/wqpo-fetch-choice-meta.php');

        // Output the option and its choices.
        switch ($option_types[$option_count]) {
            case 'number':
                require(__DIR__ . '/options/wqpo-option-number.php');
                break;
            case 'text':
                require(__DIR__ . '/options/wqpo-option-text.php');
                break;
            case 'textarea':
                require(__DIR__ . '/options/wqpo-option-textarea.php');
                break;
            default: // radio, dropdown, checkbox, etc.
                require(__DIR__ . '/wqpo-option-with-choices.php');
                break;
        }
    }

    // For each modifier, output the hidden modifier input.
    if ($total_modifier_count) {
        for ($modifier_count = 0; $modifier_count < $total_modifier_count; $modifier_count++) {
            $modifier_tag = isset($modifier_tags[$modifier_count]) ? $modifier_tags[$modifier_count] : '';
            $modifier_type = isset($modifier_types[$modifier_count]) ? $modifier_types[$modifier_count] : '';
            $modifier_enableddefault = isset($modifier_enableddefaults[$modifier_count]) ? $modifier_enableddefaults[$modifier_count] : '';
            $modifier_enabled = $modifier_enableddefault == 'on' ? 'yes' : 'no';
            if ($modifier_tag == '' || $modifier_type == '') { 
                continue; 
            } else if ($modifier_type == 'adjustment') {
                $modifier_value = $modifier_adjustments[$modifier_count];
            } else if ($modifier_type == 'multiplier') {
                $modifier_value = $modifier_multipliers[$modifier_count];
            } else { 
                $modifier_value = 0;
            } ?>
            <input type="hidden" class="wqpo-modifier" name="wqpo-modifier-<?= $modifier_count ?>" data-tag="<?= $modifier_tag ?>" data-type="<?= $modifier_type ?>" data-enableddefault="<?= $modifier_enableddefault ?>" data-enabled="<?= $modifier_enabled ?>" value="<?= $modifier_value ?>">
        <?php
        }
    }
    ?>
    <input type="hidden" id="wqpo-hidden-options" name="wqpo-hidden-options" value="">
    <input type="hidden" id="wqpo-hidden-tags" name="wqpo-hidden-tags" value="">
</section>
<?php
// Output the Price, if the product is not variable.
if ( ! $product->is_type('variable') ) { ?>
    <div class="wqpo-simple-price">
        <span class="price">
            <span class="woocommerce-Price-amount amount">
                <bdi><span class="woocommerce-Price-currencySymbol">$</span>0</bdi>
            </span>
        </span>
    </div>
<?php
} ?>