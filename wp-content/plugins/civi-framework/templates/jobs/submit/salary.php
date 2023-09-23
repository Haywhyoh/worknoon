<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $hide_jobs_fields, $current_user;
$user_id = $current_user->ID;
$jobs_user_salary_show = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'jobs_user_salary_show', true);
$jobs_user_salary_minimum = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'jobs_user_salary_minimum', true);
$jobs_user_salary_maximum = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'jobs_user_salary_maximum', true);
$jobs_user_maximum_price = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'jobs_user_maximum_price', true);
$jobs_user_minimum_price = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'jobs_user_minimum_price', true);
?>
<div class="row">
    <div class="form-group col-md-6">
        <label><?php esc_html_e('Show pay by', 'civi-framework'); ?></label>
        <div class="select2-field">
            <select id="select-salary-pay" name="jobs_salary_show" class="civi-select2">
                <option <?php if ($jobs_user_salary_show == "range" || $jobs_user_salary_show == "") {
                    echo 'selected';
                } ?> value="range"><?php esc_html_e('Range', 'civi-framework'); ?></option>
                <!-- Remove other options -->
            </select>
        </div>
    </div>
    <div class="form-group col-md-6">
        <label><?php esc_html_e('Currency', 'civi-framework'); ?></label>
        <div class="select2-field">
            <select name="jobs_currency_type" class="civi-select2">
                <?php civi_get_select_currency_type(true); ?>
            </select>
        </div>
    </div>
    <div class="civi-section-salary-select" id="range">
        <div class="form-group col-md-6">
            <label for="jobs_salary_minimum"><?php esc_html_e('Minimum', 'civi-framework'); ?></label>
            <input type="text" id="jobs_salary_minimum" name="jobs_salary_minimum" pattern="^[0-9]+[Kk]?$"
                   value="<?php echo $jobs_user_salary_minimum; ?>">
            <p>Add 'K' to indicate thousands (e.g., 50K)</p>
        </div>
        <div class="form-group col-md-6">
            <label for="jobs_salary_maximum"><?php esc_html_e('Maximum', 'civi-framework'); ?></label>
            <input type="text" id="jobs_salary_maximum" name="jobs_salary_maximum" pattern="^[0-9]+[Kk]?$"
                   value="<?php echo $jobs_user_salary_maximum; ?>">
            <p>Add 'K' to indicate thousands (e.g., 80K)</p>
        </div>
    </div>
    <div class="civi-section-salary-select col-md-6" id="starting_amount">
        <label for="jobs_minimum_price"><?php esc_html_e('Minimum', 'civi-framework'); ?></label>
        <input type="text" id="jobs_minimum_price" name="jobs_minimum_price" pattern="^[0-9]+[Kk]?$"
               value="<?php echo $jobs_user_minimum_price; ?>">
        <p>Add 'K' to indicate thousands (e.g., 30K)</p>
    </div>
    <div class="civi-section-salary-select col-md-6" id="maximum_amount">
        <label for="jobs_maximum_price"><?php esc_html_e('Maximum', 'civi-framework'); ?></label>
        <input type="text" id="jobs_maximum_price" name="jobs_maximum_price" pattern="^[0-9]+[Kk]?$"
               value="<?php echo $jobs_user_maximum_price; ?>">
        <p>Add 'K' to indicate thousands (e.g., 60K)</p>
    </div>
    <!-- Remove the rate field -->
</div>
