<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$content = get_post_field('post_content', $candidate_id);
if (isset($content) && !empty($content)) : ?>
    <div class="block-archive-inner candidate-overview-details">
        <h4 class="title-candidate"><?php esc_html_e('About me', 'civi-framework') ?></h4>
        <?php echo $content; ?>
    </div>
<?php endif;
