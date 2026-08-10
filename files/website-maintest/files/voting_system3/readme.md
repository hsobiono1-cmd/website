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
6. Public Information Officer
