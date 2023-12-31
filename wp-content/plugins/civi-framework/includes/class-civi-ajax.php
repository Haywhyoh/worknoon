<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Civi_Ajax')) {

    /**
     * Class Civi_Ajax
     */
    class Civi_Ajax
    {
        /**
         * Preview Job
         */
        public function preview_job()
        {
            ob_start();
            $post_id = isset($_REQUEST['id']) ? civi_clean(wp_unslash($_REQUEST['id'])) : '';
            $company_id = get_post_meta($post_id, CIVI_METABOX_PREFIX . 'jobs_select_company');
            $company_id = $company_id[0];
            $enable_social_twitter = civi_get_option('enable_social_twitter', '1');
            $enable_social_linkedin = civi_get_option('enable_social_linkedin', '1');
            $enable_social_facebook = civi_get_option('enable_social_facebook', '1');
            $enable_social_instagram = civi_get_option('enable_social_instagram', '1');
            if ($company_id !== '') {
                $company_logo   = get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_logo');
                $company_categories =  get_the_terms($company_id, 'company-categories');
                $company_founded =  get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_founded');
                $company_phone =  get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_phone');
                $company_email =  get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_email');
                $company_size =  get_the_terms($company_id,  'company-size');
                $company_website =  get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_website');
                $company_twitter   = get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_twitter');
                $company_facebook   = get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_facebook');
                $company_instagram   = get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_instagram');
                $company_linkedin   = get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_linkedin');
                $mycompany = get_post($company_id);
                $meta_query = civi_posts_company($company_id);
                $meta_query_post = civi_posts_company($company_id, 5);
                $company_location =  get_the_terms($company_id, 'company-location');
            }
?>
            <div id="jobs-<?php echo $post_id; ?>">
                <div class="block-jobs-warrper">
                    <div class="block-archive-top">
                        <?php
                        /**
                         * Hook: civi_preview_jobs_before_summary hook.
                         */
                        do_action('civi_preview_jobs_before_summary', $post_id); ?>
                        <div class="preview-tabs">
                            <ul class="tab-nav">
                                <li><a href="#job-detail" class="is-active"><?php esc_html_e('Job Detail', 'civi-framework'); ?></a></li>
                                <?php
                                if ($company_id !== '') {
                                ?>
                                    <li><a href="#company-overview"><?php esc_html_e('Company Overview', 'civi-framework'); ?></a></li>
                                <?php
                                }
                                ?>
                            </ul>
                            <div id="job-detail" class="tab-content is-active">
                                <?php
                                /**
                                 * Hook: civi_preview_jobs_summary hook.
                                 */
                                do_action('civi_preview_jobs_summary', $post_id);
                                ?>
                            </div>
                            <?php
                            if ($company_id !== '') {
                            ?>
                                <div id="company-overview" class="tab-content">
                                    <div class="company-overview">
                                        <h4 class="title"><?php esc_html_e('Overview', 'civi-framework'); ?></h4>
                                        <?php if (!empty($mycompany->post_content)) : ?>
                                            <div class="content"><?php echo $mycompany->post_content; ?><a href="#"><?php esc_html_e('Read more', 'civi-framework'); ?></a></div>
                                        <?php endif; ?>
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
                                                <p class="details-info company-phone"><a href="tel:<?php echo $company_phone[0]; ?>" data-phone="<?php echo $company_phone[0]; ?>"><?php echo substr($company_phone[0], 0, strlen($company_phone[0]) - 4); ?>****</a><i class="fal fa-eye"></i></p>
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
                                            <?php civi_get_social_network($company_id, 'company'); ?>
                                        </ul>
                                        <?php if (!empty($company_website[0])) :
                                            $remove_url = array("http://", "https://");
                                            $name_website = str_replace($remove_url, "", $company_website[0]);
                                        ?>
                                            <a href="<?php echo $company_website[0]; ?>" class="civi-button button-outline button-block button-visit" target="_blank"><?php esc_html_e('Visit ', 'civi-framework'); ?><?php echo $name_website ?><i class="fas fa-external-link"></i></a>
                                        <?php endif; ?>
                                        <?php civi_get_template('company/messages.php', array(
                                            'company_id' => $company_id,
                                        )); ?>
                                    </div>
                                    <div class="company-jobs">
                                        <h4 class="title"><?php esc_html_e('Jobs Opening', 'civi-framework'); ?></h4>
                                        <ul class="list-jobs">
                                            <?php foreach ($meta_query_post->posts as $post) {
                                                $id_job = $post->ID;
                                            ?>
                                                <li class="list-items">
                                                    <h6 class="title"><a href="<?php echo get_post_permalink($id_job) ?>"><?php echo get_the_title($id_job); ?></a></h6>
                                                    <div class="info-company">
                                                        <?php $jobs_categories = get_the_terms($post->ID, 'jobs-categories'); ?>
                                                        <?php if (is_array($jobs_categories)) { ?>
                                                            <div class="categories-warpper">
                                                                <?php foreach ($jobs_categories as $categories) {
                                                                    $cate_link = get_term_link($categories, 'jobs-categories'); ?>
                                                                    <div class="cate-warpper">
                                                                        <a href="<?php echo esc_url($cate_link); ?>" class="cate civi-link-bottom">
                                                                            <?php echo $categories->name; ?>
                                                                        </a>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </li>
                                            <?php }; ?>
                                        </ul>
                                        <a href="<?php echo esc_url(get_post_type_archive_link('jobs')) . '/?company_id=' . $company_id ?>" class="civi-button button-outline button-block">
                                            <?php esc_html_e('View all jobs', 'civi-framework'); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                    /**
                     * Hook: civi_after_content_single_jobs_summary hook.
                     */
                    do_action('civi_after_content_single_jobs_summary', $post_id);
                    ?>
                    <?php
                    /**
                     * Hook: civi_apply_single_jobs hook.
                     */
                    do_action('civi_apply_single_jobs', $post_id);
                    ?>
                </div>
            </div>
            <?php
            $content = ob_get_contents();
            ob_end_clean();
            echo json_encode(array('success' => true, 'job_id' => $job_id, 'content' => $content));
            wp_die();
        }

        /**
         * Jobs Archive
         */
        public function civi_jobs_archive_ajax()
        {
            $title = isset($_REQUEST['title']) ? civi_clean(wp_unslash($_REQUEST['title'])) : '';
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $sort_by = isset($_REQUEST['sort_by']) ? civi_clean(wp_unslash($_REQUEST['sort_by'])) : '';
            $categories = isset($_REQUEST['categories']) ? civi_clean(wp_unslash($_REQUEST['categories'])) : '';
            $types = isset($_REQUEST['types']) ? civi_clean(wp_unslash($_REQUEST['types'])) : '';
            $experience = isset($_REQUEST['experience']) ? civi_clean(wp_unslash($_REQUEST['experience'])) : '';
            $career = isset($_REQUEST['career']) ? civi_clean(wp_unslash($_REQUEST['career'])) : '';
            $skills = isset($_REQUEST['skills']) ? civi_clean(wp_unslash($_REQUEST['skills'])) : '';
            $salary_min = isset($_REQUEST['salary_min']) ? civi_clean(wp_unslash($_REQUEST['salary_min'])) : '';
            $salary_max = isset($_REQUEST['salary_max']) ? civi_clean(wp_unslash($_REQUEST['salary_max'])) : '';
            $salary_rate = isset($_REQUEST['salary_rate']) ? civi_clean(wp_unslash($_REQUEST['salary_rate'])) : '';
            $current_term = isset($_REQUEST['current_term']) ? civi_clean(wp_unslash($_REQUEST['current_term'])) : '';
            $type_term = isset($_REQUEST['type_term']) ? civi_clean(wp_unslash($_REQUEST['type_term'])) : '';
            $location = isset($_REQUEST['location']) ? civi_clean(wp_unslash($_REQUEST['location'])) : '';
            $location_country = isset($_REQUEST['location_country']) ? civi_clean(wp_unslash($_REQUEST['location_country'])) : '';
            $location_state = isset($_REQUEST['location_state']) ? civi_clean(wp_unslash($_REQUEST['location_state'])) : '';
            $location_city = isset($_REQUEST['location_city']) ? civi_clean(wp_unslash($_REQUEST['location_city'])) : '';
            $radius_cities = isset($_REQUEST['radius_cities']) ? civi_clean(wp_unslash($_REQUEST['radius_cities'])) : '';
            $jobs_layout = isset($_REQUEST['jobs_layout']) ? civi_clean(wp_unslash($_REQUEST['jobs_layout'])) : '';

            $meta_query = array();
            $tax_query = array();

            $args = array(
                'posts_per_page' => ($item_amount > 0) ? $item_amount : -1,
                'post_type' => 'jobs',
                'paged' => $paged,
                'meta_key' => 'civi-jobs_featured',
                'orderby' => 'meta_value date',
            );

            //Jobs expires
            $enable_jobs_show_expires = civi_get_option('enable_jobs_show_expires');
            if($enable_jobs_show_expires == 1){
                $args['post_status']  = array('publish','expired');
            } else {
                $args['post_status']  = 'publish';
            }

            $meta_query[] = array(
                array(
                    'key' => CIVI_METABOX_PREFIX . 'enable_jobs_package_expires',
                    'value' => 0,
                    'compare' => '=='
                )
            );
			$meta_query[] = array(
				'key' => CIVI_METABOX_PREFIX . 'jobs_featured', 
				'value' => 1, 
				'compare' => '!=', 
				'type' => 'NUMERIC',
			);

            //meta query jobs sort by
            if (!empty($sort_by)) {
                if ($sort_by == 'featured') {
                    $meta_query[] = array(
                        'key' => CIVI_METABOX_PREFIX . 'jobs_featured',
                        'value' => 1,
                        'type' => 'NUMERIC',
                        'compare' => '=',
                    );
                }

                if ($sort_by == 'oldest') {
                    $args['order'] = 'ASC';
                }

                if ($sort_by == 'newest') {
                    $args['order'] = 'DESC';
                }
            }

            if (!empty($title)) {
                $args['fields'] = 'ids';
                $args_search = $args_tax = $args;
                $args_search['s'] = $title;
                $data_search = new WP_Query($args_search);
                $args_tax['tax_query'] = array(
                    array(
                        'taxonomy' => 'jobs-skills',
                        'field' => 'name',
                        'terms' => $title,
                    )
                );
                $data_tax = new WP_Query($args_tax);
                $jobs_ids = array_merge($data_tax->posts, $data_search->posts);
                $jobs_ids = array_unique($jobs_ids);
                if (!empty($jobs_ids)) {
                    $args['post__in'] = $jobs_ids;
                } else {
                    $args['s'] = $title;
                }
            }

            //tax query current term
            if (!empty($current_term) && !empty($type_term)) {
                $tax_query[] = array(
                    'taxonomy' => $type_term,
                    'field' => 'id',
                    'terms' => $current_term
                );
            }

            //tax query jobs categories
            if (!empty($categories)) {
                $tax_query[] = array(
                    'taxonomy' => 'jobs-categories',
                    'field' => 'term_id',
                    'terms' => $categories
                );
            }

            //location country
            if (!empty($location_country)) {
                $taxonomy_state = get_categories(
                    array(
                        'taxonomy' => 'jobs-state',
                        'hide_empty' => false,
                        'parent' => 0,
                        'meta_query' => array(
                            array(
                                'key' => 'jobs-state-country',
                                'value' => $location_country,
                                'compare' => '=',
                            )
                        )
                    )
                );

                if(!empty($taxonomy_state)){
                    $keys_state = array();
                    foreach ($taxonomy_state as $terms_state) {
                        $keys_state[] = $terms_state->term_id;
                    }
                    $taxonomy_city = get_categories(
                        array(
                            'taxonomy' => 'jobs-location',
                            'meta_query' => array(
                                array(
                                    'key' => 'jobs-location-state',
                                    'value' => $keys_state,
                                    'compare' => 'IN'
                                )
                            )
                        )
                    );
                    $keys_city = array();
                    foreach ($taxonomy_city as $terms_city) {
                        $keys_city[] = $terms_city->term_id;
                    }
                } else {
                    $keys_city = '';
                }
                $tax_query[] = array(
                    'taxonomy' => 'jobs-location',
                    'field' => 'term_id',
                    'terms' => $keys_city
                );
            }

            //location state
            if (!empty($location_state)) {
                $taxonomy_terms = get_categories(
                    array(
                        'taxonomy' => 'jobs-location',
                        'meta_query' => array(
                            array(
                                'key' => 'jobs-location-state',
                                'value' => $location_state,
                                'compare' => '=',
                            )
                        )
                    )
                );
                $keys = array();
                foreach ($taxonomy_terms as $terms) {
                    $keys[] = $terms->term_id;
                }
                $tax_query[] = array(
                    'taxonomy' => 'jobs-location',
                    'field' => 'term_id',
                    'terms' => $keys
                );
            }

            //Location city
            if (!empty($location_city)) {
                $tax_query[] = array(
                    'taxonomy' => 'jobs-location',
                    'field' => 'term_id',
                    'terms' => $location_city
                );
            }

            //tax query jobs search location
            if (!empty($location) && ($radius_cities == 0 || empty($radius_cities))) {
                $tax_query[] = array(
                    'taxonomy' => 'jobs-location',
                    'field' => 'name',
                    'terms' => $location
                );
            }

            //Nearby cities
            $resultsCities = array();
            if (!empty($radius_cities) && $radius_cities !== 0 && !empty($location)) {
                $taxonomies = get_categories(
                    array(
                        'taxonomy' => 'jobs-location',
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => true,
                        'parent' => 0
                    )
                );

                if (!empty($taxonomies)) {
                    $resultsCities = civi_find_nearby_cities($location, intval($radius_cities));
                    if(empty($resultsCities)) {
                        $resultsCities = $location;
                    }
                    $tax_query[] = array(
                        'taxonomy' => 'jobs-location',
                        'field' => 'name',
                        'terms' => $resultsCities,
                    );
                }
            }

            //tax query jobs types
            if (!empty($types)) {
                $tax_query[] = array(
                    'taxonomy' => 'jobs-type',
                    'field' => 'term_id',
                    'terms' => $types
                );
            }

            //tax query jobs experience
            if (!empty($experience)) {
                $tax_query[] = array(
                    'taxonomy' => 'jobs-experience',
                    'field' => 'term_id',
                    'terms' => $experience
                );
            }

            //tax query jobs career
            if (!empty($career)) {
                $tax_query[] = array(
                    'taxonomy' => 'jobs-career',
                    'field' => 'term_id',
                    'terms' => $career
                );
            }

            //tax query jobs skills
            if (!empty($skills)) {
                $tax_query[] = array(
                    'taxonomy' => 'jobs-skills',
                    'field' => 'term_id',
                    'terms' => $skills
                );
            }

            switch ($salary_rate) {
                case "days":
                    $salary_min = intval($salary_min) / 24;
                    $salary_max = intval($salary_max) / 24;
                    break;
                case "week":
                    $salary_min = intval($salary_min) / (24 * 7);
                    $salary_max = intval($salary_max) / (24 * 7);
                    break;
                case "month":
                    $salary_min = intval($salary_min) / (24 * 7 * 4);
                    $salary_max = intval($salary_max) / (24 * 7 * 4);
                    break;
                case "year":
                    $salary_min = intval($salary_min) / (24 * 7 * 4 * 12);
                    $salary_max = intval($salary_max) / (24 * 7 * 4 * 12);
                    break;
                default:
                    $salary_min = intval($salary_min);
                    $salary_max = intval($salary_max);
            }

            //meta query jobs salary
            if (!empty($salary_rate)) {
                if (empty($salary_min) && empty($salary_max)) {
                    $meta_query[] = array(
                        'relation' => 'AND',
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_salary_rate',
                            'value' => $salary_rate,
                            'compare' => '=',
                        ),
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_salary_show',
                            'value' => 'agree',
                            'compare' => '!=',
                        ),
                    );
                }
                if (!empty($salary_min) && empty($salary_max)) {
                    $meta_query[] = array(
                        'relation' => 'OR',
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_salary_convert_min',
                            'value' => $salary_min,
                            'type' => 'NUMERIC',
                            'compare' => '>=',
                        ),
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_price_convert_min',
                            'value' => $salary_min,
                            'type' => 'NUMERIC',
                            'compare' => '>=',
                        ),
                    );
                }

                if (!empty($salary_max) && empty($salary_min)) {
                    $meta_query[] = array(
                        'relation' => 'OR',
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_salary_convert_max',
                            'value' => array(1, $salary_max),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN',
                        ),
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_price_convert_max',
                            'value' => array(1, $salary_max),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN',
                        ),
                    );
                }

                if (!empty($salary_max) && !empty($salary_min)) {
                    $meta_query[] = array(
                        'relation' => 'OR',
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_salary_convert_max',
                            'value' => array($salary_min, $salary_max),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN',
                        ),
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_price_convert_max',
                            'value' => array($salary_min, $salary_max),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN',
                        ),
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_salary_convert_min',
                            'value' => array($salary_min, $salary_max),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN',
                        ),
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_price_convert_min',
                            'value' => array($salary_min, $salary_max),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN',
                        ),
                    );
                }
            } else {
                if (!empty($salary_min) && empty($salary_max)) {
                    $meta_query[] = array(
                        'relation' => 'OR',
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_salary_minimum',
                            'value' => $salary_min,
                            'type' => 'NUMERIC',
                            'compare' => '>=',
                        ),
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_minimum_price',
                            'value' => $salary_min,
                            'type' => 'NUMERIC',
                            'compare' => '>=',
                        ),
                    );
                }

                if (!empty($salary_max) && empty($salary_min)) {
                    $meta_query[] = array(
                        'relation' => 'OR',
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_salary_maximum',
                            'value' => array(1, $salary_max),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN',
                        ),
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_maximum_price',
                            'value' => array(1, $salary_max),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN',
                        ),
                    );
                }

                if (!empty($salary_max) && !empty($salary_min)) {
                    $meta_query[] = array(
                        'relation' => 'OR',
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_salary_maximum',
                            'value' => array($salary_min, $salary_max),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN',
                        ),
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_maximum_price',
                            'value' => array($salary_min, $salary_max),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN',
                        ),
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_salary_minimum',
                            'value' => array($salary_min, $salary_max),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN',
                        ),
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_minimum_price',
                            'value' => array($salary_min, $salary_max),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN',
                        ),
                    );
                }
            }

            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $tax_count = count($tax_query);
            if ($tax_count > 0) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            $args_map = $args;
            $args_map['paged'] = '';
            $args_map['posts_per_page'] = '-1';

            $data = new WP_Query($args);
            $data_map = new WP_Query($args_map);
            $total_post = $data->found_posts;
            $jobs_html = $filter_html = '';
            $jobs = array();

            if (!empty($total_post)) {
                if (!empty($title)) {
                    $count_post = sprintf(esc_html__('%1$s jobs for "%2$s"', 'civi-framework'), '<span class="count">' . $total_post . '</span>', $title);
                } else {
                    $count_post = sprintf(_n('%s Jobs', '%s Jobs', $total_post, 'civi-framework'), '<span class="count">' . esc_html($total_post) . '</span>');
                }
            } else {
                $count_post = esc_html__('0 jobs', 'civi-framework');
            }

            if (!empty($current_term)) {
                $count_post = sprintf(_n('%s Jobs', '%s Jobs', $total_post, 'civi-framework'), '<span class="count">' . esc_html($total_post) . '</span>');
                if (empty($total_post)) {
                    $count_post = sprintf(__('%s Jobs', 'civi-framework'), $total_post);
                }
            }

            $max_num_pages = $data->max_num_pages;
            $pagination_type = civi_get_option('jobs_pagination_type', 'loadmore');
            if ($pagination_type == 'number') {
                $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                    'total' => $max_num_pages,
                    'current' => $paged,
                    'mid_size' => 1,
                    'type' => 'array',
                    'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                    'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
                )));
            } else {
                $pagination = '<a class="page-numbers next" href="#"><span>' . __('Load More', 'civi-framework') . '</span><span class="btn-loading"><i class="fal fa-spinner fa-spin large"></i></span></a>';
            }

            $hidden_pagination = '';
            if ($paged == $max_num_pages) {
                $hidden_pagination = 1;
            }

            if ($total_post > 0) {

                while ($data_map->have_posts()) : $data_map->the_post();

                    $jobs_id = get_the_ID();

                    $jobs_meta_data = get_post_custom($jobs_id);

                    $map_zoom_level = civi_get_option('map_zoom_level', '15');

                    $jobs_address = isset($jobs_meta_data[CIVI_METABOX_PREFIX . 'jobs_address']) ? $jobs_meta_data[CIVI_METABOX_PREFIX . 'jobs_address'][0] : '';
                    $jobs_location = get_post_meta($jobs_id, CIVI_METABOX_PREFIX . 'jobs_location', true);

                    if (!empty($jobs_location['location'])) {
                        $lat_lng = explode(',', $jobs_location['location']);
                    } else {
                        $lat_lng = array();
                    }

                    $jobs_select_company = get_post_meta($jobs_id, CIVI_METABOX_PREFIX . 'jobs_select_company');
                    $company_id = isset($jobs_select_company[0]) ? $jobs_select_company[0] : '';
                    $company_logo = get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_logo');

                    $marker_icon = '';
                    if (!empty($company_logo[0]['url'])) {
                        $marker_icon = $company_logo[0]['url'];
                    } else {
                        $marker_icon = CIVI_PLUGIN_URL . 'assets/images/map-marker-icon.png';
                    };

                    $html_jobs = ob_start();
                    civi_get_template('content-jobs.php', array(
                        'jobs_id' => $jobs_id,
                        'jobs_layout' => 'layout-grid',
                        'effect_class' => '',
                    ));
                    $html_jobs = ob_get_clean();

                    $prop = new stdClass();
                    $prop->id = $jobs_id;
                    $prop->lat = isset($lat_lng[0]) ? $lat_lng[0] : 59.325;
                    $prop->lng = isset($lat_lng[1]) ? $lat_lng[1] : 18.070;
                    $prop->jobs = $html_jobs;

                    if (empty($jobs_url)) {
                        $jobs_url = CIVI_PLUGIN_URL . 'assets/images/map-marker-icon.png';
                        $default_marker = civi_get_option('marker_icon', '');
                        if ($default_marker != '') {
                            if (is_array($default_marker) && $default_marker['url'] != '') {
                                $jobs_url = $default_marker['url'];
                            }
                        }
                    }

                    if ($marker_icon) {
                        $prop->marker_icon = $marker_icon;
                    } else {
                        $prop->marker_icon = $jobs_url;
                    }

                    array_push($jobs, $prop);

                endwhile;
            }
            wp_reset_postdata();

            $jobs_html = ob_start();

            if ($total_post > 0) {
                while ($data->have_posts()) : $data->the_post();

                    civi_get_template('content-jobs.php', array(
                        'jobs_layout' => $jobs_layout,
                    ));

                endwhile;
            }
            wp_reset_postdata();

            $jobs_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true,'resultsCities'=> $resultsCities, 'jobs' => $jobs, 'count_jobs' => count($jobs), 'pagination' => $pagination, 'hidden_pagination' => $hidden_pagination, 'pagination_type' => $pagination_type, 'jobs_html' => $jobs_html, 'total_post' => $total_post, 'count_post' => $count_post));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post, 'count_post' => $count_post));
            }
            wp_die();
        }

        /**
         * Company Archive
         */
        public function civi_company_archive_ajax()
        {
            $title = isset($_REQUEST['title']) ? civi_clean(wp_unslash($_REQUEST['title'])) : '';
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $sort_by = isset($_REQUEST['sort_by']) ? civi_clean(wp_unslash($_REQUEST['sort_by'])) : '';
            $current_term = isset($_REQUEST['current_term']) ? civi_clean(wp_unslash($_REQUEST['current_term'])) : '';
            $type_term = isset($_REQUEST['type_term']) ? civi_clean(wp_unslash($_REQUEST['type_term'])) : '';
            $size = isset($_REQUEST['size']) ? civi_clean(wp_unslash($_REQUEST['size'])) : '';
            $rating = isset($_REQUEST['rating']) ? civi_clean(wp_unslash($_REQUEST['rating'])) : '';
            $range_min = isset($_REQUEST['range_min']) ? civi_clean(wp_unslash($_REQUEST['range_min'])) : '';
            $range_max = isset($_REQUEST['range_max']) ? civi_clean(wp_unslash($_REQUEST['range_max'])) : '';
            $founded = isset($_REQUEST['founded']) ? civi_clean(wp_unslash($_REQUEST['founded'])) : '';
            $location = isset($_REQUEST['location']) ? civi_clean(wp_unslash($_REQUEST['location'])) : '';
            $location_country = isset($_REQUEST['location_country']) ? civi_clean(wp_unslash($_REQUEST['location_country'])) : '';
            $location_state = isset($_REQUEST['location_state']) ? civi_clean(wp_unslash($_REQUEST['location_state'])) : '';
            $location_city = isset($_REQUEST['location_city']) ? civi_clean(wp_unslash($_REQUEST['location_city'])) : '';
            $radius_cities = isset($_REQUEST['radius_cities']) ? civi_clean(wp_unslash($_REQUEST['radius_cities'])) : '';
            $categories = isset($_REQUEST['categories']) ? civi_clean(wp_unslash($_REQUEST['categories'])) : '';
            $company_layout = isset($_REQUEST['company_layout']) ? civi_clean(wp_unslash($_REQUEST['company_layout'])) : '';

            $meta_query = array();
            $tax_query = array();

            $args = array(
                'posts_per_page' => ($item_amount > 0) ? $item_amount : -1,
                'post_type' => 'company',
                'paged' => $paged,
                'post_status' => 'publish',
            );

            if (!empty($title)) {
                $args['fields'] = 'ids';
                $args_search = $args_tax = $args;
                $args_search['s'] = $title;
                $data_search = new WP_Query($args_search);
                $args_tax['tax_query'] = array(
                    array(
                        'taxonomy' => 'company-categories',
                        'field' => 'name',
                        'terms' => $title,
                    )
                );
                $data_tax = new WP_Query($args_tax);
                $company_ids = array_merge($data_tax->posts, $data_search->posts);
                $company_ids = array_unique($company_ids);
                if (!empty($company_ids)) {
                    $args['post__in'] = $company_ids;
                } else {
                    $args['s'] = $title;
                }
            }

            //tax query current term
            if (!empty($current_term) && !empty($type_term)) {
                $tax_query[] = array(
                    'taxonomy' => $type_term,
                    'field' => 'id',
                    'terms' => $current_term
                );
            }

            //tax query company size
            if (!empty($size)) {
                $tax_query[] = array(
                    'taxonomy' => 'company-size',
                    'field' => 'term_id',
                    'terms' => $size
                );
            }

            //location country
            if (!empty($location_country)) {
                $taxonomy_state = get_categories(
                    array(
                        'taxonomy' => 'company-state',
                        'hide_empty' => false,
                        'parent' => 0,
                        'meta_query' => array(
                            array(
                                'key' => 'company-state-country',
                                'value' => $location_country,
                                'compare' => '=',
                            )
                        )
                    )
                );

                if(!empty($taxonomy_state)){
                    $keys_state = array();
                    foreach ($taxonomy_state as $terms_state) {
                        $keys_state[] = $terms_state->term_id;
                    }
                    $taxonomy_city = get_categories(
                        array(
                            'taxonomy' => 'company-location',
                            'meta_query' => array(
                                array(
                                    'key' => 'company-location-state',
                                    'value' => $keys_state,
                                    'compare' => 'IN'
                                )
                            )
                        )
                    );
                    $keys_city = array();
                    foreach ($taxonomy_city as $terms_city) {
                        $keys_city[] = $terms_city->term_id;
                    }
                } else {
                    $keys_city = '';
                }

                $tax_query[] = array(
                    'taxonomy' => 'company-location',
                    'field' => 'term_id',
                    'terms' => $keys_city
                );
            }

            //location state
            if (!empty($location_state)) {
                $taxonomy_terms = get_categories(
                    array(
                        'taxonomy' => 'company-location',
                        'meta_query' => array(
                            array(
                                'key' => 'company-location-state',
                                'value' => $location_state,
                                'compare' => '=',
                            )
                        )
                    )
                );
                $keys = array();
                foreach ($taxonomy_terms as $terms) {
                    $keys[] = $terms->term_id;
                }
                $tax_query[] = array(
                    'taxonomy' => 'company-location',
                    'field' => 'term_id',
                    'terms' => $keys
                );
            }

            //location city
            if (!empty($location_city)) {
                $tax_query[] = array(
                    'taxonomy' => 'company-location',
                    'field' => 'term_id',
                    'terms' => $location_city
                );
            }

            //tax query company search location
            if (!empty($location) && ($radius_cities == 0 || empty($radius_cities))) {
                $tax_query[] = array(
                    'taxonomy' => 'company-location',
                    'field' => 'name',
                    'terms' => $location
                );
            }

            //Nearby cities
            $resultsCities = array();
            if (!empty($radius_cities) && $radius_cities !== 0 && !empty($location)) {
                $taxonomies = get_categories(
                    array(
                        'taxonomy' => 'company-location',
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => true,
                        'parent' => 0
                    )
                );

                if (!empty($taxonomies)) {
                    $resultsCities = civi_find_nearby_cities($location, intval($radius_cities));
                    if(empty($resultsCities)) {
                        $resultsCities = $location;
                    }
                    $tax_query[] = array(
                        'taxonomy' => 'company-location',
                        'field' => 'name',
                        'terms' => $resultsCities,
                    );
                }
            }

            //tax query company size
            if (!empty($categories)) {
                $tax_query[] = array(
                    'taxonomy' => 'company-categories',
                    'field' => 'term_id',
                    'terms' => $categories
                );
            }

            //rating
            $rating_one = $rating_two = $rating_three = $rating_four = $rating_five = '';
            if (!empty($rating)) {
                if ((is_array($rating) && in_array('rating_five', $rating)) || $rating == 'rating_five') {
                    $rating_five = array(
                        'key' => CIVI_METABOX_PREFIX . 'company_rating',
                        'value' => 5,
                        'type' => 'NUMERIC',
                        'compare' => '==',
                    );
                }

                if ((is_array($rating) && in_array('rating_four', $rating)) || $rating == 'rating_four') {
                    $rating_four = array(
                        'key' => CIVI_METABOX_PREFIX . 'company_rating',
                        'value' => array(4, 4.99),
                        'type' => 'NUMERIC',
                        'compare' => 'BETWEEN',
                    );
                }

                if ((is_array($rating) && in_array('rating_three', $rating)) || $rating == 'rating_three') {
                    $rating_three = array(
                        'key' => CIVI_METABOX_PREFIX . 'company_rating',
                        'value' => array(3, 3.99),
                        'type' => 'NUMERIC',
                        'compare' => 'BETWEEN',
                    );
                }

                if ((is_array($rating) && in_array('rating_two', $rating)) || $rating == 'rating_two') {
                    $rating_two = array(
                        'key' => CIVI_METABOX_PREFIX . 'company_rating',
                        'value' => array(2, 2.99),
                        'type' => 'NUMERIC',
                        'compare' => 'BETWEEN',
                    );
                }

                if ((is_array($rating) && in_array('rating_one', $rating)) || $rating == 'rating_one') {
                    $rating_one = array(
                        'key' => CIVI_METABOX_PREFIX . 'company_rating',
                        'value' => array(1, 1.99),
                        'type' => 'NUMERIC',
                        'compare' => 'BETWEEN',
                    );
                }

                $meta_query[] = array(
                    'relation' => 'OR',
                    $rating_five,
                    $rating_four,
                    $rating_three,
                    $rating_two,
                    $rating_one
                );
            }

            //founded
            if (!empty($range_min) && !empty($range_max)) {
                $meta_query[] = array(
                    'key' => CIVI_METABOX_PREFIX . 'company_founded',
                    'value' => array($range_min, $range_max),
                    'type' => 'NUMERIC',
                    'compare' => 'BETWEEN',
                );
            }

            if (!empty($founded)) {
                $meta_query[] = array(
                    'key' => CIVI_METABOX_PREFIX . 'company_founded',
                    'value' => $founded,
                    'compare' => '=',
                );
            }

            //meta query company sort by
            if (!empty($sort_by)) {
                if ($sort_by == 'newest') {
                    $args['orderby'] = array(
                        'menu_order' => 'ASC',
                        'date' => 'DESC',
                    );
                }

                if ($sort_by == 'oldest') {
                    $args['orderby'] = array(
                        'menu_order' => 'DESC',
                        'date' => 'ASC',
                    );
                }

                if ($sort_by == 'rating') {
                    $args['meta_key'] = CIVI_METABOX_PREFIX . 'company_rating';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                }
            }

            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $tax_count = count($tax_query);
            if ($tax_count > 0) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            $args_map = $args;
            $args_map['paged'] = '';
            $args_map['posts_per_page'] = '-1';

            $data = new WP_Query($args);
            $data_map = new WP_Query($args_map);
            $total_post = $data->found_posts;
            $max_num_pages = $data->max_num_pages;
            $company_html = '';
            $company = array();

            if (!empty($total_post)) {
                if (!empty($title)) {
                    $count_post = sprintf(esc_html__('%1$s companies for "%2$s"', 'civi-framework'), '<span class="count">' . $total_post . '</span>', $title);
                } else {
                    $count_post = sprintf(_n('%s companies', '%s Companies', $total_post, 'civi-framework'), '<span class="count">' . esc_html($total_post) . '</span>');
                }
            } else {
                $count_post = esc_html__('0 company', 'civi-framework');
            }

            if (!empty($current_term)) {
                $count_post = sprintf(_n('%s Companies', '%s Companies', $total_post, 'civi-framework'), '<span class="count">' . esc_html($total_post) . '</span>');
                if (empty($total_post)) {
                    $count_post = sprintf(__('%s Companies', 'civi-framework'), $total_post);
                }
            }

            $pagination_type = civi_get_option('company_pagination_type', 'loadmore');
            if ($pagination_type == 'number') {
                $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                    'total' => $max_num_pages,
                    'current' => $paged,
                    'mid_size' => 1,
                    'type' => 'array',
                    'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                    'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
                )));
            } else {
                $pagination = '<a class="page-numbers next" href="#"><span>' . __('Load More', 'civi-framework') . '</span><span class="btn-loading"><i class="fal fa-spinner fa-spin large"></i></span></a>';
            }

            $hidden_pagination = '';
            if ($paged == $max_num_pages) {
                $hidden_pagination = 1;
            }

            if ($total_post > 0) {

                while ($data_map->have_posts()) : $data_map->the_post();

                    $company_id = get_the_ID();

                    $company_meta_data = get_post_custom($company_id);

                    $map_zoom_level = civi_get_option('map_zoom_level', '15');

                    $company_address = isset($company_meta_data[CIVI_METABOX_PREFIX . 'company_address']) ? $company_meta_data[CIVI_METABOX_PREFIX . 'company_address'][0] : '';
                    $company_location = get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_location', true);

                    if (!empty($company_location['location'])) {
                        $lat_lng = explode(',', $company_location['location']);
                    } else {
                        $lat_lng = array();
                    }

                    $company_logo = get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_logo');
                    if (!empty($company_logo[0]['url'])) {
                        $marker_icon = $company_logo[0]['url'];
                    } else {
                        $marker_icon = CIVI_PLUGIN_URL . 'assets/images/map-marker-icon.png';
                    };

                    $html_company = ob_start();
                    civi_get_template('content-company.php', array(
                        'company_id' => $company_id,
                        'company_layout' => 'layout-grid',
                        'effect_class' => '',
                    ));
                    $html_company = ob_get_clean();

                    $prop = new stdClass();
                    $prop->id = $company_id;
                    $prop->lat = isset($lat_lng[0]) ? $lat_lng[0] : 59.325;
                    $prop->lng = isset($lat_lng[1]) ? $lat_lng[1] : 18.070;
                    $prop->company = $html_company;

                    if (empty($company_url)) {
                        $company_url = CIVI_PLUGIN_URL . 'assets/images/map-marker-icon.png';
                        $default_marker = civi_get_option('marker_icon', '');
                        if ($default_marker != '') {
                            if (is_array($default_marker) && $default_marker['url'] != '') {
                                $company_url = $default_marker['url'];
                            }
                        }
                    }

                    if ($marker_icon) {
                        $prop->marker_icon = $marker_icon;
                    } else {
                        $prop->marker_icon = $company_url;
                    }

                    array_push($company, $prop);

                endwhile;
            }
            wp_reset_postdata();

            ob_start();

            $count_data = 1;
            if ($total_post > 0) {
                while ($data->have_posts()) : $data->the_post();
                    civi_get_template('content-company.php', array(
                        'company_layout' => $company_layout,
                    ));
                endwhile;
            }
            wp_reset_postdata();

            $company_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'resultsCities'=> $resultsCities, 'company' => $company, 'count_company' => $count_data, 'pagination' => $pagination, 'hidden_pagination' => $hidden_pagination, 'pagination_type' => $pagination_type, 'company_html' => $company_html, 'total_post' => $total_post, 'count_post' => $count_post));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post, 'count_post' => $count_post));
            }
            wp_die();
        }

        /**
         *  Candidate Archive
         */
        public function civi_candidate_archive_ajax()
        {
            $title = isset($_REQUEST['title']) ? civi_clean(wp_unslash($_REQUEST['title'])) : '';
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $sort_by = isset($_REQUEST['sort_by']) ? civi_clean(wp_unslash($_REQUEST['sort_by'])) : '';
            $current_term = isset($_REQUEST['current_term']) ? civi_clean(wp_unslash($_REQUEST['current_term'])) : '';
            $type_term = isset($_REQUEST['type_term']) ? civi_clean(wp_unslash($_REQUEST['type_term'])) : '';
            $rating = isset($_REQUEST['rating']) ? civi_clean(wp_unslash($_REQUEST['rating'])) : '';
            $location = isset($_REQUEST['location']) ? civi_clean(wp_unslash($_REQUEST['location'])) : '';
            $location_country = isset($_REQUEST['location_country']) ? civi_clean(wp_unslash($_REQUEST['location_country'])) : '';
            $location_state = isset($_REQUEST['location_state']) ? civi_clean(wp_unslash($_REQUEST['location_state'])) : '';
            $location_city = isset($_REQUEST['location_city']) ? civi_clean(wp_unslash($_REQUEST['location_city'])) : '';
            $radius_cities = isset($_REQUEST['radius_cities']) ? civi_clean(wp_unslash($_REQUEST['radius_cities'])) : '';
            $categories = isset($_REQUEST['categories']) ? civi_clean(wp_unslash($_REQUEST['categories'])) : '';
            $yoe_ids = isset($_REQUEST['candidate_yoe_id']) ? civi_clean(wp_unslash($_REQUEST['candidate_yoe_id'])) : array();
            $qualification_ids = isset($_REQUEST['candidate_qualification_id']) ? civi_clean(wp_unslash($_REQUEST['candidate_qualification_id'])) : array();
            $ages_ids = isset($_REQUEST['candidate_ages_id']) ? civi_clean(wp_unslash($_REQUEST['candidate_ages_id'])) : array();
            $skills_ids = isset($_REQUEST['candidate_skills_id']) ? civi_clean(wp_unslash($_REQUEST['candidate_skills_id'])) : array();
            $languages_ids = isset($_REQUEST['candidate_languages_id']) ? civi_clean(wp_unslash($_REQUEST['candidate_languages_id'])) : array();
            $gender_ids = isset($_REQUEST['candidate_gender']) ? civi_clean(wp_unslash($_REQUEST['candidate_gender'])) : array();
            $candidate_layout = isset($_REQUEST['candidate_layout']) ? civi_clean(wp_unslash($_REQUEST['candidate_layout'])) : '';

            $meta_query = array();
            $tax_query = array();

            $args = array(
                'posts_per_page' => ($item_amount > 0) ? $item_amount : -1,
                'post_type' => 'candidate',
                'paged' => $paged,
                'post_status' => 'publish',
                'meta_key' => 'civi-candidate_featured',
                'orderby' => 'meta_value date',
            );

            if (!empty($title)) {
                $args['fields'] = 'ids';
                $args_search = $args_tax = $args;
                $args_search['s'] = $title;
                $data_search = new WP_Query($args_search);
                $args_tax['tax_query'] = array(
                    array(
                        'taxonomy' => 'candidate_skills',
                        'field' => 'name',
                        'terms' => $title,
                    )
                );
                $data_tax = new WP_Query($args_tax);
                $candidate_ids = array_merge($data_tax->posts, $data_search->posts);
                $candidate_ids = array_unique($candidate_ids);
                if (!empty($candidate_ids)) {
                    $args['post__in'] = $candidate_ids;
                } else {
                    $args['s'] = $title;
                }
            }

            //tax query current term
            if (!empty($current_term) && !empty($type_term)) {
                $tax_query[] = array(
                    'taxonomy' => $type_term,
                    'field' => 'id',
                    'terms' => $current_term
                );
            }

            //tax query candidate categories
            if (!empty($categories)) {
                $tax_query[] = array(
                    'taxonomy' => 'candidate_categories',
                    'field' => 'term_id',
                    'terms' => $categories
                );
            }

            //location country
            if (!empty($location_country)) {
                $taxonomy_state = get_categories(
                    array(
                        'taxonomy' => 'candidate_state',
                        'hide_empty' => false,
                        'parent' => 0,
                        'meta_query' => array(
                            array(
                                'key' => 'candidate_state-country',
                                'value' => $location_country,
                                'compare' => '=',
                            )
                        )
                    )
                );

                if(!empty($taxonomy_state)){
                    $keys_state = array();
                    foreach ($taxonomy_state as $terms_state) {
                        $keys_state[] = $terms_state->term_id;
                    }
                    $taxonomy_city = get_categories(
                        array(
                            'taxonomy' => 'candidate_locations',
                            'meta_query' => array(
                                array(
                                    'key' => 'candidate_locations-state',
                                    'value' => $keys_state,
                                    'compare' => 'IN'
                                )
                            )
                        )
                    );
                    $keys_city = array();
                    foreach ($taxonomy_city as $terms_city) {
                        $keys_city[] = $terms_city->term_id;
                    }
                } else {
                    $keys_city = '';
                }
                $tax_query[] = array(
                    'taxonomy' => 'candidate_locations',
                    'field' => 'term_id',
                    'terms' => $keys_city
                );
            }

            //location state
            if (!empty($location_state)) {
                $taxonomy_terms = get_categories(
                    array(
                        'taxonomy' => 'candidate_locations',
                        'meta_query' => array(
                            array(
                                'key' => 'candidate_locations-state',
                                'value' => $location_state,
                                'compare' => '=',
                            )
                        )
                    )
                );
                $keys = array();
                foreach ($taxonomy_terms as $terms) {
                    $keys[] = $terms->term_id;
                }
                $tax_query[] = array(
                    'taxonomy' => 'candidate_locations',
                    'field' => 'term_id',
                    'terms' => $keys
                );
            }

            //location city
            if (!empty($location_city)) {
                $tax_query[] = array(
                    'taxonomy' => 'candidate_locations',
                    'field' => 'term_id',
                    'terms' => $location_city
                );
            }

            //tax query candidate search location
            if (!empty($location) && ($radius_cities == 0 || empty($radius_cities))) {
                $tax_query[] = array(
                    'taxonomy' => 'candidate_locations',
                    'field' => 'name',
                    'terms' => $location
                );
            }

            //Nearby cities
            $resultsCities = array();
            if (!empty($radius_cities) && $radius_cities !== 0 && !empty($location)) {
                $taxonomies = get_categories(
                    array(
                        'taxonomy' => 'candidate_locations',
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => true,
                        'parent' => 0
                    )
                );

                if (!empty($taxonomies)) {
                    $resultsCities = civi_find_nearby_cities($location, intval($radius_cities));
                    if(empty($resultsCities)) {
                        $resultsCities = $location;
                    }
                    $tax_query[] = array(
                        'taxonomy' => 'candidate_locations',
                        'field' => 'name',
                        'terms' => $resultsCities,
                    );
                }
            }

            //tax query candidate experiences
            if (!empty($yoe_ids)) {
                $tax_query[] = array(
                    'taxonomy' => 'candidate_yoe',
                    'field' => 'term_id',
                    'terms' => $yoe_ids
                );
            }

            //tax query candidate qualification
            if (!empty($qualification_ids)) {
                $tax_query[] = array(
                    'taxonomy' => 'candidate_qualification',
                    'field' => 'term_id',
                    'terms' => $qualification_ids
                );
            }

            //tax query candidate ages
            if (!empty($ages_ids)) {
                $tax_query[] = array(
                    'taxonomy' => 'candidate_ages',
                    'field' => 'term_id',
                    'terms' => $ages_ids
                );
            }

            //tax query candidate skills
            if (!empty($skills_ids)) {
                $tax_query[] = array(
                    'taxonomy' => 'candidate_skills',
                    'field' => 'term_id',
                    'terms' => $skills_ids
                );
            }

            //tax query candidate languages
            if (!empty($languages_ids)) {
                $tax_query[] = array(
                    'taxonomy' => 'candidate_languages',
                    'field' => 'term_id',
                    'terms' => $languages_ids
                );
            }

            //tax query candidate gender
            if (!empty($gender_ids)) {
                $tax_query[] = array(
                    'taxonomy' => 'candidate_gender',
                    'field' => 'term_id',
                    'terms' => $gender_ids
                );
            }

            //rating
            $rating_one = $rating_two = $rating_three = $rating_four = $rating_five = '';
            if (!empty($rating)) {
                if ((is_array($rating) && in_array('rating_five', $rating)) || $rating == 'rating_five') {
                    $rating_five = array(
                        'key' => CIVI_METABOX_PREFIX . 'candidate_rating',
                        'value' => 5,
                        'type' => 'NUMERIC',
                        'compare' => '==',
                    );
                }

                if ((is_array($rating) && in_array('rating_four', $rating)) || $rating == 'rating_four') {
                    $rating_four = array(
                        'key' => CIVI_METABOX_PREFIX . 'candidate_rating',
                        'value' => array(4, 4.99),
                        'type' => 'NUMERIC',
                        'compare' => 'BETWEEN',
                    );
                }

                if ((is_array($rating) && in_array('rating_three', $rating)) || $rating == 'rating_three') {
                    $rating_three = array(
                        'key' => CIVI_METABOX_PREFIX . 'candidate_rating',
                        'value' => array(3, 3.99),
                        'type' => 'NUMERIC',
                        'compare' => 'BETWEEN',
                    );
                }

                if ((is_array($rating) && in_array('rating_two', $rating)) || $rating == 'rating_two') {
                    $rating_two = array(
                        'key' => CIVI_METABOX_PREFIX . 'candidate_rating',
                        'value' => array(2, 2.99),
                        'type' => 'NUMERIC',
                        'compare' => 'BETWEEN',
                    );
                }

                if ((is_array($rating) && in_array('rating_one', $rating)) || $rating == 'rating_one') {
                    $rating_one = array(
                        'key' => CIVI_METABOX_PREFIX . 'candidate_rating',
                        'value' => array(1, 1.99),
                        'type' => 'NUMERIC',
                        'compare' => 'BETWEEN',
                    );
                }

                $meta_query[] = array(
                    'relation' => 'OR',
                    $rating_five,
                    $rating_four,
                    $rating_three,
                    $rating_two,
                    $rating_one
                );
            }

            //meta query company sort by
            if (!empty($sort_by)) {
                if ($sort_by == 'newest') {
                    $args['orderby'] = array(
                        'menu_order' => 'ASC',
                        'date' => 'DESC',
                    );
                }

                if ($sort_by == 'oldest') {
                    $args['orderby'] = array(
                        'menu_order' => 'DESC',
                        'date' => 'ASC',
                    );
                }

                if ($sort_by == 'rating') {
                    $args['meta_key'] = CIVI_METABOX_PREFIX . 'candidate_rating';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                }
            }

            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $tax_count = count($tax_query);
            if ($tax_count > 0) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            $args_map = $args;
            $args_map['paged'] = '';
            $args_map['posts_per_page'] = '-1';

            $data = new WP_Query($args);
            $data_map = new WP_Query($args_map);
            $total_post = $data->found_posts;

            $candidate_html = '';
            $candidate = array();

            if (!empty($total_post)) {
                $count_post = sprintf(_n('%s Candidates', '%s Candidates', $total_post, 'civi-framework'), '<span class="count">' . esc_html($total_post) . '</span>');
            } else {
                $count_post = esc_html__('0 candidate', 'civi-framework');
            }

            if (!empty($current_term)) {
                $count_post = sprintf(_n('%s Candidates', '%s Candidates', $total_post, 'civi-framework'), '<span class="count">' . esc_html($total_post) . '</span>');
                if (empty($total_post)) {
                    $count_post = sprintf(__('%s Candidates', 'civi-framework'), $total_post);
                }
            }

            $archive_candidate_layout = civi_get_option('archive_candidate_layout', 'layout-list');
            if (!empty($title) && $archive_candidate_layout == 'layout-list') {
                $count_post = sprintf(esc_html__('%1$s candidates for "%2$s"', 'civi-framework'), '<span>' . $total_post . '</span>', $title);
            }

            $max_num_pages = $data->max_num_pages;
            $pagination_type = civi_get_option('candidate_pagination_type', 'loadmore');
            if ($pagination_type == 'number') {
                $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                    'total' => $max_num_pages,
                    'current' => $paged,
                    'mid_size' => 1,
                    'type' => 'array',
                    'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                    'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
                )));
            } else {
                $pagination = '<a class="page-numbers next" href="#"><span>' . __('Load More', 'civi-framework') . '</span><span class="btn-loading"><i class="fal fa-spinner fa-spin large"></i></span></a>';
            }

            $hidden_pagination = '';
            if ($paged == $max_num_pages) {
                $hidden_pagination = 1;
            }

            if ($total_post > 0) {

                while ($data_map->have_posts()) : $data_map->the_post();

                    $candidate_id = get_the_ID();

                    $candidate_meta_data = get_post_custom($candidate_id);

                    $map_zoom_level = civi_get_option('map_zoom_level', '15');

                    $candidate_address = isset($candidate_meta_data[CIVI_METABOX_PREFIX . 'candidate_address']) ? $candidate_meta_data[CIVI_METABOX_PREFIX . 'candidate_address'][0] : '';
                    $candidate_location = get_post_meta($candidate_id, CIVI_METABOX_PREFIX . 'candidate_location', true);

                    if (!empty($candidate_location['location'])) {
                        $lat_lng = explode(',', $candidate_location['location']);
                    } else {
                        $lat_lng = array();
                    }

                    $author_id = get_post_field('post_author', $candidate_id);
                    $candidate_avatar = get_the_author_meta('author_avatar_image_url', $author_id);
                    if (!empty($candidate_avatar)) {
                        $marker_icon = $candidate_avatar;
                    } else {
                        $marker_icon = CIVI_PLUGIN_URL . 'assets/images/map-marker-icon.png';
                    };

                    $html_candidate = ob_start();
                    civi_get_template('content-candidate.php', array(
                        'candidate_id' => $candidate_id,
                        'candidate_layout' => 'layout-grid',
                        'effect_class' => '',
                    ));
                    $html_candidate = ob_get_clean();

                    $prop = new stdClass();
                    $prop->id = $candidate_id;
                    $prop->lat = isset($lat_lng[0]) ? $lat_lng[0] : 59.325;
                    $prop->lng = isset($lat_lng[1]) ? $lat_lng[1] : 18.070;
                    $prop->candidate = $html_candidate;

                    if (empty($candidate_url)) {
                        $candidate_url = CIVI_PLUGIN_URL . 'assets/images/map-marker-icon.png';
                        $default_marker = civi_get_option('marker_icon', '');
                        if ($default_marker != '') {
                            if (is_array($default_marker) && $default_marker['url'] != '') {
                                $candidate_url = $default_marker['url'];
                            }
                        }
                    }

                    if ($marker_icon) {
                        $prop->marker_icon = $marker_icon;
                    } else {
                        $prop->marker_icon = $candidate_url;
                    }

                    array_push($candidate, $prop);

                endwhile;
            }
            wp_reset_postdata();

            $candidate_html = ob_start();

            if ($total_post > 0) {
                while ($data->have_posts()) : $data->the_post();
                    civi_get_template('content-candidate.php', array(
                        'candidate_layout' => $candidate_layout,
                    ));
                endwhile;
            }
            wp_reset_postdata();

            $candidate_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'resultsCities'=> $resultsCities,'candidate' => $candidate, 'pagination' => $pagination, 'hidden_pagination' => $hidden_pagination, 'pagination_type' => $pagination_type, 'candidate_html' => $candidate_html, 'total_post' => $total_post, 'count_post' => $count_post));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post, 'count_post' => $count_post));
            }
            wp_die();
        }

        /**
         * Service Archive
         */
        public function civi_service_archive_ajax()
        {
            $title = isset($_REQUEST['title']) ? civi_clean(wp_unslash($_REQUEST['title'])) : '';
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $sort_by = isset($_REQUEST['sort_by']) ? civi_clean(wp_unslash($_REQUEST['sort_by'])) : '';
            $current_term = isset($_REQUEST['current_term']) ? civi_clean(wp_unslash($_REQUEST['current_term'])) : '';
            $type_term = isset($_REQUEST['type_term']) ? civi_clean(wp_unslash($_REQUEST['type_term'])) : '';
            $rating = isset($_REQUEST['rating']) ? civi_clean(wp_unslash($_REQUEST['rating'])) : '';
            $location = isset($_REQUEST['location']) ? civi_clean(wp_unslash($_REQUEST['location'])) : '';
            $location_country = isset($_REQUEST['location_country']) ? civi_clean(wp_unslash($_REQUEST['location_country'])) : '';
            $location_state = isset($_REQUEST['location_state']) ? civi_clean(wp_unslash($_REQUEST['location_state'])) : '';
            $location_city = isset($_REQUEST['location_city']) ? civi_clean(wp_unslash($_REQUEST['location_city'])) : '';
            $radius_cities = isset($_REQUEST['radius_cities']) ? civi_clean(wp_unslash($_REQUEST['radius_cities'])) : '';
            $categories = isset($_REQUEST['categories']) ? civi_clean(wp_unslash($_REQUEST['categories'])) : '';
            $skills = isset($_REQUEST['skills']) ? civi_clean(wp_unslash($_REQUEST['skills'])) : '';
            $language  = isset($_REQUEST['language']) ? civi_clean(wp_unslash($_REQUEST['language'])) : '';
            $price_min = isset($_REQUEST['price_min']) ? civi_clean(wp_unslash($_REQUEST['price_min'])) : '';
            $price_max = isset($_REQUEST['price_max']) ? civi_clean(wp_unslash($_REQUEST['price_max'])) : '';
            $time_type  = isset($_REQUEST['time_type']) ? civi_clean(wp_unslash($_REQUEST['time_type'])) : '';
            $language_level = isset($_REQUEST['language_level']) ? civi_clean(wp_unslash($_REQUEST['language_level'])) : '';
            $service_layout = isset($_REQUEST['service_layout']) ? civi_clean(wp_unslash($_REQUEST['service_layout'])) : '';

            $meta_query = array();
            $tax_query = array();

            $args = array(
                'posts_per_page' => ($item_amount > 0) ? $item_amount : -1,
                'post_type' => 'service',
                'paged' => $paged,
                'meta_key'            => CIVI_METABOX_PREFIX . 'service_featured',
                'orderby' => 'meta_value date',
                'order'               => 'DESC',
                'post_status'         => 'publish',
            );

            $meta_query[] = array(
                array(
                    'key' => CIVI_METABOX_PREFIX . 'enable_service_package_expires',
                    'value' => 0,
                    'compare' => '=='
                )
            );

            if (!empty($title)) {
                $args['fields'] = 'ids';
                $args_search = $args_tax = $args;
                $args_search['s'] = $title;
                $data_search = new WP_Query($args_search);
                $args_tax['tax_query'] = array(
                    array(
                        'taxonomy' => 'service-skills',
                        'field' => 'name',
                        'terms' => $title,
                    )
                );
                $data_tax = new WP_Query($args_tax);
                $service_ids = array_merge($data_tax->posts, $data_search->posts);
                $service_ids = array_unique($service_ids);
                if (!empty($service_ids)) {
                    $args['post__in'] = $service_ids;
                } else {
                    $args['s'] = $title;
                }
            }

            //tax query current term
            if (!empty($current_term) && !empty($type_term)) {
                $tax_query[] = array(
                    'taxonomy' => $type_term,
                    'field' => 'id',
                    'terms' => $current_term
                );
            }

            //location country
            if (!empty($location_country)) {
                $taxonomy_state = get_categories(
                    array(
                        'taxonomy' => 'service-state',
                        'hide_empty' => false,
                        'parent' => 0,
                        'meta_query' => array(
                            array(
                                'key' => 'service-state-country',
                                'value' => $location_country,
                                'compare' => '=',
                            )
                        )
                    )
                );

                if(!empty($taxonomy_state)){
                    $keys_state = array();
                    foreach ($taxonomy_state as $terms_state) {
                        $keys_state[] = $terms_state->term_id;
                    }
                    $taxonomy_city = get_categories(
                        array(
                            'taxonomy' => 'service-location',
                            'meta_query' => array(
                                array(
                                    'key' => 'service-location-state',
                                    'value' => $keys_state,
                                    'compare' => 'IN'
                                )
                            )
                        )
                    );
                    $keys_city = array();
                    foreach ($taxonomy_city as $terms_city) {
                        $keys_city[] = $terms_city->term_id;
                    }
                } else {
                    $keys_city = '';
                }
                $tax_query[] = array(
                    'taxonomy' => 'service-location',
                    'field' => 'term_id',
                    'terms' => $keys_city
                );
            }

            //location state
            if (!empty($location_state)) {
                $taxonomy_terms = get_categories(
                    array(
                        'taxonomy' => 'service-location',
                        'meta_query' => array(
                            array(
                                'key' => 'service-location-state',
                                'value' => $location_state,
                                'compare' => '=',
                            )
                        )
                    )
                );
                $keys = array();
                foreach ($taxonomy_terms as $terms) {
                    $keys[] = $terms->term_id;
                }
                $tax_query[] = array(
                    'taxonomy' => 'service-location',
                    'field' => 'term_id',
                    'terms' => $keys
                );
            }

            //location city
            if (!empty($location_city)) {
                $tax_query[] = array(
                    'taxonomy' => 'service-location',
                    'field' => 'term_id',
                    'terms' => $location_city
                );
            }

            //tax query service search location
            if (!empty($location) && ($radius_cities == 0 || empty($radius_cities))) {
                $tax_query[] = array(
                    'taxonomy' => 'service-location',
                    'field' => 'name',
                    'terms' => $location
                );
            }

            //Nearby cities
            $resultsCities = array();
            if (!empty($radius_cities) && $radius_cities !== 0 && !empty($location)) {
                $taxonomies = get_categories(
                    array(
                        'taxonomy' => 'service-location',
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => true,
                        'parent' => 0
                    )
                );

                if (!empty($taxonomies)) {
                    $resultsCities = civi_find_nearby_cities($location, intval($radius_cities));
                    if(empty($resultsCities)) {
                        $resultsCities = $location;
                    }
                    $tax_query[] = array(
                        'taxonomy' => 'service-location',
                        'field' => 'name',
                        'terms' => $resultsCities,
                    );
                }
            }

            //tax query service categories
            if (!empty($categories)) {
                $tax_query[] = array(
                    'taxonomy' => 'service-categories',
                    'field' => 'term_id',
                    'terms' => $categories
                );
            }

            //tax query service skills
            if (!empty($skills)) {
                $tax_query[] = array(
                    'taxonomy' => 'service-skills',
                    'field' => 'term_id',
                    'terms' => $skills
                );
            }

            //tax query service language
            if (!empty($language)) {
                $tax_query[] = array(
                    'taxonomy' => 'service-language',
                    'field' => 'term_id',
                    'terms' => $language
                );
            }

            //rating
            $rating_one = $rating_two = $rating_three = $rating_four = $rating_five = '';
            if (!empty($rating)) {
                if ((is_array($rating) && in_array('rating_five', $rating)) || $rating == 'rating_five') {
                    $rating_five = array(
                        'key' => CIVI_METABOX_PREFIX . 'service_rating',
                        'value' => 5,
                        'type' => 'NUMERIC',
                        'compare' => '==',
                    );
                }

                if ((is_array($rating) && in_array('rating_four', $rating)) || $rating == 'rating_four') {
                    $rating_four = array(
                        'key' => CIVI_METABOX_PREFIX . 'service_rating',
                        'value' => array(4, 4.99),
                        'type' => 'NUMERIC',
                        'compare' => 'BETWEEN',
                    );
                }

                if ((is_array($rating) && in_array('rating_three', $rating)) || $rating == 'rating_three') {
                    $rating_three = array(
                        'key' => CIVI_METABOX_PREFIX . 'service_rating',
                        'value' => array(3, 3.99),
                        'type' => 'NUMERIC',
                        'compare' => 'BETWEEN',
                    );
                }

                if ((is_array($rating) && in_array('rating_two', $rating)) || $rating == 'rating_two') {
                    $rating_two = array(
                        'key' => CIVI_METABOX_PREFIX . 'service_rating',
                        'value' => array(2, 2.99),
                        'type' => 'NUMERIC',
                        'compare' => 'BETWEEN',
                    );
                }

                if ((is_array($rating) && in_array('rating_one', $rating)) || $rating == 'rating_one') {
                    $rating_one = array(
                        'key' => CIVI_METABOX_PREFIX . 'service_rating',
                        'value' => array(1, 1.99),
                        'type' => 'NUMERIC',
                        'compare' => 'BETWEEN',
                    );
                }

                $meta_query[] = array(
                    'relation' => 'OR',
                    $rating_five,
                    $rating_four,
                    $rating_three,
                    $rating_two,
                    $rating_one
                );


            }

            //service price
            if (!empty($time_type)) {
                if (empty($price_min) && empty($price_max)) {
                    $meta_query[] = array(
                        'key' => CIVI_METABOX_PREFIX . 'service_time_type',
                        'value' => $time_type,
                        'compare' => '=',
                    );
                }

                if (!empty($price_min) && empty($price_max)) {
                    $meta_query[] =  array(
                        'relation' => 'AND',
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'service_price',
                            'value' => $price_min,
                            'type' => 'NUMERIC',
                            'compare' => '>=',
                        ),
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'service_time_type',
                            'value' => $time_type,
                            'compare' => '=',
                        ),
                    );
                }

                if (!empty($price_max) && empty($price_min)) {
                    $meta_query[] = array(
                        'relation' => 'AND',
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'service_price',
                            'value' => array(1, $price_max),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN',
                        ),
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'service_time_type',
                            'value' => $time_type,
                            'compare' => '=',
                        ),
                    );
                }

                if (!empty($price_max) && !empty($price_min)) {
                    $meta_query[] = array(
                        'relation' => 'AND',
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'service_price',
                            'value' => array($price_min, $price_max),
                            'type' => 'NUMERIC',
                            'compare' => 'BETWEEN',
                        ),
                        array(
                            'key' => CIVI_METABOX_PREFIX . 'service_time_type',
                            'value' => $time_type,
                            'compare' => '=',
                        ),
                    );
                }
            } else {
                if (!empty($price_min) && empty($price_max)) {
                    $meta_query[] = array(
                        'key' => CIVI_METABOX_PREFIX . 'service_price',
                        'value' => $price_min,
                        'type' => 'NUMERIC',
                        'compare' => '>=',
                    );
                }

                if (!empty($price_max) && empty($price_min)) {
                    $meta_query[] = array(
                        'key' => CIVI_METABOX_PREFIX . 'service_price',
                        'value' => array(1, $price_max),
                        'type' => 'NUMERIC',
                        'compare' => 'BETWEEN',
                    );
                }

                if (!empty($price_max) && !empty($price_min)) {
                    $meta_query[] = array(
                        'key' => CIVI_METABOX_PREFIX . 'service_price',
                        'value' => array($price_min, $price_max),
                        'type' => 'NUMERIC',
                        'compare' => 'BETWEEN',
                    );
                }
            }

            //Languages level
            if (!empty($language_level)) {
                $basic = $conversational = $fluent = $native = $professional = '';
                if ((is_array($language_level) && in_array('basic', $language_level)) || $language_level == 'basic') {
                    $basic = array(
                        'key' => CIVI_METABOX_PREFIX . 'service_language_level',
                        'value' => 'basic',
                        'compare' => '=',
                    );
                }
                if ((is_array($language_level) && in_array('conversational', $language_level)) || $language_level == 'conversational') {
                    $conversational = array(
                        'key' => CIVI_METABOX_PREFIX . 'service_language_level',
                        'value' => 'conversational',
                        'compare' => '=',
                    );
                }
                if ((is_array($language_level) && in_array('fluent', $language_level)) || $language_level == 'fluent') {
                    $fluent = array(
                        'key' => CIVI_METABOX_PREFIX . 'service_language_level',
                        'value' => 'fluent',
                        'compare' => '=',
                    );
                }
                if ((is_array($language_level) && in_array('native', $language_level)) || $language_level == 'native') {
                    $native = array(
                        'key' => CIVI_METABOX_PREFIX . 'service_language_level',
                        'value' => 'native',
                        'compare' => '=',
                    );
                }
                if ((is_array($language_level) && in_array('professional', $language_level)) || $language_level == 'professional') {
                    $professional = array(
                        'key' => CIVI_METABOX_PREFIX . 'service_language_level',
                        'value' => 'professional',
                        'compare' => '=',
                    );
                }
                $meta_query[] = array(
                    'relation' => 'OR',
                    $basic,
                    $conversational,
                    $fluent,
                    $native,
                    $professional
                );
            }

            //meta query service sort by
            if (!empty($sort_by)) {
                if ($sort_by == 'oldest') {
                    $args['order'] = 'ASC';
                }
                if ($sort_by == 'newest') {
                    $args['order'] = 'DESC';
                }
                if ($sort_by == 'rating') {
                    $args['meta_key'] = CIVI_METABOX_PREFIX . 'service_rating';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                }
            }

            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $tax_count = count($tax_query);
            if ($tax_count > 0) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }


            $data = new WP_Query($args);
            $total_post = $data->found_posts;
            $max_num_pages = $data->max_num_pages;
            $service_html = '';
            $service = array();

            if (!empty($total_post)) {
                if (!empty($title)) {
                    $count_post = sprintf(esc_html__('%1$s services for "%2$s"', 'civi-framework'), '<span class="count">' . $total_post . '</span>', $title);
                } else {
                    $count_post = sprintf(_n('%s services', '%s services', $total_post, 'civi-framework'), '<span class="count">' . esc_html($total_post) . '</span>');
                }
            } else {
                $count_post = esc_html__('0 service', 'civi-framework');
            }

            if (!empty($current_term)) {
                $count_post = sprintf(_n('%s services', '%s services', $total_post, 'civi-framework'), '<span class="count">' . esc_html($total_post) . '</span>');
                if (empty($total_post)) {
                    $count_post = sprintf(__('%s services', 'civi-framework'), $total_post);
                }
            }

            $pagination_type = civi_get_option('service_pagination_type', 'loadmore');
            if ($pagination_type == 'number') {
                $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                    'total' => $max_num_pages,
                    'current' => $paged,
                    'mid_size' => 1,
                    'type' => 'array',
                    'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                    'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
                )));
            } else {
                $pagination = '<a class="page-numbers next" href="#"><span>' . __('Load More', 'civi-framework') . '</span><span class="btn-loading"><i class="fal fa-spinner fa-spin large"></i></span></a>';
            }

            $hidden_pagination = '';
            if ($paged == $max_num_pages) {
                $hidden_pagination = 1;
            }

            $enable_service_show_map = civi_get_option('enable_service_show_map');
            $has_map = !empty($_GET['has_map']) ? Civi_Helper::civi_clean(wp_unslash($_GET['has_map'])) : 1;
            if ($total_post > 0 && ($has_map || $enable_service_show_map == '1')) {

                $args_map = $args;
                $args_map['paged'] = '';
                $args_map['posts_per_page'] = '-1';
                $data_map = new WP_Query($args_map);

                while ($data_map->have_posts()) : $data_map->the_post();

                    $service_id = get_the_ID();

                    $service_meta_data = get_post_custom($service_id);

                    $map_zoom_level = civi_get_option('map_zoom_level', '15');

                    $service_address = isset($service_meta_data[CIVI_METABOX_PREFIX . 'service_address']) ? $service_meta_data[CIVI_METABOX_PREFIX . 'service_address'][0] : '';
                    $service_location = get_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_location', true);

                    if (!empty($service_location['location'])) {
                        $lat_lng = explode(',', $service_location['location']);
                    } else {
                        $lat_lng = array();
                    }

                    $author_id = get_post_field('post_author', $service_id);
                    $candidate_avatar = get_the_author_meta('author_avatar_image_url', $author_id);
                    if (!empty($candidate_avatar)) {
                        $marker_icon = $candidate_avatar;
                    } else {
                        $marker_icon = CIVI_PLUGIN_URL . 'assets/images/map-marker-icon.png';
                    };

                    $html_service = ob_start();
                    civi_get_template('content-service.php', array(
                        'services_id' => $service_id,
                        'service_layout' => 'layout-grid',
                        'effect_class' => '',
                    ));
                    $html_service = ob_get_clean();

                    $prop = new stdClass();
                    $prop->id = $service_id;
                    $prop->lat = isset($lat_lng[0]) ? $lat_lng[0] : 59.325;
                    $prop->lng = isset($lat_lng[1]) ? $lat_lng[1] : 18.070;
                    $prop->service = $html_service;

                    if (empty($service_url)) {
                        $service_url = CIVI_PLUGIN_URL . 'assets/images/map-marker-icon.png';
                        $default_marker = civi_get_option('marker_icon', '');
                        if ($default_marker != '') {
                            if (is_array($default_marker) && $default_marker['url'] != '') {
                                $service_url = $default_marker['url'];
                            }
                        }
                    }

                    if ($marker_icon) {
                        $prop->marker_icon = $marker_icon;
                    } else {
                        $prop->marker_icon = $service_url;
                    }

                    array_push($service, $prop);

                endwhile;
            }
            wp_reset_postdata();

            ob_start();

            if ($total_post > 0) {
                while ($data->have_posts()) : $data->the_post();
                    civi_get_template('content-service.php', array(
                        'service_layout' => $service_layout,
                    ));
                endwhile;
            }
            wp_reset_postdata();

            $service_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'resultsCities'=> $resultsCities,'service' => $service, 'count_service' => count($service), 'pagination' => $pagination, 'hidden_pagination' => $hidden_pagination, 'pagination_type' => $pagination_type, 'service_html' => $service_html, 'total_post' => $total_post, 'count_post' => $count_post));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post, 'count_post' => $count_post));
            }
            wp_die();
        }

        /**
         * Jobs Filter
         */
        public function civi_filter_jobs_dashboard()
        {
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $jobs_search = isset($_REQUEST['jobs_search']) ? civi_clean(wp_unslash($_REQUEST['jobs_search'])) : '';
            $jobs_status = isset($_REQUEST['jobs_status']) ? civi_clean(wp_unslash($_REQUEST['jobs_status'])) : '';
            $sort_by = isset($_REQUEST['jobs_sort_by']) ? civi_clean(wp_unslash($_REQUEST['jobs_sort_by'])) : '';
            $item_id = isset($_REQUEST['item_id']) ? civi_clean(wp_unslash($_REQUEST['item_id'])) : '';
            $action_click = isset($_REQUEST['action_click']) ? civi_clean(wp_unslash($_REQUEST['action_click'])) : '';
            $page = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '';

            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $civi_profile = new Civi_Profile();

            $meta_query = array();
            $tax_query = array();

            $package_num_featured_jobs = get_the_author_meta(CIVI_METABOX_PREFIX . 'package_number_featured', $user_id);

            $user_package_id = get_the_author_meta(CIVI_METABOX_PREFIX . 'package_id', $user_id);
            $package_unlimited_listing = get_post_meta($user_package_id, CIVI_METABOX_PREFIX . 'package_unlimited_listing', true);

            if (!empty($item_id)) {
                $jobs = get_post($item_id);

                if ($action_click == 'mark-featured') {
                    update_user_meta($user_id, CIVI_METABOX_PREFIX . 'package_number_featured', $package_num_featured_jobs - 1);
                    update_post_meta($item_id, CIVI_METABOX_PREFIX . 'jobs_featured', 1);
                }

                if ($action_click == 'mark-filled') {
                    $data = array(
                        'ID' => $item_id,
                        'post_type' => 'jobs',
                        'post_status' => 'expired'
                    );
                    wp_update_post($data);
                    update_post_meta($item_id, CIVI_METABOX_PREFIX . 'jobs_featured', 0);
                    update_post_meta($item_id, CIVI_METABOX_PREFIX . 'enable_jobs_expires', 1);
                }

                if ($action_click == 'show') {
                    if ($jobs->post_status == 'pause') {
                        $data = array(
                            'ID' => $item_id,
                            'post_type' => 'jobs',
                            'post_status' => 'publish'
                        );
                    }
                    wp_update_post($data);
                }

                if ($action_click == 'pause') {
                    $data = array(
                        'ID' => $item_id,
                        'post_type' => 'jobs',
                        'post_status' => 'pause'
                    );
                    wp_update_post($data);
                }

                if ($action_click == 'extend') {
                    $date = get_the_date('Y-m-d');
                    $post_date = $date . ' 00:00:00';
                    $data = array(
                        'ID' => $item_id,
                        'post_type' => 'jobs',
                        'post_status' => 'publish',
                        'post_date' => $post_date,
                        'post_date_gmt' => get_gmt_from_date($post_date),
                    );
                    wp_update_post($data);
                    update_post_meta($item_id, CIVI_METABOX_PREFIX . 'enable_jobs_expires', 0);
                }
            }

            $args = array(
                'post_type' => 'jobs',
                'paged' => $paged,
                'post_status' => array('publish', 'expired', 'pending', 'pause'),
                'ignore_sticky_posts' => 1,
                'author' => $user_id,
                'orderby' => 'date',
            );

            if (!empty($jobs_search)) {
                $args['s'] = $jobs_search;
            }

            if (!empty($jobs_status)) {
                $args['post_status'] = $jobs_status;
            }

            if (!empty($item_amount)) {
                $args['posts_per_page'] = $item_amount;
            }

            //meta query jobs sort_by
            if (!empty($sort_by)) {
                if ($sort_by == 'newest') {
                    $args['order'] = 'DESC';
                }
                if ($sort_by == 'oldest') {
                    $args['order'] = 'ASC';
                }
                if ($sort_by == 'featured') {
                    $meta_query[] = array(
                        'key' => CIVI_METABOX_PREFIX . 'jobs_featured',
                        'value' => 1,
                        'type' => 'NUMERIC',
                        'compare' => '=',
                    );
                }
            }

            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $tax_count = count($tax_query);
            if ($tax_count > 0) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            $data = new WP_Query($args);
            $total_post = $data->found_posts;
            $max_num_pages = $data->max_num_pages;
            $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                'total' => $max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'type' => 'array',
                //'add_args'  => array_map( 'urlencode', $args ),
                'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
            )));

            ob_start();

            $extend_expired_jobs = civi_get_option('enable_extend_expired_jobs');
            if ($total_post > 0) {
                while ($data->have_posts()) : $data->the_post();
                    $id = get_the_ID();
                    $status = get_post_status($id);
                    $jobs_type = get_the_terms($id, 'jobs-type');
                    $jobs_categories = get_the_terms($id, 'jobs-categories');
                    $jobs_location = get_the_terms($id, 'jobs-location');
                    $public_date = get_the_date('Y-m-d');
                    $current_date = date('Y-m-d');
                    $jobs_days_single = get_post_meta($id, CIVI_METABOX_PREFIX . 'jobs_days_closing', true);
                    $enable_jobs_expires = get_post_meta($id, CIVI_METABOX_PREFIX . 'enable_jobs_expires', true);
                    if ($enable_jobs_expires == '1') {
                        $jobs_days_closing = '0';
                    } else {
                        if ($jobs_days_single) {
                            $jobs_days_closing = $jobs_days_single;
                        } else {
                            $jobs_days_closing = civi_get_option('jobs_number_days', true);
                        }
                    }
                    $expiration_date = date('Y-m-d', strtotime($public_date . '+' . $jobs_days_closing . ' days'));
                    $jobs_featured = get_post_meta($id, CIVI_METABOX_PREFIX . 'jobs_featured', true);

                    $val_expiration_date = date(get_option('date_format'), strtotime($public_date . '+' . $jobs_days_closing . ' days'));
                    $val_public_date = get_the_date(get_option('date_format'));
                    ?>
                    <tr>
                        <td>
                            <h3 class="title-jobs-dashboard">
                                <a href="<?php echo civi_get_permalink('jobs_dashboard') ?>?pages=performance&tab=statics&jobs_id=<?php echo esc_attr($id); ?>">
                                    <?php echo civi_get_icon_status($id); ?>
                                    <?php echo get_the_title($id); ?>
                                </a>
                            </h3>
                            <p>
                                <?php if (is_array($jobs_categories)) {
                                    foreach ($jobs_categories as $categories) { ?>
                                        <?php esc_html_e($categories->name); ?>
                                <?php }
                                } ?>
                                <?php if (is_array($jobs_type)) {
                                    foreach ($jobs_type as $type) { ?>
                                        <?php esc_html_e('/ ' . $type->name); ?>
                                <?php }
                                } ?>
                                <?php if (is_array($jobs_location)) {
                                    foreach ($jobs_location as $location) { ?>
                                        <?php esc_html_e('/ ' . $location->name); ?>
                                <?php }
                                } ?>
                            </p>
                        </td>
                        <td>
                            <div class="number-applicant">
                                <span class="number"><?php echo civi_total_applications_jobs_id($id); ?></span>
                                <?php if (civi_total_applications_jobs_id($id) > 1) { ?>
                                    <a href="<?php echo civi_get_permalink('jobs_dashboard') ?>?pages=performance&tab=applicants&jobs_id=<?php echo esc_attr($id); ?>"><?php esc_html_e('Applicants', 'civi-framework') ?></a>
                                <?php } else { ?>
                                    <a href="<?php echo civi_get_permalink('jobs_dashboard') ?>?pages=performance&tab=applicants&jobs_id=<?php echo esc_attr($id); ?>"><?php esc_html_e('Application', 'civi-framework') ?></a>
                                <?php } ?>
                            </div>
                        </td>
                        <td>
                            <?php if ($enable_jobs_expires == '1' || $status == 'expired') : ?>
                                <span class="label label-close"><?php esc_html_e('Closed', 'civi-framework') ?></span>
                            <?php endif; ?>
                            <?php if ($status == 'publish' && $enable_jobs_expires != '1') : ?>
                                <span class="label label-open"><?php esc_html_e('Opening', 'civi-framework') ?></span>
                            <?php endif; ?>
                            <?php if ($status == 'pending') : ?>
                                <span class="label label-pending"><?php esc_html_e('Pending', 'civi-framework') ?></span>
                            <?php endif; ?>
                            <?php if ($status == 'pause') : ?>
                                <span class="label label-pause"><?php esc_html_e('Pause', 'civi-framework') ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="start-time"><?php echo $val_public_date ?></span>
                        </td>
                        <td>
                            <span class="expires-time">
                                <?php if ($expiration_date > $public_date && $expiration_date > $current_date) : ?>
                                    <?php echo $val_expiration_date ?>
                                <?php else : ?>
                                    <span><?php esc_html_e('Expires', 'civi-framework') ?></span>
                                <?php endif ?>
                            </span>
                        </td>
                        <?php
                        ?>
                        <td class="action-setting jobs-control">
                            <?php if ($status !== 'expired') : ?>
                                <a href="#" class="icon-setting"><i class="fal fa-ellipsis-h"></i></a>
                                <ul class="action-dropdown">
                                    <?php
                                    $jobs_dashboard_link = civi_get_permalink('jobs_dashboard');
                                    $paid_submission_type = civi_get_option('paid_submission_type', 'no');
                                    $check_package = $civi_profile->user_package_available($user_id);
                                    $package_num_featured_jobs = get_the_author_meta(CIVI_METABOX_PREFIX . 'package_number_featured', $user_id);
                                    switch ($status) {
                                        case 'publish':
                                            if ($paid_submission_type == 'per_package') {

                                                if ($check_package != -1 && $check_package != 0) { ?>
                                                    <li><a class="btn-edit" href="<?php echo esc_url($jobs_dashboard_link); ?>?pages=edit&jobs_id=<?php echo esc_attr($id); ?>"><?php esc_html_e('Edit', 'civi-framework'); ?></a>
                                                    </li>
                                                <?php }

                                                if ($check_package != -1 && $check_package != 0) { ?>
                                                    <li><a class="btn-pause" jobs-id="<?php echo esc_attr($id); ?>" href="<?php echo get_the_permalink($id); ?>"><?php esc_html_e('Paused', 'civi-framework') ?></a>
                                                    </li>
                                                <?php }

                                                if ($package_num_featured_jobs > 0 && $jobs_featured != 1 && $check_package != -1 && $check_package != 0) { ?>
                                                    <li><a class="btn-mark-featured" jobs-id="<?php echo esc_attr($id); ?>" href="<?php echo get_the_permalink($id); ?>"><?php esc_html_e('Mark featured', 'civi-framework') ?></a>
                                                    </li>
                                                <?php }

                                                if ($check_package != -1 && $check_package != 0) { ?>
                                                    <li><a class="btn-mark-filled" jobs-id="<?php echo esc_attr($id); ?>" href="<?php echo get_the_permalink($id); ?>"><?php esc_html_e('Mark Filled', 'civi-framework') ?></a>
                                                    </li>
                                                <?php }

                                                if ($check_package != -1 && $check_package != 0) { ?>
                                                    <li>
                                                        <a href="<?php echo get_the_permalink($id); ?>"><?php esc_html_e('View detail', 'civi-framework') ?></a>
                                                    </li>
                                                <?php }
                                            } else {
                                                ?>
                                                <li><a class="btn-edit" href="<?php echo esc_url($jobs_dashboard_link); ?>?pages=edit&jobs_id=<?php echo esc_attr($id); ?>"><?php esc_html_e('Edit', 'civi-framework'); ?></a>
                                                </li>
                                                <li><a class="btn-pause" jobs-id="<?php echo esc_attr($id); ?>" href="<?php echo get_the_permalink($id); ?>"><?php esc_html_e('Paused', 'civi-framework') ?></a>
                                                </li>
                                                </li>
                                                <?php if ($jobs_featured != 1) {
                                                ?>
                                                    <li><a class="btn-mark-featured" jobs-id="<?php echo esc_attr($id); ?>" href="<?php echo get_the_permalink($id); ?>"><?php esc_html_e('Mark featured', 'civi-framework') ?></a>
                                                    </li>
                                                <?php } ?>
                                                <li><a class="btn-mark-filled" jobs-id="<?php echo esc_attr($id); ?>" href="<?php echo get_the_permalink($id); ?>"><?php esc_html_e('Mark Filled', 'civi-framework') ?></a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo get_the_permalink($id); ?>"><?php esc_html_e('View detail', 'civi-framework') ?></a>
                                                </li>
                                            <?php }
                                            break;
                                        case 'pending':
                                            ?>
                                            <li><a class="btn-edit" href="<?php echo esc_url($jobs_dashboard_link); ?>?pages=edit&jobs_id=<?php echo esc_attr($id); ?>"><?php esc_html_e('Edit', 'civi-framework'); ?></a>
                                            </li>
                                        <?php
                                            break;
                                        case 'pause':
                                        ?>
                                            <li><a class="btn-edit" href="<?php echo esc_url($jobs_dashboard_link); ?>?pages=edit&jobs_id=<?php echo esc_attr($id); ?>"><?php esc_html_e('Edit', 'civi-framework'); ?></a>
                                            </li>
                                            <li><a class="btn-show" jobs-id="<?php echo esc_attr($id); ?>" href="<?php echo get_the_permalink($id); ?>"><?php esc_html_e('Continue', 'civi-framework'); ?></a>
                                        <?php
                                    } ?>
                                </ul>
                            <?php else : ?>
                                <?php if($extend_expired_jobs == 1) :?>
                                    <a href="#" class="icon-setting"><i class="fal fa-ellipsis-h"></i></a>
                                    <ul class="action-dropdown">
                                        <li><a class="btn-extend" jobs-id="<?php echo esc_attr($id); ?>"><?php esc_html_e('Extend','civi-framework'); ?></a></li>
                                    </ul>
                                <?php else: ?>
                                    <a href="#" class="icon-setting btn-add-to-message" data-text="<?php echo esc_attr('Jobs has expired so you can not change it', 'civi-framework'); ?>"><i class="fal fa-ellipsis-h"></i></a>
                                    </a>
                                <?php endif;?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php
                endwhile;
            }
            wp_reset_postdata();

            $jobs_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true,'pagination' => $pagination, 'jobs_html' => $jobs_html, 'total_post' => $total_post, 'page' => $page));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post));
            }
            wp_die();
        }


        /**
         * Employer Order
         */
        public function civi_employer_order_service()
        {
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $service_search = isset($_REQUEST['service_search']) ? civi_clean(wp_unslash($_REQUEST['service_search'])) : '';
            $service_status = isset($_REQUEST['service_status']) ? civi_clean(wp_unslash($_REQUEST['service_status'])) : '';
            $sort_by = isset($_REQUEST['service_sort_by']) ? civi_clean(wp_unslash($_REQUEST['service_sort_by'])) : '';
            $item_id = isset($_REQUEST['item_id']) ? civi_clean(wp_unslash($_REQUEST['item_id'])) : '';
            $content_refund = isset($_REQUEST['content_refund']) ? civi_clean(wp_unslash($_REQUEST['content_refund'])) : '';
            $service_payment = isset($_REQUEST['service_payment']) ? civi_clean(wp_unslash($_REQUEST['service_payment'])) : '';
            $action_click = isset($_REQUEST['action_click']) ? civi_clean(wp_unslash($_REQUEST['action_click'])) : '';
            $page = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '';

            global $current_user;
            $user_id = $current_user->ID;
            $meta_query = array();
            $tax_query = array();
            $enable_candidate_service_fee = civi_get_option('enable_candidate_service_fee');
            $candidate_number_service_fee = civi_get_option('candidate_number_service_fee');
            $currency_sign_default = civi_get_option('currency_sign_default');

            if (!empty($item_id)) {
                if ($action_click == 'completed') {
                    update_post_meta($item_id, CIVI_METABOX_PREFIX . 'service_order_payment_status', 'completed');
                    if(!empty($currency_sign_default)) {
                        $price = get_post_meta($item_id, CIVI_METABOX_PREFIX . 'service_order_price', true);
                        $author_id = get_post_meta($item_id, CIVI_METABOX_PREFIX . 'service_order_author_id', true);
                        $price = str_replace($currency_sign_default, '', $price);
                        if ($enable_candidate_service_fee === '1' && !empty($candidate_number_service_fee)) {
                            $total_price = intval($price) * (100 - intval($candidate_number_service_fee)) / 100;
                        } else {
                            $total_price = $price;
                        }
                        $withdraw_price = get_user_meta($author_id, CIVI_METABOX_PREFIX . 'service_withdraw_total_price', true);
                        if(empty($withdraw_price)){
                            $withdraw_price = 0;
                        }
                        $withdraw_price = $withdraw_price + $total_price;
                        update_user_meta($author_id, CIVI_METABOX_PREFIX . 'service_withdraw_total_price', $withdraw_price);
                    }
                }
                if ($action_click == 'refund') {
                    update_post_meta($item_id, CIVI_METABOX_PREFIX . 'service_order_payment_status', 'refund');
                    update_post_meta($item_id, CIVI_METABOX_PREFIX . 'service_refund_payment_method', $service_payment);
                    update_post_meta($item_id, CIVI_METABOX_PREFIX . 'service_refund_content', $content_refund);
                }
            }

            $args = array(
                'post_type' => 'service_order',
                'paged' => $paged,
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
                'author' => $user_id,
            );

            if (!empty($service_search)) {
                $args['s'] = $service_search;
            }

            if (!empty($service_status)) {
                $meta_query[] = array(
                    'key' => CIVI_METABOX_PREFIX . 'service_order_payment_status',
                    'value' => $service_status,
                    'compare' => '=',
                );
            }

            if (!empty($item_amount)) {
                $args['posts_per_page'] = $item_amount;
            }

            if (!empty($sort_by)) {
                if ($sort_by == 'newest') {
                    $args['order'] = 'DESC';
                }
                if ($sort_by == 'oldest') {
                    $args['order'] = 'ASC';
                }
            }

            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $tax_count = count($tax_query);
            if ($tax_count > 0) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            $data = new WP_Query($args);
            $total_post = $data->found_posts;
            $max_num_pages = $data->max_num_pages;
            $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                'total' => $max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'type' => 'array',
                //'add_args'  => array_map( 'urlencode', $args ),
                'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
            )));

            ob_start();

            if ($total_post > 0) {
                while ($data->have_posts()) : $data->the_post(); ?>
                    <?php
                    $order_id = get_the_ID();
                    $service_id = get_post_meta($order_id, CIVI_METABOX_PREFIX . 'service_order_item_id', true);
                    $service_skills = get_the_terms($service_id, 'service-skills');
                    $service_categories = get_the_terms($service_id, 'service-categories');
                    $service_location = get_the_terms($service_id, 'service-location');
                    $public_date = get_the_date(get_option('date_format'));
                    $thumbnail = get_the_post_thumbnail_url($service_id, '70x70');
                    $author_id = get_post_field('post_author', $service_id);
                    $author_name = get_the_author_meta('display_name', $author_id);

                    $active_date = strtotime(get_the_date('Y-m-d H:i:s'));
                    $current_time = strtotime(current_datetime()->format('Y-m-d H:i:s'));
                    $service_time_type = get_post_meta($order_id, CIVI_METABOX_PREFIX . 'service_order_time_type', true);
                    $number_delivery_time = intval(get_post_meta($order_id, CIVI_METABOX_PREFIX . 'service_order_number_time', true));
                    switch ($service_time_type) {
                        case 'hr':
                            $seconds = 60 * 60;
                            break;
                        case 'day':
                            $seconds = 60 * 60 * 24;
                            break;
                        case 'week':
                            $seconds = 60 * 60 * 24 * 7;
                            break;
                        case 'month':
                            $seconds = 60 * 60 * 24 * 30;
                            break;
                    }
                    if (is_numeric($active_date) && is_numeric($seconds) && is_numeric($number_delivery_time)) {
                        $expired_time = $active_date + ($seconds * $number_delivery_time);
                    } else {
                        $expired_time = 0;
                    }

                    if ($current_time < $expired_time) {
                        $seconds = $expired_time - $current_time;
                        $dtF = new \DateTime('@0');
                        $dtT = new \DateTime("@$seconds");
                        $expired_days = $dtF->diff($dtT)->format('%a');
                        $expired_hours = $dtF->diff($dtT)->format('%h');
                        $expired_minutes = $dtF->diff($dtT)->format('%i');
                        if($expired_days > 0){
                            if($expired_days === '1'){
                                $expired_date = sprintf(esc_html__('%1s day %2s hours', 'civi-framework'), $expired_days,$expired_hours);
                            } else {
                                $expired_date = sprintf(esc_html__('%1s days %2s hours', 'civi-framework'), $expired_days,$expired_hours);
                            }
                        } else {
                            if($expired_hours === '1'){
                                $expired_date = sprintf(esc_html__('%1s hour %2s minutes', 'civi-framework'), $expired_hours,$expired_minutes);
                            } else {
                                $expired_date = sprintf(esc_html__('%1s hours %2s minutes', 'civi-framework'), $expired_hours,$expired_minutes);
                            }
                        }
                    } else {
                        $expired_date = esc_html__('expired', 'civi-framework');
                        update_post_meta($order_id, CIVI_METABOX_PREFIX . 'service_order_payment_status', 'expired');
                    }

                    $args_candidate = array(
                        'post_type' => 'candidate',
                        'posts_per_page' => 1,
                        'author' => $author_id,
                    );
                    $current_user_posts = get_posts($args_candidate);
                    $candidate_id = !empty($current_user_posts) ? $current_user_posts[0]->ID : '';
                    $service_featured = get_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_featured', true);
                    $total_price = get_post_meta($order_id, CIVI_METABOX_PREFIX . 'service_order_price', true);
                    $service_refund_content = get_post_meta($order_id, CIVI_METABOX_PREFIX . 'service_refund_content', true);
                    $status = get_post_meta($order_id, CIVI_METABOX_PREFIX . 'service_order_payment_status', true);
                    ?>
                    <tr>
                        <td>
                            <span><?php echo '#' . $order_id; ?></span>
                        </td>
                        <td>
                            <div class="service-header">
                                <?php if (!empty($thumbnail)) : ?>
                                    <img class="thumbnail" src="<?php echo $thumbnail; ?>" alt=""/>
                                <?php endif; ?>
                                <div class="content">
                                    <h3 class="title-my-service">
                                        <a href="<?php echo get_the_permalink($service_id) ?>">
                                            <?php echo get_the_title($service_id); ?>
                                            <?php if ($service_featured === '1') : ?>
                                                <span class="tooltip featured"
                                                      data-title="<?php esc_attr_e('Featured', 'civi-framework') ?>">
                                                    <img src="<?php echo esc_attr(CIVI_PLUGIN_URL . 'assets/images/icon-featured.svg'); ?>"
                                                         alt="<?php echo esc_attr('featured', 'civi-framework') ?>">
                                                    </span>
                                            <?php endif; ?>
                                        </a>
                                    </h3>
                                    <p>
                                        <?php if (is_array($service_categories)) {
                                            foreach ($service_categories as $categories) { ?>
                                                <span class="cate"><?php esc_html_e($categories->name); ?></span>
                                            <?php }
                                        } ?>
                                        <?php if (is_array($service_skills)) {
                                            foreach ($service_skills as $skills) { ?>
                                                <?php esc_html_e('/ ' . $skills->name); ?>
                                            <?php }
                                        } ?>
                                        <?php if (is_array($service_location)) {
                                            foreach ($service_location as $location) { ?>
                                                <?php esc_html_e('/ ' . $location->name); ?>
                                            <?php }
                                        } ?>
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="author">
                            <a href="<?php echo get_post_permalink($candidate_id) ?>"><?php echo $author_name; ?></a>
                        </td>
                        <td class="status">
                            <?php civi_service_order_status($status); ?>
                        </td>
                        <td class="price">
                            <?php echo $total_price; ?>
                        </td>
                        <td class="start-time">
                            <?php echo $public_date; ?>
                            <span>(<?php echo $expired_date; ?>)</span>
                        </td>
                        <?php if($status !== 'completed' &&  $status !== 'inprogress') : ?>
                            <td class="action-setting service-control">
                                <a href="#" class="icon-setting"><i class="fal fa-ellipsis-h"></i></a>
                                <ul class="action-dropdown">
                                    <?php switch ($status) {
                                        case 'transferring': ?>
                                            <li><a class="btn-completed" order-id="<?php echo esc_attr($order_id); ?>"
                                                   href="#"><?php esc_html_e('Completed', 'civi-framework') ?></a></li>
                                            <li><a class="btn-order-refund"  order-id="<?php echo esc_attr($order_id); ?>"
                                                   href="#form-service-order-refund"><?php esc_html_e('Refund', 'civi-framework') ?></a></li>
                                            <?php break;
                                        case 'pending': ?>
                                            <li><a class="btn-order-refund" order-id="<?php echo esc_attr($order_id); ?>"
                                                   href="#form-service-order-refund"><?php esc_html_e('Refund', 'civi-framework') ?></a></li>
                                            <?php break;
                                        case 'canceled': ?>
                                            <li><a class="btn-order-refund" order-id="<?php echo esc_attr($order_id); ?>"
                                                   href="#form-service-order-refund"><?php esc_html_e('Refund', 'civi-framework') ?></a></li>
                                            <?php break;
                                        case 'expired': ?>
                                            <li><a class="btn-completed" order-id="<?php echo esc_attr($order_id); ?>"
                                                   href="#"><?php esc_html_e('Completed', 'civi-framework') ?></a></li>
                                            <li><a class="btn-order-refund"  order-id="<?php echo esc_attr($order_id); ?>"
                                                   href="#form-service-order-refund"><?php esc_html_e('Refund', 'civi-framework') ?></a></li>
                                            <?php break;
                                        case 'refund': ?>
                                            <?php if(!empty($service_refund_content)) : ?>
                                                <li><a class="btn-view-reason" order-id="<?php echo esc_attr($order_id); ?>" data-content-refund="<?php echo $service_refund_content;?>"
                                                       href="#form-service-view-reason"><?php esc_html_e('View reason', 'civi-framework') ?></a>
                                                </li>
                                            <?php else: ?>
                                                <li><a class="btn-add-to-message" href="#" data-text="<?php echo esc_attr('Refund reason text is empty'); ?>"><?php esc_html_e('View reason', 'civi-framework'); ?></a></li>
                                            <?php endif;?>
                                            <?php break;
                                    } ?>
                                </ul>
                            </td>
                        <?php else: ?>
                            <td></td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile;
            }
            wp_reset_postdata();

            $service_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'content_refund' => $content_refund, 'pagination' => $pagination, 'service_html' => $service_html, 'total_post' => $total_post, 'page' => $page));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post));
            }
            wp_die();
        }


        /**
         * Candidate Order
         */
        public function civi_candidate_order_service()
        {
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $service_search = isset($_REQUEST['service_search']) ? civi_clean(wp_unslash($_REQUEST['service_search'])) : '';
            $service_status = isset($_REQUEST['service_status']) ? civi_clean(wp_unslash($_REQUEST['service_status'])) : '';
            $sort_by = isset($_REQUEST['service_sort_by']) ? civi_clean(wp_unslash($_REQUEST['service_sort_by'])) : '';
            $item_id = isset($_REQUEST['item_id']) ? civi_clean(wp_unslash($_REQUEST['item_id'])) : '';
            $action_click = isset($_REQUEST['action_click']) ? civi_clean(wp_unslash($_REQUEST['action_click'])) : '';
            $page = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '';

            global $current_user;
            $user_id = $current_user->ID;
            $meta_query = array();
            $tax_query = array();

            if (!empty($item_id)) {
                if ($action_click == 'transferring') {
                    update_post_meta($item_id, CIVI_METABOX_PREFIX . 'service_order_payment_status', 'transferring');
                }
                if ($action_click == 'canceled') {
                    update_post_meta($item_id, CIVI_METABOX_PREFIX . 'service_order_payment_status', 'canceled');
                }
            }

            $args = array(
                'post_type' => 'service_order',
                'paged' => $paged,
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
                'meta_query' => array(
                    array(
                        'key' => CIVI_METABOX_PREFIX . 'service_order_author_id',
                        'value' => $user_id,
                        'compare' => '==',
                    )
                ),
            );

            if (!empty($service_search)) {
                $args['s'] = $service_search;
            }

            if (!empty($service_status)) {
                $meta_query[] = array(
                    'key' => CIVI_METABOX_PREFIX . 'service_order_payment_status',
                    'value' => $service_status,
                    'compare' => '=',
                );
            }

            if (!empty($item_amount)) {
                $args['posts_per_page'] = $item_amount;
            }

            if (!empty($sort_by)) {
                if ($sort_by == 'newest') {
                    $args['order'] = 'DESC';
                }
                if ($sort_by == 'oldest') {
                    $args['order'] = 'ASC';
                }
            }

            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $tax_count = count($tax_query);
            if ($tax_count > 0) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            $data = new WP_Query($args);
            $total_post = $data->found_posts;
            $max_num_pages = $data->max_num_pages;
            $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                'total' => $max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'type' => 'array',
                //'add_args'  => array_map( 'urlencode', $args ),
                'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
            )));

            ob_start();

            if ($total_post > 0) {
                while ($data->have_posts()) : $data->the_post(); ?>
                    <?php
                    $order_id = get_the_ID();
                    $service_id = get_post_meta($order_id, CIVI_METABOX_PREFIX . 'service_order_item_id', true);
                    $service_skills = get_the_terms($service_id, 'service-skills');
                    $service_categories = get_the_terms($service_id, 'service-categories');
                    $service_location = get_the_terms($service_id, 'service-location');
                    $public_date = get_the_date(get_option('date_format'));
                    $thumbnail = get_the_post_thumbnail_url($service_id, '70x70');
                    $service_featured = get_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_featured', true);
                    $author_id = get_post_field('post_author', $order_id);
                    $author_name = get_the_author_meta('display_name', $author_id);
                    $status = get_post_meta($order_id, CIVI_METABOX_PREFIX . 'service_order_payment_status', true);
                    $total_price = get_post_meta($order_id, CIVI_METABOX_PREFIX . 'service_order_price', true);
                    $service_refund_content = get_post_meta($order_id, CIVI_METABOX_PREFIX . 'service_refund_content', true);

                    $active_date = strtotime(get_the_date('Y-m-d H:i:s'));
                    $current_time = strtotime(current_datetime()->format('Y-m-d H:i:s'));
                    $service_time_type = get_post_meta($order_id, CIVI_METABOX_PREFIX . 'service_order_time_type', true);
                    $number_delivery_time = intval(get_post_meta($order_id, CIVI_METABOX_PREFIX . 'service_order_number_time', true));
                    switch ($service_time_type) {
                        case 'hr':
                            $seconds = 60 * 60;
                            break;
                        case 'day':
                            $seconds = 60 * 60 * 24;
                            break;
                        case 'week':
                            $seconds = 60 * 60 * 24 * 7;
                            break;
                        case 'month':
                            $seconds = 60 * 60 * 24 * 30;
                            break;
                    }
                    if (is_numeric($active_date) && is_numeric($seconds) && is_numeric($number_delivery_time)) {
                        $expired_time = $active_date + ($seconds * $number_delivery_time);
                    } else {
                        $expired_time = 0;
                    }

                    if ($current_time < $expired_time) {
                        $seconds = $expired_time - $current_time;
                        $dtF = new \DateTime('@0');
                        $dtT = new \DateTime("@$seconds");
                        $expired_days = $dtF->diff($dtT)->format('%a');
                        $expired_hours = $dtF->diff($dtT)->format('%h');
                        $expired_minutes = $dtF->diff($dtT)->format('%i');
                        if($expired_days > 0){
                            if($expired_days === '1'){
                                $expired_date = sprintf(esc_html__('%1s day %2s hours', 'civi-framework'), $expired_days,$expired_hours);
                            } else {
                                $expired_date = sprintf(esc_html__('%1s days %2s hours', 'civi-framework'), $expired_days,$expired_hours);
                            }
                        } else {
                            if($expired_hours === '1'){
                                $expired_date = sprintf(esc_html__('%1s hour %2s minutes', 'civi-framework'), $expired_hours,$expired_minutes);
                            } else {
                                $expired_date = sprintf(esc_html__('%1s hours %2s minutes', 'civi-framework'), $expired_hours,$expired_minutes);
                            }
                        }
                    } else {
                        $expired_date = esc_html__('expired', 'civi-framework');
                        update_post_meta($order_id, CIVI_METABOX_PREFIX . 'service_order_payment_status', 'expired');
                    }
                    $status = get_post_meta($order_id, CIVI_METABOX_PREFIX . 'service_order_payment_status', true);
                    ?>
                    <tr>
                        <td>
                            <span><?php echo '#' . $order_id; ?></span>
                        </td>
                        <td>
                            <div class="service-header">
                                <?php if (!empty($thumbnail)) : ?>
                                    <img class="thumbnail" src="<?php echo $thumbnail; ?>" alt=""/>
                                <?php endif; ?>
                                <div class="content">
                                    <h3 class="title-my-service">
                                        <a href="<?php echo get_the_permalink($service_id) ?>">
                                            <?php echo get_the_title($service_id); ?>
                                            <?php if ($service_featured === '1') : ?>
                                                <span class="tooltip featured"
                                                      data-title="<?php esc_attr_e('Featured', 'civi-framework') ?>">
                                                    <img src="<?php echo esc_attr(CIVI_PLUGIN_URL . 'assets/images/icon-featured.svg'); ?>"
                                                         alt="<?php echo esc_attr('featured', 'civi-framework') ?>">
                                                    </span>
                                            <?php endif; ?>
                                        </a>
                                    </h3>
                                    <p>
                                        <?php if (is_array($service_categories)) {
                                            foreach ($service_categories as $categories) { ?>
                                                <span class="cate"><?php esc_html_e($categories->name); ?></span>
                                            <?php }
                                        } ?>
                                        <?php if (is_array($service_skills)) {
                                            foreach ($service_skills as $skills) { ?>
                                                <?php esc_html_e('/ ' . $skills->name); ?>
                                            <?php }
                                        } ?>
                                        <?php if (is_array($service_location)) {
                                            foreach ($service_location as $location) { ?>
                                                <?php esc_html_e('/ ' . $location->name); ?>
                                            <?php }
                                        } ?>
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="author">
                            <?php echo $author_name; ?>
                        </td>
                        <td class="status">
                            <?php civi_service_order_status($status); ?>
                        </td>
                        <td class="price">
                            <?php echo $total_price; ?>
                        </td>
                        <td class="start-time">
                            <?php echo $public_date; ?>
                            <span>(<?php echo $expired_date; ?>)</span>
                        </td>
                        <?php if($status === 'inprogress' || $status === 'refund') : ?>
                            <td class="action-setting service-control">
                                <a href="#" class="icon-setting"><i class="fal fa-ellipsis-h"></i></a>
                                <ul class="action-dropdown">
                                    <?php switch ($status) {
                                        case 'inprogress': ?>
                                            <li><a class="btn-delivery" item-id="<?php echo esc_attr($order_id); ?>"
                                                   href="#"><?php esc_html_e('Transfer', 'civi-framework') ?></a></li>
                                            <li><a class="btn-canceled" item-id="<?php echo esc_attr($order_id); ?>"
                                                   href="#"><?php esc_html_e('Canceled', 'civi-framework') ?></a></li>
                                            <?php break;
                                        case 'refund': ?>
                                            <?php if(!empty($service_refund_content)) : ?>
                                                <li><a class="btn-view-reason" order-id="<?php echo esc_attr($order_id); ?>" data-content-refund="<?php echo $service_refund_content;?>"
                                                       href="#form-service-view-reason"><?php esc_html_e('View reason', 'civi-framework') ?></a>
                                                </li>
                                            <?php else: ?>
                                                <li><a class="btn-add-to-message" href="#" data-text="<?php echo esc_attr('Refund reason text is empty'); ?>"><?php esc_html_e('View reason', 'civi-framework'); ?></a></li>
                                            <?php endif;?>
                                            <?php break;
                                    } ?>
                                </ul>
                            </td>
                        <?php else: ?>
                            <td></td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile;
            }
            wp_reset_postdata();

            $service_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'pagination' => $pagination, 'service_html' => $service_html, 'total_post' => $total_post, 'page' => $page));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post));
            }
            wp_die();
        }


        /**
         * Wallet service
         */
        public function civi_candidate_wallet_service()
        {
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $wallet_method = isset($_REQUEST['wallet_method']) ? civi_clean(wp_unslash($_REQUEST['wallet_method'])) : '';
            $wallet_status = isset($_REQUEST['wallet_status']) ? civi_clean(wp_unslash($_REQUEST['wallet_status'])) : '';
            $wallet_sort_by = isset($_REQUEST['wallet_sort_by']) ? civi_clean(wp_unslash($_REQUEST['wallet_sort_by'])) : '';
            $page = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '';

            global $current_user;
            $user_id = $current_user->ID;
            $meta_query = array();
            $tax_query = array();

            $args = array(
                'post_type' => 'service_withdraw',
                'paged' => $paged,
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
                'meta_query' => array(
                    array(
                        'key' => CIVI_METABOX_PREFIX . 'service_withdraw_user_id',
                        'value' => $user_id,
                        'compare' => '==',
                    )
                ),
            );

            if (!empty($wallet_status)) {
                $meta_query[] = array(
                    'key' => CIVI_METABOX_PREFIX . 'service_withdraw_status',
                    'value' => $wallet_status,
                    'compare' => '=',
                );
            }

            if (!empty($wallet_method)) {
                $meta_query[] = array(
                    'key' => CIVI_METABOX_PREFIX . 'service_withdraw_payment_method',
                    'value' => $wallet_method,
                    'compare' => '=',
                );
            }

            if (!empty($item_amount)) {
                $args['posts_per_page'] = $item_amount;
            }

            if (!empty($wallet_sort_by)) {
                if ($wallet_sort_by == 'newest') {
                    $args['order'] = 'DESC';
                }
                if ($wallet_sort_by == 'oldest') {
                    $args['order'] = 'ASC';
                }
            }

            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $tax_count = count($tax_query);
            if ($tax_count > 0) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            $data = new WP_Query($args);
            $total_post = $data->found_posts;
            $max_num_pages = $data->max_num_pages;
            $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                'total' => $max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'type' => 'array',
                //'add_args'  => array_map( 'urlencode', $args ),
                'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
            )));

            ob_start();

            if ($total_post > 0) {
                while ($data->have_posts()) : $data->the_post(); ?>
                    <?php
                    $withdraw_id = get_the_ID();
                    $payment_method = get_post_meta($withdraw_id, CIVI_METABOX_PREFIX . 'service_withdraw_payment_method',true);
                    $price = get_post_meta($withdraw_id, CIVI_METABOX_PREFIX . 'service_withdraw_price',true);
                    $status = get_post_meta($withdraw_id, CIVI_METABOX_PREFIX . 'service_withdraw_status',true);
                    $request_date =  get_the_date(get_option('date_format'));
                    $process_date = get_post_meta($withdraw_id, CIVI_METABOX_PREFIX . 'service_withdraw_process_date',true);
                    if(empty($process_date)){
                        $process_date = '...';
                    } else {
                        $process_date = civi_convert_date_format($process_date);
                    }
                    if($payment_method == 'wire_transfer'){
                        $method = esc_html__('Wire Transfer', 'civi-framework');
                    } elseif ($payment_method == 'stripe'){
                        $method = esc_html__('Stripe', 'civi-framework');
                    } elseif ($payment_method == 'paypal'){
                        $method = esc_html__('Paypal', 'civi-framework');
                    }
                    $currency_position = civi_get_option('currency_position');
                    $currency_sign_default = civi_get_option('currency_sign_default');
                    if ($currency_position == 'before') {
                        $price = $currency_sign_default . $price;
                    } else {
                        $price = $price . $currency_sign_default;
                    }
                    ?>
                    <tr>
                        <td>
                            <?php echo $method;?>
                        </td>
                        <td>
                            <?php if ($status == 'pending') : ?>
                                <span class="label label-pending"><?php esc_html_e('Pending', 'civi-framework') ?></span>
                            <?php elseif ($status == 'canceled') : ?>
                                <span class="label label-close"><?php esc_html_e('Canceled', 'civi-framework') ?></span>
                            <?php elseif ($status == 'completed') : ?>
                                <span class="label label-open"><?php esc_html_e('Completed', 'civi-framework') ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="price">
                            <?php echo $price; ?>
                        </td>
                        <td>
                            <?php echo $request_date; ?>
                        </td>
                        <td>
                            <?php echo $process_date; ?>
                        </td>
                    </tr>
                <?php endwhile;
            }
            wp_reset_postdata();

            $wallet_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'pagination' => $pagination, 'wallet_html' => $wallet_html, 'total_post' => $total_post, 'page' => $page));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post));
            }
            wp_die();
        }

        /**
         * Submit withdraw
         */
        public function civi_submit_withdraw()
        {
            $withdraw_price = isset($_REQUEST['withdraw_price']) ? civi_clean(wp_unslash($_REQUEST['withdraw_price'])) : '10';
            $withdraw_payment = isset($_REQUEST['withdraw_payment']) ? civi_clean(wp_unslash($_REQUEST['withdraw_payment'])) : '1';

            global $current_user;
            $user_id = $current_user->ID;
            $user_name = $current_user->display_name;
            $author_payout_paypal = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_paypal', true);
            $author_payout_stripe = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_stripe', true);
            $author_payout_card_number = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_card_number', true);
            $author_payout_card_name = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_card_name', true);
            $author_payout_bank_transfer_name = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_bank_transfer_name', true);
            $enable_paypal = civi_get_option('enable_payout_paypal');
            $enable_stripe = civi_get_option('enable_payout_stripe');
            $enable_bank = civi_get_option('enable_payout_bank_transfer');
            $custom_payout = civi_get_option('custom_payout_setting');

            $total_price = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'service_withdraw_total_price', true);
            if(empty($total_price)){
                $total_price = 0;
            }

            if($withdraw_price == ''){
                echo json_encode(array('success' => false , 'message' => esc_html__('Please enter the amount of money', 'civi-framework')));
                wp_die();
            }

            if($withdraw_price > $total_price){
                echo json_encode(array('success' => false , 'message' => esc_html__('The amount to withdraw is larger than the available amount', 'civi-framework')));
                wp_die();
            }

            if($withdraw_payment == 'paypal' && empty($author_payout_paypal) && $enable_paypal === '1'){
                echo json_encode(array('success' => false , 'message' => esc_html__('Please enter full payout information paypal', 'civi-framework')));
                wp_die();
            }

            if($withdraw_payment == 'stripe' && empty($author_payout_stripe && $enable_stripe === '1')){
                echo json_encode(array('success' => false , 'message' => esc_html__('Please enter full payout information stripe', 'civi-framework')));
                wp_die();
            }

            if($withdraw_payment == 'wire_transfer' && $enable_bank === '1' && (empty($author_payout_card_number) || empty($author_payout_card_name) || empty($author_payout_bank_transfer_name))){
                echo json_encode(array('success' => false , 'message' => esc_html__('Please enter full payout information wire transfer', 'civi-framework')));
                wp_die();
            }

            if(!empty($custom_payout)) :
                foreach ($custom_payout as $field) :
                    if(!empty($field['name'])) :
                    $author_payout = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_custom_' . $field['id'], true);
                    $field['name_id'] = str_replace(' ', '-', $field['name']);
                    if($withdraw_payment == $field['name_id'] && empty($author_payout)){
                        echo json_encode(array('success' => false , 'message' => sprintf(__('Please enter full payout information %s', 'civi-framework'), $field['name'])));
                        wp_die();
                    }
                    endif;
                endforeach;
            endif;

            $withdraw_payment = str_replace(['-','_'], ' ', $withdraw_payment);
            $new_post = array(
                'post_type' => 'service_withdraw',
                'post_status' => 'publish',
            );
            $post_title = $user_name;
            if (isset($post_title)) {
                $new_post['post_title'] = $post_title;
                $post_id = wp_insert_post($new_post, true);
                update_user_meta($user_id, CIVI_METABOX_PREFIX . 'user_total_price_withdraw', $total_price);
                update_post_meta($post_id, CIVI_METABOX_PREFIX . 'service_withdraw_status', 'pending');
                update_post_meta($post_id, CIVI_METABOX_PREFIX . 'service_withdraw_user_id', $user_id);
                update_post_meta($post_id, CIVI_METABOX_PREFIX . 'service_withdraw_payment_method', $withdraw_payment);
                update_post_meta($post_id, CIVI_METABOX_PREFIX . 'service_withdraw_price', $withdraw_price);
                update_post_meta($post_id, CIVI_METABOX_PREFIX . 'service_withdraw_total_price', $total_price);
            }

            echo json_encode(array('success' => true));

            wp_die();
        }


        /**
         * Applicants Filter
         */
        public function civi_filter_applicants_dashboard()
        {
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $applicants_search = isset($_REQUEST['applicants_search']) ? civi_clean(wp_unslash($_REQUEST['applicants_search'])) : '';
            $sort_by = isset($_REQUEST['applicants_sort_by']) ? civi_clean(wp_unslash($_REQUEST['applicants_sort_by'])) : '';
            $item_id = isset($_REQUEST['item_id']) ? civi_clean(wp_unslash($_REQUEST['item_id'])) : '';
            $jobs_id = isset($_REQUEST['applicants_jobs_id']) ? civi_clean(wp_unslash($_REQUEST['applicants_jobs_id'])) : '';
            $filter_jobs = isset($_REQUEST['applicants_filter_jobs']) ? civi_clean(wp_unslash($_REQUEST['applicants_filter_jobs'])) : '';
            $action_click = isset($_REQUEST['action_click']) ? civi_clean(wp_unslash($_REQUEST['action_click'])) : '';
            $page = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '';

            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;

            $meta_query = array();
            $tax_query = array();

            if (!empty($item_id)) {
                if ($action_click == 'approved') {
                    update_post_meta($item_id, CIVI_METABOX_PREFIX . 'applicants_status', 'approved');
                }

                if ($action_click == 'rejected') {
                    update_post_meta($item_id, CIVI_METABOX_PREFIX . 'applicants_status', 'rejected');
                }
            }

            if (!empty($jobs_id)) {
                $jobs_employer_id = $jobs_id;
                $args_applicants = array(
                    'post_type' => 'applicants',
                    'ignore_sticky_posts' => 1,
                    'paged' => $paged,
                );
                $meta_query[] = array(
                    'key' => CIVI_METABOX_PREFIX . 'applicants_jobs_id',
                    'value' => $jobs_id,
                    'compare' => '='
                );
                if (!empty($applicants_search)) {
                    $meta_query[] = array(
                        'key' => CIVI_METABOX_PREFIX . 'applicants_author',
                        'value' => $applicants_search,
                        'compare' => '='
                    );
                }
            } else {
                $args_jobs = array(
                    'post_type' => 'jobs',
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => 1,
                    'posts_per_page' => -1,
                    'author' => $user_id,
                    'orderby' => 'date',
                );
                $data_jobs = new WP_Query($args_jobs);
                $jobs_employer_id = array();
                if ($data_jobs->have_posts()) {
                    while ($data_jobs->have_posts()) : $data_jobs->the_post();
                        $jobs_employer_id[] = get_the_ID();
                    endwhile;
                }

                $args_applicants = array(
                    'post_type' => 'applicants',
                    'ignore_sticky_posts' => 1,
                    'paged' => $paged,
                );

                $meta_query[] = array(
                    'key' => CIVI_METABOX_PREFIX . 'applicants_jobs_id',
                    'value' => $jobs_employer_id,
                    'compare' => 'IN'
                );

                if (!empty($applicants_search)) {
                    $args_applicants['s'] = $applicants_search;
                }

                if (!empty($filter_jobs)) {
                    $args_applicants['title'] = $filter_jobs;
                }
            }


            if (!empty($item_amount)) {
                $args_applicants['posts_per_page'] = $item_amount;
            }

            //meta query applicants sort_by
            if (!empty($sort_by)) {
                if ($sort_by == 'newest') {
                    $args_applicants['order'] = 'DESC';
                }
                if ($sort_by == 'oldest') {
                    $args_applicants['order'] = 'ASC';
                }
            }

            $args_applicants['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $tax_count = count($tax_query);
            if ($tax_count > 0) {
                $args_applicants['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            $data = new WP_Query($args_applicants);
            $total_post = $data->found_posts;
            $max_num_pages = $data->max_num_pages;
            $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                'total' => $max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'type' => 'array',
                'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
            )));

            ob_start();

            if ($total_post > 0 && !empty($jobs_employer_id)) {

                while ($data->have_posts()) : $data->the_post(); ?>
                    <?php
                    $id = get_the_ID();
                    global $current_user;
                    wp_get_current_user();
                    $user_id = $current_user->ID;
                    $public_date = get_the_date(get_option('date_format'));
                    $jobs_id = get_post_meta($id, CIVI_METABOX_PREFIX . 'applicants_jobs_id', true);
                    $applicants_email = get_post_meta($id, CIVI_METABOX_PREFIX . 'applicants_email', true);
                    $applicants_phone = get_post_meta($id, CIVI_METABOX_PREFIX . 'applicants_phone', true);
                    $applicants_message = get_post_meta($id, CIVI_METABOX_PREFIX . 'applicants_message', true);
                    $applicants_cv = get_post_meta($id, CIVI_METABOX_PREFIX . 'applicants_cv', true);
                    $applicants_status = get_post_meta($id, CIVI_METABOX_PREFIX . 'applicants_status', true);
                    $author_id = get_post_field('post_author', $id);
                    $candidate_id = '';
                    if(!empty($author_id)){
                        $args_candidate = array(
                            'post_type' => 'candidate',
                            'posts_per_page' => 1,
                            'author' => $author_id,
                        );
                        $current_user_posts = get_posts($args_candidate);
                        $candidate_id = !empty($current_user_posts) ? $current_user_posts[0]->ID : '';
                        $candidate_avatar = get_the_author_meta('author_avatar_image_url', $author_id);
                    }
                    $read_mess = get_post_meta($id, CIVI_METABOX_PREFIX . 'read_mess', true);
                    $reply_mess = get_post_meta($id, CIVI_METABOX_PREFIX . 'reply_mess', true);
                    ?>
                    <tr>
                        <td class="info-user">
                            <?php if (!empty($candidate_avatar)) : ?>
                                <div class="image-applicants"><img class="image-candidates" src="<?php echo esc_url($candidate_avatar) ?>" alt="" /></div>
                            <?php else : ?>
                                <div class="image-applicants"><i class="far fa-camera"></i></div>
                            <?php endif; ?>
                            <div class="info-details">
                                <?php if (!empty(get_the_author())) { ?>
                                    <h3><a href="<?php echo get_post_permalink($candidate_id); ?>"><?php echo get_the_author(); ?></a></h3>
                                <?php } else { ?>
                                    <h3><?php esc_html_e('User not logged in','civi-framework'); ?></h3>
                                <?php } ?>
                                <?php if (!empty(get_the_title())) { ?>
                                    <div class="applied"><?php esc_html_e('Applied:', 'civi-framework') ?>
                                        <a href="<?php echo esc_url(get_permalink($jobs_id)); ?>" target="_blank">
                                            <span> <?php esc_html_e(get_the_title()); ?></span>
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </td>
                        <td class="status">
                            <div class="approved">
                                <?php echo civi_applicants_status($id); ?>
                                <span class="applied-time"><?php esc_html_e('Applied:', 'civi-framework') ?><?php esc_html_e($public_date) ?></span>
                            </div>
                        </td>
                        <td class="info">
                            <?php if (!empty($applicants_email)) { ?>
                                <span class="gmail"><?php esc_html_e($applicants_email) ?></span>
                            <?php } ?>
                            <?php if (!empty($applicants_phone)) { ?>
                                <span class="phone"><?php esc_html_e($applicants_phone) ?></span>
                            <?php } ?>
                        </td>
                        <td class="applicants-control action-setting">
                            <div class="list-action">
                                <?php if (!empty(get_the_author())) { ?>
                                    <a href="#" class="action icon-video tooltip btn-reschedule-meetings" data-id="<?php echo esc_attr($id); ?>" data-title="<?php esc_attr_e('Meetings', 'civi-framework') ?>"><i class="fas fa-video-plus"></i></a>
                                    <?php if($reply_mess !== 'yes') : ?>
                                        <a href="#" class="action icon-messages tooltip" id="btn-mees-applicants"
                                           data-apply="<?php esc_html_e(get_the_title()); ?>"
                                           data-id="<?php echo esc_attr($id); ?>"
                                           data-mess="<?php echo $applicants_message; ?>"
                                           data-jobs-id="<?php echo $jobs_id; ?>"
                                           data-title="<?php esc_attr_e('Messages', 'civi-framework') ?>">
                                            <i class="fab fa-facebook-messenger <?php if($read_mess === 'yes'){ echo 'active'; } ?>"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php } ?>
                                <a href="<?php echo esc_url($applicants_cv); ?>" class="action icon-download tooltip" data-title="<?php esc_attr_e('Download CV', 'civi-framework') ?>"><i class="fas fa-download"></i></a>
                                <div class="action">
                                    <a href="#" class="icon-setting"><i class="fal fa-ellipsis-h"></i></a>
                                    <ul class="action-dropdown">
                                        <?php if (empty($applicants_status)) { ?>
                                            <li><a class="btn-approved" applicants-id="<?php echo esc_attr($id); ?>" href="#"><?php esc_html_e('Approved', 'civi-framework') ?></a></li>
                                            <li><a class="btn-rejected" applicants-id="<?php echo esc_attr($id); ?>" href="#"><?php esc_html_e('Rejected', 'civi-framework') ?></a></li>
                                        <?php } else {
                                            if ($applicants_status == 'approved') { ?>
                                                <li><a class="btn-rejected" applicants-id="<?php echo esc_attr($id); ?>" href="#"><?php esc_html_e('Rejected', 'civi-framework') ?></a>
                                                </li>
                                            <?php } else { ?>
                                                <li><a class="btn-approved" applicants-id="<?php echo esc_attr($id); ?>" href="#"><?php esc_html_e('Approved', 'civi-framework') ?></a>
                                                </li>
                                            <?php }
                                        } ?>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endwhile;
            }
            wp_reset_postdata();

            $applicants_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'pagination' => $pagination, 'applicants_html' => $applicants_html, 'total_post' => $total_post, 'page' => $page));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post));
            }
            wp_die();
        }


        /**
         * Read mess
         */
        public function civi_read_mess_ajax_load()
        {
            $item_id = isset($_REQUEST['item_id']) ? civi_clean(wp_unslash($_REQUEST['item_id'])) : '';

            update_post_meta($item_id, CIVI_METABOX_PREFIX . 'read_mess', 'yes');

            wp_die();
        }

        /**
         * Realy mess
         */
        public function civi_realy_mess_ajax_load()
        {
            $item_id = isset($_REQUEST['item_id']) ? civi_clean(wp_unslash($_REQUEST['item_id'])) : '';
            $title = isset($_REQUEST['title']) ? civi_clean(wp_unslash($_REQUEST['title'])) : '';
            $content = isset($_REQUEST['content']) ? civi_clean(wp_unslash($_REQUEST['content'])) : '';
            $jobs_id = isset($_REQUEST['jobs_id']) ? civi_clean(wp_unslash($_REQUEST['jobs_id'])) : '';

            $new_messages = array(
                'post_type' => 'messages',
                'post_status' => 'publish',
            );

            if (isset($title)) {
                $new_messages['post_title'] = $title;
            }

            if (isset($content)) {
                $new_messages['post_excerpt'] = $content;
            }

            if (!empty($new_messages['post_title'])) {
                $messages_id = wp_insert_post($new_messages, true);
            }

            $reply_message = get_post_field('post_author', $jobs_id);
            $creator_message = get_post_field('post_author', $item_id);

            civi_get_data_ajax_notification($item_id,'add-message');

            if (isset($messages_id)) {
                update_post_meta($messages_id, CIVI_METABOX_PREFIX . 'creator_message', $creator_message);
                update_post_meta($messages_id, CIVI_METABOX_PREFIX . 'recipient_message', $jobs_id);
                update_post_meta($messages_id, CIVI_METABOX_PREFIX . 'reply_message', $reply_message);
                update_post_meta($item_id, CIVI_METABOX_PREFIX . 'reply_mess', 'yes');
            }

            echo json_encode(array('success' => true));

            wp_die();
        }


        /**
         * My Wishlist
         */
        public function civi_filter_my_wishlist()
        {
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $jobs_search = isset($_REQUEST['jobs_search']) ? civi_clean(wp_unslash($_REQUEST['jobs_search'])) : '';
            $sort_by = isset($_REQUEST['jobs_sort_by']) ? civi_clean(wp_unslash($_REQUEST['jobs_sort_by'])) : '';
            $item_id = isset($_REQUEST['item_id']) ? civi_clean(wp_unslash($_REQUEST['item_id'])) : '';
            $action_click = isset($_REQUEST['action_click']) ? civi_clean(wp_unslash($_REQUEST['action_click'])) : '';
            $page = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '';

            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;

            $meta_query = array();
            $tax_query = array();

            $my_wishlist = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'my_wishlist', true);
            if (!empty($item_id)) {
                if ($action_click == 'delete') {
                    $key = array_search($item_id, $my_wishlist);
                    if ($key !== false) {
                        unset($my_wishlist[$key]);
                    }
                }
            }
            update_user_meta($user_id, CIVI_METABOX_PREFIX . 'my_wishlist', $my_wishlist);

            $args = array(
                'post_type' => 'jobs',
                'paged' => $paged,
                'post__in' => $my_wishlist,
                'ignore_sticky_posts' => 1,
            );

            if(!empty($my_wishlist)){
                $args['post__in'] = $my_wishlist;
            } else {
                $args['post__in'] = array(0);
            }

            if (!empty($jobs_search)) {
                $args['s'] = $jobs_search;
            }

            if (!empty($item_amount)) {
                $args['posts_per_page'] = $item_amount;
            }

            //meta query jobs sort_by
            if (!empty($sort_by)) {
                if ($sort_by == 'newest') {
                    $args['order'] = 'DESC';
                }
                if ($sort_by == 'oldest') {
                    $args['order'] = 'ASC';
                }
            }

            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $tax_count = count($tax_query);
            if ($tax_count > 0) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            $data = new WP_Query($args);
            $total_post = $data->found_posts;
            $max_num_pages = $data->max_num_pages;
            $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                'total' => $max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'type' => 'array',
                'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
            )));

            ob_start();

            if ($total_post > 0) {

                while ($data->have_posts()) : $data->the_post(); ?>
                    <?php
                    $id = get_the_ID();
                    global $current_user;
                    wp_get_current_user();
                    $user_id = $current_user->ID;
                    $jobs_type = get_the_terms($id, 'jobs-type');
                    $jobs_categories = get_the_terms($id, 'jobs-categories');
                    $jobs_location = get_the_terms($id, 'jobs-location');
                    $jobs_select_company = get_post_meta($id, CIVI_METABOX_PREFIX . 'jobs_select_company');
                    $company_id = isset($jobs_select_company[0]) ? $jobs_select_company[0] : '';
                    $company_logo = get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_logo');
                    $public_date = get_the_date('Y-m-d');
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
                                <div class="info-jobs">
                                    <h3 class="title-jobs-dashboard">
                                        <a href="<?php echo get_the_permalink($id); ?>">
                                            <?php echo get_the_title($id); ?>
                                        </a>
                                    </h3>
                                    <p>
                                        <?php if (is_array($jobs_categories)) {
                                            foreach ($jobs_categories as $categories) { ?>
                                                <?php esc_html_e($categories->name); ?>
                                        <?php }
                                        } ?>
                                        <?php if (is_array($jobs_type)) {
                                            foreach ($jobs_type as $type) { ?>
                                                <?php esc_html_e('/ ' . $type->name); ?>
                                        <?php }
                                        } ?>
                                        <?php if (is_array($jobs_location)) {
                                            foreach ($jobs_location as $location) { ?>
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
                        <td class="action-setting jobs-control">
                            <a href="#" class="icon-setting"><i class="fal fa-ellipsis-h"></i></a>
                            <ul class="action-dropdown">
                                <li><a class="btn-delete" jobs-id="<?php echo esc_attr($id); ?>" href="#"><?php esc_html_e('Delete', 'civi-framework') ?></a></li>
                            </ul>
                        </td>
                    </tr>
                <?php endwhile;
            }
            wp_reset_postdata();

            $jobs_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'pagination' => $pagination, 'jobs_html' => $jobs_html, 'total_post' => $total_post, 'page' => $page));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post));
            }
            wp_die();
        }

        /**
         * Employer Wishlist
         */
        public function civi_filter_employer_wishlist()
        {
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $service_search = isset($_REQUEST['service_search']) ? civi_clean(wp_unslash($_REQUEST['service_search'])) : '';
            $sort_by = isset($_REQUEST['service_sort_by']) ? civi_clean(wp_unslash($_REQUEST['service_sort_by'])) : '';
            $item_id = isset($_REQUEST['item_id']) ? civi_clean(wp_unslash($_REQUEST['item_id'])) : '';
            $action_click = isset($_REQUEST['action_click']) ? civi_clean(wp_unslash($_REQUEST['action_click'])) : '';
            $page = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '';

            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $user_demo = get_the_author_meta(CIVI_METABOX_PREFIX . 'user_demo', $user_id);
            $meta_query = array();
            $tax_query = array();

            $service_wishlist = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'service_wishlist', true);
            if (!empty($item_id)) {
                if ($action_click == 'delete') {
                    $key = array_search($item_id, $service_wishlist);
                    if ($key !== false) {
                        unset($service_wishlist[$key]);
                    }
                }
            }
            update_user_meta($user_id, CIVI_METABOX_PREFIX . 'service_wishlist', $service_wishlist);

            $args = array(
                'post_type' => 'service',
                'paged' => $paged,
                'ignore_sticky_posts' => 1,
            );

            if(!empty($service_wishlist)){
                $args['post__in'] = $service_wishlist;
            } else {
                $args['post__in'] = array(0);
            }

            if (!empty($service_search)) {
                $args['s'] = $service_search;
            }

            if (!empty($item_amount)) {
                $args['posts_per_page'] = $item_amount;
            }

            //meta query service sort_by
            if (!empty($sort_by)) {
                if ($sort_by == 'newest') {
                    $args['order'] = 'DESC';
                }
                if ($sort_by == 'oldest') {
                    $args['order'] = 'ASC';
                }
            }

            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $tax_count = count($tax_query);
            if ($tax_count > 0) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            $data = new WP_Query($args);
            $total_post = $data->found_posts;
            $max_num_pages = $data->max_num_pages;
            $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                'total' => $max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'type' => 'array',
                'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
            )));

            ob_start();

            if ($total_post > 0) {

                while ($data->have_posts()) : $data->the_post(); ?>
                    <?php
                    $service_id = get_the_ID();
                    $service_skills = get_the_terms($service_id, 'service-skills');
                    $service_categories =  get_the_terms($service_id, 'service-categories');
                    $service_location =  get_the_terms($service_id, 'service-location');
                    $public_date = get_the_date(get_option('date_format'));
                    $thumbnail = get_the_post_thumbnail_url($service_id, '70x70');
                    $author_id = get_post_field( 'post_author', $service_id );
                    $author_name = get_the_author_meta( 'display_name', $author_id );
                    $args_candidate = array(
                        'post_type' => 'candidate',
                        'posts_per_page' => 1,
                        'author' => $author_id,
                    );
                    $current_user_posts = get_posts($args_candidate);
                    $candidate_id = !empty($current_user_posts) ? $current_user_posts[0]->ID : '';
                    $service_featured  = intval(get_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_featured', true));

                    $currency_sign_default = civi_get_option('currency_sign_default');
                    $number_start_price = get_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_price', true);
                    $currency_position = civi_get_option('currency_position');
                    if(!empty($number_start_price)){
                        if ($currency_position == 'before') {
                            $start_price = $currency_sign_default . $number_start_price;
                        } else {
                            $start_price = $number_start_price . $currency_sign_default;
                        }
                    } else {
                        $start_price = '';
                    }
                    ?>
                    <tr>
                        <td>
                            <div class="service-header">
                                <?php if(!empty($thumbnail)) :?>
                                    <img class="thumbnail" src="<?php echo $thumbnail;?>" alt="" />
                                <?php endif; ?>
                                <div class="content">
                                    <h3 class="title-my-service">
                                        <a href="<?php echo get_the_permalink($service_id)?>">
                                            <?php echo get_the_title($service_id); ?>
                                            <?php if($service_featured == 1) : ?>
                                                <span class="tooltip featured" data-title="<?php esc_attr_e('Featured', 'civi-framework') ?>">
                                                    <img src="<?php echo esc_attr(CIVI_PLUGIN_URL . 'assets/images/icon-featured.svg'); ?>"
                                                         alt="<?php echo esc_attr('featured', 'civi-framework') ?>">
                                                    </span>
                                            <?php endif; ?>
                                        </a>
                                    </h3>
                                    <p>
                                        <?php if (is_array($service_categories)) {
                                            foreach ($service_categories as $categories) { ?>
                                                <span class="cate"><?php esc_html_e($categories->name); ?></span>
                                            <?php }
                                        } ?>
                                        <?php if (is_array($service_skills)) {
                                            foreach ($service_skills as $skills) { ?>
                                                <?php esc_html_e('/ ' . $skills->name); ?>
                                            <?php }
                                        } ?>
                                        <?php if (is_array($service_location)) {
                                            foreach ($service_location as $location) { ?>
                                                <?php esc_html_e('/ ' . $location->name); ?>
                                            <?php }
                                        } ?>
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="author">
                            <a href="<?php echo get_post_permalink($candidate_id) ?>"><?php echo $author_name;?></a>
                        </td>
                        <td class="price">
                            <?php echo $start_price;?>
                        </td>
                        <td class="start-time">
                            <?php echo $public_date; ?>
                        </td>
                        <td class="action-setting service-control">
                            <a href="#" class="icon-setting"><i class="fal fa-ellipsis-h"></i></a>
                            <ul class="action-dropdown">
                                <?php if ($user_demo == 'yes') : ?>
                                    <li><a class="btn-add-to-message" data-text="<?php echo esc_attr('This is a "Demo" account so you not cant delete it', 'civi-framework'); ?>" href="#"><?php esc_html_e('Delete', 'civi-framework') ?></a></li>
                                <?php else : ?>
                                    <li><a class="btn-delete" service-id="<?php echo esc_attr($service_id); ?>" href="#"><?php esc_html_e('Delete', 'civi-framework') ?></a></li>
                                <?php endif; ?>
                            </ul>
                        </td>
                    </tr>
                <?php endwhile;
            }
            wp_reset_postdata();

            $service_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'pagination' => $pagination, 'service_html' => $service_html, 'total_post' => $total_post, 'page' => $page));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post));
            }
            wp_die();
        }

        /**
         * My Follow
         */
        public function civi_filter_my_follow()
        {
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $company_search = isset($_REQUEST['company_search']) ? civi_clean(wp_unslash($_REQUEST['company_search'])) : '';
            $sort_by = isset($_REQUEST['company_sort_by']) ? civi_clean(wp_unslash($_REQUEST['company_sort_by'])) : '';
            $item_id = isset($_REQUEST['item_id']) ? civi_clean(wp_unslash($_REQUEST['item_id'])) : '';
            $action_click = isset($_REQUEST['action_click']) ? civi_clean(wp_unslash($_REQUEST['action_click'])) : '';
            $page = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '';

            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;

            $meta_query = array();
            $tax_query = array();

            $my_follow = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'my_follow', true);
            if (!empty($item_id)) {
                if ($action_click == 'delete') {
                    $key = array_search($item_id, $my_follow);
                    if ($key !== false) {
                        unset($my_follow[$key]);
                    }
                }
            }
            update_user_meta($user_id, CIVI_METABOX_PREFIX . 'my_follow', $my_follow);

            $args = array(
                'post_type' => 'company',
                'paged' => $paged,
                'post__in' => $my_follow,
                'ignore_sticky_posts' => 1,
            );

            if (!empty($company_search)) {
                $args['s'] = $company_search;
            }

            if (!empty($item_amount)) {
                $args['posts_per_page'] = $item_amount;
            }

            //meta query company sort_by
            if (!empty($sort_by)) {
                if ($sort_by == 'newest') {
                    $args['order'] = 'DESC';
                }
                if ($sort_by == 'oldest') {
                    $args['order'] = 'ASC';
                }
            }

            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $tax_count = count($tax_query);
            if ($tax_count > 0) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            $data = new WP_Query($args);
            $total_post = $data->found_posts;
            $max_num_pages = $data->max_num_pages;
            $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                'total' => $max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'type' => 'array',
                'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
            )));

            ob_start();

            if ($total_post > 0) {

                while ($data->have_posts()) : $data->the_post();
                    $id = get_the_ID();
                    global $current_user;
                    wp_get_current_user();
                    $user_id = $current_user->ID;
                    $company_categories = get_the_terms($id, 'company-categories');
                    $company_location = get_the_terms($id, 'company-location');
                    $company_logo = get_post_meta($id, CIVI_METABOX_PREFIX . 'company_logo');
                    $public_date = get_the_date('Y-m-d');
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
                                <li><a class="btn-delete" company-id="<?php echo esc_attr($id); ?>" href="#"><?php esc_html_e('Delete', 'civi-framework') ?></a></li>
                            </ul>
                        </td>
                    </tr>
                <?php endwhile;
            }
            wp_reset_postdata();

            $company_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'pagination' => $pagination, 'company_html' => $company_html, 'total_post' => $total_post, 'page' => $page));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post));
            }
            wp_die();
        }


        /**
         * My Invite
         */
        public function civi_filter_my_invite()
        {
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $jobs_search = isset($_REQUEST['jobs_search']) ? civi_clean(wp_unslash($_REQUEST['jobs_search'])) : '';
            $sort_by = isset($_REQUEST['jobs_sort_by']) ? civi_clean(wp_unslash($_REQUEST['jobs_sort_by'])) : '';
            $item_id = isset($_REQUEST['item_id']) ? civi_clean(wp_unslash($_REQUEST['item_id'])) : '';
            $action_click = isset($_REQUEST['action_click']) ? civi_clean(wp_unslash($_REQUEST['action_click'])) : '';
            $page = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '';

            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;

            $meta_query = array();
            $tax_query = array();

            $my_invite = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'my_invite', true);
            if (!empty($item_id)) {
                if ($action_click == 'delete') {
                    $key = array_search($item_id, $my_invite);
                    if ($key !== false) {
                        unset($my_invite[$key]);
                    }
                }
            }
            update_user_meta($user_id, CIVI_METABOX_PREFIX . 'my_invite', $my_invite);

            $args = array(
                'post_type' => 'jobs',
                'paged' => $paged,
                'post__in' => $my_invite,
                'ignore_sticky_posts' => 1,
            );

            if (!empty($jobs_search)) {
                $args['s'] = $jobs_search;
            }

            if (!empty($item_amount)) {
                $args['posts_per_page'] = $item_amount;
            }

            //meta query jobs sort_by
            if (!empty($sort_by)) {
                if ($sort_by == 'newest') {
                    $args['order'] = 'DESC';
                }
                if ($sort_by == 'oldest') {
                    $args['order'] = 'ASC';
                }
            }

            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $tax_count = count($tax_query);
            if ($tax_count > 0) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            $data = new WP_Query($args);
            $total_post = $data->found_posts;
            $max_num_pages = $data->max_num_pages;
            $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                'total' => $max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'type' => 'array',
                'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
            )));

            ob_start();

            if ($total_post > 0) {

                while ($data->have_posts()) : $data->the_post(); ?>
                    <?php
                    $id = get_the_ID();
                    global $current_user;
                    wp_get_current_user();
                    $user_id = $current_user->ID;
                    $jobs_type = get_the_terms($id, 'jobs-type');
                    $jobs_categories = get_the_terms($id, 'jobs-categories');
                    $jobs_location = get_the_terms($id, 'jobs-location');
                    $company_logo = get_post_meta($id, CIVI_METABOX_PREFIX . 'company_logo');
                    $public_date = get_the_date('Y-m-d');
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
                                <div class="info-jobs">
                                    <h3 class="title-jobs-dashboard">
                                        <a href="<?php echo get_the_permalink($id); ?>">
                                            <?php echo get_the_title($id); ?>
                                        </a>
                                    </h3>
                                    <p>
                                        <?php if (is_array($jobs_categories)) {
                                            foreach ($jobs_categories as $categories) { ?>
                                                <?php esc_html_e($categories->name); ?>
                                        <?php }
                                        } ?>
                                        <?php if (is_array($jobs_type)) {
                                            foreach ($jobs_type as $type) { ?>
                                                <?php esc_html_e('/ ' . $type->name); ?>
                                        <?php }
                                        } ?>
                                        <?php if (is_array($jobs_location)) {
                                            foreach ($jobs_location as $location) { ?>
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
                        <td class="action-setting jobs-control">
                            <a href="#" class="icon-setting"><i class="fal fa-ellipsis-h"></i></a>
                            <ul class="action-dropdown">
                                <li><a class="btn-apply" href="<?php echo get_the_permalink($id); ?>" target="_blank"><?php esc_html_e('Apply Now', 'civi-framework') ?></a></li>
                                <li><a class="btn-delete" jobs-id="<?php echo esc_attr($id); ?>" href="#"><?php esc_html_e('Delete', 'civi-framework') ?></a></li>
                            </ul>
                        </td>
                    </tr>
                <?php endwhile;
            }
            wp_reset_postdata();

            $jobs_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'pagination' => $pagination, 'jobs_html' => $jobs_html, 'total_post' => $total_post, 'page' => $page));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post));
            }
            wp_die();
        }

        /**
         * My Review
         */
        public function civi_filter_my_review()
        {
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $company_search = isset($_REQUEST['company_search']) ? civi_clean(wp_unslash($_REQUEST['company_search'])) : '';
            $sort_by = isset($_REQUEST['company_sort_by']) ? civi_clean(wp_unslash($_REQUEST['company_sort_by'])) : '';
            $item_id = isset($_REQUEST['item_id']) ? civi_clean(wp_unslash($_REQUEST['item_id'])) : '';
            $action_click = isset($_REQUEST['action_click']) ? civi_clean(wp_unslash($_REQUEST['action_click'])) : '';
            $page = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '';

            global $current_user, $wpdb;
            wp_get_current_user();
            $user_id = $current_user->ID;

            if (!empty($item_id) && $action_click == 'delete') {
                wp_delete_comment($item_id, $force_delete = true);
            }

            $my_reviews = $wpdb->get_results("SELECT * FROM $wpdb->comments as comment INNER JOIN $wpdb->commentmeta AS meta WHERE comment.user_id = $user_id AND meta.meta_key = 'company_rating' AND meta.comment_id = comment.comment_ID ORDER BY comment.comment_ID DESC LIMIT 999");
            $company_ids = array();
            foreach ($my_reviews as $my_review) {
                $company_ids[] = $my_review->comment_post_ID;
            }
            $args = array(
                'post_type' => 'company',
                'paged' => $paged,
                'post__in' => $company_ids,
                'ignore_sticky_posts' => 1,
            );

            if (!empty($company_search)) {
                $args['s'] = $company_search;
            }

            if (!empty($item_amount)) {
                $args['posts_per_page'] = $item_amount;
            }

            //meta query company sort_by
            if (!empty($sort_by)) {
                if ($sort_by == 'newest') {
                    $args['order'] = 'DESC';
                }
                if ($sort_by == 'oldest') {
                    $args['order'] = 'ASC';
                }
            }

            $data = new WP_Query($args);
            $total_post = $data->found_posts;
            $max_num_pages = $data->max_num_pages;
            $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                'total' => $max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'type' => 'array',
                'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
            )));

            ob_start();

            if ($total_post > 0 && !empty($company_ids)) {

                while ($data->have_posts()) : $data->the_post();
                    $company_id = get_the_ID();
                    $comment = get_comments(array(
                        'post_id' => $company_id,
                    ));
                    $comment_id = '';
                    if (!empty($comment)) {
                        $comment_id = $comment[0]->comment_ID;
                    }
                    $company_categories = get_the_terms($company_id, 'company-categories');
                    $company_location = get_the_terms($company_id, 'company-location');
                    $company_logo = get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_logo');
                    $rating = get_comment_meta($comment_id, 'company_rating', true);
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
                                        <a href="<?php echo get_the_permalink($company_id); ?>">
                                            <?php echo get_the_title($company_id) ?>
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
                        <td>
                            <span class="rating-count">
                                <i class="fas fa-star"></i>
                                <span><?php esc_html_e($rating); ?></span>
                            </span>
                        </td>
                        <td>
                            <?php echo get_comment_date('Y-m-d', $comment_id); ?>
                        </td>
                        <td class="action-setting company-control">
                            <a href="#" class="icon-setting"><i class="fal fa-ellipsis-h"></i></a>
                            <ul class="action-dropdown">
                                <li><a class="btn-edit" href="<?php echo get_the_permalink($company_id) . '/#company-review-details' ?>"><?php esc_html_e('Edit', 'civi-framework') ?></a>
                                </li>
                                <li><a class="btn-delete" comment-id="<?php echo esc_attr($comment_id); ?>" href="#"><?php esc_html_e('Delete', 'civi-framework') ?></a></li>
                            </ul>
                        </td>
                    </tr>
                <?php endwhile;
            }
            wp_reset_postdata();

            $company_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'pagination' => $pagination, 'company_html' => $company_html, 'total_post' => $total_post, 'page' => $page));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post));
            }
            wp_die();
        }

        /**
         * Filter Company Dashboard
         */
        public function civi_filter_company_dashboard()
        {
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $item_id = isset($_REQUEST['item_id']) ? civi_clean(wp_unslash($_REQUEST['item_id'])) : '';
            $action_click = isset($_REQUEST['action_click']) ? civi_clean(wp_unslash($_REQUEST['action_click'])) : '';
            $page = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '';

            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $civi_profile = new Civi_Profile();

            $meta_query = array();
            if (!empty($item_id)) {
                $company = get_post($item_id);
                if ($action_click == 'delete') {
                    wp_delete_post($item_id, true);
                }
            }

            $args = array(
                'post_type' => 'company',
                'paged' => $paged,
                'post_status' => array('publish', 'pending'),
                'ignore_sticky_posts' => 1,
                'author' => $user_id,
                'orderby' => 'date',
            );

            if (!empty($item_amount)) {
                $args['posts_per_page'] = $item_amount;
            }

            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $data = new WP_Query($args);
            $total_post = $data->found_posts;
            $max_num_pages = $data->max_num_pages;
            $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                'total' => $max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'type' => 'array',
                //'add_args'  => array_map( 'urlencode', $args ),
                'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
            )));

            ob_start();

            if ($total_post > 0) {

                while ($data->have_posts()) : $data->the_post();
                    $id = get_the_ID();
                    $company_location = get_the_terms($id, 'company-location');
                    $status = get_post_status($id);
                    $company_categories = get_the_terms($id, 'company-categories');
                    $company_logo = get_post_meta($id, CIVI_METABOX_PREFIX . 'company_logo');
                    $meta_company = civi_posts_company($id);
                    $company_dashboard_link = civi_get_permalink('company_dashboard');
                ?>
                    <tr>
                        <td class="info-user">
                            <?php
                            if (!empty($company_logo[0]['url'])) { ?>
                                <a href="<?php echo get_the_permalink($id); ?>">
                                    <img src="<?php echo $company_logo[0]['url'] ?>" alt="<?php echo get_the_title() ?>">
                                </a>
                            <?php } else { ?>
                                <div class="img-company"><i class="far fa-camera"></i></div>
                            <?php } ?>
                            <div class="info-details">
                                <h3><?php echo get_the_title() ?></h3>
                                <p>
                                    <?php if (is_array($company_location)) : ?>
                                        <?php foreach ($company_location as $location) { ?>
                                            <span><?php echo $location->name; ?></span>
                                        <?php } ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </td>
                        <td>
                            <?php if ($status == 'publish') : ?>
                                <span class="label label-open"><?php esc_html_e('Opening', 'civi-framework') ?></span>
                            <?php endif; ?>
                            <?php if ($status == 'pending') : ?>
                                <span class="label label-pending"><?php esc_html_e('Pending', 'civi-framework') ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="cate">
                                <?php if (is_array($company_categories)) : ?>
                                    <?php foreach ($company_categories as $categories) { ?>
                                        <span><?php echo $categories->name; ?></span>
                                    <?php } ?>
                                <?php endif; ?>
                            </span>
                        </td>
                        <td>
                            <span class="active-jobs"><?php echo $meta_company->post_count ?></span>
                        </td>
                        <td class="action-setting company-control">
                            <a href="#" class="icon-setting"><i class="fal fa-ellipsis-h"></i></a>
                            <ul class="action-dropdown">
                                <li><a class="btn-edit" href="<?php echo esc_url($company_dashboard_link); ?>?company_id=<?php echo esc_attr($id); ?>"><?php esc_html_e('Edit', 'civi-framework'); ?></a>
                                </li>
                                <li><a class="btn-delete" company-id="<?php echo esc_attr($id); ?>" href="#"><?php esc_html_e('Delete', 'civi-framework') ?></a></li>
                            </ul>
                        </td>
                    </tr>
                <?php
                endwhile;
            }
            wp_reset_postdata();

            $company_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'pagination' => $pagination, 'company_html' => $company_html, 'total_post' => $total_post, 'page' => $page));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post));
            }
            wp_die();
        }


        /**
         * Company Related
         */
        public function civi_company_related()
        {
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '4';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $company_id = isset($_REQUEST['company_id']) ? civi_clean(wp_unslash($_REQUEST['company_id'])) : '';
            $page = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '';

            $args = array(
                'post_type' => 'jobs',
                'post_status' => 'publish',
                'paged' => $paged,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => CIVI_METABOX_PREFIX . 'jobs_select_company',
                        'value' => $company_id,
                        'compare' => '=='
                    )
                ),

            );

            if (!empty($item_amount)) {
                $args['posts_per_page'] = $item_amount;
            }

            $related = get_posts($args);
            $wp_query = new WP_Query($args);
            $total_post = $wp_query->found_posts;
            $max_num_pages = $wp_query->max_num_pages;
            $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                'total' => $max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'type' => 'array',
                //'add_args'  => array_map( 'urlencode', $args ),
                'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
            )));

            ob_start();

            if ($total_post > 0) {

                foreach ($related as $relateds) { ?>
                    <?php civi_get_template('content-jobs.php', array(
                        'jobs_id' => $relateds->ID,
                        'jobs_layout' => 'layout-list',
                    )); ?>
                <?php }
            }
            wp_reset_postdata();

            $company_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'pagination' => $pagination, 'company_html' => $company_html, 'total_post' => $total_post, 'page' => $page));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post));
            }
            wp_die();
        }

        /**
         * Filter Candidates Dashboard
         */
        public function civi_filter_candidates_dashboard()
        {
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $item_id = isset($_REQUEST['item_id']) ? civi_clean(wp_unslash($_REQUEST['item_id'])) : '';
            $candidates_search = isset($_REQUEST['candidates_search']) ? civi_clean(wp_unslash($_REQUEST['candidates_search'])) : '';
            $sort_by = isset($_REQUEST['candidates_sort_by']) ? civi_clean(wp_unslash($_REQUEST['candidates_sort_by'])) : '';
            $candidates_id = isset($_REQUEST['candidates_id']) ? civi_clean(wp_unslash($_REQUEST['candidates_id'])) : '';
            $follow_company = isset($_REQUEST['follow_company']) ? civi_clean(wp_unslash($_REQUEST['follow_company'])) : '';
            $action_click = isset($_REQUEST['action_click']) ? civi_clean(wp_unslash($_REQUEST['action_click'])) : '';
            $author_id = isset($_REQUEST['author_id']) ? civi_clean(wp_unslash($_REQUEST['author_id'])) : '';
            $page = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '';

            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;

            $meta_query = array();
            $candidates_id = explode(',', $candidates_id);

            $my_follow = get_user_meta($author_id, CIVI_METABOX_PREFIX . 'my_follow', true);
            if ($action_click == 'delete') {
                if (!empty($follow_company)) {
                    foreach ($my_follow as $key => $value) {
                        if (in_array($value, $my_follow)) {
                            unset($my_follow[$key]);
                        }
                    }
                }
                if (!empty($item_id && !empty($candidates_id))) {
                    $key = array_search($item_id, $candidates_id);
                    if ($key !== false) {
                        unset($candidates_id[$key]);
                    }
                }
            }
            update_user_meta($author_id, CIVI_METABOX_PREFIX . 'my_follow', $my_follow);

            $args = array(
                'post_type' => 'candidate',
                'paged' => $paged,
                'post__in' => $candidates_id,
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
            );

            if (!empty($candidates_search)) {
                $args['s'] = $candidates_search;
            }

            if (!empty($item_amount)) {
                $args['posts_per_page'] = $item_amount;
            }

            //meta query candidates sort_by
            if (!empty($sort_by)) {
                if ($sort_by == 'newest') {
                    $args['order'] = 'DESC';
                }
                if ($sort_by == 'oldest') {
                    $args['order'] = 'ASC';
                }
            }

            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $data = new WP_Query($args);
            $total_post = $data->found_posts;
            $max_num_pages = $data->max_num_pages;
            $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                'total' => $max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'type' => 'array',
                'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
            )));

            ob_start();

            if ($total_post > 0 && !empty($candidates_id)) {
                $list_id_candidates = array();
                while ($data->have_posts()) : $data->the_post(); ?>
                    <?php
                    global $post;
                    $author_id = $post->post_author;
                    $id = get_the_ID();
                    $list_id_candidates[] = $id;
                    $candidate_current_position = get_post_meta($id, CIVI_METABOX_PREFIX . 'candidate_current_position', true);
                    $candidate_locations = get_the_terms($id, 'candidate_locations');
                    $candidate_email = get_post_meta($id, CIVI_METABOX_PREFIX . 'candidate_email', true);
                    $candidate_avatar = get_the_author_meta('author_avatar_image_url', $author_id);
                    $user_follow_athour = '';
                    if (!empty($user_follow_company[$author_id])) {
                        $user_follow_athour = implode(',', $user_follow_company[$author_id]);
                    }
                    ?>
                    <tr>
                        <td class="info-user">
                            <?php if (!empty($candidate_avatar)) : ?>
                                <img class="image-candidates" src="<?php echo esc_attr($candidate_avatar) ?>" alt="" />
                            <?php else : ?>
                                <div class="image-candidates"><i class="far fa-camera"></i></div>
                            <?php endif; ?>
                            <div class="info-details">
                                <h3>
                                    <a href="<?php echo esc_url(get_the_permalink($id)); ?>"><?php esc_html_e(get_the_title($id)); ?></a>
                                    <i class="far fa-check"></i>
                                </h3>
                                <div class="cate-info">
                                    <?php if (!empty($candidate_current_position)) { ?>
                                        <div class="candidate-current-position">
                                            <?php esc_html_e($candidate_current_position . ' /'); ?>
                                        </div>
                                    <?php } ?>
                                    <?php civi_get_salary_candidate($id, '-'); ?>
                                    <?php if (is_array($candidate_locations)) {
                                        foreach ($candidate_locations as $location) { ?>
                                            <?php esc_html_e('/ ' . $location->name); ?>
                                    <?php }
                                    } ?>
                                </div>
                            </div>
                        </td>
                        <td class="action-setting">
                            <div class="list-action">
                                <a href="<?php echo esc_url(get_the_permalink($id)); ?>" target="_blank" class="action icon-view tooltip" data-title="<?php echo esc_attr('View', 'civi-framework') ?>"><i class="far fa-eye"></i></i></a>
                                <a href="mailto: <?php esc_html_e($candidate_email); ?>" class="action icon-gmail tooltip" data-title="<?php echo esc_attr('Send Email', 'civi-framework') ?>"><i class="far fa-envelope-open-text"></i></a>

                                <a href="#" class="action btn-delete tooltip" athour-id="<?php echo esc_attr($author_id) ?>" follow_company="<?php echo $user_follow_athour; ?>" items-id="<?php echo esc_attr($id); ?>" data-title="<?php echo esc_attr('Delete', 'civi-framework') ?>"><i class="far fa-trash-alt"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile;
            }
            wp_reset_postdata();

            $candidates_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'pagination' => $pagination, 'candidates_html' => $candidates_html, 'total_post' => $total_post, 'page' => $page));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post));
            }
            wp_die();
        }

        /**
         * Filter Follow Candidate
         */
        public function civi_filter_follow_candidate()
        {
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $candidate_search = isset($_REQUEST['candidate_search']) ? civi_clean(wp_unslash($_REQUEST['candidate_search'])) : '';
            $sort_by = isset($_REQUEST['candidate_sort_by']) ? civi_clean(wp_unslash($_REQUEST['candidate_sort_by'])) : '';
            $item_id = isset($_REQUEST['item_id']) ? civi_clean(wp_unslash($_REQUEST['item_id'])) : '';
            $action_click = isset($_REQUEST['action_click']) ? civi_clean(wp_unslash($_REQUEST['action_click'])) : '';
            $page = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '';

            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;

            $meta_query = array();
            $tax_query = array();

            $follow_candidate = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'follow_candidate', true);
            if (!empty($item_id)) {
                if ($action_click == 'delete') {
                    $key = array_search($item_id, $follow_candidate);
                    if ($key !== false) {
                        unset($follow_candidate[$key]);
                    }
                }
            }
            update_user_meta($user_id, CIVI_METABOX_PREFIX . 'follow_candidate', $follow_candidate);

            $args = array(
                'post_type' => 'candidate',
                'paged' => $paged,
                'post__in' => $follow_candidate,
                'ignore_sticky_posts' => 1,
            );

            if (!empty($candidate_search)) {
                $args['s'] = $candidate_search;
            }

            if (!empty($item_amount)) {
                $args['posts_per_page'] = $item_amount;
            }

            //meta query candidate sort_by
            if (!empty($sort_by)) {
                if ($sort_by == 'newest') {
                    $args['order'] = 'DESC';
                }
                if ($sort_by == 'oldest') {
                    $args['order'] = 'ASC';
                }
            }

            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $tax_count = count($tax_query);
            if ($tax_count > 0) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            $data = new WP_Query($args);
            if (!empty($follow_candidate)) {
                $total_post = $data->found_posts;
            } else {
                $total_post = 0;
            }
            $max_num_pages = $data->max_num_pages;
            $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                'total' => $max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'type' => 'array',
                'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
            )));

            ob_start();

            if ($total_post > 0) {

                while ($data->have_posts()) : $data->the_post(); ?>
                    <?php
                    global $post;
                    $author_id = $post->post_author;
                    $id = get_the_ID();
                    $candidate_current_position = get_post_meta($id, CIVI_METABOX_PREFIX . 'candidate_current_position', true);
                    $candidate_locations = get_the_terms($id, 'candidate_locations');
                    $candidate_email = get_post_meta($id, CIVI_METABOX_PREFIX . 'candidate_email', true);
                    $candidate_avatar = get_the_author_meta('author_avatar_image_url', $author_id);
                    $candidate_featured = get_post_meta($id, CIVI_METABOX_PREFIX . 'candidate_featured', true);
                    $user_follow_athour = '';
                    if (!empty($user_follow_company[$author_id])) {
                        $user_follow_athour = implode(',', $user_follow_company[$author_id]);
                    }
                    ?>
                    <tr>
                        <td class="info-user">
                            <?php if (!empty($candidate_avatar)) : ?>
                                <img class="image-candidates" src="<?php echo esc_attr($candidate_avatar) ?>" alt="" />
                            <?php else : ?>
                                <div class="image-candidates"><i class="far fa-camera"></i></div>
                            <?php endif; ?>
                            <div class="info-details">
                                <h3>
                                    <a href="<?php echo esc_url(get_the_permalink($id)); ?>"><?php esc_html_e(get_the_title($id)); ?></a>
                                    <?php if ($candidate_featured == 1) : ?>
                                        <span class="tooltip" data-title="<?php echo esc_attr('Featured', 'civi-framework') ?>"><i class="fas fa-check"></i></span>
                                    <?php endif; ?>
                                </h3>
                                <div class="cate-info">
                                    <?php if (!empty($candidate_current_position)) { ?>
                                        <div class="candidate-current-position">
                                            <?php esc_html_e($candidate_current_position . ' /'); ?>
                                        </div>
                                    <?php } ?>
                                    <?php civi_get_salary_candidate($id, '-'); ?>
                                    <?php if (is_array($candidate_locations)) {
                                        foreach ($candidate_locations as $location) { ?>
                                            <?php esc_html_e('/ ' . $location->name); ?>
                                    <?php }
                                    } ?>
                                </div>
                            </div>
                        </td>
                        <td class="action-setting">
                            <div class="list-action">
                                <a href="<?php echo esc_url(get_the_permalink($id)); ?>" target="_blank" class="action icon-view tooltip" data-title="<?php echo esc_attr('View', 'civi-framework') ?>"><i class="far fa-eye"></i></i></a>
                                <a href="mailto: <?php esc_html_e($candidate_email); ?>" class="action icon-gmail tooltip" data-title="<?php echo esc_attr('Send Email', 'civi-framework') ?>"><i class="far fa-envelope-open-text"></i></a>
                                <a href="#" class="action btn-delete tooltip" athour-id="<?php echo esc_attr($author_id) ?>" follow_company="<?php echo $user_follow_athour; ?>" items-id="<?php echo esc_attr($id); ?>" data-title="<?php echo esc_attr('Delete', 'civi-framework') ?>"><i class="far fa-trash-alt"></i></a>

                            </div>
                        </td>
                    </tr>
                <?php endwhile;
            }
            wp_reset_postdata();

            $candidate_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'pagination' => $pagination, 'candidate_html' => $candidate_html, 'total_post' => $total_post, 'page' => $page));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post));
            }
            wp_die();
        }

        /**
         * Filter Invite Candidate
         */
        public function civi_filter_invite_candidate()
        {
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $candidate_search = isset($_REQUEST['candidate_search']) ? civi_clean(wp_unslash($_REQUEST['candidate_search'])) : '';
            $sort_by = isset($_REQUEST['candidate_sort_by']) ? civi_clean(wp_unslash($_REQUEST['candidate_sort_by'])) : '';
            $item_id = isset($_REQUEST['item_id']) ? civi_clean(wp_unslash($_REQUEST['item_id'])) : '';
            $action_click = isset($_REQUEST['action_click']) ? civi_clean(wp_unslash($_REQUEST['action_click'])) : '';
            $page = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '';
            $list_jobs = isset($_REQUEST['list_jobs']) ? civi_clean(wp_unslash($_REQUEST['list_jobs'])) : '';

            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $author_id = get_post_field('post_author', $item_id);

            $meta_query = array();
            $tax_query = array();

            $invite_candidate = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'invite_candidate', true);
            $my_invite = get_user_meta($author_id, CIVI_METABOX_PREFIX . 'my_invite', true);

            if (!empty($item_id)) {
                if ($action_click == 'delete') {
                    $key = array_search($item_id, $invite_candidate);
                    if ($key !== false) {
                        unset($invite_candidate[$key]);
                    }
                }
            }

            if (!empty($list_jobs)) {
                foreach (json_decode($list_jobs) as $list_job) {
                    $key_my_invite = array_search($list_job, $my_invite);
                    if ($key_my_invite !== false) {
                        unset($my_invite[$key_my_invite]);
                    }
                }
            }

            update_user_meta($author_id, CIVI_METABOX_PREFIX . 'my_invite', $my_invite);
            update_user_meta($user_id, CIVI_METABOX_PREFIX . 'invite_candidate', $invite_candidate);


            $args = array(
                'post_type' => 'candidate',
                'paged' => $paged,
                'post__in' => $invite_candidate,
                'ignore_sticky_posts' => 1,
            );

            if (!empty($candidate_search)) {
                $args['s'] = $candidate_search;
            }

            if (!empty($item_amount)) {
                $args['posts_per_page'] = $item_amount;
            }

            //meta query candidate sort_by
            if (!empty($sort_by)) {
                if ($sort_by == 'newest') {
                    $args['order'] = 'DESC';
                }
                if ($sort_by == 'oldest') {
                    $args['order'] = 'ASC';
                }
            }

            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $tax_count = count($tax_query);
            if ($tax_count > 0) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            $data = new WP_Query($args);
            if (!empty($invite_candidate)) {
                $total_post = $data->found_posts;
            } else {
                $total_post = 0;
            }
            $max_num_pages = $data->max_num_pages;
            $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                'total' => $max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'type' => 'array',
                'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
            )));

            ob_start();

            if ($total_post > 0) {

                while ($data->have_posts()) : $data->the_post(); ?>
                    <?php
                    global $post;
                    $author_id = $post->post_author;
                    $id = get_the_ID();
                    $candidate_current_position = get_post_meta($id, CIVI_METABOX_PREFIX . 'candidate_current_position', true);
                    $candidate_locations = get_the_terms($id, 'candidate_locations');
                    $candidate_email = get_post_meta($id, CIVI_METABOX_PREFIX . 'candidate_email', true);
                    $candidate_avatar = get_the_author_meta('author_avatar_image_url', $author_id);
                    $candidate_featured = get_post_meta($id, CIVI_METABOX_PREFIX . 'candidate_featured', true);
                    $user_invite_athour = '';
                    if (!empty($user_invite_company[$author_id])) {
                        $user_invite_athour = implode(',', $user_invite_company[$author_id]);
                    }
                    ?>
                    <tr>
                        <td class="info-user">
                            <?php if (!empty($candidate_avatar)) : ?>
                                <img class="image-candidates" src="<?php echo esc_attr($candidate_avatar) ?>" alt="" />
                            <?php else : ?>
                                <div class="image-candidates"><i class="far fa-camera"></i></div>
                            <?php endif; ?>
                            <div class="info-details">
                                <h3>
                                    <a href="<?php echo esc_url(get_the_permalink($id)); ?>"><?php esc_html_e(get_the_title($id)); ?></a>
                                    <?php if ($candidate_featured == 1) : ?>
                                        <span class="tooltip" data-title="<?php echo esc_attr('Featured', 'civi-framework') ?>"><i class="fas fa-check"></i></span>
                                    <?php endif; ?>
                                </h3>
                                <div class="cate-info">
                                    <?php if (!empty($candidate_current_position)) { ?>
                                        <div class="candidate-current-position">
                                            <?php esc_html_e($candidate_current_position . ' /'); ?>
                                        </div>
                                    <?php } ?>
                                    <?php civi_get_salary_candidate($id, '-'); ?>
                                    <?php if (is_array($candidate_locations)) {
                                        foreach ($candidate_locations as $location) { ?>
                                            <?php esc_html_e('/ ' . $location->name); ?>
                                    <?php }
                                    } ?>
                                </div>
                            </div>
                        </td>
                        <td class="action-setting">
                            <div class="list-action">
                                <a href="<?php echo esc_url(get_the_permalink($id)); ?>" target="_blank" class="action icon-view tooltip" data-title="<?php echo esc_attr('View', 'civi-framework') ?>"><i class="far fa-eye"></i></i></a>
                                <a href="mailto: <?php esc_html_e($candidate_email); ?>" class="action icon-gmail tooltip" data-title="<?php echo esc_attr('Send Email', 'civi-framework') ?>"><i class="far fa-envelope-open-text"></i></a>
                                <a href="#" class="action btn-delete tooltip" athour-id="<?php echo esc_attr($author_id) ?>" invite_company="<?php echo $user_invite_athour; ?>" items-id="<?php echo esc_attr($id); ?>" data-title="<?php echo esc_attr('Delete', 'civi-framework') ?>"><i class="far fa-trash-alt"></i></a>

                            </div>
                        </td>
                    </tr>
                <?php endwhile;
            }
            wp_reset_postdata();

            $candidate_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'pagination' => $pagination, 'candidate_html' => $candidate_html, 'total_post' => $total_post, 'page' => $page));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post));
            }
            wp_die();
        }

        /**
         * My Apply
         */
        public function civi_filter_my_apply()
        {
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $jobs_search = isset($_REQUEST['jobs_search']) ? civi_clean(wp_unslash($_REQUEST['jobs_search'])) : '';
            $sort_by = isset($_REQUEST['jobs_sort_by']) ? civi_clean(wp_unslash($_REQUEST['jobs_sort_by'])) : '';
            $item_id = isset($_REQUEST['item_id']) ? civi_clean(wp_unslash($_REQUEST['item_id'])) : '';
            $action_click = isset($_REQUEST['action_click']) ? civi_clean(wp_unslash($_REQUEST['action_click'])) : '';
            $page = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '';

            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $jobs_id = get_post_meta($item_id, CIVI_METABOX_PREFIX . 'applicants_jobs_id');
            if (!empty($jobs_id)) {
                $jobs_id = intval($jobs_id[0]);
            }

            $meta_query = array();
            $tax_query = array();

            $my_apply = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'my_apply', true);
            if ($action_click == 'delete') {
                if (!empty($jobs_id)) {
                    $key = array_search($jobs_id, $my_apply);
                    if ($key !== false) {
                        unset($my_apply[$key]);
                    }
                }
                if (!empty($item_id)) {
                    wp_delete_post($item_id, true);
                }
            }
            update_user_meta($user_id, CIVI_METABOX_PREFIX . 'my_apply', $my_apply);

            $args = array(
                'post_type' => 'applicants',
                'ignore_sticky_posts' => 1,
                'paged' => $paged,
                'post_status' => 'publish',
                'author' => $user_id,
            );

            if (!empty($jobs_search)) {
                $args['s'] = $jobs_search;
            }

            if (!empty($item_amount)) {
                $args['posts_per_page'] = $item_amount;
            }

            //meta query jobs sort_by
            if (!empty($sort_by)) {
                if ($sort_by == 'newest') {
                    $args['order'] = 'DESC';
                }
                if ($sort_by == 'oldest') {
                    $args['order'] = 'ASC';
                }
            }

            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $tax_count = count($tax_query);
            if ($tax_count > 0) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            $data = new WP_Query($args);
            $total_post = $data->found_posts;
            $max_num_pages = $data->max_num_pages;
            $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                'total' => $max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'type' => 'array',
                'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
            )));

            ob_start();

            if ($total_post > 0) {

                while ($data->have_posts()) : $data->the_post();
                    $applicants_id = get_the_ID();
                    $jobs_id = get_post_meta($applicants_id, CIVI_METABOX_PREFIX . 'applicants_jobs_id');
                    if (!empty($jobs_id)) {
                        $jobs_id = intval($jobs_id[0]);
                    }
                    global $current_user;
                    wp_get_current_user();
                    $user_id = $current_user->ID;
                    $jobs_type = wp_get_post_terms($jobs_id, 'jobs-type');
                    $jobs_categories = wp_get_post_terms($jobs_id, 'jobs-categories');
                    $jobs_location = wp_get_post_terms($jobs_id, 'jobs-location');
                    $jobs_select_company = get_post_meta($jobs_id, CIVI_METABOX_PREFIX . 'jobs_select_company');
                    $company_id = isset($jobs_select_company[0]) ? $jobs_select_company[0] : '';
                    $company_logo = get_post_meta($company_id, CIVI_METABOX_PREFIX . 'company_logo');
                    $public_date = get_the_date('Y-m-d');
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
                                <div class="info-jobs">
                                    <h3 class="title-jobs-dashboard">
                                        <a href="<?php echo get_permalink($jobs_id); ?>">
                                            <?php echo get_the_title($applicants_id); ?>
                                        </a>
                                    </h3>
                                    <p>
                                        <?php if (is_array($jobs_categories)) {
                                            foreach ($jobs_categories as $categories) { ?>
                                                <?php esc_html_e($categories->name); ?>
                                        <?php }
                                        } ?>
                                        <?php if (is_array($jobs_type)) {
                                            foreach ($jobs_type as $type) { ?>
                                                <?php esc_html_e('/ ' . $type->name); ?>
                                        <?php }
                                        } ?>
                                        <?php if (is_array($jobs_location)) {
                                            foreach ($jobs_location as $location) { ?>
                                                <?php esc_html_e('/ ' . $location->name); ?>
                                        <?php }
                                        } ?>
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="status">
                            <?php echo civi_applicants_status($applicants_id) ?>
                        </td>
                        <td class="table-time">
                            <span class="start-time"><?php esc_html_e($public_date); ?></span>
                        </td>
                        <?php
                        ?>
                        <td class="action-setting jobs-control">
                            <a href="#" class="icon-setting"><i class="fal fa-ellipsis-h"></i></a>
                            <ul class="action-dropdown">
                                <li><a class="btn-delete" jobs-id="<?php echo esc_attr($applicants_id); ?>" href="#"><?php esc_html_e('Delete', 'civi-framework') ?></a></li>
                            </ul>
                        </td>
                    </tr>
                <?php endwhile;
            }
            wp_reset_postdata();

            $jobs_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'pagination' => $pagination, 'jobs_html' => $jobs_html, 'total_post' => $total_post, 'page' => $page));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post));
            }
            wp_die();
        }


        /**
         * Service Filter
         */
        public function civi_filter_my_service()
        {
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '10';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $service_search = isset($_REQUEST['service_search']) ? civi_clean(wp_unslash($_REQUEST['service_search'])) : '';
            $service_status = isset($_REQUEST['service_status']) ? civi_clean(wp_unslash($_REQUEST['service_status'])) : '';
            $sort_by = isset($_REQUEST['service_sort_by']) ? civi_clean(wp_unslash($_REQUEST['service_sort_by'])) : '';
            $item_id = isset($_REQUEST['item_id']) ? civi_clean(wp_unslash($_REQUEST['item_id'])) : '';
            $action_click = isset($_REQUEST['action_click']) ? civi_clean(wp_unslash($_REQUEST['action_click'])) : '';
            $page = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '';

            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $meta_query = array();
            $tax_query = array();

            if (!empty($item_id)) {
                $service = get_post($item_id);
                if ($action_click == 'show') {
                    if ($service->post_status == 'pause') {
                        $data = array(
                            'ID' => $item_id,
                            'post_type' => 'service',
                            'post_status' => 'publish'
                        );
                    }
                    wp_update_post($data);
                }

                if ($action_click == 'pause') {
                    $data = array(
                        'ID' => $item_id,
                        'post_type' => 'service',
                        'post_status' => 'pause'
                    );
                    wp_update_post($data);
                }

                if ($action_click == 'featured') {
                    update_post_meta($item_id, CIVI_METABOX_PREFIX . 'service_featured', 1);
                    $number_featured = get_the_author_meta(CIVI_METABOX_PREFIX . 'candidate_package_number_service_featured', $user_id);
                    update_user_meta($user_id, CIVI_METABOX_PREFIX . 'candidate_package_number_service_featured', $number_featured - 1);
                }
            }

            $args = array(
                'post_type' => 'service',
                'paged' => $paged,
                'post_status' => array('publish', 'pending', 'pause'),
                'ignore_sticky_posts' => 1,
                'author' => $user_id,
                'orderby' => 'date',
            );

            if (!empty($service_search)) {
                $args['s'] = $service_search;
            }

            if (!empty($service_status)) {
                $args['post_status'] = $service_status;
            }

            if (!empty($item_amount)) {
                $args['posts_per_page'] = $item_amount;
            }

            //meta query service sort_by
            if (!empty($sort_by)) {
                if ($sort_by == 'newest') {
                    $args['order'] = 'DESC';
                }
                if ($sort_by == 'oldest') {
                    $args['order'] = 'ASC';
                }
            }

            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );

            $tax_count = count($tax_query);
            if ($tax_count > 0) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            $data = new WP_Query($args);
            $total_post = $data->found_posts;
            $max_num_pages = $data->max_num_pages;
            $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                'total' => $max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'type' => 'array',
                //'add_args'  => array_map( 'urlencode', $args ),
                'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
            )));

            ob_start();

            if ($total_post > 0) {
                while ($data->have_posts()) : $data->the_post(); ?>
                    <?php
                    $service_id = get_the_ID();
                    $status = get_post_status($service_id);
                    $service_skills = get_the_terms($service_id, 'service-skills');
                    $service_categories =  get_the_terms($service_id, 'service-categories');
                    $service_location =  get_the_terms($service_id, 'service-location');
                    $public_date = get_the_date(get_option('date_format'));
                    $thumbnail = get_the_post_thumbnail_url($service_id, '70x70');
                    $service_featured  = intval(get_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_featured', true));

                    $currency_sign_default = civi_get_option('currency_sign_default');
                    $number_start_price = get_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_price', true);
                    $currency_position = civi_get_option('currency_position');
                    if(!empty($number_start_price)){
                        if ($currency_position == 'before') {
                            $start_price = $currency_sign_default . $number_start_price;
                        } else {
                            $start_price = $number_start_price . $currency_sign_default;
                        }
                    } else {
                        $start_price = '';
                    }
                    ?>
                    <tr>
                        <td>
                            <div class="service-header">
                                <?php if(!empty($thumbnail)) :?>
                                    <img class="thumbnail" src="<?php echo $thumbnail;?>" alt="" />
                                <?php endif; ?>
                                <div class="content">
                                    <h3 class="title-my-service">
                                        <a href="<?php echo get_the_permalink($service_id)?>">
                                            <?php echo get_the_title($service_id); ?>
                                            <?php if($service_featured == 1) : ?>
                                                <span class="tooltip featured" data-title="<?php esc_attr_e('Featured', 'civi-framework') ?>">
                                                    <img src="<?php echo esc_attr(CIVI_PLUGIN_URL . 'assets/images/icon-featured.svg'); ?>"
                                                         alt="<?php echo esc_attr('featured', 'civi-framework') ?>">
                                                    </span>
                                            <?php endif; ?>
                                        </a>
                                    </h3>
                                    <p>
                                        <?php if (is_array($service_categories)) {
                                            foreach ($service_categories as $categories) { ?>
                                                <span class="cate"><?php esc_html_e($categories->name); ?></span>
                                            <?php }
                                        } ?>
                                        <?php if (is_array($service_skills)) {
                                            foreach ($service_skills as $skills) { ?>
                                                <?php esc_html_e('/ ' . $skills->name); ?>
                                            <?php }
                                        } ?>
                                        <?php if (is_array($service_location)) {
                                            foreach ($service_location as $location) { ?>
                                                <?php esc_html_e('/ ' . $location->name); ?>
                                            <?php }
                                        } ?>
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="price">
                                <?php echo $start_price;?>
                            </div>
                        </td>
                        <td>
                            <?php if ($status == 'publish') : ?>
                                <span class="label label-open"><?php esc_html_e('Opening', 'civi-framework') ?></span>
                            <?php endif; ?>
                            <?php if ($status == 'pending') : ?>
                                <span class="label label-pending"><?php esc_html_e('Pending', 'civi-framework') ?></span>
                            <?php endif; ?>
                            <?php if ($status == 'pause') : ?>
                                <span class="label label-pause"><?php esc_html_e('Pause', 'civi-framework') ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="start-time"><?php echo $public_date; ?></span>
                        </td>
                        <?php
                        ?>
                        <td class="action-setting service-control">
                            <a href="#" class="icon-setting"><i class="fal fa-ellipsis-h"></i></a>
                            <ul class="action-dropdown">
                                <?php
                                $service_dashboard_link = civi_get_permalink('service_dashboard');
                                $candidate_package_number_service_featured = get_the_author_meta(CIVI_METABOX_PREFIX . 'candidate_package_number_service_featured', $user_id);
                                $user_demo = get_the_author_meta(CIVI_METABOX_PREFIX . 'user_demo', $user_id);
                                switch ($status) {
                                    case 'publish':
                                        ?>
                                        <li><a class="btn-edit"
                                               href="<?php echo esc_url($service_dashboard_link); ?>?service_id=<?php echo esc_attr($service_id); ?>"><?php esc_html_e('Edit', 'civi-framework'); ?></a>
                                        </li>
                                        <?php if ($user_demo == 'yes') { ?>
                                        <li><a class="btn-add-to-message" href="#"
                                               data-text="<?php echo esc_attr('This is a "Demo" account, so you can not change it', 'civi-framework'); ?>"><?php esc_html_e('Paused', 'civi-framework'); ?></a>
                                        </li>
                                    <?php } else { ?>
                                        <?php  if ($check_candidate_package !== -1 && $check_candidate_package !== 0) { ?>
                                            <li><a class="btn-pause" service-id="<?php echo esc_attr($service_id); ?>"
                                                   href="<?php echo get_the_permalink($service_id); ?>"><?php esc_html_e('Paused', 'civi-framework') ?></a>
                                            </li>
                                        <?php }
                                        if ($candidate_package_number_service_featured > 0 && $service_featured !== 1 && $check_candidate_package !== -1  && $check_candidate_package !== 0) { ?>
                                            <li><a class="btn-featured" service-id="<?php echo esc_attr($service_id); ?>" href="<?php echo get_the_permalink($service_id); ?>"><?php esc_html_e('Featured', 'civi-framework') ?></a></li>
                                        <?php } ?>
                                    <?php } ?>
                                        <?php
                                        break;
                                    case 'pending': ?>
                                        <li><a class="btn-edit"
                                               href="<?php echo esc_url($service_dashboard_link); ?>?service_id=<?php echo esc_attr($service_id); ?>"><?php esc_html_e('Edit', 'civi-framework'); ?></a>
                                        </li>
                                        <?php
                                        break;
                                    case 'pause':
                                        ?>
                                        <li><a class="btn-edit"
                                               href="<?php echo esc_url($service_dashboard_link); ?>?service_id=<?php echo esc_attr($service_id); ?>"><?php esc_html_e('Edit', 'civi-framework'); ?></a>
                                        </li>
                                        <?php if ($check_candidate_package !== -1 && $check_candidate_package !== 0) { ?>
                                        <li><a class="btn-show" service-id="<?php echo esc_attr($service_id); ?>"
                                               href="<?php echo get_the_permalink($service_id); ?>"><?php esc_html_e('Continue', 'civi-framework'); ?>
                                            </a>
                                        </li>
                                    <?php }
                                        break;
                                }
                                ?>
                            </ul>
                        </td>
                    </tr>
                <?php endwhile;
            }
            wp_reset_postdata();

            $service_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'pagination' => $pagination, 'service_html' => $service_html, 'total_post' => $total_post, 'page' => $page));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post));
            }
            wp_die();
        }

        /**
         * Update profile
         */
        public function civi_update_profile_ajax()
        {
            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            check_ajax_referer('civi_update_profile_ajax_nonce', 'civi_security_update_profile');

            $user_firstname = $user_lastname = $user_email = $author_mobile_number = '';

            // Update first name
            if (!empty($_POST['user_firstname'])) {
                $user_firstname = sanitize_text_field(wp_unslash($_POST['user_firstname']));
                update_user_meta($user_id, 'first_name', $user_firstname);
            } else {
                delete_user_meta($user_id, 'first_name');
            }

            // Update last name
            if (!empty($_POST['user_lastname'])) {
                $user_lastname = sanitize_text_field(wp_unslash($_POST['user_lastname']));
                update_user_meta($user_id, 'last_name', $user_lastname);
            } else {
                delete_user_meta($user_id, 'last_name');
            }

            // Update display name
            if (!empty($_POST['user_firstname']) && !empty($_POST['user_lastname']))  {
                $type_name_candidate = civi_get_option('type_name_candidate');
                if($type_name_candidate === 'fl-name') {
                    $full_name = sanitize_text_field(wp_unslash($_POST['user_firstname'])) . ' ' . sanitize_text_field(wp_unslash($_POST['user_lastname']));
                    $userdata = array(
                        'ID' => $user_id,
                        'display_name' => $full_name,
                    );
                    wp_update_user($userdata);
                }
            }

            // Update Phone
            if (!empty($_POST['author_mobile_number'])) {
                $author_mobile_number = sanitize_text_field(wp_unslash($_POST['author_mobile_number']));
                if (0 < strlen(trim(preg_replace('/[\s\#0-9_\-\+\/\(\)\.]/', '', $author_mobile_number)))) {
                    echo json_encode(array('success' => false, 'message' => esc_html__('The phone number you entered is not valid. Please try again.', 'civi-framework')));
                    wp_die();
                }
                update_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_mobile_number', $author_mobile_number);
            } else {
                delete_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_mobile_number');
            }

            // Update Profile Avatar
            if (isset($_POST['user_image_url']) && isset($_POST['user_image_id'])) {
                $user_image_url = sanitize_text_field($_POST['user_image_url']);
                $user_image_id = sanitize_text_field($_POST['user_image_id']);
                update_user_meta($user_id, 'author_avatar_image_url', $user_image_url);
                update_user_meta($user_id, 'author_avatar_image_id', $user_image_id);
            } else {
                delete_user_meta($user_id, 'author_avatar_image_url');
                delete_user_meta($user_id, 'author_avatar_image_id');
            }

            // Update email
            if (!empty($_POST['user_email'])) {
                $user_email = sanitize_email(wp_unslash($_POST['user_email']));
                $user_email = is_email($user_email);
                if (!$user_email) {
                    echo json_encode(array('success' => false, 'message' => esc_html__('The Email you entered is not valid. Please try again.', 'civi-framework')));
                    wp_die();
                } else {
                    $email_exists = email_exists($user_email);
                    if ($email_exists) {
                        if ($email_exists != $user_id) {
                            echo json_encode(array('success' => false, 'message' => esc_html__('This Email is already used by another user. Please try a different one.', 'civi-framework')));
                            wp_die();
                        }
                    } else {
                        $return = wp_update_user(array('ID' => $user_id, 'user_email' => $user_email));
                        if (is_wp_error($return)) {
                            $error = $return->get_error_message();
                            esc_html_e($error);
                            wp_die();
                        }
                    }
                }
            }

            echo json_encode(array('success' => true, 'message' => esc_html__('Profile updated', 'civi-framework')));
            wp_die();
        }

        /**
         * Change password
         */
        public function civi_change_password_ajax()
        {
            check_ajax_referer('civi_change_password_ajax_nonce', 'civi_security_change_password');
            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $allowed_html = array();

            $oldpass = isset($_POST['oldpass']) ? civi_clean(wp_unslash($_POST['oldpass'])) : '';
            $newpass = isset($_POST['newpass']) ? civi_clean(wp_unslash($_POST['newpass'])) : '';
            $confirmpass = isset($_POST['confirmpass']) ? civi_clean(wp_slash($_POST['confirmpass'])) : '';


            if ($newpass == '' || $confirmpass == '') {
                echo json_encode(array('success' => false, 'message' => esc_html__('New password or confirm password is blank', 'civi-framework')));
                wp_die();
            }

            if ($newpass !== $confirmpass) {
                echo json_encode(array('success' => false, 'message' => esc_html__('Passwords do not match', 'civi-framework')));
                wp_die();
            }

            if (strlen($newpass) < 6 || strlen($confirmpass) < 6) {
                echo json_encode(array('success' => false, 'message' => esc_html__('Please set a password with a length of more than 6 characters', 'civi-framework')));
                wp_die();
            }

            $user = get_user_by('id', $user_id);
            if ($user && wp_check_password($oldpass, $user->data->user_pass, $user_id)) {
                wp_set_password($newpass, $user_id);
                echo json_encode(array('success' => true, 'message' => esc_html__('Password Updated', 'civi-framework')));
            } else {
                echo json_encode(array('success' => false, 'message' => esc_html__('Old password is not correct', 'civi-framework')));
            }
            wp_die();
        }


        /**
         * Update payout
         */
        public function civi_update_payout_ajax()
        {
            $payout_paypal = isset($_REQUEST['payout_paypal']) ? civi_clean(wp_unslash($_REQUEST['payout_paypal'])) : '';
            $payout_stripe = isset($_REQUEST['payout_stripe']) ? civi_clean(wp_unslash($_REQUEST['payout_stripe'])) : '';
            $payout_card_number = isset($_REQUEST['payout_card_number']) ? civi_clean(wp_unslash($_REQUEST['payout_card_number'])) : '';
            $payout_card_name = isset($_REQUEST['payout_card_name']) ? civi_clean(wp_unslash($_REQUEST['payout_card_name'])) : '';
            $payout_bank_transfer_name = isset($_REQUEST['payout_bank_transfer_name']) ? civi_clean(wp_unslash($_REQUEST['payout_bank_transfer_name'])) : '';
            $custom_field  = isset($_REQUEST['custom_field']) ? civi_clean(wp_unslash($_REQUEST['custom_field'])) : '';
            $enable_paypal = civi_get_option('enable_payout_paypal');
            $enable_stripe = civi_get_option('enable_payout_stripe');
            $enable_bank = civi_get_option('enable_payout_bank_transfer');

            global $current_user;
            $user_id = $current_user->ID;

            if (!empty($payout_paypal)) {
                if($enable_paypal === '1'){
                    update_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_paypal', $payout_paypal);
                }
            } else {
                delete_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_paypal');
            }

            if (!empty($payout_stripe)) {
                if($enable_stripe === '1'){
                    update_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_stripe', $payout_stripe);
                }
            } else {
                delete_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_stripe');
            }

            if (!empty($payout_card_number)) {
                if($enable_bank === '1'){
                    update_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_card_number', $payout_card_number);
                }
            } else {
                delete_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_card_number');
            }

            if (!empty($payout_card_name)) {
                if($enable_bank === '1'){
                    update_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_card_name', $payout_card_name);
                }
            } else {
                delete_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_card_name');
            }

            if (!empty($payout_bank_transfer_name)) {
                if($enable_bank === '1'){
                    update_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_bank_transfer_name', $payout_bank_transfer_name);
                }
            } else {
                delete_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_bank_transfer_name');
            }

            if(!empty($custom_field)) :
                foreach ($custom_field as $key => $field) :
                    if (!empty($field)) {
                        update_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_custom_'  . $key, $field);
                    } else {
                        delete_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_custom_' . $key);
                    }
                endforeach;
            endif;

            echo json_encode(array('success' => true, 'message' => esc_html__('You have successfully submitted your information', 'civi-framework')));

            wp_die();
        }

        /**
         * Chart Jobs
         */
        public function civi_chart_ajax()
        {
            $number_days = isset($_REQUEST['number_days']) ? civi_clean(wp_unslash($_REQUEST['number_days'])) : '7';
            $jobs_id = isset($_REQUEST['jobs_id']) ? civi_clean(wp_unslash($_REQUEST['jobs_id'])) : '';

            // labels
            $labels = array();
            for ($i = $number_days; $i >= 0; $i--) {
                $date = strtotime(date("Y-m-d", strtotime("-" . $i . " day")));
                $labels[] = date('M j, Y', $date);
            }

            $values_view = civi_view_jobs_date($jobs_id, $number_days);
            $values_apply = civi_total_jobs_apply($jobs_id, $number_days);

            $return = array(
                'labels' => $labels,
                'values_view' => $values_view,
                'values_apply' => $values_apply,
                'label_view' => esc_html__('Page View', 'civi-framework'),
                'label_apply' => esc_html__('Apply Click', 'civi-framework'),
            );
            echo json_encode($return);

            wp_die();
        }

        /**
         * Chart Employer
         */
        public function civi_chart_employer_ajax()
        {
            $number_days = isset($_REQUEST['number_days']) ? civi_clean(wp_unslash($_REQUEST['number_days'])) : '7';

            // labels
            $labels = array();
            for ($i = $number_days; $i >= 0; $i--) {
                $date = strtotime(date("Y-m-d", strtotime("-" . $i . " day")));
                $labels[] = date('M j, Y', $date);
            }

            $views_values = civi_total_view_jobs($number_days);

            $return = array(
                'labels_view' => $labels,
                'values_view' => $views_values,
                'label_view' => esc_html__('Page View', 'civi-framework'),
            );
            echo json_encode($return);

            wp_die();
        }

        /**
         * Chart Candidate
         */
        public function civi_chart_candidate_ajax()
        {
            $number_days = isset($_REQUEST['number_days']) ? civi_clean(wp_unslash($_REQUEST['number_days'])) : '7';

            // labels
            $labels = array();
            for ($i = $number_days; $i >= 0; $i--) {
                $date = strtotime(date("Y-m-d", strtotime("-" . $i . " day")));
                $labels[] = date('M j, Y', $date);
            }

            $views_values = civi_total_view_candidate($number_days);

            $return = array(
                'labels_view' => $labels,
                'values_view' => $views_values,
                'label_view' => esc_html__('Page View', 'civi-framework'),
            );
            echo json_encode($return);

            wp_die();
        }

        /**
         * Apply Jobs
         */
        public function jobs_add_to_apply()
        {
            $jobs_id = isset($_REQUEST['jobs_id']) ? civi_clean(wp_unslash($_REQUEST['jobs_id'])) : '';
            $candidate_id = isset($_REQUEST['candidate_id']) ? civi_clean(wp_unslash($_REQUEST['candidate_id'])) : '';
            $message = isset($_REQUEST['message']) ? civi_clean(wp_unslash($_REQUEST['message'])) : '';
            $email = isset($_REQUEST['emaill']) ? civi_clean(wp_unslash($_REQUEST['emaill'])) : '';
            $phone = isset($_REQUEST['phone']) ? civi_clean(wp_unslash($_REQUEST['phone'])) : '';
            $cv_url = isset($_REQUEST['cv_url']) ? civi_clean(wp_unslash($_REQUEST['cv_url'])) : '';
            $type_apply = isset($_REQUEST['type_apply']) ? civi_clean(wp_unslash($_REQUEST['type_apply'])) : '';

            global $current_user;
            $user_id = $current_user->ID;
            $user_name = $current_user->display_name;

            if ($type_apply == 'email') {
                $check_field = $message == '' || $email == '' || $phone == '' || $cv_url == '';
            } else {
                $check_field = $message == '' || $cv_url == '';
            }

			$author_employer_id = get_post_field('post_author', $jobs_id);
			$user_employer = get_user_by('id', $author_employer_id);
			$user_employer_email = $user_employer->user_email;
            $jobs_apply_email = !empty(get_post_meta($jobs_id, CIVI_METABOX_PREFIX . 'jobs_apply_email')) ? get_post_meta($jobs_id, CIVI_METABOX_PREFIX . 'jobs_apply_email')[0] : '';

            if(!empty($jobs_apply_email)){
                $user_employer_email = $jobs_apply_email;
            } else {
                $user_employer_email = $user_employer->user_email;
            }

            if ($check_field) {
                echo json_encode(array('added' => false, 'success' => false, 'message' => esc_html__('Please fill all form fields', 'civi-framework')));
            } else {

                $new_jobs = array(
                    'post_type' => 'applicants',
                    'post_status' => 'publish',
                );

                $jobs_title = get_the_title($jobs_id);
                if (isset($jobs_title)) {
                    $new_jobs['post_title'] = $jobs_title;
                }
                if (!empty($new_jobs['post_title'])) {
                    $applicants_id = wp_insert_post($new_jobs, true);
                }

                $date_applicants = get_the_date('Y-m-d', $applicants_id);

                if (isset($jobs_id)) {
                    update_post_meta($applicants_id, CIVI_METABOX_PREFIX . 'applicants_jobs_id', $jobs_id);
                }

                if (isset($date_applicants)) {
                    update_post_meta($applicants_id, CIVI_METABOX_PREFIX . 'applicants_date', $date_applicants);
                }

                if ($type_apply == 'email') {
                    if (isset($phone)) {
                        update_post_meta($applicants_id, CIVI_METABOX_PREFIX . 'applicants_phone', $phone);
                    }

                    if (isset($email)) {
                        update_post_meta($applicants_id, CIVI_METABOX_PREFIX . 'applicants_email', $email);
                    }
                }

                if (isset($message)) {
                    update_post_meta($applicants_id, CIVI_METABOX_PREFIX . 'applicants_message', $message);
                }

                if (isset($cv_url)) {
                    update_post_meta($applicants_id, CIVI_METABOX_PREFIX . 'applicants_cv', $cv_url);
                }

                if (isset($type_apply)) {
                    update_post_meta($applicants_id, CIVI_METABOX_PREFIX . 'applicants_type', $type_apply);
                }

                $enable_apply_login = civi_get_option('enable_apply_login');
                if ($enable_apply_login == '1' || is_user_logged_in()) {

                    if ($user_id > 0) {
                        $my_apply = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'my_apply', true);

                        if (!empty($my_apply) && (!in_array($jobs_id, $my_apply))) {
                            array_push($my_apply, $jobs_id);
                        } else {
                            $my_apply = array($jobs_id);
                        }
                        update_user_meta($user_id, CIVI_METABOX_PREFIX . 'my_apply', $my_apply);
                    }

                    if ($user_id > 0) {
                        update_post_meta($applicants_id, CIVI_METABOX_PREFIX . 'applicants_author', $user_name);
                    }

                    civi_get_data_ajax_notification($jobs_id, 'add-apply');

                    civi_number_candidate_package_ajax('jobs_apply');

                    $args_mail = array(
                        'jobs_apply' => get_the_title($jobs_id),
                        'jobs_url' => get_permalink($jobs_id),
                        'user_apply' => $user_name,
                        'user_url' => get_permalink($user_id),
                    );
                    civi_send_email($email, 'mail_candidate_apply', $args_mail);
                    civi_send_email($user_employer_email, 'mail_employer_apply', $args_mail);
                } else {
                    $user_name_nlogin = esc_html('User not logged in','civi-framework');
                    update_post_meta($applicants_id, CIVI_METABOX_PREFIX . 'applicants_author', $user_name_nlogin);

					$args_mail = array(
                        'cv_url' => $cv_url,
                        'jobs_apply' => get_the_title($jobs_id),
                        'jobs_url' => get_permalink($jobs_id),
                        'user_apply' => $email,
                        'user_url' => get_permalink($user_id),
                    );
                    civi_send_email($user_employer_email, 'mail_employer_apply', $args_mail);
                    $args_mail_nlogin = array(
                        'cv_url' => $cv_url,
                        'message' =>  $message,
                        'jobs_apply' => get_the_title($jobs_id),
                        'jobs_url' => get_permalink($jobs_id),
                    );
                    civi_send_email($email, 'mail_candidate_apply_nlogin', $args_mail_nlogin);
                }

                echo json_encode(array('added' => true, 'success' => true, 'message' => esc_html__('You have applied successfully', 'civi-framework')));
            }

            wp_die();
        }

        /**
         * Wishlist Jobs
         */
        public function civi_add_to_wishlist()
        {
            global $current_user;
            $jobs_id = $_POST['jobs_id'];
            $jobs_id = intval($jobs_id);
            wp_get_current_user();
            $user_id = $current_user->ID;
            $added = $removed = false;
            $ajax_response = '';
            if ($user_id > 0) {

                civi_number_candidate_package_ajax('jobs_wishlist');
                $candidate_package_id = get_the_author_meta(CIVI_METABOX_PREFIX . 'candidate_package_id', $user_id);
                $check_package = civi_check_candidate_package();
                $show_package_jobs_wishlist = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'show_package_jobs_wishlist', true);
                $candidate_package_number_jobs_wishlist = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'candidate_package_number_jobs_wishlist', true);
                $show_option_jobs_wishlist = civi_get_option('enable_candidate_package_jobs_wishlist');

                if ($show_option_jobs_wishlist == 1 && $show_package_jobs_wishlist == '1' && ($candidate_package_number_jobs_wishlist < 0 || $check_package == false)) {
                    $ajax_response = array('package_expires' => true);
                } else {

                    $my_favorites = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'my_wishlist', true);

                    if (!empty($my_favorites) && (!in_array($jobs_id, $my_favorites))) {
                        array_push($my_favorites, $jobs_id);
                        $added = true;
                    } else {
                        if (empty($my_favorites)) {
                            $my_favorites = array($jobs_id);
                            $added = true;
                        } else {
                            //Delete favorite
                            $key = array_search($jobs_id, $my_favorites);
                            if ($key !== false) {
                                unset($my_favorites[$key]);
                                $removed = true;
                            }
                        }
                    }

                    update_user_meta($user_id, CIVI_METABOX_PREFIX . 'my_wishlist', $my_favorites);

                    if ($added) {
                        civi_get_data_ajax_notification($jobs_id, 'add-wishlist');
                        $ajax_response = array('added' => true, 'message' => esc_html__('Added', 'civi-framework'));
                    }
                    if ($removed) {
                        $ajax_response = array('added' => false, 'message' => esc_html__('Removed', 'civi-framework'));
                    }
                }

            } else {
                $ajax_response = array(
                    'added' => false,
                    'message' => esc_html__('You are not login', 'civi-framework')
                );
            }
            echo json_encode($ajax_response);
            wp_die();
        }

        /**
         * Wishlist Service
         */
        public function civi_service_wishlist()
        {
            global $current_user;
            $user_id = $current_user->ID;
            $service_id = $_POST['service_id'];
            $service_id = intval($service_id);
            $added = $removed = false;
            $ajax_response = '';

            if ($user_id > 0) {
                $service_wishlist = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'service_wishlist', true);

                if (!empty($service_wishlist) && (!in_array($service_id, $service_wishlist))) {
                    array_push($service_wishlist, $service_id);
                    $added = true;
                } else {
                    if (empty($service_wishlist)) {
                        $service_wishlist = array($service_id);
                        $added = true;
                    } else {
                        $key = array_search($service_id, $service_wishlist);
                        if ($key !== false) {
                            unset($service_wishlist[$key]);
                            $removed = true;
                        }
                    }
                }
                update_user_meta($user_id, CIVI_METABOX_PREFIX . 'service_wishlist', $service_wishlist);

                if ($added) {
                    $ajax_response = array('added' => true, 'message' => esc_html__('Added', 'civi-framework'));
                }
                if ($removed) {
                    $ajax_response = array('added' => false, 'message' => esc_html__('Removed', 'civi-framework'));
                }

            } else {
                $ajax_response = array(
                    'added' => false,
                    'message' => esc_html__('You are not login', 'civi-framework')
                );
            }
            echo json_encode($ajax_response);
            wp_die();
        }


        /**
         * Service Addons
         */
        public function civi_service_addons()
        {
            $service_id = isset($_REQUEST['service_id']) ? civi_clean(wp_unslash($_REQUEST['service_id'])) : '';
            $price_total = isset($_REQUEST['price_total']) ? civi_clean(wp_unslash($_REQUEST['price_total'])) : '';
            $price_addons = isset($_REQUEST['price_addons']) ? civi_clean(wp_unslash($_REQUEST['price_addons'])) : '';

            global $current_user;
            $user_id = $current_user->ID;

            if(!empty($service_id)){
                update_user_meta($user_id, CIVI_METABOX_PREFIX . 'package_addons_service_id', $service_id);
            }

            if(!empty($price_total)){
                update_user_meta($user_id, CIVI_METABOX_PREFIX . 'package_addons_price_total', $price_total);
            }

            if(!empty($price_addons)){
                update_user_meta($user_id, CIVI_METABOX_PREFIX . 'package_addons_price_addons', $price_addons);
            }  else {
                update_user_meta($user_id, CIVI_METABOX_PREFIX . 'package_addons_price_addons', 0);
            }

            $ajax_response = array('success' => true);
            echo json_encode($ajax_response);

            wp_die();
        }

        /**
         * Follow Company
         */
        public function civi_add_to_follow()
        {
            global $current_user;
            $company_id = $_POST['company_id'];
            $company_id = intval($company_id);
            wp_get_current_user();
            $user_id = $current_user->ID;
            $added = $removed = false;
            $ajax_response = '';
            if ($user_id > 0) {

                civi_number_candidate_package_ajax('company_follow');
                $candidate_package_id = get_the_author_meta(CIVI_METABOX_PREFIX . 'candidate_package_id', $user_id);
                $check_package = civi_check_candidate_package();
                $show_package_company_candidate_follow = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'show_package_company_candidate_follow', true);
                $candidate_package_number_company_follow = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'candidate_package_number_company_follow', true);
                if ($show_package_company_candidate_follow == '1' && ($candidate_package_number_company_follow < 0 || $check_package == false)) {
                    $ajax_response = array('package_expires' => true);
                } else {
                    $my_follow = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'my_follow', true);

                    if (!empty($my_follow) && (!in_array($company_id, $my_follow))) {
                        array_push($my_follow, $company_id);
                        $added = true;
                    } else {
                        if (empty($my_follow)) {
                            $my_follow = array($company_id);
                            $added = true;
                        } else {
                            //Delete favorite
                            $key = array_search($company_id, $my_follow);
                            if ($key !== false) {
                                unset($my_follow[$key]);
                                $removed = true;
                            }
                        }
                    }

                    update_user_meta($user_id, CIVI_METABOX_PREFIX . 'my_follow', $my_follow);

                    if ($added) {
                        civi_get_data_ajax_notification($company_id, 'add-follow-company');
                        $ajax_response = array('added' => true, 'message' => esc_html__('Added', 'civi-framework'));
                    }
                    if ($removed) {
                        $ajax_response = array('added' => false, 'message' => esc_html__('Removed', 'civi-framework'));
                    }
                }

            } else {
                $ajax_response = array(
                    'added' => false,
                    'message' => esc_html__('You are not login', 'civi-framework')
                );
            }
            echo json_encode($ajax_response);
            wp_die();
        }

        /**
         * Follow Company
         */
        public function civi_add_to_invite()
        {
            $candidate_id = isset($_REQUEST['candidate_id']) ? civi_clean(wp_unslash($_REQUEST['candidate_id'])) : '';
            $author_id = isset($_REQUEST['author_id']) ? civi_clean(wp_unslash($_REQUEST['author_id'])) : '';
            $jobs_id = isset($_REQUEST['jobs_id']) ? civi_clean(wp_unslash($_REQUEST['jobs_id'])) : '';
            $list_jobs = isset($_REQUEST['list_jobs']) ? civi_clean(wp_unslash($_REQUEST['list_jobs'])) : '';

            global $current_user;
            $user_id = $current_user->ID;
            $my_invite = get_user_meta($author_id, CIVI_METABOX_PREFIX . 'my_invite', true);
            $invite_candidate = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'invite_candidate', true);
            if (empty($my_invite)) {
                $my_invite = array(0);
            }
            if (empty($invite_candidate)) {
                $invite_candidate = array(0);
            }

            if (!empty($jobs_id)) {
                //my_invite
                $jobs_diff = array_diff(json_decode($list_jobs), $jobs_id);
                foreach ($jobs_id as $invites) {
                    if (!empty($my_invite) && !in_array($invites, $my_invite)) {
                        array_push($my_invite, $invites);
                    }
                }
                if (!empty($jobs_diff)) {
                    foreach ($jobs_diff as $job_diff) {
                        $key_my_invite = array_search($job_diff, $my_invite);
                        if ($key_my_invite !== false) {
                            unset($my_invite[$key_my_invite]);
                        }
                    }
                }
                civi_get_data_ajax_notification($candidate_id, 'add-invite');
            } else {
                foreach (json_decode($list_jobs) as $list_job) {
                    $key_my_invite = array_search($list_job, $my_invite);
                    if ($key_my_invite !== false) {
                        unset($my_invite[$key_my_invite]);
                    }
                }
            }

            //invite_candidate
            if (!empty($jobs_id) && !in_array($candidate_id, $invite_candidate)) {
                array_push($invite_candidate, $candidate_id);
            } else if (empty($jobs_id) && in_array($candidate_id, $invite_candidate)) {
                $key_invite_candidate = array_search($candidate_id, $invite_candidate);
                if ($key_invite_candidate !== false) {
                    unset($invite_candidate[$key_invite_candidate]);
                }
            }

            update_user_meta($author_id, CIVI_METABOX_PREFIX . 'my_invite', $my_invite);
            update_user_meta($user_id, CIVI_METABOX_PREFIX . 'invite_candidate', $invite_candidate);

            $ajax_response = array('success' => true);
            echo json_encode($ajax_response);
            wp_die();
        }

        /**
         * Follow Candidate
         */
        public function civi_add_to_follow_candidate()
        {
            global $current_user;
            $candidate_id = $_POST['candidate_id'];
            $candidate_id = intval($candidate_id);
            wp_get_current_user();
            $user_id = $current_user->ID;
            $added = $removed = false;
            $ajax_response = '';
            if ($user_id > 0) {
                $follow_candidate = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'follow_candidate', true);

                $paid_submission_type = civi_get_option('paid_submission_type');
                $package_id = get_the_author_meta(CIVI_METABOX_PREFIX . 'package_id', $user_id);
                $civi_profile = new Civi_Profile();
                $check_package = $civi_profile->user_package_available($user_id);
                $show_package_follow = get_post_meta($package_id, CIVI_METABOX_PREFIX . 'show_package_company_candidate_follow', true);
                $enable_company_package_follow = civi_get_option('enable_company_package_candidate_follow');
                $company_package_number_candidate_follow = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'package_number_candidate_follow', true);

                if ($paid_submission_type == 'per_package' && $enable_company_package_follow === '1' && $show_package_follow === '1' && ($company_package_number_candidate_follow <= 0 || ($check_package == -1 || $check_package == 0))) {
                    $ajax_response = array('package_expires' => true);
                } else {
                    if (!empty($follow_candidate) && (!in_array($candidate_id, $follow_candidate))) {
                        array_push($follow_candidate, $candidate_id);
                        $added = true;
                    } else {
                        if (empty($follow_candidate)) {
                            $follow_candidate = array($candidate_id);
                            $added = true;
                        } else {
                            //Delete favorite
                            $key = array_search($candidate_id, $follow_candidate);
                            if ($key !== false) {
                                unset($follow_candidate[$key]);
                                $removed = true;
                            }
                        }
                    }

                    update_user_meta($user_id, CIVI_METABOX_PREFIX . 'follow_candidate', $follow_candidate);

                    if ($added) {
                        civi_get_data_ajax_notification($candidate_id, 'add-follow-candidate');
                        if($company_package_number_candidate_follow - 1 >= 0) {
                            update_user_meta($user_id, CIVI_METABOX_PREFIX . 'package_number_candidate_follow', $company_package_number_candidate_follow - 1);
                        }
                        $ajax_response = array('added' => true, 'message' => esc_html__('Added', 'civi-framework'));
                    }
                    if ($removed) {
                        $ajax_response = array('added' => false, 'message' => esc_html__('Removed', 'civi-framework'));
                    }
                }
            } else {
                $ajax_response = array(
                    'added' => false,
                    'message' => esc_html__('You are not login', 'civi-framework')
                );
            }
            echo json_encode($ajax_response);
            wp_die();
        }

        /**
         * upload thumbnail
         */
        public function civi_thumbnail_upload_ajax()
        {
            $nonce = isset($_REQUEST['nonce']) ? civi_clean(wp_unslash($_REQUEST['nonce'])) : '';
            if (!wp_verify_nonce($nonce, 'civi_thumbnail_allow_upload')) {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Security check failed!', 'civi-framework'));
                echo json_encode($ajax_response);
                wp_die();
            }

            $submitted_file = $_FILES['civi_thumbnail_upload_file']; // WPCS: sanitization ok, input var ok.

            $uploaded_image = wp_handle_upload($submitted_file, array('test_form' => false));

            if (isset($uploaded_image['file'])) {
                $file_name = basename($submitted_file['name']);
                $file_type = wp_check_filetype($uploaded_image['file']);
                $attachment_details = array(
                    'guid'           => $uploaded_image['url'],
                    'post_mime_type' => $file_type['type'],
                    'post_title'     => preg_replace('/\.[^.]+$/', '', basename($file_name)),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                $attach_id     = wp_insert_attachment($attachment_details, $uploaded_image['file']);
                $attach_data   = wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);
                $thumbnail_url = wp_get_attachment_url($attach_id);
                $fullimage_url = wp_get_attachment_image_src($attach_id, 'full');

                $ajax_response = array(
                    'success'       => true,
                    'title'         => $file_name,
                    'url'           => $thumbnail_url,
                    'attachment_id' => $attach_id,
                    'full_image'    => $fullimage_url[0]
                );
                echo json_encode($ajax_response);
                wp_die();
            } else {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Image upload failed!', 'civi-framework'));
                echo json_encode($ajax_response);
                wp_die();
            }
        }

        /**
         * Remove thumbnail img
         */
        public function civi_thumbnail_remove_ajax()
        {
            $nonce = isset($_POST['removeNonce']) ? civi_clean(wp_unslash($_POST['removeNonce'])) : '';
            $user_id = isset($_POST['user_id']) ? civi_clean(wp_unslash($_POST['user_id'])) : '';
            if (!wp_verify_nonce($nonce, 'civi_thumbnail_allow_upload')) {
                $json_response = array(
                    'success' => false,
                    'reason'  => esc_html__('Security check fails', 'civi-framework')
                );
                echo json_encode($json_response);
                wp_die();
            }
            $success = false;
            if (isset($_POST['attachment_id'])) {
                $attachment_id = absint(wp_unslash($_POST['attachment_id']));
                if ($attachment_id > 0) {
                    wp_delete_attachment($attachment_id);
                    $success = true;
                }
            }
            if ($user_id) {
                update_user_meta($user_id, 'author_avatar_image_url', CIVI_THEME_URI . '/assets/images/default-user-image.png');
            }
            $ajax_response = array(
                'success' => $success,
                'url'        => get_the_author_meta('author_avatar_image_url', $user_id),
            );

            echo json_encode($ajax_response);
            wp_die();
        }

        /**
         * upload avatar
         */
        public function civi_avatar_upload_ajax()
        {
            $nonce = isset($_REQUEST['nonce']) ? civi_clean(wp_unslash($_REQUEST['nonce'])) : '';
            if (!wp_verify_nonce($nonce, 'civi_avatar_allow_upload')) {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Security check failed!', 'civi-framework'));
                echo json_encode($ajax_response);
                wp_die();
            }

            $submitted_file = $_FILES['civi_avatar_upload_file']; // WPCS: sanitization ok, input var ok.

            $uploaded_image = wp_handle_upload($submitted_file, array('test_form' => false));

            if (isset($uploaded_image['file'])) {
                $file_name = basename($submitted_file['name']);
                $file_type = wp_check_filetype($uploaded_image['file']);
                $attachment_details = array(
                    'guid'           => $uploaded_image['url'],
                    'post_mime_type' => $file_type['type'],
                    'post_title'     => preg_replace('/\.[^.]+$/', '', basename($file_name)),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                $attach_id     = wp_insert_attachment($attachment_details, $uploaded_image['file']);
                $attach_data   = wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);
                $avatar_url = wp_get_attachment_url($attach_id);
                $fullimage_url = wp_get_attachment_image_src($attach_id, 'full');

                $ajax_response = array(
                    'success'       => true,
                    'title'         => $file_name,
                    'url'           => $avatar_url,
                    'attachment_id' => $attach_id,
                    'full_image'    => $fullimage_url[0]
                );
                echo json_encode($ajax_response);
                wp_die();
            } else {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Image upload failed!', 'civi-framework'));
                echo json_encode($ajax_response);
                wp_die();
            }
        }

        /**
         * Remove avatar img
         */
        public function civi_avatar_remove_ajax()
        {
            $nonce = isset($_POST['removeNonce']) ? civi_clean(wp_unslash($_POST['removeNonce'])) : '';
            $user_id = isset($_POST['user_id']) ? civi_clean(wp_unslash($_POST['user_id'])) : '';
            if (!wp_verify_nonce($nonce, 'civi_avatar_allow_upload')) {
                $json_response = array(
                    'success' => false,
                    'reason'  => esc_html__('Security check fails', 'civi-framework')
                );
                echo json_encode($json_response);
                wp_die();
            }
            $success = false;
            if (isset($_POST['attachment_id'])) {
                $attachment_id = absint(wp_unslash($_POST['attachment_id']));
                if ($attachment_id > 0) {
                    wp_delete_attachment($attachment_id);
                    $success = true;
                }
            }
            if ($user_id) {
                update_user_meta($user_id, 'author_avatar_image_url', CIVI_THEME_URI . '/assets/images/default-user-image.png');
            }
            $ajax_response = array(
                'success' => $success,
                'url'        => get_the_author_meta('author_avatar_image_url', $user_id),
            );

            echo json_encode($ajax_response);
            wp_die();
        }


        /**
         * upload custom_image
         */
        public function civi_custom_image_upload_ajax()
        {
            $nonce = isset($_REQUEST['nonce']) ? civi_clean(wp_unslash($_REQUEST['nonce'])) : '';
            $custom_image_id = isset($_REQUEST['custom_image_id']) ? civi_clean(wp_unslash($_REQUEST['custom_image_id'])) : '';

            if (!wp_verify_nonce($nonce, 'civi_custom_image_allow_upload')) {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Security check failed!', 'civi-framework'));
                echo json_encode($ajax_response);
                wp_die();
            }

            $submitted_file = $_FILES['civi_custom_image_upload_file_' . $custom_image_id]; // WPCS: sanitization ok, input var ok.

            $uploaded_image = wp_handle_upload($submitted_file, array('test_form' => false));

            if (isset($uploaded_image['file'])) {
                $file_name = basename($submitted_file['name']);
                $file_type = wp_check_filetype($uploaded_image['file']);
                $attachment_details = array(
                    'guid'           => $uploaded_image['url'],
                    'post_mime_type' => $file_type['type'],
                    'post_title'     => preg_replace('/\.[^.]+$/', '', basename($file_name)),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                $attach_id     = wp_insert_attachment($attachment_details, $uploaded_image['file']);
                $attach_data   = wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);
                $custom_image_url = wp_get_attachment_url($attach_id);
                $fullimage_url = wp_get_attachment_image_src($attach_id, 'full');

                $ajax_response = array(
                    'success'       => true,
                    'title'         => $file_name,
                    'url'           => $custom_image_url,
                    'attachment_id' => $attach_id,
                    'full_image'    => $fullimage_url[0]
                );
                echo json_encode($ajax_response);
                wp_die();
            } else {
                $ajax_response = array('success' => false, 'dsadsa' => $uploaded_image,'reason' => esc_html__('Image upload failed!', 'civi-framework'));
                echo json_encode($ajax_response);
                wp_die();
            }
        }

        /**
         * Remove custom_image img
         */
        public function civi_custom_image_remove_ajax()
        {
            $nonce = isset($_POST['removeNonce']) ? civi_clean(wp_unslash($_POST['removeNonce'])) : '';
            $user_id = isset($_POST['user_id']) ? civi_clean(wp_unslash($_POST['user_id'])) : '';
            if (!wp_verify_nonce($nonce, 'civi_custom_image_allow_upload')) {
                $json_response = array(
                    'success' => false,
                    'reason'  => esc_html__('Security check fails', 'civi-framework')
                );
                echo json_encode($json_response);
                wp_die();
            }
            $success = false;
            if (isset($_POST['attachment_id'])) {
                $attachment_id = absint(wp_unslash($_POST['attachment_id']));
                if ($attachment_id > 0) {
                    wp_delete_attachment($attachment_id);
                    $success = true;
                }
            }
            if ($user_id) {
                update_user_meta($user_id, 'author_avatar_image_url', CIVI_THEME_URI . '/assets/images/default-user-image.png');
            }
            $ajax_response = array(
                'success' => $success,
                'url'        => get_the_author_meta('author_avatar_image_url', $user_id),
            );

            echo json_encode($ajax_response);
            wp_die();
        }

        /**
         * upload gallery
         */
        public function civi_gallery_upload_ajax()
        {
            $nonce = isset($_REQUEST['nonce']) ? civi_clean(wp_unslash($_REQUEST['nonce'])) : '';
            if (!wp_verify_nonce($nonce, 'civi_gallery_allow_upload')) {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Security check failed!', 'civi-framework'));
                echo json_encode($ajax_response);
                wp_die();
            }

            $submitted_file = $_FILES['civi_gallery_upload_file']; // WPCS: sanitization ok, input var ok.

            $uploaded_image = wp_handle_upload($submitted_file, array('test_form' => false));

            if (isset($uploaded_image['file'])) {
                $file_name = basename($submitted_file['name']);
                $file_type = wp_check_filetype($uploaded_image['file']);
                $attachment_details = array(
                    'guid'           => $uploaded_image['url'],
                    'post_mime_type' => $file_type['type'],
                    'post_title'     => preg_replace('/\.[^.]+$/', '', basename($file_name)),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                $attach_id     = wp_insert_attachment($attachment_details, $uploaded_image['file']);
                $attach_data   = wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);
                $gallery_url = wp_get_attachment_url($attach_id);
                $fullimage_url = wp_get_attachment_image_src($attach_id, 'full');

                $ajax_response = array(
                    'success'       => true,
                    'title'         => $file_name,
                    'url'           => $gallery_url,
                    'attachment_id' => $attach_id,
                    'full_image'    => $fullimage_url[0]
                );
                echo json_encode($ajax_response);
                wp_die();
            } else {
                $ajax_response = array('success' => false, 'reason' => esc_html__('Image upload failed!', 'civi-framework'));
                echo json_encode($ajax_response);
                wp_die();
            }
        }

        /**
         * Remove gallery img
         */
        public function civi_gallery_remove_ajax()
        {
            $nonce = isset($_POST['removeNonce']) ? civi_clean(wp_unslash($_POST['removeNonce'])) : '';
            $user_id = isset($_POST['user_id']) ? civi_clean(wp_unslash($_POST['user_id'])) : '';
            if (!wp_verify_nonce($nonce, 'civi_gallery_allow_upload')) {
                $json_response = array(
                    'success' => false,
                    'reason'  => esc_html__('Security check fails', 'civi-framework')
                );
                echo json_encode($json_response);
                wp_die();
            }
            $success = false;
            if (isset($_POST['attachment_id'])) {
                $attachment_id = absint(wp_unslash($_POST['attachment_id']));
                if ($attachment_id > 0) {
                    wp_delete_attachment($attachment_id);
                    $success = true;
                }
            }
            if ($user_id) {
                update_user_meta($user_id, 'author_avatar_image_url', CIVI_THEME_URI . '/assets/images/default-user-image.png');
            }
            $ajax_response = array(
                'success' => $success,
                'url'        => get_the_author_meta('author_avatar_image_url', $user_id),
            );

            echo json_encode($ajax_response);
            wp_die();
        }

        /**
         * Candidate Print Ajax
         */
        public function civi_candidate_print_ajax(){
            $candidate_id = isset($_REQUEST['candidate_id']) ? civi_clean(wp_unslash($_REQUEST['candidate_id'])) : '';
            if (empty($candidate_id)) {
                return;
            }
            $isRTL = 'false';
            if (isset($_POST['isRTL'])) {
                $isRTL = sanitize_text_field($_POST['isRTL']);
            }
            civi_get_template('candidate/print.php', array('candidate_id' => $candidate_id, 'isRTL' => $isRTL));
            wp_die();
        }

        /**
         * Select Country
         */
        public function civi_select_country(){
            $country = isset($_REQUEST['country']) ? civi_clean(wp_unslash($_REQUEST['country'])) : '';
            $post_type = isset($_REQUEST['post_type']) ? civi_clean(wp_unslash($_REQUEST['post_type'])) : '';

            if($post_type == 'jobs'){
                $taxonomy = 'jobs-state';
            } elseif ($post_type == 'company') {
                $taxonomy = 'company-state';
            } elseif ($post_type == 'candidate') {
                $taxonomy = 'candidate_state';
            } elseif ($post_type == 'service') {
                $taxonomy = 'service-state';
            }

            $taxonomy_terms = get_categories(
                array(
                    'taxonomy' => $taxonomy,
                    'orderby' => 'name',
                    'order' => 'ASC',
                    'hide_empty' => false,
                    'parent' => 0,
                    'meta_query' => array(
                        array(
                            'key' => $taxonomy . '-country',
                            'value' => $country,
                            'compare' => '=',
                        )
                    )
                )
            );

            ob_start();
            foreach ($taxonomy_terms as $terms) {
                echo '<option value="' . $terms->term_id . '">' . $terms->name . '</option>';
            }
            $state_html = ob_get_clean();

            $ajax_response = array('success' => true, 'state_html' => $state_html);

            echo json_encode($ajax_response);

            wp_die();
        }

        /**
         * Select State
         */
        public function civi_select_state(){
            $state = isset($_REQUEST['state']) ? civi_clean(wp_unslash($_REQUEST['state'])) : '';
            $post_type = isset($_REQUEST['post_type']) ? civi_clean(wp_unslash($_REQUEST['post_type'])) : '';

            if($post_type == 'jobs'){
                $taxonomy = 'jobs-location';
            } elseif ($post_type == 'company') {
                $taxonomy = 'company-location';
            } elseif ($post_type == 'candidate') {
                $taxonomy = 'candidate_locations';
            } elseif ($post_type == 'service') {
                $taxonomy = 'service-location';
            }

            $taxonomy_terms = get_categories(
                array(
                    'taxonomy' => $taxonomy,
                    'orderby' => 'name',
                    'order' => 'ASC',
                    'hide_empty' => false,
                    'parent' => 0,
                    'meta_query' => array(
                        array(
                            'key' => $taxonomy . '-state',
                            'value' => $state,
                            'compare' => '=',
                        )
                    )
                )
            );

            ob_start();
            foreach ($taxonomy_terms as $terms) {
                echo '<option value="' . $terms->term_id . '">' . $terms->name . '</option>';
            }
            $city_html = ob_get_clean();

            $ajax_response = array('success' => true, 'city_html' => $city_html);

            echo json_encode($ajax_response);

            wp_die();
        }

        /**
         * Elementor
         */
        public function civi_el_jobs_pagination_ajax()
        {
            $item_amount = isset($_REQUEST['item_amount']) ? civi_clean(wp_unslash($_REQUEST['item_amount'])) : '4';
            $paged = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '1';
            $page = isset($_REQUEST['paged']) ? civi_clean(wp_unslash($_REQUEST['paged'])) : '';
            $layout = isset($_REQUEST['layout']) ? civi_clean(wp_unslash($_REQUEST['layout'])) : '';
            $type_pagination = isset($_REQUEST['type_pagination']) ? civi_clean(wp_unslash($_REQUEST['type_pagination'])) : '';
            $include_ids = isset($_REQUEST['include_ids']) ? civi_clean(wp_unslash($_REQUEST['include_ids'])) : '';
            $type_query = isset($_REQUEST['type_query']) ? civi_clean(wp_unslash($_REQUEST['type_query'])) : '';
            $orderby = isset($_REQUEST['orderby']) ? civi_clean(wp_unslash($_REQUEST['orderby'])) : '';
            $jobs_categories = isset($_REQUEST['jobs_categories']) ? civi_clean(wp_unslash($_REQUEST['jobs_categories'])) : '';
            $jobs_skills = isset($_REQUEST['jobs_skills']) ? civi_clean(wp_unslash($_REQUEST['jobs_skills'])) : '';
            $jobs_type = isset($_REQUEST['jobs_type']) ? civi_clean(wp_unslash($_REQUEST['jobs_type'])) : '';
            $jobs_location = isset($_REQUEST['jobs_location']) ? civi_clean(wp_unslash($_REQUEST['jobs_location'])) : '';
            $jobs_career = isset($_REQUEST['jobs_career']) ? civi_clean(wp_unslash($_REQUEST['jobs_career'])) : '';
            $jobs_experience = isset($_REQUEST['jobs_experience']) ? civi_clean(wp_unslash($_REQUEST['jobs_experience'])) : '';

            $args = array(
                'post_type' => 'jobs',
                'paged' => $paged,
                'ignore_sticky_posts' => 1,
                'post_status' => 'publish',
            );

            if (!empty($item_amount)) {
                $args['posts_per_page'] = $item_amount;
            }

            //Query
            $tax_query = array(
                array(
                    'key' => 'civi-enable_jobs_package_expires',
                    'value' => 0,
                    'compare' => '=='
                )
            );
            $meta_query = array();

            $include_ids = json_decode($include_ids);
            if (!empty($include_ids) && $type_query == 'title') {
                $args['post__in'] = $include_ids;
            }

            if ($type_query == 'orderby') {
                if (!empty($orderby)) {
                    if ($orderby == 'featured') {
                        $meta_query[] = array(
                            'key' => CIVI_METABOX_PREFIX . 'jobs_featured',
                            'value' => 1,
                            'type' => 'NUMERIC',
                            'compare' => '=',
                        );
                    }
                    if ($orderby == 'oldest') {
                        $args['orderby'] = array(
                            'menu_order' => 'DESC',
                            'date' => 'ASC',
                        );
                    }
                    if ($orderby == 'newest') {
                        $args['orderby'] = array(
                            'menu_order' => 'ASC',
                            'date' => 'DESC',
                        );
                    }
                    if ($orderby == 'random') {
                        $args['meta_key'] = '';
                        $args['orderby'] = 'rand';
                        $args['order'] = 'ASC';
                    }
                }
            }

            if ($jobs_categories) {
                $tax_query[] = array(
                    'taxonomy' => 'jobs-categories',
                    'field' => 'term_id',
                    'terms' => $jobs_categories,
                );
            }
            if ($jobs_skills) {
                $tax_query[] = array(
                    'taxonomy' => 'jobs-skills',
                    'field' => 'term_id',
                    'terms' => $jobs_skills,
                );
            }
            if ($jobs_type) {
                $tax_query[] = array(
                    'taxonomy' => 'jobs-type',
                    'field' => 'term_id',
                    'terms' => $jobs_type,
                );
            }
            if ($jobs_location) {
                $tax_query[] = array(
                    'taxonomy' => 'jobs-location',
                    'field' => 'term_id',
                    'terms' => $jobs_location,
                );
            }
            if ($jobs_career) {
                $tax_query[] = array(
                    'taxonomy' => 'jobs-career',
                    'field' => 'term_id',
                    'terms' => $jobs_career,
                );
            }
            if ($jobs_experience) {
                $tax_query[] = array(
                    'taxonomy' => 'jobs-experience',
                    'field' => 'term_id',
                    'terms' => $jobs_experience,
                );
            }

            if (!empty($tax_query)) {
                $args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            if (!empty($meta_query)) {
                $args['meta_query'] = array(
                    'relation' => 'AND',
                    $meta_query
                );
            }

            $data = new \WP_Query($args);
            $total_post = $data->found_posts;
            $max_num_pages = $data->max_num_pages;

            $hidden_pagination = '';
            if ($paged == $max_num_pages) {
                $hidden_pagination = 1;
            }

            if ($type_pagination == 'number') {
                $pagination = paginate_links(apply_filters('civi_pagination_args', array(
                    'total' => $max_num_pages,
                    'current' => $paged,
                    'mid_size' => 1,
                    'type' => 'array',
                    //'add_args'  => array_map( 'urlencode', $args ),
                    'prev_text' => __('<i class="fal fa-chevron-left"></i>', 'civi-framework'),
                    'next_text' => __('<i class="fal fa-chevron-right"></i>', 'civi-framework'),
                )));
            } else {
                $pagination = '<a class="page-numbers next" href="#"><span>' . __('Load More', 'civi-framework') . '</span><span class="btn-loading"><i class="fal fa-spinner fa-spin large"></i></span></a>';
            }

            ob_start();

            if ($total_post > 0) { ?>
                <?php while ($data->have_posts()) : $data->the_post(); ?>
                    <?php civi_get_template('content-jobs.php', array(
                        'jobs_layout' => $layout,
                    )); ?>
                <?php endwhile; ?>
<?php }
            wp_reset_postdata();

            $jobs_html = ob_get_clean();

            if ($total_post > 0) {
                echo json_encode(array('success' => true, 'pagination' => $pagination, 'layout' => $type_pagination, 'jobs_html' => $jobs_html, 'total_post' => $total_post, 'page' => $page, 'hidden_pagination' => $hidden_pagination));
            } else {
                echo json_encode(array('success' => false, 'total_post' => $total_post));
            }
            wp_die();
        }
    }
}
