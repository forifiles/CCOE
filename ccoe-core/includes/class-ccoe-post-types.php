<?php
/**
 * Custom Post Types, Taxonomies, and Admin Meta Boxes for CCoE
 *
 * @package CCOE_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CCOE_Post_Types {

	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 10 );
		add_action( 'add_meta_boxes', array( __CLASS__, 'register_meta_boxes' ) );
		add_action( 'save_post', array( __CLASS__, 'save_meta_boxes' ), 10, 2 );
		add_filter( 'the_content', array( __CLASS__, 'enhance_single_cpt_content' ) );
	}

	/**
	 * Register Custom Taxonomies
	 */
	public static function register_taxonomies() {
		// 1. School / Faculty / Department Taxonomy
		register_taxonomy(
			'ccoe_school',
			array( 'ccoe_programme', 'ccoe_course' ),
			array(
				'labels'            => array(
					'name'          => __( 'Schools & Departments', 'ccoe-core' ),
					'singular_name' => __( 'School / Department', 'ccoe-core' ),
					'search_items'  => __( 'Search Schools', 'ccoe-core' ),
					'all_items'     => __( 'All Schools & Departments', 'ccoe-core' ),
					'edit_item'     => __( 'Edit School / Department', 'ccoe-core' ),
					'add_new_item'  => __( 'Add New School / Department', 'ccoe-core' ),
					'menu_name'     => __( 'Schools / Faculties', 'ccoe-core' ),
				),
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => 'school' ),
			)
		);

		// 2. Programme Level Taxonomy (NCE, Degree, PGDE, PDE, Skills)
		register_taxonomy(
			'ccoe_programme_level',
			array( 'ccoe_programme', 'ccoe_course' ),
			array(
				'labels'            => array(
					'name'          => __( 'Programme Levels', 'ccoe-core' ),
					'singular_name' => __( 'Programme Level', 'ccoe-core' ),
					'all_items'     => __( 'All Programme Levels', 'ccoe-core' ),
					'add_new_item'  => __( 'Add New Programme Level', 'ccoe-core' ),
					'menu_name'     => __( 'Programme Levels', 'ccoe-core' ),
				),
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => 'programme-level' ),
			)
		);

		// 3. Event Category Taxonomy
		register_taxonomy(
			'ccoe_event_category',
			array( 'ccoe_event' ),
			array(
				'labels'            => array(
					'name'          => __( 'Event Categories', 'ccoe-core' ),
					'singular_name' => __( 'Event Category', 'ccoe-core' ),
					'all_items'     => __( 'All Event Categories', 'ccoe-core' ),
					'add_new_item'  => __( 'Add New Event Category', 'ccoe-core' ),
					'menu_name'     => __( 'Event Categories', 'ccoe-core' ),
				),
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => 'event-category' ),
			)
		);
	}

	/**
	 * Register Custom Post Types: Programmes, Courses, Events
	 */
	public static function register_post_types() {
		// 1. Academic Programmes CPT
		register_post_type(
			'ccoe_programme',
			array(
				'labels'       => array(
					'name'               => __( 'Academic Programmes', 'ccoe-core' ),
					'singular_name'      => __( 'Academic Programme', 'ccoe-core' ),
					'add_new'            => __( 'Add New Programme', 'ccoe-core' ),
					'add_new_item'       => __( 'Add New Academic Programme', 'ccoe-core' ),
					'edit_item'          => __( 'Edit Academic Programme', 'ccoe-core' ),
					'new_item'           => __( 'New Academic Programme', 'ccoe-core' ),
					'view_item'          => __( 'View Programme', 'ccoe-core' ),
					'search_items'       => __( 'Search Programmes', 'ccoe-core' ),
					'not_found'          => __( 'No programmes found.', 'ccoe-core' ),
					'menu_name'          => __( 'Programmes', 'ccoe-core' ),
				),
				'public'       => true,
				'has_archive'  => true,
				'menu_icon'    => 'dashicons-welcome-learn-more',
				'menu_position'=> 21,
				'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
				'show_in_rest' => true,
				'rewrite'      => array( 'slug' => 'programmes' ),
			)
		);

		// 2. Courses CPT
		register_post_type(
			'ccoe_course',
			array(
				'labels'       => array(
					'name'               => __( 'Courses', 'ccoe-core' ),
					'singular_name'      => __( 'Course', 'ccoe-core' ),
					'add_new'            => __( 'Add New Course', 'ccoe-core' ),
					'add_new_item'       => __( 'Add New Course', 'ccoe-core' ),
					'edit_item'          => __( 'Edit Course', 'ccoe-core' ),
					'new_item'           => __( 'New Course', 'ccoe-core' ),
					'view_item'          => __( 'View Course', 'ccoe-core' ),
					'search_items'       => __( 'Search Courses', 'ccoe-core' ),
					'not_found'          => __( 'No courses found.', 'ccoe-core' ),
					'menu_name'          => __( 'Courses', 'ccoe-core' ),
				),
				'public'       => true,
				'has_archive'  => true,
				'menu_icon'    => 'dashicons-book-alt',
				'menu_position'=> 22,
				'supports'     => array( 'title', 'editor', 'excerpt', 'page-attributes' ),
				'show_in_rest' => true,
				'rewrite'      => array( 'slug' => 'courses-catalog' ),
			)
		);

		// 3. Events CPT
		register_post_type(
			'ccoe_event',
			array(
				'labels'       => array(
					'name'               => __( 'College Events', 'ccoe-core' ),
					'singular_name'      => __( 'Event', 'ccoe-core' ),
					'add_new'            => __( 'Add New Event', 'ccoe-core' ),
					'add_new_item'       => __( 'Add New College Event', 'ccoe-core' ),
					'edit_item'          => __( 'Edit Event', 'ccoe-core' ),
					'new_item'           => __( 'New Event', 'ccoe-core' ),
					'view_item'          => __( 'View Event', 'ccoe-core' ),
					'search_items'       => __( 'Search Events', 'ccoe-core' ),
					'not_found'          => __( 'No events found.', 'ccoe-core' ),
					'menu_name'          => __( 'Events', 'ccoe-core' ),
				),
				'public'       => true,
				'has_archive'  => true,
				'menu_icon'    => 'dashicons-calendar-alt',
				'menu_position'=> 23,
				'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
				'show_in_rest' => true,
				'rewrite'      => array( 'slug' => 'events' ),
			)
		);
	}

	/**
	 * Register Admin Meta Boxes
	 */
	public static function register_meta_boxes() {
		add_meta_box(
			'ccoe_programme_details',
			__( 'Programme Details (Duration, Affiliation, Courses & Entry Requirements)', 'ccoe-core' ),
			array( __CLASS__, 'render_programme_meta_box' ),
			'ccoe_programme',
			'normal',
			'high'
		);

		add_meta_box(
			'ccoe_course_details',
			__( 'Course Details (Code, Credit Units, Semester & Status)', 'ccoe-core' ),
			array( __CLASS__, 'render_course_meta_box' ),
			'ccoe_course',
			'normal',
			'high'
		);

		add_meta_box(
			'ccoe_event_details',
			__( 'Event Schedule & Venue Details', 'ccoe-core' ),
			array( __CLASS__, 'render_event_meta_box' ),
			'ccoe_event',
			'normal',
			'high'
		);
	}

	/**
	 * Render Programme Meta Box
	 */
	public static function render_programme_meta_box( $post ) {
		wp_nonce_field( 'ccoe_save_programme_meta', 'ccoe_programme_meta_nonce' );
		$duration       = get_post_meta( $post->ID, '_ccoe_programme_duration', true );
		$affiliation    = get_post_meta( $post->ID, '_ccoe_programme_affiliation', true );
		$badge          = get_post_meta( $post->ID, '_ccoe_programme_badge', true );
		$requirements   = get_post_meta( $post->ID, '_ccoe_programme_requirements', true );
		$courses_list   = get_post_meta( $post->ID, '_ccoe_programme_courses_list', true );
		?>
		<table class="form-table">
			<tr>
				<th><label for="ccoe_programme_badge"><?php esc_html_e( 'Qualification / Badge Label', 'ccoe-core' ); ?></label></th>
				<td><input type="text" id="ccoe_programme_badge" name="ccoe_programme_badge" value="<?php echo esc_attr( $badge ); ?>" class="regular-text" placeholder="e.g. NCCE 2026 Minimum Standard / Affiliated with AAUA" /></td>
			</tr>
			<tr>
				<th><label for="ccoe_programme_duration"><?php esc_html_e( 'Programme Duration', 'ccoe-core' ); ?></label></th>
				<td><input type="text" id="ccoe_programme_duration" name="ccoe_programme_duration" value="<?php echo esc_attr( $duration ); ?>" class="regular-text" placeholder="e.g. 3 Years (6 Semesters)" /></td>
			</tr>
			<tr>
				<th><label for="ccoe_programme_affiliation"><?php esc_html_e( 'Affiliating / Regulatory Body', 'ccoe-core' ); ?></label></th>
				<td><input type="text" id="ccoe_programme_affiliation" name="ccoe_programme_affiliation" value="<?php echo esc_attr( $affiliation ); ?>" class="large-text" placeholder="e.g. In Affiliation with Adekunle Ajasin University, Akungba-Akoko (AAUA)" /></td>
			</tr>
			<tr>
				<th><label for="ccoe_programme_courses_list"><?php esc_html_e( 'Available Courses (One per line)', 'ccoe-core' ); ?></label></th>
				<td>
					<textarea id="ccoe_programme_courses_list" name="ccoe_programme_courses_list" rows="6" class="large-text"><?php echo esc_textarea( $courses_list ); ?></textarea>
					<p class="description"><?php esc_html_e( 'Enter each course or subject combination under this programme/school on a new line.', 'ccoe-core' ); ?></p>
				</td>
			</tr>
			<tr>
				<th><label for="ccoe_programme_requirements"><?php esc_html_e( 'Admission / Entry Requirements', 'ccoe-core' ); ?></label></th>
				<td>
					<textarea id="ccoe_programme_requirements" name="ccoe_programme_requirements" rows="4" class="large-text"><?php echo esc_textarea( $requirements ); ?></textarea>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Render Course Meta Box
	 */
	public static function render_course_meta_box( $post ) {
		wp_nonce_field( 'ccoe_save_course_meta', 'ccoe_course_meta_nonce' );
		$code     = get_post_meta( $post->ID, '_ccoe_course_code', true );
		$units    = get_post_meta( $post->ID, '_ccoe_course_units', true );
		$status   = get_post_meta( $post->ID, '_ccoe_course_status', true );
		$semester = get_post_meta( $post->ID, '_ccoe_course_semester', true );
		?>
		<table class="form-table">
			<tr>
				<th><label for="ccoe_course_code"><?php esc_html_e( 'Course Code', 'ccoe-core' ); ?></label></th>
				<td><input type="text" id="ccoe_course_code" name="ccoe_course_code" value="<?php echo esc_attr( $code ); ?>" class="regular-text" placeholder="e.g. PDE 601" /></td>
			</tr>
			<tr>
				<th><label for="ccoe_course_units"><?php esc_html_e( 'Credit Units / Duration', 'ccoe-core' ); ?></label></th>
				<td><input type="text" id="ccoe_course_units" name="ccoe_course_units" value="<?php echo esc_attr( $units ); ?>" class="regular-text" placeholder="e.g. 2" /></td>
			</tr>
			<tr>
				<th><label for="ccoe_course_status"><?php esc_html_e( 'Course Status', 'ccoe-core' ); ?></label></th>
				<td>
					<select id="ccoe_course_status" name="ccoe_course_status">
						<option value="C" <?php selected( $status, 'C' ); ?>><?php esc_html_e( 'C — Compulsory', 'ccoe-core' ); ?></option>
						<option value="E" <?php selected( $status, 'E' ); ?>><?php esc_html_e( 'E — Elective', 'ccoe-core' ); ?></option>
						<option value="R" <?php selected( $status, 'R' ); ?>><?php esc_html_e( 'R — Required', 'ccoe-core' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="ccoe_course_semester"><?php esc_html_e( 'Semester / Session', 'ccoe-core' ); ?></label></th>
				<td>
					<select id="ccoe_course_semester" name="ccoe_course_semester">
						<option value="First Semester" <?php selected( $semester, 'First Semester' ); ?>><?php esc_html_e( 'First Semester', 'ccoe-core' ); ?></option>
						<option value="Second Semester" <?php selected( $semester, 'Second Semester' ); ?>><?php esc_html_e( 'Second Semester', 'ccoe-core' ); ?></option>
						<option value="Full Session" <?php selected( $semester, 'Full Session' ); ?>><?php esc_html_e( 'Full Academic Session', 'ccoe-core' ); ?></option>
					</select>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Render Event Meta Box
	 */
	public static function render_event_meta_box( $post ) {
		wp_nonce_field( 'ccoe_save_event_meta', 'ccoe_event_meta_nonce' );
		$date  = get_post_meta( $post->ID, '_ccoe_event_date', true );
		$time  = get_post_meta( $post->ID, '_ccoe_event_time', true );
		$venue = get_post_meta( $post->ID, '_ccoe_event_venue', true );
		?>
		<table class="form-table">
			<tr>
				<th><label for="ccoe_event_date"><?php esc_html_e( 'Event Date', 'ccoe-core' ); ?></label></th>
				<td><input type="date" id="ccoe_event_date" name="ccoe_event_date" value="<?php echo esc_attr( $date ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="ccoe_event_time"><?php esc_html_e( 'Event Time', 'ccoe-core' ); ?></label></th>
				<td><input type="text" id="ccoe_event_time" name="ccoe_event_time" value="<?php echo esc_attr( $time ); ?>" class="regular-text" placeholder="e.g. 09:00 AM – 02:00 PM" /></td>
			</tr>
			<tr>
				<th><label for="ccoe_event_venue"><?php esc_html_e( 'Venue / Location', 'ccoe-core' ); ?></label></th>
				<td><input type="text" id="ccoe_event_venue" name="ccoe_event_venue" value="<?php echo esc_attr( $venue ); ?>" class="large-text" placeholder="e.g. Main Auditorium, City College of Education, Mararaba" /></td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Save Meta Boxes safely with Nonce + Capability Checks
	 */
	public static function save_meta_boxes( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save Programme Meta
		if ( isset( $_POST['ccoe_programme_meta_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ccoe_programme_meta_nonce'] ) ), 'ccoe_save_programme_meta' ) ) {
			if ( isset( $_POST['ccoe_programme_badge'] ) ) {
				update_post_meta( $post_id, '_ccoe_programme_badge', sanitize_text_field( wp_unslash( $_POST['ccoe_programme_badge'] ) ) );
			}
			if ( isset( $_POST['ccoe_programme_duration'] ) ) {
				update_post_meta( $post_id, '_ccoe_programme_duration', sanitize_text_field( wp_unslash( $_POST['ccoe_programme_duration'] ) ) );
			}
			if ( isset( $_POST['ccoe_programme_affiliation'] ) ) {
				update_post_meta( $post_id, '_ccoe_programme_affiliation', sanitize_text_field( wp_unslash( $_POST['ccoe_programme_affiliation'] ) ) );
			}
			if ( isset( $_POST['ccoe_programme_courses_list'] ) ) {
				update_post_meta( $post_id, '_ccoe_programme_courses_list', sanitize_textarea_field( wp_unslash( $_POST['ccoe_programme_courses_list'] ) ) );
			}
			if ( isset( $_POST['ccoe_programme_requirements'] ) ) {
				update_post_meta( $post_id, '_ccoe_programme_requirements', sanitize_textarea_field( wp_unslash( $_POST['ccoe_programme_requirements'] ) ) );
			}
		}

		// Save Course Meta
		if ( isset( $_POST['ccoe_course_meta_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ccoe_course_meta_nonce'] ) ), 'ccoe_save_course_meta' ) ) {
			if ( isset( $_POST['ccoe_course_code'] ) ) {
				update_post_meta( $post_id, '_ccoe_course_code', sanitize_text_field( wp_unslash( $_POST['ccoe_course_code'] ) ) );
			}
			if ( isset( $_POST['ccoe_course_units'] ) ) {
				update_post_meta( $post_id, '_ccoe_course_units', sanitize_text_field( wp_unslash( $_POST['ccoe_course_units'] ) ) );
			}
			if ( isset( $_POST['ccoe_course_status'] ) ) {
				update_post_meta( $post_id, '_ccoe_course_status', sanitize_text_field( wp_unslash( $_POST['ccoe_course_status'] ) ) );
			}
			if ( isset( $_POST['ccoe_course_semester'] ) ) {
				update_post_meta( $post_id, '_ccoe_course_semester', sanitize_text_field( wp_unslash( $_POST['ccoe_course_semester'] ) ) );
			}
		}

		// Save Event Meta
		if ( isset( $_POST['ccoe_event_meta_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ccoe_event_meta_nonce'] ) ), 'ccoe_save_event_meta' ) ) {
			if ( isset( $_POST['ccoe_event_date'] ) ) {
				update_post_meta( $post_id, '_ccoe_event_date', sanitize_text_field( wp_unslash( $_POST['ccoe_event_date'] ) ) );
			}
			if ( isset( $_POST['ccoe_event_time'] ) ) {
				update_post_meta( $post_id, '_ccoe_event_time', sanitize_text_field( wp_unslash( $_POST['ccoe_event_time'] ) ) );
			}
			if ( isset( $_POST['ccoe_event_venue'] ) ) {
				update_post_meta( $post_id, '_ccoe_event_venue', sanitize_text_field( wp_unslash( $_POST['ccoe_event_venue'] ) ) );
			}
		}
	}

	/**
	 * Enhance single CPT view with structured metadata card
	 */
	public static function enhance_single_cpt_content( $content ) {
		if ( ! is_singular() || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}
		$post_id   = get_the_ID();
		$post_type = get_post_type( $post_id );

		if ( 'ccoe_programme' === $post_type ) {
			$badge        = get_post_meta( $post_id, '_ccoe_programme_badge', true );
			$duration     = get_post_meta( $post_id, '_ccoe_programme_duration', true );
			$affiliation  = get_post_meta( $post_id, '_ccoe_programme_affiliation', true );
			$requirements = get_post_meta( $post_id, '_ccoe_programme_requirements', true );
			$courses_list = get_post_meta( $post_id, '_ccoe_programme_courses_list', true );
			$apply_url    = site_url( '/apply-for-provisional-admission/' );

			ob_start();
			?>
			<div class="ccoe-single-meta-box">
				<div class="ccoe-meta-grid">
					<?php if ( $badge ) : ?>
						<div class="ccoe-meta-item"><strong><?php esc_html_e( 'Standard / Category:', 'ccoe-core' ); ?></strong> <span><?php echo esc_html( $badge ); ?></span></div>
					<?php endif; ?>
					<?php if ( $duration ) : ?>
						<div class="ccoe-meta-item"><strong><?php esc_html_e( 'Duration:', 'ccoe-core' ); ?></strong> <span><?php echo esc_html( $duration ); ?></span></div>
					<?php endif; ?>
					<?php if ( $affiliation ) : ?>
						<div class="ccoe-meta-item"><strong><?php esc_html_e( 'Affiliation / Accreditation:', 'ccoe-core' ); ?></strong> <span><?php echo esc_html( $affiliation ); ?></span></div>
					<?php endif; ?>
				</div>
				<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( $courses_list ) : ?>
					<div class="ccoe-programme-subbox">
						<h3><?php esc_html_e( 'Available Courses / Departments', 'ccoe-core' ); ?></h3>
						<ul class="ccoe-check-list">
							<?php
							foreach ( array_filter( array_map( 'trim', explode( "\n", $courses_list ) ) ) as $course_item ) :
								?>
								<li><?php echo esc_html( $course_item ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				<?php if ( $requirements ) : ?>
					<div class="ccoe-programme-subbox">
						<h3><?php esc_html_e( 'Admission Requirements', 'ccoe-core' ); ?></h3>
						<p><?php echo nl2br( esc_html( $requirements ) ); ?></p>
					</div>
				<?php endif; ?>
				<div class="ccoe-cta-inline">
					<a href="<?php echo esc_url( $apply_url ); ?>" class="ccoe-btn ccoe-btn-gold"><?php esc_html_e( 'Apply for This Programme Now', 'ccoe-core' ); ?></a>
				</div>
			</div>
			<?php
			return ob_get_clean();
		}

		if ( 'ccoe_event' === $post_type ) {
			$date  = get_post_meta( $post_id, '_ccoe_event_date', true );
			$time  = get_post_meta( $post_id, '_ccoe_event_time', true );
			$venue = get_post_meta( $post_id, '_ccoe_event_venue', true );

			ob_start();
			?>
			<div class="ccoe-single-meta-box">
				<div class="ccoe-meta-grid">
					<?php if ( $date ) : ?>
						<div class="ccoe-meta-item"><strong><?php esc_html_e( 'Date:', 'ccoe-core' ); ?></strong> <span><?php echo esc_html( date_i18n( 'F j, Y', strtotime( $date ) ) ); ?></span></div>
					<?php endif; ?>
					<?php if ( $time ) : ?>
						<div class="ccoe-meta-item"><strong><?php esc_html_e( 'Time:', 'ccoe-core' ); ?></strong> <span><?php echo esc_html( $time ); ?></span></div>
					<?php endif; ?>
					<?php if ( $venue ) : ?>
						<div class="ccoe-meta-item"><strong><?php esc_html_e( 'Venue:', 'ccoe-core' ); ?></strong> <span><?php echo esc_html( $venue ); ?></span></div>
					<?php endif; ?>
				</div>
				<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<?php
			return ob_get_clean();
		}

		return $content;
	}
}
