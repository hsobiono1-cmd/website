<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'voting_system3');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die('<div style="background:#000;color:#fff;font-family:Arial;padding:40px;text-align:center;">
        <h2>Database Connection Failed</h2>
        <p>' . mysqli_connect_error() . '</p>
        <p>Make sure XAMPP MySQL is running and the database <strong>voting_system3</strong> exists.</p>
    </div>');
}

mysqli_set_charset($conn, 'utf8mb4');
