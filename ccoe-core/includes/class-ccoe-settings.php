<?php
/**
 * WordPress Administration Settings Page for City College of Education
 * Allows non-technical staff to update institutional details, Vision/Mission,
 * Provost's message, photographs, and contact information without editing code.
 *
 * @package CCOE_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CCOE_Settings {

	const OPTION_KEY = 'ccoe_institutional_settings';

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_settings_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
	}

	/**
	 * Retrieve merged settings (saved options + defaults)
	 */
	public static function get_settings() {
		$defaults = CCOE_Data::get_default_settings();
		$saved    = get_option( self::OPTION_KEY, array() );
		if ( ! is_array( $saved ) ) {
			$saved = array();
		}
		return wp_parse_args( $saved, $defaults );
	}

	/**
	 * Register Settings Page in WP Admin
	 */
	public static function register_settings_menu() {
		add_menu_page(
			__( 'City College Settings', 'ccoe-core' ),
			__( 'College Settings', 'ccoe-core' ),
			'manage_options',
			'ccoe-settings',
			array( __CLASS__, 'render_settings_page' ),
			'dashicons-building',
			25
		);
	}

	/**
	 * Register Settings API and Handle One-Click Seeder Action
	 */
	public static function register_settings() {
		register_setting(
			'ccoe_settings_group',
			self::OPTION_KEY,
			array(
				'sanitize_callback' => array( __CLASS__, 'sanitize_settings' ),
			)
		);

		if ( isset( $_POST['ccoe_run_seeder'], $_POST['ccoe_seeder_nonce'] ) && current_user_can( 'manage_options' ) ) {
			if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ccoe_seeder_nonce'] ) ), 'ccoe_run_seeder_action' ) ) {
				CCOE_Seeder::seed_all( true );
				add_settings_error(
					'ccoe_settings_messages',
					'ccoe_seeded',
					__( 'All 10 official City College of Education pages, navigation menus, academic programmes, courses, and events have been synchronized!', 'ccoe-core' ),
					'updated'
				);
			}
		}
	}

	/**
	 * Sanitize all settings fields
	 */
	public static function sanitize_settings( $input ) {
		$defaults = CCOE_Data::get_default_settings();
		$output   = array();

		$text_keys = array(
			'institution_name', 'short_name', 'motto', 'address', 'postal_address',
			'phone_primary', 'phone_secondary', 'academic_session', 'admissions_open',
			'provost_name', 'provost_designation',
		);
		foreach ( $text_keys as $k ) {
			$output[ $k ] = isset( $input[ $k ] ) ? sanitize_text_field( $input[ $k ] ) : $defaults[ $k ];
		}

		$email_keys = array( 'email_official', 'email_admissions' );
		foreach ( $email_keys as $k ) {
			$output[ $k ] = isset( $input[ $k ] ) ? sanitize_email( $input[ $k ] ) : $defaults[ $k ];
		}

		$url_keys = array(
			'provost_photo', 'google_maps_embed', 'facebook_url', 'twitter_url',
			'instagram_url', 'linkedin_url', 'logo_url', 'hero_image_url',
		);
		foreach ( $url_keys as $k ) {
			$output[ $k ] = isset( $input[ $k ] ) ? esc_url_raw( $input[ $k ] ) : $defaults[ $k ];
		}

		$textarea_keys = array(
			'vision_statement', 'mission_statement', 'about_background',
			'about_history', 'about_philosophy', 'provost_welcome', 'provost_statement',
		);
		foreach ( $textarea_keys as $k ) {
			$output[ $k ] = isset( $input[ $k ] ) ? sanitize_textarea_field( $input[ $k ] ) : $defaults[ $k ];
		}

		return $output;
	}

	/**
	 * Render the Institutional Settings Admin Page
	 */
	public static function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$s = self::get_settings();
		settings_errors( 'ccoe_settings_messages' );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'City College of Education, Mararaba — Institutional Administration Settings', 'ccoe-core' ); ?></h1>
			<p class="description">
				<?php esc_html_e( 'Manage official school information, Vision & Mission statements, Provost\'s Message, photographs, and contact details from one central dashboard.', 'ccoe-core' ); ?>
			</p>

			<form method="post" action="options.php" style="background:#fff;padding:24px;margin-top:16px;box-shadow:0 1px 3px rgba(0,0,0,0.08);max-width:1050px;">
				<?php settings_fields( 'ccoe_settings_group' ); ?>

				<h2 class="title"><?php esc_html_e( '1. General Institutional Information & Contact Details', 'ccoe-core' ); ?></h2>
				<table class="form-table">
					<tr>
						<th><label><?php esc_html_e( 'Institution Name', 'ccoe-core' ); ?></label></th>
						<td><input type="text" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[institution_name]" value="<?php echo esc_attr( $s['institution_name'] ); ?>" class="large-text" /></td>
					</tr>
					<tr>
						<th><label><?php esc_html_e( 'Official Motto', 'ccoe-core' ); ?></label></th>
						<td><input type="text" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[motto]" value="<?php echo esc_attr( $s['motto'] ); ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th><label><?php esc_html_e( 'Campus Physical Address', 'ccoe-core' ); ?></label></th>
						<td><input type="text" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[address]" value="<?php echo esc_attr( $s['address'] ); ?>" class="large-text" /></td>
					</tr>
					<tr>
						<th><label><?php esc_html_e( 'Postal Address', 'ccoe-core' ); ?></label></th>
						<td><input type="text" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[postal_address]" value="<?php echo esc_attr( $s['postal_address'] ); ?>" class="large-text" /></td>
					</tr>
					<tr>
						<th><label><?php esc_html_e( 'Telephone Numbers', 'ccoe-core' ); ?></label></th>
						<td>
							<input type="text" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[phone_primary]" value="<?php echo esc_attr( $s['phone_primary'] ); ?>" class="regular-text" placeholder="Primary Phone" />
							<input type="text" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[phone_secondary]" value="<?php echo esc_attr( $s['phone_secondary'] ); ?>" class="regular-text" placeholder="Secondary Phone" />
						</td>
					</tr>
					<tr>
						<th><label><?php esc_html_e( 'Official & Admissions Emails', 'ccoe-core' ); ?></label></th>
						<td>
							<input type="email" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[email_official]" value="<?php echo esc_attr( $s['email_official'] ); ?>" class="regular-text" placeholder="Official Email" />
							<input type="email" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[email_admissions]" value="<?php echo esc_attr( $s['email_admissions'] ); ?>" class="regular-text" placeholder="Admissions Email" />
						</td>
					</tr>
					<tr>
						<th><label><?php esc_html_e( 'Current Academic Session', 'ccoe-core' ); ?></label></th>
						<td>
							<input type="text" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[academic_session]" value="<?php echo esc_attr( $s['academic_session'] ); ?>" class="regular-text" />
							<select name="<?php echo esc_attr( self::OPTION_KEY ); ?>[admissions_open]">
								<option value="yes" <?php selected( $s['admissions_open'], 'yes' ); ?>><?php esc_html_e( 'Admissions Portal Open', 'ccoe-core' ); ?></option>
								<option value="no" <?php selected( $s['admissions_open'], 'no' ); ?>><?php esc_html_e( 'Admissions Portal Closed', 'ccoe-core' ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th><label><?php esc_html_e( 'Logo & Hero Banner URLs', 'ccoe-core' ); ?></label></th>
						<td>
							<p><input type="url" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[logo_url]" value="<?php echo esc_attr( $s['logo_url'] ); ?>" class="large-text" placeholder="Official Logo Image URL" /></p>
							<p><input type="url" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[hero_image_url]" value="<?php echo esc_attr( $s['hero_image_url'] ); ?>" class="large-text" placeholder="Homepage Hero Image URL" /></p>
						</td>
					</tr>
				</table>

				<hr>
				<h2 class="title"><?php esc_html_e( '2. Vision, Mission & Institutional Background', 'ccoe-core' ); ?></h2>
				<table class="form-table">
					<tr>
						<th><label><?php esc_html_e( 'Vision Statement', 'ccoe-core' ); ?></label></th>
						<td><textarea name="<?php echo esc_attr( self::OPTION_KEY ); ?>[vision_statement]" rows="3" class="large-text"><?php echo esc_textarea( $s['vision_statement'] ); ?></textarea></td>
					</tr>
					<tr>
						<th><label><?php esc_html_e( 'Mission Statement', 'ccoe-core' ); ?></label></th>
						<td><textarea name="<?php echo esc_attr( self::OPTION_KEY ); ?>[mission_statement]" rows="3" class="large-text"><?php echo esc_textarea( $s['mission_statement'] ); ?></textarea></td>
					</tr>
					<tr>
						<th><label><?php esc_html_e( 'Institutional Background', 'ccoe-core' ); ?></label></th>
						<td><textarea name="<?php echo esc_attr( self::OPTION_KEY ); ?>[about_background]" rows="4" class="large-text"><?php echo esc_textarea( $s['about_background'] ); ?></textarea></td>
					</tr>
					<tr>
						<th><label><?php esc_html_e( 'College History', 'ccoe-core' ); ?></label></th>
						<td><textarea name="<?php echo esc_attr( self::OPTION_KEY ); ?>[about_history]" rows="4" class="large-text"><?php echo esc_textarea( $s['about_history'] ); ?></textarea></td>
					</tr>
					<tr>
						<th><label><?php esc_html_e( 'Institutional Philosophy', 'ccoe-core' ); ?></label></th>
						<td><textarea name="<?php echo esc_attr( self::OPTION_KEY ); ?>[about_philosophy]" rows="3" class="large-text"><?php echo esc_textarea( $s['about_philosophy'] ); ?></textarea></td>
					</tr>
				</table>

				<hr>
				<h2 class="title"><?php esc_html_e( '3. Office of the Provost', 'ccoe-core' ); ?></h2>
				<table class="form-table">
					<tr>
						<th><label><?php esc_html_e( 'Provost Name & Titles', 'ccoe-core' ); ?></label></th>
						<td><input type="text" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[provost_name]" value="<?php echo esc_attr( $s['provost_name'] ); ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th><label><?php esc_html_e( 'Designation', 'ccoe-core' ); ?></label></th>
						<td><input type="text" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[provost_designation]" value="<?php echo esc_attr( $s['provost_designation'] ); ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th><label><?php esc_html_e( 'Provost Photograph URL', 'ccoe-core' ); ?></label></th>
						<td><input type="url" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[provost_photo]" value="<?php echo esc_attr( $s['provost_photo'] ); ?>" class="large-text" /></td>
					</tr>
					<tr>
						<th><label><?php esc_html_e( 'Welcome Message (Lead)', 'ccoe-core' ); ?></label></th>
						<td><textarea name="<?php echo esc_attr( self::OPTION_KEY ); ?>[provost_welcome]" rows="3" class="large-text"><?php echo esc_textarea( $s['provost_welcome'] ); ?></textarea></td>
					</tr>
					<tr>
						<th><label><?php esc_html_e( 'Leadership Statement', 'ccoe-core' ); ?></label></th>
						<td><textarea name="<?php echo esc_attr( self::OPTION_KEY ); ?>[provost_statement]" rows="4" class="large-text"><?php echo esc_textarea( $s['provost_statement'] ); ?></textarea></td>
					</tr>
				</table>

				<hr>
				<h2 class="title"><?php esc_html_e( '4. Google Maps & Social Media Links', 'ccoe-core' ); ?></h2>
				<table class="form-table">
					<tr>
						<th><label><?php esc_html_e( 'Google Maps Embed URL', 'ccoe-core' ); ?></label></th>
						<td><input type="url" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[google_maps_embed]" value="<?php echo esc_attr( $s['google_maps_embed'] ); ?>" class="large-text" /></td>
					</tr>
					<tr>
						<th><label><?php esc_html_e( 'Social Media Profiles', 'ccoe-core' ); ?></label></th>
						<td>
							<p><input type="url" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[facebook_url]" value="<?php echo esc_attr( $s['facebook_url'] ); ?>" class="regular-text" placeholder="Facebook URL" />
							<input type="url" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[twitter_url]" value="<?php echo esc_attr( $s['twitter_url'] ); ?>" class="regular-text" placeholder="X / Twitter URL" /></p>
							<p><input type="url" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[instagram_url]" value="<?php echo esc_attr( $s['instagram_url'] ); ?>" class="regular-text" placeholder="Instagram URL" />
							<input type="url" name="<?php echo esc_attr( self::OPTION_KEY ); ?>[linkedin_url]" value="<?php echo esc_attr( $s['linkedin_url'] ); ?>" class="regular-text" placeholder="LinkedIn URL" /></p>
						</td>
					</tr>
				</table>

				<?php submit_button( __( 'Save Institutional Settings', 'ccoe-core' ) ); ?>
			</form>

			<div style="background:#fff;padding:24px;margin-top:24px;box-shadow:0 1px 3px rgba(0,0,0,0.08);max-width:1050px;">
				<h2><?php esc_html_e( 'Synchronize Official Pages, Menus & Programmes', 'ccoe-core' ); ?></h2>
				<p><?php esc_html_e( 'Click the button below to verify or recreate all 10 required pages (Home, About Us, Our Vision and Mission, Message from the Provost, Academic Programmes, Courses, Admissions, News and Events, Contact Us, Apply for Provisional Admission), navigation menus, and default CCoE programmes/courses.', 'ccoe-core' ); ?></p>
				<form method="post">
					<?php wp_nonce_field( 'ccoe_run_seeder_action', 'ccoe_seeder_nonce' ); ?>
					<button type="submit" name="ccoe_run_seeder" value="1" class="button button-secondary">
						<?php esc_html_e( 'Run One-Click Page & Programme Synchronizer', 'ccoe-core' ); ?>
					</button>
				</form>
			</div>
		</div>
		<?php
	}
}
