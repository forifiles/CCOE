<?php
/**
 * Plugin Name:       City College of Education Core (CCoE Core)
 * Plugin URI:        https://citycollegeofeducation.edu.ng/
 * Description:       Official core platform plugin for City College of Education, Mararaba, Nasarawa State. Provides Custom Post Types (Programmes, Courses, Events), Database-Backed Provisional Admission Application System, Elementor Widgets, Institutional Settings, and One-Click Content Seeding.
 * Version:           1.0.0
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Author:            City College of Education ICT Directorate
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ccoe-core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CCOE_CORE_VERSION', '1.0.0' );
define( 'CCOE_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'CCOE_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once CCOE_CORE_PATH . 'includes/class-ccoe-data.php';
require_once CCOE_CORE_PATH . 'includes/class-ccoe-post-types.php';
require_once CCOE_CORE_PATH . 'includes/class-ccoe-admissions.php';
require_once CCOE_CORE_PATH . 'includes/class-ccoe-settings.php';
require_once CCOE_CORE_PATH . 'includes/class-ccoe-shortcodes.php';
require_once CCOE_CORE_PATH . 'includes/class-ccoe-seeder.php';
require_once CCOE_CORE_PATH . 'includes/class-ccoe-elementor.php';

/**
 * Plugin Activation Hook:
 * 1. Registers CPTs & Taxonomies
 * 2. Creates database tables for Provisional Admissions & Contact Inquiries
 * 3. Seeds default institutional settings, 10 required pages, and real CCoE programmes/courses
 * 4. Flushes rewrite rules
 */
function ccoe_core_activate() {
	CCOE_Post_Types::register_post_types();
	CCOE_Post_Types::register_taxonomies();
	CCOE_Admissions::create_tables();
	CCOE_Admissions::ensure_protected_upload_dir();
	CCOE_Seeder::seed_all();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ccoe_core_activate' );

/**
 * Plugin Deactivation Hook
 */
function ccoe_core_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ccoe_core_deactivate' );

/**
 * Initialize Plugin Components
 */
function ccoe_core_init() {
	CCOE_Post_Types::init();
	CCOE_Admissions::init();
	CCOE_Settings::init();
	CCOE_Shortcodes::init();
	CCOE_Elementor::init();
}
add_action( 'plugins_loaded', 'ccoe_core_init' );

/**
 * Enqueue Frontend Styles and Scripts
 */
function ccoe_core_enqueue_assets() {
	wp_enqueue_style(
		'ccoe-google-fonts',
		'https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:ital,wght@0,400;0,700;1,400&family=Crimson+Pro:ital,wght@0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'ccoe-frontend',
		CCOE_CORE_URL . 'assets/css/ccoe-frontend.css',
		array(),
		CCOE_CORE_VERSION
	);

	wp_enqueue_script(
		'ccoe-frontend',
		CCOE_CORE_URL . 'assets/js/ccoe-frontend.js',
		array(),
		CCOE_CORE_VERSION,
		true
	);

	wp_localize_script(
		'ccoe-frontend',
		'ccoeData',
		array(
			'ajaxUrl'          => admin_url( 'admin-ajax.php' ),
			'coursesByProgram' => CCOE_Data::get_courses_by_programme_type(),
			'statesLgas'       => CCOE_Data::get_nigerian_states_and_lgas(),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'ccoe_core_enqueue_assets', 20 );

/**
 * Add SEO Meta Description & OpenGraph tags on frontend pages
 */
function ccoe_core_seo_meta_tags() {
	if ( is_admin() ) {
		return;
	}
	$settings    = CCOE_Settings::get_settings();
	$description = $settings['vision_statement'] . ' ' . $settings['address'];
	if ( is_singular() ) {
		global $post;
		if ( ! empty( $post->post_excerpt ) ) {
			$description = wp_strip_all_tags( $post->post_excerpt );
		}
	}
	echo '<meta name="description" content="' . esc_attr( wp_trim_words( $description, 28, '...' ) ) . '" />' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( $settings['institution_name'] ) . '" />' . "\n";
	echo '<meta property="og:type" content="website" />' . "\n";
}
add_action( 'wp_head', 'ccoe_core_seo_meta_tags', 5 );

/**
 * Output Official CCoE Crest Logo as Site Favicon (Frontend & WP Admin)
 */
function ccoe_core_favicon_tags() {
	if ( function_exists( 'has_site_icon' ) && has_site_icon() ) {
		return;
	}
	$favicon_url = get_template_directory_uri() . '/images/ccoe/favicon.png';
	$logo_url    = get_template_directory_uri() . '/images/ccoe/ccoe-logo.png';
	echo '<link rel="icon" type="image/png" sizes="128x128" href="' . esc_url( $favicon_url ) . '" />' . "\n";
	echo '<link rel="shortcut icon" href="' . esc_url( $favicon_url ) . '" />' . "\n";
	echo '<link rel="apple-touch-icon" href="' . esc_url( $logo_url ) . '" />' . "\n";
}
add_action( 'wp_head', 'ccoe_core_favicon_tags', 4 );
add_action( 'admin_head', 'ccoe_core_favicon_tags', 4 );
