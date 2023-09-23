<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $current_user;
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$candidate_package_id = get_the_author_meta(CIVI_METABOX_PREFIX . 'candidate_package_id', $user_id);
$candidate_package_activate = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'candidate_package_activate_date', true);
$candidate_package_activate_date = civi_convert_date_format($candidate_package_activate);
$candidate_package_time_unit = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_time_unit', true);
$candidate_package_period = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_period', true);
$enable_package_service_unlimited_time = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'enable_package_service_unlimited_time', true);
$candidate_package_name = get_the_title($candidate_package_id);
$user_info = get_userdata($user_id);
$civi_candidate_package = new Civi_candidate_package();
$expired_date = $civi_candidate_package->get_expired_date($candidate_package_id, $user_id);
$check_candidate_package = $civi_candidate_package->user_candidate_package_available($user_id);
$candidate_paid_submission_type = civi_get_option('candidate_paid_submission_type');
$expired_date_format = date(get_option('date_format'), strtotime($expired_date));
$candidate_package_activate_date = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'candidate_package_activate_date', true);
$activate_date_format = date(get_option('date_format'), strtotime($candidate_package_activate_date));

$current_date = date('Y-m-d');
if ($current_date < $expired_date) {
    $seconds = strtotime($expired_date) - strtotime($current_date);
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    $expired_days = $dtF->diff($dtT)->format('%a');
} else {
    $expired_days = 0;
}
?>
<?php if ($candidate_paid_submission_type !== 'candidate_per_package') : ?>
    <p class="notice"><i class="fal fa-exclamation-circle"></i>
        <?php esc_html_e("You are on free submit active", 'civi-framework'); ?>
        <a href="<?php echo civi_get_permalink('submit_service'); ?>">
            <?php esc_html_e('Add Service', 'civi-framework'); ?>
        </a>
    </p>
<?php else : ?>
    <?php if ($current_date >= $expired_date) : ?>
        <p class="notice"><i class="fal fa-exclamation-circle"></i>
            <?php esc_html_e("Package expired. Please select a new one.", 'civi-framework'); ?>
        </p>
    <?php endif; ?>
    <div class="entry-my-page pakages-dashboard my-candidate-package">
        <div class="entry-title">
            <h4><?php esc_html_e('My Package', 'civi-framework') ?></h4>
            <?php if (civi_get_option('enable_post_type_service') === '1') {?>
                <a href="<?php echo civi_get_permalink('submit_service'); ?>"
                   class="civi-button button-outline-accent">
                    <i class="far fa-plus"></i><?php esc_html_e('Create new service', 'civi-framework') ?>
                </a>
            <?php } ?>
        </div>
        <div class="table-dashboard-wapper">
            <table class="table-dashboard <?php if($check_candidate_package == -1 || $check_candidate_package == 0) {
                echo 'expired';
            } ?>">
                <thead>
                <tr>
                    <th><?php esc_html_e('ID', 'civi-framework') ?></th>
                    <th><?php esc_html_e('Package Name', 'civi-framework') ?></th>
                    <th><?php esc_html_e('Status', 'civi-framework') ?></th>
                    <th><?php esc_html_e('Activation Date', 'civi-framework') ?></th>
                    <th><?php esc_html_e('Expiration Date', 'civi-framework') ?></th>
                    <th><?php esc_html_e('Date Remaining', 'civi-framework') ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <span class="package-id">
                            <?php if ($candidate_package_id) {
                                echo "#$candidate_package_id";
                            } ?>
                        </span>
                    </td>
                    <td>
                        <h3>
                            <a href="<?php echo civi_get_permalink('package_candidate') ?>"><?php echo esc_attr($candidate_package_name) ?></a>
                        </h3>
                        <p><?php echo esc_attr($candidate_package_activate_date) ?></p>
                    </td>
                    <td>
                        <?php $package_status = civi_candidate_package_status();
                        if ($package_status === '0') { ?>
                            <span class="label label-pending"><?php esc_html_e('Pending', 'civi-framework') ?></span>
                        <?php } elseif($package_status === '-1') { ?>
                            <span class="label label-close"><?php esc_html_e('Canceled', 'civi-framework') ?></span>
                        <?php } else { ?>
                            <?php if (($current_date < $expired_date) || ($enable_package_service_unlimited_time == 1)) { ?>
                                <span class="label label-open"><?php esc_html_e('Actived', 'civi-framework') ?></span>
                            <?php } else { ?>
                                <span class="label label-close"><?php esc_html_e('Expired', 'civi-framework') ?></span>
                            <?php } ?>
                        <?php } ?>
                    </td>
                    <td>
                        <span class="active-date">
                            <?php if ($enable_package_service_unlimited_time == 1) {
                                esc_html_e('Unlimited', 'civi-framework');
                            } else {
                                echo $activate_date_format;
                            } ?>
                        </span>
                    </td>
                    <td>
                         <span class="expired-date">
                            <?php if ($enable_package_service_unlimited_time == 1) {
                                esc_html_e('Unlimited', 'civi-framework');
                            } else {
                                echo $expired_date_format;
                            } ?>
                        </span>
                    </td>
                    <td>
                        <span class="remaining">
                            <?php if ($enable_package_service_unlimited_time == 1) {
                                esc_html_e('Never Expires', 'civi-framework');
                            } else {
                                echo sprintf(esc_html__('%s Days', 'civi-framework'), $expired_days);
                            } ?>
                        </span>
                    </td>
                    <td>
                        <a href="#form-candidate-user-package" id="action-user-package">
                            <?php esc_html_e('Overview', 'civi-framework') ?>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <a href="<?php echo civi_get_permalink('candidate_package'); ?>" class="civi-button civi-new-package">
            <i class="far fa-plus"></i><?php esc_html_e('Add new package', 'civi-framework') ?>
        </a>
    </div>
<?php endif; ?>