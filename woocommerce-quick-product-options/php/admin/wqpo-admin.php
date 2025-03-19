<?php 
defined( 'ABSPATH' ) || exit;

if (!current_user_can('edit_posts')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

global $post;
$post_id = $post->ID;

// Retrieve the Total Number of Options
$total_option_count = get_post_meta($post_id, "wqpo-option-count", true);
$total_option_count = intval($total_option_count);

// Retrieve the Total Number of Modifiers
$total_modifier_count = get_post_meta($post_id, "wqpo-modifier-count", true);
$total_modifier_count = intval($total_modifier_count);

// Toggle Advanced Settings On or Off
$setting_showsettings= get_post_meta($post_id, 'wqpo_sshowsettings', true);
?>

<div id="wqpo_admin_tab" class="panel wqpo-admin-tab" style="display: block;">
    <div class="wqpo-admin-tab-content">
        
        <div id="wqpo_admin_options_group" class="wqpo_admin_options_group<?php if ($total_option_count == 0) { echo ' wqpo-hidden'; } ?>">
            <?php require_once dirname( __FILE__ ) . '/wqpo-options.php'; ?>
        </div>

        <div id="wqpo_admin_modifiers_group" class="wqpo_admin_modifiers_group<?php if ($total_modifier_count == 0) { echo ' wqpo-hidden'; } ?>">
            <?php require_once dirname( __FILE__ ) . '/wqpo-modifiers.php'; ?>
        </div>

        <div class="wqpo-admin-settings flex-row<?php if (!$setting_showsettings == 'on') { echo ' wqpo-hidden'; } ?>">
            <?php require_once dirname( __FILE__ ) . '/wqpo-settings.php'; ?>
        </div>

        <input type="hidden" id="wqpo_option_count_changed" name="wqpo_option_count_changed" value="no">
        <input type="hidden" id="wqpo_option_count" name="wqpo_option_count" value="<?php echo $total_option_count; ?>">
        <input type="hidden" id="wqpo_modifier_count_changed" name="wqpo_modifier_count_changed" value="no">
        <input type="hidden" id="wqpo_modifier_count" name="wqpo_modifier_count" value="<?php echo $total_modifier_count; ?>">
        <div class="wqpo-admin-bottom-container flex-row">
            <button type="button" id="wqpo_admin_add_option" class="button" onclick="wqpo_add_option(event)"><?php echo __('Add Product Option', 'wqpo') ?></button>
            <button type="button" id="wqpo_admin_add_modifier" class="button" onclick="wqpo_add_modifier(event)"><?php echo __('Add Modifier', 'wqpo') ?></button>
            <div class="wqpo-admin-field wqpo-admin-sshowsettings">
                <label for="wqpo_sshowsettings">Show Advanced Settings</label>
                <input type="checkbox" name="wqpo_sshowsettings" <?php checked($setting_showsettings, 'on'); ?>>
            </div>
        </div>

    </div>
</div>