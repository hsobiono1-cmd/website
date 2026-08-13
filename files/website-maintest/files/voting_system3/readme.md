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
- **Password:** `$$Drowranger^^55`

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


# AGILE DOCUMENTATION

## PROJECT TITLE

**Online Voting System 3**

---

# 1. PROJECT VISION

## Vision Statement

The **Online Voting System 3** aims to provide a secure, reliable, and user-friendly web-based voting platform that allows registered voters to cast their votes electronically. The system also enables administrators to efficiently manage voters, candidates, election positions, election settings, and election results.

The system is designed to replace traditional paper-based voting with a digital solution that promotes fairness, transparency, efficiency, and accurate vote counting.

---

# 2. PROJECT OBJECTIVES

The main objectives of the **Online Voting System 3** are:

* Develop a secure online voting application using PHP and MySQL.
* Allow voters to register and authenticate securely.
* Implement password validation during voter registration.
* Protect user passwords through secure password hashing.
* Prevent voters from voting more than once.
* Allow voters to view candidates according to their positions.
* Enable voters to submit their votes successfully.
* Allow administrators to manage voter accounts.
* Allow administrators to manage candidates.
* Allow administrators to manage election positions.
* Allow administrators to start and stop the election.
* Allow administrators to start the election even if some positions do not have candidates.
* Display election results accurately.
* Maintain data integrity and security using prepared statements and password hashing.
* Create a responsive interface without JavaScript.

---

# 3. PRODUCT BACKLOG – USER STORIES

| ID        | User Story                                                                                                                                                                      | Priority | Story Points |
| --------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------- | -----------: |
| US-01     | As a voter, I want to register an account so I can participate in the election.                                                                                                 | High     |            5 |
| US-02     | As a voter, I want to log in securely using my username or voter ID so my account is protected.                                                                                 | High     |            5 |
| US-03     | As a voter, I want my password to be securely hashed so my account information is protected.                                                                                    | High     |            3 |
| US-04     | As a voter, I want to vote only once so the election remains fair.                                                                                                              | High     |            8 |
| US-05     | As a voter, I want to view the list of candidates by position so I can make informed choices.                                                                                   | High     |            5 |
| US-06     | As a voter, I want to submit my votes successfully so my choices can be recorded.                                                                                               | High     |            8 |
| US-07     | As an administrator, I want to manage voter accounts so voter information can be properly maintained.                                                                           | High     |            8 |
| US-08     | As an administrator, I want to manage candidates so the correct candidates are available during the election.                                                                   | High     |            8 |
| US-09     | As an administrator, I want to manage election positions so the election positions can be properly configured.                                                                  | High     |            5 |
| US-10     | As an administrator, I want to upload candidate photos so voters can easily identify candidates.                                                                                | Medium   |            5 |
| US-11     | As an administrator, I want to start or stop an election so I can control when voting is available.                                                                             | High     |            5 |
| US-12     | As an administrator, I want to reset election results so the system can be prepared for another election.                                                                       | High     |            5 |
| US-13     | As a voter, I want to view election results after voting so I can see the current election outcome.                                                                             | Medium   |            5 |
| US-14     | As an administrator, I want to monitor voting statistics so I can track election activity.                                                                                      | Medium   |            3 |
| US-15     | As an administrator, I want secure session authentication so unauthorized users cannot access protected pages.                                                                  | High     |            5 |
| **US-16** | **As an administrator, I want to start the election even if some positions do not have candidates, so the election can proceed without requiring every position to be filled.** | **High** |        **5** |
| **US-17** | **As a voter, I want password validation during registration so that my account is protected by a strong password.**                                                            | **High** |        **5** |

---

# 4. PRODUCT BACKLOG PRIORITIZATION

## Must Have

The following features are required for the basic operation of the system:

* User Registration
* Login Authentication
* Password Validation
* Password Hashing
* Voting Module
* One-Vote Restriction
* Admin Login
* Candidate Management
* Position Management
* Election Control
* Results Module
* Secure Session Authentication

## Should Have

The following features improve the system's functionality:

* Candidate Photo Upload
* Responsive Design
* Public Results
* Automatic Vote Counting

## Could Have

The following features may be added as additional improvements:

* Election Statistics
* Dashboard Summary
* Additional Election Reports

---

# 5. DATABASE DESIGN

## Database Name

**voting_system3**

## Database Tables

| Table             | Description                          |
| ----------------- | ------------------------------------ |
| admins            | Stores administrator accounts.       |
| voters            | Stores voter information.            |
| positions         | Stores election positions.           |
| candidates        | Stores candidate information.        |
| votes             | Stores voting records.               |
| election_settings | Stores election status and schedule. |

---

## Database Structure

### admins

| Field      | Type         |
| ---------- | ------------ |
| id         | INT (PK)     |
| username   | VARCHAR(100) |
| password   | VARCHAR(255) |
| created_at | TIMESTAMP    |

The `admins` table stores administrator account information.

---

### voters

| Field      | Type                       |
| ---------- | -------------------------- |
| id         | INT (PK)                   |
| voter_id   | VARCHAR(50)                |
| first_name | VARCHAR(100)               |
| last_name  | VARCHAR(100)               |
| birthdate  | DATE                       |
| address    | TEXT                       |
| username   | VARCHAR(100)               |
| password   | VARCHAR(255)               |
| status     | ENUM('Pending','Approved') |
| voted      | TINYINT(1)                 |

The `voters` table stores registered voter information and voting status.

---

### positions

| Field         | Type         |
| ------------- | ------------ |
| id            | INT (PK)     |
| position_name | VARCHAR(100) |

### Election Positions

The system may include the following positions:

* President
* Vice President
* Secretary
* Treasurer
* Auditor

---

### candidates

| Field       | Type         |
| ----------- | ------------ |
| id          | INT (PK)     |
| position_id | INT (FK)     |
| fullname    | VARCHAR(150) |
| photo       | VARCHAR(255) |
| description | TEXT         |

The `candidates` table stores information about candidates and their assigned positions.

### Sample Candidates

| Candidate           | Position       |
| ------------------- | -------------- |
| Rodrigo Roa Duterte | President      |
| Bongbong Marcos     | President      |
| Robin Padilla       | Vice President |
| Leni Robredo        | Vice President |
| Kiko Pangilinan     | Secretary      |
| Tito Sotto          | Secretary      |
| Pablo Escobar       | Treasurer      |
| Al Pacino           | Treasurer      |
| Bam Aquino          | Auditor        |
| Bato Dela Rosa      | Auditor        |

---

### votes

| Field        | Type      |
| ------------ | --------- |
| id           | INT (PK)  |
| voter_id     | INT (FK)  |
| candidate_id | INT (FK)  |
| position_id  | INT (FK)  |
| voted_at     | TIMESTAMP |

The `votes` table stores the votes submitted by voters.

---

### election_settings

| Field           | Type                  |
| --------------- | --------------------- |
| id              | INT (PK)              |
| election_status | ENUM('Open','Closed') |
| start_date      | DATETIME              |
| end_date        | DATETIME              |

The `election_settings` table stores the current election status and schedule.

---

## Database Relationships

```text
admins

voters
   │
   └───────────────┐
                   │
positions          │
   │               │
   └── candidates   │
          │         │
          └── votes ┘

election_settings
```

### Foreign Keys

* `candidates.position_id` → `positions.id`
* `votes.voter_id` → `voters.id`
* `votes.candidate_id` → `candidates.id`
* `votes.position_id` → `positions.id`

---

# 6. PASSWORD VALIDATION REQUIREMENTS

The system must validate voter passwords during registration.

A valid password must:

* Have a minimum of **12 characters**.
* Have a maximum of **15 characters**.
* Contain at least **one uppercase letter (A-Z)**.
* Contain at least **one lowercase letter (a-z)**.
* Contain at least **one number (0-9)**.
* Contain at least **one special character**.
* Allow special characters such as `*`, `=`, `+`, and `-`.
* Match the confirmation password.
* Be securely stored using `password_hash()`.

If the password does not meet the required rules, the system must reject the registration and display an appropriate validation message.

---

# 7. ELECTION START REQUIREMENTS

The administrator controls when the election begins and ends.

The system must allow the administrator to **start the election even if some positions do not have candidates**.

The election does not require every available position to have candidates before voting can begin. This allows the election to proceed without requiring every position to be completely filled.

The administrator can:

* Start the election.
* Stop the election.
* View the current election status.
* Reset the election results.

When the election is open, approved voters can vote for the available candidates.

---

# 8. SPRINT PLAN

## Sprint 1 – Project Setup

**Week 1**

### Goal

Build the foundation of the Online Voting System.

### Tasks

* Create the database.
* Create project folders.
* Configure the database connection.
* Design the responsive layout.
* Create the landing page.
* Configure sessions.
* Create the initial CSS structure.

### Deliverables

* Database connected.
* Home page completed.
* Responsive layout.
* Basic project structure.

---

## Sprint 2 – Authentication Module

**Week 2**

### Goal

Develop secure authentication for voters and administrators.

### Tasks

* Voter registration.
* Password validation.
* Password confirmation.
* Password hashing.
* Voter login.
* Admin login.
* Logout.
* Session management.

### Deliverables

* Registration completed.
* Login system completed.
* Password validation implemented.
* Authentication secured.
* Session management completed.

---

## Sprint 3 – Voting Module

**Week 3**

### Goal

Allow approved voters to cast their votes.

### Tasks

* Display positions.
* Display available candidates.
* Validate votes.
* Prevent duplicate voting.
* Save submitted votes.
* Update voter voting status.
* Prevent voting when the election is closed.

### Deliverables

* Voting page.
* Vote validation.
* Vote recording.
* One-vote enforcement.

---

## Sprint 4 – Admin Module

**Week 4**

### Goal

Create the administrator functions.

### Tasks

* Admin login.
* Admin dashboard.
* CRUD voters.
* CRUD candidates.
* CRUD positions.
* Upload candidate photos.
* Manage voter status.

### Deliverables

* Complete admin panel.
* Voter management.
* Candidate management.
* Position management.
* Candidate photo management.

---

## Sprint 5 – Election Management

**Week 5**

### Goal

Provide administrators with control over election activities.

### Tasks

* Start election.
* End election.
* Allow election to start even if some positions do not have candidates.
* Reset election.
* Display live results.
* Calculate vote percentages.
* Monitor voting statistics.

### Deliverables

* Election controls.
* Election status management.
* Live results page.
* Vote statistics.

---

## Sprint 6 – Testing and Deployment

**Week 6**

### Goal

Finalize and prepare the system for deployment.

### Tasks

* Functional testing.
* Security testing.
* Password validation testing.
* Duplicate-vote testing.
* Election control testing.
* Bug fixing.
* UI improvements.
* Responsive design testing.
* Final deployment using XAMPP.

### Deliverables

* Working Online Voting System.
* Tested system.
* Fixed bugs.
* Deployment-ready project.

---

# 9. SPRINT BACKLOG

## Sprint 1

* Database
* Folder Structure
* Database Connection
* CSS
* Homepage
* Session Configuration

## Sprint 2

* Registration
* Password Validation
* Password Hashing
* Login
* Admin Login
* Logout
* Sessions

## Sprint 3

* Voting Page
* Position Display
* Candidate Display
* Vote Validation
* Vote Submission
* Duplicate Vote Prevention

## Sprint 4

* Admin Dashboard
* Voter CRUD
* Candidate CRUD
* Position CRUD
* Candidate Photo Upload

## Sprint 5

* Election Control
* Start Election
* Stop Election
* Reset Election
* Results
* Vote Percentages
* Reports

## Sprint 6

* Testing
* Security Testing
* Bug Fixing
* Responsive Testing
* Deployment
* Documentation

---

# 10. FUNCTIONAL REQUIREMENTS

## Voter Module

The voter module shall allow users to:

* Register an account.
* Enter the required registration information.
* Create a password that meets the password requirements.
* Log in securely.
* View their dashboard.
* View available election positions.
* View available candidates.
* Submit their votes.
* Vote only once.
* View election results.
* Log out.

## Admin Module

The administrator module shall allow administrators to:

* Log in securely.
* Access the administrator dashboard.
* Manage voter accounts.
* Approve voter accounts.
* Manage candidates.
* Manage election positions.
* Upload candidate images.
* Start the election.
* Stop the election.
* Start the election even if some positions do not have candidates.
* Reset election results.
* View election results.
* Monitor voting statistics.

---

# 11. NON-FUNCTIONAL REQUIREMENTS

## Performance

The system should provide:

* Fast page loading.
* Efficient SQL queries.
* Efficient vote processing.
* Fast result calculation.

## Security

The system shall use:

* Password hashing.
* Password validation.
* Prepared statements.
* Session authentication.
* Input validation.
* Output escaping.
* SQL injection prevention.
* Duplicate-vote prevention.

## Reliability

The system should:

* Prevent duplicate votes.
* Record votes accurately.
* Maintain accurate vote counts.
* Maintain database consistency.
* Properly enforce election status.

## Usability

The system should provide:

* Responsive design.
* Black-and-white interface.
* Simple navigation.
* Clear validation messages.
* Easy-to-use voting controls.

## Compatibility

The system should support:

* Windows.
* XAMPP.
* PHP 8+.
* MySQL.
* Modern web browsers.
* Desktop devices.
* Tablet devices.
* Mobile devices.

---

# 12. SYSTEM MODULES

## Public Module

The Public Module includes:

* Landing Page
* Election Information
* Results Page

## Voter Module

The Voter Module includes:

* Registration
* Login
* Dashboard
* Candidate Viewing
* Voting
* Results
* Logout

## Administrator Module

The Administrator Module includes:

* Admin Login
* Dashboard
* Voter Management
* Candidate Management
* Position Management
* Candidate Photo Management
* Election Management
* Results
* Voting Statistics

---

# 13. DATABASE TABLES

| Table             | Description                          |
| ----------------- | ------------------------------------ |
| admins            | Stores administrator accounts.       |
| voters            | Stores registered voter information. |
| candidates        | Stores candidate information.        |
| positions         | Stores election positions.           |
| votes             | Stores submitted votes.              |
| election_settings | Stores election status and schedule. |

---

# 14. ACCEPTANCE CRITERIA

The system will be considered complete when:

* Users can register successfully.
* Registration information is properly validated.
* Password validation is enforced.
* Passwords are securely stored using `password_hash()`.
* Users can log in securely.
* Administrators can log in securely.
* Sessions protect authenticated pages.
* Only approved voters can vote.
* Each voter can vote only once.
* Votes are saved correctly.
* Administrators can perform CRUD operations.
* Administrators can manage voters.
* Administrators can manage candidates.
* Administrators can manage positions.
* Candidate photo uploads work properly.
* Administrators can start and stop the election.
* Administrators can start the election even if some positions do not have candidates.
* Voters cannot vote when the election is closed.
* Election results update correctly.
* Vote percentages are calculated correctly.
* Administrators can reset election results.
* Responsive design works on desktop, tablet, and mobile devices.
* The system does not require JavaScript for its core functionality.
* SQL injection is prevented through prepared statements.
* Passwords are not stored as plain text.

---

# 15. DEFINITION OF DONE

A feature is considered complete when:

* The code is fully functional.
* Required validation is implemented.
* Security measures are applied.
* Database operations are successful.
* No major bugs remain.
* The feature has been tested successfully.
* The feature works correctly with other system modules.
* Responsive design has been verified.
* Authentication and authorization work correctly.
* Documentation has been updated.
* The feature is ready for deployment using XAMPP.

---

# 16. EXPECTED OUTCOME

The **Online Voting System 3** is expected to provide a complete, secure, reliable, and responsive electronic voting platform. The system allows registered and approved voters to authenticate their accounts, view available candidates, and submit their votes electronically while preventing duplicate voting.

Administrators can manage voters, candidates, positions, election settings, and election results through a dedicated administration module. The system also allows administrators to start an election even when some positions do not have candidates, ensuring that the election can proceed without requiring every position to be filled.

The system incorporates password validation, password hashing, prepared statements, session authentication, input validation, and vote restrictions to help protect user accounts and election information. Overall, the project provides an organized web-based voting solution suitable for an academic Information Systems or Software Engineering project using the Agile development methodology.

