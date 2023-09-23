<?php
if (!defined('ABSPATH')) {
	exit;
}
if (!class_exists('Civi_Service_Payment')) {
	/**
	 * Class Civi_Service_Payment
	 */
	class Civi_Service_Payment
	{
		protected $civi_order;

		/**
		 * Construct
		 */
		public function __construct()
		{
			$this->civi_order = new Civi_Service_Order();
		}

		/**
		 * service_payment service_package by stripe
		 * @param $service_id
		 */
		public function civi_stripe_payment_service_addons($service_id)
		{
			require_once(CIVI_PLUGIN_DIR . 'includes/partials/service/stripe-php/init.php');
			$service_stripe_secret_key = civi_get_option('service_stripe_secret_key');
			$service_tripe_publishable_key = civi_get_option('service_tripe_publishable_key');

			$current_user = wp_get_current_user();

			$user_id = $current_user->ID;
			$user_email = get_the_author_meta('user_email', $user_id);

			$stripe = array(
				"secret_key" => $service_stripe_secret_key,
				"publishable_key" => $service_tripe_publishable_key
			);

			\MyStripe\Stripe::setApiKey($stripe['secret_key']);
            $total_price = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'package_addons_price_total', true);
			$service_package_name = get_the_title($service_id);
            //update_user_meta($user_id, CIVI_METABOX_PREFIX . 'service_id', $service_id);


			$currency_code = civi_get_option('currency_type_default', 'USD');
            $total_price = $total_price * 100;
			$payment_completed_link = civi_get_permalink('service_payment_completed');
			$stripe_processor_link = add_query_arg(array('payment_method' => 2), $payment_completed_link);
			wp_enqueue_script('stripe-checkout');
			wp_localize_script('stripe-checkout', 'civi_stripe_vars', array(
				'civi_stripe_service_addons' => array(
					'key' => $service_tripe_publishable_key,
					'params' => array(
						'amount' => $total_price,
						'email' => $user_email,
						'currency' => $currency_code,
						'zipCode' => true,
						'billingAddress' => true,
						'name' => esc_html__('Pay with Credit Card', 'civi-framework'),
						'description' => wp_kses_post(sprintf(__('%s Package Service Payment', 'civi-framework'), $service_package_name))
					)
				)
			));
            ?>
			<form class="civi-service-stripe-form" action="<?php echo esc_url($stripe_processor_link) ?>" method="post" id="civi_stripe_service_addons">
				<button class="civi-stripe-button" style="display: none !important;"></button>
				<input type="hidden" id="service_id" name="service_id" value="<?php echo esc_attr($service_id) ?>">
				<input type="hidden" id="payment_money" name="payment_money" value="<?php echo esc_attr($total_price) ?>">
			</form>
        <?php

		}

		private function get_paypal_access_token($url, $postArgs)
		{
			$client_id = civi_get_option('service_paypal_client_id');
			$secret_key = civi_get_option('service_paypal_client_secret_key');

			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_USERPWD, $client_id . ":" . $secret_key);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);
			$response = curl_exec($curl);
			if (empty($response)) {
				die(curl_error($curl));
				curl_close($curl);
			} else {
				$info = curl_getinfo($curl);
				curl_close($curl);
				if ($info['http_code'] != 200 && $info['http_code'] != 201) {
					echo "Received error: " . $info['http_code'] . "\n";
					echo "Raw response:" . $response . "\n";
					die();
				}
			}
			$response = json_decode($response);
			return $response->access_token;
		}

		private function execute_paypal_request($url, $jsonData, $access_token)
		{
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				'Authorization: Bearer ' . $access_token,
				'Accept: application/json',
				'Content-Type: application/json'
			));

			curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
			$response = curl_exec($curl);
			if (empty($response)) {
				die(curl_error($curl));
				curl_close($curl);
			} else {
				$info = curl_getinfo($curl);
				curl_close($curl);
				if ($info['http_code'] != 200 && $info['http_code'] != 201) {
					echo "Received error: " . $info['http_code'] . "\n";
					echo "Raw response:" . $response . "\n";
					die();
				}
			}
			$jsonResponse = json_decode($response, TRUE);
			return $jsonResponse;
		}

		/**
		 * service_payment per package by Paypal
		 */
		public function civi_paypal_payment_service_addons()
		{
			check_ajax_referer('civi_service_payment_ajax_nonce', 'civi_service_security_payment');
			global $current_user;
			wp_get_current_user();
			$user_id = $current_user->ID;

			$blogInfo = esc_url(home_url());

            $service_id = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'package_addons_service_id', true);
            $service_id = intval($service_id);
            $total_price = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'package_addons_price_total', true);
            $service_name = get_the_title($service_id);

			if (empty($total_price) && empty($service_id)) {
				exit();
			}
			$currency = civi_get_option('currency_type_default');
			$payment_description = $service_name . ' ' . esc_html__('Membership payment on ', 'civi-framework') . $blogInfo;
			$is_paypal_live = civi_get_option('service_paypal_api');
			$host = 'https://api.sandbox.paypal.com';
			if ($is_paypal_live == 'live') {
				$host = 'https://api.paypal.com';
			}
			$url = $host . '/v1/oauth2/token';
			$postArgs = 'grant_type=client_credentials';
			$access_token = $this->get_paypal_access_token($url, $postArgs);
			$url = $host . '/v1/payments/payment';
			$payment_completed_link = civi_get_permalink('service_payment_completed');
			$return_url = add_query_arg(array('payment_method' => 1), $payment_completed_link);
			$dash_profile_link = civi_get_permalink('dashboard');
            update_user_meta($user_id, CIVI_METABOX_PREFIX . 'service_id', $service_id);

            $payment = array(
				'intent' => 'sale',
				"redirect_urls" => array(
					"return_url" => $return_url,
					"cancel_url" => $dash_profile_link
				),
				'payer' => array("payment_method" => "paypal"),
			);

			$payment['transactions'][0] = array(
				'amount' => array(
					'total' => $total_price,
					'currency' => $currency,
					'details' => array(
						'subtotal' => $total_price,
						'tax' => '0.00',
						'shipping' => '0.00'
					)
				),
				'description' => $payment_description
			);

			$payment['transactions'][0]['item_list']['items'][] = array(
				'quantity' => '1',
				'name' => esc_html__('Service Payment Package', 'civi-framework'),
				'price' => $total_price,
				'currency' => $currency,
				'sku' => $service_name . ' ' . esc_html__('Service Payment Package', 'civi-framework'),
			);

			$jsonEncode = json_encode($payment);
			$json_response = $this->execute_paypal_request($url, $jsonEncode, $access_token);
			$payment_approval_url = $payment_execute_url = '';
			foreach ($json_response['links'] as $link) {
				if ($link['rel'] == 'execute') {
					$payment_execute_url = $link['href'];
				} else if ($link['rel'] == 'approval_url') {
					$payment_approval_url = $link['href'];
				}
			}
			$output['payment_execute_url'] = $payment_execute_url;
			$output['access_token'] = $access_token;
			$output['service_id'] = $service_id;
			update_user_meta($user_id, CIVI_METABOX_PREFIX . 'service_paypal_transfer', $output);

			print $payment_approval_url;
			wp_die();
		}

		/**
		 * service payment by wire transfer
		 */
		public function civi_wire_transfer_service_addons()
		{
			check_ajax_referer('civi_service_payment_ajax_nonce', 'civi_service_security_payment');
			global $current_user;
			$current_user = wp_get_current_user();

			if (!is_user_logged_in()) {
				exit('No Login');
			}
			$user_id = $current_user->ID;
            $service_id = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'package_addons_service_id', true);
            $total_price = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'package_addons_price_total', true);
            $total_price = civi_get_format_money($total_price);
			$payment_method = 'Wire_Transfer';

			 //insert order
			$order_id = $this->civi_order->insert_service_order($total_price,$service_id, $user_id, $payment_method);
			$payment_completed_link = civi_get_permalink('service_payment_completed');

			$return_link = add_query_arg(array('payment_method' => 3, 'order_id' => $order_id), $payment_completed_link);
			print $return_link;
			wp_die();
		}

        /**
         * service_payment per package by Woocommerce
         */
        public function civi_woocommerce_payment_service_addons()
        {
            check_ajax_referer('civi_service_payment_ajax_nonce', 'civi_service_security_payment');
            global $current_user, $wpdb;
            wp_get_current_user();
            $user_id            = $current_user->ID;
            $service_id = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'package_addons_service_id', true);
            $service_title      = get_the_title($service_id);
            $total_price = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'package_addons_price_total', true);
            $total_price = civi_get_format_money($total_price);
            $checkout_url       = wc_get_checkout_url();
			$payment_method = 'Woocommerce';

            $query = $wpdb->prepare(
                'SELECT ID FROM ' . $wpdb->posts . '
                WHERE post_title = %s
                AND post_type = \'product\'',
                $service_title
            );
            $wpdb->query( $query );

            if ( $wpdb->num_rows ) {
                $product_id = $wpdb->get_var( $query );
            } else {
                $objProduct         = new WC_Product();

                $objProduct->set_name( $service_title );
                $objProduct->set_price($total_price);
                $objProduct->set_status("");
                $objProduct->set_catalog_visibility('hidden');
                $objProduct->set_regular_price($total_price);
                $product_id = $objProduct->save();
            }

            global $woocommerce;
            $woocommerce->cart->empty_cart();
            $woocommerce->cart->add_to_cart( $product_id );

			// insert order
			$order_id = $this->civi_order->insert_service_order($total_price, $service_id, $user_id, $payment_method);
            $url = add_query_arg( array(
                'service_id' => esc_attr($service_id),
            ), $checkout_url );

            print $url;
            wp_die();
        }

		/**
		 * service_stripe_payment_completed
		 */
		public function stripe_payment_completed()
		{
			require_once(CIVI_PLUGIN_DIR . 'includes/partials/service/stripe-php/init.php');
            global $current_user;
            $user_id = $current_user->ID;
            $service_id = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'package_addons_service_id', true);
            $service_id = intval($service_id);
            $total_price = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'package_addons_price_total', true);
			$current_user = wp_get_current_user();
			$user_id = $current_user->ID;
			$user_email = $current_user->user_email;
			$currency_code = civi_get_option('currency_type_default', 'USD');
			$payment_method = 'Stripe';
			$service_stripe_secret_key = civi_get_option('service_stripe_secret_key');
			$service_tripe_publishable_key = civi_get_option('service_tripe_publishable_key');
			$stripe = array(
				"secret_key" => $service_stripe_secret_key,
				"publishable_key" => $service_tripe_publishable_key
			);
			\MyStripe\Stripe::setApiKey($stripe['secret_key']);
            $stripeEmail = '';
			if (is_email($_POST['stripeEmail'])) {
				$stripeEmail = sanitize_email(wp_unslash($_POST['stripeEmail']));
			} else {
				wp_die('None Mail');
			}

			$paymentId = 0;
			try {
				$token = isset($_POST['stripeToken']) ? civi_clean(wp_unslash($_POST['stripeToken'])) : '';
				$customer = \MyStripe\Customer::create(array(
					"email" => $stripeEmail,
					"source" => $token
				));
                $charge = \MyStripe\Charge::create(array(
					"amount" => $total_price,
					'customer' => $customer->id,
					"currency" => $currency_code,
				));
				$payerId = $customer->id;
				if (isset($charge->id) && (!empty($charge->id))) {
					$paymentId = $charge->id;
				}
				$payment_Status = '';
				if (isset($charge->status) && (!empty($charge->status))) {
					$payment_Status = $charge->status;
				}

				if ($payment_Status == "succeeded") {
                    //service_payment Stripe service_package
                    $total_price = civi_get_format_money($total_price);
                    $this->civi_order->insert_user_service_package($user_id, $service_id);
                    $this->civi_order->insert_service_order($total_price, $service_id, $user_id, $payment_method, 'pending');
                    $args = array();
                    civi_send_email($user_email, 'mail_activated_service_package', $args);
				} else {
					$error = '<div class="alert alert-error" role="alert">' . wp_kses_post(__('<strong>Error!</strong> Transaction failed', 'civi-framework')) . '</div>';
					echo wp_kses_post($error);
				}
			} catch (Exception $e) {
				$error = '<div class="alert alert-error" role="alert"><strong>' . esc_html__('Error!', 'civi-framework') . ' </strong> ' . $e->getMessage() . '</div>';
				echo wp_kses_post($error);
			}
		}

		/**
		 * paypal_payment_completed
		 */
		public function paypal_payment_completed()
		{
			global $current_user;
			wp_get_current_user();
			$user_id = $current_user->ID;
			$user_email = $current_user->user_email;
			$allowed_html = array();
			$payment_method = 'Paypal';
            $total_price = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'package_addons_price_total', true);
            $total_price = civi_get_format_money($total_price);
			try {
				if (isset($_GET['token']) && isset($_GET['PayerID'])) {
					$payerId = wp_kses(civi_clean(wp_unslash($_GET['PayerID'])), $allowed_html);
					$paymentId = wp_kses(civi_clean(wp_unslash($_GET['paymentId'])), $allowed_html);
					$transfered_data = get_user_meta($user_id, CIVI_METABOX_PREFIX . 'service_paypal_transfer', true);
					if (empty($transfered_data)) {
						return;
					}
					$payment_execute_url = $transfered_data['payment_execute_url'];
					$token = $transfered_data['access_token'];

					$payment_execute = array(
						'payer_id' => $payerId
					);
					$json = json_encode($payment_execute);
					$json_response = $this->execute_paypal_request($payment_execute_url, $json, $token);
					delete_user_meta($user_id, CIVI_METABOX_PREFIX . 'service_paypal_transfer');
					if ($json_response['state'] == 'approved') {
                        $service_id = $transfered_data['service_id'];
                        $this->civi_order->insert_user_service_package($user_id, $service_id);
                        $this->civi_order->insert_service_order($total_price, $service_id, $user_id, $payment_method, 'pending');
                        $args = array();
                        civi_send_email($user_email, 'mail_activated_service_package', $args);
					} else {
						$error = '<div class="alert alert-error" role="alert">' . sprintf(__('<strong>Error!</strong> Transaction failed', 'civi-framework')) . '</div>';
						print $error;
					}
				}
			} catch (Exception $e) {
				$error = '<div class="alert alert-error" role="alert"><strong>Error!</strong> ' . $e->getMessage() . '</div>';
				print $error;
			}
		}
	}
}
