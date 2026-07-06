<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <h1>⚙️ ADMIN</h1>
        <p>Voting System</p>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-nav-section">Overview</div>
        <a href="dashboard.php" class="<?= $current === 'dashboard.php' ? 'active' : '' ?>">
            <span class="nav-icon">📊</span> Dashboard
        </a>

        <div class="sidebar-nav-section">Manage</div>
        <a href="voters.php" class="<?= $current === 'voters.php' ? 'active' : '' ?>">
            <span class="nav-icon">👥</span> Voters
        </a>
        <a href="candidates.php" class="<?= $current === 'candidates.php' ? 'active' : '' ?>">
            <span class="nav-icon">🧑</span> Candidates
        </a>
        <a href="positions.php" class="<?= $current === 'positions.php' ? 'active' : '' ?>">
            <span class="nav-icon">📋</span> Positions
        </a>

        <div class="sidebar-nav-section">Election</div>
        <a href="election.php" class="<?= $current === 'election.php' ? 'active' : '' ?>">
            <span class="nav-icon">🗳️</span> Election Control
        </a>
        <a href="results.php" class="<?= $current === 'results.php' ? 'active' : '' ?>">
            <span class="nav-icon">📈</span> Results
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="logout.php">
            <span class="nav-icon">🚪</span> Logout
        </a>
    </div>
</aside>
