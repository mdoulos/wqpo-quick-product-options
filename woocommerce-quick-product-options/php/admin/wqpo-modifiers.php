<?php defined( 'ABSPATH' ) || exit;

global $post;
$post_id = $post->ID;

if (!$total_modifier_count) { $total_modifier_count = 1; }

// Get the saved modifier information.
$wqpo_mkeys = apply_filters('wqpo_modify_modifier_keys', ['tag', 'type', 'adjustment', 'multiplier', 'enableddefault']);
foreach ($wqpo_mkeys as $wqpo_mkey) {
    ${'modifier_' . $wqpo_mkey .'s'} = get_post_meta($post_id, 'wqpo_m' . $wqpo_mkey . 's', true);
}

// For each modifier, output the modifier HTML.
for ($modifier_count = 0; $modifier_count < $total_modifier_count; $modifier_count++) {
    $modifier_tag = isset($modifier_tags[$modifier_count]) ? $modifier_tags[$modifier_count] : '';
    $modifier_type = isset($modifier_types[$modifier_count]) ? $modifier_types[$modifier_count] : 'adjustment';
    
    // Modifier HTML ?>
    <div class="wqpo-admin-modifier flex-column <?= 'wqpo-admin-modifier-' . $modifier_type; ?>">
        <div class="wqpo-admin-modifier-inner flex-row">
            <div class="wqpo-admin-field wqpo-admin-mtag">
                    <label for="wqpo_mtag[]">Modifier Tag</label>
                    <input type="text" name="wqpo_mtag[]" placeholder="" value="<?php echo esc_attr($modifier_tag); ?>">
            </div>
            <div class="wqpo-admin-field wqpo-admin-mtype">
                <label for="wqpo_mtype[]">Input Type</label>
                <select name="wqpo_mtype[]">
                    <option value="adjustment" <?php selected($modifier_type, 'adjustment'); ?>>Adjustment</option>
                    <option value="multiplier" <?php selected($modifier_type, 'multiplier'); ?>>Multiplier</option>
                </select>
            </div>
            <div class="wqpo-admin-field wqpo-admin-madjustment">
                <label for="wqpo_madjustment[]">Adjust Dollar Amount (Ex: -15 or 35)</label>
                <input type="number" name="wqpo_madjustment[]" placeholder="0" step="0.01" value="<?php echo esc_attr($modifier_adjustments[$modifier_count]); ?>">
            </div>
            <div class="wqpo-admin-field wqpo-admin-mmultiplier">
                <label for="wqpo_mmultiplier[]">Adjust by Multiplier (Ex: 0.25 or 1.5)</label>
                <input type="number" name="wqpo_mmultiplier[]" placeholder="0" step="0.01" value="<?php echo esc_attr($modifier_multipliers[$modifier_count]); ?>">
            </div>
            <div class="wqpo-admin-field wqpo-admin-menableddefault">
                <label for="wqpo_menableddefault<?= '_' . $modifier_count; ?>">Enable by Default</label>
                <input type="checkbox" name="wqpo_menableddefault<?= '_' . $modifier_count; ?>" <?php checked($modifier_enableddefaults[$modifier_count], 'on'); ?>>
            </div>
            <div class="wqpo-admin-field wqpo-admin-mremove">
                <button onclick="wqpo_remove_modifier(event)">Remove</button>
            </div>
        </div>
    </div>
<?php } ?>