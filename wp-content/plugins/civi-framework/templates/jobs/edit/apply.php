<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $jobs_meta_data;
$jobs_select_apply = isset($jobs_meta_data[CIVI_METABOX_PREFIX . 'jobs_select_apply']) ? $jobs_meta_data[CIVI_METABOX_PREFIX . 'jobs_select_apply'][0] : '';
$jobs_apply_email = isset($jobs_meta_data[CIVI_METABOX_PREFIX . 'jobs_apply_email']) ? $jobs_meta_data[CIVI_METABOX_PREFIX . 'jobs_apply_email'][0] : '';
$jobs_apply_external = isset($jobs_meta_data[CIVI_METABOX_PREFIX . 'jobs_apply_external']) ? $jobs_meta_data[CIVI_METABOX_PREFIX . 'jobs_apply_external'][0] : '';
$jobs_apply_call_to = isset($jobs_meta_data[CIVI_METABOX_PREFIX . 'jobs_apply_call_to']) ? $jobs_meta_data[CIVI_METABOX_PREFIX . 'jobs_apply_call_to'][0] : '';
$hide_jobs_apply = civi_get_option('hide_jobs_apply_fields');
if(empty($hide_jobs_apply)){
    $hide_jobs_apply = array();
}
?>
<div class="row">
    <div class="form-group col-md-6">
        <label><?php esc_html_e('Select type', 'civi-framework') ?></label>
        <div class="select2-field">
			<select id="select-apply-type" name="jobs_select_apply" class="civi-select2">
                <?php if (!in_array('fields_jobs_apply_email', $hide_jobs_apply)) : ?>
                    <option <?php if ($jobs_select_apply == "email") {
                        echo 'selected';
                    } ?> value="email"><?php esc_html_e('By email', 'civi-framework') ?></option>
                <?php endif; ?>

                <?php if (!in_array('fields_jobs_apply_external', $hide_jobs_apply)) : ?>
                    <option <?php if ($jobs_select_apply == "external") {
                        echo 'selected';
                    } ?> value="external"><?php esc_html_e('External Apply', 'civi-framework') ?></option>
                <?php endif; ?>

                <?php if (!in_array('fields_jobs_apply_internal', $hide_jobs_apply)) : ?>
                    <option <?php if ($jobs_select_apply == "internal") {
                        echo 'selected';
                    } ?> value="internal"><?php esc_html_e('Internal Apply', 'civi-framework') ?></option>
                <?php endif; ?>

                <?php if (!in_array('fields_jobs_call_to_apply', $hide_jobs_apply)) : ?>
                    <option <?php if ($jobs_select_apply == "call-to") {
                        echo 'selected';
                    } ?> value="call-to"><?php esc_html_e('Call To Apply', 'civi-framework') ?></option>
                <?php endif; ?>
			</select>
		</div>
    </div>

    <?php if (!in_array('fields_jobs_apply_email', $hide_jobs_apply)) : ?>
        <div class="civi-section-apply-select form-group col-md-6" id="email">
            <label for="jobs_apply_email"><?php esc_html_e('Job apply email', 'civi-framework') ?></label>
            <input type="email" id="jobs_apply_email" name="jobs_apply_email"
                   value="<?php echo esc_attr($jobs_apply_email) ?>"
                   placeholder="<?php esc_attr_e('Enter email', 'civi-framework') ?>">
        </div>
    <?php endif; ?>

    <?php if (!in_array('fields_jobs_apply_external', $hide_jobs_apply)) : ?>
        <div class="civi-section-apply-select form-group col-md-6" id="external">
            <label for="jobs_apply_external"><?php esc_html_e('Job apply external', 'civi-framework') ?></label>
            <input type="url" id="jobs_apply_external" name="jobs_apply_external"
                   value="<?php echo esc_attr($jobs_apply_external) ?>"
                   placeholder="<?php esc_attr_e('Enter url', 'civi-framework') ?>">
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
                       value="<?php echo esc_attr($jobs_apply_call_to) ?>"
                       placeholder="<?php esc_attr_e('Enter phone', 'civi-framework') ?>">
            </div>
        </div>
    <?php endif; ?>



</div>
