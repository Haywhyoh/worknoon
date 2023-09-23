<?php

namespace Civi_Elementor;

defined('ABSPATH') || exit;

class Widget_Account extends Base
{

	public function get_name()
	{
		return 'civi-account';
	}

	public function get_title()
	{
		return esc_html__('Account', 'civi');
	}

	public function get_icon_part()
	{
		return 'eicon-my-account';
	}

	public function get_keywords()
	{
		return ['modern', 'account'];
	}

	protected function register_controls()
	{
		$this->add_account_section();
	}

	private function add_account_section()
	{
		$this->start_controls_section('account_section', [
			'label' => esc_html__('Account', 'civi'),
		]);

		$this->end_controls_section();
	}

	protected function render()
	{
		if (is_user_logged_in()) {
			$accent_color = \Civi_Helper::get_setting('accent_color');
			$secondary_color = \Civi_Helper::get_setting('secondary_color');
			$enable_service = \Civi_Helper::civi_get_option('enable_post_type_service');
			$currency_sign_default = \Civi_Helper::civi_get_option('currency_sign_default');
			$currency_position = \Civi_Helper::civi_get_option('currency_position');

			$current_user = wp_get_current_user();
			$user_name = $current_user->display_name;
			$user_id = $current_user->ID;
			$user_link = get_edit_user_link($current_user->ID);
			$avatar_url = get_avatar_url($current_user->ID);
			$author_avatar_image_url = get_the_author_meta(
				"author_avatar_image_url",
				$current_user->ID
			);
			$author_avatar_image_id = get_the_author_meta(
				"author_avatar_image_id",
				$current_user->ID
			);
			if (!empty($author_avatar_image_url)) {
				$avatar_url = $author_avatar_image_url;
			}
			$enable_post_type_service = civi_get_option('enable_post_type_service');
			$current_user = wp_get_current_user();
			$key_employer = [
				"dashboard" => esc_html__('Dashboard', 'civi-framework'),
				"jobs_dashboard" => esc_html__('Jobs', 'civi-framework'),
				"applicants" => esc_html__('Applicants', 'civi-framework'),
				"candidates" => esc_html__('Candidates', 'civi-framework'),
				"user_package" => esc_html__('Package', 'civi-framework'),
				"messages" => esc_html__('Messages', 'civi-framework'),
				"meetings" => esc_html__('Meetings', 'civi-framework'),
				"company" => esc_html__('Company', 'civi-framework'),
				"settings" => esc_html__('Settings', 'civi-framework'),
				"logout" => esc_html__('Logout', 'civi-framework'),
			];

			$key_candidate = [
				"candidate_dashboard" => esc_html__('Dashboard', 'civi-framework'),
				"candidate_profile" => esc_html__('Profile', 'civi-framework'),
				"my_jobs" => esc_html__('My jobs', 'civi-framework'),
				"candidate_reviews" => esc_html__('My Reviews', 'civi-framework'),
				"candidate_company" => esc_html__('My Following', 'civi-framework'),
				"candidate_messages" => esc_html__('Messages', 'civi-framework'),
				"candidate_meetings" => esc_html__('Meetings', 'civi-framework'),
			];

			if ($enable_post_type_service === '1') {
				$key_candidate["my_service"] = esc_html__('Services', 'civi-framework');
			}
			$key_candidate["candidate_settings"] = esc_html__('Settings', 'civi-framework');
			$key_candidate["candidate_logout"] = esc_html__('Logout', 'civi-framework');
?>
			<div class="account logged-in">
				<?php if ($avatar_url) : ?>
					<div class="user-show">
						<a class="avatar" href="#">
							<img src="<?php echo esc_url(
											$avatar_url
										); ?>" title="<?php echo esc_attr(
															$user_name
														); ?>" alt="<?php echo esc_attr($user_name); ?>">
							<span>
								<?php esc_html_e($user_name); ?>
								<?php if ($enable_service === '1' && in_array("civi_user_candidate", (array)$current_user->roles)) {
									$total_price = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'service_withdraw_total_price', true);
									if (empty($total_price)) {
										$total_price = 0;
									}
									if ($currency_position == 'before') {
										$total_price = $currency_sign_default . $total_price;
									} else {
										$total_price = $total_price . $currency_sign_default;
									}
									echo '<span class="price">(' . $total_price . ')</span>';
								} ?>
							</span>
							<i class="far fa-chevron-down"></i>
						</a>
					</div>
				<?php endif; ?>
				<?php if (
					in_array("civi_user_candidate", (array)$current_user->roles) ||
					in_array("civi_user_employer", (array)$current_user->roles)
				) : ?>
					<div class="user-control civi-nav-dashboard" data-secondary="<?php echo $secondary_color; ?>" data-accent="<?php echo $accent_color; ?>">
						<div class="inner-control nav-dashboard">
							<ul class="list-nav-dashboard">
								<?php if (in_array("civi_user_employer", (array)$current_user->roles)) :
									foreach ($key_employer as $key => $value) {
										$show_employer = civi_get_option("show_employer_" . $key, "1");
										$image_employer = civi_get_option("image_employer_" . $key);
										$id = civi_get_option("civi_" . $key . "_page_id");
								?>
										<?php if ($show_employer) : ?>
											<li class="nav-item <?php if (is_page($id) && $key !== "logout") :
																	echo esc_attr("active");
																endif; ?>">
												<?php if ($key === "logout") { ?>
													<a href="<?php echo wp_logout_url(home_url()); ?>">
													<?php } else { ?>
														<a href="<?php echo get_permalink($id); ?>" class="civi-icon-items">
														<?php } ?>
														<?php if (!empty($image_employer["url"])) : ?>
															<span class="image">
																<?php if (civi_get_option('type_icon_employer') === 'svg') { ?>
																	<object class="civi-svg" type="image/svg+xml" data="<?php echo esc_url($image_employer['url']) ?>"></object>
																<?php } else { ?>
																	<img src="<?php echo esc_url($image_employer['url']) ?>" alt="<?php echo $value; ?>" />
																<?php } ?>
															</span>
														<?php endif; ?>
														<span><?php esc_html_e($value) ?></span>
														<?php if ($key === "messages") { ?>
															<?php civi_get_total_unread_message(); ?>
														<?php } ?>
														</a>
											</li>
										<?php endif; ?>
									<?php
									} ?>
									<?php
								elseif (in_array("civi_user_candidate", (array)$current_user->roles)) :

									foreach ($key_candidate as $key => $value) :

										$show_candidate = civi_get_option('show_' . $key, '1');

										if (!$show_candidate) {
											continue;
										}

										$id = civi_get_option("civi_" . $key . "_page_id");
										$image_candidate = civi_get_option("image_" . $key, "");

										$class_active = (is_page($id) && $key !== "candidate_logout") ? 'active' : '';

										$link_url = '';
										$link_url = $key === "candidate_logout" ? wp_logout_url(home_url()) : get_permalink($id);

										$html_icon = '';
										if (!empty($image_candidate['url'])) {
											if (civi_get_option("type_icon_candidate") === "svg") {
												$html_icon =
													'<object class="civi-svg" type="image/svg+xml" data="' .
													esc_url($image_candidate["url"]) .
													'"></object>';
											} else {
												$html_icon =
													'<img src="' .
													esc_url($image_candidate["url"]) .
													'" alt="' .
													$value .
													'"/>';
											}
										}
									?>
										<li class="nav-item <?php esc_html_e($class_active) ?>">
											<a href="<?php echo esc_url($link_url) ?>">
												<?php if (!empty($image_candidate["url"])) { ?>
													<span class="image">
														<?php echo $html_icon; ?>
													</span>
												<?php } ?>
												<span><?php esc_html_e($value); ?></span>
												<?php if ($key === "candidate_messages") { ?>
													<?php civi_get_total_unread_message(); ?>
												<?php } ?>
											</a>
										</li>

									<?php endforeach; ?>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php
		} else {
		?>
			<div class="account logged-out">
				<a href="#popup-form" class="btn-login"><?php esc_html_e("Login", "civi"); ?></a>
			</div>
		<?php
		} ?>
<?php
	}
}
