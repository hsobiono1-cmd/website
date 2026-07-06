# Online Voting System 3
## Setup Instructions for XAMPP

### 1. Copy Project Files
Copy the entire `voting_system3` folder to:
```
C:/xampp/htdocs/voting_system3/
```

### 2. Create the Database
1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click **Import**
3. Choose the file: `voting_system3.sql`
4. Click **Go**

Or run this in the SQL tab:
```sql
SOURCE C:/xampp/htdocs/voting_system3/voting_system3.sql;
```

### 3. Configure Database (if needed)
Edit `includes/db.php` if your MySQL credentials differ:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');       // Add your MySQL password here
define('DB_NAME', 'voting_system3');
```

### 4. Set Folder Permissions
Make sure `uploads/candidates/` is writable (for photo uploads).
XAMPP on Windows handles this automatically.

### 5. Access the System
- **Home Page:**    http://localhost/voting_system3/
- **Voter Login:**  http://localhost/voting_system3/login.php
- **Register:**     http://localhost/voting_system3/register.php
- **Admin Panel:**  http://localhost/voting_system3/admin/login.php

---

## Default Admin Login
- **Username:** `admin`
- **Password:** `password`

> ⚠️ Change the admin password after first login via phpMyAdmin!
> Run: `UPDATE admins SET password = '$2y$10$...' WHERE username = 'admin';`
> Generate hash with: `echo password_hash('yourpassword', PASSWORD_DEFAULT);`

---

## System Structure
```
voting_system3/
├── index.php              # Landing page
├── login.php              # Voter login
├── register.php           # Voter registration
├── dashboard.php          # Voter dashboard
├── vote.php               # Voting page
├── results.php            # Public results
├── logout.php             # Voter logout
├── voting_system3.sql     # Database file
├── css/
│   └── style.css          # Main stylesheet
├── includes/
│   ├── db.php             # Database connection
│   └── auth.php           # Auth helpers
├── uploads/
│   └── candidates/        # Candidate photos
└── admin/
    ├── login.php           # Admin login
    ├── logout.php          # Admin logout
    ├── dashboard.php       # Admin dashboard
    ├── voters.php          # Voter CRUD
    ├── candidates.php      # Candidate CRUD
    ├── positions.php       # Position CRUD
    ├── election.php        # Election control
    ├── results.php         # Admin results
    └── includes/
        └── sidebar.php     # Sidebar navigation
```

---

## Features
- ✅ Voter Registration with unique Voter ID check
- ✅ Login with Username or Voter ID
- ✅ PHP `password_hash()` / `password_verify()`
- ✅ PHP Sessions for authentication
- ✅ One vote per voter enforcement
- ✅ Candidate photo uploads
- ✅ Admin CRUD for Voters, Candidates, Positions
- ✅ Election Start / End / Reset controls
- ✅ Live results with vote percentages
- ✅ Prepared statements (SQL injection prevention)
- ✅ Modern Black & White responsive design
- ✅ No JavaScript required

---

## Default Positions (pre-loaded)
1. President
2. Vice President
3. Secretary
4. Treasurer
5. Auditor


Agile Documentation

 Project Title

Online Voting System 3



 1. Project Vision

 Vision Statement

The Online Voting System 3 is a web-based application designed to provide a secure, reliable, and user-friendly voting platform.
 It allows registered voters to cast their votes electronically while enabling administrators to manage elections, candidates, positions, voters, and election results efficiently.
 The system replaces the traditional paper-based voting process with a digital solution that promotes fairness, transparency, and accurate vote counting.

 2. Project Objectives

* Develop a secure online voting application using PHP and MySQL.
* Allow voters to register and log in securely.
* Ensure each voter can vote only once.
* Provide administrators with tools to manage the entire election process.
* Display election results accurately.
* Protect user data through password hashing and prepared SQL statements.
* Create a responsive interface using HTML and CSS without JavaScript.



 3. Product Backlog (User Stories)

| ID    | User Story                                                                      | Priority | Story Points |
| ----- | ------------------------------------------------------------------------------- | -------- | ------------ |
| US-01 | As a voter, I want to register an account so I can participate in the election. | High     | 5            |
| US-02 | As a voter, I want to log in securely using my username or voter ID.            | High     | 5            |
| US-03 | As a voter, I want my password to be encrypted for security.                    | High     | 3            |
| US-04 | As a voter, I want to vote only once to ensure a fair election.                 | High     | 8            |
| US-05 | As a voter, I want to view candidates according to their positions.             | High     | 5            |
| US-06 | As a voter, I want to submit my vote successfully.                              | High     | 8            |
| US-07 | As an administrator, I want to manage voter accounts.                           | High     | 8            |
| US-08 | As an administrator, I want to manage candidate information.                    | High     | 8            |
| US-09 | As an administrator, I want to manage election positions.                       | High     | 5            |
| US-10 | As an administrator, I want to upload candidate photos.                         | Medium   | 5            |
| US-11 | As an administrator, I want to start and stop the election.                     | High     | 5            |
| US-12 | As an administrator, I want to reset election results when necessary.           | High     | 5            |
| US-13 | As a voter, I want to view election results after voting.                       | Medium   | 5            |
| US-14 | As an administrator, I want to monitor voting statistics.                       | Medium   | 3            |
| US-15 | As an administrator, I want secure session authentication.                      | High     | 5            |

---

 4. Product Backlog Prioritization

Must Have

* User Registration
* User Login
* Password Hashing
* Voting Module
* One Vote Restriction
* Administrator Login
* Candidate Management
* Position Management
* Election Control
* Results Module

Should Have

* Candidate Photo Upload
* Responsive User Interface
* Public Results Page

 Could Have

* Voting Statistics
* Dashboard Summary



5. Sprint Plan

 Sprint 1 – Project Setup

   Goal

Establish the foundation of the Online Voting System.

 Tasks

* Create the database.
* Organize the project folder structure.
* Configure the database connection.
* Design the responsive layout.
* Develop the landing page.
* Configure PHP sessions.

Deliverables

* Connected database.
* Functional landing page.
* Responsive page layout.



Sprint 2 – Authentication Module

Goal

Implement secure user authentication.

 Tasks

* Develop voter registration.
* Validate user passwords.
* Encrypt passwords using password hashing.
* Create login functionality.
* Implement logout.
* Configure session management.

 Deliverables

* Working registration module.
* Secure login system.
* Protected user authentication.



 Sprint 3 – Voting Module

Goal

Allow registered voters to cast their votes securely.

 Tasks

* Display available positions.
* Display candidates under each position.
* Validate submitted votes.
* Prevent duplicate voting.
* Save voting records.

 Deliverables

* Voting interface.
* Vote recording functionality.
* One-vote enforcement.



Sprint 4 – Administrator Module

 Goal

Develop the administrative functions.

 Tasks

* Administrator login.
* Dashboard.
* Manage voters.
* Manage candidates.
* Manage positions.
* Upload candidate photos.

 Deliverables

* Fully functional administrator panel.



 Sprint 5 – Election Management

 Goal

Provide complete election control.

Tasks

* Start election.
* End election.
* Reset election.
* Display live election results.
* Calculate vote percentages.

 Deliverables
* Election management module.
* Live results page.



 Sprint 6 – Testing and Deployment

 Goal

Finalize and prepare the system for deployment.

 Tasks

* Functional testing.
* Security testing.
* Bug fixing.
* User interface improvements.
* Deploy using XAMPP.

 Deliverables

* Fully functional Online Voting System.



 6. Sprint Backlog

 Sprint 1

* Database creation
* Project structure
* CSS layout
* Landing page

 Sprint 2

* Registration
* Login
* Logout
* Session management

 Sprint 3

* Voting interface
* Vote validation
* Vote submission

 Sprint 4

* Administrator dashboard
* CRUD modules

 Sprint 5

* Election management
* Results page
* Reports

 Sprint 6

* Testing
* Deployment
* Documentation



 7. Functional Requirements

 Voter Module

* Register an account.
* Log in securely.
* Access the voter dashboard.
* Vote only once.
* View election results.
* Log out.

Administrator Module

* Log in securely.
* Access the administrator dashboard.
* Manage voter records.
* Manage candidate records.
* Manage election positions.
* Upload candidate images.
* Start the election.
* End the election.
* Reset election results.
* View election results.



 8. Non-Functional Requirements

Performance

* Fast page loading.
* Optimized database queries.

 Security

* Password hashing.
* Prepared SQL statements.
* Secure session management.
* Protection against SQL injection.

 Reliability

* Prevent duplicate voting.
* Ensure accurate vote counting.

 Usability

* Responsive interface.
* Black-and-white theme.
* Easy navigation.

Compatibility

* Windows operating system.
* XAMPP.
* PHP 8 or later.
* MySQL.
* Modern web browsers.



 9. System Modules

 Public Module

* Landing Page
* Results Page

 Voter Module

* Registration
* Login
* Dashboard
* Voting
* Logout

 Administrator Module

* Login
* Dashboard
* Voter Management
* Candidate Management
* Position Management
* Election Management
* Results


10. Database Tables

| Table      | Description                          |
| ---------- | ------------------------------------ |
| admins     | Stores administrator accounts.       |
| voters     | Stores registered voter information. |
| candidates | Stores candidate details.            |
| positions  | Stores available election positions. |
| votes      | Stores submitted votes.              |
| election   | Stores the current election status.  |



 11. Acceptance Criteria

The system will be considered complete when all of the following conditions are met:

* Users can register successfully.
* Password validation requirements are enforced.
* Passwords are encrypted using `password_hash()`.
* Users can log in securely.
* Authenticated pages are protected using sessions.
* Each voter is allowed to vote only once.
* Votes are recorded accurately in the database.
* Administrators can perform Create, Read, Update, and Delete (CRUD) operations.
* Election controls function correctly.
* Election results update automatically.
* Candidate photo uploads work properly.
* The interface is responsive on desktop, tablet, and mobile devices.
* The system operates without JavaScript.
* SQL injection is prevented through prepared statements.



 12. Definition of Done

A feature is considered complete when:

* The feature functions as intended.
* Input validation has been implemented.
* No critical bugs remain.
* Database operations execute successfully.
* Responsive layout has been verified.
* Security measures have been applied.
* The feature has passed testing.
* Documentation has been updated.
* The system is ready for deployment using XAMPP.



 13. Expected Outcome

The Online Voting System 3 is expected to provide a secure, reliable, and responsive electronic voting platform that enables registered voters to participate in elections efficiently while allowing administrators to manage election activities effectively
The system promotes transparency, fairness, and accuracy through secure authentication, password encryption, one-vote enforcement, real-time vote recording, and comprehensive administrative tools. It is intended to serve as a complete academic project
 for Information Systems or Software Engineering developed using the Agile methodology.

