<?php
/**
 * Database-Backed Provisional Admission Application System & Contact Inquiries
 *
 * @package CCOE_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CCOE_Admissions {

	private static $submission_result = null;
	private static $contact_result    = null;

	public static function init() {
		add_action( 'init', array( __CLASS__, 'handle_admission_submission' ) );
		add_action( 'init', array( __CLASS__, 'handle_contact_submission' ) );
		add_action( 'admin_menu', array( __CLASS__, 'register_admin_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'handle_admin_actions' ) );
	}

	/**
	 * Create Custom Database Tables for Applications and Contact Messages
	 */
	public static function create_tables() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_apps      = $wpdb->prefix . 'ccoe_applications';
		$table_contacts  = $wpdb->prefix . 'ccoe_contact_messages';

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$sql_apps = "CREATE TABLE $table_apps (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			reference_number varchar(40) NOT NULL,
			first_name varchar(100) NOT NULL,
			middle_name varchar(100) DEFAULT '',
			surname varchar(100) NOT NULL,
			dob date NOT NULL,
			gender varchar(30) NOT NULL,
			nationality varchar(80) NOT NULL,
			state_of_origin varchar(80) NOT NULL,
			lga varchar(120) NOT NULL,
			residential_address text NOT NULL,
			phone varchar(40) NOT NULL,
			email varchar(150) NOT NULL,
			highest_qualification varchar(120) NOT NULL,
			exam_type varchar(80) NOT NULL,
			exam_year varchar(20) NOT NULL,
			exam_number varchar(60) NOT NULL,
			programme_interest varchar(120) NOT NULL,
			preferred_course varchar(200) NOT NULL,
			nok_name varchar(150) NOT NULL,
			nok_phone varchar(40) NOT NULL,
			nok_relationship varchar(80) NOT NULL,
			passport_file varchar(255) DEFAULT '',
			credentials_file varchar(255) DEFAULT '',
			declaration_accepted tinyint(1) NOT NULL DEFAULT 1,
			application_status varchar(30) NOT NULL DEFAULT 'pending',
			admin_notes text DEFAULT '',
			ip_address varchar(64) DEFAULT '',
			submitted_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			UNIQUE KEY reference_number (reference_number),
			KEY email (email),
			KEY application_status (application_status)
		) $charset_collate;";

		$sql_contacts = "CREATE TABLE $table_contacts (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			full_name varchar(150) NOT NULL,
			email varchar(150) NOT NULL,
			phone varchar(40) DEFAULT '',
			subject varchar(200) NOT NULL,
			message text NOT NULL,
			submitted_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
		) $charset_collate;";

		dbDelta( $sql_apps );
		dbDelta( $sql_contacts );
	}

	/**
	 * Ensure protected upload directory exists and blocks direct public browsing
	 */
	public static function ensure_protected_upload_dir() {
		$upload_dir = wp_upload_dir();
		$ccoe_dir   = trailingslashit( $upload_dir['basedir'] ) . 'ccoe-applications';

		if ( ! file_exists( $ccoe_dir ) ) {
			wp_mkdir_p( $ccoe_dir );
		}

		$index_file = trailingslashit( $ccoe_dir ) . 'index.php';
		if ( ! file_exists( $index_file ) ) {
			file_put_contents( $index_file, "<?php\n// Silence is golden. Personal applicant documents are protected.\n" );
		}

		$htaccess_file = trailingslashit( $ccoe_dir ) . '.htaccess';
		if ( ! file_exists( $htaccess_file ) ) {
			$rules = "Options -Indexes\n<FilesMatch \"\\.(php|php\\.|phtml|pl|py|jsp|asp|htm|shtml|sh|cgi)$\">\nOrder Allow,Deny\nDeny from all\n</FilesMatch>\n";
			file_put_contents( $htaccess_file, $rules );
		}

		return $ccoe_dir;
	}

	/**
	 * Generate a unique application reference number (e.g. CCOE/2026/ADM/78241)
	 */
	public static function generate_reference_number() {
		global $wpdb;
		$table = $wpdb->prefix . 'ccoe_applications';
		$year  = gmdate( 'Y' );

		do {
			$rand = wp_rand( 10000, 99999 );
			$ref  = sprintf( 'CCOE/%s/ADM/%d', $year, $rand );
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$exists = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE reference_number = %s", $ref ) );
		} while ( $exists > 0 );

		return $ref;
	}

	/**
	 * Handle Provisional Admission Form Submission
	 */
	public static function handle_admission_submission() {
		if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || empty( $_POST['ccoe_admission_submit'] ) ) {
			return;
		}

		if ( ! isset( $_POST['ccoe_admission_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ccoe_admission_nonce'] ) ), 'ccoe_submit_admission' ) ) {
			self::$submission_result = array(
				'success' => false,
				'errors'  => array( __( 'Security verification failed. Please refresh the page and try again.', 'ccoe-core' ) ),
			);
			return;
		}

		// Honeypot anti-spam check
		if ( ! empty( $_POST['ccoe_website_hp'] ) ) {
			return;
		}

		$errors = array();

		// Mandatory fields map
		$required_fields = array(
			'first_name'            => __( 'First Name', 'ccoe-core' ),
			'surname'               => __( 'Surname', 'ccoe-core' ),
			'dob'                   => __( 'Date of Birth', 'ccoe-core' ),
			'gender'                => __( 'Gender', 'ccoe-core' ),
			'nationality'           => __( 'Nationality', 'ccoe-core' ),
			'state_of_origin'       => __( 'State of Origin', 'ccoe-core' ),
			'lga'                   => __( 'Local Government Area', 'ccoe-core' ),
			'residential_address'   => __( 'Residential Address', 'ccoe-core' ),
			'phone'                 => __( 'Phone Number', 'ccoe-core' ),
			'email'                 => __( 'Email Address', 'ccoe-core' ),
			'highest_qualification' => __( 'Highest Educational Qualification', 'ccoe-core' ),
			'exam_type'             => __( 'Examination Type', 'ccoe-core' ),
			'exam_year'             => __( 'Examination Year', 'ccoe-core' ),
			'exam_number'           => __( 'Examination Number', 'ccoe-core' ),
			'programme_interest'    => __( 'Programme of Interest', 'ccoe-core' ),
			'preferred_course'      => __( 'Preferred Course of Study', 'ccoe-core' ),
			'nok_name'              => __( 'Next of Kin Full Name', 'ccoe-core' ),
			'nok_phone'             => __( 'Next of Kin Phone Number', 'ccoe-core' ),
			'nok_relationship'      => __( 'Next of Kin Relationship', 'ccoe-core' ),
		);

		$clean = array();
		foreach ( $required_fields as $key => $label ) {
			$val = isset( $_POST[ $key ] ) ? trim( sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) ) : '';
			if ( '' === $val ) {
				/* translators: %s: field label */
				$errors[] = sprintf( __( '%s is required.', 'ccoe-core' ), $label );
			}
			$clean[ $key ] = $val;
		}

		$clean['middle_name']         = isset( $_POST['middle_name'] ) ? sanitize_text_field( wp_unslash( $_POST['middle_name'] ) ) : '';
		$clean['residential_address'] = isset( $_POST['residential_address'] ) ? sanitize_textarea_field( wp_unslash( $_POST['residential_address'] ) ) : '';

		// Validate email
		if ( ! empty( $clean['email'] ) && ! is_email( $clean['email'] ) ) {
			$errors[] = __( 'Please enter a valid email address.', 'ccoe-core' );
		}

		// Validate phone number
		if ( ! empty( $clean['phone'] ) && ! preg_match( '/^[0-9+\-\s()]{7,20}$/', $clean['phone'] ) ) {
			$errors[] = __( 'Please enter a valid telephone number.', 'ccoe-core' );
		}

		// Validate Declaration checkbox
		if ( empty( $_POST['declaration_accepted'] ) ) {
			$errors[] = __( 'You must accept the Applicant Declaration to submit your application.', 'ccoe-core' );
		}

		// Validate Passport Photograph (Mandatory, JPG/PNG, max 2MB)
		$passport_saved_name = '';
		if ( empty( $_FILES['passport_photo']['name'] ) ) {
			$errors[] = __( 'Passport Photograph is required (JPG or PNG, max 2MB).', 'ccoe-core' );
		} else {
			$upload_check = self::validate_and_save_upload(
				$_FILES['passport_photo'],
				array( 'jpg', 'jpeg', 'png' ),
				array( 'image/jpeg', 'image/png' ),
				2 * 1024 * 1024,
				'passport'
			);
			if ( is_wp_error( $upload_check ) ) {
				$errors[] = $upload_check->get_error_message();
			} else {
				$passport_saved_name = $upload_check;
			}
		}

		// Validate Academic Credentials (Optional / Where Applicable, PDF/JPG/PNG, max 5MB)
		$credentials_saved_name = '';
		if ( ! empty( $_FILES['academic_credentials']['name'] ) ) {
			$cred_check = self::validate_and_save_upload(
				$_FILES['academic_credentials'],
				array( 'pdf', 'jpg', 'jpeg', 'png' ),
				array( 'application/pdf', 'image/jpeg', 'image/png' ),
				5 * 1024 * 1024,
				'credentials'
			);
			if ( is_wp_error( $cred_check ) ) {
				$errors[] = $cred_check->get_error_message();
			} else {
				$credentials_saved_name = $cred_check;
			}
		}

		if ( ! empty( $errors ) ) {
			self::$submission_result = array(
				'success' => false,
				'errors'  => $errors,
				'old'     => $clean,
			);
			return;
		}

		// Save to Database
		global $wpdb;
		$table            = $wpdb->prefix . 'ccoe_applications';
		$reference_number = self::generate_reference_number();
		$ip_address       = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';

		// Ensure table exists
		self::create_tables();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$inserted = $wpdb->insert(
			$table,
			array(
				'reference_number'      => $reference_number,
				'first_name'            => $clean['first_name'],
				'middle_name'           => $clean['middle_name'],
				'surname'               => $clean['surname'],
				'dob'                   => $clean['dob'],
				'gender'                => $clean['gender'],
				'nationality'           => $clean['nationality'],
				'state_of_origin'       => $clean['state_of_origin'],
				'lga'                   => $clean['lga'],
				'residential_address'   => $clean['residential_address'],
				'phone'                 => $clean['phone'],
				'email'                 => $clean['email'],
				'highest_qualification' => $clean['highest_qualification'],
				'exam_type'             => $clean['exam_type'],
				'exam_year'             => $clean['exam_year'],
				'exam_number'           => $clean['exam_number'],
				'programme_interest'    => $clean['programme_interest'],
				'preferred_course'      => $clean['preferred_course'],
				'nok_name'              => $clean['nok_name'],
				'nok_phone'             => $clean['nok_phone'],
				'nok_relationship'      => $clean['nok_relationship'],
				'passport_file'         => $passport_saved_name,
				'credentials_file'      => $credentials_saved_name,
				'declaration_accepted'  => 1,
				'application_status'    => 'pending',
				'ip_address'            => $ip_address,
				'submitted_at'          => current_time( 'mysql' ),
				'updated_at'            => current_time( 'mysql' ),
			),
			array(
				'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
				'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
				'%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s',
			)
		);

		if ( false === $inserted ) {
			self::$submission_result = array(
				'success' => false,
				'errors'  => array( __( 'A database error occurred while saving your application. Please try again.', 'ccoe-core' ) ),
				'old'     => $clean,
			);
			return;
		}

		// Send Email Notifications
		self::send_application_emails( $reference_number, $clean );

		self::$submission_result = array(
			'success'          => true,
			'reference_number' => $reference_number,
			'applicant'        => $clean,
			'submitted_at'     => current_time( 'F j, Y g:i A' ),
		);
	}

	/**
	 * Validate and Save Uploaded File to Protected Folder
	 */
	private static function validate_and_save_upload( $file, $allowed_exts, $allowed_mimes, $max_bytes, $prefix ) {
		if ( ! empty( $file['error'] ) && UPLOAD_ERR_OK !== $file['error'] ) {
			return new WP_Error( 'upload_err', __( 'File upload error occurred. Please check your file size and try again.', 'ccoe-core' ) );
		}

		if ( $file['size'] > $max_bytes ) {
			$max_mb = round( $max_bytes / ( 1024 * 1024 ), 1 );
			/* translators: %s: max file size in MB */
			return new WP_Error( 'file_too_large', sprintf( __( 'Uploaded file exceeds the maximum allowed size of %s MB.', 'ccoe-core' ), $max_mb ) );
		}

		$filetype = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'] );
		$ext      = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );

		if ( ! in_array( $ext, $allowed_exts, true ) || ( ! empty( $filetype['type'] ) && ! in_array( $filetype['type'], $allowed_mimes, true ) ) ) {
			return new WP_Error(
				'invalid_file_type',
				sprintf(
					/* translators: %s: allowed file extensions */
					__( 'Invalid file type. Allowed file formats: %s.', 'ccoe-core' ),
					strtoupper( implode( ', ', $allowed_exts ) )
				)
			);
		}

		$protected_dir = self::ensure_protected_upload_dir();
		$safe_filename = sprintf( '%s-%s-%s.%s', $prefix, gmdate( 'YmdHis' ), wp_generate_password( 10, false ), $ext );
		$dest_path     = trailingslashit( $protected_dir ) . $safe_filename;

		if ( ! @move_uploaded_file( $file['tmp_name'], $dest_path ) ) {
			return new WP_Error( 'move_failed', __( 'Could not save uploaded document on the server.', 'ccoe-core' ) );
		}

		return $safe_filename;
	}

	/**
	 * Send Acknowledgement Email to Applicant & Notification Email to Admissions Office
	 */
	private static function send_application_emails( $reference_number, $applicant ) {
		$settings   = CCOE_Settings::get_settings();
		$full_name  = trim( $applicant['first_name'] . ' ' . $applicant['middle_name'] . ' ' . $applicant['surname'] );
		$inst_name  = $settings['institution_name'];
		$admin_mail = ! empty( $settings['email_admissions'] ) ? $settings['email_admissions'] : get_option( 'admin_email' );

		// 1. Applicant Acknowledgement Email
		$subj_applicant = sprintf( '[%s] Provisional Admission Application Received — Ref: %s', $inst_name, $reference_number );
		$body_applicant = sprintf(
			"Dear %s,\n\n" .
			"Thank you for applying for Provisional Admission at %s for the %s Academic Session.\n\n" .
			"=== APPLICATION SUMMARY ===\n" .
			"Application Reference Number: %s\n" .
			"Full Name: %s\n" .
			"Programme of Interest: %s\n" .
			"Preferred Course of Study: %s\n" .
			"Highest Qualification: %s (%s - %s)\n" .
			"Date Submitted: %s\n\n" .
			"Please keep your Application Reference Number (%s) safe, as it will be required during physical screening and verification of credentials at the Admissions Office.\n\n" .
			"Campus Address: %s\n" .
			"Enquiries: %s | %s\n\n" .
			"Signed,\nAdmissions Office,\n%s\n\"%s\"",
			$full_name,
			$inst_name,
			$settings['academic_session'],
			$reference_number,
			$full_name,
			$applicant['programme_interest'],
			$applicant['preferred_course'],
			$applicant['highest_qualification'],
			$applicant['exam_type'],
			$applicant['exam_year'],
			current_time( 'F j, Y g:i A' ),
			$reference_number,
			$settings['address'],
			$settings['phone_primary'],
			$settings['phone_secondary'],
			$inst_name,
			$settings['motto']
		);

		@wp_mail( $applicant['email'], $subj_applicant, $body_applicant );

		// 2. Admissions Office Notification Email
		$subj_admin = sprintf( '[New Admission Application] %s — %s (%s)', $reference_number, $full_name, $applicant['programme_interest'] );
		$body_admin = sprintf(
			"A new Provisional Admission Application has been submitted on the %s website.\n\n" .
			"Reference Number: %s\n" .
			"Applicant Name: %s\n" .
			"Gender / DOB: %s / %s\n" .
			"State / LGA: %s / %s\n" .
			"Phone / Email: %s / %s\n" .
			"Programme of Interest: %s\n" .
			"Preferred Course: %s\n" .
			"Qualification / Exam: %s (%s %s — Exam No: %s)\n" .
			"Next of Kin: %s (%s — %s)\n\n" .
			"Review and manage this application in the WordPress Dashboard:\n%s",
			$inst_name,
			$reference_number,
			$full_name,
			$applicant['gender'],
			$applicant['dob'],
			$applicant['state_of_origin'],
			$applicant['lga'],
			$applicant['phone'],
			$applicant['email'],
			$applicant['programme_interest'],
			$applicant['preferred_course'],
			$applicant['highest_qualification'],
			$applicant['exam_type'],
			$applicant['exam_year'],
			$applicant['exam_number'],
			$applicant['nok_name'],
			$applicant['nok_relationship'],
			$applicant['nok_phone'],
			admin_url( 'admin.php?page=ccoe-admissions' )
		);

		@wp_mail( $admin_mail, $subj_admin, $body_admin );
	}

	/**
	 * Handle Contact Form Submission
	 */
	public static function handle_contact_submission() {
		if ( 'POST' !== $_SERVER['REQUEST_METHOD'] || empty( $_POST['ccoe_contact_submit'] ) ) {
			return;
		}

		if ( ! isset( $_POST['ccoe_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ccoe_contact_nonce'] ) ), 'ccoe_submit_contact' ) ) {
			self::$contact_result = array(
				'success' => false,
				'message' => __( 'Security check failed. Please refresh and try again.', 'ccoe-core' ),
			);
			return;
		}

		$full_name = isset( $_POST['contact_name'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_name'] ) ) : '';
		$email     = isset( $_POST['contact_email'] ) ? sanitize_email( wp_unslash( $_POST['contact_email'] ) ) : '';
		$phone     = isset( $_POST['contact_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_phone'] ) ) : '';
		$subject   = isset( $_POST['contact_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_subject'] ) ) : '';
		$message   = isset( $_POST['contact_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ) ) : '';

		if ( empty( $full_name ) || empty( $email ) || ! is_email( $email ) || empty( $subject ) || empty( $message ) ) {
			self::$contact_result = array(
				'success' => false,
				'message' => __( 'Please complete all required fields and provide a valid email address.', 'ccoe-core' ),
			);
			return;
		}

		global $wpdb;
		self::create_tables();
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$wpdb->insert(
			$wpdb->prefix . 'ccoe_contact_messages',
			array(
				'full_name'    => $full_name,
				'email'        => $email,
				'phone'        => $phone,
				'subject'      => $subject,
				'message'      => $message,
				'submitted_at' => current_time( 'mysql' ),
			),
			array( '%s', '%s', '%s', '%s', '%s', '%s' )
		);

		$settings = CCOE_Settings::get_settings();
		@wp_mail(
			$settings['email_official'],
			sprintf( '[CCoE Website Inquiry] %s', $subject ),
			sprintf( "Name: %s\nEmail: %s\nPhone: %s\n\nMessage:\n%s", $full_name, $email, $phone, $message )
		);

		self::$contact_result = array(
			'success' => true,
			'message' => __( 'Thank you for contacting City College of Education, Mararaba. Your inquiry has been received and our team will respond shortly.', 'ccoe-core' ),
		);
	}

	public static function get_submission_result() {
		return self::$submission_result;
	}

	public static function get_contact_result() {
		return self::$contact_result;
	}

	/**
	 * Register WP Admin Menu for Managing Admission Applications
	 */
	public static function register_admin_menu() {
		add_menu_page(
			__( 'Admission Applications', 'ccoe-core' ),
			__( 'Admissions', 'ccoe-core' ),
			'manage_options',
			'ccoe-admissions',
			array( __CLASS__, 'render_admin_applications_page' ),
			'dashicons-id-alt',
			24
		);

		add_submenu_page(
			'ccoe-admissions',
			__( 'All Applications', 'ccoe-core' ),
			__( 'All Applications', 'ccoe-core' ),
			'manage_options',
			'ccoe-admissions',
			array( __CLASS__, 'render_admin_applications_page' )
		);

		add_submenu_page(
			'ccoe-admissions',
			__( 'Contact Inquiries', 'ccoe-core' ),
			__( 'Contact Inquiries', 'ccoe-core' ),
			'manage_options',
			'ccoe-contact-inquiries',
			array( __CLASS__, 'render_admin_contacts_page' )
		);
	}

	/**
	 * Handle Admin Status Updates, CSV Export, and Protected File Downloads
	 */
	public static function handle_admin_actions() {
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// 1. Update Application Status & Admin Notes
		if ( isset( $_POST['ccoe_update_app_status'], $_POST['ccoe_admin_app_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ccoe_admin_app_nonce'] ) ), 'ccoe_update_app' ) ) {
			global $wpdb;
			$app_id = isset( $_POST['app_id'] ) ? absint( $_POST['app_id'] ) : 0;
			$status = isset( $_POST['application_status'] ) ? sanitize_text_field( wp_unslash( $_POST['application_status'] ) ) : 'pending';
			$notes  = isset( $_POST['admin_notes'] ) ? sanitize_textarea_field( wp_unslash( $_POST['admin_notes'] ) ) : '';

			if ( $app_id > 0 && in_array( $status, array( 'pending', 'under_review', 'admitted', 'declined' ), true ) ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$wpdb->update(
					$wpdb->prefix . 'ccoe_applications',
					array(
						'application_status' => $status,
						'admin_notes'        => $notes,
						'updated_at'         => current_time( 'mysql' ),
					),
					array( 'id' => $app_id ),
					array( '%s', '%s', '%s' ),
					array( '%d' )
				);
			}
		}

		// 1b. Delete Application Record and Associated Protected Files
		if ( isset( $_GET['ccoe_delete_app'], $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'ccoe_delete_app' ) ) {
			global $wpdb;
			$del_id = absint( $_GET['ccoe_delete_app'] );
			if ( $del_id > 0 ) {
				$table = $wpdb->prefix . 'ccoe_applications';
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$app_row = $wpdb->get_row( $wpdb->prepare( "SELECT passport_file, credentials_file FROM $table WHERE id = %d", $del_id ) );
				if ( $app_row ) {
					$dir = self::ensure_protected_upload_dir();
					foreach ( array( $app_row->passport_file, $app_row->credentials_file ) as $f ) {
						if ( ! empty( $f ) ) {
							$p = trailingslashit( $dir ) . basename( $f );
							if ( file_exists( $p ) && is_file( $p ) ) {
								@unlink( $p );
							}
						}
					}
					// phpcs:ignore WordPress.DB.DirectDatabaseQuery
					$wpdb->delete( $table, array( 'id' => $del_id ), array( '%d' ) );
				}
				wp_safe_redirect( admin_url( 'admin.php?page=ccoe-admissions&deleted=1' ) );
				exit;
			}
		}

		// 2. Secure Protected Document Download for Admins
		if ( isset( $_GET['ccoe_download_doc'], $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'ccoe_download_doc' ) ) {
			$file      = basename( sanitize_text_field( wp_unslash( $_GET['ccoe_download_doc'] ) ) );
			$dir       = self::ensure_protected_upload_dir();
			$file_path = trailingslashit( $dir ) . $file;

			if ( file_exists( $file_path ) && is_file( $file_path ) ) {
				$mime = wp_check_filetype( $file_path );
				header( 'Content-Type: ' . ( $mime['type'] ? $mime['type'] : 'application/octet-stream' ) );
				header( 'Content-Disposition: inline; filename="' . esc_attr( $file ) . '"' );
				header( 'Content-Length: ' . filesize( $file_path ) );
				readfile( $file_path );
				exit;
			}
		}

		// 3. Export Applications to CSV
		if ( isset( $_GET['ccoe_export_csv'], $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'ccoe_export_csv' ) ) {
			global $wpdb;
			$table = $wpdb->prefix . 'ccoe_applications';
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$rows = $wpdb->get_results( "SELECT * FROM $table ORDER BY submitted_at DESC", ARRAY_A );

			header( 'Content-Type: text/csv; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename="ccoe-admission-applications-' . gmdate( 'Y-m-d' ) . '.csv"' );

			$output = fopen( 'php://output', 'w' );
			if ( ! empty( $rows ) ) {
				fputcsv( $output, array_keys( $rows[0] ) );
				foreach ( $rows as $row ) {
					fputcsv( $output, $row );
				}
			} else {
				fputcsv( $output, array( 'Reference Number', 'First Name', 'Surname', 'Programme', 'Course', 'Status', 'Submitted At' ) );
			}
			fclose( $output );
			exit;
		}
	}

	/**
	 * Render WordPress Admin Page for Viewing & Managing Admission Submissions
	 */
	public static function render_admin_applications_page() {
		global $wpdb;
		self::create_tables();
		$table = $wpdb->prefix . 'ccoe_applications';

		// View Single Application Detail
		if ( isset( $_GET['view_id'] ) ) {
			$view_id = absint( $_GET['view_id'] );
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$app = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $view_id ) );
			if ( $app ) {
				self::render_single_application_admin( $app );
				return;
			}
		}

		$status_filter = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : '';
		$search_query  = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';

		$where = 'WHERE 1=1';
		$args  = array();

		if ( $status_filter && in_array( $status_filter, array( 'pending', 'under_review', 'admitted', 'declined' ), true ) ) {
			$where .= ' AND application_status = %s';
			$args[] = $status_filter;
		}
		if ( $search_query ) {
			$where .= ' AND (reference_number LIKE %s OR first_name LIKE %s OR surname LIKE %s OR email LIKE %s OR phone LIKE %s)';
			$like   = '%' . $wpdb->esc_like( $search_query ) . '%';
			array_push( $args, $like, $like, $like, $like, $like );
		}

		$sql = "SELECT * FROM $table $where ORDER BY submitted_at DESC LIMIT 200";
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.NotPrepared
		$applications = ! empty( $args ) ? $wpdb->get_results( $wpdb->prepare( $sql, $args ) ) : $wpdb->get_results( $sql );

		// Counts
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$total_count    = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$pending_count  = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE application_status = 'pending'" );
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$admitted_count = (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table WHERE application_status = 'admitted'" );

		$export_url = wp_nonce_url( admin_url( 'admin.php?page=ccoe-admissions&ccoe_export_csv=1' ), 'ccoe_export_csv' );
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Provisional Admission Applications — City College of Education', 'ccoe-core' ); ?></h1>
			<a href="<?php echo esc_url( $export_url ); ?>" class="page-title-action"><?php esc_html_e( 'Export All to CSV', 'ccoe-core' ); ?></a>
			<hr class="wp-header-end">

			<div style="display:flex;gap:16px;margin:16px 0;">
				<div style="background:#fff;border-left:4px solid #00337C;padding:12px 20px;box-shadow:0 1px 3px rgba(0,0,0,0.08);">
					<strong><?php esc_html_e( 'Total Applications:', 'ccoe-core' ); ?></strong> <?php echo esc_html( $total_count ); ?>
				</div>
				<div style="background:#fff;border-left:4px solid #F5B800;padding:12px 20px;box-shadow:0 1px 3px rgba(0,0,0,0.08);">
					<strong><?php esc_html_e( 'Pending Review:', 'ccoe-core' ); ?></strong> <?php echo esc_html( $pending_count ); ?>
				</div>
				<div style="background:#fff;border-left:4px solid #007A33;padding:12px 20px;box-shadow:0 1px 3px rgba(0,0,0,0.08);">
					<strong><?php esc_html_e( 'Offered Provisional Admission:', 'ccoe-core' ); ?></strong> <?php echo esc_html( $admitted_count ); ?>
				</div>
			</div>

			<form method="get" style="margin-bottom:16px;display:flex;gap:8px;align-items:center;">
				<input type="hidden" name="page" value="ccoe-admissions" />
				<select name="status">
					<option value=""><?php esc_html_e( 'All Statuses', 'ccoe-core' ); ?></option>
					<option value="pending" <?php selected( $status_filter, 'pending' ); ?>><?php esc_html_e( 'Pending', 'ccoe-core' ); ?></option>
					<option value="under_review" <?php selected( $status_filter, 'under_review' ); ?>><?php esc_html_e( 'Under Review', 'ccoe-core' ); ?></option>
					<option value="admitted" <?php selected( $status_filter, 'admitted' ); ?>><?php esc_html_e( 'Admitted', 'ccoe-core' ); ?></option>
					<option value="declined" <?php selected( $status_filter, 'declined' ); ?>><?php esc_html_e( 'Declined', 'ccoe-core' ); ?></option>
				</select>
				<input type="search" name="s" value="<?php echo esc_attr( $search_query ); ?>" placeholder="<?php esc_attr_e( 'Search Reference No, Name, Email...', 'ccoe-core' ); ?>" class="regular-text" />
				<button type="submit" class="button"><?php esc_html_e( 'Filter / Search', 'ccoe-core' ); ?></button>
			</form>

			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Reference No.', 'ccoe-core' ); ?></th>
						<th><?php esc_html_e( 'Applicant Name', 'ccoe-core' ); ?></th>
						<th><?php esc_html_e( 'Phone & Email', 'ccoe-core' ); ?></th>
						<th><?php esc_html_e( 'Programme & Course', 'ccoe-core' ); ?></th>
						<th><?php esc_html_e( 'Qualification', 'ccoe-core' ); ?></th>
						<th><?php esc_html_e( 'Status', 'ccoe-core' ); ?></th>
						<th><?php esc_html_e( 'Submitted', 'ccoe-core' ); ?></th>
						<th><?php esc_html_e( 'Action', 'ccoe-core' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( empty( $applications ) ) : ?>
						<tr>
							<td colspan="8"><?php esc_html_e( 'No admission applications found.', 'ccoe-core' ); ?></td>
						</tr>
					<?php else : ?>
						<?php foreach ( $applications as $app ) : ?>
							<tr>
								<td><strong><?php echo esc_html( $app->reference_number ); ?></strong></td>
								<td><?php echo esc_html( trim( $app->first_name . ' ' . $app->middle_name . ' ' . $app->surname ) ); ?></td>
								<td><?php echo esc_html( $app->phone ); ?><br><small><?php echo esc_html( $app->email ); ?></small></td>
								<td><strong><?php echo esc_html( $app->programme_interest ); ?></strong><br><small><?php echo esc_html( $app->preferred_course ); ?></small></td>
								<td><?php echo esc_html( $app->highest_qualification . ' (' . $app->exam_type . ' ' . $app->exam_year . ')' ); ?></td>
								<td><code><?php echo esc_html( strtoupper( $app->application_status ) ); ?></code></td>
								<td><?php echo esc_html( $app->submitted_at ); ?></td>
								<td>
									<?php
									$view_url   = admin_url( 'admin.php?page=ccoe-admissions&view_id=' . absint( $app->id ) );
									$delete_url = wp_nonce_url( admin_url( 'admin.php?page=ccoe-admissions&ccoe_delete_app=' . absint( $app->id ) ), 'ccoe_delete_app' );
									?>
									<a href="<?php echo esc_url( $view_url ); ?>" class="button button-small button-primary">
										<?php esc_html_e( 'View / Manage', 'ccoe-core' ); ?>
									</a>
									<a href="<?php echo esc_url( $delete_url ); ?>" class="button button-small" style="color:#b91c1c;border-color:#fca5a5;" onclick="return confirm('<?php echo esc_js( __( 'Are you sure you want to permanently delete this application?', 'ccoe-core' ) ); ?>');">
										<?php esc_html_e( 'Delete', 'ccoe-core' ); ?>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Render Single Application Detail in WP Admin
	 */
	private static function render_single_application_admin( $app ) {
		$back_url     = admin_url( 'admin.php?page=ccoe-admissions' );
		$passport_url = $app->passport_file ? wp_nonce_url( admin_url( 'admin.php?page=ccoe-admissions&ccoe_download_doc=' . rawurlencode( $app->passport_file ) ), 'ccoe_download_doc' ) : '';
		$cred_url     = $app->credentials_file ? wp_nonce_url( admin_url( 'admin.php?page=ccoe-admissions&ccoe_download_doc=' . rawurlencode( $app->credentials_file ) ), 'ccoe_download_doc' ) : '';
		?>
		<div class="wrap">
			<h1>
				<?php
				/* translators: %s: reference number */
				printf( esc_html__( 'Application Detail — %s', 'ccoe-core' ), esc_html( $app->reference_number ) );
				?>
			</h1>
			<p><a href="<?php echo esc_url( $back_url ); ?>" class="button">&larr; <?php esc_html_e( 'Back to All Applications', 'ccoe-core' ); ?></a></p>

			<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;margin-top:16px;">
				<div style="background:#fff;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
					<h2><?php esc_html_e( '1. Personal & Contact Details', 'ccoe-core' ); ?></h2>
					<table class="widefat striped">
						<tbody>
							<tr><th><?php esc_html_e( 'Full Name', 'ccoe-core' ); ?></th><td><?php echo esc_html( trim( $app->first_name . ' ' . $app->middle_name . ' ' . $app->surname ) ); ?></td></tr>
							<tr><th><?php esc_html_e( 'Date of Birth / Gender', 'ccoe-core' ); ?></th><td><?php echo esc_html( $app->dob . ' / ' . $app->gender ); ?></td></tr>
							<tr><th><?php esc_html_e( 'Nationality', 'ccoe-core' ); ?></th><td><?php echo esc_html( $app->nationality ); ?></td></tr>
							<tr><th><?php esc_html_e( 'State of Origin / LGA', 'ccoe-core' ); ?></th><td><?php echo esc_html( $app->state_of_origin . ' — ' . $app->lga ); ?></td></tr>
							<tr><th><?php esc_html_e( 'Residential Address', 'ccoe-core' ); ?></th><td><?php echo nl2br( esc_html( $app->residential_address ) ); ?></td></tr>
							<tr><th><?php esc_html_e( 'Phone Number', 'ccoe-core' ); ?></th><td><?php echo esc_html( $app->phone ); ?></td></tr>
							<tr><th><?php esc_html_e( 'Email Address', 'ccoe-core' ); ?></th><td><?php echo esc_html( $app->email ); ?></td></tr>
						</tbody>
					</table>

					<h2 style="margin-top:24px;"><?php esc_html_e( '2. Academic Details', 'ccoe-core' ); ?></h2>
					<table class="widefat striped">
						<tbody>
							<tr><th><?php esc_html_e( 'Programme of Interest', 'ccoe-core' ); ?></th><td><strong><?php echo esc_html( $app->programme_interest ); ?></strong></td></tr>
							<tr><th><?php esc_html_e( 'Preferred Course of Study', 'ccoe-core' ); ?></th><td><strong><?php echo esc_html( $app->preferred_course ); ?></strong></td></tr>
							<tr><th><?php esc_html_e( 'Highest Qualification', 'ccoe-core' ); ?></th><td><?php echo esc_html( $app->highest_qualification ); ?></td></tr>
							<tr><th><?php esc_html_e( 'Examination Type & Year', 'ccoe-core' ); ?></th><td><?php echo esc_html( $app->exam_type . ' (' . $app->exam_year . ')' ); ?></td></tr>
							<tr><th><?php esc_html_e( 'Examination Number', 'ccoe-core' ); ?></th><td><?php echo esc_html( $app->exam_number ); ?></td></tr>
						</tbody>
					</table>

					<h2 style="margin-top:24px;"><?php esc_html_e( '3. Next of Kin', 'ccoe-core' ); ?></h2>
					<table class="widefat striped">
						<tbody>
							<tr><th><?php esc_html_e( 'Next of Kin Full Name', 'ccoe-core' ); ?></th><td><?php echo esc_html( $app->nok_name ); ?></td></tr>
							<tr><th><?php esc_html_e( 'Next of Kin Phone', 'ccoe-core' ); ?></th><td><?php echo esc_html( $app->nok_phone ); ?></td></tr>
							<tr><th><?php esc_html_e( 'Relationship', 'ccoe-core' ); ?></th><td><?php echo esc_html( $app->nok_relationship ); ?></td></tr>
						</tbody>
					</table>
				</div>

				<div style="background:#fff;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
					<h2><?php esc_html_e( 'Uploaded Documents', 'ccoe-core' ); ?></h2>
					<p>
						<strong><?php esc_html_e( 'Passport Photograph:', 'ccoe-core' ); ?></strong><br>
						<?php if ( $passport_url ) : ?>
							<a href="<?php echo esc_url( $passport_url ); ?>" target="_blank" class="button"><?php esc_html_e( 'View / Download Passport', 'ccoe-core' ); ?></a>
						<?php else : ?>
							<em><?php esc_html_e( 'Not uploaded', 'ccoe-core' ); ?></em>
						<?php endif; ?>
					</p>
					<p>
						<strong><?php esc_html_e( 'Academic Credentials:', 'ccoe-core' ); ?></strong><br>
						<?php if ( $cred_url ) : ?>
							<a href="<?php echo esc_url( $cred_url ); ?>" target="_blank" class="button"><?php esc_html_e( 'View / Download Credentials', 'ccoe-core' ); ?></a>
						<?php else : ?>
							<em><?php esc_html_e( 'Not provided', 'ccoe-core' ); ?></em>
						<?php endif; ?>
					</p>

					<hr style="margin:24px 0;">
					<h2><?php esc_html_e( 'Update Application Status', 'ccoe-core' ); ?></h2>
					<form method="post">
						<?php wp_nonce_field( 'ccoe_update_app', 'ccoe_admin_app_nonce' ); ?>
						<input type="hidden" name="app_id" value="<?php echo absint( $app->id ); ?>" />
						<p>
							<label for="application_status"><strong><?php esc_html_e( 'Decision Status:', 'ccoe-core' ); ?></strong></label><br>
							<select id="application_status" name="application_status" style="width:100%;margin-top:6px;">
								<option value="pending" <?php selected( $app->application_status, 'pending' ); ?>><?php esc_html_e( 'Pending Review', 'ccoe-core' ); ?></option>
								<option value="under_review" <?php selected( $app->application_status, 'under_review' ); ?>><?php esc_html_e( 'Under Review', 'ccoe-core' ); ?></option>
								<option value="admitted" <?php selected( $app->application_status, 'admitted' ); ?>><?php esc_html_e( 'Offered Provisional Admission', 'ccoe-core' ); ?></option>
								<option value="declined" <?php selected( $app->application_status, 'declined' ); ?>><?php esc_html_e( 'Declined', 'ccoe-core' ); ?></option>
							</select>
						</p>
						<p>
							<label for="admin_notes"><strong><?php esc_html_e( 'Admissions Office Notes:', 'ccoe-core' ); ?></strong></label><br>
							<textarea id="admin_notes" name="admin_notes" rows="5" style="width:100%;margin-top:6px;"><?php echo esc_textarea( $app->admin_notes ); ?></textarea>
						</p>
						<button type="submit" name="ccoe_update_app_status" value="1" class="button button-primary"><?php esc_html_e( 'Save Decision', 'ccoe-core' ); ?></button>
					</form>
					<hr style="margin:24px 0;">
					<?php $delete_url = wp_nonce_url( admin_url( 'admin.php?page=ccoe-admissions&ccoe_delete_app=' . absint( $app->id ) ), 'ccoe_delete_app' ); ?>
					<a href="<?php echo esc_url( $delete_url ); ?>" class="button" style="color:#b91c1c;border-color:#fca5a5;" onclick="return confirm('<?php echo esc_js( __( 'Are you sure you want to permanently delete this application?', 'ccoe-core' ) ); ?>');">
						<?php esc_html_e( 'Delete Application Permanently', 'ccoe-core' ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Contact Inquiries Admin Page
	 */
	public static function render_admin_contacts_page() {
		global $wpdb;
		self::create_tables();
		$table = $wpdb->prefix . 'ccoe_contact_messages';
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$messages = $wpdb->get_results( "SELECT * FROM $table ORDER BY submitted_at DESC LIMIT 100" );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Contact Form Inquiries — City College of Education', 'ccoe-core' ); ?></h1>
			<table class="wp-list-table widefat fixed striped" style="margin-top:16px;">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Date', 'ccoe-core' ); ?></th>
						<th><?php esc_html_e( 'Sender Name', 'ccoe-core' ); ?></th>
						<th><?php esc_html_e( 'Email & Phone', 'ccoe-core' ); ?></th>
						<th><?php esc_html_e( 'Subject', 'ccoe-core' ); ?></th>
						<th><?php esc_html_e( 'Message', 'ccoe-core' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( empty( $messages ) ) : ?>
						<tr><td colspan="5"><?php esc_html_e( 'No contact inquiries submitted yet.', 'ccoe-core' ); ?></td></tr>
					<?php else : ?>
						<?php foreach ( $messages as $msg ) : ?>
							<tr>
								<td><?php echo esc_html( $msg->submitted_at ); ?></td>
								<td><strong><?php echo esc_html( $msg->full_name ); ?></strong></td>
								<td><?php echo esc_html( $msg->email ); ?><br><small><?php echo esc_html( $msg->phone ); ?></small></td>
								<td><strong><?php echo esc_html( $msg->subject ); ?></strong></td>
								<td><?php echo nl2br( esc_html( $msg->message ) ); ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}
}
