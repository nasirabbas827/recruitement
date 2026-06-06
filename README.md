# Recruitement_Final

## Overview
Recruitement_Final is a PHP‑based web application that facilitates the hiring process for both administrators and candidates. Admins can post jobs, manage users, and review feedback, while candidates can browse listings, apply for positions, and communicate with recruiters. The project includes a ready‑to‑import MySQL schema and a clean, responsive UI.

## Features
- **Admin Panel**
  - Secure login & logout
  - Create, edit, and delete job postings
  - View and manage candidate profiles
  - Receive and reply to messages
  - Collect and review candidate feedback
- **Candidate Portal**
  - Register / login with password reset support
  - Browse and apply to available jobs
  - Upload supporting documents (PDFs)
  - View application status and notifications
  - Send messages to admins and submit feedback
- **Common**
  - Centralized configuration (`config.php`)
  - Reusable navigation bars for admin and candidate sections
  - Simple CSS styling (`style.css`) for a consistent look

## Tech Stack
| Layer | Technology |
|-------|------------|
| Backend | PHP 7.4+ |
| Database | MySQL (schema in `Database/jobs_db.sql`) |
| Front‑end | HTML5, CSS3 |
| Server | Apache / Nginx (any LAMP/LEMP stack) |

## Installation
1. **Clone the repository**  
   ```bash
   git clone https://github.com/your-username/Recruitement_Final.git
   cd Recruitement_Final
   ```

2. **Set up the database**  
   - Create a new MySQL database (e.g., `recruitment`).  
   - Import the schema:  
     ```bash
     mysql -u root -p recruitment < Database/jobs_db.sql
     ```

3. **Configure the application**  
   - Copy `config.php.example` to `config.php` (if an example file exists) or edit the existing `config.php`.  
   - Update the following placeholders with your environment values:  
     ```php
     define('DB_HOST', 'YOUR_DB_HOST');
     define('DB_NAME', 'YOUR_DB_NAME');
     define('DB_USER', 'YOUR_DB_USER');
     define('DB_PASS', 'YOUR_DB_PASSWORD');
     define('BASE_URL', 'http://your-domain.com/');
     ```

4. **Set file permissions** (optional but recommended)  
   ```bash
   chmod -R 755 candidate/uploads/
   ```

5. **Start the web server**  
   - If using XAMPP/WAMP, place the project in the `htdocs`/`www` directory.  
   - For Docker or a custom setup, expose the project via your web server’s document root.

## Usage
### Admin
1. Navigate to `http://your-domain.com/admin/admin_login.php`.  
2. Log in with the admin credentials (default credentials can be set in the database).  
3. Use the admin navigation bar to:
   - **Post Jobs** – `admin_jobs.php`
   - **View Applicants** – `view_user_details.php` / `view_users.php`
   - **Manage Feedback** – `admin_feedback.php`
   - **Logout** – `logout.php`

### Candidate
1. Open `http://your-domain.com/index.php` and register or log in.  
2. After authentication, you’ll be redirected to the candidate dashboard (`candidate_dashboard.php`).  
3. From the dashboard you can:
   - **Browse Jobs** – `candidate/apply_job.php`
   - **Apply** – upload PDFs (`candidate/uploads/`) and submit the application.
   - **View Notifications** – `view_notifications.php`
   - **Message Admin** – `view_received_messages.php` / `reply_message.php`
   - **Submit Feedback** – `submit_feedback.php`
   - **Update Profile** – `update_profile