<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$civi_candidate_payment = new Civi_candidate_payment();
$candidate_payment_method = isset($_GET['payment_method']) ? absint(wp_unslash($_GET['payment_method'])) : -1;
if ($candidate_payment_method == 1) {
    $civi_candidate_payment->paypal_payment_completed();
} elseif ($candidate_payment_method == 2) {
    $civi_candidate_payment->stripe_payment_completed();
}
?>
<div class="civi-payment-completed-wrap">
    <div class="inner-payment-completed">
        <?php
        do_action('civi_before_candidate_payment_completed');
        if (isset($_GET['order_id']) && $_GET['order_id'] != '') :
            $order_id = absint(wp_unslash($_GET['order_id']));
            $civi_candidate_order = new Civi_candidate_order();
            $order_meta = $civi_candidate_order->get_candidate_order_meta($order_id);
            $service_wire_transfer_card_number = civi_get_option('service_wire_transfer_card_number', '');
            $service_wire_transfer_card_name = civi_get_option('service_wire_transfer_card_name', '');
            $service_wire_transfer_bank_name = civi_get_option('service_wire_transfer_bank_name', '');
            ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><?php esc_html_e('Thank you for your purchase!', 'civi-framework'); ?></h2>
                </div>
                <p><?php esc_html_e('Please transfer to our account number with the "Order Number" and wait for us to confirm.', 'civi-framework'); ?></p>

                <?php if ($service_wire_transfer_card_number || $service_wire_transfer_card_name || $service_wire_transfer_bank_name) : ?>
                    <div class="card-info">
                        <table>
                            <tr>
                                <th><?php esc_html_e('Card Number', 'civi-framework'); ?></th>
                                <td><?php esc_html_e($service_wire_transfer_card_number); ?></td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('Card Name', 'civi-framework'); ?></th>
                                <td><?php esc_html_e($service_wire_transfer_card_name); ?></td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('Bank Name', 'civi-framework'); ?></th>
                                <td><?php esc_html_e($service_wire_transfer_bank_name); ?></td>
                            </tr>
                        </table>
                    </div>
                <?php endif; ?>

                <div class="entry-title">
                    <h3><?php esc_html_e('Order Detail', 'civi-framework'); ?></h3>
                </div>
                <ul class="list-group">
                    <li class="list-group-item">
                        <span><?php esc_html_e('Order Number', 'civi-framework'); ?></span>
                        <strong class="pull-right"><?php esc_html_e($order_id); ?></strong>
                    </li>
                    <li class="list-group-item">
                        <span><?php esc_html_e('Date', 'civi-framework'); ?></span>
                        <strong class="pull-right"><?php echo get_the_date('', $order_id); ?></strong>
                    </li>
                    <li class="list-group-item">
                        <span><?php esc_html_e('Payment Method', 'civi-framework'); ?></span>
                        <strong class="pull-right">
                            <?php echo Civi_candidate_order::get_candidate_order_payment_method($order_meta['candidate_order_payment_method']);  ?>
                        </strong>
                    </li>
                    <li class="list-group-item">
                        <span><?php esc_html_e('Total', 'civi-framework'); ?></span>
                        <strong class="pull-right"><?php echo civi_get_format_money($order_meta['candidate_order_item_price']); ?></strong>
                    </li>
                </ul>
            </div>
            <a href="<?php echo civi_get_permalink('candidate_dashboard'); ?>" class="civi-button"><?php esc_html_e('Go to Dashboard', 'civi-framework'); ?></a>
        <?php else : ?>
            <div class="civi-heading">
                <h2><?php esc_html_e('Thank you for your purchase', 'civi-framework'); ?></h2>
            </div>
            <div class="civi-thankyou-content">
                <?php $html_info = esc_html_e('I wanted to take a moment to express my heartfelt gratitude for the wonderful package you sent me. It truly brightened up my day and brought a smile to my face.', 'civi-framework');
                echo wpautop($html_info); ?>
            </div>
            <a href="<?php echo civi_get_permalink('candidate_dashboard'); ?>" class="civi-button"> <?php esc_html_e('Go to Dashboard', 'civi-framework'); ?> </a>
        <?php endif;
        do_action('civi_after_candidate_payment_completed');
        ?>
    </div>
</div>