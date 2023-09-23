<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="entry-my-page settings-dashboard">
    <div class="entry-title">
        <h4><?php esc_html_e('Settings', 'civi-framework') ?></h4>
    </div>
    <div class="tab-dashboard">
        <ul class="tab-list">
            <li class="tab-item tab-profile"><a href="#tab-profile"><?php esc_html_e('Personal info', 'civi-framework'); ?></a></li>
            <li class="tab-item tab-payout"><a href="#tab-payout"><?php esc_html_e('Payout', 'civi-framework'); ?></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-info" id="tab-profile">
                <?php civi_get_template('dashboard/employer/settings/profile.php'); ?>
            </div>
            <div class="tab-info" id="tab-payout">
                <?php civi_get_template('dashboard/payout/payout.php'); ?>
            </div>
        </div>
    </div>
</div>