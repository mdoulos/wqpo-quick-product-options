<?php defined( 'ABSPATH' ) || exit;

global $post;
$post_id = $post->ID;

if (!$total_option_count) { $total_option_count = 1; }

// Get the saved option information.
$wqpo_okeys = apply_filters('wqpo_modify_option_keys', ['name', 'type', 'sort', 'classname', 'prefix', 'suffix', 'maxlength', 'required', 'showinput', 'usedescription', 'selectfirst', 'priceperchar', 'flatrate', 'showtext']);
foreach ($wqpo_okeys as $wqpo_okey) {
    ${'option_' . $wqpo_okey .'s'} = get_post_meta($post_id, 'wqpo_o' . $wqpo_okey . 's', true);
}


// For each option, output the option HTML.
for ($option_count = 0; $option_count < $total_option_count; $option_count++) {
    $option_name = isset($option_names[$option_count]) ? $option_names[$option_count] : '';
    $option_type = isset($option_types[$option_count]) ? $option_types[$option_count] : 'radio';
    if ($option_type == 'textarea') {
        $option_maxlength = isset($option_maxlengths[$option_count]) ? $option_maxlengths[$option_count] : 500;
    } else {
        $option_maxlength = isset($option_maxlengths[$option_count]) ? $option_maxlengths[$option_count] : 100;
    }
    
    // Option HTML ?>
    <div class="wqpo-admin-option flex-column <?= 'wqpo-admin-option-' . $option_type; ?>">
        <div class="wqpo-admin-option-inner flex-row">
            <div class="wqpo-admin-field wqpo-admin-oname">
                    <label for="wqpo_oname[]">Option Name</label>
                    <input type="text" name="wqpo_oname[]" placeholder="Option" value="<?php echo esc_attr($option_name); ?>">
            </div>
            <div class="wqpo-admin-field wqpo-admin-otype">
                <label for="wqpo_otype[]">Input Type</label>
                <select name="wqpo_otype[]">
                    <option value="radio" <?php selected($option_type, 'radio'); ?>>Radio</option>
                    <option value="dropdown" <?php selected($option_type, 'dropdown'); ?>>Dropdown</option>
                    <option value="checkbox" <?php selected($option_type, 'checkbox'); ?>>Checkbox</option>
                    <option value="swatches-radio" <?php selected($option_type, 'swatches-radio'); ?>>Swatches (Single)</option>
                    <option value="swatches-checkbox" <?php selected($option_type, 'swatches-checkbox'); ?>>Swatches (Multi)</option>
                    <option value="color-radio" <?php selected($option_type, 'color-radio'); ?>>Color</option>
                    <option value="number" <?php selected($option_type, 'number'); ?>>Number</option>
                    <option value="text" <?php selected($option_type, 'text'); ?>>Short Text</option>
                    <option value="textarea" <?php selected($option_type, 'textarea'); ?>>Text Area</option>
                </select>
            </div>
            <div class="wqpo-admin-field wqpo-admin-osort">
                <label for="wqpo_osort[]">Order</label>
                <input type="number" name="wqpo_osort[]" placeholder="1" max="99" step="1" value="<?php echo esc_attr($option_sorts[$option_count]); ?>">
            </div>
            <div class="wqpo-admin-field wqpo-admin-oclass">
                    <label for="wqpo_oclassname[]">Classes/Tags/Mods</label>
                    <input type="text" name="wqpo_oclassname[]" placeholder="" value="<?php echo esc_attr($option_classnames[$option_count]); ?>">
            </div>
            <div class="wqpo-admin-field wqpo-admin-oprefix">
                <label for="wqpo_oprefix[]">Prefix</label>
                <input type="text" name="wqpo_oprefix[]" placeholder="Choose a " value="<?php echo esc_attr($option_prefixs[$option_count]); ?>">
            </div>
            <div class="wqpo-admin-field wqpo-admin-osuffix">
                <label for="wqpo_osuffix[]">Suffix</label>
                <input type="text" name="wqpo_osuffix[]" placeholder=":" value="<?php echo esc_attr($option_suffixs[$option_count]); ?>">
            </div>
            <div class="wqpo-admin-field wqpo-admin-omaxlength">
                <label for="wqpo_omaxlength[]">Max Length</label>
                <input type="number" name="wqpo_omaxlength[]" step="1" value="<?php echo esc_attr($option_maxlength); ?>">
            </div>
            <?php do_action('wqpo_after_admin_text_fields', $post_id, $option_count); ?>
            <div class="wqpo-admin-field wqpo-admin-orequired">
                <label for="wqpo_orequired<?= '_' . $option_count; ?>">Required</label>
                <input type="checkbox" name="wqpo_orequired<?= '_' . $option_count; ?>" <?php checked($option_requireds[$option_count], 'on'); ?>>
            </div>
            <div class="wqpo-admin-field wqpo-admin-oshowinput">
                <label for="wqpo_oshowinput<?= '_' . $option_count; ?>">Show Input</label>
                <input type="checkbox" name="wqpo_oshowinput<?= '_' . $option_count; ?>" <?php checked($option_showinputs[$option_count], 'on'); ?>>
            </div>
            <div class="wqpo-admin-field wqpo-admin-ousedescription">
                <label for="wqpo_ousedescription<?= '_' . $option_count; ?>">Description</label>
                <input type="checkbox" class="wqpo-ousedescription" name="wqpo_ousedescription<?= '_' . $option_count; ?>" <?php checked($option_usedescriptions[$option_count], 'on'); ?>>
            </div>
            <div class="wqpo-admin-field wqpo-admin-oselectfirst">
                <label for="wqpo_oselectfirst<?= '_' . $option_count; ?>">Select First</label>
                <input type="checkbox" class="wqpo-oselectfirst" name="wqpo_oselectfirst<?= '_' . $option_count; ?>" <?php checked($option_selectfirsts[$option_count], 'on'); ?>>
            </div>
            <div class="wqpo-admin-field wqpo-admin-opriceperchar">
                <label for="wqpo_opriceperchar<?= '_' . $option_count; ?>">Price per Character</label>
                <input type="checkbox" name="wqpo_opriceperchar<?= '_' . $option_count; ?>" <?php checked($option_priceperchars[$option_count], 'on'); ?>>
            </div>
            <div class="wqpo-admin-field wqpo-admin-oflatrate">
                <label for="wqpo_oflatrate<?= '_' . $option_count; ?>">Flat Rate</label>
                <input type="checkbox" name="wqpo_oflatrate<?= '_' . $option_count; ?>" <?php checked($option_flatrates[$option_count], 'on'); ?>>
            </div>
            <div class="wqpo-admin-field wqpo-admin-oshowtext">
                <label for="wqpo_oshowtext<?= '_' . $option_count; ?>">Show Text</label>
                <input type="checkbox" name="wqpo_oshowtext<?= '_' . $option_count; ?>" <?php checked($option_showtexts[$option_count], 'on'); ?>>
            </div>
            <?php do_action('wqpo_after_admin_checkbox_fields', $post_id, $option_count); ?>
            <div class="wqpo-admin-field wqpo-admin-onumber">
                <span>Option #<?php echo $option_count + 1; ?></span>
                <div class="wqpo-admin-oremove">
                    <button onclick="wqpo_remove_option(event)">Remove</button>
                </div>
            </div>
        </div>
    
        <div class="wqpo-admin-choices"><?php 
        $total_choice_count = get_post_meta($post_id, 'wqpo_o' . $option_count . '_choice_count', true);
        $total_choice_count = intval($total_choice_count);
        if (!$total_choice_count) { $total_choice_count = 1; }

        // Get the saved choice information.
        $wqpo_ckeys = apply_filters('wqpo_modify_choice_keys', ['name', 'price', 'sort', 'sku', 'image', 'tag', 'showtag', 'hidetag', 'min', 'max', 'step', 'description', 'fillhex', 'borderhex', 'enablemodifier']);
        foreach ($wqpo_ckeys as $wqpo_ckey) {
            ${'choice_' . $wqpo_ckey . 's'} = get_post_meta($post_id, 'wqpo_o' . $option_count . '_c' . $wqpo_ckey .'s', true);
        }

        // For each choice, output the choice HTML.
        for ($choice_count = 0; $choice_count < $total_choice_count; $choice_count++) { 
            // Choice HTML ?>
            <div class="wqpo-admin-choice">
                <div class="flex-row wqpo-admin-crowmain">
                    <div class="wqpo-admin-field wqpo-admin-cname">
                        <label for="wqpo_<?= 'o' . $option_count . '_'; ?>cname[]">
                            <?php if ($option_type == 'radio' || $option_type == 'dropdown' || $option_type == 'checkbox') { echo 'Choice Name'; } else { echo 'Description (Optional)'; } ?>
                        </label>
                        <input type="text" name="wqpo_<?= 'o' . $option_count . '_'; ?>cname[]" placeholder="<?php if ($option_type == 'radio' || $option_type == 'dropdown' || $option_type == 'checkbox') { echo 'Choice Name'; } else { echo 'Description (Optional)'; } ?>" value="<?php echo esc_attr($choice_names[$choice_count]); ?>">
                    </div>
                    <div class="flex-row wqpo-admin-sharedinputs">
                        <div class="wqpo-admin-field wqpo-admin-cprice">
                            <label for="wqpo_<?= 'o' . $option_count . '_'; ?>cprice[]">Price</label>
                            <input type="number" name="wqpo_<?= 'o' . $option_count . '_'; ?>cprice[]" placeholder="0" step="0.01" value="<?php echo esc_attr($choice_prices[$choice_count]); ?>">
                        </div>
                        <div class="wqpo-admin-field wqpo-admin-csort">
                            <label for="wqpo_<?= 'o' . $option_count . '_'; ?>csort[]">Order</label>
                            <input type="number" name="wqpo_<?= 'o' . $option_count . '_'; ?>csort[]" placeholder="" max="99" step="1" value="<?php echo esc_attr(isset($choice_sorts[$choice_count]) ? $choice_sorts[$choice_count] : 1); ?>">
                        </div>
                        <?php do_action('wqpo_admin_after_order_choice_field', $post_id, $option_count, $choice_count); ?>
                    </div>
                    <div class="flex-row wqpo-admin-colorinputs">
                        <div class="wqpo-admin-field wqpo-admin-cfillhex">
                            <label for="wqpo_<?= 'o' . $option_count . '_'; ?>cfillhex[]">Fill Hexcode</label>
                            <input type="text" name="wqpo_<?= 'o' . $option_count . '_'; ?>cfillhex[]" placeholder="" value="<?php echo esc_attr($choice_fillhexs[$choice_count]); ?>">
                        </div>
                        <div class="wqpo-admin-field wqpo-admin-cborderhex">
                            <label for="wqpo_<?= 'o' . $option_count . '_'; ?>cborderhex[]">Border Hexcode</label>
                            <input type="text" name="wqpo_<?= 'o' . $option_count . '_'; ?>cborderhex[]" placeholder="" value="<?php echo esc_attr($choice_borderhexs[$choice_count]); ?>">
                        </div>
                        <?php do_action('wqpo_admin_color_choice_fields', $post_id, $option_count, $choice_count); ?>
                    </div>
                    <div class="flex-row wqpo-admin-choiceinputs">
                        <div class="wqpo-admin-field wqpo-admin-csku">
                            <label for="wqpo_<?= 'o' . $option_count . '_'; ?>csku[]">SKU</label>
                            <input type="text" name="wqpo_<?= 'o' . $option_count . '_'; ?>csku[]" placeholder="" value="<?php echo esc_attr($choice_skus[$choice_count]); ?>">
                        </div>
                        <div class="wqpo-admin-field wqpo-admin-cimage">
                            <label for="wqpo_<?= 'o' . $option_count . '_'; ?>cimage[]">Image ID</label>
                            <input type="number" name="wqpo_<?= 'o' . $option_count . '_'; ?>cimage[]" placeholder="" step="1" value="<?php echo esc_attr($choice_images[$choice_count]); ?>">
                        </div>
                        <div class="wqpo-admin-field wqpo-admin-ctag">
                            <label for="wqpo_<?= 'o' . $option_count . '_'; ?>ctag[]">Tags/Mods</label>
                            <input type="text" name="wqpo_<?= 'o' . $option_count . '_'; ?>ctag[]" placeholder="" value="<?php echo esc_attr($choice_tags[$choice_count]); ?>">
                        </div>
                        <div class="wqpo-admin-field wqpo-admin-cshowtag">
                            <label for="wqpo_<?= 'o' . $option_count . '_'; ?>cshowtag[]">Show Tags</label>
                            <input type="text" name="wqpo_<?= 'o' . $option_count . '_'; ?>cshowtag[]" placeholder="" value="<?php echo esc_attr($choice_showtags[$choice_count]); ?>">
                        </div>
                        <div class="wqpo-admin-field wqpo-admin-chidetag">
                            <label for="wqpo_<?= 'o' . $option_count . '_'; ?>chidetag[]">Hide Tags</label>
                            <input type="text" name="wqpo_<?= 'o' . $option_count . '_'; ?>chidetag[]" placeholder="" value="<?php echo esc_attr($choice_hidetags[$choice_count]); ?>">
                        </div>
                        <div class="wqpo-admin-field wqpo-admin-cenablemodifiers">
                            <label for="wqpo_<?= 'o' . $option_count . '_'; ?>cenablemodifier[]">Enable Modifiers</label>
                            <input type="text" name="wqpo_<?= 'o' . $option_count . '_'; ?>cenablemodifier[]" placeholder="" value="<?php echo esc_attr($choice_enablemodifiers[$choice_count]); ?>">
                        </div>
                        <?php do_action('wqpo_admin_shared_choice_fields', $post_id, $option_count, $choice_count); ?>
                    </div>
                    <div class="flex-row wqpo-admin-numberinputs">
                        <div class="wqpo-admin-field wqpo-admin-cmin">
                            <label for="wqpo_<?= 'o' . $option_count . '_'; ?>cmin[]">Min</label>
                            <input type="number" name="wqpo_<?= 'o' . $option_count . '_'; ?>cmin[]" placeholder="0" value="<?php echo esc_attr($choice_mins[$choice_count]); ?>">
                        </div>
                        <div class="wqpo-admin-field wqpo-admin-cmax">
                            <label for="wqpo_<?= 'o' . $option_count . '_'; ?>cmax[]">Max</label>
                            <input type="number" name="wqpo_<?= 'o' . $option_count . '_'; ?>cmax[]" placeholder="999" value="<?php echo esc_attr($choice_maxs[$choice_count]); ?>">
                        </div>
                        <div class="wqpo-admin-field wqpo-admin-cstep">
                            <label for="wqpo_<?= 'o' . $option_count . '_'; ?>cstep[]">Step</label>
                            <input type="number" name="wqpo_<?= 'o' . $option_count . '_'; ?>cstep[]" placeholder="1" value="<?php echo esc_attr($choice_steps[$choice_count]); ?>" step="0.01">
                        </div>
                        <?php do_action('wqpo_admin_number_choice_fields', $post_id, $option_count, $choice_count); ?>
                    </div>
                    <?php do_action('wqpo_after_admin_choice_row_fields', $post_id, $option_count, $choice_count); ?>
                    <div class="wqpo-admin-field wqpo-admin-cremove">
                        <button onclick="wqpo_remove_choice(event)">-</button>
                    </div>
                </div>
                <?php do_action('wqpo_before_admin_description_input', $post_id, $option_count, $choice_count); ?>
                <div class="flex-row wqpo-admin-crowdesc">
                    <div class="wqpo-admin-field wqpo-admin-cdescription flex-row">
                        <label for="wqpo_<?= 'o' . $option_count . '_'; ?>cdescription[]">Description</label>
                        <input type="text" name="wqpo_<?= 'o' . $option_count . '_'; ?>cdescription[]" placeholder="Description" value="<?php echo esc_attr($choice_descriptions[$choice_count]); ?>">
                    </div>
                </div>
                <?php do_action('wqpo_after_admin_description_input', $post_id, $option_count, $choice_count); ?>
            </div>
        <?php
        } ?>
        </div>
        <input type="hidden" name="wqpo_<?= 'o' . $option_count . '_'; ?>choice_count" class="wqpo-choice-count" value="<?php echo $total_choice_count; ?>">
        <button type="button" class="button wqpo-add-choice" onclick="wqpo_add_choice(event)"><?php echo __('Add Choice', 'wqpo') ?></button>
    </div>
    <?php
} ?>
