<?php
/**
 * Elementor Widget: CCoE Institutional Section & Page Builder Block
 *
 * @package CCOE_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CCOE_Elementor_Section_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ccoe_institutional_section';
	}

	public function get_title() {
		return __( 'CCoE Institutional Section', 'ccoe-core' );
	}

	public function get_icon() {
		return 'eicon-library-open';
	}

	public function get_categories() {
		return array( 'ccoe-widgets' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'CCoE Section Selection', 'ccoe-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'section_type',
			array(
				'label'   => __( 'Select Institutional Layout / Component', 'ccoe-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'homepage',
				'options' => array(
					'homepage'            => __( 'Full Homepage Layout', 'ccoe-core' ),
					'programmes_cards'    => __( 'Academic Programmes Showcase Cards', 'ccoe-core' ),
					'programmes_page'     => __( 'Full Academic Programmes Page (NCE, AAUA Degree, PGDE, Skills)', 'ccoe-core' ),
					'courses_page'        => __( 'Full Courses Catalog & PGDE Tables', 'ccoe-core' ),
					'about_page'          => __( 'About the College Section', 'ccoe-core' ),
					'vision_mission_page' => __( 'Our Vision & Mission Section', 'ccoe-core' ),
					'provost_page'        => __( 'Message from the Provost', 'ccoe-core' ),
					'admissions_page'     => __( 'Admissions Guidelines & Entry Requirements', 'ccoe-core' ),
					'events_grid'         => __( 'Upcoming Events & News Grid', 'ccoe-core' ),
					'admission_form'      => __( 'Provisional Admission Application Form', 'ccoe-core' ),
					'contact_page'        => __( 'Contact Information, Form & Google Map', 'ccoe-core' ),
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$type     = isset( $settings['section_type'] ) ? $settings['section_type'] : 'homepage';

		switch ( $type ) {
			case 'programmes_cards':
				echo CCOE_Shortcodes::render_major_programmes_cards(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
			case 'programmes_page':
				echo CCOE_Shortcodes::render_programmes_page(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
			case 'courses_page':
				echo CCOE_Shortcodes::render_courses_page(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
			case 'about_page':
				echo CCOE_Shortcodes::render_about_page(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
			case 'vision_mission_page':
				echo CCOE_Shortcodes::render_vision_mission_page(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
			case 'provost_page':
				echo CCOE_Shortcodes::render_provost_page(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
			case 'admissions_page':
				echo CCOE_Shortcodes::render_admissions_page(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
			case 'events_grid':
				echo CCOE_Shortcodes::render_events_grid( 6 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
			case 'admission_form':
				echo CCOE_Shortcodes::render_admission_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
			case 'contact_page':
				echo CCOE_Shortcodes::render_contact_page(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
			case 'homepage':
			default:
				echo CCOE_Shortcodes::render_homepage(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				break;
		}
	}
}
