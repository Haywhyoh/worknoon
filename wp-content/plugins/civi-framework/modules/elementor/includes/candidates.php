<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Plugin;

defined('ABSPATH') || exit;

Plugin::instance()->widgets_manager->register(new Widget_Candidates());

class Widget_Candidates extends Widget_Base
{
    public function get_post_type()
    {
        return 'candidate';
    }

    public function get_name()
    {
        return 'civi-candidates';
    }

    public function get_title()
    {
        return esc_html__('Candidates', 'civi-framework');
    }

    public function get_icon()
    {
        return 'civi-badge eicon-person';
    }

    public function get_keywords()
    {
        return ['candidates'];
    }

    protected function register_controls()
    {
        $this->register_layout_section();
        $this->register_query_section();
    }

    private function register_layout_section()
    {
        $this->start_controls_section('layout_section', [
            'label' => esc_html__('Layout', 'civi-framework'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__('Columns', 'civi-framework'),
                'type' => Controls_Manager::NUMBER,
                'prefix_class' => 'elementor-grid%s-',
                'min' => 1,
                'max' => 4,
                'default' => 2,
                'required' => true,
                'device_args' => [
                    Controls_Stack::RESPONSIVE_TABLET => [
                        'required' => false,
                    ],
                    Controls_Stack::RESPONSIVE_MOBILE => [
                        'required' => false,
                    ],
                ],
                'min_affected_device' => [
                    Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
                    Controls_Stack::RESPONSIVE_TABLET => Controls_Stack::RESPONSIVE_TABLET,
                ],
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__('Posts Per Page', 'civi-framework'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3,
            ]
        );

        $this->add_responsive_control(
            'column_gap',
            [
                'label' => __('Columns Gap', 'civi-framework'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-carousel .candidates-item-inner' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .slick-list' => 'margin-left: calc(-{{SIZE}}{{UNIT}}/2);margin-right: calc(-{{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .elementor-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label' => esc_html__('Rows Gap', 'civi-framework'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 30,
                ],
                'frontend_available' => true,
                'selectors' => [
                    '{{WRAPPER}} .elementor-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

		$this->add_control(
            'excerpt_trim_words',
            [
                'label' => esc_html__('Excerpt Trim Words', 'civi-framework'),
                'type' => Controls_Manager::NUMBER,
                'default' => 30,
            ]
        );

        $this->end_controls_section();
    }

    private function register_query_section()
    {
        $this->start_controls_section('query_section', [
            'label' => esc_html__('Query', 'civi-framework'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'type_query',
            [
                'label' => esc_html__('Filter', 'civi-framework'),
                'type' => Controls_Manager::SELECT,
                'default' => 'orderby',
                'options' => [
                    'title' => esc_html__('Title', 'civi-framework'),
                    'orderby' => esc_html__('Orderby', 'civi-framework'),
                    'taxonomy' => esc_html__('Taxonomy', 'civi-framework'),
                ],
            ]
        );

        $taxonomies  = array(
            "Categories" => "candidate_categories",
            "Location" => "candidate_locations",
            "Age" => "candidate_ages",
            "Language" => "candidate_languages",
            "Qualification" => "candidate_qualification",
            "Experience" => "candidate_yoe",
            "Level" => "candidate_education_levels",
            "Skill" => "candidate_skills",
        );

        foreach ($taxonomies as $label_taxonomy => $taxonomy) {
            $categories = get_terms([
                'taxonomy'   => $taxonomy,
                'hide_empty' => true,
            ]);

            $options = array();
            foreach ($categories as $category) {
                if(!empty($category) && $category->slug != 'uncategorized') {
                    $options[ $category->term_id ] = $category->name;
                }
            }

            $this->add_control($taxonomy, [
                'label'       => esc_html__($label_taxonomy, 'civi-framework'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $options,
                'default'     => [],
                'label_block' => true,
                'multiple'    => true,
                'condition' => [
                    'type_query' => 'taxonomy',
                ],
            ]);
        }

        $this->add_control(
            'orderby',
            [
                'label' => esc_html__('Order By', 'civi-framework'),
                'type' => Controls_Manager::SELECT,
                'default' => 'newest',
                'options' => [
                    'oldest' => esc_html__('Oldest', 'civi-framework'),
                    'newest' => esc_html__('Newest', 'civi-framework'),
                    'random' => esc_html__('Random', 'civi-framework'),
                ],
                'condition' => [
                    'type_query' => 'orderby',
                ],
            ]
        );

        $options_candidate = [];
        $args_candidate = array(
            'post_type' => $this->get_post_type(),
            'ignore_sticky_posts' => 1,
            'post_status' => 'publish',
        );

        $data_candidate = new \WP_Query($args_candidate);
        if ($data_candidate->have_posts()) {
            while ($data_candidate->have_posts()) : $data_candidate->the_post();
                $id = get_the_id();
                $title = get_the_title($id);
                $options_candidate[$id] = $title;
            endwhile;
        }
        wp_reset_postdata();

        $this->add_control('include_ids', [
            'label'       => esc_html__('Search & Select', 'civi-framework'),
            'type'        => Controls_Manager::SELECT2,
            'options'     => $options_candidate,
            'default'     => [],
            'label_block' => true,
            'multiple'    => true,
            'condition' => [
                'type_query' => 'title',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('wrapper', 'class', 'civi-candidates');
        $args = array(
            'posts_per_page' => $settings['posts_per_page'],
            'post_type' => 'candidate',
            'ignore_sticky_posts' => 1,
            'post_status' => 'publish',
        );

        //Query
        $tax_query = $meta_query = array();
        if(!empty($settings['include_ids']) && $settings['type_query'] == 'title') {
            $args['post__in'] = $settings['include_ids'];
        }

        if($settings['type_query'] == 'orderby') {
            if (!empty($settings['orderby'])) {
                if ($settings['orderby'] == 'oldest') {
                    $args['orderby'] = array(
                        'menu_order' => 'DESC',
                        'date' => 'ASC',
                    );
                }
                if ($settings['orderby'] == 'newest') {
                    $args['orderby'] = array(
                        'menu_order' => 'ASC',
                        'date' => 'DESC',
                    );
                }
                if ($settings['orderby'] == 'random') {
                    $args['meta_key'] = '';
                    $args['orderby'] = 'rand';
                    $args['order'] = 'ASC';
                }
            }
        }
        if($settings['type_query'] == 'taxonomy') {
            $taxonomies = array("candidate_categories", "candidate_locations", "candidate_ages", "candidate_languages", "candidate_qualification", "candidate_yoe", "candidate_education_levels", "candidate_skills");
            foreach ($taxonomies as $taxonomy) {
                if (!empty($settings[$taxonomy])) {
                    $tax_query[] = array(
                        'taxonomy' => $taxonomy,
                        'field' => 'term_id',
                        'terms' => $settings[$taxonomy],
                    );
                }
            }
        }

        if (!empty($tax_query)) {
            $args['tax_query'] = array(
                'relation' => 'AND',
                $tax_query
            );
        }

        if (!empty($meta_query)) {
            $args['meta_query'] = array(
                'relation' => 'AND',
                $meta_query
            );
        }

        $data = new \WP_Query($args);
        $total_post = $data->found_posts;

        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper') ?>>
            <?php if ($data->have_posts()) { ?>
				<div class="elementor-grid-candidates">
					<div class="elementor-grid">
						<?php while ($data->have_posts()): $data->the_post(); ?>
							<?php civi_get_template('content-candidate.php', array(
								'candidate_layout' => 'layout-grid',
								'button_type' => 'service',
								'excerpt_trim_words' => $settings['excerpt_trim_words']
							)); ?>
						<?php endwhile; ?>
					</div>
				</div>
            <?php } else { ?>
                <div class="item-not-found"><?php esc_html_e('No item found', 'civi-framework'); ?></div>
            <?php } ?>
        </div>
    <?php }
    }
