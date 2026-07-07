<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireAdminLogin();

$total_voters     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM voters"))['c'];
$total_candidates = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM candidates"))['c'];
$total_positions  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM positions"))['c'];
$total_votes      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT voter_id) AS c FROM votes"))['c'];
$status           = getElectionStatus($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Voting System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="admin-wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-topbar">
            <h2>Dashboard</h2>
            <span class="text-muted" style="font-size:0.85rem;">Welcome, <strong><?= htmlspecialchars($_SESSION['admin_user']) ?></strong></span>
        </div>

        <div class="admin-body">
            <!-- Election Status -->
            <div class="election-status-bar">
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
                <a href="election.php" class="btn btn-outline btn-sm" style="margin-left:auto;">Manage Election</a>
            </div>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?= $total_voters ?></div>
                    <div class="stat-label">Total Voters</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $total_candidates ?></div>
                    <div class="stat-label">Candidates</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $total_positions ?></div>
                    <div class="stat-label">Positions</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $total_votes ?></div>
                    <div class="stat-label">Votes Cast</div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card">
                <div class="card-header"><h3>Quick Actions</h3></div>
                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <a href="voters.php?action=add" class="btn btn-outline">+ Add Voter</a>
                    <a href="candidates.php?action=add" class="btn btn-outline">+ Add Candidate</a>
                    <a href="positions.php?action=add" class="btn btn-outline">+ Add Position</a>
                    <a href="election.php" class="btn btn-outline">⚡ Election Control</a>
                    <a href="results.php" class="btn btn-outline">📊 View Results</a>
                </div>
            </div>

            <!-- Recent Voters -->
            <div class="card">
                <div class="card-header">
                    <h3>Recent Voters</h3>
                    <a href="voters.php" class="btn btn-outline btn-sm">View All</a>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Voter ID</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $rv = mysqli_query($conn, "SELECT * FROM voters ORDER BY created_at DESC LIMIT 5");
                        while ($row = mysqli_fetch_assoc($rv)):
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($row['voter_id']) ?></td>
                                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td>
                                    <?php if ($row['has_voted']): ?>
                                        <span class="badge badge-success">Voted</span>
                                    <?php else: ?>
                                        <span class="badge badge-info">Not Yet</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
