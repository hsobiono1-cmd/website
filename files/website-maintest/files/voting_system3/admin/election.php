<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireAdminLogin();

$error   = '';
$success = '';
$status  = getElectionStatus($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_req = $_POST['election_action'] ?? '';

    if ($action_req === 'start') {
        $r = mysqli_query($conn, "UPDATE election_status SET status = 'active'");
        if ($r) { $success = 'Election started successfully.'; $status = 'active'; }
        else $error = 'Failed to start election.';

    } elseif ($action_req === 'end') {
        $r = mysqli_query($conn, "UPDATE election_status SET status = 'ended'");
        if ($r) { $success = 'Election ended.'; $status = 'ended'; }
        else $error = 'Failed to end election.';

    } elseif ($action_req === 'reset') {
        // Reset: delete all votes, reset has_voted, set status inactive
        mysqli_query($conn, "DELETE FROM votes");
        mysqli_query($conn, "UPDATE voters SET has_voted = 0");
        mysqli_query($conn, "UPDATE election_status SET status = 'inactive'");
        $success = 'Election has been reset. All votes cleared.';
        $status = 'inactive';
    }
}

// Stats
$total_voters  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM voters"))['c'];
$voted_count   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM voters WHERE has_voted = 1"))['c'];
$total_votes   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM votes"))['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Control - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="admin-wrapper">
    <?php include 'includes/sidebar.php'; ?>
    <main class="admin-main">
        <div class="admin-topbar">
            <h2>Election Control</h2>
        </div>
        <div class="admin-body">
            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

            <!-- Current Status -->
            <div class="election-status-bar" style="margin-bottom:32px;">
                <div class="election-status-dot <?= $status ?>"></div>
                <span style="font-size:1.1rem;">Current Status: <strong>
                    <?php if($status === 'active'): ?>
                        <span class="text-success">ACTIVE — Voting is open</span>
                    <?php elseif($status === 'ended'): ?>
                        <span class="text-danger">ENDED — Voting is closed</span>
                    <?php else: ?>
                        <span class="text-muted">INACTIVE — Not started</span>
                    <?php endif; ?>
                </strong></span>
            </div>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?= $total_voters ?></div>
                    <div class="stat-label">Total Voters</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $voted_count ?></div>
                    <div class="stat-label">Voted</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $total_voters - $voted_count ?></div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $total_votes ?></div>
                    <div class="stat-label">Total Ballots</div>
                </div>
            </div>

            <!-- Controls -->
            <div class="card">
                <div class="card-header"><h3>Election Controls</h3></div>
                <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:20px;">

                    <!-- Start -->
                    <div class="card" style="margin:0;">
                        <div style="font-size:2rem; margin-bottom:12px;">▶️</div>
                        <h3 style="margin-bottom:8px;">Start Election</h3>
                        <p class="text-muted" style="font-size:0.85rem; margin-bottom:16px;">Open voting for all registered voters.</p>
                        <?php if ($status === 'inactive'): ?>
                            <form method="POST">
                                <input type="hidden" name="election_action" value="start">
                                <button type="submit" class="btn btn-success btn-block">Start Election</button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-outline btn-block" disabled>
                                <?= $status === 'active' ? 'Already Active' : 'Election Ended' ?>
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- End -->
                    <div class="card" style="margin:0;">
                        <div style="font-size:2rem; margin-bottom:12px;">⏹️</div>
                        <h3 style="margin-bottom:8px;">End Election</h3>
                        <p class="text-muted" style="font-size:0.85rem; margin-bottom:16px;">Close voting. Results will still be visible.</p>
                        <?php if ($status === 'active'): ?>
                            <form method="POST">
                                <input type="hidden" name="election_action" value="end">
                                <button type="submit" class="btn btn-warning btn-block">End Election</button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-outline btn-block" disabled>
                                <?= $status === 'inactive' ? 'Not Started' : 'Already Ended' ?>
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Reset -->
                    <div class="card" style="margin:0;">
                        <div style="font-size:2rem; margin-bottom:12px;">🔄</div>
                        <h3 style="margin-bottom:8px;">Reset Election</h3>
                        <p class="text-muted" style="font-size:0.85rem; margin-bottom:16px;">Clear ALL votes and reset voter status. Irreversible!</p>
                        <a href="#confirm-reset" class="btn btn-danger btn-block">Reset Election</a>
                    </div>
                </div>

                <!-- View Results Link -->
                <div style="margin-top:24px; padding-top:20px; border-top:1px solid var(--border);">
                    <a href="results.php" class="btn btn-outline">📊 View Full Results</a>
                </div>
            </div>

            <!-- Reset Confirm Modal -->
            <div class="modal-overlay" id="confirm-reset">
                <div class="modal-box">
                    <h3>⚠️ Confirm Election Reset</h3>
                    <p>This will permanently delete ALL votes, reset all voter statuses to "not voted", and set the election back to Inactive. <strong>This cannot be undone.</strong></p>
                    <div class="modal-actions">
                        <a href="#" class="btn btn-outline">Cancel</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="election_action" value="reset">
                            <button type="submit" class="btn btn-danger">Yes, Reset Everything</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
