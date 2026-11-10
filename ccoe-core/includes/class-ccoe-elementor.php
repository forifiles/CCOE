<?php
/**
 * Native Elementor & Elementor Pro Integration for City College of Education
 * Registers custom CCoE Elementor Widgets and Widget Category when Elementor is active.
 *
 * @package CCOE_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CCOE_Elementor {

	public static function init() {
		add_action( 'elementor/elements/categories_registered', array( __CLASS__, 'register_widget_category' ) );
		add_action( 'elementor/widgets/register', array( __CLASS__, 'register_widgets' ) );
	}

	/**
	 * Register "City College of Education" Elementor Category
	 */
	public static function register_widget_category( $elements_manager ) {
		$elements_manager->add_category(
			'ccoe-widgets',
			array(
				'title' => __( 'City College of Education (CCoE)', 'ccoe-core' ),
				'icon'  => 'eicon-welcome',
			)
		);
	}

	/**
	 * Register CCoE Custom Elementor Widgets
	 */
	public static function register_widgets( $widgets_manager ) {
		if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
			return;
		}

		require_once CCOE_CORE_PATH . 'includes/widgets/class-ccoe-elementor-section-widget.php';
		$widgets_manager->register( new CCOE_Elementor_Section_Widget() );
	}
}
