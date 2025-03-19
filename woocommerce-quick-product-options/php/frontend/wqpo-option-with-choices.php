<?php defined( 'ABSPATH' ) || exit;
$prefix_html = $option_prefixs[$option_count] ? '<span class="wqpo-prefix">' . $option_prefixs[$option_count] . '</span>' : '';
$suffix_html = $option_suffixs[$option_count] ? '<span class="wqpo-suffix">' . $option_suffixs[$option_count] . '</span>' : '';
$option_class = $option_classnames[$option_count] ? ' ' . str_replace(',', ' ', str_replace(' ', '', $option_classnames[$option_count])) : '';
$select_first = $option_selectfirsts[$option_count] == 'on' ? ' wqpo-selectfirst' : '';
$option_order = intval($option_sorts[$option_count]) ? ' wqpo-order-' . intval($option_sorts[$option_count]) : '';
?>

<fieldset class="wqpo-option <?= 'wqpo-' . $option_types[$option_count] . '-option' ?><?= $option_order . $inputvisibility . $option_class . $select_first ?>" <?= $option_modifiers_string ?>data-option="<?= $option_count ?>" data-type="<?= $option_types[$option_count] ?>">
    <legend>
        <?php echo $prefix_html . '<span>' . $option_names[$option_count] . '</span>' . $suffix_html; ?>
        <?php if ( $option_requireds[$option_count] == 'on' ) { echo '<abbr class="required" title="required">*</abbr>'; } ?>
    </legend>
    <div class="wqpo-choices flex-column">
        <?php
        // These option types share a similar wrapping HTML structure.
        switch ($option_types[$option_count]) {
            case 'radio':
                require(__DIR__ . '/options/wqpo-option-radio.php');
                break;
            case 'dropdown':
                require(__DIR__ . '/options/wqpo-option-dropdown.php');
                break;
            case 'checkbox':
                require(__DIR__ . '/options/wqpo-option-checkbox.php');
                break;
            case 'swatches-radio':
                require(__DIR__ . '/options/wqpo-option-radio-swatch.php');
                break;
            case 'swatches-checkbox':
                require(__DIR__ . '/options/wqpo-option-checkbox-swatch.php');
                break;
            case 'color-radio':
                require(__DIR__ . '/options/wqpo-option-radio-color.php');
                break;
        }
        ?>
    </div>
    <input type="hidden" name="wqpo-<?= $option_count ?>-price" id="wqpo-<?= $option_count ?>-price" class="wqpo-option-price" data-price="0" value="">
</fieldset>

