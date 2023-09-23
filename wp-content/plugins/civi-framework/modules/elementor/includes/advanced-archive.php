<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Plugin;

defined('ABSPATH') || exit;

Plugin::instance()->widgets_manager->register(new Widget_Advanced_Archive());

class Widget_Advanced_Archive extends Widget_Base
{

    public function get_name()
    {
        return 'civi-advanced-archive';
    }

    public function get_title()
    {
        return esc_html__('Advanced Archive', 'civi-framework');
    }

    public function get_icon()
    {
        return 'civi-badge eicon-archive-posts';
    }

    public function get_keywords()
    {
        return ['jobs', 'companies', 'candidate'];
    }

    protected function register_controls()
    {
        $this->add_content_section();
        $this->add_sidebar_section();
    }

    private function add_content_section()
    {
        $this->start_controls_section('content_section', [
            'label' => esc_html__('Content', 'civi-framework'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('post_type', [
            'label' => esc_html__('Post Type', 'civi-framework'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'jobs' => esc_html__('Jobs', 'civi-framework'),
                'company' => esc_html__('Companies', 'civi-framework'),
                'candidate' => esc_html__('Candidates', 'civi-framework'),
            ],
            'default' => 'jobs',
        ]);

        $this->add_control(
            'color_featured',
            [
                'label' => esc_html__('Color Featured ', 'civi-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .civi-jobs-featured ' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .civi-candidate-featured ' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .civi-company-featured ' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function add_sidebar_section()
    {
        $this->start_controls_section('sidebar_section', [
            'label' => esc_html__('Sidebar', 'civi-framework'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control(
            'show_count',
            [
                'label' => esc_html__('Show Count', 'civi-framework'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'sidebar_checkbox_style',
            [
                'label' => esc_html__('Check Box Style', 'civi-framework'),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'square' => esc_html__('Square', 'civi-framework'),
                    'round' => esc_html__('Round', 'civi-framework'),
                ),
                'default' => 'square',
                'label_block' => true,
                'prefix_class' => 'civi-checkbox-',
            ]
        );

        $this->add_control(
            'sidebar_range',
            [
                'label' => esc_html__('Slider Range Color', 'civi-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ui-slider .ui-slider-range' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} #slider-range .ui-state-default' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'sidebar_background',
            [
                'label' => esc_html__('Background Color', 'civi-framework'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .inner-content .inner-filter' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control('sidebar_space', [
            'label'     => esc_html__('Spacing', 'civi'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .inner-content .inner-filter' => 'margin-right: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('sidebar_padding', [
            'label' => esc_html__('Padding', 'civi-framework'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .inner-content .inner-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('sidebar_border_radius', [
            'label' => esc_html__('Border Radius', 'civi-framework'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .inner-content .inner-filter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('wrapper', 'class', 'civi-advanced-archive');

        if($settings['show_count'] !== 'yes'){
            $this->add_render_attribute('wrapper', 'class', 'off-count');
        }
		
        if($settings['post_type'] == 'jobs'){
            $jobs_map_postion = $map_event = '';
            $content_jobs = civi_get_option('archive_jobs_layout', 'layout-list');
            $content_jobs = !empty($_GET['layout']) ? Civi_Helper::civi_clean(wp_unslash($_GET['layout'])) : $content_jobs;
            $enable_jobs_show_map = civi_get_option('enable_jobs_show_map', 1);
            $enable_jobs_show_map = !empty($_GET['has_map']) ? Civi_Helper::civi_clean(wp_unslash($_GET['has_map'])) : $enable_jobs_show_map;

            if ($enable_jobs_show_map == 1) {
                $archive_jobs_filter = 'filter-canvas';
                $jobs_map_postion = civi_get_option('jobs_map_postion');
                $jobs_map_postion = !empty($_GET['map']) ? Civi_Helper::civi_clean(wp_unslash($_GET['map'])) : $jobs_map_postion;
                if ($jobs_map_postion == 'map-right') {
                    $map_event = 'map-event';
                }
            } else if($content_jobs == 'layout-full') {
                $archive_jobs_filter = 'filter-canvas';
            } else {
                $archive_jobs_filter = civi_get_option('jobs_filter_sidebar_option', 'filter-left');
            };
            $archive_jobs_filter = !empty($_GET['filter']) ? Civi_Helper::civi_clean(wp_unslash($_GET['filter'])) : $archive_jobs_filter;
            $archive_classes = array('archive-layout', 'archive-jobs', $archive_jobs_filter, $map_event, $jobs_map_postion);
        } elseif ($settings['post_type'] == 'company'){
            $company_map_postion = $map_event = '';
            $content_company              = civi_get_option('archive_company_layout', 'layout-list');
            $enable_company_show_map = civi_get_option('enable_company_show_map', 1);
            $enable_company_show_map = !empty($_GET['has_map']) ? Civi_Helper::civi_clean(wp_unslash($_GET['has_map'])) : $enable_company_show_map;

            $map_event = '';
            if ($enable_company_show_map == 1) {
                $archive_company_filter = 'filter-canvas';
                $company_map_postion = civi_get_option('company_map_postion');
                $company_map_postion = !empty($_GET['map']) ? Civi_Helper::civi_clean(wp_unslash($_GET['map'])) : $company_map_postion;
                if ($company_map_postion == 'map-right') {
                    $map_event = 'map-event';
                }
            } else {
                $archive_company_filter = civi_get_option('company_filter_sidebar_option', 'filter-left');
            };
            $archive_company_filter = !empty($_GET['filter']) ? Civi_Helper::civi_clean(wp_unslash($_GET['filter'])) : $archive_company_filter;
            $content_company = !empty($_GET['layout']) ? Civi_Helper::civi_clean(wp_unslash($_GET['layout'])) : $content_company;
            $archive_classes = array('archive-layout', 'archive-company', $archive_company_filter,$map_event, $company_map_postion);
        } elseif ($settings['post_type'] == 'candidate'){
            $map_event = $candidate_map_postion = '';
            $content_candidate = civi_get_option('archive_candidate_layout', 'layout-list');
            $enable_candidate_show_map = civi_get_option('enable_candidate_show_map', 1);
            $enable_candidate_show_map = !empty($_GET['has_map']) ? Civi_Helper::civi_clean(wp_unslash($_GET['has_map'])) : $enable_candidate_show_map;

            if ($enable_candidate_show_map == 1) {
                $archive_candidate_filter = 'filter-canvas';
                $candidate_map_postion = civi_get_option('candidate_map_postion');
                $candidate_map_postion = !empty($_GET['map']) ? Civi_Helper::civi_clean(wp_unslash($_GET['map'])) : $candidate_map_postion;
                if ($candidate_map_postion == 'map-right') {
                    $map_event = 'map-event';
                }
            } else {
                $archive_candidate_filter = civi_get_option('candidate_filter_sidebar_option', 'filter-left');
            };
            $archive_candidate_filter = !empty($_GET['filter']) ? Civi_Helper::civi_clean(wp_unslash($_GET['filter'])) : $archive_candidate_filter;
            $content_candidate = !empty($_GET['layout']) ? Civi_Helper::civi_clean(wp_unslash($_GET['layout'])) : $content_candidate;
            $archive_classes = array('archive-layout', 'archive-candidates', $archive_candidate_filter,$map_event, $candidate_map_postion);
        }

        echo '<div ' . $this->get_render_attribute_string('wrapper') . '>';
        echo '<div class="' . join(' ', $archive_classes) . '">';
        if($settings['post_type'] == 'jobs'){
            civi_get_template('jobs/archive/layout/layout-default.php');
        } elseif ($settings['post_type'] == 'company'){
            civi_get_template('company/archive/layout/layout-default.php');
        } elseif ($settings['post_type'] == 'candidate'){
            civi_get_template('candidate/archive/layout/layout-default.php');
        }
        echo '</div>';
        echo '</div>';
    }
}
