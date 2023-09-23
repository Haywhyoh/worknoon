<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Plugin;

defined('ABSPATH') || exit;

Plugin::instance()->widgets_manager->register( new Widget_Package() );

class Widget_Package extends Widget_Base
{

    public function get_name()
    {
        return 'civi-package';
    }

    public function get_title()
    {
        return esc_html__('Package', 'civi-framework');
    }

    public function get_icon()
    {
        return 'civi-badge eicon-price-table';
    }

    public function get_keywords()
    {
        return ['package'];
    }

    public function get_style_depends()
    {
        return [CIVI_PLUGIN_PREFIX . 'package'];
    }

    protected function register_controls()
    {
        $this->add_layout_section();
    }

    private function add_layout_section()
    {
        $this->start_controls_section('layout_section', [
            'label' => esc_html__('Layout', 'civi-framework'),
        ]);

        $this->add_control('for', [
            'label' => esc_html__('For', 'civi-framework'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'package' => esc_html__('Employer', 'civi-framework'),
                'candidate_package' => esc_html__('Candidate', 'civi-framework'),
            ],
            'default' => 'package',
        ]);

		$this->add_control('layout', [
            'label' => esc_html__('Layout', 'civi-framework'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                '01' => esc_html__('Layout 01', 'civi-framework'),
                '02' => esc_html__('Layout 02', 'civi-framework'),
            ],
            'default' => '01',
            'prefix_class' => 'civi-package-layout-',
        ]);

        $employer_options = [];
        $employer_package = array(
            'post_type' => 'package',
            'posts_per_page' => -1,
            'orderby' => 'meta_value',
            'meta_key' => CIVI_METABOX_PREFIX . 'package_order_display',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => CIVI_METABOX_PREFIX . 'package_visible',
                    'value' => '1',
                    'compare' => '=',
                )
            )
        );

        $employer_package = new \WP_Query($employer_package);
        if ($employer_package->have_posts()) {
            while ($employer_package->have_posts()) : $employer_package->the_post();
                $id = get_the_id();
                $title = get_the_title($id);
                $employer_options[$id] = $title;
            endwhile;
        }
        wp_reset_postdata();

        $this->add_control('employer_title', [
            'label'       => esc_html__('Title Package', 'civi'),
            'type'        => Controls_Manager::SELECT2,
            'options'     => $employer_options,
            'default'     => [],
            'label_block' => true,
            'multiple'    => true,
			'condition' => [
				'for' => 'package',
			],
        ]);

		$candidate_options = [];
        $candidate_package = array(
            'post_type' => 'candidate_package',
            'posts_per_page' => -1,
            'orderby' => 'meta_value',
            'meta_key' => CIVI_METABOX_PREFIX . 'candidate_package_order_display',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => CIVI_METABOX_PREFIX . 'candidate_package_visible',
                    'value' => '1',
                    'compare' => '=',
                )
            )
        );

        $candidate_package = new \WP_Query($candidate_package);
        if ($candidate_package->have_posts()) {
            while ($candidate_package->have_posts()) : $candidate_package->the_post();
                $id = get_the_id();
                $title = get_the_title($id);
                $candidate_options[$id] = $title;
            endwhile;
        }
        wp_reset_postdata();

        $this->add_control('candidate_title', [
            'label'       => esc_html__('Title Package', 'civi'),
            'type'        => Controls_Manager::SELECT2,
            'options'     => $candidate_options,
            'default'     => [],
            'label_block' => true,
            'multiple'    => true,
			'condition' => [
				'for' => 'candidate_package',
			],
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('wrapper', 'class', 'civi-package civi-package-wrap');
        if (empty($settings['employer_title']) && empty($settings['candidate_title'])) {
            return;
        }
        global $current_user;
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        $field_package = array('candidate_follow', 'download_cv', 'invite', 'send_message', 'print', 'review_and_commnent', 'info');
        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper') ?>>
            <div class="row">
                <?php
                $user_package_id = get_the_author_meta(CIVI_METABOX_PREFIX . 'package_id', $user_id);
				$items = array();
				if( $settings['for'] === 'package' ){
					foreach ($settings['employer_title'] as $item) {
						array_push( $items, $item );
					}
					$args = array(
						'post_type' => 'package',
						'posts_per_page' => -1,
						'orderby' => 'meta_value',
						'meta_key' => CIVI_METABOX_PREFIX . 'package_order_display',
						'order' => 'ASC',
						'post__in' => $items,
						'meta_query' => array(
							array(
								'key' => CIVI_METABOX_PREFIX . 'package_visible',
								'value' => '1',
								'compare' => '=',
							)
						)
					);
				} else if( $settings['for'] === 'candidate_package' ){
					foreach ($settings['candidate_title'] as $item) {
						array_push( $items, $item );
					}
					$args = array(
						'post_type' => 'candidate_package',
						'posts_per_page' => -1,
						'orderby' => 'meta_value',
						'meta_key' => CIVI_METABOX_PREFIX . 'candidate_package_order_display',
						'order' => 'ASC',
						'post__in' => $items,
						'meta_query' => array(
							array(
								'key' => CIVI_METABOX_PREFIX . 'candidate_package_visible',
								'value' => '1',
								'compare' => '=',
							)
						)
					);
				}
				$data = new \WP_Query($args);
				$total_records = $data->found_posts;
				while ($data->have_posts()) : $data->the_post();
					if( $settings['for'] === 'package' ){
						$package_id = get_the_ID();
					$package_time_unit = get_post_meta($package_id, CIVI_METABOX_PREFIX . 'package_time_unit', true);
					$package_period = get_post_meta($package_id, CIVI_METABOX_PREFIX . 'package_period', true);
					$package_num_job = get_post_meta($package_id, CIVI_METABOX_PREFIX . 'package_number_job', true);
					$package_free = get_post_meta($package_id, CIVI_METABOX_PREFIX . 'package_free', true);
					$used_free_package = get_user_meta($user_id, 'used_free_package', true);
					if( $package_free == 1 && $used_free_package === 'yes' ){
						continue;
					}
					if ($package_free == 1) {
						$package_price = 0;
					} else {
						$package_price = get_post_meta($package_id, CIVI_METABOX_PREFIX . 'package_price', true);
					}
					$package_unlimited_job = get_post_meta($package_id, CIVI_METABOX_PREFIX . 'package_unlimited_job', true);
					$package_unlimited_time = get_post_meta($package_id, CIVI_METABOX_PREFIX . 'package_unlimited_time', true);
					$package_featured_job = get_post_meta($package_id, CIVI_METABOX_PREFIX . 'package_unlimited_job_featured', true);
					$package_num_featured_job = get_post_meta($package_id, CIVI_METABOX_PREFIX . 'package_number_featured', true);
					$package_featured = get_post_meta($package_id, CIVI_METABOX_PREFIX . 'package_featured', true);
					$package_additional = get_post_meta($package_id, CIVI_METABOX_PREFIX . 'package_additional_details', true);
					if ($package_additional > 0) {
						$package_additional_text = get_post_meta($package_id, CIVI_METABOX_PREFIX . 'package_details_text', true);
					}

					if ($package_period > 1) {
						$package_time_unit .= 's';
					}
					if ($package_featured == 1) {
						$is_featured = ' active';
					} else {
						$is_featured = '';
					}
					$civi_package = new \civi_Package();
					$get_expired_date = $civi_package->get_expired_date($package_id, $user_id);
					$current_date = date('Y-m-d');

					$d1 = strtotime($get_expired_date);
					$d2 = strtotime($current_date);

					if ($get_expired_date === 'never expires') {
						$d1 = 999999999999999999999999;
					}

					if ($user_package_id == $package_id && $d1 > $d2) {
						$is_current = 'current';
					} else {
						$is_current = '';
					}
					$payment_link = civi_get_permalink('payment');
					$payment_process_link = add_query_arg('package_id', $package_id, $payment_link);
					?>
					<div class="col-md-4 col-sm-6">
						<div class="civi-package-item panel panel-default <?php echo esc_attr($is_current); ?> <?php echo esc_attr($is_featured); ?>">
							<?php if (has_post_thumbnail()) : ?>
								<div class="civi-package-thumbnail"><?php the_post_thumbnail(); ?></div>
							<?php endif; ?>
							<div class="civi-package-title">
								<h2 class="entry-title"><?php the_title(); ?></h2>
								<?php if ($package_featured == 1) { ?>
									<span class="recommended"><?php esc_html_e('Recommended', 'civi-framework'); ?></span>
								<?php } ?>
							</div>
							<div class="civi-package-price">
								<?php
								if ($package_price > 0) {
									echo civi_get_format_money($package_price, '', 2, true);
								} else {
									esc_html_e('Free', 'civi-framework');
								}
								?>
							</div>
							<?php if($settings['layout'] == '02'){ ?>
								<div class="civi-package-choose">
									<?php if ($user_package_id == $package_id && $d1 > $d2) {?>
										<span class="civi-button button-block"><?php esc_html_e('Package Actived', 'civi-framework'); ?></span>
									<?php } else {?>
										<a href="<?php echo esc_url($payment_process_link); ?>" class="civi-button button-outline-accent button-block"><?php esc_html_e('Get Started', 'civi-framework'); ?></a>
									<?php } ?>
								</div>
							<?php } ?>
							<ul class="list-group custom-scrollbar">
                                <?php if(!empty($package_num_job)) : ?>
                                    <li class="list-group-item">
                                        <i class="fas fa-check"></i>
                                        <span class="badge">
                                        <?php if ($package_unlimited_job == 1) {
                                            esc_html_e('Unlimited', 'civi-framework');
                                        } else {
                                            esc_html_e($package_num_job);
                                        } ?>
                                    </span>
                                        <?php esc_html_e('job posting', 'civi-framework'); ?>
                                    </li>
                                <?php endif; ?>

                                <?php if(!empty($package_num_featured_job)) : ?>
                                    <li class="list-group-item">
                                        <i class="fas fa-check"></i>
                                        <span class="badge">
                                        <?php if ($package_featured_job == 1) {
                                            esc_html_e('Unlimited', 'civi-framework');
                                        } else {
                                            esc_html_e($package_num_featured_job);
                                        } ?>
                                    </span>
                                        <?php esc_html_e('featured job', 'civi-framework') ?>
                                    </li>
                                <?php endif; ?>

                                <?php foreach ($field_package as $field) :
                                    $show_option = civi_get_option('enable_company_package_' . $field);
                                    $show_field = get_post_meta($package_id, CIVI_METABOX_PREFIX . 'show_package_company_' . $field, true);
                                    $field_unlimited = get_post_meta($package_id, CIVI_METABOX_PREFIX . 'enable_package_' . $field . '_unlimited', true);
                                    $field_number = get_post_meta($package_id, CIVI_METABOX_PREFIX . 'company_package_number_' . $field, true);
                                    $is_check = true;
                                    switch ($field) {
                                        case 'candidate_follow':
                                            $name = esc_html__('candidates follow', 'civi-framework');
                                            $is_check = false;
                                            break;
                                        case 'download_cv':
                                            $name = esc_html__('Download CV', 'civi-framework');
                                            break;
                                        case 'invite':
                                            $name = esc_html__('Invite Candidates', 'civi-framework');
                                            break;
                                        case 'send_message':
                                            $name = esc_html__('Send Messages', 'civi-framework');
                                            break;
                                        case 'print':
                                            $name = esc_html__('Print candidate profiles', 'civi-framework');
                                            break;
                                        case 'review_and_commnent':
                                            $name = esc_html__('Review and comment', 'civi-framework');
                                            break;
                                        case 'info':
                                            $name = esc_html__('View candidate information', 'civi-framework');
                                            break;
                                    }
                                    if ($show_field == 1 && $show_option == 1) :
                                        ?>
                                        <?php if(!empty($field_number)) : ?>
                                        <li class="list-group-item"">
                                        <i class="fas fa-check"></i>
                                        <?php if ($is_check == true) { ?>
                                        <span class="badge">
                                        <?php esc_html_e($name); ?>
                                    </span>
                                    <?php } else { ?>
                                        <span class="badge">
                                         <?php if ($field_unlimited == 1) { ?>
                                             <?php esc_html_e('Unlimited', 'civi-framework'); ?>
                                         <?php } else { ?>
                                             <?php echo $field_number; ?>
                                         <?php } ?>
                                    </span>
                                        <?php echo $name; ?>
                                    <?php } ?>
                                        </li>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>

								<?php if ($package_additional > 0) {
									foreach ($package_additional_text as $value) { ?>
										<li class="list-group-item">
											<i class="fas fa-check"></i>
											<span class="badge">
											<?php esc_html_e($value); ?>
										</span>
										</li>
									<?php }
								} ?>
                                <li class="list-group-item">
                                    <i class="fas fa-check"></i>
                                    <?php esc_html_e('Job post live for', 'civi-framework'); ?>
                                    <span class="badge">
									<?php if ($package_unlimited_time == 1) {
                                        esc_html_e('never expires', 'civi-framework');
                                    } else {
                                        echo ($package_period) . ' ' . \Civi_Package::get_time_unit($package_time_unit);
                                    }
                                    ?>
								</span>
                                </li>
							</ul>
							<?php
							if($settings['layout'] == '01'){ ?>
								<div class="civi-package-choose">
									<?php if ($user_package_id == $package_id && $d1 > $d2) {?>
										<span class="civi-button button-block"><?php esc_html_e('Package Actived', 'civi-framework'); ?></span>
									<?php } else {?>
										<a href="<?php echo esc_url($payment_process_link); ?>" class="civi-button button-outline button-block"><?php esc_html_e('Get Started', 'civi-framework'); ?></a>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					</div>
					<?php
					} else if( $settings['for'] === 'candidate_package' ){
						$candidate_package_id = get_the_ID();
						$candidate_package_time_unit = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_time_unit', true);
						$candidate_package_period = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_period', true);
						$candidate_package_number_service = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_number_service', true);
						$candidate_package_free = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_free', true);
						if ($candidate_package_free == 1) {
							$candidate_package_price = 0;
						} else {
							$candidate_package_price = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_price', true);
						}
						$enable_package_service_unlimited = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'enable_package_service_unlimited', true);
						$enable_package_service_unlimited_time = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'enable_package_service_unlimited_time', true);
						$candidate_package_featured_candidate = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'enable_package_service_featured_unlimited', true);
						$candidate_package_number_service_featured = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_number_service_featured', true);
						$candidate_package_featured = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_featured', true);
						$candidate_package_additional = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_additional_details', true);
						if ($candidate_package_additional > 0) {
							$candidate_package_additional_text = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_details_text', true);
						}

						if ($candidate_package_period > 1) {
							$candidate_package_time_unit .= 's';
						}
						if ($candidate_package_featured == 1) {
							$is_featured = ' active';
						} else {
							$is_featured = '';
						}
						$civi_candidate_package = new \civi_candidate_package();
						$get_expired_date = $civi_candidate_package->get_expired_date($candidate_package_id, $user_id);
						$current_date = date('Y-m-d');

						$d1 = strtotime($get_expired_date);
						$d2 = strtotime($current_date);

						if ($get_expired_date === 'never expires') {
							$d1 = 999999999999999999999999;
						}

						$user_candidate_package_id = get_the_author_meta(CIVI_METABOX_PREFIX . 'candidate_package_id', $user_id);

						if ($user_candidate_package_id == $candidate_package_id && $d1 > $d2) {
							$is_current = 'current';
						} else {
							$is_current = '';
						}
						$payment_link = civi_get_permalink('candidate_payment');
						$payment_process_link = add_query_arg('candidate_package_id', $candidate_package_id, $payment_link);
						$field_package = array('jobs_apply', 'jobs_wishlist', 'company_follow', 'contact_company', 'info_company', 'send_message', 'review_and_commnent');
						?>
						<div class="col-md-4 col-sm-6">
							<div class="civi-package-item panel panel-default <?php echo esc_attr($is_current); ?> <?php echo esc_attr($is_featured); ?>">
								<?php if (has_post_thumbnail()) : ?>
									<div class="civi-package-thumbnail"><?php the_post_thumbnail(); ?></div>
								<?php endif; ?>
								<div class="civi-package-title">
									<h2 class="entry-title"><?php the_title(); ?></h2>
									<?php if ($candidate_package_featured == 1) { ?>
										<span class="recommended"><?php esc_html_e('Recommended', 'civi-framework'); ?></span>
									<?php } ?>
								</div>
								<div class="civi-package-price">
									<?php
									if ($candidate_package_price > 0) {
										echo civi_get_format_money($candidate_package_price, '', 2, true);
									} else {
										esc_html_e('Free', 'civi-framework');
									}
									?>
								</div>
								<ul class="list-group custom-scrollbar">
									<li class="list-group-item">
										<i class="fas fa-check"></i>
										<?php esc_html_e('Package live for', 'civi-framework'); ?>
										<span class="badge">
											<?php if ($enable_package_service_unlimited_time == 1) {
												esc_html_e('never expires', 'civi-framework');
											} else {
												esc_html_e($candidate_package_period . ' ' . \Civi_Candidate_Package::get_time_unit($candidate_package_time_unit));
											}
											?>
										</span>
									</li>
									<?php if (civi_get_option('enable_post_type_service') === '1') {?>
                                        <?php if(!empty($candidate_package_number_service)) : ?>
                                            <li class="list-group-item">
											<i class="fas fa-check"></i>
											<span class="badge">
												<?php if ($enable_package_service_unlimited == 1) {
													esc_html_e('Unlimited', 'civi-framework');
												} else {
													esc_html_e($candidate_package_number_service);
												} ?>
											</span>
											<?php esc_html_e('service posting', 'civi-framework'); ?>
										</li>
                                        <?php endif; ?>
                                        <?php if(!empty($candidate_package_number_service_featured)) : ?>
                                            <li class="list-group-item">
											<i class="fas fa-check"></i>
											<span class="badge">
												<?php if ($candidate_package_featured_candidate == 1) {
													esc_html_e('Unlimited', 'civi-framework');
												} else {
													esc_html_e($candidate_package_number_service_featured);
												} ?>
											</span>
											<?php esc_html_e('featured service', 'civi-framework') ?>
										</li>
                                        <?php endif; ?>
									<?php } ?>
									<?php foreach ($field_package as $field) :
										$show_field = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'show_package_' . $field, true);
										$field_number = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_number_' . $field, true);
										$field_unlimited = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'enable_package_' . $field . '_unlimited', true);
										$is_check = false;
										switch ($field) {
											case 'jobs_apply':
												$name = esc_html__('jobs applied', 'civi-framework');
												break;
											case 'jobs_wishlist':
												$name = esc_html__('jobs wishlist', 'civi-framework');
												break;
											case 'company_follow':
												$name = esc_html__(' company follow', 'civi-framework');
												break;
											case 'contact_company':
												$name = esc_html__('View company in jobs', 'civi-framework');
												$is_check = true;
												break;
											case 'info_company':
												$name = esc_html__('View information company', 'civi-framework');
												$is_check = true;
												break;
											case 'send_message':
												$name = esc_html__('Send message', 'civi-framework');
												$is_check = true;
												break;
											case 'review_and_commnent':
												$name = esc_html__('Review and commnet', 'civi-framework');
												$is_check = true;
												break;

										}
										if (intval($show_field) == 1 && !empty($field_number)) : ?>
											<li class="list-group-item"">
												<i class="fas fa-check"></i>
												<?php if ($is_check == true) { ?>
												<span class="badge">
														<?php esc_html_e($name); ?>
													</span>
												<?php } else { ?>
												<span class="badge">
													<?php if ($field_unlimited == 1) { ?>
														<?php esc_html_e('Unlimited', 'civi-framework'); ?>
													<?php } else { ?>
														<?php echo $field_number; ?>
													<?php } ?>
													</span>
												<?php echo $name; ?>
												<?php } ?>
											</li>
										<?php endif; ?>
									<?php endforeach; ?>

									<?php if ($candidate_package_additional > 0) {
										foreach ($candidate_package_additional_text as $value) { ?>
											<?php if(!empty($value)) : ?>
												<li class="list-group-item">
													<i class="fas fa-check"></i>
													<span class="badge">
														<?php esc_html_e($value); ?>
													</span>
												</li>
											<?php endif;?>
										<?php }
									} ?>
								</ul>
								<div class="civi-package-choose">
									<?php if ($user_candidate_package_id == $candidate_package_id && $d1 > $d2) { ?>
										<span class="civi-button button-block"><?php esc_html_e('Package Actived', 'civi-framework'); ?></span>
									<?php } else { ?>
										<a href="<?php echo esc_url($payment_process_link); ?>"
										class="civi-button button-outline button-block"><?php esc_html_e('Get Started', 'civi-framework'); ?></a>
									<?php } ?>
								</div>
							</div>
						</div>
						<?php
					}
				endwhile;
				wp_reset_query(); ?>
            </div>
        </div>

        <?php
    }
}
