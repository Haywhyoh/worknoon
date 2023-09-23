<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
wp_enqueue_script(CIVI_PLUGIN_PREFIX . 'candidate-payment');
global $current_user;
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$candidate_package_id = isset($_GET['candidate_package_id']) ? civi_clean(wp_unslash($_GET['candidate_package_id'])) : '';
$user_candidate_package_id = get_the_author_meta(CIVI_METABOX_PREFIX . 'candidate_package_id', $user_id);
$civi_candidate = new Civi_candidate_package();
$check_candidate_package = $civi_candidate->user_candidate_package_available($user_id);

$candidate_package_free = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_free', true);

if ($candidate_package_free == 1) {
	$candidate_package_price = 0;
} else {
	$candidate_package_price = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_price', true);
}
$candidate_package_time_unit = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_time_unit', true);
$candidate_package_number_service = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_number_service', true);
$candidate_package_period = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_period', true);
$enable_package_service_unlimited = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'enable_package_service_unlimited', true);
$enable_package_service_unlimited_time = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'enable_package_service_unlimited_time', true);
$candidate_package_featured_candidate = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'enable_package_service_featured_unlimited', true);
$candidate_package_number_service_featured = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_number_service_featured', true);
$candidate_package_featured = get_post_meta($candidate_package_id, CIVI_METABOX_PREFIX . 'candidate_package_featured', true);
$candidate_package_title = get_the_title($candidate_package_id);
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
$terms_condition = civi_get_option('terms_condition');
$allowed_html = array(
	'a' => array(
		'href' => array(),
		'title' => array(),
		'target' => array()
	),
	'strong' => array()
);
$candidate_enable_paypal = civi_get_option('candidate_enable_paypal', 1);
$candidate_enable_stripe = civi_get_option('candidate_enable_stripe', 1);
$candidate_enable_woocheckout = civi_get_option('candidate_enable_woocheckout', 1);
$candidate_enable_wire_transfer = civi_get_option('candidate_enable_wire_transfer', 1);
$select_candidate_packages_link = civi_get_permalink('candidate_package');
$field_package = array('jobs_apply', 'jobs_wishlist', 'company_follow', 'contact_company', 'info_company', 'send_message', 'review_and_commnent');
?>

<div class="row">
	<div class="col-lg-8 col-md-7 col-sm-6">
            <?php if (($candidate_package_id == $user_candidate_package_id) && $check_candidate_package == 1) : ?>
			<div class="entry-heading">
				<h2 class="entry-title"><?php esc_html_e('Checked Package', 'civi-framework'); ?></h2>
			</div>

			<div class="alert alert-warning" role="alert"><?php echo sprintf(__('You currently have "%s" candidate package. The package hasn\'t expired yet, so you cannot buy it at this time. If you would like, you can buy another package.', 'civi-framework'), $candidate_package_title); ?></div>
		<?php else : ?>

			<?php if ($candidate_package_price > 0) : ?>
				<div class="civi-payment-method-wrap">
					<div class="entry-heading">
						<h2 class="entry-title"><?php esc_html_e('Payment Method', 'civi-framework'); ?></h2>
					</div>
					<?php if ($candidate_enable_paypal != 0) : ?>
						<div class="radio active">
							<label>
								<input type="radio" class="payment-paypal" name="civi_candidate_payment_method" value="paypal" checked>
								<img src="<?php echo esc_attr(CIVI_PLUGIN_URL . 'assets/images/paypal.png'); ?>" alt="<?php esc_html_e('Paypal', 'civi-framework'); ?>">
								<?php esc_html_e('Pay With Paypal', 'civi-framework'); ?>
							</label>
						</div>
					<?php endif; ?>

					<?php if ($candidate_enable_stripe != 0) : ?>
						<div class="radio">
							<label>
								<input type="radio" class="payment-stripe" name="civi_candidate_payment_method" value="stripe">
								<img src="<?php echo esc_attr(CIVI_PLUGIN_URL . 'assets/images/stripe.png'); ?>" alt="<?php esc_html_e('Stripe', 'civi-framework'); ?>">
								<?php esc_html_e('Pay with Credit Card', 'civi-framework'); ?>
							</label>
							<?php
							$civi_payment = new Civi_Candidate_Payment();
							$civi_payment->candidate_stripe_payment_per_package($candidate_package_id);
							?>
						</div>
					<?php endif; ?>

                    <?php if ($candidate_enable_woocheckout != 0): ?>
                        <div class="radio">
                            <label>
                                <input type="radio" class="payment-woocheckout" name="civi_candidate_payment_method" value="woocheckout">
                                <img src="<?php echo esc_attr(CIVI_PLUGIN_URL . 'assets/images/woocommerce-logo.png'); ?>" alt="<?php esc_html_e('Woocommerce', 'civi-framework'); ?>">
                                <?php esc_html_e('Pay with Woocommerce', 'civi-framework'); ?>
                            </label>
                        </div>
                    <?php endif; ?>

					<?php if ($candidate_enable_wire_transfer != 0) : ?>
						<div class="radio wire-transfer">
							<label>
								<input type="radio" name="civi_candidate_payment_method" value="wire_transfer">
								<i class="fas fa-window-restore"></i><?php esc_html_e('Wire Transfer', 'civi-framework'); ?>
							</label>
						</div>
						<div class="civi-wire-transfer-info">
							<?php
							$html_info = civi_get_option('wire_transfer_info', '');
							echo wpautop($html_info);
							?>
						</div>
					<?php endif; ?>

				</div>
			<?php endif; ?>
			<input type="hidden" name="civi_candidate_package_id" value="<?php echo esc_attr($candidate_package_id); ?>">

			<p class="terms-conditions"><i class="fa fa-hand-o-right"></i> <?php echo sprintf(wp_kses(__('Please read <a target="_blank" href="%s"><strong>Terms & Conditions</strong></a> first', 'civi-framework'), $allowed_html), get_permalink($terms_condition)); ?></p>
			<?php if ($candidate_package_price > 0) : ?>
				<button id="civi_payment_candidate_package" type="submit" class="btn btn-success btn-submit gl-button"><?php esc_html_e('Pay Now', 'civi-framework'); ?></button>
				<?php else :
				$user_free_candidate_package = get_the_author_meta(CIVI_METABOX_PREFIX . 'free_candidate_package', $user_id);
				if ($user_free_candidate_package == 'yes' && $check_candidate_package == 1) : ?>
					<div class="civi-message alert alert-warning" role="alert"><?php esc_html_e('You have already used your first free package, please choose different package.', 'civi-framework'); ?></div>
				<?php else : ?>
					<button id="civi_free_candidate_package" type="submit" class="btn btn-success btn-submit civi-button"><?php esc_html_e('Get Free Listing Package', 'civi-framework'); ?></button>
				<?php endif; ?>
			<?php endif; ?>

		<?php endif; ?>
	</div>

	<div class="col-lg-4 col-md-5 col-sm-6">
		<div class="civi-payment-for civi-package-wrap panel panel-default">
			<div class="entry-heading">
				<h2 class="entry-title"><?php esc_html_e('Selected Package', 'civi-framework'); ?></h2>
			</div>
			<div class="civi-package-item panel panel-default <?php echo esc_attr($is_featured); ?>">
				<?php if (has_post_thumbnail($candidate_package_id)) : ?>
					<div class="civi-package-thumbnail"><?php echo get_the_post_thumbnail($candidate_package_id); ?></div>
				<?php endif; ?>

				<div class="civi-package-title">
					<h2 class="entry-title"><?php echo get_the_title($candidate_package_id); ?></h2>
				</div>

				<ul class="list-group custom-scrollbar">
                    <li class="list-group-item">
                        <i class="fas fa-check"></i>
                        <?php esc_html_e('Package live for', 'civi-framework'); ?>
                        <span class="badge">
							<?php if ($enable_package_service_unlimited_time == 1) {
                                esc_html_e('never expires', 'civi-framework');
                            } else {
                                esc_html_e($candidate_package_period . ' ' . Civi_Package::get_time_unit($candidate_package_time_unit));
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
				<div class="civi-total-price">
					<span><?php esc_html_e('Total', 'civi-framework'); ?></span>
					<span class="price">
						<?php
						if ($candidate_package_price > 0) {
							echo civi_get_format_money($candidate_package_price, '', 2, true);
						} else {
							esc_html_e('Free', 'civi-framework');
						}
						?>
					</span>
				</div>
				<a class="civi-button" href="<?php echo esc_url($select_candidate_packages_link); ?>"><?php esc_html_e('Change Package', 'civi-framework'); ?></a>
			</div>
		</div>
	</div>
</div>
