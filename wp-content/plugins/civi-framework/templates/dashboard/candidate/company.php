<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!is_user_logged_in()) {
    civi_get_template('global/access-denied.php', array('type' => 'not_login'));
    return;
}

wp_enqueue_script(CIVI_PLUGIN_PREFIX . 'my-follow');
wp_localize_script(
    CIVI_PLUGIN_PREFIX . 'my-follow',
    'civi_my_follow_vars',
    array(
        'ajax_url'    => CIVI_AJAX_URL,
        'not_company'   => esc_html__('No company found', 'civi-framework'),
    )
);

global $current_user;
$user_id = $current_user->ID;
$my_follow    = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'my_follow', true);
$posts_per_page = 10;
$user_demo = get_the_author_meta(CIVI_METABOX_PREFIX . 'user_demo', $user_id);

if (empty($my_follow)) {
    $my_follow = array(0);
}
$args = array(
    'post_type'           => 'company',
    'post__in'            => $my_follow,
    'ignore_sticky_posts' => 1,
    'posts_per_page'      => $posts_per_page,
    'offset'              => (max(1, get_query_var('paged')) - 1) * $posts_per_page,
);
$data = new WP_Query($args);
?>

<div class="civi-my-follow entry-my-page">
    <div class="entry-title">
        <h4><?php esc_html_e('My Following', 'civi-framework'); ?></h4>
    </div>
    <div class="search-dashboard-warpper">
        <div class="search-left">
            <div class="action-search">
                <input class="search-control" type="text" name="company_search" placeholder="<?php esc_attr_e('Search company title', 'civi-framework') ?>">
                <button class="btn-search">
                    <i class="far fa-search"></i>
                </button>
            </div>
        </div>
        <div class="search-right">
            <label class="text-sorting"><?php esc_html_e('Sort by', 'civi-framework') ?></label>
            <div class="select2-field">
				<select class="search-control action-sorting civi-select2" name="company_sort_by">
					<option value="newest"><?php esc_html_e('Newest', 'civi-framework') ?></option>
					<option value="oldest"><?php esc_html_e('Oldest', 'civi-framework') ?></option>
				</select>
			</div>
        </div>
    </div>
    <?php if ($data->have_posts()) { ?>
        <div class="table-dashboard-wapper">
            <table class="table-dashboard" id="my-follow">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Name', 'civi-framework') ?></th>
                        <th><?php esc_html_e('Founded Date', 'civi-framework') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($data->have_posts()) : $data->the_post();
                        $id = get_the_ID();
                        global $current_user;
                        wp_get_current_user();
                        $user_id = $current_user->ID;
                        $company_categories =  get_the_terms($id, 'company-categories');
                        $company_location =  get_the_terms($id, 'company-location');
                        $company_logo   = get_post_meta($id, CIVI_METABOX_PREFIX . 'company_logo');
                        $public_date = get_the_date(get_option('date_format'));
                    ?>
                        <tr>
                            <td>
                                <div class="company-header">
                                    <div class="img-comnpany">
                                        <?php if (!empty($company_logo[0]['url'])) : ?>
                                            <img class="logo-comnpany" src="<?php echo $company_logo[0]['url'] ?>" alt="" />
                                        <?php else : ?>
                                            <div class="logo-comnpany"><i class="far fa-camera"></i></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="info-company">
                                        <h3 class="title-company-dashboard">
                                            <a href="<?php echo get_the_permalink($id); ?>">
                                                <?php echo get_the_title($id); ?>
                                            </a>
                                        </h3>
                                        <p>
                                            <?php if (is_array($company_categories)) {
                                                foreach ($company_categories as $categories) { ?>
                                                    <?php esc_html_e($categories->name); ?>
                                            <?php }
                                            } ?>
                                            <?php if (is_array($company_location)) {
                                                foreach ($company_location as $location) { ?>
                                                    <?php esc_html_e('/ ' . $location->name); ?>
                                            <?php }
                                            } ?>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="table-time">
                                <span class="start-time"><?php echo $public_date ?></span>
                            </td>
                            <?php
                            ?>
                            <td class="action-setting company-control">
                                <a href="#" class="icon-setting"><i class="fal fa-ellipsis-h"></i></a>
                                <ul class="action-dropdown">
                                    <?php if ($user_demo == 'yes') : ?>
                                        <li><a class="btn-add-to-message" data-text="<?php echo esc_attr('This is a "Demo" account so you not cant delete it', 'civi-framework'); ?>" href="#"><?php esc_html_e('Delete', 'civi-framework') ?></a></li>
                                    <?php else : ?>
                                        <li><a class="btn-delete" company-id="<?php echo esc_attr($id); ?>" href="#"><?php esc_html_e('Delete', 'civi-framework') ?></a></li>
                                    <?php endif; ?>
                                </ul>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="civi-loading-effect"><span class="civi-dual-ring"></span></div>
        </div>
    <?php } else { ?>
        <div class="item-not-found"><?php esc_html_e('No item found', 'civi-framework'); ?></div>
    <?php } ?>
    <?php $total_post = $data->found_posts;
    if ($total_post > $posts_per_page) { ?>
        <div class="pagination-dashboard">
            <?php $max_num_pages = $data->max_num_pages;
            civi_get_template('global/pagination.php', array('total_post' => $total_post, 'max_num_pages' => $max_num_pages, 'type' => 'dashboard', 'layout' => 'number'));
            wp_reset_postdata(); ?>
        </div>
    <?php } ?>

</div>
