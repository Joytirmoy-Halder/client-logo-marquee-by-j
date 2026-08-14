<?php
/**
 * Client Logo Marquee widget.
 *
 * @package client-logo-marquee-by-j
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class CLMJ_Widget extends Widget_Base {

	public function get_name() {
		return 'client_logo_marquee_by_j';
	}

	public function get_title() {
		return esc_html__( 'Client Logo Marquee', 'client-logo-marquee-by-j' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_categories() {
		return array( 'by-j' );
	}

	public function get_keywords() {
		return array( 'client', 'clients', 'logo', 'logos', 'marquee', 'ticker', 'carousel', 'brands', 'partners', 'trusted by' );
	}

	public function get_style_depends() {
		return array( 'clmj-marquee' );
	}

	public function get_script_depends() {
		return array( 'clmj-marquee' );
	}

	protected function register_controls() {

		/* ------------------------------------------------------------- LOGOS */
		$this->start_controls_section(
			'section_logos',
			array(
				'label' => esc_html__( 'Client Logos', 'client-logo-marquee-by-j' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'resolution_guide',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					'<strong>%s</strong><br>%s<br><br><strong>%s</strong><br>%s<br><br><strong>%s</strong><br>%s',
					esc_html__( 'Image resolution guide', 'client-logo-marquee-by-j' ),
					esc_html__( 'Export every logo at 2x the height you set below, on a transparent background. For the default 44px height that is 88px tall, e.g. 320 x 88 px. Width can vary per logo, the marquee measures each one.', 'client-logo-marquee-by-j' ),
					esc_html__( 'Best format', 'client-logo-marquee-by-j' ),
					esc_html__( 'SVG for vector wordmarks (sharpest, smallest), otherwise PNG-24 with transparency. Trim empty padding from each file so logos optically match in size. Keep files under 40 KB.', 'client-logo-marquee-by-j' ),
					esc_html__( 'Using the Custom resolution', 'client-logo-marquee-by-j' ),
					esc_html__( 'Set the Height only and leave the Width empty, otherwise logos of different shapes get cropped to the same box. Custom sizes serve one fixed file instead of a responsive srcset, so only reach for it when the built-in sizes do not fit.', 'client-logo-marquee-by-j' )
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'logo',
			array(
				'label'   => esc_html__( 'Logo', 'client-logo-marquee-by-j' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$repeater->add_control(
			'client_name',
			array(
				'label'       => esc_html__( 'Client Name', 'client-logo-marquee-by-j' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Client name', 'client-logo-marquee-by-j' ),
				'description' => esc_html__( 'Used as the image alt text. Never shown on screen.', 'client-logo-marquee-by-j' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => esc_html__( 'Link (optional)', 'client-logo-marquee-by-j' ),
				'type'        => Controls_Manager::URL,
				'options'     => array( 'url', 'is_external', 'nofollow' ),
				'placeholder' => 'https://example.com',
				'label_block' => true,
			)
		);

		$this->add_control(
			'logos',
			array(
				'label'       => esc_html__( 'Logos', 'client-logo-marquee-by-j' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ client_name || "Logo" }}}',
				'default'     => array(
					array( 'client_name' => esc_html__( 'Client one', 'client-logo-marquee-by-j' ) ),
					array( 'client_name' => esc_html__( 'Client two', 'client-logo-marquee-by-j' ) ),
					array( 'client_name' => esc_html__( 'Client three', 'client-logo-marquee-by-j' ) ),
					array( 'client_name' => esc_html__( 'Client four', 'client-logo-marquee-by-j' ) ),
					array( 'client_name' => esc_html__( 'Client five', 'client-logo-marquee-by-j' ) ),
					array( 'client_name' => esc_html__( 'Client six', 'client-logo-marquee-by-j' ) ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'logo',
				'default'   => 'full',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'aria_label',
			array(
				'label'       => esc_html__( 'Accessible Label', 'client-logo-marquee-by-j' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Our clients', 'client-logo-marquee-by-j' ),
				'description' => esc_html__( 'Announced by screen readers to describe the strip.', 'client-logo-marquee-by-j' ),
				'label_block' => true,
				'separator'   => 'before',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------------------ MOTION */
		$this->start_controls_section(
			'section_motion',
			array(
				'label' => esc_html__( 'Marquee', 'client-logo-marquee-by-j' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'direction',
			array(
				'label'   => esc_html__( 'Direction', 'client-logo-marquee-by-j' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => array(
					'left'  => array(
						'title' => esc_html__( 'Right to left', 'client-logo-marquee-by-j' ),
						'icon'  => 'eicon-arrow-left',
					),
					'right' => array(
						'title' => esc_html__( 'Left to right', 'client-logo-marquee-by-j' ),
						'icon'  => 'eicon-arrow-right',
					),
				),
				'toggle'  => false,
			)
		);

		$this->add_responsive_control(
			'duration',
			array(
				'label'       => esc_html__( 'Loop Duration', 'client-logo-marquee-by-j' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 's' ),
				'range'       => array(
					's' => array(
						'min'  => 6,
						'max'  => 120,
						'step' => 1,
					),
				),
				'default'     => array(
					'unit' => 's',
					'size' => 38,
				),
				'tablet_default' => array(
					'unit' => 's',
					'size' => 30,
				),
				'mobile_default' => array(
					'unit' => 's',
					'size' => 22,
				),
				'description' => esc_html__( 'Seconds for one logo set to travel the full width. Higher is slower. Shorter screens usually want a shorter duration to keep the perceived speed the same.', 'client-logo-marquee-by-j' ),
				'selectors'   => array(
					'{{WRAPPER}} .clmj' => '--clmj-duration: {{SIZE}}s;',
				),
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => esc_html__( 'Space Between Logos', 'client-logo-marquee-by-j' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'vw' ),
				'range'      => array(
					'px' => array(
						'min' => 8,
						'max' => 220,
					),
					'em' => array(
						'min'  => 0.5,
						'max'  => 14,
						'step' => 0.1,
					),
					'vw' => array(
						'min'  => 1,
						'max'  => 14,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 72,
				),
				'tablet_default' => array(
					'unit' => 'px',
					'size' => 56,
				),
				'mobile_default' => array(
					'unit' => 'px',
					'size' => 40,
				),
				'selectors'  => array(
					'{{WRAPPER}} .clmj' => '--clmj-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'        => esc_html__( 'Pause On Hover', 'client-logo-marquee-by-j' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'pause_offscreen',
			array(
				'label'        => esc_html__( 'Pause When Off-Screen', 'client-logo-marquee-by-j' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => esc_html__( 'Stops the animation while the strip is out of view so it costs nothing to scroll past. Recommended.', 'client-logo-marquee-by-j' ),
			)
		);

		$this->add_control(
			'edge_fade',
			array(
				'label'        => esc_html__( 'Fade Edges', 'client-logo-marquee-by-j' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_responsive_control(
			'fade_width',
			array(
				'label'      => esc_html__( 'Fade Width', 'client-logo-marquee-by-j' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 400,
					),
					'%'  => array(
						'min' => 0,
						'max' => 30,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 140,
				),
				'mobile_default' => array(
					'unit' => 'px',
					'size' => 48,
				),
				'condition'  => array(
					'edge_fade' => 'yes',
				),
				'selectors'  => array(
					'{{WRAPPER}} .clmj' => '--clmj-fade: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------------- STYLE: LOGOS */
		$this->start_controls_section(
			'section_style_logos',
			array(
				'label' => esc_html__( 'Logos', 'client-logo-marquee-by-j' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'item_style',
			array(
				'label'   => esc_html__( 'Presentation', 'client-logo-marquee-by-j' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'plain',
				'options' => array(
					'plain' => esc_html__( 'Bare logos', 'client-logo-marquee-by-j' ),
					'card'  => esc_html__( 'Logos in cards', 'client-logo-marquee-by-j' ),
				),
			)
		);

		$this->add_responsive_control(
			'logo_height',
			array(
				'label'      => esc_html__( 'Logo Height', 'client-logo-marquee-by-j' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 12,
						'max' => 160,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 44,
				),
				'tablet_default' => array(
					'unit' => 'px',
					'size' => 40,
				),
				'mobile_default' => array(
					'unit' => 'px',
					'size' => 34,
				),
				'selectors'  => array(
					'{{WRAPPER}} .clmj' => '--clmj-logo-h: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'logo_max_width',
			array(
				'label'       => esc_html__( 'Logo Max Width', 'client-logo-marquee-by-j' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min' => 60,
						'max' => 420,
					),
				),
				'default'     => array(
					'unit' => 'px',
					'size' => 200,
				),
				'description' => esc_html__( 'Stops one very wide wordmark from dominating the strip.', 'client-logo-marquee-by-j' ),
				'selectors'   => array(
					'{{WRAPPER}} .clmj' => '--clmj-logo-max-w: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_idle',
			array(
				'label'     => esc_html__( 'Resting State', 'client-logo-marquee-by-j' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'grayscale',
			array(
				'label'      => esc_html__( 'Greyscale', 'client-logo-marquee-by-j' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'selectors'  => array(
					'{{WRAPPER}} .clmj' => '--clmj-gray: {{SIZE}}%;',
				),
			)
		);

		$this->add_control(
			'opacity',
			array(
				'label'      => esc_html__( 'Opacity', 'client-logo-marquee-by-j' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 55,
				),
				'selectors'  => array(
					'{{WRAPPER}} .clmj' => '--clmj-opacity: calc({{SIZE}} / 100);',
				),
			)
		);

		$this->add_control(
			'heading_hover',
			array(
				'label'     => esc_html__( 'Hover State', 'client-logo-marquee-by-j' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'grayscale_hover',
			array(
				'label'      => esc_html__( 'Greyscale', 'client-logo-marquee-by-j' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 0,
				),
				'selectors'  => array(
					'{{WRAPPER}} .clmj' => '--clmj-gray-hover: {{SIZE}}%;',
				),
			)
		);

		$this->add_control(
			'opacity_hover',
			array(
				'label'      => esc_html__( 'Opacity', 'client-logo-marquee-by-j' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'selectors'  => array(
					'{{WRAPPER}} .clmj' => '--clmj-opacity-hover: calc({{SIZE}} / 100);',
				),
			)
		);

		$this->add_control(
			'hover_scale',
			array(
				'label'      => esc_html__( 'Scale', 'client-logo-marquee-by-j' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min'  => 100,
						'max'  => 125,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 106,
				),
				'selectors'  => array(
					'{{WRAPPER}} .clmj' => '--clmj-hover-scale: calc({{SIZE}} / 100);',
				),
			)
		);

		$this->add_control(
			'transition',
			array(
				'label'      => esc_html__( 'Transition Speed', 'client-logo-marquee-by-j' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'ms' ),
				'range'      => array(
					'ms' => array(
						'min'  => 0,
						'max'  => 1200,
						'step' => 20,
					),
				),
				'default'    => array(
					'unit' => 'ms',
					'size' => 420,
				),
				'selectors'  => array(
					'{{WRAPPER}} .clmj' => '--clmj-t: {{SIZE}}ms;',
				),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------------- STYLE: CARDS */
		$this->start_controls_section(
			'section_style_cards',
			array(
				'label'     => esc_html__( 'Cards', 'client-logo-marquee-by-j' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'item_style' => 'card',
				),
			)
		);

		$this->add_responsive_control(
			'card_min_width',
			array(
				'label'      => esc_html__( 'Minimum Width', 'client-logo-marquee-by-j' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 80,
						'max' => 480,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 230,
				),
				'mobile_default' => array(
					'unit' => 'px',
					'size' => 170,
				),
				'description' => esc_html__( 'Keeps every card the same size regardless of how wide each logo is.', 'client-logo-marquee-by-j' ),
				'selectors'  => array(
					'{{WRAPPER}} .clmj' => '--clmj-card-min-w: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => esc_html__( 'Padding', 'client-logo-marquee-by-j' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => 26,
					'right'    => 34,
					'bottom'   => 26,
					'left'     => 34,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .clmj-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'client-logo-marquee-by-j' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => 18,
					'right'    => 18,
					'bottom'   => 18,
					'left'     => 18,
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .clmj-cell' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'card_tabs' );

		$this->start_controls_tab(
			'card_tab_normal',
			array(
				'label' => esc_html__( 'Normal', 'client-logo-marquee-by-j' ),
			)
		);

		$this->add_control(
			'card_bg',
			array(
				'label'     => esc_html__( 'Background', 'client-logo-marquee-by-j' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					'{{WRAPPER}} .clmj-cell' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .clmj-cell',
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width'  => array(
						'default' => array(
							'top'      => 1,
							'right'    => 1,
							'bottom'   => 1,
							'left'     => 1,
							'unit'     => 'px',
							'isLinked' => true,
						),
					),
					'color'  => array(
						'default' => '#EBEDF1',
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_shadow',
				'selector' => '{{WRAPPER}} .clmj-cell',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'card_tab_hover',
			array(
				'label' => esc_html__( 'Hover', 'client-logo-marquee-by-j' ),
			)
		);

		$this->add_control(
			'card_bg_hover',
			array(
				'label'     => esc_html__( 'Background', 'client-logo-marquee-by-j' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					'{{WRAPPER}} .clmj-item:hover .clmj-cell' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_border_hover',
			array(
				'label'     => esc_html__( 'Border Colour', 'client-logo-marquee-by-j' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#D7DBE4',
				'selectors' => array(
					'{{WRAPPER}} .clmj-item:hover .clmj-cell' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'           => 'card_shadow_hover',
				'selector'       => '{{WRAPPER}} .clmj-item:hover .clmj-cell',
				'fields_options' => array(
					'box_shadow_type' => array(
						'default' => 'yes',
					),
					'box_shadow'      => array(
						'default' => array(
							'horizontal' => 0,
							'vertical'   => 18,
							'blur'       => 40,
							'spread'     => -14,
							'color'      => 'rgba(4, 28, 86, 0.18)',
						),
					),
				),
			)
		);

		$this->add_control(
			'card_lift',
			array(
				'label'      => esc_html__( 'Lift', 'client-logo-marquee-by-j' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 24,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 6,
				),
				'selectors'  => array(
					'{{WRAPPER}} .clmj' => '--clmj-card-lift: -{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'card_sheen',
			array(
				'label'        => esc_html__( 'Sheen Sweep', 'client-logo-marquee-by-j' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => esc_html__( 'A soft highlight that sweeps across the card on hover.', 'client-logo-marquee-by-j' ),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Single logo image. Uses core's attachment helper so every logo ships with
	 * srcset, intrinsic width/height (no layout shift) and lazy loading.
	 */
	private function get_logo_html( $item, $settings ) {
		$image = ( isset( $item['logo'] ) && is_array( $item['logo'] ) ) ? $item['logo'] : array();
		$id    = ! empty( $image['id'] ) ? absint( $image['id'] ) : 0;
		$name  = ! empty( $item['client_name'] ) ? $item['client_name'] : '';
		$size  = ! empty( $settings['logo_size'] ) ? $settings['logo_size'] : 'full';

		/*
		 * Custom sizes are generated (and cached) by Elementor rather than by
		 * WordPress, so they need Elementor's own resolver. Leaving the width
		 * empty and setting only a height keeps each logo's aspect ratio.
		 */
		if ( $id && 'custom' === $size ) {
			$custom_src = Group_Control_Image_Size::get_attachment_image_src( $id, 'logo', $settings );

			if ( $custom_src ) {
				return sprintf(
					'<img class="clmj-logo" src="%1$s" alt="%2$s" loading="lazy" decoding="async">',
					esc_url( $custom_src ),
					esc_attr( $name )
				);
			}
		}

		if ( $id ) {
			return wp_get_attachment_image(
				$id,
				$size,
				false,
				array(
					'class'    => 'clmj-logo',
					'alt'      => $name,
					'loading'  => 'lazy',
					'decoding' => 'async',
				)
			);
		}

		if ( empty( $image['url'] ) ) {
			return '';
		}

		return sprintf(
			'<img class="clmj-logo" src="%1$s" alt="%2$s" loading="lazy" decoding="async">',
			esc_url( $image['url'] ),
			esc_attr( $name )
		);
	}

	/**
	 * One complete set of logos. The track holds two identical sets so the CSS
	 * animation can shift by exactly one set width and loop with no visible
	 * seam. Clones are hidden from assistive tech and taken out of the tab order.
	 *
	 * @param bool $is_clone Whether this set is a duplicate.
	 */
	private function get_set_html( $logos, $settings, $is_clone = false ) {
		$out = $is_clone
			? '<ul class="clmj-set" data-clmj-clone="" aria-hidden="true">'
			: '<ul class="clmj-set" data-clmj-set="">';

		foreach ( $logos as $item ) {
			$img = $this->get_logo_html( $item, $settings );

			if ( '' === $img ) {
				continue;
			}

			$url = ( isset( $item['link']['url'] ) && '' !== $item['link']['url'] ) ? $item['link']['url'] : '';

			if ( $url ) {
				$rel = array();

				if ( ! empty( $item['link']['nofollow'] ) ) {
					$rel[] = 'nofollow';
				}

				$target = '';

				if ( ! empty( $item['link']['is_external'] ) ) {
					$target = ' target="_blank"';
					$rel[]  = 'noopener';
				}

				$cell = sprintf(
					'<a class="clmj-cell" href="%1$s"%2$s%3$s%4$s>%5$s</a>',
					esc_url( $url ),
					$target,
					$rel ? ' rel="' . esc_attr( implode( ' ', $rel ) ) . '"' : '',
					$is_clone ? ' tabindex="-1"' : '',
					$img
				);
			} else {
				$cell = '<span class="clmj-cell">' . $img . '</span>';
			}

			$out .= '<li class="clmj-item">' . $cell . '</li>';
		}

		return $out . '</ul>';
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$logos    = ( ! empty( $settings['logos'] ) && is_array( $settings['logos'] ) ) ? $settings['logos'] : array();

		if ( empty( $logos ) ) {
			return;
		}

		$style   = ( isset( $settings['item_style'] ) && 'card' === $settings['item_style'] ) ? 'card' : 'plain';
		$classes = array( 'clmj', 'clmj--' . $style );

		if ( isset( $settings['edge_fade'] ) && 'yes' === $settings['edge_fade'] ) {
			$classes[] = 'clmj--fade';
		}

		if ( isset( $settings['pause_on_hover'] ) && 'yes' === $settings['pause_on_hover'] ) {
			$classes[] = 'clmj--pause-hover';
		}

		if ( isset( $settings['direction'] ) && 'right' === $settings['direction'] ) {
			$classes[] = 'clmj--reverse';
		}

		if ( 'card' === $style && isset( $settings['card_sheen'] ) && 'yes' === $settings['card_sheen'] ) {
			$classes[] = 'clmj--sheen';
		}

		$offscreen = ( isset( $settings['pause_offscreen'] ) && 'yes' === $settings['pause_offscreen'] ) ? '1' : '0';
		$label     = ! empty( $settings['aria_label'] ) ? $settings['aria_label'] : esc_html__( 'Our clients', 'client-logo-marquee-by-j' );

		$this->add_render_attribute(
			'wrapper',
			array(
				'class'                => $classes,
				'role'                 => 'region',
				'aria-label'           => $label,
				'data-clmj'            => '',
				'data-pause-offscreen' => $offscreen,
			)
		);
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="clmj-viewport">
				<div class="clmj-track">
					<?php
					// Markup is escaped inside the helpers above.
					echo $this->get_set_html( $logos, $settings, false ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $this->get_set_html( $logos, $settings, true );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</div>
			</div>
		</div>
		<?php
	}
}
