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



# Agile Documentation

## Project Title

**Online Voting System 3**

---

# 1. Project Vision

## Vision Statement

The **Online Voting System 3** aims to provide a secure, reliable, and user-friendly web-based voting platform that allows registered voters to cast their votes electronically while enabling administrators to efficiently manage elections, candidates, positions, voters, and election results. The system replaces traditional paper-based voting with a digital solution that ensures fairness, transparency, and accurate vote counting.

---

# 2. Project Objectives

* Develop a secure online voting application using PHP and MySQL.
* Allow voters to register and authenticate securely.
* Prevent duplicate voting.
* Enable administrators to manage the election process.
* Display live election results.
* Maintain data integrity and security using prepared statements and password hashing.
* Create a responsive interface without JavaScript.

---

# 3. Product Backlog (User Stories)

| ID    | User Story                                                                      | Priority | Story Points |
| ----- | ------------------------------------------------------------------------------- | -------- | ------------ |
| US-01 | As a voter, I want to register an account so I can participate in the election. | High     | 5            |
| US-02 | As a voter, I want to log in securely using my username or voter ID.            | High     | 5            |
| US-03 | As a voter, I want my password to be encrypted for security.                    | High     | 3            |
| US-04 | As a voter, I want to vote only once so the election remains fair.              | High     | 8            |
| US-05 | As a voter, I want to view the list of candidates by position.                  | High     | 5            |
| US-06 | As a voter, I want to submit my votes successfully.                             | High     | 8            |
| US-07 | As an administrator, I want to manage voter accounts.                           | High     | 8            |
| US-08 | As an administrator, I want to manage candidates.                               | High     | 8            |
| US-09 | As an administrator, I want to manage election positions.                       | High     | 5            |
| US-10 | As an administrator, I want to upload candidate photos.                         | Medium   | 5            |
| US-11 | As an administrator, I want to start or stop an election.                       | High     | 5            |
| US-12 | As an administrator, I want to reset election results.                          | High     | 5            |
| US-13 | As a user, I want to view election results after voting.                        | Medium   | 5            |
| US-14 | As an administrator, I want to monitor voting statistics.                       | Medium   | 3            |
| US-15 | As an administrator, I want secure session authentication.                      | High     | 5            |

---

# 4. Product Backlog Prioritization

## Must Have

* User Registration
* Login Authentication
* Password Hashing
* Voting Module
* One Vote Restriction
* Admin Login
* Candidate Management
* Position Management
* Election Control
* Results Module

## Should Have

* Candidate Photo Upload
* Responsive Design
* Public Results

## Could Have

* Election Statistics
* Dashboard Summary

---

# 5. Database Design

## Database Name

**voting_system3**

### Database Tables

| Table             | Description                         |
| ----------------- | ----------------------------------- |
| admins            | Stores administrator accounts       |
| voters            | Stores voter information            |
| positions         | Stores election positions           |
| candidates        | Stores candidate information        |
| votes             | Stores voting records               |
| election_settings | Stores election status and schedule |

---

## Database Structure

### admins

| Field      | Type         |
| ---------- | ------------ |
| id         | INT (PK)     |
| username   | VARCHAR(100) |
| password   | VARCHAR(255) |
| created_at | TIMESTAMP    |

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

### positions

| Field         | Type         |
| ------------- | ------------ |
| id            | INT (PK)     |
| position_name | VARCHAR(100) |

#### Positions

* President
* Vice President
* Secretary
* Treasurer
* Auditor

### candidates

| Field       | Type         |
| ----------- | ------------ |
| id          | INT (PK)     |
| position_id | INT (FK)     |
| fullname    | VARCHAR(150) |
| photo       | VARCHAR(255) |
| description | TEXT         |

#### Candidates

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

### votes

| Field        | Type      |
| ------------ | --------- |
| id           | INT (PK)  |
| voter_id     | INT (FK)  |
| candidate_id | INT (FK)  |
| position_id  | INT (FK)  |
| voted_at     | TIMESTAMP |

### election_settings

| Field           | Type                  |
| --------------- | --------------------- |
| id              | INT (PK)              |
| election_status | ENUM('Open','Closed') |
| start_date      | DATETIME              |
| end_date        | DATETIME              |

---

## Database Relationships

```text
admins

voters
   │
   ├───────────────┐
   │               │
positions      election_settings
   │
   │
candidates
   │
   │
votes
```

### Foreign Keys

* candidates.position_id → positions.id
* votes.voter_id → voters.id
* votes.candidate_id → candidates.id
* votes.position_id → positions.id

---

# 6. Sprint Plan

## Sprint 1 – Project Setup (Week 1)

### Goal

Build the foundation of the Online Voting System.

### Tasks

* Create database
* Create project folders
* Configure database connection
* Design responsive layout
* Landing page
* Session configuration

### Deliverables

* Database connected
* Home page completed
* Responsive layout

---

## Sprint 2 – Authentication Module (Week 2)

### Goal

Develop secure authentication.

### Tasks

* Voter Registration
* Password validation
* Password hashing
* Login
* Logout
* Session management

### Deliverables

* Registration completed
* Login system completed
* Authentication secured

---

## Sprint 3 – Voting Module (Week 3)

### Goal

Allow voters to cast votes.

### Tasks

* Display positions
* Display candidates
* Vote validation
* Prevent duplicate voting
* Save votes

### Deliverables

* Voting page
* Vote recording
* One vote enforcement

---

## Sprint 4 – Admin Module (Week 4)

### Goal

Create administrator functions.

### Tasks

* Admin Login
* Dashboard
* CRUD Voters
* CRUD Candidates
* CRUD Positions
* Upload Candidate Photos

### Deliverables

* Complete admin panel

---

## Sprint 5 – Election Management (Week 5)

### Goal

Control election activities.

### Tasks

* Start Election
* End Election
* Reset Election
* Live Results
* Vote Percentages

### Deliverables

* Election controls
* Live results page

---

## Sprint 6 – Testing & Deployment (Week 6)

### Goal

Finalize the system.

### Tasks

* Functional testing
* Security testing
* Bug fixing
* UI improvements
* Final deployment using XAMPP

### Deliverables

* Working Online Voting System

---

# 7. Sprint Backlog

## Sprint 1

* Database
* Folder Structure
* CSS
* Homepage

## Sprint 2

* Register
* Login
* Logout
* Sessions

## Sprint 3

* Voting Page
* Vote Validation
* Vote Submission

## Sprint 4

* Admin Dashboard
* CRUD Modules

## Sprint 5

* Election Control
* Results
* Reports

## Sprint 6

* Testing
* Deployment
* Documentation

---

# 8. Functional Requirements

## Voter Module

* Register account
* Login
* View dashboard
* Vote once only
* View election results
* Logout

## Admin Module

* Login
* Dashboard
* Manage voters
* Manage candidates
* Manage positions
* Upload candidate images
* Start election
* End election
* Reset election
* View results

---

# 9. Non-Functional Requirements

## Performance

* Fast page loading
* Efficient SQL queries

## Security

* Password hashing
* Prepared statements
* Session authentication
* SQL injection prevention

## Reliability

* Prevent duplicate votes
* Accurate vote counting

## Usability

* Responsive design
* Black and white interface
* Easy navigation

## Compatibility

* Windows
* XAMPP
* PHP 8+
* MySQL
* Modern web browsers

---

# 10. System Modules

## Public Module

* Landing Page
* Results Page

## Voter Module

* Registration
* Login
* Dashboard
* Voting
* Logout

## Administrator Module

* Login
* Dashboard
* Voter Management
* Candidate Management
* Position Management
* Election Management
* Results

---

# 11. Database Tables

| Table             | Description                         |
| ----------------- | ----------------------------------- |
| admins            | Stores administrator accounts       |
| voters            | Stores registered voters            |
| candidates        | Stores candidate information        |
| positions         | Stores election positions           |
| votes             | Stores submitted votes              |
| election_settings | Stores election status and schedule |

---

# 12. Acceptance Criteria

The system is considered complete when:

* ✔ Users can register successfully.
* ✔ Password validation is enforced.
* ✔ Passwords are encrypted using `password_hash()`.
* ✔ Users can log in securely.
* ✔ Sessions protect authenticated pages.
* ✔ Each voter can vote only once.
* ✔ Votes are saved correctly.
* ✔ Administrators can perform CRUD operations.
* ✔ Election controls work correctly.
* ✔ Results update automatically.
* ✔ Candidate photo uploads function properly.
* ✔ Responsive design works on desktop, tablet, and mobile devices.
* ✔ No JavaScript is required.
* ✔ SQL injection attacks are prevented using prepared statements.

---

# 13. Definition of Done (DoD)

A feature is considered complete when:

* Code is fully functional.
* Validation is implemented.
* No major bugs remain.
* Database operations are successful.
* Responsive layout is verified.
* Security measures are applied.
* Feature has been tested successfully.
* Documentation is updated.
* Ready for deployment in XAMPP.

---

# 14. Expected Outcome

The **Online Voting System 3** provides a complete, secure, and responsive electronic voting platform where voters can register, authenticate, and cast their votes with confidence, while administrators can efficiently manage all aspects of the election process. The system ensures fair elections through one-vote enforcement, secure password handling, live vote counting, and comprehensive administrative controls, making it suitable as a complete academic Information Systems or Software Engineering project following the Agile development methodology.


