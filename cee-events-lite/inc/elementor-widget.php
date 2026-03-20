<?php
if (!defined('ABSPATH')) exit;

class CEE_Events_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'cee_events_widget';
    }

    public function get_title() {
        return 'CEE Events';
    }

    public function get_icon() {
        return 'eicon-calendar';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_style',
            [
                'label' => 'Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_bg_color',
            [
                'label' => 'Item Background Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cee-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_border_color',
            [
                'label' => 'Item Border Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cee-item' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => 'Heading Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cee-item h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label' => 'Button Background Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cee-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_text_color',
            [
                'label' => 'Button Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .cee-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        echo do_shortcode('[cee_events]');
    }
}
