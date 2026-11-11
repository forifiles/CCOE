<?php
/**
 * Automatic Page, Navigation Menu, Programme, Course & Event Seeder
 *
 * @package CCOE_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CCOE_Seeder {

	/**
	 * Seed all required pages, menus, and CPT entries
	 */
	public static function seed_all( $force = false ) {
		if ( ! $force && get_option( 'ccoe_core_initial_seed_done' ) ) {
			return;
		}

		// 1. Save default institutional settings if not yet set
		if ( false === get_option( CCOE_Settings::OPTION_KEY ) ) {
			update_option( CCOE_Settings::OPTION_KEY, CCOE_Data::get_default_settings() );
		}

		// 2. Create all 10 required pages
		$pages = array(
			'home'                             => array(
				'title'   => 'Home',
				'content' => '[ccoe_homepage]',
			),
			'about-us'                         => array(
				'title'   => 'About Us',
				'content' => '[ccoe_about_page]',
			),
			'our-vision-and-mission'           => array(
				'title'   => 'Our Vision and Mission',
				'content' => '[ccoe_vision_mission_page]',
			),
			'message-from-the-provost'         => array(
				'title'   => 'Message from the Provost',
				'content' => '[ccoe_provost_page]',
			),
			'academic-programmes'              => array(
				'title'   => 'Academic Programmes',
				'content' => '[ccoe_programmes_page]',
			),
			'courses'                          => array(
				'title'   => 'Courses',
				'content' => '[ccoe_courses_page]',
			),
			'admissions'                       => array(
				'title'   => 'Admissions & Provisional Application',
				'content' => '[ccoe_admission_form]',
			),
			'news-and-events'                  => array(
				'title'   => 'News and Events',
				'content' => '[ccoe_news_events_page]',
			),
			'contact-us'                       => array(
				'title'   => 'Contact Us',
				'content' => '[ccoe_contact_page]',
			),
			'apply-for-provisional-admission'  => array(
				'title'   => 'Admissions & Apply',
				'content' => '[ccoe_admission_form]',
			),
		);

		$page_ids = array();
		$order    = 1;

		foreach ( $pages as $slug => $p_data ) {
			$existing = get_page_by_path( $slug );
			if ( $existing ) {
				$page_ids[ $slug ] = $existing->ID;
				if ( $force ) {
					wp_update_post(
						array(
							'ID'           => $existing->ID,
							'post_content' => $p_data['content'],
						)
					);
				}
			} else {
				$page_ids[ $slug ] = wp_insert_post(
					array(
						'post_title'   => $p_data['title'],
						'post_name'    => $slug,
						'post_content' => $p_data['content'],
						'post_status'  => 'publish',
						'post_type'    => 'page',
						'menu_order'   => $order,
					)
				);
			}
			update_post_meta( $page_ids[ $slug ], '_education_zone_sidebar_layout', 'no-sidebar' );
			++$order;
		}

		// 3. Configure WordPress Site Identity, Permalinks & Static Front Page
		update_option( 'blogname', 'City College of Education, Mararaba' );
		update_option( 'blogdescription', 'Education for Self Employment' );

		if ( ! empty( $page_ids['home'] ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $page_ids['home'] );
		}

		// Disable Education Zone's default demo home sections so our custom Front Page / Elementor page renders cleanly
		set_theme_mod( 'education_zone_ed_slider_section', false );
		set_theme_mod( 'education_zone_ed_info_section', false );
		set_theme_mod( 'education_zone_ed_welcome_section', false );
		set_theme_mod( 'education_zone_ed_courses_section', false );
		set_theme_mod( 'education_zone_ed_extra_info_section', false );
		set_theme_mod( 'education_zone_ed_choose_section', false );
		set_theme_mod( 'education_zone_ed_testimonial_section', false );
		set_theme_mod( 'education_zone_ed_blog_section', false );
		set_theme_mod( 'education_zone_ed_gallery_section', false );
		set_theme_mod( 'education_zone_ed_search_section', false );
		set_theme_mod( 'education_zone_phone', '08032920100, 08058431596' );
		set_theme_mod( 'education_zone_email', 'info@citycollegeofeducation.edu.ng' );
		set_theme_mod( 'education_zone_address', 'No. 1 City College Road, Sani Abacha Road Estate, Mararaba-Karu, Nasarawa State' );
		set_theme_mod( 'education_zone_button_label', 'Apply Now' );
		set_theme_mod( 'education_zone_button_url', site_url( '/apply-for-provisional-admission/' ) );

		// 4. Create & Assign Primary and Secondary Navigation Menus
		self::seed_navigation_menus( $page_ids );

		// 5. Seed Programmes, Courses & Events CPTs
		self::seed_cpt_content();

		update_option( 'ccoe_core_initial_seed_done', 1 );
	}

	/**
	 * Create Primary and Quick-Links Menus
	 */
	private static function seed_navigation_menus( $page_ids ) {
		$menu_name = 'CCoE Primary Navigation';
		$menu_obj  = wp_get_nav_menu_object( $menu_name );

		// Streamlined navbar items (excluding 'our-vision-and-mission', 'contact-us', and duplicate 'admissions')
		$nav_slugs = array(
			'home'                            => 'Home',
			'message-from-the-provost'        => 'Provost\'s Message',
			'academic-programmes'             => 'Academic Programmes',
			'courses'                         => 'Courses',
			'news-and-events'                 => 'News & Events',
			'about-us'                        => 'About Us',
			'apply-for-provisional-admission' => 'Admissions & Apply',
		);

		if ( ! $menu_obj ) {
			$menu_id = wp_create_nav_menu( $menu_name );
			if ( ! is_wp_error( $menu_id ) ) {
				$parent_items = array();
				foreach ( $nav_slugs as $slug => $label ) {
					if ( ! empty( $page_ids[ $slug ] ) ) {
						$parent_items[ $slug ] = wp_update_nav_menu_item(
							$menu_id,
							0,
							array(
								'menu-item-title'     => $label,
								'menu-item-object-id' => $page_ids[ $slug ],
								'menu-item-object'    => 'page',
								'menu-item-type'      => 'post_type',
								'menu-item-status'    => 'publish',
							)
						);
					}
				}

				// Add dropdown child items under Academic Programmes (parent remains clickable)
				if ( ! empty( $parent_items['academic-programmes'] ) ) {
					$prog_url = site_url( '/academic-programmes/' );
					$prog_children = array(
						'All Academic Programmes'                 => $prog_url,
						'NCE Programmes (7 Schools — NCCE 2026)'  => $prog_url . '#nce-schools',
						'B.Ed. / B.Sc. (Ed.) Degrees (AAUA)'      => $prog_url . '#degree-aaua',
						'PGDE (AAUA) & PDE Diplomas'              => site_url( '/courses/#pgde-courses' ),
						'Skills Acquisition & Entrepreneurship'   => $prog_url . '#skills-department',
					);
					foreach ( $prog_children as $child_title => $child_url ) {
						wp_update_nav_menu_item(
							$menu_id,
							0,
							array(
								'menu-item-title'     => $child_title,
								'menu-item-url'       => $child_url,
								'menu-item-type'      => 'custom',
								'menu-item-parent-id' => $parent_items['academic-programmes'],
								'menu-item-status'    => 'publish',
							)
						);
					}
				}

				// Add dropdown child items under Courses (parent remains clickable)
				if ( ! empty( $parent_items['courses'] ) ) {
					$courses_url = site_url( '/courses/' );
					$course_children = array(
						'All Courses Directory'             => $courses_url,
						'PGDE Semester Courses (AAUA)'      => $courses_url . '#pgde-courses',
						'NCE Courses by School (NCCE 2026)' => $courses_url . '#nce-courses',
						'AAUA Affiliated Degree Courses'    => $courses_url . '#degree-courses',
						'Vocational & Digital Skills'       => site_url( '/academic-programmes/#skills-department' ),
					);
					foreach ( $course_children as $child_title => $child_url ) {
						wp_update_nav_menu_item(
							$menu_id,
							0,
							array(
								'menu-item-title'     => $child_title,
								'menu-item-url'       => $child_url,
								'menu-item-type'      => 'custom',
								'menu-item-parent-id' => $parent_items['courses'],
								'menu-item-status'    => 'publish',
							)
						);
					}
				}

				$locations              = get_theme_mod( 'nav_menu_locations', array() );
				$locations['primary']   = $menu_id;
				$locations['secondary'] = $menu_id;
				set_theme_mod( 'nav_menu_locations', $locations );
			}
		}
	}

	/**
	 * Seed Academic Programmes, Courses, and Events into Custom Post Types
	 */
	private static function seed_cpt_content() {
		// Seed NCE Schools as Programmes
		foreach ( CCOE_Data::get_nce_schools() as $school ) {
			$slug = sanitize_title( 'nce-' . $school['name'] );
			$existing = get_page_by_path( $slug, OBJECT, 'ccoe_programme' );
			if ( ! $existing ) {
				$pid = wp_insert_post(
					array(
						'post_title'   => $school['name'] . ' (NCE)',
						'post_name'    => $slug,
						'post_content' => $school['description'],
						'post_excerpt' => $school['description'],
						'post_status'  => 'publish',
						'post_type'    => 'ccoe_programme',
					)
				);
				if ( $pid && ! is_wp_error( $pid ) ) {
					update_post_meta( $pid, '_ccoe_programme_badge', 'NCCE 2026 Minimum Standard' );
					update_post_meta( $pid, '_ccoe_programme_duration', $school['duration'] );
					update_post_meta( $pid, '_ccoe_programme_affiliation', 'National Commission for Colleges of Education (NCCE)' );
					update_post_meta( $pid, '_ccoe_programme_courses_list', implode( "\n", $school['programmes'] ) );
					update_post_meta( $pid, '_ccoe_programme_requirements', 'Minimum of 4–5 O\'Level credits including English Language and Mathematics in not more than two sittings.' );
					wp_set_object_terms( $pid, 'NCE', 'ccoe_programme_level' );
					wp_set_object_terms( $pid, $school['name'], 'ccoe_school' );
				}
			}
		}

		// Seed AAUA Degree Departments as Programmes
		foreach ( CCOE_Data::get_degree_departments() as $dept ) {
			$slug = sanitize_title( 'degree-aaua-' . $dept['department'] );
			$existing = get_page_by_path( $slug, OBJECT, 'ccoe_programme' );
			if ( ! $existing ) {
				$pid = wp_insert_post(
					array(
						'post_title'   => 'Department of ' . $dept['department'] . ' (B.Ed. / B.Sc. Ed. — AAUA)',
						'post_name'    => $slug,
						'post_content' => 'Bachelor\'s degree programmes in ' . $dept['department'] . ' offered in affiliation with Adekunle Ajasin University, Akungba-Akoko (AAUA), Ondo State.',
						'post_excerpt' => 'Affiliated with Adekunle Ajasin University, Akungba-Akoko (AAUA), Ondo State.',
						'post_status'  => 'publish',
						'post_type'    => 'ccoe_programme',
					)
				);
				if ( $pid && ! is_wp_error( $pid ) ) {
					update_post_meta( $pid, '_ccoe_programme_badge', 'Degree (Affiliated with AAUA)' );
					update_post_meta( $pid, '_ccoe_programme_duration', $dept['duration'] );
					update_post_meta( $pid, '_ccoe_programme_affiliation', $dept['affiliation'] );
					update_post_meta( $pid, '_ccoe_programme_courses_list', implode( "\n", $dept['programmes'] ) );
					update_post_meta( $pid, '_ccoe_programme_requirements', 'Five (5) O\'Level credit passes including English Language and Mathematics. Direct Entry requires NCE or ND.' );
					wp_set_object_terms( $pid, 'Degree (AAUA)', 'ccoe_programme_level' );
					wp_set_object_terms( $pid, $dept['department'], 'ccoe_school' );
				}
			}
		}

		// Seed PGDE Courses into ccoe_course CPT
		$pgde_courses = CCOE_Data::get_pgde_courses();
		foreach ( array( 'First Semester' => $pgde_courses['first_semester'], 'Second Semester' => $pgde_courses['second_semester'] ) as $sem_label => $courses ) {
			foreach ( $courses as $c ) {
				$slug     = sanitize_title( $c['code'] . '-' . $c['title'] );
				$existing = get_page_by_path( $slug, OBJECT, 'ccoe_course' );
				if ( ! $existing ) {
					$cid = wp_insert_post(
						array(
							'post_title'   => $c['code'] . ' — ' . $c['title'],
							'post_name'    => $slug,
							'post_content' => sprintf( '%s (%s) is a %d-unit compulsory %s course in the Postgraduate Diploma in Education (PGDE) programme in affiliation with Adekunle Ajasin University, Akungba-Akoko (AAUA).', $c['title'], $c['code'], $c['units'], $sem_label ),
							'post_status'  => 'publish',
							'post_type'    => 'ccoe_course',
						)
					);
					if ( $cid && ! is_wp_error( $cid ) ) {
						update_post_meta( $cid, '_ccoe_course_code', $c['code'] );
						update_post_meta( $cid, '_ccoe_course_units', $c['units'] );
						update_post_meta( $cid, '_ccoe_course_status', $c['status'] );
						update_post_meta( $cid, '_ccoe_course_semester', $sem_label );
						wp_set_object_terms( $cid, 'PGDE (AAUA)', 'ccoe_programme_level' );
					}
				}
			}
		}

		// Seed Default College Events into ccoe_event CPT
		foreach ( CCOE_Data::get_default_events() as $ev ) {
			$slug     = sanitize_title( $ev['title'] );
			$existing = get_page_by_path( $slug, OBJECT, 'ccoe_event' );
			if ( ! $existing ) {
				$eid = wp_insert_post(
					array(
						'post_title'   => $ev['title'],
						'post_name'    => $slug,
						'post_content' => $ev['description'],
						'post_excerpt' => $ev['description'],
						'post_status'  => 'publish',
						'post_type'    => 'ccoe_event',
					)
				);
				if ( $eid && ! is_wp_error( $eid ) ) {
					update_post_meta( $eid, '_ccoe_event_date', $ev['date'] );
					update_post_meta( $eid, '_ccoe_event_time', $ev['time'] );
					update_post_meta( $eid, '_ccoe_event_venue', $ev['venue'] );
					update_post_meta( $eid, '_ccoe_event_image_file', $ev['image'] );
					wp_set_object_terms( $eid, $ev['category'], 'ccoe_event_category' );
				}
			}
		}
	}
}
