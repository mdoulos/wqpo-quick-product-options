<?php defined( 'ABSPATH' ) || exit;
$prefix_html = $option_prefixs[$option_count] ? '<span class="wqpo-prefix">' . $option_prefixs[$option_count] . '</span>' : '';
$suffix_html = $option_suffixs[$option_count] ? '<span class="wqpo-suffix">' . $option_suffixs[$option_count] . '</span>' : '';
$price_per_char = $option_priceperchars[$option_count] == 'on' ? ' wqpo-chargeperchar' : '';
$modifies_price = $choice_prices[0] > 0 ? ' wqpo-modifies-price' : '';
$maxlength = $option_maxlengths[$option_count] > 0 ? $option_maxlengths[$option_count] : 0;
$option_class = $option_classnames[$option_count] ? ' ' . str_replace(',', ' ', str_replace(' ', '', $option_classnames[$option_count])) : '';
$option_order = intval($option_sorts[$option_count]) ? ' wqpo-order-' . intval($option_sorts[$option_count]) : '';
?>

<div class="wqpo-option wqpo-text-option<?= $option_order . $modifies_price . $price_per_char . $option_class ?>" <?= $option_modifiers_string ?>data-option="<?= $option_count ?>" data-type="<?= $option_types[$option_count] ?>">
    <label for="wqpo-<?= $option_count ?>">
        <?php echo $prefix_html . '<span>' . $option_names[$option_count] . '</span>' . $suffix_html; ?>
        <?php if ( $option_requireds[$option_count] == 'on' ) { echo '<abbr class="required" title="required">*</abbr>'; } ?>
    </label>
    <div class="wqpo-option-text-container">
        <input type="text" name="wqpo-<?= $option_count ?>" id="wqpo-<?= $option_count ?>" maxlength="<?= $maxlength ?>" value=""<?= $is_required ?> data-price="<?= $choice_prices[0] ?>" oninput="textInputChanged(this)">
        <?php if ( $maxlength > 0 ) { ?>
            <span class="wqpo-text-char-count" data-maxlength="<?= $maxlength ?>">0 / <?= $maxlength ?></span>
        <?php } ?>
    </div>
    <?php if ( $choice_names[0] ) { ?>
        <div class="wqpo-option-single-description"><?= $choice_names[0] ?></div>
    <?php } ?>
    <input type="hidden" name="wqpo-<?= $option_count ?>-price" id="wqpo-<?= $option_count ?>-price" class="wqpo-option-price" data-price="0" value="0">
</div>
