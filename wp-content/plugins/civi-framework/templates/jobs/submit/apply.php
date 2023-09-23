<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $current_user;
$user_id = $current_user->ID;
$jobs_user_select_apply = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'jobs_user_select_apply', true);
$jobs_user_apply_email = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'jobs_user_apply_email', true);
$jobs_user_apply_call_to = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'jobs_user_apply_call_to', true);
$jobs_user_apply_external_link = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'jobs_user_apply_external_link', true);

$hide_jobs_apply = civi_get_option('hide_jobs_apply_fields');
if (empty($hide_jobs_apply)) {
    $hide_jobs_apply = array();
}
?>
<div class="row">
    <div class="form-group col-md-6">
        <label><?php esc_html_e('Select type', 'civi-framework') ?></label>
        <div class="select2-field">
            <select id="select-apply-type" name="jobs_select_apply" class="civi-select2">
                <?php if (!in_array('fields_jobs_apply_email', $hide_jobs_apply)) : ?>
                    <option selected <?php if ($jobs_user_select_apply == "email") {
                        echo 'selected';
                    } ?> value="email"><?php esc_html_e('By email', 'civi-framework') ?></option>
                <?php endif; ?>

                <?php if (!in_array('fields_jobs_apply_internal', $hide_jobs_apply)) : ?>
                    <option <?php if ($jobs_user_select_apply == "internal") {
                        echo 'selected';
                    } ?> value="internal"><?php esc_html_e('Internal Apply', 'civi-framework') ?></option>
                <?php endif; ?>

                <?php if (!in_array('fields_jobs_call_to_apply', $hide_jobs_apply)) : ?>
                    <option <?php if ($jobs_user_select_apply == "call-to") {
                        echo 'selected';
                    } ?> value="call-to"><?php esc_html_e('Call To Apply', 'civi-framework') ?></option>
                <?php endif; ?>
				  <?php if (!in_array('fields_jobs_apply_external_link', $hide_jobs_apply)) : ?>
                    <option <?php if ($jobs_user_select_apply == "external-link") {
                        echo 'selected';
                    } ?> value="external-link"><?php esc_html_e('External Link', 'civi-framework') ?></option>
                <?php endif; ?>
            </select>
        </div>
    </div>

    <?php if (!in_array('fields_jobs_apply_email', $hide_jobs_apply)) : ?>
        <div class="civi-section-apply-select form-group col-md-6" id="email">
            <label for="jobs_apply_email"><?php esc_html_e('Job apply email', 'civi-framework') ?></label>
            <input type="email" id="jobs_apply_email" name="jobs_apply_email"
                   value="<?php echo esc_attr($jobs_user_apply_email) ?>"
                   placeholder="<?php esc_attr_e('Enter email', 'civi-framework') ?>">
        </div>
    <?php endif; ?>

    <?php if (!in_array('fields_jobs_call_to_apply', $hide_jobs_apply)) : ?>
        <div class="civi-section-apply-select form-group col-md-6" id="call-to">
            <label for="jobs_apply_call_to"><?php esc_html_e('Call to apply', 'civi-framework') ?></label>
            <div class="tel-group">
                <select name="prefix_code" class="civi-select2 prefix-code">
                    <?php
                    $prefix_code = phone_prefix_code();
                    foreach ($prefix_code as $key => $value) {
                        echo '<option value="' . $key . '" data-dial-code="' . $value['code'] . '">' . $value['name'] . ' (' . $value['code'] . ')</option>';
                    }
                    ?>
                </select>
                <input type="tel" id="jobs_apply_call_to" name="jobs_apply_call_to"
                       value="<?php echo esc_attr($jobs_user_apply_call_to) ?>"
                       placeholder="<?php esc_attr_e('Enter phone', 'civi-framework') ?>">
            </div>
        </div>
    <?php endif; ?>
	 <?php if (!in_array('fields_jobs_apply_external_link', $hide_jobs_apply)) : ?>
        <div class="civi-section-apply-select form-group col-md-6" id="external-link">
            <label for="jobs_apply_external_link"><?php esc_html_e('External Link', 'civi-framework') ?></label>
            <input type="url" id="jobs_apply_external_link" name="jobs_apply_external_link"
                   value="<?php echo esc_url($jobs_user_apply_external_link) ?>"
                   placeholder="<?php esc_attr_e('Enter external link', 'civi-framework') ?>">
        </div>
    <?php endif; ?>
</div>
