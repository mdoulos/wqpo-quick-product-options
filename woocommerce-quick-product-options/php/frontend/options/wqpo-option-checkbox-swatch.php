<?php defined( 'ABSPATH' ) || exit;

// Output the Choices
for ($choice_count = 0; $choice_count < $total_choice_count; $choice_count++) {
    $modifies_price = $choice_prices[$choice_count] > 0 ? ' wqpo-modifies-price' : '';
    $tags = $choice_tags[$choice_count] ? ' ' . str_replace(', ', ' ', $choice_tags[$choice_count]) : '';
    $showtags = $choice_showtags[$choice_count] ? ' data-showtags="' . esc_attr($choice_showtags[$choice_count]) . '"' : '';
    $hidetags = $choice_hidetags[$choice_count] ? ' data-hidetags="' . esc_attr($choice_hidetags[$choice_count]) . '"' : '';
    $enable_modifiers = $choice_enablemodifiers[$choice_count] ? ' data-enablemodifiers="' . esc_attr($choice_enablemodifiers[$choice_count]) . '"' : '';

    $choice_tag_array = $choice_tags[$choice_count] ? array_map('trim', array_map('strtolower', explode(',', $choice_tags[$choice_count]))) : [];
    $mod_tags = array_map('trim', array_map('strtolower', $mod_tags));
    $choice_modifiers = $mod_tags ? array_intersect($choice_tag_array, $mod_tags) : [];
    $choice_modifiers = array_unique(array_merge($choice_modifiers, $option_modifiers)); 
    $choice_modifiers_string = $choice_modifiers ? ' data-modifiers="' . implode(',', $choice_modifiers) . '"' : '';

    // Define the Choice ID and Sort Order
    $choice_id = 'wqpo-cb-' . $option_count . '-' . $choice_count; // Example: wqpo-1-3 where 1 is the option number and 3 is the choice number.
    $choice_order = intval($choice_sorts[$choice_count]) ? ' wqpo-order-' . intval($choice_sorts[$choice_count]) : '';

    // Output the Choice HTML ?>
    <div class="<?= 'wqpo-choice' . $choice_order ?><?= $modifies_price . $tags ?>" data-type="<?= $option_types[$option_count] ?>" data-price="<?= $choice_prices[$choice_count] ?>"<?php echo $showtags . $hidetags . $enable_modifiers . $choice_modifiers_string . $option_modifiers_string; ?>>
        <label for="<?= $choice_id ?>"><?= $choice_names[$choice_count] ?></label>
        <input type="checkbox" id="<?= $choice_id ?>" name="<?= $choice_id ?>" value="<?= $choice_count ?>" onchange="wqpoClickSwatchesCheckbox()">
    </div>

    <?php 
}

if ( $option_requireds[$option_count] == 'on' ) { ?>
    <input type="hidden" name="wqpo-<?= $option_count ?>-required" id="wqpo-<?= $option_count ?>-required" class="wqpo-checkbox-option-required" value="">
<?php 
}