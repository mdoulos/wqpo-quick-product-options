<?php defined( 'ABSPATH' ) || exit;

function wqpo_display_product_options() {
    require_once dirname( __FILE__ ) . '/frontend/wqpo-product-page.php';
}

// Hook to display product options only for simple products
add_action('woocommerce_before_add_to_cart_button', 'wqpo_display_for_simple_products', 10);
function wqpo_display_for_simple_products() {
    global $product;

    if ($product->is_type('simple')) {
        wqpo_display_product_options();
    }
}

// Hook to display product options for variable products
add_action('woocommerce_before_single_variation', 'wqpo_display_for_variable_products', 10);
function wqpo_display_for_variable_products() {
    global $product;

    if ($product->is_type('variable')) {
        wqpo_display_product_options();
    }
}


// Save the Product Options set on the Product Edit Page (Backend)
add_action('save_post', 'save_wqpo_product_options');
function save_wqpo_product_options($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if(get_post_type($post_id) !== 'product'){
        return;
    }

    // Retrieve the Total Number of Options.
    // If the 1st Option Name is Empty, Set the Total Option Count to 0
    $total_option_count = isset($_POST['wqpo_option_count']) ? intval($_POST['wqpo_option_count']) : 0;
    if ( isset($_POST['wqpo_oname']) && empty($_POST['wqpo_oname'][0]) ) { $total_option_count = 0; }

    // Only save wqpo data if there are options to save or if options were removed. This prevents the plugin from saving empty data.
    $option_count_changed = isset($_POST['wqpo_option_count_changed']) && $_POST['wqpo_option_count_changed'] == 'yes' ? $_POST['wqpo_option_count_changed'] : 'no';
    $total_option_count == 0 && $option_count_changed == 'no' ? $save_wqpo_data = false : $save_wqpo_data = true;

    if ($save_wqpo_data == false) {
        return;
    }

    // Save the Total Number of Modifiers
    $total_modifier_count = isset($_POST['wqpo_modifier_count']) ? intval($_POST['wqpo_modifier_count']) : 0;
    update_post_meta($post_id, 'wqpo-modifier-count', $total_modifier_count);

    // Save the Modifiers
    // Define the Names of the Text Based Inputs, Loop Through Them, and Save Their Values
    $input_names = ['tag', 'type'];
    foreach ($input_names as $input_name) {
        $input_values = array();

        // If the input is set, save it, otherwise save an empty string.
        for ($i = 0; $i < $total_modifier_count; $i++) {
            $input_values[$i] = isset($_POST['wqpo_m' . $input_name][$i]) ? sanitize_text_field($_POST['wqpo_m' . $input_name][$i]) : '';
        }

        update_post_meta($post_id, 'wqpo_m'. $input_name . 's', $input_values);
    }

    // Save the Modifiers
    // Sanitize and Save the Float Based Inputs for Each Modifier
    $input_names = ['adjustment', 'multiplier'];
    foreach ($input_names as $input_name) {
        $input_values = array();

        for ($i = 0; $i < $total_modifier_count; $i++) {
            $input_values[$i] = isset($_POST['wqpo_m' . $input_name][$i]) ? floatval($_POST['wqpo_m' . $input_name][$i]) : 0;
        }

        update_post_meta($post_id, 'wqpo_m'. $input_name . 's', $input_values);
    }

    // Save the Modifiers
    // Define the Names of the Checkboxes, Loop Through Them, and Save Their States
    $checkbox_names = ['enableddefault'];
    foreach ($checkbox_names as $checkbox_name) {
        $checkbox_states = array();

        // If the checkbox is checked, save 'on', otherwise save 'off'.
        for ($i = 0; $i < $total_modifier_count; $i++) {
            $checkbox_states[$i] = isset($_POST['wqpo_m' . $checkbox_name . '_' . $i]) ? 'on' : 'off';
        }

        update_post_meta($post_id, 'wqpo_m'. $checkbox_name . 's', $checkbox_states);
    }


    $allowed_html = array(); // No HTML tags allowed, but preserves white space.

    do_action('wqpo_save_product_settings', $post_id);

    // Save the Total Number of Options
    update_post_meta($post_id, 'wqpo-option-count', $total_option_count);

    // Save the Advanced Settings
    $setting_showsettings = isset($_POST['wqpo_sshowsettings']) ? 'on' : 'off';
    update_post_meta($post_id, 'wqpo_sshowsettings', $setting_showsettings);

    // Save the SKU Label and Suffixes
    $setting_skulabel = isset($_POST['wqpo_sskulabel']) ? wp_kses($_POST['wqpo_sskulabel'], $allowed_html) : '';
    $setting_skulabelsuffix = isset($_POST['wqpo_sskulabelsuffix']) ? wp_kses($_POST['wqpo_sskulabelsuffix'], $allowed_html) : '';
    $setting_skusuffix = isset($_POST['wqpo_sskusuffix']) ? wp_kses($_POST['wqpo_sskusuffix'], $allowed_html) : '';
    update_post_meta($post_id, 'wqpo_sskulabel', $setting_skulabel);
    update_post_meta($post_id, 'wqpo_sskulabelsuffix', $setting_skulabelsuffix);
    update_post_meta($post_id, 'wqpo_sskusuffix', $setting_skusuffix);

    // Save the Itemization Prefix and Suffixes
    $setting_sitemizeprefix = isset($_POST['wqpo_sitemizeprefix']) ? wp_kses($_POST['wqpo_sitemizeprefix'], $allowed_html) : '';
    $setting_sitemizesuffix = isset($_POST['wqpo_sitemizesuffix']) ? wp_kses($_POST['wqpo_sitemizesuffix'], $allowed_html) : '';
    $setting_sitemize = isset($_POST['wqpo_sitemize']) ? 'on' : 'off';
    update_post_meta($post_id, 'wqpo_sitemizeprefix', $setting_sitemizeprefix);
    update_post_meta($post_id, 'wqpo_sitemizesuffix', $setting_sitemizesuffix);
    update_post_meta($post_id, 'wqpo_sitemize', $setting_sitemize);

    // Save the Replace Product Price Setting, and the Field Size Setting
    $setting_replace = isset($_POST['wqpo_sreplace']) ? 'on' : 'off';
    $setting_type = isset($_POST['wqpo_stype']) ? sanitize_text_field($_POST['wqpo_stype']) : 'normal';
    update_post_meta($post_id, 'wqpo_sreplace', $setting_replace);
    update_post_meta($post_id, 'wqpo_stype', $setting_type);

    // Define the Names of the Checkboxes, Loop Through Them, and Save Their States
    $checkbox_names = ['required', 'showinput', 'usedescription', 'selectfirst', 'priceperchar', 'flatrate', 'showtext'];
    foreach ($checkbox_names as $checkbox_name) {
        $checkbox_states = array();

        // If the checkbox is checked, save 'on', otherwise save 'off'.
        for ($i = 0; $i < $total_option_count; $i++) {
            $checkbox_states[$i] = isset($_POST['wqpo_o' . $checkbox_name . '_' . $i]) ? 'on' : 'off';
        }

        update_post_meta($post_id, 'wqpo_o'. $checkbox_name . 's', $checkbox_states);
    }

    // Define the Names of the Text Based Inputs, Loop Through Them, and Save Their Values
    $input_names = ['name', 'type', 'classname'];
    foreach ($input_names as $input_name) {
        $input_values = array();

        // If the input is set, save it, otherwise save an empty string.
        for ($i = 0; $i < $total_option_count; $i++) {
            $input_values[$i] = isset($_POST['wqpo_o' . $input_name][$i]) ? sanitize_text_field($_POST['wqpo_o' . $input_name][$i]) : '';
        }

        update_post_meta($post_id, 'wqpo_o'. $input_name . 's', $input_values);
    }

    // Define the Names of the Text Based Inputs that need to allow leading white space, Loop Through Them, and Save Their Values
    $input_names = ['prefix', 'suffix'];
    foreach ($input_names as $input_name) {
        $input_values = array();

        // If the input is set, save it, otherwise save an empty string.
        for ($i = 0; $i < $total_option_count; $i++) {
            $input_values[$i] = isset($_POST['wqpo_o' . $input_name][$i]) ? wp_kses($_POST['wqpo_o' . $input_name][$i], $allowed_html) : '';
        }

        update_post_meta($post_id, 'wqpo_o'. $input_name . 's', $input_values);
    }

    // Save Option Sort Orders and Max Lengths
    $option_sortorders = array();
    $option_maxlengths = array();

    // If the input is set, save it, otherwise save it as 0.
    for ($i = 0; $i < $total_option_count; $i++) {
        $option_sortorders[$i] = isset($_POST['wqpo_osort'][$i]) ? intval($_POST['wqpo_osort'][$i]) : 0;
        $option_maxlengths[$i] = isset($_POST['wqpo_omaxlength'][$i]) ? intval($_POST['wqpo_omaxlength'][$i]) : 0;
    }

    update_post_meta($post_id, 'wqpo_osorts', $option_sortorders);
    update_post_meta($post_id, 'wqpo_omaxlengths', $option_maxlengths);

    // Loop Through The Total Number of Options
    for ($option_count = 0; $option_count < $total_option_count; $option_count++) {
        // For Each Option, Record the number of Choices saved as wqpo_o#_choice_count where # is the option number.
        $total_choice_count = isset($_POST['wqpo_o' . $option_count . '_choice_count']) ? intval($_POST['wqpo_o' . $option_count . '_choice_count']) : 0;
        update_post_meta($post_id, 'wqpo_o' . $option_count . '_choice_count', $total_choice_count);

        do_action('wqpo_save_product_options', $post_id, $option_count, $total_choice_count);

        // Sanitize and Save the Text Based Inputs for Each Choice
        $input_names = ['name', 'sku', 'description', 'tag', 'showtag', 'hidetag', 'fillhex', 'borderhex', 'enablemodifier'];
        foreach ($input_names as $input_name) {
            $input_values = array();

            for ($i = 0; $i < $total_choice_count; $i++) {
                $input_values[$i] = isset($_POST['wqpo_o' . $option_count . '_c' . $input_name][$i]) ? sanitize_text_field($_POST['wqpo_o' . $option_count . '_c' . $input_name][$i]) : '';
            }

            update_post_meta($post_id, 'wqpo_o' . $option_count . '_c' . $input_name . 's', $input_values);
        }

        // Sanitize and Save the Float Based Inputs for Each Choice
        $input_names = ['price', 'step'];
        foreach ($input_names as $input_name) {
            $input_values = array();

            for ($i = 0; $i < $total_choice_count; $i++) {
                $input_values[$i] = isset($_POST['wqpo_o' . $option_count . '_c' . $input_name][$i]) ? floatval($_POST['wqpo_o' . $option_count . '_c' . $input_name][$i]) : 0;
            }

            update_post_meta($post_id, 'wqpo_o' . $option_count . '_c' . $input_name . 's', $input_values);
        }

        // Sanitize and Save the Integer Based Inputs for Each Choice
        $input_names = ['sort', 'image', 'hide', 'min', 'max'];
        foreach ($input_names as $input_name) {
            $input_values = array();

            for ($i = 0; $i < $total_choice_count; $i++) {
                $input_values[$i] = isset($_POST['wqpo_o' . $option_count . '_c' . $input_name][$i]) ? intval($_POST['wqpo_o' . $option_count . '_c' . $input_name][$i]) : 0;
            }

            update_post_meta($post_id, 'wqpo_o' . $option_count . '_c' . $input_name . 's', $input_values);
        }
    }
}

// Add the Product Options to the Cart Item when Added to the Cart on the Product Page
add_filter( 'woocommerce_add_cart_item_data', 'wqpo_add_to_cart', 10, 3 );
function wqpo_add_to_cart( $cart_item_data, $product_id, $variation_id ) {

    // Retrieve the Total Number of Options
    $product = wc_get_product( $product_id );
    $total_option_count = get_post_meta($product_id, "wqpo-option-count", true);
    $total_option_count = intval($total_option_count);
    if ($total_option_count == 0) { return $cart_item_data; } // If there are no options, stop here.

    $hidden_options = isset($_POST['wqpo-hidden-options']) ? sanitize_text_field($_POST['wqpo-hidden-options']) : '';
    $hidden_options_array = array_filter(explode(',', $hidden_options), 'strlen');

    $itemize_prices = get_post_meta($product_id, 'wqpo_sitemize', true) == 'on' ? true : false; 
    $itemized_price_prefix = get_post_meta($product_id, 'wqpo_sitemizeprefix', true) ? get_post_meta($product_id, 'wqpo_sitemizeprefix', true) : ' (';
    $itemized_price_suffix = get_post_meta($product_id, 'wqpo_sitemizesuffix', true) ? get_post_meta($product_id, 'wqpo_sitemizesuffix', true) : ')';
    $all_options_price = 0;
    $option_choice_numbers = [];

    // Get the Product Price
    if ( $variation_id ) {
        $variation = wc_get_product( $variation_id );
        $product_price = $variation->get_price();
    } else {
        $product_price = $product->get_price();
    }

    // Get the saved option information.
    require(__DIR__ . '/frontend/wqpo-fetch-option-meta.php');
    $replace_price = get_post_meta($product_id, 'wqpo_sreplace', true) == 'on' ? true : false;

    // Save the Base Price output data if relevant.
    if ( !$replace_price && $itemize_prices && $product_price > 0 ) {
        $cart_item_data['wqpo_option_name_base_price'] = "Base Price";
        $cart_item_data['wqpo_option_value_base_price'] = $product_price;
    }

    // Retrieve the Total Number of Modifiers
    $total_modifier_count = get_post_meta($product_id, "wqpo-modifier-count", true);
    $total_modifier_count = intval($total_modifier_count);

    // Get the saved modifier information, and set up the arrays for quick reference.
    require(__DIR__ . '/frontend/wqpo-fetch-modifier-meta.php');

    // Create an array of enabled modifier tags.
    $enabled_modifier_array = [];
    for ($modifier_count = 0; $modifier_count < $total_modifier_count; $modifier_count++) {
        if ($modifier_enableddefaults[$modifier_count] == 'on') {
            $enabled_modifier_array[] = $modifier_tags[$modifier_count];
        }
    }

    // Loop Through Each Option and fetch choice info, and identify which modifier tags are enabled.
    for ($i = 0; $i < $total_option_count; $i++) {
        $choice_enablemodifiers = get_post_meta($product_id, 'wqpo_o' . $i . '_cenablemodifiers', true); // Array of enabled modifier tags for each choice.
        
        if ($option_types[$i] == 'checkbox' || $option_types[$i] == 'swatches-checkbox') {
            $checkbox_values = [];
            foreach ($_POST as $key => $posted_value) { // Capture checkbox values with dynamic names like wqpo-cb-2-0, wqpo-cb-2-1, etc.
                if (strpos($key, "wqpo-cb-$i-") === 0) { // Check if the key starts with wqpo-cb-$i-
                    $checkbox_values[] = sanitize_text_field($posted_value); // Sanitizes Checkbox Input Types
                }
            }
            
            if (!empty($checkbox_values)) {
                foreach ($checkbox_values as $choice_number) {
                    if (isset($choice_enablemodifiers[$choice_number])) {
                        // Add any enabled modifier tags to the $enabled_modifier_array
                        $choice_modifier_array = array_map('trim', array_map('strtolower', explode(',', $choice_enablemodifiers[$choice_number])));
                        $enabled_modifier_array = array_unique(array_merge($enabled_modifier_array, $choice_modifier_array));
                    }
                }
            }
        } else {
            if (isset($_POST["wqpo-$i"])) {
                if ($option_types[$i] == 'radio' || $option_types[$i] == 'swatches-radio' || $option_types[$i] == 'color-radio' || $option_types[$i] == 'dropdown') {
                    $choice_number = sanitize_text_field($_POST["wqpo-$i"]);
                    if (isset($choice_enablemodifiers[$choice_number])) {
                        // Add any enabled modifier tags to the $enabled_modifier_array
                        $choice_modifier_array = array_map('trim', array_map('strtolower', explode(',', $choice_enablemodifiers[$choice_number])));
                        $enabled_modifier_array = array_unique(array_merge($enabled_modifier_array, $choice_modifier_array));
                    }
                }
            }
        }
    }

    // Remove any $enabled_modifier_array values that are not in $modifier_tag_array.
    $enabled_modifier_array = array_intersect($enabled_modifier_array, $modifier_tag_array);

    
    // Loop Through Each Option
    for ($i = 0; $i < $total_option_count; $i++) {

        // Split option classnames into an array for easier comparison, and trim them. Identify the modifier tags for this option.
        $option_class_array = $option_classnames[$i] ? array_map('trim', array_map('strtolower', explode(',', $option_classnames[$i]))) : [];
        $option_modifiers = array_intersect($option_class_array, $enabled_modifier_array);

        // Get the saved choice information.
        $wqpo_ckeys = apply_filters('wqpo_modify_choice_keys', ['name', 'price', 'sort', 'sku', 'image', 'tag', 'showtag', 'hidetag', 'min', 'max', 'step', 'description', 'fillhex', 'borderhex', 'enablemodifier']);
        foreach ($wqpo_ckeys as $wqpo_ckey) {
            ${'choice_' . $wqpo_ckey . 's'} = get_post_meta($product_id, 'wqpo_o' . $i . '_c' . $wqpo_ckey .'s', true);
        }

        $chosen_value = '';
        $this_option_price = 0;
        
        // Sanitize the Input Data
        if ($option_types[$i] == 'checkbox' || $option_types[$i] == 'swatches-checkbox') {
            $checkbox_values = [];
            foreach ($_POST as $key => $posted_value) { // Capture checkbox values with dynamic names like wqpo-cb-2-0, wqpo-cb-2-1, etc.
                if (strpos($key, "wqpo-cb-$i-") === 0) {
                    $checkbox_values[] = sanitize_text_field($posted_value); // Sanitizes Checkbox Input Types
                }
            }
            
            if (!empty($checkbox_values)) {
                $option_choice_numbers[$i] = $checkbox_values;

                // Convert choice numbers to choice names
                $chosen_value_names = [];
                foreach ($checkbox_values as $choice_number) {
                    // Ensure that $choice_number exists in $choice_names[$i]
                    if (isset($choice_names[$choice_number])) {
                        $chosen_value_names[] = $choice_names[$choice_number];  // Convert the choice number to its corresponding name
                    }

                    $choice_tag_array = $choice_tags[$choice_number] ? array_map('trim', array_map('strtolower', explode(',', $choice_tags[$choice_number]))) : [];
                    $choice_modifiers = array_intersect($choice_tag_array, $enabled_modifier_array);
                    $choice_modifiers = array_unique(array_merge($option_modifiers, $choice_modifiers)); // Add option_modifiers to choice_modifiers and remove duplicates

                    // If the choice has modifiers, apply them to the option price, otherwise apply the choice price.
                    if (!empty($choice_modifiers)) {
                        $choice_price = 0;
                        $adjustments = 0;

                        if (isset($choice_prices[$choice_number])) {
                            $choice_price = $choice_prices[$choice_number];
                        }

                        foreach ($choice_modifiers as $modifier_tag) {
                            $modifier_key = array_search($modifier_tag, $modifier_tags);
                            $modifier_type = $modifier_types[$modifier_key];
                            $modifier_adjustment = $modifier_adjustments[$modifier_key];
                            $modifier_multiplier = $modifier_multipliers[$modifier_key];

                            if ($modifier_type == 'adjustment') {
                                $adjustments += $modifier_adjustment;
                            } elseif ($modifier_type == 'multiplier') {
                                $choice_price = $choice_price * $modifier_multiplier;
                            }

                            $this_option_price += $choice_price + $adjustments;
                        }
                    } else {
                        if (isset($choice_prices[$choice_number])) {
                            $this_option_price += $choice_prices[$choice_number];
                        }
                    }
                }

                $chosen_value = implode(', ', $chosen_value_names);  // Store the collected checkbox values as a comma-separated string.
            }

        } else {
            if (isset($_POST["wqpo-$i"])) {
                $chosen_value = sanitize_text_field($_POST["wqpo-$i"]); // Sanitizes Other Input Types: textarea, text, number, radio, select, etc
                $option_choice_numbers[$i] = $chosen_value;

                if ($option_types[$i] == 'radio' || $option_types[$i] == 'swatches-radio' || $option_types[$i] == 'color-radio' || $option_types[$i] == 'dropdown') {
                    $choice_number = $chosen_value;
                    $chosen_value = $choice_names[$choice_number];

                    // Split choice tags into an array for easier comparison, and trim them. Identify the modifier tags for this choice.
                    $choice_tag_array = $choice_tags[$choice_number] ? array_map('trim', array_map('strtolower', explode(',', $choice_tags[$choice_number]))) : [];
                    $choice_modifiers = array_intersect($choice_tag_array, $enabled_modifier_array);
                    $choice_modifiers = array_unique(array_merge($option_modifiers, $choice_modifiers)); // Add option_modifiers to choice_modifiers and remove duplicates

                    // If the choice has modifiers, apply them to the option price, otherwise apply the choice price.
                    if (!empty($choice_modifiers)) {
                        $choice_price = 0;
                        $adjustments = 0;

                        if (isset($choice_prices[$choice_number])) {
                            $choice_price = $choice_prices[$choice_number];
                        } 

                        foreach ($choice_modifiers as $modifier_tag) {
                            $modifier_key = array_search($modifier_tag, $modifier_tags);
                            $modifier_type = $modifier_types[$modifier_key];
                            $modifier_adjustment = $modifier_adjustments[$modifier_key];
                            $modifier_multiplier = $modifier_multipliers[$modifier_key];

                            if ($modifier_type == 'adjustment') {
                                $adjustments += $modifier_adjustment;
                            } elseif ($modifier_type == 'multiplier') {
                                $choice_price = $choice_price * $modifier_multiplier;
                            }
                        }

                        $this_option_price += $choice_price + $adjustments;
                        
                    } else {
                        if (isset($choice_prices[$choice_number])) {
                            $this_option_price += $choice_prices[$choice_number];
                        }
                    }
                } elseif ($option_types[$i] == 'number') {
                    if ($option_flatrates[$i] == 'on') {
                        $this_option_price += $choice_prices[0];
                    } else {
                        $this_option_price += $choice_prices[0] * $chosen_value;
                    }
                } elseif ($option_types[$i] == 'textarea') {
                    $this_option_price += $choice_prices[0];
                } elseif ($option_types[$i] == 'text') {
                    if ($option_priceperchars[$i] == 'on') {
                        $this_option_price += $choice_prices[0] * strlen($chosen_value);
                    } else {
                        $this_option_price += $choice_prices[0];
                    }
                }

                // Apply the option modifiers to the option price if the option is a number, textarea, or text.
                if ($option_types[$i] == 'number' || $option_types[$i] == 'textarea' || $option_types[$i] == 'text') {
                    if (!empty($option_modifiers)) {
                        $option_price = $this_option_price;
                        $adjustments = 0;

                        foreach ($option_modifiers as $modifier_tag) {
                            $modifier_key = array_search($modifier_tag, $modifier_tags);
                            $modifier_type = $modifier_types[$modifier_key];
                            $modifier_adjustment = $modifier_adjustments[$modifier_key];
                            $modifier_multiplier = $modifier_multipliers[$modifier_key];

                            if ($modifier_type == 'adjustment') {
                                $adjustments += $modifier_adjustment;
                            } elseif ($modifier_type == 'multiplier') {
                                $option_price = $option_price * $modifier_multiplier;
                            }

                            $this_option_price = $option_price + $adjustments;
                        }
                    }
                }
            }
        }

        // Save the Option Names and Values if it's not empty and not hidden.
        if (!empty($chosen_value) && !in_array($i, $hidden_options_array)) {
            $cart_item_data['wqpo_option_name_' . $i] = $option_names[$i];
            if ($itemize_prices && $this_option_price > 0) {
                $cart_item_data['wqpo_option_value_' . $i] = $chosen_value . $itemized_price_prefix . $this_option_price . $itemized_price_suffix;
            } else {
                $cart_item_data['wqpo_option_value_' . $i] = $chosen_value;
            }

            $all_options_price += $this_option_price;
        }
    }

    $cart_item_data['wqpo_update_price'] = $replace_price ? $all_options_price : $product_price + $all_options_price;
    $cart_item_data['wqpo_option_count'] = $total_option_count;
    $cart_item_data['wqpo_option_choice_numbers'] = $option_choice_numbers;
    $cart_item_data = apply_filters('wqpo_modify_cart_item_data', $cart_item_data, $product_id);
	return $cart_item_data;
}

// Display the Product Options in the Cart and Checkout
add_filter( 'woocommerce_get_item_data', 'wqpo_cart_checkout_output', 10, 2 );
function wqpo_cart_checkout_output( $item_data, $cart_item ) {
    $item_id = $cart_item['product_id'];
    $total_option_count = $cart_item['wqpo_option_count'];
    if ($total_option_count == 0) { return $item_data; }

    if ( isset( $cart_item['wqpo_option_name_base_price'] ) ) {
        $item_data[] = array(
            'key'     => $cart_item['wqpo_option_name_base_price'],
            'value'   => $cart_item['wqpo_option_value_base_price'],
        );
    }

    for ($i = 0; $i < $total_option_count; $i++) {
        if ( isset( $cart_item['wqpo_option_name_' . $i] ) ) {
            $key = $cart_item['wqpo_option_name_' . $i];
            $value = $cart_item['wqpo_option_value_' . $i];

            $item_data[] = array(
                'key'     => $key,
                'value'   => $value,
            );
        }
    }
    
    return $item_data;
}

// Add the Product Options to the Order Details, Email, and Thank You Page
add_action( 'woocommerce_add_order_item_meta', 'wqpo_add_custom_item_meta_data_to_order', 10, 3 );
function wqpo_add_custom_item_meta_data_to_order( $item_id, $values, $cart_item_key ) {
    $total_option_count = $values['wqpo_option_count'];
    
    for ($i = 0; $i < $total_option_count; $i++) {
        if ( isset( $values['wqpo_option_value_' . $i] ) ) {
            $key = $values['wqpo_option_name_' . $i];
            $value = $values['wqpo_option_value_' . $i];
            wc_add_order_item_meta( $item_id, $key, $value );
        }
    }
}

// Update the Cart Item Price with the Product Options Price
add_filter( 'woocommerce_cart_item_subtotal', 'wqpo_cart_item_subtotal', 10, 3 );
function wqpo_cart_item_subtotal( $subtotal, $cart_item, $cart_item_key ) {
    if( isset( $cart_item['wqpo_update_price'] ) ) {
        $subtotal = wc_price( $cart_item['wqpo_update_price'] * $cart_item['quantity'] );
    }
    return $subtotal;
}

// Update the Cart Item Price with the Product Options Price
add_action( 'woocommerce_before_calculate_totals', 'wqpo_calculate_totals', 10, 1 );
function wqpo_calculate_totals( $cart_obj ) {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}

	// Iterate through each cart item
	foreach( $cart_obj->get_cart() as $key=>$value ) {
		if( isset( $value['wqpo_update_price'] ) ) {
			$price = $value['wqpo_update_price'];
			$value['data']->set_price( ( $price ) );
		}
	}
}