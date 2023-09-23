<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!is_user_logged_in()) {
    echo civi_get_template_html('global/access-denied.php', array('type' => 'not_login'));
    return;
}
$allow_submit = civi_allow_submit();
if (!$allow_submit) {
    echo civi_get_template_html('global/access-denied.php', array('type' => 'not_permission'));
    return;
}
$candidate_package_id = isset($_GET['candidate_package_id']) ? absint(wp_unslash($_GET['candidate_package_id']))  : '';
$candidate_id   = isset($_GET['candidate_id']) ? absint(wp_unslash($_GET['candidate_id']))  : '';
if (empty($candidate_package_id) && empty($candidate_id)) {
    echo ("<script>location.href = '" . home_url() . "'</script>");
}
set_time_limit(700);
$candidate_paid_submission_type = civi_get_option('candidate_paid_submission_type');
?>
<div class="payment-wrap">
    <?php
    do_action('civi_candidate_payment_before');
    if ($candidate_paid_submission_type == 'candidate_per_package') {
        civi_get_template('candidate/payment/per-package.php');
    } else { ?>
        <p class="notice"><i class="fal fa-exclamation-circle"></i>
            <?php esc_html_e("You are on free submit active", 'civi-framework'); ?>
            <a href="<?php echo civi_get_permalink('submit_service'); ?>">
                <?php esc_html_e('Add Service', 'civi-framework'); ?>
            </a>
        </p>
    <?php }
    wp_nonce_field('civi_candidate_payment_ajax_nonce', 'civi_candidate_security_payment');
    do_action('civi_candidate_payment_after');
    ?>
</div>