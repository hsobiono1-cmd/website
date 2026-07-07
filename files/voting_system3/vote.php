<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireVoterLogin();

$status = getElectionStatus($conn);

// Check if already voted (from DB)
$stmt = mysqli_prepare($conn, "SELECT has_voted FROM voters WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $_SESSION['voter_id']);
mysqli_stmt_execute($stmt);
$r = mysqli_stmt_get_result($stmt);
$v = mysqli_fetch_assoc($r);
mysqli_stmt_close($stmt);

if ($v['has_voted']) {
    header('Location: dashboard.php');
    exit;
}

if ($status !== 'active') {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

// Handle vote submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get all positions
    $pos_result = mysqli_query($conn, "SELECT id FROM positions");
    $positions = [];
    while ($row = mysqli_fetch_assoc($pos_result)) {
        $positions[] = $row['id'];
    }

    // Collect votes only for positions the user actually selected
    $votes = [];
    foreach ($positions as $pid) {
        $key = 'position_' . $pid;
        if (isset($_POST[$key]) && !empty($_POST[$key])) {
            $votes[$pid] = (int)$_POST[$key];
        }
    }

    // Ensure they voted for at least one candidate before submitting the ballot
    if (empty($votes)) {
        $error = 'Please select at least one candidate before submitting your ballot.';
    } else {
        // Verify each selected candidate belongs to the correct position
        $valid = true;
        foreach ($votes as $pid => $cid) {
            $stmt = mysqli_prepare($conn, "SELECT id FROM candidates WHERE id = ? AND position_id = ?");
            mysqli_stmt_bind_param($stmt, 'ii', $cid, $pid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) === 0) { $valid = false; }
            mysqli_stmt_close($stmt);
        }

        if (!$valid) {
            $error = 'Invalid vote submission. Please try again.';
        } else {
            // Insert votes
            mysqli_begin_transaction($conn);
            try {
                foreach ($votes as $pid => $cid) {
                    $stmt = mysqli_prepare($conn, "INSERT INTO votes (voter_id, candidate_id, position_id) VALUES (?,?,?)");
                    mysqli_stmt_bind_param($stmt, 'iii', $_SESSION['voter_id'], $cid, $pid);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                }
                // Mark voter as voted
                $stmt = mysqli_prepare($conn, "UPDATE voters SET has_voted = 1 WHERE id = ?");
                mysqli_stmt_bind_param($stmt, 'i', $_SESSION['voter_id']);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                mysqli_commit($conn);
                $_SESSION['has_voted'] = 1;
                $success = 'Your vote has been successfully recorded! Thank you for participating.';
            } catch (Exception $e) {
                mysqli_rollback($conn);
                $error = 'Voting failed. Please try again.';
            }
        }
    }
}

// Fetch positions with candidates
$positions_result = mysqli_query($conn, "SELECT * FROM positions ORDER BY id ASC");
$positions_data = [];
while ($pos = mysqli_fetch_assoc($positions_result)) {
    $pid = $pos['id'];
    $cstmt = mysqli_prepare($conn, "SELECT * FROM candidates WHERE position_id = ? ORDER BY last_name ASC");
    mysqli_stmt_bind_param($cstmt, 'i', $pid);
    mysqli_stmt_execute($cstmt);
    $cres = mysqli_stmt_get_result($cstmt);
    $candidates = [];
    while ($c = mysqli_fetch_assoc($cres)) {
        $candidates[] = $c;
    }
    mysqli_stmt_close($cstmt);
    $pos['candidates'] = $candidates;
    $positions_data[] = $pos;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote - Voting System</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Radio card toggle via CSS hack */
        .candidate-card { user-select: none; }
        .candidate-radio:checked + .candidate-card-label { border-color: #fff; box-shadow: 0 0 0 1px #fff; background: var(--surface2); }
        .candidate-radio:checked + .candidate-card-label .candidate-check { background: #fff; color: #000; border-color: #fff; }
    </style>
</head>
<body>
<nav class="navbar">
    <a href="dashboard.php" class="navbar-brand">VOTE<span>SYSTEM</span></a>
    <ul class="navbar-nav">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="logout.php" class="btn-nav">Logout</a></li>
    </ul>
</nav>

<div class="main-content container">
    <div class="page-header">
        <h1>Cast Your Vote</h1>
        <p>Select your preferred candidates. You may leave positions blank if you wish to abstain. Review your choices before submitting.</p>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?> <a href="dashboard.php">← Back to Dashboard</a></div>
    <?php else: ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <?php foreach ($positions_data as $pos): ?>
        <div class="position-section">
            <div class="card">
                <div class="card-header">
                    <h3 class="position-title" style="border:none;margin:0;padding:0;"><?= htmlspecialchars($pos['position_name']) ?></h3>
                    <span class="badge badge-info">Choose 1 (Optional)</span>
                </div>

                <?php if (empty($pos['candidates'])): ?>
                    <p class="text-muted">No candidates for this position.</p>
                <?php else: ?>
                <div class="candidates-grid">
                    <?php foreach ($pos['candidates'] as $c): ?>
                    <label style="display:block; cursor:pointer; position:relative;">
                        <!-- REMOVED "required" attribute here -->
                        <input type="radio" name="position_<?= $pos['id'] ?>" value="<?= $c['id'] ?>"
                               class="candidate-radio" style="position:absolute;opacity:0;pointer-events:none;">
                        <div class="candidate-card candidate-card-label">
                            <div class="candidate-check">✓</div>
                            <?php if ($c['photo'] && $c['photo'] !== 'default.png' && file_exists('uploads/candidates/' . $c['photo'])): ?>
                                <img src="uploads/candidates/<?= htmlspecialchars($c['photo']) ?>" alt="" class="candidate-photo">
                            <?php else: ?>
                                <div class="candidate-photo-placeholder">👤</div>
                            <?php endif; ?>
                            <div class="candidate-name"><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></div>
                            <?php if ($c['description']): ?>
                                <div class="candidate-desc"><?= htmlspecialchars(mb_strimwidth($c['description'], 0, 80, '...')) ?></div>
                            <?php endif; ?>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="card" style="text-align:center;">
            <p class="text-muted mb-2">Please review your selections before submitting. <strong>Your vote cannot be changed once submitted.</strong></p>
            <button type="submit" class="btn btn-primary btn-lg">Submit My Vote</button>
            <a href="dashboard.php" class="btn btn-outline btn-lg" style="margin-left:12px;">Cancel</a>
        </div>
    </form>

    <?php endif; ?>
</div>
</body>
</html>