# City College of Education, Mararaba — WordPress Administration & Setup Guide

## 1. Overview of Deliverables

This workspace contains the complete, production-ready WordPress website package for **City College of Education, Mararaba, Nasarawa State**:

1. **Customized WordPress Theme (`education-zone/` & `education-zone-ccoe.zip`)**:
   - Customized with the official City College of Education crest logo (`ccoe-logo.png`), campus photographs (`education-zone/images/ccoe/`), institutional colour scheme (Royal Navy Blue `#00337C`, Academic Gold `#F5B800`, and Emerald Green `#007A33`), Elementor support, and institutional header/footer.
2. **Core Institutional Plugin (`ccoe-core/` & `ccoe-core.zip`)**:
   - **Custom Post Types & Taxonomies**: Academic Programmes (`ccoe_programme`), Courses (`ccoe_course`), College Events (`ccoe_event`), Schools/Faculties (`ccoe_school`), Programme Levels (`ccoe_programme_level`), and Event Categories (`ccoe_event_category`).
   - **Database-Backed Provisional Admission System**: Custom database table (`wp_ccoe_applications`), protected upload storage (`wp-content/uploads/ccoe-applications/`), unique Reference Number generator (`CCOE/2026/ADM/XXXXX`), automated applicant & admissions office email notifications, Printable Acknowledgement Slip, and Admin management dashboard with CSV export.
   - **Elementor Integration**: Native `CCoE Institutional Section` Elementor widget under the **City College of Education (CCoE)** category.
   - **One-Click Content Seeder**: Automatically creates all 10 required pages, populates all NCE (New NCCE Minimum Standard 2026), AAUA Affiliated Degree, PGDE, PDE, and Skills Acquisition programmes/courses, and configures the navigation menu.
3. **WordPress Playground Blueprint (`blueprint.json`)**:
   - Declarative Blueprint for launching the entire configured WordPress site in WordPress Playground.
4. **Interactive Live Preview (`preview/index.html`)**:
   - Standalone browser preview of all 10 pages, real CCoE programmes & courses, campus photography, and the interactive Provisional Admission Application Form.

---

## 2. How to Install on WordPress (cPanel / LocalWP / XAMPP / Hosting)

1. Log in to your WordPress Administrator Dashboard (`/wp-admin/`).
2. **Install & Activate the Theme**:
   - Navigate to **Appearance → Themes → Add New Theme → Upload Theme**.
   - Select `education-zone-ccoe.zip`, click **Install Now**, and click **Activate**.
3. **Install & Activate the Core Plugin**:
   - Navigate to **Plugins → Add New Plugin → Upload Plugin**.
   - Select `ccoe-core.zip`, click **Install Now**, and click **Activate Plugin**.
4. **Automatic Setup**:
   - Upon activating **City College of Education Core (CCoE Core)**, the plugin automatically:
     - Creates the database tables (`wp_ccoe_applications` and `wp_ccoe_contact_messages`).
     - Creates all **10 required pages** (*Home, About Us, Our Vision and Mission, Message from the Provost, Academic Programmes, Courses, Admissions, News and Events, Contact Us, Apply for Provisional Admission*).
     - Populates all NCE Schools, AAUA Degree Departments, PGDE First & Second Semester Courses, and Default Events.
     - Assigns the **Home** page as the static Front Page and builds the **Primary Navigation Menu**.
5. *(Optional)* Install **Elementor** (`Plugins → Add New → Search "Elementor"`) to visually drag-and-drop CCoE widgets on any page.

---

## 3. Routine Website Maintenance Guide for Non-Technical Administrators

### A. Updating School Information, Vision, Mission & Contact Details
1. In the WordPress left sidebar, click **College Settings** (`WP Admin → College Settings`).
2. Update any of the following fields without touching code:
   - **General Information**: Institution Name, Motto, Campus Address, Postal Address, Telephone Numbers (`08032920100`, `08058431596`), Official & Admissions Emails, Current Academic Session (`2026/2027`), and Admissions Portal Open/Closed toggle.
   - **Vision, Mission & Background**: Vision Statement, Mission Statement, Institutional Background, College History, and Philosophy.
   - **Google Maps & Social Media**: Google Maps Embed URL and Facebook, X/Twitter, Instagram, and LinkedIn profile links.
3. Click **Save Institutional Settings**.

### B. Updating the Provost's Photograph & Welcome Message
1. Go to **Media → Add New Media File** and upload the official portrait photograph of the Provost. Copy the file URL.
2. Go to **College Settings → Section 3: Office of the Provost**.
3. Enter the Provost's Full Name & Academic Titles, Designation, paste the Photograph URL, and update the Welcome Message and Leadership Statement.
4. Click **Save Institutional Settings**.

### C. Adding, Editing, or Removing Academic Programmes & Courses
- **To Manage Programmes**:
  1. Go to **Programmes → All Programmes** (or click **Add New Programme**).
  2. Enter the Programme Title, description, select the **School / Department** and **Programme Level** (*NCE, Degree (AAUA), PGDE, PDE, Skills Acquisition*) on the right sidebar.
  3. In the **Programme Details** box below the editor, enter the Qualification Badge, Duration, Affiliating Body, Available Courses (one per line), and Entry Requirements.
  4. Click **Publish** or **Update**.
- **To Manage Courses**:
  1. Go to **Courses → All Courses** (or click **Add New Course**).
  2. Enter the Course Title and fill in the **Course Details** meta box (*Course Code* e.g. `PDE 601`, *Credit Units* e.g. `2`, *Course Status* `C` or `E`, and *Semester*).
  3. Click **Publish** or **Update**.

### D. Publishing News & Upcoming Events
1. For **College Events**:
   - Go to **Events → Add New Event**.
   - Enter the Event Title, description, upload a **Featured Image**, select an **Event Category** (*Admissions, Academic Workshop, Matriculation, Entrepreneurship*), and fill in the **Event Date**, **Event Time**, and **Venue** in the Event Schedule box.
   - Click **Publish**. The event will automatically appear on the Homepage and the **News and Events** page.
2. For **General News Articles**:
   - Go to **Posts → Add New Post**, write the announcement, set a Featured Image, and click **Publish**.

### E. Reviewing & Managing Provisional Admission Applications
1. In the WordPress left sidebar, click **Admissions → All Applications**.
2. You will see summary counters (*Total Applications, Pending Review, Offered Provisional Admission*) and a searchable table of all applicants.
3. Click **View / Manage** next to any applicant to:
   - View their Personal Details, Contact Details, Academic Details, and Next of Kin information.
   - Securely view or download their **Passport Photograph** and **Academic Credentials**.
   - Update their **Decision Status** (*Pending Review, Under Review, Offered Provisional Admission, Declined*) and record internal **Admissions Office Notes**.
4. Click **Export All to CSV** at the top of the page to download all applications into Microsoft Excel.
