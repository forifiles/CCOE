<?php
/**
 * Authoritative Institutional Data for City College of Education, Mararaba
 * Sourced from official CCoE documentation & New NCCE Minimum Standard (2026).
 *
 * @package CCOE_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CCOE_Data {

	/**
	 * Get default institutional settings
	 */
	public static function get_default_settings() {
		$theme_img_base = get_template_directory_uri() . '/images/ccoe/';
		return array(
			'institution_name'     => 'City College of Education, Mararaba',
			'short_name'           => 'CCoE Mararaba',
			'motto'                => 'Education for Self Employment',
			'address'              => 'No. 1 City College Road, Off Old Karu Road, Sani Abacha Road Estate, Mararaba-Karu, Nasarawa State',
			'postal_address'       => 'Sani Abacha Road, Mararaba Gurku, P.M.B 3094 Garki, Abuja',
			'phone_primary'        => '08032920100',
			'phone_secondary'      => '08058431596',
			'email_official'       => 'info@citycollegeofeducation.edu.ng',
			'email_admissions'     => 'admissions@citycollegeofeducation.edu.ng',
			'academic_session'     => '2026/2027',
			'admissions_open'      => 'yes',
			'vision_statement'     => 'To be a distinctive quality based educational institution, making the difference in the Nation history through positive impact of its services and its graduate output.',
			'mission_statement'    => 'To establish and maintain a most suitable academic environment synergizing world class human capital best technology for creating and impacting knowledge to develop and produce superior entrepreneurial teachers for the Nigeria society.',
			'about_background'     => 'City College of Education, Mararaba (CCoE) is a premier tertiary teacher-education institution strategically located at No. 1 City College Road, Off Old Karu Road, Sani Abacha Road Estate, Mararaba-Karu, Nasarawa State (along the greater Abuja-Keffi corridor). Regulated in line with the National Commission for Colleges of Education (NCCE) Minimum Standards (2026) and affiliated with Adekunle Ajasin University, Akungba-Akoko (AAUA), Ondo State for its Undergraduate Degree and Postgraduate Diploma in Education programmes, the College combines rigorous academic scholarship with practical entrepreneurship and modern computing education.',
			'about_history'        => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Established to bridge the gap between theoretical teacher training and practical self-reliance, City College of Education, Mararaba has grown into a respected centre of academic excellence serving Nasarawa State, the Federal Capital Territory (Abuja), and the wider Nigerian federation. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante.',
			'about_philosophy'     => 'Guided by our motto — "Education for Self Employment" — our institutional philosophy holds that every graduate educator must be intellectually grounded, pedagogically versatile, technologically fluent, and entrepreneurially self-reliant. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
			'provost_name'         => '[Provost Full Name & Academic Titles — Placeholder]',
			'provost_designation'  => 'Provost, City College of Education, Mararaba',
			'provost_photo'        => $theme_img_base . 'campus-courtyard-statue.jpg',
			'provost_welcome'      => 'Welcome to City College of Education, Mararaba, Nasarawa State — a citadel of teacher education, innovation, and entrepreneurial excellence. At CCoE, we are committed to nurturing a new generation of educators who do not merely transmit knowledge, but inspire transformation in classrooms, communities, and enterprises across Nigeria.',
			'provost_statement'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In alignment with the New NCCE Minimum Standard (2026) and our strategic affiliation with Adekunle Ajasin University, Akungba-Akoko (AAUA), Ondo State, we have expanded our academic offerings to include cutting-edge programmes in Artificial Intelligence and Robotics Education, Data Science, Cybersecurity, and Entrepreneurial Studies alongside foundational Sciences, Arts, and Social Sciences. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
			'google_maps_embed'    => 'https://www.google.com/maps?q=9.0064714,7.5822794&z=18&output=embed',
			'facebook_url'         => 'https://facebook.com/',
			'twitter_url'          => 'https://x.com/',
			'instagram_url'        => 'https://instagram.com/',
			'linkedin_url'         => 'https://linkedin.com/',
			'logo_url'             => $theme_img_base . 'ccoe-logo.png',
			'hero_image_url'       => $theme_img_base . 'campus-main-gate.jpg',
		);
	}

	/**
	 * Core Values of City College of Education
	 */
	public static function get_core_values() {
		return array(
			array(
				'title'       => 'Academic Excellence',
				'icon'        => 'graduation-cap',
				'description' => 'Upholding the highest standards of scholarship, curriculum delivery, and educational research in full compliance with NCCE 2026 and NUC benchmarks.',
			),
			array(
				'title'       => 'Entrepreneurial Self-Reliance',
				'icon'        => 'briefcase',
				'description' => 'Empowering every student with practical vocational and digital competencies in line with our motto: Education for Self Employment.',
			),
			array(
				'title'       => 'Technological Innovation',
				'icon'        => 'cpu',
				'description' => 'Synergizing world-class human capital with modern computing, AI, robotics, and educational technology.',
			),
			array(
				'title'       => 'Integrity & Discipline',
				'icon'        => 'shield-check',
				'description' => 'Fostering moral rectitude, professional ethics, accountability, and civic responsibility in all trainees and staff.',
			),
			array(
				'title'       => 'Inclusiveness & Service',
				'icon'        => 'users',
				'description' => 'Providing an accessible, supportive, and serene learning environment that serves diverse communities across Nigeria.',
			),
		);
	}

	/**
	 * Major Programme Categories offered by CCoE
	 */
	public static function get_major_programmes() {
		return array(
			array(
				'slug'         => 'nce-programmes',
				'title'        => 'Nigeria Certificate in Education (NCE)',
				'badge'        => 'NCCE 2026 Minimum Standard',
				'duration'     => '3 Years (6 Semesters)',
				'affiliation'  => 'Accredited by National Commission for Colleges of Education (NCCE)',
				'summary'      => 'Full-time NCE teacher education programmes structured across seven (7) specialized Schools in line with the New NCCE Minimum Standard (2026), combining subject mastery, pedagogy, and entrepreneurship.',
				'requirements' => 'Minimum of 4–5 O\'Level credits (WAEC, NECO, NABTEB, or GCE) including English Language and Mathematics in not more than two sittings, plus acceptable JAMB UTME score.',
				'school_count' => '7 Schools • 28 Programmes',
				'image'        => 'academic-block.jpg',
			),
			array(
				'slug'         => 'degree-aaua',
				'title'        => 'B.Ed. / B.Sc. (Ed.) Degree Programmes',
				'badge'        => 'Affiliated with AAUA',
				'duration'     => '4 Years (UTME) / 3 Years (Direct Entry)',
				'affiliation'  => 'In Affiliation with Adekunle Ajasin University, Akungba-Akoko (AAUA), Ondo State',
				'summary'      => 'Bachelor of Education (B.Ed.) and Bachelor of Science Education (B.Sc. Ed.) degree programmes awarded by Adekunle Ajasin University, Akungba-Akoko, Ondo State and delivered at City College of Education, Mararaba.',
				'requirements' => 'Five (5) O\'Level credit passes including English Language, Mathematics, and three relevant subjects. Direct Entry candidates require a minimum of Merit pass at NCE or National Diploma.',
				'school_count' => '5 Departments • 10 Degree Programmes',
				'image'        => 'lecture-walkway.jpg',
			),
			array(
				'slug'         => 'pgde-aaua',
				'title'        => 'Postgraduate Diploma in Education (PGDE)',
				'badge'        => 'Affiliated with AAUA',
				'duration'     => '1 Academic Session (2 Semesters • 40 Credit Units)',
				'affiliation'  => 'In Affiliation with Adekunle Ajasin University, Akungba-Akoko (AAUA), Ondo State',
				'summary'      => 'Intensive postgraduate professional teacher qualification for HND, Bachelor\'s, Master\'s, and Doctorate holders seeking professional pedagogical certification and TRCN eligibility.',
				'requirements' => 'Minimum of Higher National Diploma (HND) or Bachelor\'s Degree (B.A., B.Sc., B.Tech., etc.) from a recognized tertiary institution, plus NYSC discharge/exemption certificate.',
				'school_count' => '20 Compulsory Courses (40 Credit Units)',
				'image'        => 'campus-courtyard-statue.jpg',
			),
			array(
				'slug'         => 'pde-programme',
				'title'        => 'Professional Diploma in Education (PDE)',
				'badge'        => 'Professional Certification',
				'duration'     => '1 Academic Session (Full-Time / Part-Time)',
				'affiliation'  => 'Teachers Registration Council of Nigeria (TRCN) Aligned',
				'summary'      => 'Designed for practicing educators, lecturers, and graduate professionals who wish to acquire formal pedagogical training, instructional leadership skills, and professional teaching qualification.',
				'requirements' => 'HND, Bachelor\'s Degree, or equivalent qualification from a recognized institution.',
				'school_count' => '2 Semesters • Research Project & Practicum',
				'image'        => 'campus-boulevard.jpg',
			),
			array(
				'slug'         => 'skills-acquisition',
				'title'        => 'Centre for Skills Acquisition & Entrepreneurship Development',
				'badge'        => 'Skill Department',
				'duration'     => '3 Months – 1 Year',
				'affiliation'  => 'Directorate of Skills Acquisition & Entrepreneurship Development, CCoE Mararaba',
				'summary'      => 'Intensive practical skills acquisition training programmes designed to empower students and community members with hands-on, income-generating vocational and digital skills at affordable fees.',
				'requirements' => 'Open to NCE/Degree students, youth, and members of the public seeking practical trade and digital mastery.',
				'school_count' => '5 Specialized Vocational Tracks',
				'image'        => 'skills-department-flyer.jpg',
			),
		);
	}

	/**
	 * NCE Schools & Programmes (New NCCE Minimum Standard 2026)
	 * Extracted directly from Sheet 1 of Assignment by Mr. ED.xlsx
	 */
	public static function get_nce_schools() {
		return array(
			array(
				'code'        => 'School A',
				'name'        => 'School of Arts and Languages Education',
				'duration'    => '3 Years (6 Semesters)',
				'level'       => 'NCE',
				'description' => 'Prepares articulate educators in language arts, literature, communication, and creative theatre pedagogy.',
				'programmes'  => array(
					'English',
					'Theatre Arts',
				),
			),
			array(
				'code'        => 'School B',
				'name'        => 'School of Science Education',
				'duration'    => '3 Years (6 Semesters)',
				'level'       => 'NCE',
				'description' => 'Equips science teachers with laboratory competence, quantitative reasoning, and inquiry-based STEM teaching methodologies.',
				'programmes'  => array(
					'Mathematics Education',
					'Biology Education',
					'Chemistry Education',
					'Physics Education',
				),
			),
			array(
				'code'        => 'School C',
				'name'        => 'School of Computing Education',
				'duration'    => '3 Years (6 Semesters)',
				'level'       => 'NCE',
				'description' => 'Forward-looking computing curriculum aligned with the 2026 NCCE Minimum Standards covering AI, Robotics, Data Science, Cybersecurity, and Software Engineering.',
				'programmes'  => array(
					'Artificial Intelligence and Robotics Education',
					'Computer Science Education',
					'Data Science and Analytics Education',
					'Networking and Cybersecurity Education',
					'Software and Web Development Education',
				),
			),
			array(
				'code'        => 'School D',
				'name'        => 'School of Business Management Education',
				'duration'    => '3 Years (6 Semesters)',
				'level'       => 'NCE',
				'description' => 'Develops entrepreneurial business educators grounded in financial accounting, corporate administration, marketing, and modern office technology.',
				'programmes'  => array(
					'Accounting Education',
					'Business Administration Education',
					'Entrepreneurship Education',
					'Office Technology and Management Education',
					'Marketing Education',
				),
			),
			array(
				'code'        => 'School E',
				'name'        => 'School of Social Sciences Education',
				'duration'    => '3 Years (6 Semesters)',
				'level'       => 'NCE',
				'description' => 'Trains educators in economic policy, public finance, governance, religious ethics, and civic leadership.',
				'programmes'  => array(
					'Economics and Public Finance Education',
					'CRS Education',
					'Political Science Education',
					'Social Studies and Civic Education',
				),
			),
			array(
				'code'        => 'School F',
				'name'        => 'School of Foundation and Lifelong Education',
				'duration'    => '3 Years (6 Semesters)',
				'level'       => 'NCE',
				'description' => 'Specializes in foundational childhood cognitive development, Montessori/ECCDE methods, and primary school pedagogy.',
				'programmes'  => array(
					'Early Childhood Care, Development and Education (ECCDE)',
					'Primary Education Studies',
				),
			),
			array(
				'code'        => 'School G',
				'name'        => 'School of Education and General Studies Education',
				'duration'    => '3 Years (6 Semesters)',
				'level'       => 'NCE',
				'description' => 'The pedagogical backbone of the College overseeing core curriculum studies, educational psychology, instructional technology, and supervised teaching practice.',
				'programmes'  => array(
					'Curriculum and Instruction',
					'Educational Foundations',
					'Educational Psychology and Counselling',
					'Educational Technology',
					'Centre of Educational Technology (CET)',
					'Teaching Practice Unit',
				),
			),
		);
	}

	/**
	 * Degree Programmes in Affiliation with Adekunle Ajasin University, Akungba-Akoko (AAUA)
	 * Extracted directly from Sheet 2 of Assignment by Mr. ED.xlsx
	 */
	public static function get_degree_departments() {
		return array(
			array(
				'code'        => 'Department A',
				'department'  => 'Arts Education',
				'affiliation' => 'Adekunle Ajasin University, Akungba-Akoko (AAUA)',
				'duration'    => '4 Years (UTME) / 3 Years (Direct Entry)',
				'programmes'  => array(
					'B.A. (Ed.) English',
				),
			),
			array(
				'code'        => 'Department B',
				'department'  => 'Social Science Education',
				'affiliation' => 'Adekunle Ajasin University, Akungba-Akoko (AAUA)',
				'duration'    => '4 Years (UTME) / 3 Years (Direct Entry)',
				'programmes'  => array(
					'B.Sc. (Ed.) Economics Education',
					'B.Sc. (Ed.) Political Science Education',
					'B.Ed. Social Studies and Civic Education',
				),
			),
			array(
				'code'        => 'Department C',
				'department'  => 'Science Education',
				'affiliation' => 'Adekunle Ajasin University, Akungba-Akoko (AAUA)',
				'duration'    => '4 Years (UTME) / 3 Years (Direct Entry)',
				'programmes'  => array(
					'B.Sc. (Ed.) Biology Education',
					'B.Sc. (Ed.) Chemistry Education',
					'B.Sc. (Ed.) Computer Science Education',
				),
			),
			array(
				'code'        => 'Department D',
				'department'  => 'Guidance and Counseling',
				'affiliation' => 'Adekunle Ajasin University, Akungba-Akoko (AAUA)',
				'duration'    => '4 Years (UTME) / 3 Years (Direct Entry)',
				'programmes'  => array(
					'B.Ed. Early Childhood Education',
					'B.Ed. Guidance and Counseling',
				),
			),
			array(
				'code'        => 'Department E',
				'department'  => 'Educational Management',
				'affiliation' => 'Adekunle Ajasin University, Akungba-Akoko (AAUA)',
				'duration'    => '4 Years (UTME) / 3 Years (Direct Entry)',
				'programmes'  => array(
					'B.Ed. Educational Management',
				),
			),
		);
	}

	/**
	 * PGDE Courses in Affiliation with AAUA
	 * Extracted directly from Sheet 3 of Assignment by Mr. ED.xlsx
	 */
	public static function get_pgde_courses() {
		return array(
			'first_semester'  => array(
				array( 'sn' => 1,  'code' => 'PDE 601', 'title' => 'History of Education',                'units' => 2, 'status' => 'C' ),
				array( 'sn' => 2,  'code' => 'PDE 603', 'title' => 'Introduction to Curriculum Studies',  'units' => 2, 'status' => 'C' ),
				array( 'sn' => 3,  'code' => 'PDE 605', 'title' => 'Introduction to Education Management','units' => 2, 'status' => 'C' ),
				array( 'sn' => 4,  'code' => 'PDE 607', 'title' => 'Research Method in Education',        'units' => 2, 'status' => 'C' ),
				array( 'sn' => 5,  'code' => 'PDE 609', 'title' => 'Statistic Method in Education',       'units' => 2, 'status' => 'C' ),
				array( 'sn' => 6,  'code' => 'PDE 611', 'title' => 'Sociology of Education',              'units' => 2, 'status' => 'C' ),
				array( 'sn' => 7,  'code' => 'PDE 613', 'title' => 'Educational Technology',              'units' => 2, 'status' => 'C' ),
				array( 'sn' => 8,  'code' => 'PDE 615', 'title' => 'Introduction to Special Education',   'units' => 2, 'status' => 'C' ),
				array( 'sn' => 9,  'code' => 'PDE 617', 'title' => 'Curriculum and Instructions',         'units' => 2, 'status' => 'C' ),
				array( 'sn' => 10, 'code' => 'PDE 619', 'title' => 'Leadership in Education',             'units' => 2, 'status' => 'C' ),
			),
			'second_semester' => array(
				array( 'sn' => 1,  'code' => 'PDE 600', 'title' => 'Introduction to Teaching Profession',                 'units' => 2, 'status' => 'C' ),
				array( 'sn' => 2,  'code' => 'PDE 602', 'title' => 'Psychological Foundation of Education',               'units' => 2, 'status' => 'C' ),
				array( 'sn' => 3,  'code' => 'PDE 604', 'title' => 'Teaching Method',                                     'units' => 2, 'status' => 'C' ),
				array( 'sn' => 4,  'code' => 'PDE 606', 'title' => 'Principles of Guidance and Counseling',               'units' => 2, 'status' => 'C' ),
				array( 'sn' => 5,  'code' => 'PDE 608', 'title' => 'Tests and Measurement',                               'units' => 2, 'status' => 'C' ),
				array( 'sn' => 6,  'code' => 'PDE 612', 'title' => 'Philosophical Foundation of Education',               'units' => 2, 'status' => 'C' ),
				array( 'sn' => 7,  'code' => 'PDE 620', 'title' => 'Curriculum Development Implementation and Change',    'units' => 2, 'status' => 'C' ),
				array( 'sn' => 8,  'code' => 'PDE 622', 'title' => 'Development Psychology',                              'units' => 2, 'status' => 'C' ),
				array( 'sn' => 9,  'code' => 'PDE 623', 'title' => 'Development of Education in Nigeria',                 'units' => 2, 'status' => 'C' ),
				array( 'sn' => 10, 'code' => 'PDE 616', 'title' => 'Introduction to Adult Education',                     'units' => 2, 'status' => 'C' ),
			),
		);
	}

	/**
	 * Centre for Skills Acquisition & Entrepreneurship Development Courses
	 * Sourced from official CCoE Skill Department Flyer
	 */
	public static function get_skills_courses() {
		return array(
			array( 'sn' => 1, 'skill' => 'Tailoring & Fashion Design', 'duration' => '1 Year',             'fees' => 'Affordable' ),
			array( 'sn' => 2, 'skill' => 'Hair Dressing & Cosmetology', 'duration' => '6 Months',          'fees' => 'Affordable' ),
			array( 'sn' => 3, 'skill' => 'Shoe Making & Leather Works', 'duration' => '6 Months',          'fees' => 'Affordable' ),
			array( 'sn' => 4, 'skill' => 'Computer Training & ICT',     'duration' => '3 Months & 6 Months', 'fees' => 'Affordable' ),
			array( 'sn' => 5, 'skill' => 'Screeding and Painting',      'duration' => '6 Months',          'fees' => 'Affordable' ),
		);
	}

	/**
	 * Map programme types to courses for dynamic dropdown in Provisional Admission Form
	 */
	public static function get_courses_by_programme_type() {
		$nce_courses = array();
		foreach ( self::get_nce_schools() as $school ) {
			foreach ( $school['programmes'] as $prog ) {
				$nce_courses[] = 'NCE — ' . $prog . ' (' . $school['name'] . ')';
			}
		}

		$degree_courses = array();
		foreach ( self::get_degree_departments() as $dept ) {
			foreach ( $dept['programmes'] as $prog ) {
				$degree_courses[] = $prog . ' (AAUA Affiliation)';
			}
		}

		$skills = array();
		foreach ( self::get_skills_courses() as $sk ) {
			$skills[] = $sk['skill'] . ' (' . $sk['duration'] . ')';
		}

		return array(
			'NCE'                 => $nce_courses,
			'Degree (AAUA)'       => $degree_courses,
			'PGDE (AAUA)'         => array(
				'Postgraduate Diploma in Education (PGDE — Full-Time / Part-Time)',
			),
			'PDE'                 => array(
				'Professional Diploma in Education (PDE — TRCN Aligned)',
			),
			'Skills Acquisition'  => $skills,
		);
	}

	/**
	 * Default Upcoming Events & Institutional News
	 */
	public static function get_default_events() {
		return array(
			array(
				'title'       => '2026/2027 Provisional Admission Screening & Documentation Exercise',
				'date'        => '2026-11-25',
				'time'        => '09:00 AM – 03:00 PM',
				'venue'       => 'Administrative Block Auditorium, Main Campus, Mararaba',
				'category'    => 'Admissions',
				'image'       => 'campus-main-gate.jpg',
				'description' => 'Screening and verification of original academic credentials for candidates applying for NCE, AAUA Affiliated Degree (B.Ed./B.Sc. Ed.), PGDE, and PDE programmes for the 2026/2027 academic session. Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
			),
			array(
				'title'       => 'Orientation Workshop on New NCCE Minimum Standard (2026) Curriculum',
				'date'        => '2026-12-04',
				'time'        => '10:00 AM – 02:00 PM',
				'venue'       => 'School of Computing & Science Lecture Theatre',
				'category'    => 'Academic Workshop',
				'image'       => 'lecture-walkway.jpg',
				'description' => 'Academic orientation seminar introducing new and returning students to the 2026 NCCE Minimum Standards, featuring special sessions on Artificial Intelligence & Robotics Education, Data Science, and Entrepreneurship.',
			),
			array(
				'title'       => 'Skills Acquisition & Entrepreneurship Exhibition Day ("Learn Today, Earn Tomorrow")',
				'date'        => '2026-12-14',
				'time'        => '11:00 AM – 04:00 PM',
				'venue'       => 'Centre for Skills Acquisition & Entrepreneurship Development, CCoE Mararaba',
				'category'    => 'Entrepreneurship',
				'image'       => 'skills-department-flyer.jpg',
				'description' => 'Practical exhibition hosted by the Director, Centre for Skills Acquisition & Entrepreneurship Development showcasing student projects in Tailoring, Hair Dressing, Shoe Making, Computer Training, and Screeding & Painting.',
			),
			array(
				'title'        => 'First Semester PGDE & AAUA Affiliated Degree Matriculation Ceremony',
				'date'         => '2027-01-18',
				'time'         => '09:30 AM Prompt',
				'venue'        => 'College Convocation Square, Mararaba Campus',
				'category'     => 'Matriculation',
				'image'        => 'campus-courtyard-statue.jpg',
				'description'  => 'Formal matriculation ceremony for newly admitted NCE, B.Ed./B.Sc. (Ed.) Degree (in affiliation with Adekunle Ajasin University, Akungba-Akoko), and PGDE students. Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
			),
		);
	}

	/**
	 * Nigerian States for the Provisional Admission Form
	 */
	public static function get_nigerian_states_and_lgas() {
		return array(
			'Nasarawa'    => array( 'Karu (Mararaba)', 'Lafia', 'Keffi', 'Akwanga', 'Kokona', 'Nasarawa', 'Nasarawa Egon', 'Doma', 'Awe', 'Keana', 'Obi', 'Toto', 'Wamba' ),
			'FCT - Abuja' => array( 'Abuja Municipal (AMAC)', 'Bwari', 'Gwagwalada', 'Kuje', 'Kwali', 'Abaji' ),
			'Kogi'        => array( 'Lokoja', 'Okene', 'Idah', 'Dekina', 'Ankpa', 'Kabba/Bunu', 'Olamaboro' ),
			'Benue'       => array( 'Makurdi', 'Gboko', 'Otukpo', 'Katsina-Ala', 'Vandeikya', 'Kwande' ),
			'Plateau'     => array( 'Jos North', 'Jos South', 'Barkin Ladi', 'Mangu', 'Pankshin', 'Shendam' ),
			'Kaduna'      => array( 'Kaduna North', 'Kaduna South', 'Zaria', 'Kafanchan (Jema\'a)', 'Chikun', 'Sabon Gari' ),
			'Niger'       => array( 'Minna (Chanchaga)', 'Suleja', 'Bida', 'Kontagora', 'Gurara', 'Tafa' ),
			'Ondo'        => array( 'Akoko South-West (Akungba-Akoko)', 'Akure South', 'Ondo West', 'Owo', 'Okitipupa' ),
			'Lagos'       => array( 'Ikeja', 'Surulere', 'Alimosho', 'Eti-Osa', 'Ikorodu', 'Epe', 'Badagry' ),
			'Kano'        => array( 'Kano Municipal', 'Nassarawa', 'Fagge', 'Gwale', 'Tarauni', 'Dala' ),
			'Rivers'      => array( 'Port Harcourt', 'Obio/Akpor', 'Bonny', 'Eleme', 'Ikwerre' ),
			'Enugu'       => array( 'Enugu North', 'Enugu South', 'Nsukka', 'Udi', 'Awgu' ),
			'Oyo'         => array( 'Ibadan North', 'Ogbomosho North', 'Oyo East', 'Iseyin', 'Saki West' ),
			'Anambra'     => array( 'Awka South', 'Onitsha North', 'Nnewi North', 'Aguata', 'Idemili North' ),
			'Delta'       => array( 'Oshimili South (Asaba)', 'Warri South', 'Ughelli North', 'Sapele', 'Ika South' ),
			'Other Nigerian State' => array( 'Specify Local Government Area in input field' ),
		);
	}
}
