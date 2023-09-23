<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('Civi_Candidate_Order')) {
    /**
     * Class Civi_Candidate_Order
     */
    class Civi_Candidate_Order
    {
        /**
         * Get total my candidate_order
         * @return int
         */
        public function get_total_my_candidate_order()
        {
            $args = array(
                'post_type' => 'candidate_order',
                'meta_query' => array(
                    array(
                        'key' => CIVI_METABOX_PREFIX . 'candidate_order_user_id',
                        'value' => get_current_user_id(),
                        'compare' => '='
                    )
                )
            );
            $candidate_orders = new WP_Query($args);
            wp_reset_postdata();
            return $candidate_orders->found_posts;
        }

        /**
         * Insert candidate_order
         * @param $payment_type
         * @param $item_id
         * @param $user_id
         * @param $payment_for
         * @param $payment_method
         * @param int $paid
         * @param string $payment_id
         * @param string $payer_id
         * @return int|WP_Error
         */
        public function insert_candidate_order($payment_type, $item_id, $user_id, $payment_for, $payment_method, $paid = 0, $payment_id = '', $payer_id = '')
        {
            $package_free = get_post_meta($item_id, CIVI_METABOX_PREFIX . 'candidate_package_free', true);
            if ($package_free == 1) {
                $total_money = 0;
            } else {
                $total_money = get_post_meta($item_id, CIVI_METABOX_PREFIX . 'candidate_package_price', true);
            }
            $time = time();
            $candidate_order_date = date('Y-m-d', $time);

            $civi_meta = array();
            $civi_meta['candidate_order_item_id'] = $item_id;
            $civi_meta['candidate_order_item_price'] = $total_money;
            $civi_meta['candidate_order_purchase_date'] = $candidate_order_date;
            $civi_meta['candidate_order_user_id'] = $user_id;
            $civi_meta['candidate_order_payment_method'] = $payment_method;
            $civi_meta['trans_payment_id'] = $payment_id;
            $civi_meta['trans_payer_id'] = $payer_id;
            $posttitle = 'Order_' . $payment_method . '_' . $total_money . $user_id;
                $args = array(
                'post_title'    => $posttitle,
                'post_status'    => 'publish',
                'post_type'     => 'candidate_order'
            );

            $rw = get_page_by_title($posttitle, OBJECT, 'candidate_order');
            $candidate_order_payment_status = get_post_meta($rw->ID, CIVI_METABOX_PREFIX . 'candidate_order_payment_status', true);

            $civi_candidate_package = new civi_candidate_package();
            $civi_candidate_package->insert_user_candidate_package($user_id, $item_id);

            if (empty($rw->ID) || ($rw->ID && $candidate_order_payment_status == '1')) {
                $candidate_order_id =  wp_insert_post($args);
                update_post_meta($candidate_order_id, CIVI_METABOX_PREFIX . 'candidate_order_user_id', $user_id);
                update_post_meta($candidate_order_id, CIVI_METABOX_PREFIX . 'candidate_order_item_id', $item_id);
                update_post_meta($candidate_order_id, CIVI_METABOX_PREFIX . 'candidate_order_price', $total_money);
                update_post_meta($candidate_order_id, CIVI_METABOX_PREFIX . 'candidate_order_date', $candidate_order_date);
                update_post_meta($candidate_order_id, CIVI_METABOX_PREFIX . 'candidate_order_payment_method', $payment_method);
                update_post_meta($candidate_order_id, CIVI_METABOX_PREFIX . 'candidate_order_payment_status', $paid);

                update_post_meta($candidate_order_id, CIVI_METABOX_PREFIX . 'trans_payment_id', $payment_id);
                update_post_meta($candidate_order_id, CIVI_METABOX_PREFIX . 'trans_payer_id', $payer_id);

                update_post_meta($candidate_order_id, CIVI_METABOX_PREFIX . 'candidate_order_meta', $civi_meta);
                $update_post = array(
                    'ID'         => $candidate_order_id,
                );
                wp_update_post($update_post);
            } else {
                $candidate_order_id = $rw->ID;
            }
            return $candidate_order_id;
        }

        /**
         * get_candidate_order_meta
         * @param $post_id
         * @param bool|false $field
         * @return array|bool|mixed
         */
        public function get_candidate_order_meta($post_id, $field = false)
        {
            $defaults = array(
                'candidate_order_item_id' => '',
                'candidate_order_item_price' => '',
                'candidate_order_purchase_date' => '',
                'candidate_order_user_id' => '',
                'candidate_order_payment_method' => '',
                'trans_payment_id' => '',
                'trans_payer_id' => '',
            );
            $meta = get_post_meta($post_id, CIVI_METABOX_PREFIX . 'candidate_order_meta', true);
            $meta = wp_parse_args((array)$meta, $defaults);

            if ($field) {
                if (isset($meta[$field])) {
                    return $meta[$field];
                } else {
                    return false;
                }
            }
            return $meta;
        }

        /**
         * @param $payment_method
         * @return string
         */
        public static function get_candidate_order_payment_method($payment_method)
        {
            switch ($payment_method) {
                case 'Paypal':
                    return esc_html__('Paypal', 'civi-framework');
                    break;
                case 'Stripe':
                    return esc_html__('Stripe', 'civi-framework');
                    break;
                case 'Wire_Transfer':
                    return esc_html__('Wire Transfer', 'civi-framework');
                    break;
                case 'Free_Package':
                    return esc_html__('Free Package', 'civi-framework');
                    break;
                case 'Woocommerce':
                    return esc_html__('Woocommerce', 'civi-framework');
                    break;
                default:
                    return '';
            }
        }
        /**
         * Print candidate_order
         */
        public function candidate_order_print_ajax()
        {
            if (!isset($_POST['candidate_order_id']) || !is_numeric($_POST['candidate_order_id'])) {
                return;
            }
            $candidate_order_id = absint(wp_unslash($_POST['candidate_order_id']));
            $isRTL = 'false';
            if (isset($_POST['isRTL'])) {
                $isRTL = $_POST['isRTL'];
            }
            civi_get_template('candidate_order/candidate_order-print.php', array('candidate_order_id' => intval($candidate_order_id), 'isRTL' => $isRTL));
            wp_die();
        }
    }
}
