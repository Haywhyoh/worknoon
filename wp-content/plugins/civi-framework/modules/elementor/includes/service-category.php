<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Plugin;

defined('ABSPATH') || exit;

Plugin::instance()->widgets_manager->register(new Widget_Service_Category());

class Widget_Service_Category extends Widget_Base
{

    public function get_post_type()
    {
        return 'service';
    }

    public function get_name()
    {
        return 'civi-service-category';
    }

    public function get_title()
    {
        return esc_html__('Service Category', 'civi-framework');
    }

    public function get_icon()
    {
        return 'civi-badge eicon-archive-title';
    }

    public function get_keywords()
    {
        return ['service', 'category', 'carousel'];
    }

    public function get_style_depends()
    {
        return [CIVI_PLUGIN_PREFIX . 'service-category'];
    }

    protected function register_controls()
    {
        $this->register_layout_section();
        $this->register_slider_section();
        $this->register_layout_style_section();
        $this->register_title_style_section();
        $this->register_count_style_section();
    }

    private function register_layout_section()
    {
        $this->start_controls_section('layout_section', [
            'label' => esc_html__('Layout', 'civi-framework'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_responsive_control('content_v_align', [
            'label' => esc_html__('Alignment vertical', 'civi-framework'),
            'type' => Controls_Manager::CHOOSE,
            'options' => array(
                'flex-start' => [
                    'title' => esc_html__('Top', 'civi-framework'),
                    'icon' => 'eicon-v-align-top',
                ],
                'center' => [
                    'title' => esc_html__('Middle', 'civi-framework'),
                    'icon' => 'eicon-v-align-middle',
                ],
                'flex-end' => [
                    'title' => esc_html__('Bottom', 'civi-framework'),
                    'icon' => 'eicon-v-align-bottom',
                ],
            ),
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .cate-content' => 'align-items: {{VALUE}};',
            ],
        ]);

		$this->add_responsive_control('content_h_align', [
            'label' => esc_html__('Alignment horizontal', 'civi-framework'),
            'type' => Controls_Manager::CHOOSE,
            'options' => array(
                'flex-start' => [
                    'title' => esc_html__('Left', 'civi-framework'),
                    'icon' => 'eicon-h-align-left',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'civi-framework'),
                    'icon' => ' eicon-h-align-center',
                ],
                'flex-end' => [
                    'title' => esc_html__('Right', 'civi-framework'),
                    'icon' => 'eicon-h-align-right',
                ],
            ),
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .cate-content' => 'justify-content: {{VALUE}};',
            ],
        ]);

        $repeater = new Repeater();
        $taxonomy_terms = get_categories(
            array(
                'taxonomy' => 'service-categories',
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => true,
                'parent' => 0,
            )
        );

        $categories = [];
        foreach ($taxonomy_terms as $service) {
            $categories[$service->slug] = $service->name;
        }
        $repeater->add_control(
            'service_cat',
            [
                'label' => esc_html__('Categories', 'civi-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => $categories,
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Choose Image', 'civi-framework'),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'service_cat_list',
            [
                'label' => '',
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'text' => esc_html__('Service Category #1', 'civi-framework'),
                    ],
                    [
                        'text' => esc_html__('Service Category #2', 'civi-framework'),
                    ],
                    [
                        'text' => esc_html__('Service Category #3', 'civi-framework'),
                    ],
                    [
                        'text' => esc_html__('Service Category #3', 'civi-framework'),
                    ],
                ],
            ]
        );

        $this->add_control(
            'show_count',
            [
                'label' => esc_html__('Show Count', 'civi-framework'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
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
                    '{{WRAPPER}} .elementor-carousel .list-cate-item' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .slick-list' => 'margin-left: calc(-{{SIZE}}{{UNIT}}/2);margin-right: calc(-{{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .elementor-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_slider_section()
    {
        $this->start_controls_section('slider_section', [
            'label' => esc_html__('Slider', 'civi-framework'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $slides_to_show = range(1, 10);
        $slides_to_show = array_combine($slides_to_show, $slides_to_show);

        $this->add_responsive_control(
            'slides_to_show',
            [
                'label' => esc_html__('Slides to Show', 'civi-framework'),
                'type' => Controls_Manager::SELECT,
                'default' => '2',
                'options' => [
                        '' => esc_html__('Default', 'civi-framework'),
                    ] + $slides_to_show,
            ]
        );

        $this->add_responsive_control(
            'slides_to_scroll',
            [
                'label' => esc_html__('Slides to Scroll', 'civi-framework'),
                'type' => Controls_Manager::SELECT,
                'description' => esc_html__('Set how many slides are scrolled per swipe.', 'civi-framework'),
                'default' => '1',
                'options' => [
                        '' => esc_html__('Default', 'civi-framework'),
                    ] + $slides_to_show,
            ]
        );

        $this->add_control(
            'navigation',
            [
                'label' => esc_html__('Navigation', 'civi-framework'),
                'type' => Controls_Manager::SELECT,
                'default' => 'both',
                'options' => [
                    'both' => esc_html__('Arrows and Dots', 'civi-framework'),
                    'arrows' => esc_html__('Arrows', 'civi-framework'),
                    'dots' => esc_html__('Dots', 'civi-framework'),
                    'none' => esc_html__('None', 'civi-framework'),
                ],
            ]
        );

        $this->add_control(
            'center_mode',
            [
                'label' => esc_html__('Center Mode', 'civi-framework'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label' => esc_html__('Pause on Hover', 'civi-framework'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__('Autoplay', 'civi-framework'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => esc_html__('Autoplay Speed', 'civi-framework'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5000,
                'condition' => [
                    'autoplay' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-slide-bg' => 'animation-duration: calc({{VALUE}}ms*1.2); transition-duration: calc({{VALUE}}ms)',
                ],
            ]
        );

        $this->add_control(
            'infinite',
            [
                'label' => esc_html__('Infinite Loop', 'civi-framework'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'transition',
            [
                'label' => esc_html__('Transition', 'civi-framework'),
                'type' => Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => [
                    'slide' => esc_html__('Slide', 'civi-framework'),
                    'fade' => esc_html__('Fade', 'civi-framework'),
                ],
            ]
        );

        $this->add_control(
            'transition_speed',
            [
                'label' => esc_html__('Transition Speed', 'civi-framework') . ' (ms)',
                'type' => Controls_Manager::NUMBER,
                'default' => 500,
            ]
        );

        $this->end_controls_section();
    }

    private function register_layout_style_section()
    {
        $this->start_controls_section(
            'section_layout_style',
            [
                'label' => esc_html__('Layout', 'civi-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'thumbnail_size',
            [
                'label' => esc_html__('Image Size', 'civi-framework'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Example: 300x300', 'civi-framework'),
                'default' => '270x320',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'box_background',
                'label' => esc_html__('Background', 'civi-framework'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .cate-inner',
            ]
        );

        $this->add_control('thumbnail_border_radius', [
            'label' => esc_html__('Image Border Radius', 'civi-framework'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .image-cate img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .civi-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('box_padding', [
            'label' => esc_html__('Padding Box', 'civi-framework'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .cate-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('content_padding', [
            'label' => esc_html__('Padding Content', 'civi-framework'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .cate-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('layout_border_radius', [
            'label' => esc_html__('Border Radius', 'civi-framework'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .cate-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .cate-inner:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'layout_border',
                'selector' => '{{WRAPPER}} .cate-inner',
            ]
        );

        $this->end_controls_section();
    }

    private function register_title_style_section()
    {
        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('Title', 'civi-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Text Color', 'civi-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cate-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Typography', 'civi-framework'),
                'selector' => '{{WRAPPER}} .cate-title',
            ]
        );

        $this->end_controls_section();
    }

    private function register_count_style_section()
    {
        $this->start_controls_section(
            'section_count_style',
            [
                'label' => esc_html__('Count', 'civi-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_count' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'count_spacing',
            [
                'label' => esc_html__('Spacing', 'civi-framework'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cate-count' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'count_color',
            [
                'label' => esc_html__('Text Color', 'civi-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cate-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'count_typography',
                'label' => esc_html__('Typography', 'civi-framework'),
                'selector' => '{{WRAPPER}} .cate-count',
            ]
        );

        $this->end_controls_section();
    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();

        //Slider
        $show_dots = (in_array($settings['navigation'], ['dots', 'both']));
        $show_arrows = (in_array($settings['navigation'], ['arrows', 'both']));

        if (empty($settings['slides_to_show_tablet'])) : $settings['slides_to_show_tablet'] = $settings['slides_to_show'];endif;
        if (empty($settings['slides_to_show_mobile'])) : $settings['slides_to_show_mobile'] = $settings['slides_to_show'];endif;
        if (empty($settings['slides_to_scroll_tablet'])) : $settings['slides_to_scroll_tablet'] = $settings['slides_to_scroll'];endif;
        if (empty($settings['slides_to_scroll_mobile'])) : $settings['slides_to_scroll_mobile'] = $settings['slides_to_scroll'];endif;

        $slick_options = [
            '"slidesToShow":' . absint($settings['slides_to_show']),
            '"slidesToScroll":' . absint($settings['slides_to_scroll']),
            '"autoplaySpeed":' . absint($settings['autoplay_speed']),
            '"autoplay":' . (('yes' === $settings['autoplay']) ? 'true' : 'false'),
            '"infinite":' . (('yes' === $settings['infinite']) ? 'true' : 'false'),
            '"pauseOnHover":' . (('yes' === $settings['pause_on_hover']) ? 'true' : 'false'),
            '"centerMode":' . (('yes' === $settings['center_mode']) ? 'true' : 'false'),
            '"speed":' . absint($settings['transition_speed']),
            '"arrows":' . ($show_arrows ? 'true' : 'false'),
            '"dots":' . ($show_dots ? 'true' : 'false'),
            '"responsive": [{ "breakpoint":567, "settings":{ "slidesToShow":' . $settings["slides_to_show_mobile"] . ', "slidesToScroll":' . $settings["slides_to_scroll_mobile"] . '}},{ "breakpoint":767, "settings":{ "slidesToShow": 2, "slidesToScroll": 2} }, { "breakpoint":1024, "settings":{ "slidesToShow":' . $settings["slides_to_show_tablet"] . ', "slidesToScroll":' . $settings["slides_to_scroll_tablet"] . ' } } ]',
        ];
        $slick_data = '{' . implode(', ', $slick_options) . '}';

        if ('fade' === $settings['transition']) {
            $slick_options['fade'] = true;
        }

        $this->add_render_attribute('box','class',array(
            'list-cate-item',
            'civi-box',
        ));

        $carousel_classes = ['elementor-carousel'];
        $this->add_render_attribute('slides', [
            'class' => $carousel_classes,
            'data-slider_options' => $slick_data,
        ]);

        ?>

        <div class="elementor-slick-slider">
        <div <?php echo $this->get_render_attribute_string('slides'); ?>>

        <?php foreach ($settings['service_cat_list'] as $category) {
        $service_slug = $category['service_cat'];
        if (!empty($service_slug)) {
            $cate = get_term_by('slug', $service_slug, 'service-categories');
            if ($cate) {
                $term_name = $cate->name;
                $term_count = $cate->count;
                $term_link = get_term_link($cate, 'service-categories');
            }

            $thumbnail_size = $settings['thumbnail_size'];
            $width = $height = '';
            if(!empty($thumbnail_size)){
                if (preg_match('/\d+x\d+/', $thumbnail_size)) {
                    $thumbnail_size = explode('x', $thumbnail_size);
                    $width = $thumbnail_size[0];
                    $height = $thumbnail_size[1];
                }
            }
            if ($category['image']['url']) {
                $image_src_full = civi_image_resize_url($category['image']['url'], $width, $height);
                $image_src = $image_src_full['url'];
            }
            ?>
            <div <?php echo $this->get_render_attribute_string('box'); ?>>
                <div class="cate-inner">
                    <span class="image-cate">
                        <?php if (!empty($image_src)) { ?>
                            <a href="<?php echo esc_url($term_link) ?>" class="civi-image">
                            <img src="<?php echo esc_url($image_src); ?>" width="<?php echo esc_attr($width) ?>"
                                 height="<?php echo esc_attr($height) ?>" alt="<?php echo esc_attr($term_name); ?>">
                            </a>
                        <?php } ?>
                    </span>
                    <div class="cate-content">
                        <?php if (!empty($term_name)): ?>
                            <h4 class="cate-title"><a
                                        href="<?php echo esc_url($term_link) ?>"><?php esc_html_e($term_name); ?></a>
                            </h4>
                        <?php endif; ?>
                        <?php if (!empty($term_count) && $settings['show_count'] == 'yes'): ?>
                            <p class="cate-count"><?php echo sprintf(esc_html__('%s services', 'civi-framework'), $term_count) ?></p>
                        <?php endif; ?>
                    </div>
					<a class="link-box" href="<?php echo esc_url($term_link) ?>"></a>
                </div>
            </div>
        <?php }
    } ?>
        </div>
        </div>
    <?php }
}
