<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if($job_id){
	$jobs_id = $job_id;
} else {
	$jobs_id = get_the_ID();
}

?>
<div class="block-archive-inner jobs-apply-details">
    <div class="info-apply">
        <h4><?php esc_html_e('Interested in this job?', 'civi-framework') ?></h4>
        <p class="days">
            <span> <?php echo civi_get_expiration_apply($jobs_id); ?> </span><?php esc_html_e('days left to apply', 'civi-framework') ?>
        </p>
    </div>
    <?php civi_get_status_apply($jobs_id);?>
</div>
