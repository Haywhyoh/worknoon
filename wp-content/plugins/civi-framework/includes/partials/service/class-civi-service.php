<?php
if (!defined('ABSPATH')) {
	exit;
}
if (!class_exists('Civi_Service')) {
	/**
	 * Class Civi_Service
	 */
	class Civi_Service
	{
		/**
		 * Submit review
		 */
		public function submit_review_ajax()
		{
			check_ajax_referer('civi_submit_review_ajax_nonce', 'civi_security_submit_review');
			global $wpdb, $current_user;
			wp_get_current_user();
			$user_id                    = $current_user->ID;
			$user                       = get_user_by('id', $user_id);
			$service_id                   = isset($_POST['service_id']) ? civi_clean(wp_unslash($_POST['service_id'])) : '';
			$rating_salary_value       = isset($_POST['rating_salary']) ? civi_clean(wp_unslash($_POST['rating_salary'])) : '';
			$rating_service_value         = isset($_POST['rating_service']) ? civi_clean(wp_unslash($_POST['rating_service'])) : '';
			$rating_skill_value      = isset($_POST['rating_skill']) ? civi_clean(wp_unslash($_POST['rating_skill'])) : '';
			$rating_work_value   = isset($_POST['rating_work']) ? civi_clean(wp_unslash($_POST['rating_work'])) : '';
			$my_review    = $wpdb->get_row("SELECT * FROM $wpdb->comments as comment INNER JOIN $wpdb->commentmeta AS meta WHERE comment.comment_post_ID = $service_id AND comment.user_id = $user_id  AND meta.meta_key = 'service_rating' AND meta.comment_id = comment.comment_ID ORDER BY comment.comment_ID DESC");
			$comment_approved = 1;
			$auto_publish_review_service = get_option('comment_moderation');
			if ($auto_publish_review_service == 1) {
				$comment_approved = 0;
			}
			if ($my_review == null) {
				$data = array();
				$user = $user->data;

				$data['comment_post_ID']      = $service_id;
				$data['comment_content']      = isset($_POST['message']) ?  wp_filter_post_kses($_POST['message']) : '';
				$data['comment_date']         = current_time('mysql');
				$data['comment_approved']     = $comment_approved;
				$data['comment_author']       = $user->user_login;
				$data['comment_author_email'] = $user->user_email;
				$data['comment_author_url']   = $user->user_url;
				$data['user_id']              = $user_id;

				$comment_id = wp_insert_comment($data);

				add_comment_meta($comment_id, 'service_salary_rating', $rating_salary_value);
				add_comment_meta($comment_id, 'service_service_rating', $rating_service_value);
				add_comment_meta($comment_id, 'service_skill_rating', $rating_skill_value);
				add_comment_meta($comment_id, 'service_work_rating', $rating_work_value);

				$service_rating = (intval($rating_salary_value) + intval($rating_service_value) + intval($rating_skill_value) + intval($rating_work_value)) / 4;
				$service_rating = number_format((float)$service_rating, 2, '.', '');

				add_comment_meta($comment_id, 'service_rating', $service_rating);

				if ($comment_approved == 1) {
					apply_filters('civi_service_rating_meta', $service_id, $service_rating);
				}

				$countfiles = count($_FILES['files']['name']);

				$submitted_file = '';

				$comment_thumb = array();

				for ($i = 0; $i < $countfiles; $i++) {

					$submitted_file = array(
						'error'         => $_FILES['files']['error'][$i],
						'name'          => $_FILES['files']['name'][$i],
						'size'          => $_FILES['files']['size'][$i],
						'tmp_name'      => $_FILES['files']['tmp_name'][$i],
						'type'          => $_FILES['files']['type'][$i],
					);

					// File name
					$filename = $_FILES['files']['name'][$i];

					$upload_overrides = array(
						'test_form' => false
					);

					$movefile = wp_handle_upload($submitted_file, $upload_overrides);

					if (isset($movefile['file'])) {
						$filetype = wp_check_filetype($movefile['file'], null);
						$attachment_details = array(
							'guid'           => $movefile['url'],
							'post_mime_type' => $filetype['type'],
							'post_title'     => preg_replace('/\.[^.]+$/', '', basename($filename)),
							'post_content'   => '',
							'post_status'    => 'inherit'
						);

						$attach_id     = wp_insert_attachment($attachment_details, $movefile['file']);
						$attach_data   = wp_generate_attachment_metadata($attach_id, $movefile['file']);
						wp_update_attachment_metadata($attach_id, $attach_data);
						$thumbnail_url = wp_get_attachment_thumb_url($attach_id);
						$fullimage_url = wp_get_attachment_image_src($attach_id, 'full');

						array_push($comment_thumb, $attach_id);

						$ajax_response = array(
							'success'       => true,
							'url'           => $thumbnail_url,
							'attachment_id' => $attach_id,
							'full_image'    => $fullimage_url[0]
						);
					} else {
						$ajax_response = array('success' => false, 'reason' => esc_html__('Image upload failed!', 'civi-framework'));
					}
				}

                civi_get_data_ajax_notification($service_id,'add-review-service');

                add_comment_meta($comment_id, 'comment_thumb', $comment_thumb);

			} else {
				$data = array();

				$data['comment_ID']       = $my_review->comment_ID;
				$data['comment_post_ID']  = $service_id;
				$data['comment_content']  = isset($_POST['message']) ? wp_filter_post_kses($_POST['message']) : '';
				$data['comment_date']     = current_time('mysql');
				$data['comment_approved'] = $comment_approved;

				wp_update_comment($data);
				update_comment_meta($my_review->comment_ID, 'service_salary_rating', $rating_salary_value);
				update_comment_meta($my_review->comment_ID, 'service_service_rating', $rating_service_value);
				update_comment_meta($my_review->comment_ID, 'service_skill_rating', $rating_skill_value);
				update_comment_meta($my_review->comment_ID, 'service_work_rating', $rating_work_value);

				$service_rating = (intval($rating_salary_value) + intval($rating_service_value) + intval($rating_skill_value) + intval($rating_work_value)) / 4;
				$service_rating = number_format((float)$service_rating, 2, '.', '');

				update_comment_meta($my_review->comment_ID, 'service_rating', $service_rating, $my_review->meta_value);

				if ($comment_approved == 1) {
					apply_filters('civi_service_rating_meta', $service_id, $service_rating, false, $my_review->meta_value);
				}

				$countfiles = count($_FILES['files']['name']);

				$submitted_file = '';

				$comment_thumb = array();

				for ($i = 0; $i < $countfiles; $i++) {

					$submitted_file = array(
						'error'         => $_FILES['files']['error'][$i],
						'name'          => $_FILES['files']['name'][$i],
						'size'          => $_FILES['files']['size'][$i],
						'tmp_name'      => $_FILES['files']['tmp_name'][$i],
						'type'          => $_FILES['files']['type'][$i],
					);

					// File name
					$filename = $_FILES['files']['name'][$i];

					$upload_overrides = array(
						'test_form' => false
					);

					$movefile = wp_handle_upload($submitted_file, $upload_overrides);

					if (isset($movefile['file'])) {
						$filetype = wp_check_filetype($movefile['file'], null);
						$attachment_details = array(
							'guid'           => $movefile['url'],
							'post_mime_type' => $filetype['type'],
							'post_title'     => preg_replace('/\.[^.]+$/', '', basename($filename)),
							'post_content'   => '',
							'post_status'    => 'inherit'
						);

						$attach_id     = wp_insert_attachment($attachment_details, $movefile['file']);
						$attach_data   = wp_generate_attachment_metadata($attach_id, $movefile['file']);
						wp_update_attachment_metadata($attach_id, $attach_data);
						$thumbnail_url = wp_get_attachment_thumb_url($attach_id);
						$fullimage_url = wp_get_attachment_image_src($attach_id, 'full');

						array_push($comment_thumb, $attach_id);

						$ajax_response = array(
							'success'       => true,
							'url'           => $thumbnail_url,
							'attachment_id' => $attach_id,
							'full_image'    => $fullimage_url[0]
						);
					} else {
						$ajax_response = array('success' => false, 'reason' => esc_html__('Image upload failed!', 'civi-framework'));
					}
				}

				update_comment_meta($my_review->comment_ID, 'comment_thumb', $comment_thumb);
			}

			$comments_query = "SELECT * FROM $wpdb->comments as comment INNER JOIN $wpdb->commentmeta AS meta WHERE comment.comment_post_ID = $service_id AND meta.meta_key = 'service_rating' AND meta.comment_id = comment.comment_ID AND ( comment.comment_approved = 1 OR comment.user_id = $user_id )";
			$get_comments   = $wpdb->get_results($comments_query);
			$rating_number = 0;

			if (!is_null($get_comments)) {

				$service_salary_rating = $service_service_rating = $service_skill_rating = $service_work_rating = array();
				foreach ($get_comments as $comment) {
					if (intval(get_comment_meta($comment->comment_ID, 'service_salary_rating', true)) != 0) {
						$service_salary_rating[]         = intval(get_comment_meta($comment->comment_ID, 'service_salary_rating', true));
					}
					if (intval(get_comment_meta($comment->comment_ID, 'service_service_rating', true)) != 0) {
						$service_service_rating[]         = intval(get_comment_meta($comment->comment_ID, 'service_service_rating', true));
					}
					if (intval(get_comment_meta($comment->comment_ID, 'service_skill_rating', true)) != 0) {
						$service_skill_rating[]         = intval(get_comment_meta($comment->comment_ID, 'service_skill_rating', true));
					}
					if (intval(get_comment_meta($comment->comment_ID, 'service_work_rating', true)) != 0) {
						$service_work_rating[]         = intval(get_comment_meta($comment->comment_ID, 'service_work_rating', true));
					}

					if ($comment->comment_approved == 1) {
						if (!empty($comment->meta_value) && $comment->meta_value != 0.00) {
							$total_reviews++;
						}
						if ($comment->meta_value > 0) {
							$total_stars += $comment->meta_value;
						}
					}
				}

				if ($total_reviews != 0) {
					$rating_number = number_format($total_stars / $total_reviews, 1);
				}
			}

			update_post_meta( $service_id, 'total_point_review', (int)($rating_number) );

			echo json_encode(array('success' => true));

			wp_die();
		}

		/**
		 * @param $service_id
		 * @param $rating_value
		 * @param bool|true $comment_exist
		 * @param int $old_rating_value
		 */
		public function rating_meta_filter($service_id, $rating_value, $comment_exist = true, $old_rating_value = 0)
		{
			update_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_rating', $rating_value);
		}

		/**
		 * Submit review
		 */
		public function submit_reply_ajax()
		{
			check_ajax_referer('civi_submit_reply_ajax_nonce', 'civi_security_submit_reply');
			global $wpdb, $current_user;
			wp_get_current_user();
			$user_id  = $current_user->ID;
			$user     = get_user_by('id', $user_id);
			$service_id = isset($_POST['service_id']) ? civi_clean(wp_unslash($_POST['service_id'])) : '';
			$comment_approved = 1;
			$auto_publish_review_service = get_option('comment_moderation');
			if ($auto_publish_review_service == 1) {
				$comment_approved = 0;
			}
			$data = array();
			$user = $user->data;

			$data['comment_post_ID']      = $service_id;
			$data['comment_content']      = isset($_POST['message']) ? wp_filter_post_kses($_POST['message']) : '';
			$data['comment_date']         = current_time('mysql');
			$data['comment_approved']     = $comment_approved;
			$data['comment_author']       = $user->user_login;
			$data['comment_author_email'] = $user->user_email;
			$data['comment_author_url']   = $user->user_url;
			$data['comment_parent']       = isset($_POST['comment_id']) ? civi_clean(wp_unslash($_POST['comment_id'])) : '';
			$data['user_id']              = $user_id;

			$comment_id = wp_insert_comment($data);

			echo json_encode(array('success' => true));

			wp_die();
		}

        /**
         * Company submit
         */
        public function service_submit_ajax()
        {
            $service_form               = isset($_REQUEST['service_form']) ? civi_clean(wp_unslash($_REQUEST['service_form'])) : '';
            $service_id                 = isset($_REQUEST['service_id']) ? civi_clean(wp_unslash($_REQUEST['service_id'])) : '';
            $service_title              = isset($_REQUEST['service_title']) ? civi_clean(wp_unslash($_REQUEST['service_title'])) : '';
            $service_categories         = isset($_REQUEST['service_categories']) ? civi_clean(wp_unslash($_REQUEST['service_categories'])) : '';
            $service_skills         = isset($_REQUEST['service_skills']) ? civi_clean(wp_unslash($_REQUEST['service_skills'])) : '';
            $service_price      = isset($_REQUEST['service_price']) ? civi_clean(wp_unslash($_REQUEST['service_price'])) : '';
            $service_currency       = isset($_REQUEST['service_currency']) ? wp_kses_post(wp_unslash($_REQUEST['service_currency'])) : '';
            $service_time      = isset($_REQUEST['service_time']) ? civi_clean(wp_unslash($_REQUEST['service_time'])) : '';
            $service_time_type       = isset($_REQUEST['service_time_type']) ? civi_clean(wp_unslash($_REQUEST['service_time_type'])) : '';
            $service_des        = isset($_REQUEST['service_des']) ? civi_clean(wp_unslash($_REQUEST['service_des'])) : '';
            $service_languages      = isset($_REQUEST['service_languages']) ? civi_clean(wp_unslash($_REQUEST['service_languages'])) : '';
            $service_languages_level     = isset($_REQUEST['service_languages_level']) ? civi_clean(wp_unslash($_REQUEST['service_languages_level'])) : '';

            $service_thumbnail_url = isset($_REQUEST['service_thumbnail_url']) ? civi_clean(wp_unslash($_REQUEST['service_thumbnail_url'])) : '';
            $service_thumbnail_id  = isset($_REQUEST['service_thumbnail_id']) ? civi_clean(wp_unslash($_REQUEST['service_thumbnail_id'])) : '';
            $civi_gallery_ids          = isset($_REQUEST['civi_gallery_ids']) ? civi_clean(wp_unslash($_REQUEST['civi_gallery_ids'])) : '';
            $service_video_url      = isset($_REQUEST['service_video_url']) ? civi_clean(wp_unslash($_REQUEST['service_video_url'])) : '';
            $service_map_location       = isset($_REQUEST['service_map_location']) ? civi_clean(wp_unslash($_REQUEST['service_map_location'])) : '';
            $service_map_address        = isset($_REQUEST['service_map_address']) ? civi_clean(wp_unslash($_REQUEST['service_map_address'])) : '';
            $service_location       = isset($_REQUEST['service_location']) ? civi_clean(wp_unslash($_REQUEST['service_location'])) : '';
            $service_latitude      = isset($_REQUEST['service_latitude']) ? civi_clean(wp_unslash($_REQUEST['service_latitude'])) : '';
            $service_longtitude       = isset($_REQUEST['service_longtitude']) ? civi_clean(wp_unslash($_REQUEST['service_longtitude'])) : '';

            $service_addons_title       = isset($_REQUEST['service_addons_title']) ? civi_clean(wp_unslash($_REQUEST['service_addons_title'])) : '';
            $service_addons_price        = isset($_REQUEST['service_addons_price']) ? civi_clean(wp_unslash($_REQUEST['service_addons_price'])) : '';
            $service_addons_description      = isset($_REQUEST['service_addons_description']) ? civi_clean(wp_unslash($_REQUEST['service_addons_description'])) : '';

            $service_faq_title      = isset($_REQUEST['service_faq_title']) ? civi_clean(wp_unslash($_REQUEST['service_faq_title'])) : '';
            $service_faq_description       = isset($_REQUEST['service_faq_description']) ? civi_clean(wp_unslash($_REQUEST['service_faq_description'])) : '';

            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;

            $new_service = array();
            $new_service['post_type'] = 'service';
            $new_service['post_author'] = $user_id;

            if (isset($service_title)) {
                $new_service['post_title'] = $service_title;
            }

            if (isset($service_url)) {
                $new_service['post_name'] = $service_url;
            }

            if (isset($service_des)) {
                $new_service['post_content'] = $service_des;
            }

            $submit_action = $service_form;
            $auto_publish         = civi_get_option('service_auto_publish', 1);
            $auto_publish_edited  = civi_get_option('service_auto_publish_edited', 1);
            $paid_submission_type = civi_get_option('candidate_paid_submission_type', 'no');
            $enable_candidate_service_fee = civi_get_option('enable_candidate_service_fee');
            $candidate_number_service_fee = civi_get_option('candidate_number_service_fee');

            if ($submit_action == 'submit-service') {
                $service_id = 0;
                if ($auto_publish == 1) {
                    $new_service['post_status'] = 'publish';
                } else {
                    $new_service['post_status'] = 'pending';
                }
                if (!empty($new_service['post_title'])) {
                    $service_id = wp_insert_post($new_service, true);
                }
                if ($service_id > 0) {
                    if ($paid_submission_type == 'candidate_per_package') {
                        $candidate_package_key = get_the_author_meta(CIVI_METABOX_PREFIX . 'candidate_package_key', $user_id);
                        update_post_meta($service_id, CIVI_METABOX_PREFIX . 'candidate_package_key', $candidate_package_key);
                        $candidate_package_number_service = intval(get_the_author_meta(CIVI_METABOX_PREFIX . 'candidate_package_number_service', $user_id));
                        if ($candidate_package_number_service - 1 >= 0) {
                            update_user_meta($user_id, CIVI_METABOX_PREFIX . 'candidate_package_number_service', $candidate_package_number_service - 1);
                        }
                    }
                    update_post_meta($service_id, CIVI_METABOX_PREFIX . 'enable_candidate_package_expires', 0);
					update_post_meta( $service_id, 'total_point_review', 0 );
                }
                echo json_encode(array('success' => true));
            } elseif ($submit_action == 'edit-service') {
                $service_id        = absint(wp_unslash($service_id));
                $new_service['ID'] = intval($service_id);
                if ($auto_publish_edited == 1) {
                    $new_service['post_status'] = 'publish';
                } else {
                    $new_service['post_status'] = 'pending';
                }
                if ($paid_submission_type == 'candidate_per_package') {
                    $civi_candidate_package = new Civi_candidate_package();
                    $check_candidate_package = $civi_candidate_package->user_candidate_package_available($user_id);
                    if (($check_candidate_package == -1) || ($check_candidate_package == 0)) {
                        return -1;
                    }
                }

                $service_id = wp_update_post($new_service);
                echo json_encode(array('success' => true));
            }

            if ($service_id > 0) {
                //Category
                if (!empty($service_categories)) {
                    $service_categories = intval($service_categories);
                    wp_set_object_terms($service_id, $service_categories, 'service-categories');
                }

                if (!empty($service_skills)) {
                    $service_skills = array_map('intval', $service_skills);
                    wp_set_object_terms($service_id, $service_skills, 'service-skills');
                }

                if (!empty($service_languages)) {
                    $service_languages = array_map('intval', $service_languages);
                    wp_set_object_terms($service_id, $service_languages, 'service-language');
                }

                if (!empty($service_location)) {
                    $service_location = intval($service_location);
                    wp_set_object_terms($service_id, $service_location, 'service-location');
                }

                //Field

                if (isset($service_price)) {
                    update_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_price', $service_price);

                    $price_received = intval($service_price) * (100 - intval($candidate_number_service_fee)) / 100;
                    if ($enable_candidate_service_fee === '1' && !empty($candidate_number_service_fee)){
                        update_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_price_received', $price_received);
                    }
                }

                if (isset($service_currency)) {
                    update_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_currency_type', $service_currency);
                }

                if (isset($service_time)) {
                    update_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_number_time', $service_time);
                }

                if (isset($service_time_type)) {
                    update_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_time_type', $service_time_type);
                }

                if (isset($service_languages_level)) {
                    update_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_language_level', $service_languages_level);
                }

                if (isset($service_video_url)) {
                    update_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_video_url', $service_video_url);
                }

                if (isset($service_map_address)) {
                    update_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_address', $service_map_address);
                }

                if (isset($service_map_location)) {
                    $lat_lng = $service_map_location;
                    $address = $service_map_address;
                    $arr_location = array(
                        'location' => $lat_lng,
                        'address' => $address,
                    );
                    update_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_location', $arr_location);
                    update_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_address', $service_map_address);
                    update_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_latitude', $service_latitude);
                    update_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_longtitude', $service_longtitude);
                }

                if (!empty($service_addons_title)) {
                    $addons_data  = array();
                    for ($i = 0; $i < count($service_addons_title); $i++) {
                        $addons_data[] = array(
                            CIVI_METABOX_PREFIX . 'service_addons_title'   => $service_addons_title[$i],
                            CIVI_METABOX_PREFIX . 'service_addons_price'    => $service_addons_price[$i],
                            CIVI_METABOX_PREFIX . 'service_addons_description'    => $service_addons_description[$i],
                        );
                    }
                    update_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_tab_addon', $addons_data);
                }

                if (!empty($service_faq_title)) {
                    $faq_data  = array();
                    for ($i = 0; $i < count($service_faq_title); $i++) {
                        $faq_data[] = array(
                            CIVI_METABOX_PREFIX . 'service_faq_title'   => $service_faq_title[$i],
                            CIVI_METABOX_PREFIX . 'service_faq_description'    => $service_faq_description[$i],
                        );
                    }
                    update_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_tab_faq', $faq_data);
                }

                if (isset($service_thumbnail_url) && isset($service_thumbnail_id)) {
                    $service_thumbnail = array(
                        'id'  => $service_thumbnail_id,
                        'url' => $service_thumbnail_url,
                    );
                    update_post_meta($service_id, '_thumbnail_id', $service_thumbnail_id);
                }

                if (isset($civi_gallery_ids)) {
                    $str_img_ids = '';
                    foreach ($civi_gallery_ids as $service_img_id) {
                        $civi_gallery_ids[] = intval($service_img_id);
                        $str_img_ids .= '|' . intval($service_img_id);
                    }
                    $str_img_ids = substr($str_img_ids, 1);
                    update_post_meta($service_id, CIVI_METABOX_PREFIX . 'service_images', $str_img_ids);
                }
            }

            wp_die();
        }
	}
}
