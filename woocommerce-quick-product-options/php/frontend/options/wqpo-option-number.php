<?php defined( 'ABSPATH' ) || exit;
$prefix_html = $option_prefixs[$option_count] ? '<span class="wqpo-prefix">' . $option_prefixs[$option_count] . '</span>' : '';
$suffix_html = $option_suffixs[$option_count] ? '<span class="wqpo-suffix">' . $option_suffixs[$option_count] . '</span>' : '';
$flat_rate = $option_flatrates[$option_count] == 'on' ? ' wqpo-flatrate' : '';
$minimum_price = $choice_prices[0] * $choice_mins[0] > 0 ? $choice_prices[0] * $choice_mins[0] : 0;
$modifies_price = $choice_prices[0] > 0 ? ' wqpo-modifies-price' : '';
$option_class = $option_classnames[$option_count] ? ' ' . str_replace(',', ' ', str_replace(' ', '', $option_classnames[$option_count])) : '';
$option_order = intval($option_sorts[$option_count]) ? ' wqpo-order-' . intval($option_sorts[$option_count]) : '';
?>

<div class="wqpo-option wqpo-number-option<?= $option_order . $modifies_price . $flat_rate . $option_class ?>" <?= $option_modifiers_string ?>data-option="<?= $option_count ?>" data-type="<?= $option_types[$option_count] ?>">
    <label for="wqpo-<?= $option_count ?>">
        <?php echo $prefix_html . '<span>' . $option_names[$option_count] . '</span>' . $suffix_html; ?>
        <?php if ( $option_requireds[$option_count] == 'on' ) { echo '<abbr class="required" title="required">*</abbr>'; } ?>
    </label>
    <input type="number" name="wqpo-<?= $option_count ?>" id="wqpo-<?= $option_count ?>" min="<?= $choice_mins[0] ?>" max="<?= $choice_maxs[0] ?>" step="<?= $choice_steps[0] ?>" value="<?= $choice_mins[0] ?>"<?= $is_required ?> data-price="<?= $choice_prices[0] ?>" onchange="numberInputChanged(this)">
    <?php if ( $choice_names[0] ) { ?>
        <div class="wqpo-option-single-description"><?= $choice_names[0] ?></div>
    <?php } ?>
    <input type="hidden" name="wqpo-<?= $option_count ?>-price" id="wqpo-<?= $option_count ?>-price" class="wqpo-option-price" data-price="<?= $minimum_price ?>" value="<?= $minimum_price ?>">
</div>