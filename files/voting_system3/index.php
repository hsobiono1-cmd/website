<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (isset($_SESSION['voter_id'])) {
    header('Location: dashboard.php');
    exit;
}

$status = getElectionStatus($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Voting System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="navbar-brand">VOTE<span>SYSTEM</span></div>
    <ul class="navbar-nav">
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php" class="btn-nav">Register</a></li>
    </ul>
</nav>

<div class="main-content container" style="text-align:center; padding-top:80px;">
    <div style="max-width:600px; margin:0 auto;">
        <div style="font-size:4rem; margin-bottom:24px;">🗳️</div>
        <h1 style="font-size:2.5rem; letter-spacing:4px; text-transform:uppercase; margin-bottom:16px;">Online Voting System</h1>
        <p style="font-size:1.1rem; color:var(--text-muted); margin-bottom:12px;">A secure, transparent, and modern electronic voting platform.</p>

        <div class="election-status-bar" style="justify-content:center; margin:32px 0;">
            <div class="election-status-dot <?= $status ?>"></div>
            <span>Election Status: <strong>
                <?php if($status === 'active'): ?>
                    <span class="text-success">ACTIVE</span>
                <?php elseif($status === 'ended'): ?>
                    <span class="text-danger">ENDED</span>
                <?php else: ?>
                    <span class="text-muted">INACTIVE</span>
                <?php endif; ?>
            </strong></span>
        </div>

        <div style="display:flex; gap:16px; justify-content:center; flex-wrap:wrap;">
            <a href="login.php" class="btn btn-outline btn-lg">Login to Vote</a>
            <a href="register.php" class="btn btn-primary btn-lg">Register</a>
        </div>

        <p style="margin-top:40px; color:var(--text-dim); font-size:0.85rem;">
            <a href="admin/login.php" style="color:var(--text-dim);">Admin Panel →</a>
        </p>
    </div>
</div>
</body>
</html>
