<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('Civi_Profile')) {

	/**
	 * Class Civi_Profile
	 */
	class Civi_Profile
	{

		public function custom_user_profile_fields($user)
		{
            $enable_paypal = civi_get_option('enable_payout_paypal');
            $enable_stripe = civi_get_option('enable_payout_stripe');
            $enable_bank = civi_get_option('enable_payout_bank_transfer');
            $enable_status = civi_get_option('enable_status_user');
            $custom_payout = civi_get_option('custom_payout_setting');
		    ?>
			<h3><?php esc_html_e('Profile Info', 'civi-framework'); ?></h3>
			<table class="form-table">
				<tbody>
					<tr class="author-avatar-image-wrap">
						<th><label for="author_avatar_image_url"><?php echo esc_html__('Avatar', 'civi-framework'); ?></label></th>
						<td>
							<img class="show_author_avatar_image_url" src="<?php echo esc_attr(get_the_author_meta('author_avatar_image_url', $user->ID)); ?>" style="width: 96px;height: 96px; object-fit: cover;display: block;margin-bottom: 10px;">
							<input type="text" name="author_avatar_image_url" id="author_avatar_image_url" value="<?php echo esc_attr(get_the_author_meta('author_avatar_image_url', $user->ID)); ?>" style="display: block;margin-bottom: 10px;max-width: 350px;width: 100%;">
							<input type="hidden" name="author_avatar_image_id" id="author_avatar_image_id" value="<?php echo esc_attr(get_the_author_meta('author_avatar_image_id', $user->ID)); ?>">
							<input type='button' class="button-primary" value="Upload Image" id="uploadimage" />
						</td>
					</tr>
					<tr class="author-phone-number-wrap">
						<th><label for="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'author_mobile_number'); ?>"><?php echo esc_html__('Phone', 'civi-framework'); ?></label></th>
						<td><input type="text" name="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'author_mobile_number'); ?>" id="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'author_mobile_number'); ?>" value="<?php echo esc_attr(get_the_author_meta(CIVI_METABOX_PREFIX . 'author_mobile_number', $user->ID)); ?>" class="regular-text"></td>
					</tr>
                    <?php if($enable_paypal === '1') : ?>
                        <tr class="author-payout-paypal-wrap">
                            <th><label for="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'author_payout_paypal'); ?>"><?php echo esc_html__('Paypal email', 'civi-framework'); ?></label></th>
                            <td><input type="text" name="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'author_payout_paypal'); ?>" id="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'author_payout_paypal'); ?>" value="<?php echo esc_attr(get_the_author_meta(CIVI_METABOX_PREFIX . 'author_payout_paypal', $user->ID)); ?>" class="regular-text"></td>
                        </tr>
                    <?php endif; ?>
                    <?php if($enable_stripe === '1') : ?>
                        <tr class="author-payout-stripe-wrap">
                            <th><label for="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'author_payout_stripe'); ?>"><?php echo esc_html__('Stripe account', 'civi-framework'); ?></label></th>
                            <td><input type="text" name="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'author_payout_stripe'); ?>" id="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'author_payout_stripe'); ?>" value="<?php echo esc_attr(get_the_author_meta(CIVI_METABOX_PREFIX . 'author_payout_stripe', $user->ID)); ?>" class="regular-text"></td>
                        </tr>
                    <?php endif; ?>
                    <?php if($enable_bank === '1') : ?>
                        <tr class="author-payout-card-number-wrap">
                            <th><label for="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'author_payout_card_number'); ?>"><?php echo esc_html__('Card Number (Bank Transfer)', 'civi-framework'); ?></label></th>
                            <td><input type="text" name="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'author_payout_card_number'); ?>" id="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'author_payout_card_number'); ?>" value="<?php echo esc_attr(get_the_author_meta(CIVI_METABOX_PREFIX . 'author_payout_card_number', $user->ID)); ?>" class="regular-text"></td>
                        </tr>
                        <tr class="author-payout-card-name-wrap">
                            <th><label for="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'author_payout_card_name'); ?>"><?php echo esc_html__('Card Name (Bank Transfer)', 'civi-framework'); ?></label></th>
                            <td><input type="text" name="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'author_payout_card_name'); ?>" id="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'author_payout_card_name'); ?>" value="<?php echo esc_attr(get_the_author_meta(CIVI_METABOX_PREFIX . 'author_payout_card_name', $user->ID)); ?>" class="regular-text"></td>
                        </tr>
                        <tr class="author-payout-bank-transfer-name-wrap">
                            <th><label for="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'author_payout_bank_transfer_name'); ?>"><?php echo esc_html__('Bank Name (Bank Transfer)', 'civi-framework'); ?></label></th>
                            <td><input type="text" name="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'author_payout_bank_transfer_name'); ?>" id="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'author_payout_bank_transfer_name'); ?>" value="<?php echo esc_attr(get_the_author_meta(CIVI_METABOX_PREFIX . 'author_payout_bank_transfer_name', $user->ID)); ?>" class="regular-text"></td>
                        </tr>
                    <?php endif;
                    if(!empty($custom_payout)) :
                        foreach ($custom_payout as $field) :
                            if(!empty($field['name'])) : ?>
                            <tr class="author-payout-cusom">
                                <th><label for="<?php echo esc_attr($field['id']); ?>"><?php echo sprintf(__('%1s (%2s)', 'civi-framework'), $field['label'],$field['name']); ?></label></th>
                                <td><input type="text" name="<?php echo esc_attr($field['id']); ?>" id="<?php echo esc_attr($field['id']); ?>" value="<?php echo esc_attr(get_the_author_meta(CIVI_METABOX_PREFIX . 'author_payout_custom_' . $field['id'], $user->ID)); ?>" class="regular-text"></td>
                            </tr>
                        <?php endif;
                        endforeach;
                    endif; ?>
                    <?php if($enable_status === '1') : ?>
                        <tr class="author-user-demo-wrap">
                            <?php $user_selected = get_the_author_meta(CIVI_METABOX_PREFIX . 'user_status', $user->ID); ?>
                            <th><label for="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'user_status'); ?>"><?php echo esc_html__('User Status', 'civi-framework'); ?></label></th>
                            <td>
                                <select name="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'user_status'); ?>">
                                    <option <?php if($user_selected == 'pending'){?> selected <?php } ?> value="pending"><?php esc_html_e('Pending', 'civi-framework'); ?></option>
                                    <option <?php if($user_selected == 'approve'){?> selected <?php } ?> value="approve"><?php esc_html_e('Approve', 'civi-framework'); ?></option>
                                </select>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr class="author-user-demo-wrap">
                        <?php $user_selected = get_the_author_meta(CIVI_METABOX_PREFIX . 'user_demo', $user->ID); ?>
                        <th><label for="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'user_demo'); ?>"><?php echo esc_html__('User Demo', 'civi-framework'); ?></label></th>
                        <td>
                            <select name="<?php echo esc_attr(CIVI_METABOX_PREFIX . 'user_demo'); ?>">
                                <option <?php if($user_selected == ''){?> selected <?php } ?> value=""><?php esc_html_e('No', 'civi-framework'); ?></option>
                                <option <?php if($user_selected == 'yes'){?> selected <?php } ?> value="yes"><?php esc_html_e('Yes', 'civi-framework'); ?></option>
                            </select>
                        </td>
                    </tr>
				</tbody>
			</table>
		<?php
		}

        public function user_package_available($user_id)
        {
            $args_invoice = array(
                'post_type'           => 'invoice',
                'posts_per_page'      => 1,
                'author'              => $user_id,
            );
            $data_invoice = new WP_Query($args_invoice);
            $invoice_status = '1';
            if (!empty($data_invoice->post)){
                $invoice_id = $data_invoice->post->ID;
                $invoice_status = get_post_meta($invoice_id, CIVI_METABOX_PREFIX . 'invoice_payment_status', true);
            }

            $package_id = get_the_author_meta(CIVI_METABOX_PREFIX . 'package_id', $user_id);
            if (empty($package_id) || $invoice_status === '0') {
                return 0;
            } else {
                $civi_package = new Civi_Package();
                $package_unlimited_time = get_post_meta($package_id, CIVI_METABOX_PREFIX . 'package_unlimited_time', true);
                if ($package_unlimited_time == 0) {
                    $expired_date = $civi_package->get_expired_time($package_id, $user_id);
                    $today = time();
                    if ($today > $expired_date) {
                        return -1;
                    }
                }
            }
            return 1;
        }

		public function update_custom_user_profile_fields($user_id)
		{
			global $current_user;
			wp_get_current_user();

			if (current_user_can('edit_user', $user_id)) {

				$author_avatar_image_url = isset($_POST['author_avatar_image_url']) ? civi_clean(wp_unslash($_POST['author_avatar_image_url'])) : '';
				$author_avatar_image_id  = isset($_POST['author_avatar_image_id']) ? civi_clean(wp_unslash($_POST['author_avatar_image_id'])) : '';
				$author_mobile_number    = isset($_POST[CIVI_METABOX_PREFIX . 'author_mobile_number']) ? civi_clean(wp_unslash($_POST[CIVI_METABOX_PREFIX . 'author_mobile_number'])) : '';
                $author_payout_paypal   = isset($_POST[CIVI_METABOX_PREFIX . 'author_payout_paypal']) ? civi_clean(wp_unslash($_POST[CIVI_METABOX_PREFIX . 'author_payout_paypal'])) : '';
                $author_payout_stripe   = isset($_POST[CIVI_METABOX_PREFIX . 'author_payout_stripe']) ? civi_clean(wp_unslash($_POST[CIVI_METABOX_PREFIX . 'author_payout_stripe'])) : '';
                $author_payout_card_number    = isset($_POST[CIVI_METABOX_PREFIX . 'author_payout_card_number']) ? civi_clean(wp_unslash($_POST[CIVI_METABOX_PREFIX . 'author_payout_card_number'])) : '';
                $author_payout_card_name   = isset($_POST[CIVI_METABOX_PREFIX . 'author_payout_card_name']) ? civi_clean(wp_unslash($_POST[CIVI_METABOX_PREFIX . 'author_payout_card_name'])) : '';
                $author_payout_bank_transfer_name  = isset($_POST[CIVI_METABOX_PREFIX . 'author_payout_bank_transfer_name']) ? civi_clean(wp_unslash($_POST[CIVI_METABOX_PREFIX . 'author_payout_bank_transfer_name'])) : '';
                $user_demo    = isset($_POST[CIVI_METABOX_PREFIX . 'user_demo']) ? civi_clean(wp_unslash($_POST[CIVI_METABOX_PREFIX . 'user_demo'])) : '';
                $user_status    = isset($_POST[CIVI_METABOX_PREFIX . 'user_status']) ? civi_clean(wp_unslash($_POST[CIVI_METABOX_PREFIX . 'user_status'])) : '';


                update_user_meta($user_id, 'author_avatar_image_url', $author_avatar_image_url);
				update_user_meta($user_id, 'author_avatar_image_id', $author_avatar_image_id);
				update_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_mobile_number', $author_mobile_number);
                update_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_paypal', $author_payout_paypal);
                update_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_stripe', $author_payout_stripe);
                update_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_card_number', $author_payout_card_number);
                update_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_card_name', $author_payout_card_name);
                update_user_meta($user_id, CIVI_METABOX_PREFIX . 'author_payout_bank_transfer_name', $author_payout_bank_transfer_name);
                update_user_meta($user_id, CIVI_METABOX_PREFIX . 'user_demo', $user_demo);
                update_user_meta($user_id, CIVI_METABOX_PREFIX . 'user_status', $user_status);
			}
		}

		function my_profile_upload_js()
		{
			wp_enqueue_media();
		?>
			<script type="text/javascript">
				jQuery(document).ready(function() {

					jQuery(document).find("input[id^='uploadimage']").on('click', function(e) {
						e.preventDefault();

						var button = jQuery(this),
							custom_uploader = wp.media({
								title: 'Insert image',
								library: {
									// uncomment the next line if you want to attach image to the current post
									// uploadedTo : wp.media.view.settings.post.id, 
									type: 'image'
								},
								button: {
									text: 'Use this image' // button label text
								},
								multiple: false // for multiple image selection set to true
							}).on('select', function() { // it also has "open" and "close" events 
								var attachment = custom_uploader.state().get('selection').first().toJSON();
								jQuery(button).removeClass('button').html('<img class="true_pre_image" src="' + attachment.url + '" style="max-width:95%;display:block;" />').next().val(attachment.id).next().show();
								jQuery('#author_avatar_image_url').val(attachment.url);
								jQuery('#author_avatar_image_id').val(attachment.id);
								jQuery('.show_author_avatar_image_url').attr('src', attachment.url);
							})
							.open();
					});
				});
			</script>
<?php
		}
	}
}
