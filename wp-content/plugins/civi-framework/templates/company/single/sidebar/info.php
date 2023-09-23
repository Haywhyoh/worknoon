<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $current_user;
$user_id = $current_user->ID;
$company_id = get_the_ID();
$post_author_id = get_post_field('post_author', $company_id);
$company_logo   = get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_logo');
$company_categories =  get_the_terms($company_id, 'company-categories');
$company_size =  get_the_terms($company_id,  'company-size');
$company_location =  get_the_terms($company_id, 'company-location');
$enable_social_twitter = civi_get_option('enable_social_twitter', '1');
$enable_social_linkedin = civi_get_option('enable_social_linkedin', '1');
$enable_social_facebook = civi_get_option('enable_social_facebook', '1');
$enable_social_instagram = civi_get_option('enable_social_instagram', '1');
$company_founded =  get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_founded');
$company_phone =  get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_phone');
$company_email =  get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_email');
$company_website =  get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_website');
$company_twitter   = get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_twitter');
$company_facebook   = get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_facebook');
$company_instagram   = get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_instagram');
$company_linkedin   = get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_linkedin');

$classes = array();
$check_package = civi_get_field_check_candidate_package('info_company');
$enable_sticky_sidebar_type = civi_get_option('enable_sticky_company_sidebar_type', 1);
if ($enable_sticky_sidebar_type) {
    $classes[] = 'has-sticky';
};

$hide_info_company_fields = civi_get_option('hide_candidate_info_company_fields', array());
if (!is_array($hide_info_company_fields)) {
    $hide_info_company_fields = array();
}
if (in_array("civi_user_candidate", (array)$current_user->roles)) {
    $notice =  esc_attr__("Please renew the package to view", "civi-framework");
} else {
    $notice =  esc_attr__("Please access the role Candidate and purchase the package to view", "civi-framework");
}
?>
<?php if(($check_package == -1 || $check_package == 0) && $user_id != $post_author_id) {?>
    <?php if(in_array("categories", $hide_info_company_fields) && in_array("size", $hide_info_company_fields) && in_array("founded", $hide_info_company_fields)
    && in_array("location", $hide_info_company_fields) && in_array("phone", $hide_info_company_fields) && in_array("email", $hide_info_company_fields)  && in_array("social", $hide_info_company_fields)) : ?>
        <?php if (in_array("civi_user_candidate", (array)$current_user->roles)) { ?>
            <div class="jobs-company-sidebar block-archive-sidebar company-sidebar <?php echo implode(" ", $classes); ?>">
                <h3 class="title-company"><?php esc_html_e('Information', 'civi-framework'); ?></h3>
                <p class="notice">
                    <i class="fal fa-exclamation-circle"></i>
                    <?php esc_html_e("Please renew the package to see the full information", "civi-framework"); ?>
                </p>
            </div>
        <?php } else { ?>
            <div class="jobs-company-sidebar block-archive-sidebar company-sidebar <?php echo implode(" ", $classes); ?>">
                <h3 class="title-company"><?php esc_html_e('Information', 'civi-framework'); ?></h3>
                <p class="notice">
                    <i class="fal fa-exclamation-circle"></i>
                    <?php esc_html_e("Please access the role Candidate and purchase the package to view full information", "civi-framework"); ?>
                </p>
            </div>
        <?php } ?>
    <?php else: ?>
        <div class="jobs-company-sidebar block-archive-sidebar company-sidebar <?php echo implode(" ", $classes); ?>">
            <h3 class="title-company"><?php esc_html_e('Information', 'civi-framework'); ?></h3>
            <?php if (is_array($company_categories)) : ?>
                <div class="info">
                    <p class="title-info"><?php esc_html_e('Categories', 'civi-framework'); ?></p>
                    <div class="list-cate">
                        <?php if( !in_array("categories", $hide_info_company_fields)) :?>
                            <?php foreach ($company_categories as $categories) {
                                $cate_link = get_term_link($categories, 'jobs-categories'); ?>
                                <a href="<?php echo esc_url($cate_link); ?>" class="cate civi-link-bottom">
                                    <?php echo $categories->name; ?>
                                </a>
                            <?php } ?>
                        <?php else: ?>
                            *************
                            <a class="btn-add-to-message" href="#"
                               data-text="<?php echo $notice; ?>">
                                <i class="far fa-eye"></i>
                            </a>
                        <?php endif;?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (is_array($company_size)) : ?>
                <div class="info">
                    <p class="title-info"><?php esc_html_e('Company size', 'civi-framework'); ?></p>
                    <div class="list-cate">
                        <?php if( !in_array("size", $hide_info_company_fields)) :?>
                            <?php foreach ($company_size as $size) {
                                echo $size->name;
                            } ?>
                        <?php else: ?>
                            *************
                            <a class="btn-add-to-message" href="#"
                               data-text="<?php echo $notice; ?>">
                                <i class="far fa-eye"></i>
                            </a>
                        <?php endif;?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (!empty($company_founded[0])) : ?>
                <div class="info">
                    <p class="title-info"><?php esc_html_e('Founded in', 'civi-framework'); ?></p>
                    <p class="details-info">
                        <?php if( !in_array("founded", $hide_info_company_fields)) :?>
                            <?php echo $company_founded[0]; ?>
                        <?php else: ?>
                            *************
                            <a class="btn-add-to-message" href="#"
                               data-text="<?php echo $notice; ?>">
                                <i class="far fa-eye"></i>
                            </a>
                        <?php endif;?>
                    </p>
                </div>
            <?php endif; ?>
            <?php if (is_array($company_location)) : ?>
                <div class="info">
                    <p class="title-info"><?php esc_html_e('Location', 'civi-framework'); ?></p>
                    <p class="details-info">
                        <?php if( !in_array("location", $hide_info_company_fields)) :?>
                            <?php foreach ($company_location as $location) { ?>
                                <span><?php echo $location->name; ?></span>
                            <?php } ?>
                        <?php else: ?>
                            *************
                            <a class="btn-add-to-message" href="#"
                               data-text="<?php echo $notice; ?>">
                                <i class="far fa-eye"></i>
                            </a>
                        <?php endif;?>
                    </p>
                </div>
            <?php endif; ?>
            <?php if (!empty($company_phone[0])) : ?>
                <div class="info">
                    <p class="title-info"><?php esc_html_e('Phone', 'civi-framework'); ?></p>
                    <p class="details-info">
                        <?php if( !in_array("phone", $hide_info_company_fields)) :?>
                            <a href="tel:<?php echo $company_phone[0]; ?>"><?php echo $company_phone[0]; ?></a>
                        <?php else: ?>
                            ***********
                            <a class="btn-add-to-message" href="#"
                               data-text="<?php echo $notice; ?>">
                                <i class="far fa-eye"></i>
                            </a>
                        <?php endif;?>
                    </p>
                </div>
            <?php endif; ?>
            <?php if (!empty($company_email[0])) : ?>
                <div class="info">
                    <p class="title-info"><?php esc_html_e('Email', 'civi-framework'); ?></p>
                    <p class="details-info email">
                        <?php if( !in_array("email", $hide_info_company_fields)) :?>
                            <a href="mailto:<?php echo $company_email[0]; ?>"><?php echo $company_email[0]; ?></a>
                        <?php else: ?>
                            *************
                            <a class="btn-add-to-message" href="#"
                               data-text="<?php echo $notice; ?>">
                                <i class="far fa-eye"></i>
                            </a>
                        <?php endif;?>
                    </p>
                </div>
            <?php endif; ?>
            <ul class="list-social">
                <?php if( !in_array("social", $hide_info_company_fields)) :?>
                    <?php if (!empty($company_facebook[0]) && $enable_social_facebook == 1) :
                        var_dump($company_facebook);
                        ?>
                        <li><a href="<?php echo $company_facebook[0]; ?>"><i class="fab fa-facebook-f"></i></a></li>
                    <?php endif; ?>
                    <?php if (!empty($company_twitter[0]) && $enable_social_twitter == 1) : ?>
                        <li><a href="<?php echo $company_twitter[0]; ?>"><i class="fab fa-twitter"></i></a></li>
                    <?php endif; ?>
                    <?php if (!empty($company_linkedin[0]) && $enable_social_linkedin == 1) : ?>
                        <li><a href="<?php echo $company_linkedin[0]; ?>"><i class="fab fa-linkedin"></i></a></li>
                    <?php endif; ?>
                    <?php if (!empty($company_instagram[0]) && $enable_social_instagram == 1) : ?>
                        <li><a href="<?php echo $company_instagram[0]; ?>"><i class="fab fa-instagram"></i></a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if (!empty($company_facebook[0]) && $enable_social_facebook == 1) : ?>
                        <li><a class="btn-add-to-message" href="#" data-text="<?php echo $notice; ?>"><i class="fab fa-facebook-f"></i></a></li>
                    <?php endif; ?>
                    <?php if (!empty($company_twitter[0]) && $enable_social_twitter == 1) : ?>
                        <li><a class="btn-add-to-message" href="#" data-text="<?php echo $notice; ?>"><i class="fab fa-twitter"></i></a></li>
                    <?php endif; ?>
                    <?php if (!empty($company_linkedin[0]) && $enable_social_linkedin == 1) : ?>
                        <li><a class="btn-add-to-message" href="#" data-text="<?php echo $notice; ?>"><i class="fab fa-linkedin"></i></a></li>
                    <?php endif; ?>
                    <?php if (!empty($company_instagram[0]) && $enable_social_instagram == 1) : ?>
                        <li><a class="btn-add-to-message" href="#" data-text="<?php echo $notice; ?>"><i class="fab fa-instagram"></i></a></li>
                    <?php endif; ?>
                <?php endif;?>
                <?php civi_get_social_network($company_id,'company'); ?>
            </ul>
        </div>
    <?php endif; ?>
<?php } ?>
<?php if ($company_id !== '' && ($check_package == 1 || $check_package == 2 || $user_id == $post_author_id)) : ?>
    <div class="jobs-company-sidebar block-archive-sidebar company-sidebar <?php echo implode(" ", $classes); ?>">
        <h3 class="title-company"><?php esc_html_e('Information', 'civi-framework'); ?></h3>
        <?php if (is_array($company_categories)) : ?>
            <div class="info">
                <p class="title-info"><?php esc_html_e('Categories', 'civi-framework'); ?></p>
                <div class="list-cate">
                    <?php foreach ($company_categories as $categories) {
                        $cate_link = get_term_link($categories, 'jobs-categories'); ?>
                        <a href="<?php echo esc_url($cate_link); ?>" class="cate civi-link-bottom">
                            <?php echo $categories->name; ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (is_array($company_size)) : ?>
            <div class="info">
                <p class="title-info"><?php esc_html_e('Company size', 'civi-framework'); ?></p>
                <div class="list-cate">
                    <?php foreach ($company_size as $size) {
                        echo $size->name;
                    } ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($company_founded[0])) : ?>
            <div class="info">
                <p class="title-info"><?php esc_html_e('Founded in', 'civi-framework'); ?></p>
                <p class="details-info"><?php echo $company_founded[0]; ?></p>
            </div>
        <?php endif; ?>
        <?php if (is_array($company_location)) : ?>
            <div class="info">
                <p class="title-info"><?php esc_html_e('Location', 'civi-framework'); ?></p>
                <p class="details-info">
                    <?php foreach ($company_location as $location) { ?>
                        <span><?php echo $location->name; ?></span>
                    <?php } ?>
                </p>
            </div>
        <?php endif; ?>
        <?php if (!empty($company_phone[0])) : ?>
            <div class="info">
                <p class="title-info"><?php esc_html_e('Phone', 'civi-framework'); ?></p>
                <p class="details-info"><a href="tel:<?php echo $company_phone[0]; ?>"><?php echo $company_phone[0]; ?></a></p>
            </div>
        <?php endif; ?>
        <?php if (!empty($company_email[0])) : ?>
            <div class="info">
                <p class="title-info"><?php esc_html_e('Email', 'civi-framework'); ?></p>
                <p class="details-info email"><a href="mailto:<?php echo $company_email[0]; ?>"><?php echo $company_email[0]; ?></a></p>
            </div>
        <?php endif; ?>
        <ul class="list-social">
            <?php if (!empty($company_facebook[0]) && $enable_social_facebook == 1) : ?>
                <li><a href="<?php echo $company_facebook[0]; ?>"><i class="fab fa-facebook-f"></i></a></li>
            <?php endif; ?>
            <?php if (!empty($company_twitter[0]) && $enable_social_twitter == 1) : ?>
                <li><a href="<?php echo $company_twitter[0]; ?>"><i class="fab fa-twitter"></i></a></li>
            <?php endif; ?>
            <?php if (!empty($company_linkedin[0]) && $enable_social_linkedin == 1) : ?>
                <li><a href="<?php echo $company_linkedin[0]; ?>"><i class="fab fa-linkedin"></i></a></li>
            <?php endif; ?>
            <?php if (!empty($company_instagram[0]) && $enable_social_instagram == 1) : ?>
                <li><a href="<?php echo $company_instagram[0]; ?>"><i class="fab fa-instagram"></i></a></li>
            <?php endif; ?>
            <?php civi_get_social_network($company_id,'company'); ?>
        </ul>
    </div>
<?php endif; ?>