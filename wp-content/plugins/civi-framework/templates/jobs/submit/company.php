<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
global $hide_company_fields;
$image_max_file_size = civi_get_option('civi_image_max_file_size', '1000kb');
civi_get_avatar_enqueue();
?>

<div id="select_company_div" class="row">
    <div class="form-group col-md-6">
        <label><?php esc_html_e('Select your company', 'civi-framework') ?></label>
            <div class="select2-field">
				<select name="jobs_select_company" class="civi-select2">
					<?php civi_select_post_company(true); ?>
				</select>
			</div>
    </div>
    <div class="form-group col-md-6 mt-2 pl-3">
        <a id="create_company_btn" href="javascript:void(0);" style="background-color: #FF3131; border: none;" class="btn-submit-jobs civi-button">
            <i class="far fa-plus-circle"></i>
            Create new company
        </a>
    </div>
</div>

<div id="select_existing_company_btn" class="row d-none mb-3">
    <a href="javascript:void(0);" class="civi-button button-link text-danger mt-0">
        <i class="fa fa-arrow-left m-1"></i>
        OR select an existing company
    </a>
</div>


<div id="new_company_job_form" class="row d-none">
    <?php if (!in_array('fields_company_name', $hide_company_fields)) : ?>
        <div class="form-group col-md-6">
            <label for="company_title"><?php esc_html_e('Company name', 'civi-framework') ?> <sup>*</sup></label>
            <input type="text" id="company_title" name="company_title"
                   placeholder="<?php esc_attr_e('Name', 'civi-framework') ?>">
        </div>
    <?php endif; ?>
    <?php if (!in_array('fields_company_category', $hide_company_fields)) : ?>
        <div class="form-group col-md-6">
            <label><?php esc_html_e('Categories', 'civi-framework') ?> <sup>*</sup></label>
            <div class="select2-field">
				<select name="company_categories" class="civi-select2">
					<?php civi_get_taxonomy('company-categories', false, true); ?>
				</select>
			</div>
        </div>
    <?php endif; ?>
    <?php if (!in_array('fields_company_url', $hide_company_fields)) : ?>
        <div class="form-group col-md-12">
            <label><?php esc_html_e('Company Url Slug', 'civi-framework') ?></label>
            <div class="company-url-warp">
                <input class="input-url" type="text"
                       placeholder="<?php echo esc_url(get_post_type_archive_link('company')) ?>" disabled>
                <input class="input-slug" type="text" id="company_url" name="company_url"
                       placeholder="<?php esc_attr_e('company-name', 'civi-framework') ?>">
            </div>
        </div>
    <?php endif; ?>
    <?php if (!in_array('fields_company_about', $hide_company_fields)) : ?>
        <div class="form-group col-md-12">
            <label class="label-des-company"><?php esc_html_e('About company', 'civi-framework'); ?>
                <sup>*</sup></label>
            <?php
            $content = '';
            $editor_id = 'company_des';
            $settings = array(
                'wpautop' => true,
                'media_buttons' => false,
                'textarea_name' => $editor_id,
                'textarea_rows' => get_option('default_post_edit_rows', 8),
                'tabindex' => '',
                'editor_css' => '',
                'editor_class' => '',
                'teeny' => false,
                'dfw' => false,
                'tinymce' => true,
                'quicktags' => true
            );
            wp_editor(html_entity_decode(stripcslashes($content)), $editor_id, $settings); ?>
        </div>
    <?php endif; ?>
    <?php if (!in_array('fields_company_website', $hide_company_fields)) : ?>
        <div class="form-group col-md-6">
            <label><?php esc_html_e(' Website', 'civi-framework'); ?></label>
            <input type="url" id="company_website" name="company_website"
                   placeholder="<?php esc_attr_e('www.domain.com', 'civi-framework') ?>">
        </div>
    <?php endif; ?>
    <?php if (!in_array('fields_company_phone', $hide_company_fields)) : ?>
        <div class="form-group col-md-6">
            <label><?php esc_html_e('Phone Number', 'civi-framework'); ?></label>
			<div class="tel-group">
				<select name="prefix_code" class="civi-select2 prefix-code">
					<?php
					$prefix_code = phone_prefix_code();
						foreach ($prefix_code as $key => $value) {
							echo '<option value="' . $key . '" data-dial-code="' . $value['code'] . '">' . $value['name'] . ' (' . $value['code'] . ')</option>';
						}
					?>
				</select>
            	<input type="tel" id="company_phone" name="company_phone"
                   placeholder="<?php esc_attr_e('+00 12 334 5678', 'civi-framework') ?>">
			</div>
        </div>
    <?php endif; ?>
    <?php if (!in_array('fields_company_email', $hide_company_fields)) : ?>
        <div class="form-group col-md-6">
            <label><?php esc_html_e('Email', 'civi-framework') ?> <sup>*</sup></label>
            <input type="email" id="company_email" name="company_email"
                   placeholder="<?php esc_attr_e('hello@domain.com', 'civi-framework') ?>">
        </div>
    <?php endif; ?>
    <?php if (!in_array('fields_company_founded', $hide_company_fields)) : ?>
        <div class="form-group col-md-6">
            <label><?php esc_html_e('Founded in', 'civi-framework') ?></label>
            <div class="select2-field">
				<select name="company_founded" class="civi-select2">
					<?php echo civi_get_company_founded(); ?>
				</select>
			</div>
        </div>
    <?php endif; ?>
    <?php if (!in_array('fields_company_size', $hide_company_fields)) : ?>
        <div class="form-group col-md-6">
            <label><?php esc_html_e('Company size', 'civi-framework') ?> <sup>*</sup></label>
            <div class="select2-field">
				<select name="company_size" class="civi-select2">
					<?php civi_get_taxonomy('company-size', false, true); ?>
				</select>
			</div>
        </div>
    <?php endif; ?>

    <div class="form-group col-md-12">
        <div class="company-fields-warpper">
            <div class="company-fields-avatar civi-fields-avatar">
                <label><?php esc_html_e('Logo', 'civi-framework'); ?></label>
                <div class="form-field">
                    <div id="civi_avatar_errors" class="errors-log"></div>
                    <div id="civi_avatar_container" class="file-upload-block preview">
                        <div id="civi_avatar_view"></div>
                        <div id="civi_add_avatar">
                            <i class="far fa-arrow-from-bottom large"></i>
                            <p id="civi_drop_avatar">
                                <button type="button" id="civi_select_avatar"><?php esc_html_e('Upload', 'civi-framework') ?></button>
                            </p>
                        </div>
                        <input type="hidden" class="avatar_url form-control" name="company_avatar_url" value="" id="avatar_url">
                        <input type="hidden" class="avatar_id" name="company_avatar_id" value="" id="avatar_id" />
                    </div>
                </div>
                <div class="field-note"><?php echo sprintf(__('Maximum file size: %s.', 'civi-framework'), $image_max_file_size); ?></div>
            </div>
            <div class="company-fields-thumbnail civi-fields-thumbnail">
                <label><?php esc_html_e('Cover image', 'civi-framework'); ?></label>
                <div class="form-field">
                    <div id="civi_thumbnail_errors" class="errors-log"></div>
                    <div id="civi_thumbnail_container" class="file-upload-block preview">
                        <div id="civi_thumbnail_view"></div>
                        <div id="civi_add_thumbnail">
                            <i class="far fa-arrow-from-bottom large"></i>
                            <p id="civi_drop_thumbnail">
                                <button type="button" id="civi_select_thumbnail"><?php esc_html_e('Click here', 'civi-framework') ?></button>
                                <?php esc_html_e(' or drop files to upload', 'civi-framework') ?>
                            </p>
                        </div>
                        <input type="hidden" class="thumbnail_url form-control" name="company_thumbnail_url" value="" id="thumbnail_url">
                        <input type="hidden" class="thumbnail_id" name="company_thumbnail_id" value="" id="thumbnail_id" />
                    </div>
                </div>
                <p class="civi-thumbnail-size"><?php esc_html_e('The cover image size should be max 1920 x 400px', 'civi-framework') ?></p>
            </div>
        </div>
    </div>

    <?php if (!in_array('fields_company_location', $hide_company_fields)) : ?>
		<div class="form-group col-lg-6">
			<label><?php esc_html_e('Company Location', 'civi-framework') ?></label>
			<div class="select2-field">
				<select name="company_location" class="civi-select2">
                    <?php civi_get_taxonomy_location('company-location','company-state','company-location-state','company-state-country'); ?>
				</select>
			</div>
		</div>
	<?php endif; ?>

</div>
