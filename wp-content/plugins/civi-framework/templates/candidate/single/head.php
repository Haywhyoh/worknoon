<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $current_user;
wp_enqueue_script(CIVI_PLUGIN_PREFIX . 'candidate-print');
$candidate_id = get_the_ID();
$candidate_location = get_the_terms($candidate_id, 'candidate_locations');
$candidate_categories = get_the_terms($candidate_id, 'candidate_categories');
$candidate_resume = wp_get_attachment_url(get_post_meta($candidate_id, CIVI_METABOX_PREFIX . 'candidate_resume_id_list', true));
$candidate_featured = get_post_meta($candidate_id, CIVI_METABOX_PREFIX . 'candidate_featured', true);
$candidate_current_position = get_post_meta($candidate_id, CIVI_METABOX_PREFIX . 'candidate_current_position', true);
$author_id = get_post_field('post_author', $candidate_id);
$candidate_avatar = get_the_author_meta('author_avatar_image_url', $author_id);
$candidate_website = get_post_meta($candidate_id, CIVI_METABOX_PREFIX . 'candidate_website', true);
$offer_salary = !empty(get_post_meta($candidate_id, CIVI_METABOX_PREFIX . 'candidate_offer_salary')) ? get_post_meta($candidate_id, CIVI_METABOX_PREFIX . 'candidate_offer_salary')[0] : '';
$enable_download_cv = civi_get_option('enable_single_candidate_download_cv','0');

$check_package_download_cv = civi_get_field_check_employer_package('download_cv');
$check_package_invite = civi_get_field_check_employer_package('invite');
$check_package_print = civi_get_field_check_employer_package('print');
?>
<div class="block-archive-inner candidate-head-details">
    <div class="civi-candidate-header-top">
        <?php if (!empty($candidate_avatar)) : ?>
            <img class="image-candidates" src="<?php echo esc_attr($candidate_avatar) ?>" alt=""/>
        <?php else : ?>
            <div class="image-candidates"><i class="far fa-camera"></i></div>
        <?php endif; ?>
        <div class="info">
            <div class="title-wapper">
                <?php if (!empty(get_the_title())) : ?>
                    <h1><?php echo get_the_title(); ?></h1>
                    <?php if ($candidate_featured == 1) : ?>
                        <span class="tooltip" data-title="<?php echo esc_attr('Featured', 'civi-framework') ?>"><i
                                    class="fas fa-check"></i></span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="candidate-info">
                <?php if (!empty($candidate_current_position)) { ?>
                    <div class="candidate-current-position">
                        <?php esc_html_e($candidate_current_position); ?>
                    </div>
                <?php } ?>
                <?php if (is_array($candidate_location)) { ?>
                    <div class="candidate-warpper">
                        <i class="fas fa-map-marker-alt"></i>
                        <?php foreach ($candidate_location as $location) {
                            $cate_link = get_term_link($location, 'candidate_locations'); ?>
                            <div class="cate-warpper">
                                <a href="<?php echo esc_url($cate_link); ?>" class="cate civi-link-bottom">
                                    <?php echo $location->name; ?>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <?php if (!empty($offer_salary)) { ?>
                    <div class="candidate-warpper salary">
                        <i class="fas fa-money-bill-alt"></i>
                        <?php civi_get_salary_candidate($candidate_id); ?>
                    </div>
                <?php } ?>
                <?php echo civi_get_total_rating('candidate', $candidate_id); ?>
            </div>
        </div>
    </div>
    <div class="civi-candidate-header-bottom">
        <?php civi_get_template('candidate/follow.php', array(
            'candidate_id' => $candidate_id,
        )); ?>

        <?php if (is_user_logged_in() && in_array('civi_user_employer', (array)$current_user->roles)) { ?>
            <?php if($check_package_print == -1 || $check_package_print == 0) {?>
                <a href="#" class="civi-button btn-add-to-message button-outline" data-text="<?php echo esc_attr('Package expired. Please select a new one.', 'civi-framework'); ?>">
                    <i class="fal fa-file-pdf"></i>
                    <?php esc_html_e('Save to PDF', 'civi-framework') ?>
                </a>
            <?php } else { ?>
                <a href="#" class="civi-button button-outline" id="btn-print-candidate" data-candidate-id="<?php echo $candidate_id; ?>">
                    <i class="fal fa-file-pdf"></i>
                    <?php esc_html_e('Save to PDF', 'civi-framework') ?>
                </a>
            <?php } ?>
        <?php } else { ?>
            <div class="logged-out">
                <a href="#popup-form" class="civi-button button-outline btn-login notice-employer"
                   data-notice="<?php esc_attr_e('Please login role Employer to view', 'civi-framework') ?>">
                    <i class="fal fa-file-pdf"></i>
                    <?php esc_html_e('Save to PDF', 'civi-framework') ?>
                </a>
            </div>
        <?php } ?>

        <?php if($enable_download_cv === '1') { ?>
            <?php if (is_user_logged_in() && in_array('civi_user_employer', (array)$current_user->roles)) { ?>
                <?php if(!empty($candidate_resume)){ ?>
                    <?php if($check_package_download_cv == -1 || $check_package_download_cv == 0) {?>
                        <a href="#" class="civi-button  btn-add-to-message button-outline" data-text="<?php echo esc_attr('Package expired. Please select a new one.', 'civi-framework'); ?>">
                            <i class="fal fa-download"></i>
                            <?php esc_html_e('Download CV', 'civi-framework') ?>
                        </a>
                    <?php } else { ?>
                        <a href="<?php echo $candidate_resume ?>" class="civi-button button-outline">
                            <i class="fal fa-download"></i>
                            <?php esc_html_e('Download CV', 'civi-framework') ?>
                        </a>
                    <?php } ?>
                <?php } ?>
            <?php } else { ?>
                <div class="logged-out">
                    <a href="#popup-form" class="civi-button button-outline btn-login notice-employer"
                       data-notice="<?php esc_attr_e('Please login role Employer to view', 'civi-framework') ?>">
                        <i class="fal fa-download"></i>
                        <?php esc_html_e('Download CV', 'civi-framework') ?>
                    </a>
                </div>
            <?php } ?>
        <?php } ?>

        <?php if (is_user_logged_in() && in_array('civi_user_employer', (array)$current_user->roles)) { ?>
            <?php if($check_package_invite == -1 || $check_package_invite == 0) {?>
                <a href="#" class="civi-button button-outline btn-add-to-message" data-text="<?php echo esc_attr('Package expired. Please select a new one.', 'civi-framework'); ?>">
                    <i class="fal fa-seedling"></i>
                    <?php esc_html_e('Invite', 'civi-framework') ?>
                </a>
            <?php } else { ?>
                <a href="#" class="civi-button button-outline" id="btn-invite-candidate">
                    <i class="fal fa-seedling"></i>
                    <?php esc_html_e('Invite', 'civi-framework') ?>
                </a>
            <?php } ?>
        <?php } else { ?>
            <div class="logged-out">
                <a href="#popup-form" class="civi-button button-outline btn-login notice-employer"
                   data-notice="<?php esc_attr_e('Please login role Employer to view', 'civi-framework') ?>">
                    <i class="fal fa-seedling"></i>
                    <?php esc_html_e('Invite', 'civi-framework') ?>
                </a>
            </div>
        <?php } ?>
        <?php civi_get_template('candidate/messages.php', array(
            'candidate_id' => $candidate_id,
        )); ?>
    </div>
    <?php civi_custom_field_single_candidate('info'); ?>
</div>
