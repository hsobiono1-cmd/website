<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireVoterLogin();

$status    = getElectionStatus($conn);
$has_voted = $_SESSION['has_voted'];

// Refresh has_voted from DB
$stmt = mysqli_prepare($conn, "SELECT has_voted FROM voters WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $_SESSION['voter_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$voter = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
$has_voted = $voter['has_voted'];
$_SESSION['has_voted'] = $has_voted;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Voting System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <a href="dashboard.php" class="navbar-brand">VOTE<span>SYSTEM</span></a>
    <ul class="navbar-nav">
        <li><a href="results.php">Results</a></li>
        <li><a href="logout.php" class="btn-nav">Logout</a></li>
    </ul>
</nav>

<div class="main-content container">
    <div class="page-header">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['voter_name']) ?> 👋</h1>
        <p>Voter ID: <strong><?= htmlspecialchars($_SESSION['voter_uid']) ?></strong></p>
    </div>

    <!-- Election Status -->
    <div class="election-status-bar">
        <div class="election-status-dot <?= $status ?>"></div>
        <div>
            <strong>Election Status:</strong>
            <?php if($status === 'active'): ?>
                <span class="text-success">ACTIVE — Voting is open</span>
            <?php elseif($status === 'ended'): ?>
                <span class="text-danger">ENDED — Voting is closed</span>
            <?php else: ?>
                <span class="text-muted">INACTIVE — Voting has not started yet</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Vote Status -->
    <?php if ($has_voted): ?>
        <div class="alert alert-success">✓ You have already cast your vote. Thank you for participating!</div>
    <?php endif; ?>

    <!-- Dashboard Cards -->
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="dc-icon">🗳️</div>
            <h3>Cast Your Vote</h3>
            <p>Vote for your preferred candidates in this election.</p>
            <?php if ($status === 'active' && !$has_voted): ?>
                <a href="vote.php" class="btn btn-primary btn-block">Vote Now</a>
            <?php elseif ($has_voted): ?>
                <button class="btn btn-outline btn-block" disabled>Already Voted</button>
            <?php else: ?>
                <button class="btn btn-outline btn-block" disabled>Voting <?= $status === 'ended' ? 'Ended' : 'Not Started' ?></button>
            <?php endif; ?>
        </div>

        <div class="dashboard-card">
            <div class="dc-icon">📊</div>
            <h3>View Results</h3>
            <p>See the current vote counts and standings for all positions.</p>
            <a href="results.php" class="btn btn-outline btn-block">View Results</a>
        </div>

        <div class="dashboard-card">
            <div class="dc-icon">👤</div>
            <h3>My Account</h3>
            <p>Logged in as <strong><?= htmlspecialchars($_SESSION['voter_name']) ?></strong></p>
            <a href="logout.php" class="btn btn-danger btn-block">Logout</a>
        </div>
    </div>
</div>
</body>
</html>
