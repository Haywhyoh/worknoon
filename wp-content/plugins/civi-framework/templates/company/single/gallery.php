<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
wp_enqueue_style('lightgallery');
wp_enqueue_script('lightgallery');
wp_enqueue_script(CIVI_PLUGIN_PREFIX . 'light-gallery');

$id = get_the_ID();
$company_gallery     = get_post_meta(get_the_ID(), CIVI_METABOX_PREFIX . 'company_images', true);
$attach_id         = get_post_thumbnail_id();
$show = 3;
?>
<?php if (!empty($company_gallery)) : ?>
    <div class="block-archive-inner company-gallery-details">
        <h4 class="title-company"><?php esc_html_e('Photos', 'civi-framework') ?></h4>
        <div class="entry-company-element">
            <div class="single-company-thumbs enable civi-light-gallery">
                <?php
                $slick_attributes = array(
                    '"slidesToShow": ' . $show,
                    '"slidesToScroll": 1',
                    '"dots": true',
                    '"autoplay": false',
                    '"autoplaySpeed": 5000',
                    '"responsive": [{ "breakpoint": 479, "settings": {"slidesToShow": 1} },{ "breakpoint": 768, "settings": {"slidesToShow": 2}} ]'
                );
                $wrapper_attributes[] = "data-slick='{" . implode(', ', $slick_attributes) . "}'";
                ?>
                <div class="civi-slick-carousel slick-nav" <?php echo implode(' ', $wrapper_attributes); ?>>
                    <?php
                    $jobj_company_gallery = explode('|', $company_gallery);
                    $count = count($jobj_company_gallery);
                    foreach ($jobj_company_gallery as $key => $image) :
                        if ($image) {
                            $image_full_src = wp_get_attachment_image_src($image, 'full');
                            if (isset($image_full_src[0])) {
                                $thumb_src      = $image_full_src[0];
                            }
                        }
                        if (!empty($thumb_src)) {
                    ?>
                            <figure>
                                <a href="<?php echo esc_url($thumb_src); ?>" class="lgbox">
                                    <img src="<?php echo esc_url($thumb_src); ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>">
                                </a>
                            </figure>
                        <?php } ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>