<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$service_id = isset($_GET['service_id']) ? civi_clean(wp_unslash($_GET['service_id'])) : '';
global $current_user,$service_data,$service_meta_data,$current_user, $hide_service_fields;
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$user_demo = get_the_author_meta(CIVI_METABOX_PREFIX . 'user_demo', $user_id);
$civi_service_page_id = civi_get_option('civi_candidate_service_page_id');
wp_enqueue_script(CIVI_PLUGIN_PREFIX . 'service-submit');
wp_enqueue_script('jquery-validate');
wp_localize_script(
    CIVI_PLUGIN_PREFIX . 'service-submit',
    'civi_submit_vars',
    array(
        'ajax_url' => CIVI_AJAX_URL,
        'service_dashboard' => get_page_link($civi_service_page_id),
    )
);
$form = 'edit-service';
$service_data      = get_post($service_id);
$service_meta_data = get_post_custom($service_data->ID);

$hide_service_fields = civi_get_option('hide_service_fields', array());
if (!is_array($hide_service_fields)) {
    $hide_service_fields = array();
}
$layout = array('overview', 'addons', 'skills', 'faq');
$package_status = civi_candidate_package_status();

//Package
$candidate_paid_submission_type = civi_get_option('candidate_paid_submission_type');
$user_package_id = get_the_author_meta(CIVI_METABOX_PREFIX . 'candidate_package_id', $user_id);
$candidate_package_number_service = get_post_meta($user_package_id, CIVI_METABOX_PREFIX . 'candidate_package_number_service', true);
$enable_package_service_unlimited = get_post_meta($user_package_id, CIVI_METABOX_PREFIX . 'enable_package_service_unlimited', true);
$notice_text = $shortcode = '';
$civi_candidate_package = new Civi_candidate_package();
$get_expired_date = $civi_candidate_package->get_expired_date($user_package_id, $user_id);
$current_date = date('Y-m-d');
$d1 = strtotime($get_expired_date);
$d2 = strtotime($current_date);
if ($get_expired_date === 'Never Expires' || $get_expired_date === 'Unlimited') {
    $d1 = 999999999999999999999999;
}

if ($candidate_paid_submission_type == 'no') {
    if (in_array('civi_user_candidate', (array)$current_user->roles)) {
        $notice_text = esc_html__("Sorry, you can't view this page as Candidate, register Employer account to get access.", 'civi-framework');
    }
} else {
    if (in_array('civi_user_candidate', (array)$current_user->roles)) {
        $notice_text = esc_html__("Sorry, you can't view this page as Candidate, register Employer account to get access.", 'civi-framework');
    } elseif ((in_array('civi_user_employer', (array)$current_user->roles) && $user_package_id == '') || $d1 < $d2) {
        $notice_text = esc_html__("You have not purchased the package. Please choose 1 of the packages now.", 'civi-framework');
        $shortcode = '1';
    } elseif (in_array('civi_user_employer', (array)$current_user->roles) && $candidate_package_number_service < 1 && $enable_package_service_unlimited != '1') {
        $notice_text = esc_html__("The package you selected has reached its allowable limit. Please come back later!", 'civi-framework');
    }
}

$has_candidate_package = true;
if ($candidate_paid_submission_type == 'candidate_per_package') {
    $civi_candidate_package = new Civi_candidate_package();
    $check_candidate_package = $civi_candidate_package->user_candidate_package_available($user_id);
    if (($check_candidate_package == -1) || ($check_candidate_package == 0)) {
        $has_candidate_package = false;
    }
}
?>

<div class="entry-my-page submit-service-dashboard">
    <form action="#" method="post" id="submit_service_form" class="form-dashboard" enctype="multipart/form-data"
          data-titleerror="<?php echo esc_html__('Please enter service name', 'civi-framework'); ?>"
          data-deserror="<?php echo esc_html__('Please enter service description', 'civi-framework'); ?>"
          data-caterror="<?php echo esc_html__('Please choose category', 'civi-framework'); ?>"
          data-priceerror="<?php echo esc_html__('Please choose price', 'civi-framework'); ?>"
          data-timeerror="<?php echo esc_html__('Please choose time', 'civi-framework'); ?>">
        <div class="content-service tab-dashboard">
            <div class="row">
                <div class="col-lg-8 col-md-7">
                    <div class="submit-service-header civi-submit-header">
                        <div class="entry-title">
                            <h4><?php esc_html_e('Update Service', 'civi-framework') ?></h4>
                        </div>
                        <div class="button-warpper">
                            <a href="<?php echo civi_get_permalink('candidate_service'); ?>" class="civi-button button-link">
                                <?php esc_html_e('Cancel', 'civi-framework') ?>
                            </a>
                            <?php if ($user_demo == 'yes') : ?>
                                <button class="civi-button btn-add-to-message" data-text="<?php echo esc_attr('This is a "Demo" account, so you can not change it', 'civi-framework'); ?>">
                                    <span><?php esc_html_e('Update', 'civi-framework'); ?></span>
                                </button>
                            <?php else : ?>
                                <?php if ($package_status == 1) { ?>
                                    <?php if (($has_candidate_package && $candidate_package_number_service > 0) || $candidate_paid_submission_type !== 'candidate_per_package') { ?>
                                        <button type="submit" class="btn-submit-service civi-button" name="submit_service">
                                            <span><?php esc_html_e('Update', 'civi-framework'); ?></span>
                                            <span class="btn-loading"><i class="fal fa-spinner fa-spin large"></i></span>
                                        </button>
                                    <?php } else { ?>
                                        <a href="<?php echo civi_get_permalink('candidate_package'); ?>" class="civi-button package-out-stock"><?php esc_html_e('Upgrade now', 'civi-framework'); ?></a>
                                    <?php } ?>
                                <?php } else { ?>
                                    <a href="<?php echo civi_get_permalink('candidate_user_package'); ?>" class="civi-button"><?php esc_html_e('Pending', 'civi-framework'); ?></a>
                                <?php } ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <ul class="tab-list service-submit-tab">
                        <li class="tab-item">
                            <a href="#tab-overview"><?php esc_html_e('Overview', 'civi-framework') ?></a>
                        </li>
                        <?php if (!in_array('fields_service_skills', $hide_service_fields)) : ?>
                            <li class="tab-item">
                                <a href="#tab-skills"><?php esc_html_e('Skills', 'civi-framework') ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if (!in_array('fields_service_addons', $hide_service_fields)) : ?>
                            <li class="tab-item">
                                <a href="#tab-addons"><?php esc_html_e('Add-Ons', 'civi-framework') ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if (!in_array('fields_service_faq', $hide_service_fields)) : ?>
                            <li class="tab-item">
                                <a href="#tab-faq"><?php esc_html_e('FAQ', 'civi-framework') ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>

                    <?php foreach ($layout as $value) { ?>
                        <div id="tab-<?php echo $value; ?>" class="tab-info">
                            <?php civi_get_template('service/edit/' . $value . '.php'); ?>
                        </div>
                    <?php } ?>

                    <?php wp_nonce_field('civi_submit_service_action', 'civi_submit_service_nonce_field'); ?>

                    <input type="hidden" name="service_form" value="<?php echo esc_attr($form); ?>"/>
                    <input type="hidden" name="service_id" value="<?php echo esc_attr($service_id); ?>"/>
                </div>
            </div>
        </div>
    </form>
</div>