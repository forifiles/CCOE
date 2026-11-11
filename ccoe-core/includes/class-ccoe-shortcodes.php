<?php
/**
 * Shortcodes & Dynamic Page Renderers for City College of Education, Mararaba
 *
 * @package CCOE_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CCOE_Shortcodes {

	public static function init() {
		add_shortcode( 'ccoe_homepage', array( __CLASS__, 'render_homepage' ) );
		add_shortcode( 'ccoe_about_page', array( __CLASS__, 'render_about_page' ) );
		add_shortcode( 'ccoe_vision_mission_page', array( __CLASS__, 'render_vision_mission_page' ) );
		add_shortcode( 'ccoe_provost_page', array( __CLASS__, 'render_provost_page' ) );
		add_shortcode( 'ccoe_programmes_page', array( __CLASS__, 'render_programmes_page' ) );
		add_shortcode( 'ccoe_courses_page', array( __CLASS__, 'render_courses_page' ) );
		add_shortcode( 'ccoe_admissions_page', array( __CLASS__, 'render_admissions_page' ) );
		add_shortcode( 'ccoe_news_events_page', array( __CLASS__, 'render_news_events_page' ) );
		add_shortcode( 'ccoe_contact_page', array( __CLASS__, 'render_contact_page' ) );
		add_shortcode( 'ccoe_admission_form', array( __CLASS__, 'render_admission_form' ) );
	}

	/**
	 * Helper: Get theme CCoE asset image URL
	 */
	public static function img_url( $filename ) {
		return esc_url( get_template_directory_uri() . '/images/ccoe/' . $filename );
	}

	/**
	 * Helper: Render Accessible 2px-Stroke Lucide Vector Icon (UI/UX Pro Max: No Emojis as UI Icons)
	 */
	public static function svg_icon( $name, $size = 22 ) {
		$icons = array(
			'target'         => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
			'graduation-cap' => '<path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>',
			'lightbulb'      => '<path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/>',
			'map-pin'        => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
			'phone'          => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
			'mail'           => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
			'calendar'       => '<rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>',
			'clock'          => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
			'printer'        => '<polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>',
		);
		$inner = isset( $icons[ $name ] ) ? $icons[ $name ] : $icons['target'];
		return sprintf(
			'<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="ccoe-svg-inline">%s</svg>',
			absint( $size ),
			absint( $size ),
			$inner
		);
	}

	/**
	 * 1. HOMEPAGE ([ccoe_homepage])
	 */
	public static function render_homepage() {
		$s         = CCOE_Settings::get_settings();
		$apply_url = site_url( '/apply-for-provisional-admission/' );
		$prog_url  = site_url( '/academic-programmes/' );
		$about_url = site_url( '/about-us/' );
		$prov_url  = site_url( '/message-from-the-provost/' );
		$event_url = site_url( '/news-and-events/' );
		$cont_url  = site_url( '/contact-us/' );

		ob_start();
		?>
		<div class="ccoe-wrapper ccoe-home">
			<!-- HERO BANNER -->
			<section class="ccoe-hero" style="background-image: linear-gradient(125deg, rgba(0, 35, 85, 0.90) 0%, rgba(0, 51, 124, 0.80) 55%, rgba(0, 122, 51, 0.72) 100%), url('<?php echo esc_url( $s['hero_image_url'] ); ?>');">
				<div class="ccoe-container ccoe-hero-inner">
					<div class="ccoe-hero-badge">
						<span class="ccoe-dot"></span>
						<?php echo esc_html( $s['academic_session'] . ' Admissions Now Open • NCCE 2026 & AAUA Affiliated' ); ?>
					</div>
					<h1 class="ccoe-hero-title">
						<?php esc_html_e( 'Welcome to City College of Education, Mararaba', 'ccoe-core' ); ?>
					</h1>
					<p class="ccoe-hero-subtitle">
						<?php echo esc_html( $s['mission_statement'] ); ?>
					</p>
					<div class="ccoe-hero-actions">
						<a href="<?php echo esc_url( $apply_url ); ?>" class="ccoe-btn ccoe-btn-gold">
							<?php esc_html_e( 'Apply Now for Provisional Admission', 'ccoe-core' ); ?> &rarr;
						</a>
						<a href="<?php echo esc_url( $prog_url ); ?>" class="ccoe-btn ccoe-btn-outline-light">
							<?php esc_html_e( 'Explore Programmes', 'ccoe-core' ); ?>
						</a>
					</div>
					<div class="ccoe-hero-stats">
						<div class="ccoe-stat-box">
							<span class="ccoe-stat-num">7</span>
							<span class="ccoe-stat-label"><?php esc_html_e( 'NCE Academic Schools', 'ccoe-core' ); ?></span>
						</div>
						<div class="ccoe-stat-box">
							<span class="ccoe-stat-num">28+</span>
							<span class="ccoe-stat-label"><?php esc_html_e( 'NCCE (2026) NCE Programmes', 'ccoe-core' ); ?></span>
						</div>
						<div class="ccoe-stat-box">
							<span class="ccoe-stat-num">10</span>
							<span class="ccoe-stat-label"><?php esc_html_e( 'AAUA Affiliated B.Ed./B.Sc. Degrees', 'ccoe-core' ); ?></span>
						</div>
						<div class="ccoe-stat-box">
							<span class="ccoe-stat-num">PGDE &amp; PDE</span>
							<span class="ccoe-stat-label"><?php esc_html_e( 'Postgraduate & Professional Diplomas', 'ccoe-core' ); ?></span>
						</div>
					</div>
				</div>
			</section>

			<!-- ABOUT THE COLLEGE SECTION -->
			<section class="ccoe-section ccoe-bg-white">
				<div class="ccoe-container ccoe-split-grid">
					<div class="ccoe-split-content">
						<span class="ccoe-eyebrow"><?php esc_html_e( 'About the Institution', 'ccoe-core' ); ?></span>
						<h2 class="ccoe-section-title"><?php esc_html_e( 'Shaping Entrepreneurial Educators for a Changing World', 'ccoe-core' ); ?></h2>
						<p class="ccoe-lead-text"><?php echo esc_html( $s['about_background'] ); ?></p>
						<p><?php echo esc_html( wp_trim_words( $s['about_history'], 45, '...' ) ); ?></p>
						<div class="ccoe-feature-pills">
							<div class="ccoe-pill"><strong>&#10003; NCCE (2026) Compliant:</strong> Full NCE Minimum Standards curriculum</div>
							<div class="ccoe-pill"><strong>&#10003; University Affiliation:</strong> Degree &amp; PGDE with Adekunle Ajasin University (AAUA)</div>
							<div class="ccoe-pill"><strong>&#10003; Self-Employment Focus:</strong> Dedicated Centre for Skills Acquisition &amp; Entrepreneurship</div>
						</div>
						<div style="margin-top:24px;">
							<a href="<?php echo esc_url( $about_url ); ?>" class="ccoe-btn ccoe-btn-primary"><?php esc_html_e( 'Discover More About CCoE', 'ccoe-core' ); ?></a>
						</div>
					</div>
					<div class="ccoe-split-media">
						<div class="ccoe-photo-collage">
							<img src="<?php echo self::img_url( 'academic-block.jpg' ); ?>" alt="<?php esc_attr_e( 'City College of Education Academic Block', 'ccoe-core' ); ?>" class="ccoe-img-main" />
							<img src="<?php echo self::img_url( 'lecture-walkway.jpg' ); ?>" alt="<?php esc_attr_e( 'City College of Education Lecture Wing', 'ccoe-core' ); ?>" class="ccoe-img-accent" />
							<div class="ccoe-motto-card">
								<img src="<?php echo esc_url( $s['logo_url'] ); ?>" alt="<?php esc_attr_e( 'City College of Education Official Crest', 'ccoe-core' ); ?>" />
								<div>
									<strong><?php esc_html_e( 'Our Official Motto', 'ccoe-core' ); ?></strong>
									<span>"<?php echo esc_html( $s['motto'] ); ?>"</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- VISION AND MISSION HIGHLIGHTS -->
			<section class="ccoe-section ccoe-bg-light">
				<div class="ccoe-container">
					<div class="ccoe-section-header ccoe-text-center">
						<span class="ccoe-eyebrow"><?php esc_html_e( 'Our Guiding Mandate', 'ccoe-core' ); ?></span>
						<h2 class="ccoe-section-title"><?php esc_html_e( 'Vision, Mission & Institutional Philosophy', 'ccoe-core' ); ?></h2>
					</div>
					<div class="ccoe-vm-grid">
						<div class="ccoe-vm-card ccoe-vm-vision">
							<div class="ccoe-icon-box"><?php echo self::svg_icon( 'target', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
							<h3><?php esc_html_e( 'Our Vision', 'ccoe-core' ); ?></h3>
							<p><?php echo esc_html( $s['vision_statement'] ); ?></p>
						</div>
						<div class="ccoe-vm-card ccoe-vm-mission">
							<div class="ccoe-icon-box ccoe-icon-box-gold"><?php echo self::svg_icon( 'graduation-cap', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
							<h3><?php esc_html_e( 'Our Mission', 'ccoe-core' ); ?></h3>
							<p><?php echo esc_html( $s['mission_statement'] ); ?></p>
						</div>
						<div class="ccoe-vm-card ccoe-vm-philosophy">
							<div class="ccoe-icon-box ccoe-icon-box-green"><?php echo self::svg_icon( 'lightbulb', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
							<h3><?php esc_html_e( 'Our Philosophy', 'ccoe-core' ); ?></h3>
							<p><?php echo esc_html( $s['about_philosophy'] ); ?></p>
						</div>
					</div>
					<div class="ccoe-split-grid" style="margin-top:32px;">
						<div class="ccoe-card">
							<div class="ccoe-card-body">
								<span class="ccoe-eyebrow"><?php esc_html_e( 'Core Institutional Values', 'ccoe-core' ); ?></span>
								<h3><?php esc_html_e( 'What Guides City College of Education, Mararaba', 'ccoe-core' ); ?></h3>
								<ul class="ccoe-check-list">
									<?php foreach ( CCOE_Data::get_core_values() as $val ) : ?>
										<li><strong><?php echo esc_html( $val['title'] ); ?>:</strong> <?php echo esc_html( $val['description'] ); ?></li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
						<div>
							<img src="<?php echo self::img_url( 'admin-block.jpg' ); ?>" alt="<?php esc_attr_e( 'Vision and Mission Plaques at Administrative Block Entrance', 'ccoe-core' ); ?>" class="ccoe-rounded-img" style="width:100%;height:auto;display:block;" />
							<p class="ccoe-caption-text"><?php esc_html_e( 'Pictured above: The engraved Vision and Mission marble plaques at the entrance of the Administrative Block, City College of Education, Mararaba.', 'ccoe-core' ); ?></p>
						</div>
					</div>
				</div>
			</section>

			<!-- ACADEMIC PROGRAMMES SHOWCASE -->
			<section class="ccoe-section ccoe-bg-white">
				<div class="ccoe-container">
					<div class="ccoe-section-header ccoe-flex-between">
						<div>
							<span class="ccoe-eyebrow"><?php esc_html_e( 'Academic Excellence', 'ccoe-core' ); ?></span>
							<h2 class="ccoe-section-title"><?php esc_html_e( 'Academic Programmes Showcase', 'ccoe-core' ); ?></h2>
						</div>
						<a href="<?php echo esc_url( $prog_url ); ?>" class="ccoe-btn ccoe-btn-outline"><?php esc_html_e( 'View All Programmes & Courses', 'ccoe-core' ); ?> &rarr;</a>
					</div>
					<?php echo self::render_major_programmes_cards(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</section>

			<!-- MESSAGE FROM THE PROVOST -->
			<section class="ccoe-section ccoe-bg-navy">
				<div class="ccoe-container ccoe-provost-highlight">
					<div class="ccoe-provost-photo-wrap">
						<img src="<?php echo esc_url( $s['provost_photo'] ); ?>" alt="<?php echo esc_attr( $s['provost_name'] ); ?>" class="ccoe-provost-img" />
						<div class="ccoe-provost-caption">
							<strong><?php echo esc_html( $s['provost_name'] ); ?></strong>
							<span><?php echo esc_html( $s['provost_designation'] ); ?></span>
						</div>
					</div>
					<div class="ccoe-provost-text">
						<span class="ccoe-eyebrow ccoe-eyebrow-gold"><?php esc_html_e( 'From the Office of the Provost', 'ccoe-core' ); ?></span>
						<h2><?php esc_html_e( 'Message from the Provost', 'ccoe-core' ); ?></h2>
						<blockquote class="ccoe-provost-quote">
							"<?php echo esc_html( $s['provost_welcome'] ); ?>"
						</blockquote>
						<p><?php echo esc_html( wp_trim_words( $s['provost_statement'], 55, '...' ) ); ?></p>
						<a href="<?php echo esc_url( $prov_url ); ?>" class="ccoe-btn ccoe-btn-gold"><?php esc_html_e( 'Read Full Provost\'s Address', 'ccoe-core' ); ?> &rarr;</a>
					</div>
				</div>
			</section>

			<!-- UPCOMING EVENTS & LATEST NEWS -->
			<section class="ccoe-section ccoe-bg-light">
				<div class="ccoe-container">
					<div class="ccoe-section-header ccoe-flex-between">
						<div>
							<span class="ccoe-eyebrow"><?php esc_html_e( 'Campus Happenings', 'ccoe-core' ); ?></span>
							<h2 class="ccoe-section-title"><?php esc_html_e( 'Upcoming Events & Latest Institutional News', 'ccoe-core' ); ?></h2>
						</div>
						<a href="<?php echo esc_url( $event_url ); ?>" class="ccoe-btn ccoe-btn-outline"><?php esc_html_e( 'All News & Events Archive', 'ccoe-core' ); ?> &rarr;</a>
					</div>
					<?php echo self::render_events_grid( 4 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</section>

			<!-- CONTACT & ADMISSIONS CTA BANNER -->
			<section class="ccoe-section ccoe-cta-banner">
				<div class="ccoe-container ccoe-cta-banner-inner">
					<div>
						<span class="ccoe-eyebrow ccoe-eyebrow-gold"><?php esc_html_e( 'Begin Your Academic Journey', 'ccoe-core' ); ?></span>
						<h2><?php esc_html_e( 'Apply for Provisional Admission into NCE, Degree (AAUA), PGDE, or PDE Today', 'ccoe-core' ); ?></h2>
						<p>
							<strong><?php esc_html_e( 'Campus Location:', 'ccoe-core' ); ?></strong> <?php echo esc_html( $s['address'] ); ?><br>
							<strong><?php esc_html_e( 'Admissions Hotlines:', 'ccoe-core' ); ?></strong> <?php echo esc_html( $s['phone_primary'] . ' | ' . $s['phone_secondary'] ); ?> &bull;
							<strong><?php esc_html_e( 'Email:', 'ccoe-core' ); ?></strong> <?php echo esc_html( $s['email_admissions'] ); ?>
						</p>
					</div>
					<div class="ccoe-cta-buttons">
						<a href="<?php echo esc_url( $apply_url ); ?>" class="ccoe-btn ccoe-btn-gold"><?php esc_html_e( 'Complete Online Application Form', 'ccoe-core' ); ?></a>
						<button type="button" onclick="document.getElementById('ccoe-wp-contact-modal').style.display='flex';" class="ccoe-btn ccoe-btn-outline-light"><?php esc_html_e( 'Contact Us & Campus Map', 'ccoe-core' ); ?></button>
					</div>
				</div>
			</section>

			<!-- CONTACT US MODAL DIALOG -->
			<div id="ccoe-wp-contact-modal" style="display:none;position:fixed;inset:0;background:rgba(0,27,66,0.72);z-index:9999;align-items:center;justify-content:center;padding:20px;" onclick="if(event.target===this)this.style.display='none';">
				<div style="background:#fff;width:100%;max-width:1020px;max-height:90vh;border-radius:14px;overflow-y:auto;border-top:5px solid #F5B800;box-shadow:0 25px 50px -12px rgba(0,0,0,0.45);">
					<div style="background:#002355;color:#fff;padding:18px 24px;display:flex;justify-content:space-between;align-items:center;">
						<strong style="font-size:1.25rem;"><?php esc_html_e( 'Contact City College of Education, Mararaba', 'ccoe-core' ); ?></strong>
						<button type="button" onclick="document.getElementById('ccoe-wp-contact-modal').style.display='none';" style="background:rgba(255,255,255,0.15);color:#fff;border:none;width:40px;height:40px;border-radius:8px;cursor:pointer;font-size:1.3rem;">&times;</button>
					</div>
					<div style="padding:24px;">
						<?php echo self::render_contact_page(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * 2. ABOUT US PAGE ([ccoe_about_page])
	 */
	public static function render_about_page() {
		$s           = CCOE_Settings::get_settings();
		$core_values = CCOE_Data::get_core_values();
		$apply_url   = site_url( '/apply-for-provisional-admission/' );

		ob_start();
		?>
		<div class="ccoe-wrapper ccoe-page-about">
			<div class="ccoe-page-banner" style="background-image: linear-gradient(120deg, rgba(0, 35, 85, 0.92), rgba(0, 51, 124, 0.82)), url('<?php echo self::img_url( 'campus-boulevard.jpg' ); ?>');">
				<div class="ccoe-container">
					<span class="ccoe-eyebrow ccoe-eyebrow-gold"><?php esc_html_e( 'Who We Are', 'ccoe-core' ); ?></span>
					<h1><?php esc_html_e( 'About City College of Education, Mararaba', 'ccoe-core' ); ?></h1>
					<p><?php echo esc_html( $s['address'] ); ?></p>
				</div>
			</div>

			<section class="ccoe-section ccoe-bg-white">
				<div class="ccoe-container ccoe-split-grid">
					<div>
						<span class="ccoe-eyebrow"><?php esc_html_e( 'Institutional Background', 'ccoe-core' ); ?></span>
						<h2><?php esc_html_e( 'A Premier Citadel of Teacher & Entrepreneurial Education', 'ccoe-core' ); ?></h2>
						<p class="ccoe-lead-text"><?php echo esc_html( $s['about_background'] ); ?></p>

						<h3 style="margin-top:28px;"><?php esc_html_e( 'Our History', 'ccoe-core' ); ?></h3>
						<p><?php echo esc_html( $s['about_history'] ); ?></p>

						<h3 style="margin-top:28px;"><?php esc_html_e( 'Institutional Philosophy', 'ccoe-core' ); ?></h3>
						<p><?php echo esc_html( $s['about_philosophy'] ); ?></p>
					</div>
					<div>
						<img src="<?php echo self::img_url( 'campus-main-gate.jpg' ); ?>" alt="<?php esc_attr_e( 'City College of Education Main Gate, Mararaba', 'ccoe-core' ); ?>" class="ccoe-rounded-img" style="margin-bottom:20px;" />
						<img src="<?php echo self::img_url( 'admin-block-exterior.jpg' ); ?>" alt="<?php esc_attr_e( 'City College of Education Administrative Block', 'ccoe-core' ); ?>" class="ccoe-rounded-img" />
					</div>
				</div>
			</section>

			<section class="ccoe-section ccoe-bg-light">
				<div class="ccoe-container">
					<div class="ccoe-vm-grid" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));">
						<div class="ccoe-vm-card ccoe-vm-vision">
							<span class="ccoe-eyebrow"><?php esc_html_e( 'Official Vision Statement', 'ccoe-core' ); ?></span>
							<h3><?php esc_html_e( 'Our Vision', 'ccoe-core' ); ?></h3>
							<p><?php echo esc_html( $s['vision_statement'] ); ?></p>
						</div>
						<div class="ccoe-vm-card ccoe-vm-mission">
							<span class="ccoe-eyebrow"><?php esc_html_e( 'Official Mission Statement', 'ccoe-core' ); ?></span>
							<h3><?php esc_html_e( 'Our Mission', 'ccoe-core' ); ?></h3>
							<p><?php echo esc_html( $s['mission_statement'] ); ?></p>
						</div>
					</div>
				</div>
			</section>

			<section class="ccoe-section ccoe-bg-white">
				<div class="ccoe-container">
					<div class="ccoe-section-header ccoe-text-center">
						<span class="ccoe-eyebrow"><?php esc_html_e( 'What We Stand For', 'ccoe-core' ); ?></span>
						<h2 class="ccoe-section-title"><?php esc_html_e( 'Our Core Values', 'ccoe-core' ); ?></h2>
					</div>
					<div class="ccoe-cards-grid-3">
						<?php foreach ( $core_values as $val ) : ?>
							<div class="ccoe-card">
								<div class="ccoe-card-body">
									<h3><?php echo esc_html( $val['title'] ); ?></h3>
									<p><?php echo esc_html( $val['description'] ); ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="ccoe-text-center" style="margin-top:36px;">
						<a href="<?php echo esc_url( $apply_url ); ?>" class="ccoe-btn ccoe-btn-gold"><?php esc_html_e( 'Apply for Provisional Admission', 'ccoe-core' ); ?> &rarr;</a>
					</div>
				</div>
			</section>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * 3. OUR VISION AND MISSION PAGE ([ccoe_vision_mission_page])
	 */
	public static function render_vision_mission_page() {
		$s           = CCOE_Settings::get_settings();
		$core_values = CCOE_Data::get_core_values();

		ob_start();
		?>
		<div class="ccoe-wrapper ccoe-page-vision-mission">
			<div class="ccoe-page-banner" style="background-image: linear-gradient(120deg, rgba(0, 35, 85, 0.92), rgba(0, 122, 51, 0.80)), url('<?php echo self::img_url( 'campus-courtyard-statue.jpg' ); ?>');">
				<div class="ccoe-container">
					<span class="ccoe-eyebrow ccoe-eyebrow-gold"><?php esc_html_e( 'Institutional Charter', 'ccoe-core' ); ?></span>
					<h1><?php esc_html_e( 'Our Vision and Mission', 'ccoe-core' ); ?></h1>
					<p><?php echo esc_html( '"' . $s['motto'] . '"' ); ?></p>
				</div>
			</div>

			<section class="ccoe-section ccoe-bg-white">
				<div class="ccoe-container">
					<div class="ccoe-split-grid">
						<div>
							<div class="ccoe-vm-card ccoe-vm-vision" style="margin-bottom:24px;">
								<span class="ccoe-eyebrow"><?php esc_html_e( 'Vision of the College', 'ccoe-core' ); ?></span>
								<h2><?php esc_html_e( 'Our Vision', 'ccoe-core' ); ?></h2>
								<p class="ccoe-lead-text"><?php echo esc_html( $s['vision_statement'] ); ?></p>
							</div>
							<div class="ccoe-vm-card ccoe-vm-mission">
								<span class="ccoe-eyebrow"><?php esc_html_e( 'Mission of the College', 'ccoe-core' ); ?></span>
								<h2><?php esc_html_e( 'Our Mission', 'ccoe-core' ); ?></h2>
								<p class="ccoe-lead-text"><?php echo esc_html( $s['mission_statement'] ); ?></p>
							</div>
						</div>
						<div>
							<img src="<?php echo self::img_url( 'admin-block-entrance.jpg' ); ?>" alt="<?php esc_attr_e( 'Vision and Mission Plaques at the Administrative Block Entrance, City College of Education', 'ccoe-core' ); ?>" class="ccoe-rounded-img" />
							<p class="ccoe-caption-text">
								<?php esc_html_e( 'Pictured above: The engraved Vision and Mission marble plaques flanking the entrance to the Administrative Block at City College of Education, Mararaba.', 'ccoe-core' ); ?>
							</p>
						</div>
					</div>
				</div>
			</section>

			<section class="ccoe-section ccoe-bg-light">
				<div class="ccoe-container">
					<div class="ccoe-section-header ccoe-text-center">
						<span class="ccoe-eyebrow"><?php esc_html_e( 'Institutional Pillars', 'ccoe-core' ); ?></span>
						<h2 class="ccoe-section-title"><?php esc_html_e( 'Core Values & Strategic Objectives', 'ccoe-core' ); ?></h2>
					</div>
					<div class="ccoe-cards-grid-3">
						<?php foreach ( $core_values as $val ) : ?>
							<div class="ccoe-card">
								<div class="ccoe-card-body">
									<h3><?php echo esc_html( $val['title'] ); ?></h3>
									<p><?php echo esc_html( $val['description'] ); ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * 4. MESSAGE FROM THE PROVOST PAGE ([ccoe_provost_page])
	 */
	public static function render_provost_page() {
		$s         = CCOE_Settings::get_settings();
		$apply_url = site_url( '/apply-for-provisional-admission/' );

		ob_start();
		?>
		<div class="ccoe-wrapper ccoe-page-provost">
			<div class="ccoe-page-banner" style="background-image: linear-gradient(120deg, rgba(0, 35, 85, 0.92), rgba(0, 51, 124, 0.85)), url('<?php echo self::img_url( 'academic-block.jpg' ); ?>');">
				<div class="ccoe-container">
					<span class="ccoe-eyebrow ccoe-eyebrow-gold"><?php esc_html_e( 'Institutional Leadership', 'ccoe-core' ); ?></span>
					<h1><?php esc_html_e( 'Message from the Provost', 'ccoe-core' ); ?></h1>
					<p><?php echo esc_html( $s['institution_name'] ); ?></p>
				</div>
			</div>

			<section class="ccoe-section ccoe-bg-white">
				<div class="ccoe-container ccoe-split-grid">
					<div class="ccoe-provost-profile-card">
						<img src="<?php echo esc_url( $s['provost_photo'] ); ?>" alt="<?php echo esc_attr( $s['provost_name'] ); ?>" class="ccoe-rounded-img" />
						<div class="ccoe-provost-meta">
							<h3><?php echo esc_html( $s['provost_name'] ); ?></h3>
							<p class="ccoe-designation"><?php echo esc_html( $s['provost_designation'] ); ?></p>
							<hr>
							<p><small><?php esc_html_e( 'Note: Official Provost portrait and full academic biography can be updated anytime under WP Admin → College Settings → Office of the Provost.', 'ccoe-core' ); ?></small></p>
						</div>
					</div>
					<div>
						<span class="ccoe-eyebrow"><?php esc_html_e( 'Welcome Address', 'ccoe-core' ); ?></span>
						<h2><?php esc_html_e( 'Building Superior Entrepreneurial Teachers for Nigeria', 'ccoe-core' ); ?></h2>
						<blockquote class="ccoe-provost-quote-light">
							"<?php echo esc_html( $s['provost_welcome'] ); ?>"
						</blockquote>
						<p><?php echo nl2br( esc_html( $s['provost_statement'] ) ); ?></p>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
						</p>
						<div class="ccoe-signature-block">
							<strong><?php echo esc_html( $s['provost_name'] ); ?></strong><br>
							<span><?php echo esc_html( $s['provost_designation'] ); ?></span>
						</div>
						<div style="margin-top:28px;">
							<a href="<?php echo esc_url( $apply_url ); ?>" class="ccoe-btn ccoe-btn-primary"><?php esc_html_e( 'Apply for Provisional Admission', 'ccoe-core' ); ?> &rarr;</a>
						</div>
					</div>
				</div>
			</section>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * 5. ACADEMIC PROGRAMMES PAGE ([ccoe_programmes_page])
	 */
	public static function render_programmes_page() {
		$nce_schools = CCOE_Data::get_nce_schools();
		$deg_depts   = CCOE_Data::get_degree_departments();
		$skills      = CCOE_Data::get_skills_courses();
		$apply_url   = site_url( '/apply-for-provisional-admission/' );
		$courses_url = site_url( '/courses/' );

		ob_start();
		?>
		<div class="ccoe-wrapper ccoe-page-programmes">
			<div class="ccoe-page-banner" style="background-image: linear-gradient(120deg, rgba(0, 35, 85, 0.92), rgba(0, 51, 124, 0.84)), url('<?php echo self::img_url( 'lecture-walkway.jpg' ); ?>');">
				<div class="ccoe-container">
					<span class="ccoe-eyebrow ccoe-eyebrow-gold"><?php esc_html_e( 'Schools, Faculties & Affiliations', 'ccoe-core' ); ?></span>
					<h1><?php esc_html_e( 'Academic Programmes', 'ccoe-core' ); ?></h1>
					<p><?php esc_html_e( 'NCE (New NCCE Minimum Standard 2026) • B.Ed./B.Sc. (Ed.) Degree & PGDE (Affiliated with AAUA) • PDE • Skills Acquisition', 'ccoe-core' ); ?></p>
				</div>
			</div>

			<!-- OVERVIEW CARDS -->
			<section class="ccoe-section ccoe-bg-white">
				<div class="ccoe-container">
					<div class="ccoe-section-header ccoe-text-center">
						<span class="ccoe-eyebrow"><?php esc_html_e( 'Programme Categories', 'ccoe-core' ); ?></span>
						<h2 class="ccoe-section-title"><?php esc_html_e( 'The Programmes of City College of Education, Mararaba', 'ccoe-core' ); ?></h2>
					</div>
					<?php echo self::render_major_programmes_cards(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</section>

			<!-- SECTION A: NCE SCHOOLS -->
			<section class="ccoe-section ccoe-bg-light" id="nce-schools">
				<div class="ccoe-container">
					<div class="ccoe-section-header">
						<span class="ccoe-eyebrow"><?php esc_html_e( '1. Nigeria Certificate in Education (NCE)', 'ccoe-core' ); ?></span>
						<h2 class="ccoe-section-title"><?php esc_html_e( 'NCE Schools & Programmes (New NCCE Minimum Standard 2026)', 'ccoe-core' ); ?></h2>
						<p><?php esc_html_e( 'Duration: 3 Years (6 Semesters) • Entry Requirement: Minimum of 4–5 O\'Level Credits including English & Mathematics.', 'ccoe-core' ); ?></p>
					</div>
					<div class="ccoe-cards-grid-3">
						<?php foreach ( $nce_schools as $school ) : ?>
							<div class="ccoe-card ccoe-school-card">
								<div class="ccoe-card-header">
									<span class="ccoe-badge"><?php echo esc_html( $school['code'] . ' • ' . $school['level'] ); ?></span>
									<h3><?php echo esc_html( $school['name'] ); ?></h3>
								</div>
								<div class="ccoe-card-body">
									<p><?php echo esc_html( $school['description'] ); ?></p>
									<h4><?php esc_html_e( 'Available Programmes / Courses:', 'ccoe-core' ); ?></h4>
									<ul class="ccoe-check-list">
										<?php foreach ( $school['programmes'] as $prog ) : ?>
											<li><?php echo esc_html( $prog ); ?></li>
										<?php endforeach; ?>
									</ul>
									<div class="ccoe-card-meta">
										<span><strong><?php esc_html_e( 'Duration:', 'ccoe-core' ); ?></strong> <?php echo esc_html( $school['duration'] ); ?></span>
									</div>
								</div>
								<div class="ccoe-card-footer">
									<a href="<?php echo esc_url( $apply_url ); ?>" class="ccoe-btn ccoe-btn-primary ccoe-btn-sm"><?php esc_html_e( 'Apply Now', 'ccoe-core' ); ?></a>
									<a href="<?php echo esc_url( $courses_url ); ?>" class="ccoe-btn ccoe-btn-outline ccoe-btn-sm"><?php esc_html_e( 'Course Details', 'ccoe-core' ); ?></a>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>

			<!-- SECTION B: DEGREE IN AFFILIATION WITH AAUA -->
			<section class="ccoe-section ccoe-bg-white" id="degree-aaua">
				<div class="ccoe-container">
					<div class="ccoe-section-header">
						<span class="ccoe-eyebrow"><?php esc_html_e( '2. Undergraduate Degree Programmes', 'ccoe-core' ); ?></span>
						<h2 class="ccoe-section-title"><?php esc_html_e( 'Degree in Affiliation with Adekunle Ajasin University, Akungba-Akoko (AAUA), Ondo State', 'ccoe-core' ); ?></h2>
						<p><?php esc_html_e( 'Duration: 4 Years (UTME) / 3 Years (Direct Entry) • Awarded by Adekunle Ajasin University, Akungba-Akoko.', 'ccoe-core' ); ?></p>
					</div>
					<div class="ccoe-cards-grid-3">
						<?php foreach ( $deg_depts as $dept ) : ?>
							<div class="ccoe-card ccoe-school-card">
								<div class="ccoe-card-header">
									<span class="ccoe-badge ccoe-badge-gold"><?php echo esc_html( $dept['code'] . ' • AAUA Degree' ); ?></span>
									<h3><?php echo esc_html( 'Department of ' . $dept['department'] ); ?></h3>
								</div>
								<div class="ccoe-card-body">
									<h4><?php esc_html_e( 'Available Degree Programmes:', 'ccoe-core' ); ?></h4>
									<ul class="ccoe-check-list">
										<?php foreach ( $dept['programmes'] as $prog ) : ?>
											<li><?php echo esc_html( $prog ); ?></li>
										<?php endforeach; ?>
									</ul>
									<div class="ccoe-card-meta">
										<span><strong><?php esc_html_e( 'Duration:', 'ccoe-core' ); ?></strong> <?php echo esc_html( $dept['duration'] ); ?></span>
									</div>
								</div>
								<div class="ccoe-card-footer">
									<a href="<?php echo esc_url( $apply_url ); ?>" class="ccoe-btn ccoe-btn-primary ccoe-btn-sm"><?php esc_html_e( 'Apply for Degree', 'ccoe-core' ); ?></a>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>

			<!-- SECTION C: SKILLS ACQUISITION -->
			<section class="ccoe-section ccoe-bg-light" id="skills-department">
				<div class="ccoe-container ccoe-split-grid">
					<div>
						<span class="ccoe-eyebrow"><?php esc_html_e( 'Learn Today, Earn Tomorrow!', 'ccoe-core' ); ?></span>
						<h2><?php esc_html_e( 'Centre for Skills Acquisition & Entrepreneurship Development', 'ccoe-core' ); ?></h2>
						<p><?php esc_html_e( 'The Skills Department at City College of Education, Mararaba offers intensive skills acquisition training programmes designed to empower individuals with practical, income-generating skills at affordable fees.', 'ccoe-core' ); ?></p>
						<table class="ccoe-table">
							<thead>
								<tr>
									<th><?php esc_html_e( 'S/N', 'ccoe-core' ); ?></th>
									<th><?php esc_html_e( 'Training Area / Skill', 'ccoe-core' ); ?></th>
									<th><?php esc_html_e( 'Duration', 'ccoe-core' ); ?></th>
									<th><?php esc_html_e( 'Fees', 'ccoe-core' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $skills as $sk ) : ?>
									<tr>
										<td><?php echo esc_html( $sk['sn'] ); ?></td>
										<td><strong><?php echo esc_html( $sk['skill'] ); ?></strong></td>
										<td><?php echo esc_html( $sk['duration'] ); ?></td>
										<td><span class="ccoe-badge ccoe-badge-green"><?php echo esc_html( $sk['fees'] ); ?></span></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<div style="margin-top:20px;">
							<a href="<?php echo esc_url( $apply_url ); ?>" class="ccoe-btn ccoe-btn-gold"><?php esc_html_e( 'Enrol in a Skill Programme', 'ccoe-core' ); ?></a>
						</div>
					</div>
					<div>
						<img src="<?php echo self::img_url( 'skills-department-flyer.jpg' ); ?>" alt="<?php esc_attr_e( 'City College of Education Skill Department Official Brochure', 'ccoe-core' ); ?>" class="ccoe-rounded-img" />
					</div>
				</div>
			</section>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * 6. COURSES PAGE ([ccoe_courses_page])
	 */
	public static function render_courses_page() {
		$nce_schools = CCOE_Data::get_nce_schools();
		$deg_depts   = CCOE_Data::get_degree_departments();
		$pgde        = CCOE_Data::get_pgde_courses();
		$apply_url   = site_url( '/apply-for-provisional-admission/' );

		ob_start();
		?>
		<div class="ccoe-wrapper ccoe-page-courses">
			<div class="ccoe-page-banner" style="background-image: linear-gradient(120deg, rgba(0, 35, 85, 0.92), rgba(0, 51, 124, 0.85)), url('<?php echo self::img_url( 'academic-block.jpg' ); ?>');">
				<div class="ccoe-container">
					<span class="ccoe-eyebrow ccoe-eyebrow-gold"><?php esc_html_e( 'Official Curriculum & Course Catalog', 'ccoe-core' ); ?></span>
					<h1><?php esc_html_e( 'Courses Offered', 'ccoe-core' ); ?></h1>
					<p><?php esc_html_e( 'Complete Directory of NCE (NCCE 2026), AAUA Affiliated Degree, PGDE, and PDE Courses at City College of Education, Mararaba', 'ccoe-core' ); ?></p>
				</div>
			</div>

			<section class="ccoe-section ccoe-bg-white">
				<div class="ccoe-container">
					<!-- Quick Filter Bar -->
					<div class="ccoe-course-filter-bar">
						<input type="search" id="ccoe-course-search" class="ccoe-input" placeholder="<?php esc_attr_e( 'Search courses by title, code (e.g. PDE 601, Computer Science, Accounting)...', 'ccoe-core' ); ?>" />
					</div>

					<!-- 1. PGDE COURSES TABLE (First & Second Semester) -->
					<div class="ccoe-course-block" id="pgde-courses">
						<span class="ccoe-eyebrow"><?php esc_html_e( 'Postgraduate Diploma in Education (PGDE)', 'ccoe-core' ); ?></span>
						<h2><?php esc_html_e( 'PGDE Courses in Affiliation with Adekunle Ajasin University, Akungba-Akoko (AAUA)', 'ccoe-core' ); ?></h2>
						<p><?php esc_html_e( 'Below are the First Semester and Second Semester compulsory courses for the Postgraduate Diploma in Education (PGDE) programme.', 'ccoe-core' ); ?></p>

						<div class="ccoe-semester-grid">
							<div>
								<h3><?php esc_html_e( 'First Semester Courses', 'ccoe-core' ); ?></h3>
								<table class="ccoe-table ccoe-searchable-table">
									<thead>
										<tr>
											<th><?php esc_html_e( 'S/N', 'ccoe-core' ); ?></th>
											<th><?php esc_html_e( 'Course Code', 'ccoe-core' ); ?></th>
											<th><?php esc_html_e( 'Course Title', 'ccoe-core' ); ?></th>
											<th><?php esc_html_e( 'Credit Unit', 'ccoe-core' ); ?></th>
											<th><?php esc_html_e( 'Status', 'ccoe-core' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ( $pgde['first_semester'] as $c ) : ?>
											<tr>
												<td><?php echo esc_html( $c['sn'] ); ?></td>
												<td><code><?php echo esc_html( $c['code'] ); ?></code></td>
												<td><strong><?php echo esc_html( $c['title'] ); ?></strong></td>
												<td><?php echo esc_html( $c['units'] ); ?></td>
												<td><span class="ccoe-badge"><?php echo esc_html( $c['status'] ); ?></span></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
									<tfoot>
										<tr>
											<th colspan="3"><?php esc_html_e( 'Total First Semester Credit Units', 'ccoe-core' ); ?></th>
											<th colspan="2">20 Units</th>
										</tr>
									</tfoot>
								</table>
							</div>

							<div>
								<h3><?php esc_html_e( 'Second Semester Courses', 'ccoe-core' ); ?></h3>
								<table class="ccoe-table ccoe-searchable-table">
									<thead>
										<tr>
											<th><?php esc_html_e( 'S/N', 'ccoe-core' ); ?></th>
											<th><?php esc_html_e( 'Course Code', 'ccoe-core' ); ?></th>
											<th><?php esc_html_e( 'Course Title', 'ccoe-core' ); ?></th>
											<th><?php esc_html_e( 'Credit Unit', 'ccoe-core' ); ?></th>
											<th><?php esc_html_e( 'Status', 'ccoe-core' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ( $pgde['second_semester'] as $c ) : ?>
											<tr>
												<td><?php echo esc_html( $c['sn'] ); ?></td>
												<td><code><?php echo esc_html( $c['code'] ); ?></code></td>
												<td><strong><?php echo esc_html( $c['title'] ); ?></strong></td>
												<td><?php echo esc_html( $c['units'] ); ?></td>
												<td><span class="ccoe-badge"><?php echo esc_html( $c['status'] ); ?></span></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
									<tfoot>
										<tr>
											<th colspan="3"><?php esc_html_e( 'Total Second Semester Credit Units', 'ccoe-core' ); ?></th>
											<th colspan="2">20 Units</th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>

					<!-- 2. NCE COURSES BY SCHOOL -->
					<div class="ccoe-course-block" style="margin-top:48px;">
						<span class="ccoe-eyebrow"><?php esc_html_e( 'New NCCE Minimum Standard (2026)', 'ccoe-core' ); ?></span>
						<h2><?php esc_html_e( 'A. Nigeria Certificate in Education (NCE) Courses by School', 'ccoe-core' ); ?></h2>
						<table class="ccoe-table ccoe-searchable-table">
							<thead>
								<tr>
									<th><?php esc_html_e( 'School', 'ccoe-core' ); ?></th>
									<th><?php esc_html_e( 'Courses / Programmes Offered', 'ccoe-core' ); ?></th>
									<th><?php esc_html_e( 'Duration', 'ccoe-core' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $nce_schools as $school ) : ?>
									<tr>
										<td><strong><?php echo esc_html( $school['code'] . '. ' . $school['name'] ); ?></strong></td>
										<td>
											<ul class="ccoe-inline-list">
												<?php foreach ( $school['programmes'] as $idx => $prog ) : ?>
													<li><?php echo esc_html( ( $idx + 1 ) . '. ' . $prog ); ?></li>
												<?php endforeach; ?>
											</ul>
										</td>
										<td><?php echo esc_html( $school['duration'] ); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>

					<!-- 3. DEGREE COURSES IN AFFILIATION WITH AAUA -->
					<div class="ccoe-course-block" style="margin-top:48px;">
						<span class="ccoe-eyebrow"><?php esc_html_e( 'Undergraduate Degree Courses', 'ccoe-core' ); ?></span>
						<h2><?php esc_html_e( 'B. Degree Courses in Affiliation with Adekunle Ajasin University, Akungba-Akoko (AAUA)', 'ccoe-core' ); ?></h2>
						<table class="ccoe-table ccoe-searchable-table">
							<thead>
								<tr>
									<th><?php esc_html_e( 'Department', 'ccoe-core' ); ?></th>
									<th><?php esc_html_e( 'Degree Programme / Course of Study', 'ccoe-core' ); ?></th>
									<th><?php esc_html_e( 'Duration', 'ccoe-core' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $deg_depts as $dept ) : ?>
									<tr>
										<td><strong><?php echo esc_html( $dept['code'] . '. ' . $dept['department'] ); ?></strong></td>
										<td>
											<ul class="ccoe-inline-list">
												<?php foreach ( $dept['programmes'] as $prog ) : ?>
													<li><?php echo esc_html( $prog ); ?></li>
												<?php endforeach; ?>
											</ul>
										</td>
										<td><?php echo esc_html( $dept['duration'] ); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>

					<div class="ccoe-text-center" style="margin-top:40px;">
						<a href="<?php echo esc_url( $apply_url ); ?>" class="ccoe-btn ccoe-btn-gold"><?php esc_html_e( 'Apply for Your Preferred Course Now', 'ccoe-core' ); ?> &rarr;</a>
					</div>
				</div>
			</section>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * 7. ADMISSIONS OVERVIEW PAGE ([ccoe_admissions_page])
	 */
	public static function render_admissions_page() {
		$s         = CCOE_Settings::get_settings();
		$apply_url = site_url( '/apply-for-provisional-admission/' );
		$progs     = CCOE_Data::get_major_programmes();

		ob_start();
		?>
		<div class="ccoe-wrapper ccoe-page-admissions">
			<div class="ccoe-page-banner" style="background-image: linear-gradient(120deg, rgba(0, 35, 85, 0.92), rgba(0, 122, 51, 0.82)), url('<?php echo self::img_url( 'campus-main-gate.jpg' ); ?>');">
				<div class="ccoe-container">
					<span class="ccoe-eyebrow ccoe-eyebrow-gold"><?php echo esc_html( $s['academic_session'] . ' Academic Session' ); ?></span>
					<h1><?php esc_html_e( 'Admissions Information & Guidelines', 'ccoe-core' ); ?></h1>
					<p><?php esc_html_e( 'Entry Requirements, Application Procedure, and Screening Guidelines for NCE, Degree (AAUA), PGDE, PDE & Skills Programmes', 'ccoe-core' ); ?></p>
				</div>
			</div>

			<section class="ccoe-section ccoe-bg-white">
				<div class="ccoe-container">
					<div class="ccoe-section-header ccoe-text-center">
						<span class="ccoe-eyebrow"><?php esc_html_e( 'How to Apply', 'ccoe-core' ); ?></span>
						<h2 class="ccoe-section-title"><?php esc_html_e( '4 Simple Steps to Provisional Admission', 'ccoe-core' ); ?></h2>
					</div>
					<div class="ccoe-steps-grid">
						<div class="ccoe-step-card">
							<div class="ccoe-step-num">01</div>
							<h3><?php esc_html_e( 'Choose Your Programme', 'ccoe-core' ); ?></h3>
							<p><?php esc_html_e( 'Review the admission requirements for NCE, AAUA Affiliated Degree, PGDE, PDE, or Skills Acquisition.', 'ccoe-core' ); ?></p>
						</div>
						<div class="ccoe-step-card">
							<div class="ccoe-step-num">02</div>
							<h3><?php esc_html_e( 'Fill Online Form', 'ccoe-core' ); ?></h3>
							<p><?php esc_html_e( 'Complete the Provisional Admission Application Form with your personal, academic, and Next of Kin details.', 'ccoe-core' ); ?></p>
						</div>
						<div class="ccoe-step-card">
							<div class="ccoe-step-num">03</div>
							<h3><?php esc_html_e( 'Upload Credentials', 'ccoe-core' ); ?></h3>
							<p><?php esc_html_e( 'Upload a clear passport photograph and your academic result/credentials (WAEC/NECO/NABTEB/Degree/HND).', 'ccoe-core' ); ?></p>
						</div>
						<div class="ccoe-step-card">
							<div class="ccoe-step-num">04</div>
							<h3><?php esc_html_e( 'Print Reference Slip', 'ccoe-core' ); ?></h3>
							<p><?php esc_html_e( 'Receive your unique Application Reference Number immediately and bring your acknowledgement slip for campus screening.', 'ccoe-core' ); ?></p>
						</div>
					</div>

					<div style="margin-top:48px;">
						<h2 class="ccoe-section-title"><?php esc_html_e( 'Entry Requirements by Programme Level', 'ccoe-core' ); ?></h2>
						<div class="ccoe-cards-grid-2">
							<?php foreach ( $progs as $p ) : ?>
								<div class="ccoe-card">
									<div class="ccoe-card-body">
										<span class="ccoe-badge"><?php echo esc_html( $p['badge'] ); ?></span>
										<h3><?php echo esc_html( $p['title'] ); ?></h3>
										<p><strong><?php esc_html_e( 'Duration:', 'ccoe-core' ); ?></strong> <?php echo esc_html( $p['duration'] ); ?></p>
										<p><strong><?php esc_html_e( 'Admission Requirements:', 'ccoe-core' ); ?></strong> <?php echo esc_html( $p['requirements'] ); ?></p>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<div class="ccoe-text-center" style="margin-top:40px;">
						<a href="<?php echo esc_url( $apply_url ); ?>" class="ccoe-btn ccoe-btn-gold"><?php esc_html_e( 'Proceed to Online Provisional Admission Form', 'ccoe-core' ); ?> &rarr;</a>
					</div>
				</div>
			</section>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * 8. NEWS AND EVENTS PAGE ([ccoe_news_events_page])
	 */
	public static function render_news_events_page() {
		ob_start();
		?>
		<div class="ccoe-wrapper ccoe-page-events">
			<div class="ccoe-page-banner" style="background-image: linear-gradient(120deg, rgba(0, 35, 85, 0.92), rgba(0, 51, 124, 0.84)), url('<?php echo self::img_url( 'campus-tree-walkway.jpg' ); ?>');">
				<div class="ccoe-container">
					<span class="ccoe-eyebrow ccoe-eyebrow-gold"><?php esc_html_e( 'Official Bulletins & Calendar', 'ccoe-core' ); ?></span>
					<h1><?php esc_html_e( 'News and Events', 'ccoe-core' ); ?></h1>
					<p><?php esc_html_e( 'Stay informed on academic dates, matriculation, seminars, workshops, and campus announcements at CCoE Mararaba', 'ccoe-core' ); ?></p>
				</div>
			</div>

			<section class="ccoe-section ccoe-bg-white">
				<div class="ccoe-container">
					<div class="ccoe-section-header">
						<span class="ccoe-eyebrow"><?php esc_html_e( 'Academic Calendar & Activities', 'ccoe-core' ); ?></span>
						<h2 class="ccoe-section-title"><?php esc_html_e( 'Upcoming Events & Announcements', 'ccoe-core' ); ?></h2>
					</div>
					<?php echo self::render_events_grid( 12 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</section>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * 9. CONTACT US PAGE ([ccoe_contact_page])
	 */
	public static function render_contact_page() {
		$s              = CCOE_Settings::get_settings();
		$contact_result = CCOE_Admissions::get_contact_result();

		ob_start();
		?>
		<div class="ccoe-wrapper ccoe-page-contact">
			<div class="ccoe-page-banner" style="background-image: linear-gradient(120deg, rgba(0, 35, 85, 0.92), rgba(0, 51, 124, 0.84)), url('<?php echo self::img_url( 'campus-boulevard.jpg' ); ?>');">
				<div class="ccoe-container">
					<span class="ccoe-eyebrow ccoe-eyebrow-gold"><?php esc_html_e( 'Get in Touch With Us', 'ccoe-core' ); ?></span>
					<h1><?php esc_html_e( 'Contact City College of Education, Mararaba', 'ccoe-core' ); ?></h1>
					<p><?php echo esc_html( $s['address'] ); ?></p>
				</div>
			</div>

			<section class="ccoe-section ccoe-bg-white">
				<div class="ccoe-container">
					<div class="ccoe-cards-grid-3" style="margin-bottom:40px;">
						<div class="ccoe-card">
							<div class="ccoe-card-body">
								<div class="ccoe-icon-box" style="margin-bottom:12px;"><?php echo self::svg_icon( 'map-pin', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
								<h3><?php esc_html_e( 'Campus Address', 'ccoe-core' ); ?></h3>
								<p><strong><?php esc_html_e( 'Main Campus:', 'ccoe-core' ); ?></strong><br><?php echo esc_html( $s['address'] ); ?></p>
								<p><strong><?php esc_html_e( 'Postal Address:', 'ccoe-core' ); ?></strong><br><?php echo esc_html( $s['postal_address'] ); ?></p>
							</div>
						</div>
						<div class="ccoe-card">
							<div class="ccoe-card-body">
								<div class="ccoe-icon-box" style="margin-bottom:12px;"><?php echo self::svg_icon( 'phone', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
								<h3><?php esc_html_e( 'Telephone Numbers', 'ccoe-core' ); ?></h3>
								<p><strong><?php esc_html_e( 'Admissions & Enquiries:', 'ccoe-core' ); ?></strong><br>
									<a href="tel:<?php echo esc_attr( $s['phone_primary'] ); ?>"><?php echo esc_html( $s['phone_primary'] ); ?></a><br>
									<a href="tel:<?php echo esc_attr( $s['phone_secondary'] ); ?>"><?php echo esc_html( $s['phone_secondary'] ); ?></a>
								</p>
								<p><small><?php esc_html_e( 'Office Hours: Monday – Friday, 8:00 AM – 4:00 PM (WAT)', 'ccoe-core' ); ?></small></p>
							</div>
						</div>
						<div class="ccoe-card">
							<div class="ccoe-card-body">
								<div class="ccoe-icon-box" style="margin-bottom:12px;"><?php echo self::svg_icon( 'mail', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
								<h3><?php esc_html_e( 'Official Email & Social Links', 'ccoe-core' ); ?></h3>
								<p>
									<strong><?php esc_html_e( 'Registry / General:', 'ccoe-core' ); ?></strong> <a href="mailto:<?php echo esc_attr( $s['email_official'] ); ?>"><?php echo esc_html( $s['email_official'] ); ?></a><br>
									<strong><?php esc_html_e( 'Admissions Office:', 'ccoe-core' ); ?></strong> <a href="mailto:<?php echo esc_attr( $s['email_admissions'] ); ?>"><?php echo esc_html( $s['email_admissions'] ); ?></a>
								</p>
								<div class="ccoe-social-row">
									<a href="<?php echo esc_url( $s['facebook_url'] ); ?>" target="_blank" rel="noopener">Facebook</a> &bull;
									<a href="<?php echo esc_url( $s['twitter_url'] ); ?>" target="_blank" rel="noopener">X (Twitter)</a> &bull;
									<a href="<?php echo esc_url( $s['instagram_url'] ); ?>" target="_blank" rel="noopener">Instagram</a> &bull;
									<a href="<?php echo esc_url( $s['linkedin_url'] ); ?>" target="_blank" rel="noopener">LinkedIn</a>
								</div>
							</div>
						</div>
					</div>

					<div class="ccoe-split-grid">
						<div class="ccoe-form-card">
							<h2><?php esc_html_e( 'Send Us a Message', 'ccoe-core' ); ?></h2>
							<?php if ( ! empty( $contact_result ) ) : ?>
								<div class="ccoe-alert <?php echo $contact_result['success'] ? 'ccoe-alert-success' : 'ccoe-alert-error'; ?>">
									<?php echo esc_html( $contact_result['message'] ); ?>
								</div>
							<?php endif; ?>
							<form method="post" class="ccoe-form">
								<?php wp_nonce_field( 'ccoe_submit_contact', 'ccoe_contact_nonce' ); ?>
								<div class="ccoe-form-group">
									<label for="contact_name"><?php esc_html_e( 'Your Full Name *', 'ccoe-core' ); ?></label>
									<input type="text" id="contact_name" name="contact_name" required class="ccoe-input" />
								</div>
								<div class="ccoe-form-row-2">
									<div class="ccoe-form-group">
										<label for="contact_email"><?php esc_html_e( 'Email Address *', 'ccoe-core' ); ?></label>
										<input type="email" id="contact_email" name="contact_email" required class="ccoe-input" />
									</div>
									<div class="ccoe-form-group">
										<label for="contact_phone"><?php esc_html_e( 'Telephone Number', 'ccoe-core' ); ?></label>
										<input type="tel" id="contact_phone" name="contact_phone" class="ccoe-input" />
									</div>
								</div>
								<div class="ccoe-form-group">
									<label for="contact_subject"><?php esc_html_e( 'Subject *', 'ccoe-core' ); ?></label>
									<input type="text" id="contact_subject" name="contact_subject" required class="ccoe-input" />
								</div>
								<div class="ccoe-form-group">
									<label for="contact_message"><?php esc_html_e( 'Your Message / Enquiry *', 'ccoe-core' ); ?></label>
									<textarea id="contact_message" name="contact_message" rows="5" required class="ccoe-input"></textarea>
								</div>
								<button type="submit" name="ccoe_contact_submit" value="1" class="ccoe-btn ccoe-btn-primary">
									<?php esc_html_e( 'Send Inquiry', 'ccoe-core' ); ?>
								</button>
							</form>
						</div>

						<div>
							<h2><?php esc_html_e( 'Campus Location Map (Mararaba-Karu, Nasarawa State)', 'ccoe-core' ); ?></h2>
							<div class="ccoe-map-frame">
								<iframe
									title="<?php esc_attr_e( 'City College of Education Mararaba Google Map', 'ccoe-core' ); ?>"
									src="<?php echo esc_url( $s['google_maps_embed'] ); ?>"
									width="100%"
									height="420"
									style="border:0;border-radius:12px;"
									allowfullscreen=""
									loading="lazy"
									referrerpolicy="no-referrer-when-downgrade">
								</iframe>
							</div>
							<div style="margin-top:12px;">
								<a href="https://maps.app.goo.gl/3ed1NLUWMCdouesD6" target="_blank" rel="noopener noreferrer" class="ccoe-btn ccoe-btn-outline ccoe-btn-sm">
									<?php echo self::svg_icon( 'map-pin', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<?php esc_html_e( 'Open Exact Location in Google Maps', 'ccoe-core' ); ?> &nearr;
								</a>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * 10. APPLY FOR PROVISIONAL ADMISSION PAGE ([ccoe_admission_form])
	 */
	public static function render_admission_form() {
		$s               = CCOE_Settings::get_settings();
		$result          = CCOE_Admissions::get_submission_result();
		$states_map      = CCOE_Data::get_nigerian_states_and_lgas();
		$courses_by_prog = CCOE_Data::get_courses_by_programme_type();
		$old             = ( is_array( $result ) && isset( $result['old'] ) ) ? $result['old'] : array();

		ob_start();
		?>
		<div class="ccoe-wrapper ccoe-page-apply">
			<div class="ccoe-page-banner" style="background-image: linear-gradient(120deg, rgba(0, 35, 85, 0.94), rgba(0, 122, 51, 0.84)), url('<?php echo self::img_url( 'campus-main-gate.jpg' ); ?>');">
				<div class="ccoe-container">
					<span class="ccoe-eyebrow ccoe-eyebrow-gold"><?php echo esc_html( $s['academic_session'] . ' Provisional Admission Portal' ); ?></span>
					<h1><?php esc_html_e( 'Admissions & Provisional Application', 'ccoe-core' ); ?></h1>
					<p><?php echo esc_html( $s['institution_name'] . ' — "' . $s['motto'] . '"' ); ?></p>
				</div>
			</div>

			<section class="ccoe-section ccoe-bg-white" style="padding-bottom:32px;">
				<div class="ccoe-container">
					<div class="ccoe-section-header ccoe-text-center">
						<span class="ccoe-eyebrow"><?php esc_html_e( 'How to Apply', 'ccoe-core' ); ?></span>
						<h2 class="ccoe-section-title"><?php esc_html_e( '4 Simple Steps to Provisional Admission', 'ccoe-core' ); ?></h2>
					</div>
					<div class="ccoe-steps-grid">
						<div class="ccoe-step-card">
							<div class="ccoe-step-num">01</div>
							<h3><?php esc_html_e( 'Select Programme', 'ccoe-core' ); ?></h3>
							<p><?php esc_html_e( 'Choose from NCE (NCCE 2026), AAUA Degree, PGDE, PDE, or Skills Acquisition.', 'ccoe-core' ); ?></p>
						</div>
						<div class="ccoe-step-card">
							<div class="ccoe-step-num">02</div>
							<h3><?php esc_html_e( 'Complete Bio-Data', 'ccoe-core' ); ?></h3>
							<p><?php esc_html_e( 'Fill in your personal, contact, academic, and Next of Kin details below.', 'ccoe-core' ); ?></p>
						</div>
						<div class="ccoe-step-card">
							<div class="ccoe-step-num">03</div>
							<h3><?php esc_html_e( 'Upload Credentials', 'ccoe-core' ); ?></h3>
							<p><?php esc_html_e( 'Attach a recent passport photograph (JPG/PNG) and your academic result/credentials.', 'ccoe-core' ); ?></p>
						</div>
						<div class="ccoe-step-card">
							<div class="ccoe-step-num">04</div>
							<h3><?php esc_html_e( 'Reference Slip', 'ccoe-core' ); ?></h3>
							<p><?php esc_html_e( 'Receive your unique Application Reference Number immediately and print your slip.', 'ccoe-core' ); ?></p>
						</div>
					</div>
				</div>
			</section>

			<section class="ccoe-section ccoe-bg-light">
				<div class="ccoe-container">
					<?php if ( ! empty( $result ) && ! empty( $result['success'] ) ) : ?>
						<!-- APPLICATION SUCCESS PAGE / ACKNOWLEDGEMENT SLIP -->
						<?php $app = $result['applicant']; ?>
						<div class="ccoe-success-slip" id="ccoe-printable-slip">
							<div class="ccoe-slip-header">
								<img src="<?php echo esc_url( $s['logo_url'] ); ?>" alt="<?php esc_attr_e( 'CCoE Official Logo', 'ccoe-core' ); ?>" class="ccoe-slip-logo" />
								<div>
									<h2><?php echo esc_html( strtoupper( $s['institution_name'] ) ); ?></h2>
									<p><?php echo esc_html( $s['address'] ); ?></p>
									<span class="ccoe-badge ccoe-badge-green"><?php esc_html_e( 'PROVISIONAL ADMISSION APPLICATION ACKNOWLEDGEMENT SLIP', 'ccoe-core' ); ?></span>
								</div>
							</div>

							<div class="ccoe-alert ccoe-alert-success">
								<strong><?php esc_html_e( 'Application Submitted Successfully!', 'ccoe-core' ); ?></strong>
								<?php esc_html_e( 'An acknowledgement email has been sent to your email address and the Admissions Office has been notified.', 'ccoe-core' ); ?>
							</div>

							<div class="ccoe-ref-highlight">
								<span><?php esc_html_e( 'YOUR UNIQUE APPLICATION REFERENCE NUMBER:', 'ccoe-core' ); ?></span>
								<strong><?php echo esc_html( $result['reference_number'] ); ?></strong>
								<small><?php esc_html_e( 'Please print or save this Reference Number for physical screening and admission verification.', 'ccoe-core' ); ?></small>
							</div>

							<table class="ccoe-table">
								<tbody>
									<tr>
										<th><?php esc_html_e( 'Applicant Full Name', 'ccoe-core' ); ?></th>
										<td><strong><?php echo esc_html( trim( $app['first_name'] . ' ' . $app['middle_name'] . ' ' . $app['surname'] ) ); ?></strong></td>
										<th><?php esc_html_e( 'Date Submitted', 'ccoe-core' ); ?></th>
										<td><?php echo esc_html( $result['submitted_at'] ); ?></td>
									</tr>
									<tr>
										<th><?php esc_html_e( 'Date of Birth / Gender', 'ccoe-core' ); ?></th>
										<td><?php echo esc_html( $app['dob'] . ' (' . $app['gender'] . ')' ); ?></td>
										<th><?php esc_html_e( 'State of Origin / LGA', 'ccoe-core' ); ?></th>
										<td><?php echo esc_html( $app['state_of_origin'] . ' / ' . $app['lga'] ); ?></td>
									</tr>
									<tr>
										<th><?php esc_html_e( 'Phone / Email', 'ccoe-core' ); ?></th>
										<td><?php echo esc_html( $app['phone'] . ' | ' . $app['email'] ); ?></td>
										<th><?php esc_html_e( 'Highest Qualification', 'ccoe-core' ); ?></th>
										<td><?php echo esc_html( $app['highest_qualification'] . ' (' . $app['exam_type'] . ' ' . $app['exam_year'] . ')' ); ?></td>
									</tr>
									<tr>
										<th><?php esc_html_e( 'Programme of Interest', 'ccoe-core' ); ?></th>
										<td><strong><?php echo esc_html( $app['programme_interest'] ); ?></strong></td>
										<th><?php esc_html_e( 'Preferred Course of Study', 'ccoe-core' ); ?></th>
										<td><strong><?php echo esc_html( $app['preferred_course'] ); ?></strong></td>
									</tr>
								</tbody>
							</table>

							<div class="ccoe-slip-actions">
								<button type="button" onclick="window.print();" class="ccoe-btn ccoe-btn-primary">
									<?php echo self::svg_icon( 'printer', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e( 'Print Acknowledgement Slip', 'ccoe-core' ); ?>
								</button>
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ccoe-btn ccoe-btn-outline">
									<?php esc_html_e( 'Return to Homepage', 'ccoe-core' ); ?>
								</a>
							</div>
						</div>
					<?php else : ?>
						<!-- APPLICATION FORM -->
						<div class="ccoe-form-card ccoe-admission-form-container">
							<div class="ccoe-form-intro">
								<h2><?php esc_html_e( 'Provisional Admission Application Form', 'ccoe-core' ); ?></h2>
								<p><?php esc_html_e( 'Complete all mandatory fields (*) accurately. Uploaded documents are encrypted and stored securely in compliance with institutional data protection policies.', 'ccoe-core' ); ?></p>
							</div>

							<?php if ( ! empty( $result['errors'] ) ) : ?>
								<div class="ccoe-alert ccoe-alert-error" role="alert">
									<strong><?php esc_html_e( 'Please correct the following errors before submitting:', 'ccoe-core' ); ?></strong>
									<ul>
										<?php foreach ( $result['errors'] as $err ) : ?>
											<li><?php echo esc_html( $err ); ?></li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>

							<form method="post" enctype="multipart/form-data" class="ccoe-form" id="ccoe-provisional-admission-form" novalidate>
								<?php wp_nonce_field( 'ccoe_submit_admission', 'ccoe_admission_nonce' ); ?>
								<input type="text" name="ccoe_website_hp" value="" style="display:none !important;" tabindex="-1" autocomplete="off" />

								<!-- SECTION 1: PERSONAL DETAILS -->
								<fieldset class="ccoe-fieldset">
									<legend><?php esc_html_e( 'Section A: Personal Details', 'ccoe-core' ); ?></legend>
									<div class="ccoe-form-row-3">
										<div class="ccoe-form-group">
											<label for="first_name"><?php esc_html_e( 'First Name *', 'ccoe-core' ); ?></label>
											<input type="text" id="first_name" name="first_name" required class="ccoe-input" value="<?php echo esc_attr( $old['first_name'] ?? '' ); ?>" placeholder="Enter First Name" />
										</div>
										<div class="ccoe-form-group">
											<label for="middle_name"><?php esc_html_e( 'Middle Name', 'ccoe-core' ); ?></label>
											<input type="text" id="middle_name" name="middle_name" class="ccoe-input" value="<?php echo esc_attr( $old['middle_name'] ?? '' ); ?>" placeholder="Enter Middle Name (Optional)" />
										</div>
										<div class="ccoe-form-group">
											<label for="surname"><?php esc_html_e( 'Surname *', 'ccoe-core' ); ?></label>
											<input type="text" id="surname" name="surname" required class="ccoe-input" value="<?php echo esc_attr( $old['surname'] ?? '' ); ?>" placeholder="Enter Surname" />
										</div>
									</div>

									<div class="ccoe-form-row-3">
										<div class="ccoe-form-group">
											<label for="dob"><?php esc_html_e( 'Date of Birth *', 'ccoe-core' ); ?></label>
											<input type="date" id="dob" name="dob" required class="ccoe-input" value="<?php echo esc_attr( $old['dob'] ?? '' ); ?>" />
										</div>
										<div class="ccoe-form-group">
											<label for="gender"><?php esc_html_e( 'Gender *', 'ccoe-core' ); ?></label>
											<select id="gender" name="gender" required class="ccoe-input">
												<option value=""><?php esc_html_e( '— Select Gender —', 'ccoe-core' ); ?></option>
												<option value="Male" <?php selected( $old['gender'] ?? '', 'Male' ); ?>><?php esc_html_e( 'Male', 'ccoe-core' ); ?></option>
												<option value="Female" <?php selected( $old['gender'] ?? '', 'Female' ); ?>><?php esc_html_e( 'Female', 'ccoe-core' ); ?></option>
											</select>
										</div>
										<div class="ccoe-form-group">
											<label for="nationality"><?php esc_html_e( 'Nationality *', 'ccoe-core' ); ?></label>
											<input type="text" id="nationality" name="nationality" required class="ccoe-input" value="<?php echo esc_attr( $old['nationality'] ?? 'Nigerian' ); ?>" />
										</div>
									</div>

									<div class="ccoe-form-row-2">
										<div class="ccoe-form-group">
											<label for="state_of_origin"><?php esc_html_e( 'State of Origin *', 'ccoe-core' ); ?></label>
											<select id="state_of_origin" name="state_of_origin" required class="ccoe-input">
												<option value=""><?php esc_html_e( '— Select State of Origin —', 'ccoe-core' ); ?></option>
												<?php foreach ( array_keys( $states_map ) as $state_name ) : ?>
													<option value="<?php echo esc_attr( $state_name ); ?>" <?php selected( $old['state_of_origin'] ?? '', $state_name ); ?>><?php echo esc_html( $state_name ); ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="ccoe-form-group">
											<label for="lga"><?php esc_html_e( 'Local Government Area (LGA) *', 'ccoe-core' ); ?></label>
											<input type="text" id="lga" name="lga" required class="ccoe-input" list="ccoe-lga-datalist" value="<?php echo esc_attr( $old['lga'] ?? '' ); ?>" placeholder="Enter or select your LGA" />
											<datalist id="ccoe-lga-datalist"></datalist>
										</div>
									</div>

									<div class="ccoe-form-group">
										<label for="residential_address"><?php esc_html_e( 'Residential Address *', 'ccoe-core' ); ?></label>
										<textarea id="residential_address" name="residential_address" rows="2" required class="ccoe-input" placeholder="Enter your full current residential address"><?php echo esc_textarea( $old['residential_address'] ?? '' ); ?></textarea>
									</div>
								</fieldset>

								<!-- SECTION 2: CONTACT DETAILS -->
								<fieldset class="ccoe-fieldset">
									<legend><?php esc_html_e( 'Section B: Contact Details', 'ccoe-core' ); ?></legend>
									<div class="ccoe-form-row-2">
										<div class="ccoe-form-group">
											<label for="phone"><?php esc_html_e( 'Phone Number (Active WhatsApp / GSM) *', 'ccoe-core' ); ?></label>
											<input type="tel" id="phone" name="phone" required class="ccoe-input" value="<?php echo esc_attr( $old['phone'] ?? '' ); ?>" placeholder="e.g. 0803 000 0000" />
										</div>
										<div class="ccoe-form-group">
											<label for="email"><?php esc_html_e( 'Email Address *', 'ccoe-core' ); ?></label>
											<input type="email" id="email" name="email" required class="ccoe-input" value="<?php echo esc_attr( $old['email'] ?? '' ); ?>" placeholder="applicant@example.com" />
										</div>
									</div>
								</fieldset>

								<!-- SECTION 3: ACADEMIC DETAILS -->
								<fieldset class="ccoe-fieldset">
									<legend><?php esc_html_e( 'Section C: Academic Details & Programme Selection', 'ccoe-core' ); ?></legend>
									<div class="ccoe-form-row-2">
										<div class="ccoe-form-group">
											<label for="programme_interest"><?php esc_html_e( 'Programme of Interest *', 'ccoe-core' ); ?></label>
											<select id="programme_interest" name="programme_interest" required class="ccoe-input">
												<option value=""><?php esc_html_e( '— Select Programme Level —', 'ccoe-core' ); ?></option>
												<?php foreach ( array_keys( $courses_by_prog ) as $prog_type ) : ?>
													<option value="<?php echo esc_attr( $prog_type ); ?>" <?php selected( $old['programme_interest'] ?? '', $prog_type ); ?>><?php echo esc_html( $prog_type ); ?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="ccoe-form-group">
											<label for="preferred_course"><?php esc_html_e( 'Preferred Course of Study *', 'ccoe-core' ); ?></label>
											<select id="preferred_course" name="preferred_course" required class="ccoe-input">
												<option value=""><?php esc_html_e( '— Select Preferred Course of Study —', 'ccoe-core' ); ?></option>
												<?php
												foreach ( $courses_by_prog as $p_label => $course_items ) :
													?>
													<optgroup label="<?php echo esc_attr( $p_label ); ?>">
														<?php foreach ( $course_items as $c_name ) : ?>
															<option value="<?php echo esc_attr( $c_name ); ?>" <?php selected( $old['preferred_course'] ?? '', $c_name ); ?>><?php echo esc_html( $c_name ); ?></option>
														<?php endforeach; ?>
													</optgroup>
												<?php endforeach; ?>
											</select>
										</div>
									</div>

									<div class="ccoe-form-row-2">
										<div class="ccoe-form-group">
											<label for="highest_qualification"><?php esc_html_e( 'Highest Educational Qualification *', 'ccoe-core' ); ?></label>
											<select id="highest_qualification" name="highest_qualification" required class="ccoe-input">
												<option value=""><?php esc_html_e( '— Select Highest Qualification —', 'ccoe-core' ); ?></option>
												<option value="SSCE / O-Level" <?php selected( $old['highest_qualification'] ?? '', 'SSCE / O-Level' ); ?>><?php esc_html_e( 'SSCE / Senior Secondary Certificate (O\'Level)', 'ccoe-core' ); ?></option>
												<option value="NCE / National Diploma (ND)" <?php selected( $old['highest_qualification'] ?? '', 'NCE / National Diploma (ND)' ); ?>><?php esc_html_e( 'NCE / National Diploma (ND)', 'ccoe-core' ); ?></option>
												<option value="Higher National Diploma (HND)" <?php selected( $old['highest_qualification'] ?? '', 'Higher National Diploma (HND)' ); ?>><?php esc_html_e( 'Higher National Diploma (HND)', 'ccoe-core' ); ?></option>
												<option value="Bachelor's Degree (B.A / B.Sc / B.Ed)" <?php selected( $old['highest_qualification'] ?? '', "Bachelor's Degree (B.A / B.Sc / B.Ed)" ); ?>><?php esc_html_e( 'Bachelor\'s Degree (B.A. / B.Sc. / B.Ed.)', 'ccoe-core' ); ?></option>
												<option value="Master's Degree / Ph.D" <?php selected( $old['highest_qualification'] ?? '', "Master's Degree / Ph.D" ); ?>><?php esc_html_e( 'Master\'s Degree / Ph.D.', 'ccoe-core' ); ?></option>
											</select>
										</div>
										<div class="ccoe-form-group">
											<label for="exam_type"><?php esc_html_e( 'Examination Type *', 'ccoe-core' ); ?></label>
											<select id="exam_type" name="exam_type" required class="ccoe-input">
												<option value=""><?php esc_html_e( '— Select Examination Type —', 'ccoe-core' ); ?></option>
												<option value="WAEC (WASSCE)" <?php selected( $old['exam_type'] ?? '', 'WAEC (WASSCE)' ); ?>>WAEC (WASSCE)</option>
												<option value="NECO (SSCE)" <?php selected( $old['exam_type'] ?? '', 'NECO (SSCE)' ); ?>>NECO (SSCE)</option>
												<option value="NABTEB" <?php selected( $old['exam_type'] ?? '', 'NABTEB' ); ?>>NABTEB</option>
												<option value="GCE" <?php selected( $old['exam_type'] ?? '', 'GCE' ); ?>>GCE</option>
												<option value="JAMB UTME / Direct Entry" <?php selected( $old['exam_type'] ?? '', 'JAMB UTME / Direct Entry' ); ?>>JAMB UTME / Direct Entry</option>
												<option value="Degree / HND / NCE Certificate" <?php selected( $old['exam_type'] ?? '', 'Degree / HND / NCE Certificate' ); ?>>Degree / HND / NCE Certificate</option>
											</select>
										</div>
									</div>

									<div class="ccoe-form-row-2">
										<div class="ccoe-form-group">
											<label for="exam_year"><?php esc_html_e( 'Examination / Graduation Year *', 'ccoe-core' ); ?></label>
											<input type="text" id="exam_year" name="exam_year" required class="ccoe-input" value="<?php echo esc_attr( $old['exam_year'] ?? '' ); ?>" placeholder="e.g. 2024" />
										</div>
										<div class="ccoe-form-group">
											<label for="exam_number"><?php esc_html_e( 'Examination / Registration Number *', 'ccoe-core' ); ?></label>
											<input type="text" id="exam_number" name="exam_number" required class="ccoe-input" value="<?php echo esc_attr( $old['exam_number'] ?? '' ); ?>" placeholder="Enter Exam or Registration Number" />
										</div>
									</div>
								</fieldset>

								<!-- SECTION 4: NEXT OF KIN -->
								<fieldset class="ccoe-fieldset">
									<legend><?php esc_html_e( 'Section D: Next of Kin Details', 'ccoe-core' ); ?></legend>
									<div class="ccoe-form-row-3">
										<div class="ccoe-form-group">
											<label for="nok_name"><?php esc_html_e( 'Next of Kin Full Name *', 'ccoe-core' ); ?></label>
											<input type="text" id="nok_name" name="nok_name" required class="ccoe-input" value="<?php echo esc_attr( $old['nok_name'] ?? '' ); ?>" placeholder="Full Name of Next of Kin" />
										</div>
										<div class="ccoe-form-group">
											<label for="nok_phone"><?php esc_html_e( 'Next of Kin Phone Number *', 'ccoe-core' ); ?></label>
											<input type="tel" id="nok_phone" name="nok_phone" required class="ccoe-input" value="<?php echo esc_attr( $old['nok_phone'] ?? '' ); ?>" placeholder="e.g. 0803 000 0000" />
										</div>
										<div class="ccoe-form-group">
											<label for="nok_relationship"><?php esc_html_e( 'Relationship *', 'ccoe-core' ); ?></label>
											<select id="nok_relationship" name="nok_relationship" required class="ccoe-input">
												<option value=""><?php esc_html_e( '— Select Relationship —', 'ccoe-core' ); ?></option>
												<option value="Parent (Father / Mother)" <?php selected( $old['nok_relationship'] ?? '', 'Parent (Father / Mother)' ); ?>><?php esc_html_e( 'Parent (Father / Mother)', 'ccoe-core' ); ?></option>
												<option value="Guardian" <?php selected( $old['nok_relationship'] ?? '', 'Guardian' ); ?>><?php esc_html_e( 'Guardian', 'ccoe-core' ); ?></option>
												<option value="Spouse" <?php selected( $old['nok_relationship'] ?? '', 'Spouse' ); ?>><?php esc_html_e( 'Spouse', 'ccoe-core' ); ?></option>
												<option value="Sibling (Brother / Sister)" <?php selected( $old['nok_relationship'] ?? '', 'Sibling (Brother / Sister)' ); ?>><?php esc_html_e( 'Sibling (Brother / Sister)', 'ccoe-core' ); ?></option>
												<option value="Other Relative" <?php selected( $old['nok_relationship'] ?? '', 'Other Relative' ); ?>><?php esc_html_e( 'Other Relative', 'ccoe-core' ); ?></option>
											</select>
										</div>
									</div>
								</fieldset>

								<!-- SECTION 5: DOCUMENT UPLOADS -->
								<fieldset class="ccoe-fieldset">
									<legend><?php esc_html_e( 'Section E: Document Uploads', 'ccoe-core' ); ?></legend>
									<div class="ccoe-form-row-2">
										<div class="ccoe-form-group">
											<label for="passport_photo"><?php esc_html_e( 'Passport Photograph * (JPG or PNG, max 2MB)', 'ccoe-core' ); ?></label>
											<input type="file" id="passport_photo" name="passport_photo" accept=".jpg,.jpeg,.png,image/jpeg,image/png" required class="ccoe-input" />
											<small class="ccoe-help-text"><?php esc_html_e( 'Upload a clear, recent passport-sized photograph on a plain background.', 'ccoe-core' ); ?></small>
										</div>
										<div class="ccoe-form-group">
											<label for="academic_credentials"><?php esc_html_e( 'Academic Credentials, Where Applicable (PDF, JPG or PNG, max 5MB)', 'ccoe-core' ); ?></label>
											<input type="file" id="academic_credentials" name="academic_credentials" accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png" class="ccoe-input" />
											<small class="ccoe-help-text"><?php esc_html_e( 'Upload your O\'Level statement of result, NCE/ND/HND/Degree certificate, or awaiting result slip.', 'ccoe-core' ); ?></small>
										</div>
									</div>
								</fieldset>

								<!-- SECTION 6: PRIVACY NOTICE & DECLARATION -->
								<fieldset class="ccoe-fieldset">
									<legend><?php esc_html_e( 'Section F: Data Protection Notice & Applicant Declaration', 'ccoe-core' ); ?></legend>
									<div class="ccoe-privacy-box">
										<strong><?php esc_html_e( 'Privacy & Data Protection Notice (NDPR Compliant):', 'ccoe-core' ); ?></strong>
										<p><?php esc_html_e( 'City College of Education, Mararaba collects and processes the personal and academic data provided on this form strictly for admission evaluation, student registration, and regulatory reporting (NCCE / AAUA). Your personal information and uploaded documents are stored securely and are never displayed publicly or shared with unauthorized third parties.', 'ccoe-core' ); ?></p>
									</div>
									<label class="ccoe-checkbox-label">
										<input type="checkbox" name="declaration_accepted" value="1" required />
										<span><?php esc_html_e( 'I hereby declare that the information provided in this application form is true, complete, and accurate to the best of my knowledge. I understand that any false declaration or forged credential will result in automatic disqualification or withdrawal of provisional admission by City College of Education, Mararaba. *', 'ccoe-core' ); ?></span>
									</label>
								</fieldset>

								<div class="ccoe-form-submit-row">
									<button type="submit" name="ccoe_admission_submit" value="1" class="ccoe-btn ccoe-btn-gold ccoe-btn-lg">
										<?php esc_html_e( 'Submit Provisional Admission Application', 'ccoe-core' ); ?> &rarr;
									</button>
								</div>
							</form>
						</div>
					<?php endif; ?>
				</div>
			</section>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Helper: Render Major Programme Cards (Reads from `ccoe_programme` CPT if available, with fallback to CCOE_Data)
	 */
	public static function render_major_programmes_cards() {
		$apply_url   = site_url( '/apply-for-provisional-admission/' );
		$courses_url = site_url( '/courses/' );
		$progs       = CCOE_Data::get_major_programmes();

		ob_start();
		?>
		<div class="ccoe-cards-grid-3">
			<?php foreach ( $progs as $p ) : ?>
				<article class="ccoe-card ccoe-programme-card">
					<div class="ccoe-card-img-wrap">
						<img src="<?php echo self::img_url( $p['image'] ); ?>" alt="<?php echo esc_attr( $p['title'] ); ?>" loading="lazy" />
						<span class="ccoe-card-floating-badge"><?php echo esc_html( $p['badge'] ); ?></span>
					</div>
					<div class="ccoe-card-body">
						<h3><?php echo esc_html( $p['title'] ); ?></h3>
						<p><?php echo esc_html( $p['summary'] ); ?></p>
						<div class="ccoe-card-meta-list">
							<div><strong><?php esc_html_e( 'Duration:', 'ccoe-core' ); ?></strong> <?php echo esc_html( $p['duration'] ); ?></div>
							<div><strong><?php esc_html_e( 'Scope:', 'ccoe-core' ); ?></strong> <?php echo esc_html( $p['school_count'] ); ?></div>
							<div><strong><?php esc_html_e( 'Affiliation:', 'ccoe-core' ); ?></strong> <?php echo esc_html( $p['affiliation'] ); ?></div>
						</div>
					</div>
					<div class="ccoe-card-footer">
						<a href="<?php echo esc_url( $apply_url ); ?>" class="ccoe-btn ccoe-btn-primary ccoe-btn-sm"><?php esc_html_e( 'Apply Now', 'ccoe-core' ); ?></a>
						<a href="<?php echo esc_url( $courses_url ); ?>" class="ccoe-btn ccoe-btn-outline ccoe-btn-sm"><?php esc_html_e( 'View Courses', 'ccoe-core' ); ?></a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Helper: Render Dynamic Events Grid (Reads from `ccoe_event` CPT first, falls back to CCOE_Data)
	 */
	public static function render_events_grid( $limit = 4 ) {
		$query = new WP_Query(
			array(
				'post_type'      => 'ccoe_event',
				'posts_per_page' => $limit,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);

		$events = array();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id  = get_the_ID();
				$terms    = get_the_terms( $post_id, 'ccoe_event_category' );
				$cat_name = ( ! empty( $terms ) && ! is_wp_error( $terms ) ) ? $terms[0]->name : __( 'College Event', 'ccoe-core' );
				$img_file = get_post_meta( $post_id, '_ccoe_event_image_file', true );
				$events[] = array(
					'title'       => get_the_title(),
					'permalink'   => get_permalink(),
					'date'        => get_post_meta( $post_id, '_ccoe_event_date', true ) ?: get_the_date( 'Y-m-d' ),
					'time'        => get_post_meta( $post_id, '_ccoe_event_time', true ) ?: '09:00 AM',
					'venue'       => get_post_meta( $post_id, '_ccoe_event_venue', true ) ?: 'Main Campus, Mararaba',
					'category'    => $cat_name,
					'image_url'   => has_post_thumbnail() ? get_the_post_thumbnail_url( $post_id, 'large' ) : self::img_url( $img_file ?: 'campus-main-gate.jpg' ),
					'description' => get_the_excerpt() ?: wp_trim_words( get_the_content(), 26 ),
				);
			}
			wp_reset_postdata();
		} else {
			foreach ( array_slice( CCOE_Data::get_default_events(), 0, $limit ) as $ev ) {
				$ev['permalink'] = site_url( '/news-and-events/' );
				$ev['image_url'] = self::img_url( $ev['image'] );
				$events[]        = $ev;
			}
		}

		ob_start();
		?>
		<div class="ccoe-cards-grid-2">
			<?php foreach ( $events as $ev ) : ?>
				<article class="ccoe-card ccoe-event-card">
					<div class="ccoe-event-img-wrap">
						<img src="<?php echo esc_url( $ev['image_url'] ); ?>" alt="<?php echo esc_attr( $ev['title'] ); ?>" loading="lazy" />
						<span class="ccoe-badge ccoe-badge-gold"><?php echo esc_html( $ev['category'] ); ?></span>
					</div>
					<div class="ccoe-card-body">
						<div class="ccoe-event-meta">
							<span><?php echo self::svg_icon( 'calendar', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( date_i18n( 'M j, Y', strtotime( $ev['date'] ) ) ); ?></span>
							<span><?php echo self::svg_icon( 'clock', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( $ev['time'] ); ?></span>
							<span><?php echo self::svg_icon( 'map-pin', 15 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( $ev['venue'] ); ?></span>
						</div>
						<h3><a href="<?php echo esc_url( $ev['permalink'] ); ?>"><?php echo esc_html( $ev['title'] ); ?></a></h3>
						<p><?php echo esc_html( $ev['description'] ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}
