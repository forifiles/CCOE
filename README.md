# City College of Education, Mararaba — Official Website & WordPress Platform

> **"Education for Self Employment"**  
> **Location:** No. 1 City College Road, Off Old Karu Road, Sani Abacha Road Estate, Mararaba-Karu, Nasarawa State, Nigeria  
> **Affiliations & Accreditations:** National Commission for Colleges of Education (**New NCCE Minimum Standard 2026**) & **Adekunle Ajasin University, Akungba-Akoko (AAUA), Ondo State**

---

## Overview

This repository contains the complete official website, custom WordPress core plugin (`ccoe-core`), customized WordPress theme (`education-zone`), design system specifications, and standalone local preview server for **City College of Education, Mararaba, Nasarawa State**.

### Key Features

1. **Streamlined Institutional Navigation & Pages**
   - **Home**: Hero showcase, institutional profile, integrated **Vision, Mission, Philosophy & Core Values** (with the Administrative Block engraved plaques), Academic Programmes showcase, Message from the Provost, Upcoming Events, and a bottom **Contact Us & Campus Map** call-to-action banner.
   - **About Us**: Institutional background, history, philosophy, and core values.
   - **Provost's Message**: Welcome address and leadership statement from the Office of the Provost.
   - **Academic Programmes** *(Clickable Page + Dropdown Menu)*:
     - **NCE Programmes (New NCCE Minimum Standard 2026)**: 28 programmes across 7 specialized Academic Schools (*Arts & Languages*, *Science*, *Computing — AI & Robotics, Data Science, Cybersecurity, Software Development*, *Business Management*, *Social Sciences*, *Foundation & Lifelong Education*, and *Education & General Studies*).
     - **B.Ed. / B.Sc. (Ed.) Degree Programmes**: 10 undergraduate degree programmes across 5 Departments in affiliation with **Adekunle Ajasin University, Akungba-Akoko (AAUA), Ondo State**.
     - **Postgraduate & Professional Diplomas**: **PGDE** (in affiliation with AAUA) and **PDE**.
     - **Centre for Skills Acquisition & Entrepreneurship**: 5 practical vocational & digital tracks (*Tailoring*, *Hair Dressing*, *Shoe Making*, *Computer Training*, and *Screeding & Painting*).
   - **Courses** *(Clickable Page + Dropdown Menu)*:
     - Live searchable course catalog featuring the 20 compulsory **PGDE First & Second Semester Courses** (`PDE 601`–`PDE 619` and `PDE 600`–`PDE 623`, 40 Credit Units), NCE courses by school, AAUA affiliated degree courses, and vocational skills courses.
   - **News & Events**: Dynamic campus events and news archive synced with the Admin Events Manager.
   - **Admissions & Apply**: Unified 4-step admission guidelines and **Provisional Admission Application Form** with automatic Reference Number generation (`CCOE/2026/ADM/XXXXX`) and printable Acknowledgement Slip.

2. **WordPress Custom Post Types & Database-Backed Admissions (`ccoe-core`)**
   - **Custom Post Types**:
     - `ccoe_programme` — Academic Programmes & Schools
     - `ccoe_course` — Course Catalog & Credit Units
     - `ccoe_event` — Campus Events (Date, Time, Venue, Category, Featured Image)
   - **Database-Backed Provisional Admission System (`CCOE_Admissions`)**:
     - Validates personal, contact, academic, Next of Kin, and file upload fields (Passport Photograph & Academic Credentials).
     - Stores submissions in a dedicated custom database table (`{prefix}_ccoe_admission_applications`), sends email notifications, and generates a printable acknowledgement slip.
   - **Elementor Integration (`CCOE_Elementor`)**:
     - Custom **CCoE Section Renderer** Elementor widget for drag-and-drop page building.

3. **UI/UX Pro Max Design System**
   - Built on **Swiss Modernism 2.0 + Editorial Grid** architecture ([`design-system/city-college-of-education/MASTER.md`](./design-system/city-college-of-education/MASTER.md)).
   - **Typography**: *Crimson Pro* (academic serif headings) paired with *Atkinson Hyperlegible* & *Inter* (accessible body copy).
   - **Official Crest Palette**: Royal Navy (`#00337C`), Academic Gold (`#F5B800`), and Laurel Green (`#007A33`).
   - **Accessibility**: Crisp `2px`-stroke inline Lucide vector icons (`aria-hidden="true"`), `>=44px` touch targets, `:focus-visible` keyboard rings, and `prefers-reduced-motion` support.

---

## Repository Structure

```text
├── ccoe-core/                        # Custom WordPress Core Plugin (CPTs, Admissions DB, Shortcodes, Elementor)
│   ├── assets/css/ccoe-frontend.css  # UI/UX Pro Max stylesheet
│   ├── assets/js/ccoe-frontend.js    # Dynamic state/LGA & programme/course selectors
│   ├── includes/                     # PHP classes (Data, Post Types, Admissions, Settings, Shortcodes, Seeder)
│   └── ccoe-core.php                 # Main plugin bootstrap
├── education-zone/                   # Customized WordPress Theme with CCoE branding & campus photography
│   ├── images/ccoe/                  # Official crest logo, favicon, and campus photographs
│   └── inc/ccoe-theme-setup.php      # Theme overrides & Elementor support
├── preview/                          # Standalone Interactive Local Preview (Frontend & WP Admin Portal)
│   ├── index.html                    # Interactive frontend website
│   └── wp-admin.html                 # Interactive WP Admin dashboard (Admissions CSV export & Events CRUD)
├── design-system/                    # UI/UX Pro Max Design System Master Specification
├── .agents/skills/                   # Installed WordPress & UI/UX Pro Max Agent Skills
├── start-local-server.ps1            # Zero-dependency PowerShell HTTP server (ports 8000 & 8080)
├── docker-compose.yml                # Docker Compose configuration for WordPress + MySQL 8.0
├── blueprint.json                    # WordPress Playground Blueprint
├── ccoe-core.zip                     # Distributable WordPress plugin archive
├── education-zone-ccoe.zip           # Distributable WordPress theme archive
└── ADMINISTRATION_AND_SETUP_GUIDE.md # Full WordPress administrator & deployment guide
```

---

## Quick Start (Local Server)

### Option 1: Instant Zero-Dependency PowerShell Server (Windows)

Run the included PowerShell server script to launch the interactive website and Admin Portal immediately without needing PHP or Docker:

```powershell
powershell -ExecutionPolicy Bypass -File .\start-local-server.ps1
```

- **Frontend Website:** [http://localhost:8000/](http://localhost:8000/) (or [http://localhost:8080/](http://localhost:8080/))
- **WordPress Admin Portal:** [http://localhost:8000/wp-admin](http://localhost:8000/wp-admin)
  - **Username:** `admin`
  - **Password:** `CCoE@Admin2026`
  - Create or delete campus events under **Events (CPT)** and review/export Provisional Admission applications under **Admissions Applications**.

### Option 2: Docker Compose (Full WordPress + MySQL Stack)

```bash
docker compose up -d
```

Then open `http://localhost:8080` to complete the WordPress setup; the `education-zone` theme and `ccoe-core` plugin are mounted automatically.

### Option 3: Existing WordPress Hosting (cPanel / Plesk / LocalWP / XAMPP)

1. Upload and activate [`education-zone-ccoe.zip`](./education-zone-ccoe.zip) under **Appearance → Themes → Add New → Upload Theme**.
2. Upload and activate [`ccoe-core.zip`](./ccoe-core.zip) under **Plugins → Add New → Upload Plugin**.
3. Upon activation, **CCoE Core** automatically creates all pages, navigation menus, custom database tables, and academic programmes/courses.

See [`ADMINISTRATION_AND_SETUP_GUIDE.md`](./ADMINISTRATION_AND_SETUP_GUIDE.md) for detailed administration instructions.
