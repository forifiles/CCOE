<?php
/**
 * City College of Education, Mararaba — Theme Branding & Elementor Integration
 *
 * @package Education_Zone
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add Elementor & Wide Alignment Support
 */
function ccoe_education_zone_theme_support() {
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'elementor' );
}
add_action( 'after_setup_theme', 'ccoe_education_zone_theme_support', 20 );

/**
 * Pluggable Override: Site Branding with Official CCoE Crest Logo Fallback
 */
if ( ! function_exists( 'education_zone_site_branding' ) ) :
	function education_zone_site_branding() {
		$site_title       = get_bloginfo( 'name' ) ?: 'City College of Education, Mararaba';
		$site_description = get_bloginfo( 'description', 'display' ) ?: 'Education for Self Employment';
		$logo_url         = get_template_directory_uri() . '/images/ccoe/ccoe-logo.png';
		?>
		<div class="site-branding" itemscope itemtype="https://schema.org/CollegeOrUniversity" style="display:flex;align-items:center;gap:14px;">
			<?php
			if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
				the_custom_logo();
			} else {
				?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link" rel="home">
					<img src="<?php echo esc_url( $logo_url ); ?>" class="custom-logo" alt="<?php echo esc_attr( $site_title ); ?>" style="max-height:70px;width:auto;background:#fff;padding:4px;border-radius:8px;" />
				</a>
				<?php
			}
			?>
			<div class="site-branding-wrapper">
				<?php if ( is_front_page() ) : ?>
					<h1 class="site-title" itemprop="name" style="margin:0;font-size:1.35rem;font-weight:700;">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url" style="color:#ffffff;text-decoration:none;"><?php echo esc_html( $site_title ); ?></a>
					</h1>
				<?php else : ?>
					<p class="site-title" itemprop="name" style="margin:0;font-size:1.35rem;font-weight:700;">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url" style="color:#ffffff;text-decoration:none;"><?php echo esc_html( $site_title ); ?></a>
					</p>
				<?php endif; ?>
				<p class="site-description" itemprop="description" style="margin:2px 0 0;color:#F5B800;font-size:0.86rem;font-weight:600;">
					<?php echo esc_html( $site_description ); ?>
				</p>
			</div>
		</div>
		<?php
	}
endif;

/**
 * Pluggable Override: Institutional Footer for City College of Education
 */
if ( ! function_exists( 'education_zone_footer_bottom' ) ) :
	function education_zone_footer_bottom() {
		$address = 'No. 1 City College Road, Off Old Karu Road, Sani Abacha Road Estate, Mararaba-Karu, Nasarawa State';
		$phones  = '08032920100 | 08058431596';
		$email   = 'info@citycollegeofeducation.edu.ng';
		$logo    = get_template_directory_uri() . '/images/ccoe/ccoe-logo.png';
		?>
		<div class="ccoe-footer-institutional" style="padding:48px 0 28px;border-bottom:1px solid rgba(255,255,255,0.15);margin-bottom:20px;">
			<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:32px;text-align:left;">
				<div>
					<div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
						<img src="<?php echo esc_url( $logo ); ?>" alt="CCoE Logo" style="width:62px;height:auto;background:#fff;padding:4px;border-radius:6px;" />
						<div>
							<strong style="color:#fff;font-size:1.05rem;display:block;">City College of Education</strong>
							<span style="color:#F5B800;font-size:0.82rem;">Mararaba, Nasarawa State</span>
						</div>
					</div>
					<p style="font-size:0.9rem;color:#CBD5E1;line-height:1.6;">
						"Education for Self Employment" — Accredited NCE Programmes (New NCCE Minimum Standard 2026), B.Ed./B.Sc. (Ed.) Degree &amp; PGDE in Affiliation with Adekunle Ajasin University, Akungba-Akoko (AAUA).
					</p>
				</div>

				<div>
					<h3 style="color:#F5B800;font-size:1rem;margin-bottom:14px;">Quick Navigation</h3>
					<ul style="list-style:none;padding:0;margin:0;line-height:2;font-size:0.92rem;">
						<li><a href="<?php echo esc_url( site_url( '/about-us/' ) ); ?>" style="color:#E2E8F0;text-decoration:none;">About the College</a></li>
						<li><a href="<?php echo esc_url( site_url( '/our-vision-and-mission/' ) ); ?>" style="color:#E2E8F0;text-decoration:none;">Our Vision and Mission</a></li>
						<li><a href="<?php echo esc_url( site_url( '/message-from-the-provost/' ) ); ?>" style="color:#E2E8F0;text-decoration:none;">Message from the Provost</a></li>
						<li><a href="<?php echo esc_url( site_url( '/academic-programmes/' ) ); ?>" style="color:#E2E8F0;text-decoration:none;">Academic Programmes</a></li>
						<li><a href="<?php echo esc_url( site_url( '/courses/' ) ); ?>" style="color:#E2E8F0;text-decoration:none;">Courses Catalog</a></li>
					</ul>
				</div>

				<div>
					<h3 style="color:#F5B800;font-size:1rem;margin-bottom:14px;">Admissions &amp; Portals</h3>
					<ul style="list-style:none;padding:0;margin:0;line-height:2;font-size:0.92rem;">
						<li><a href="<?php echo esc_url( site_url( '/apply-for-provisional-admission/' ) ); ?>" style="color:#F5B800;font-weight:700;text-decoration:none;">Apply for Provisional Admission &rarr;</a></li>
						<li><a href="<?php echo esc_url( site_url( '/admissions/' ) ); ?>" style="color:#E2E8F0;text-decoration:none;">Admission Requirements</a></li>
						<li><a href="<?php echo esc_url( site_url( '/news-and-events/' ) ); ?>" style="color:#E2E8F0;text-decoration:none;">News and Events</a></li>
						<li><a href="<?php echo esc_url( site_url( '/contact-us/' ) ); ?>" style="color:#E2E8F0;text-decoration:none;">Contact Us &amp; Campus Map</a></li>
						<li><a href="<?php echo esc_url( admin_url() ); ?>" style="color:#E2E8F0;text-decoration:none;">Staff / Admin Portal</a></li>
					</ul>
				</div>

				<div>
					<h3 style="color:#F5B800;font-size:1rem;margin-bottom:14px;">Official Contact Details</h3>
					<p style="font-size:0.9rem;color:#CBD5E1;margin:0 0 10px;">
						<strong>Campus Address:</strong><br><?php echo esc_html( $address ); ?>
					</p>
					<p style="font-size:0.9rem;color:#CBD5E1;margin:0 0 10px;">
						<strong>Telephone:</strong><br><?php echo esc_html( $phones ); ?>
					</p>
					<p style="font-size:0.9rem;color:#CBD5E1;margin:0;">
						<strong>Email:</strong><br><?php echo esc_html( $email ); ?>
					</p>
				</div>
			</div>
		</div>
		<div class="site-info" style="text-align:center;padding-bottom:16px;">
			<p style="margin:0;font-size:0.88rem;color:#94A3B8;">
				Copyright &copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <strong>City College of Education, Mararaba, Nasarawa State</strong>. All Rights Reserved.
			</p>
		</div>
		<?php
	}
endif;
