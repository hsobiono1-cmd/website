<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireVoterLogin();

// Get total votes cast
$total_votes_result = mysqli_query($conn, "SELECT COUNT(DISTINCT voter_id) AS total FROM votes");
$total_voters_row = mysqli_fetch_assoc($total_votes_result);
$total_cast = $total_voters_row['total'];

// Fetch positions and results
$positions_result = mysqli_query($conn, "SELECT * FROM positions ORDER BY id ASC");
$positions_data = [];
while ($pos = mysqli_fetch_assoc($positions_result)) {
    $pid = $pos['id'];

    // Get total votes for this position
    $tv_stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS cnt FROM votes WHERE position_id = ?");
    mysqli_stmt_bind_param($tv_stmt, 'i', $pid);
    mysqli_stmt_execute($tv_stmt);
    $tv_res = mysqli_stmt_get_result($tv_stmt);
    $tv_row = mysqli_fetch_assoc($tv_res);
    $position_total = $tv_row['cnt'];
    mysqli_stmt_close($tv_stmt);

    // Get candidates with vote counts
    $cstmt = mysqli_prepare($conn, "
        SELECT c.id, c.first_name, c.last_name, c.photo, c.description,
               COUNT(v.id) AS vote_count
        FROM candidates c
        LEFT JOIN votes v ON v.candidate_id = c.id AND v.position_id = ?
        WHERE c.position_id = ?
        GROUP BY c.id
        ORDER BY vote_count DESC
    ");
    mysqli_stmt_bind_param($cstmt, 'ii', $pid, $pid);
    mysqli_stmt_execute($cstmt);
    $cres = mysqli_stmt_get_result($cstmt);
    $candidates = [];
    while ($c = mysqli_fetch_assoc($cres)) {
        $c['percentage'] = $position_total > 0 ? round(($c['vote_count'] / $position_total) * 100, 1) : 0;
        $candidates[] = $c;
    }
    mysqli_stmt_close($cstmt);

    $pos['candidates'] = $candidates;
    $pos['total_votes'] = $position_total;
    $positions_data[] = $pos;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results - Voting System</title>
    <link rel="stylesheet" href="css/style.css">
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
        <h1>Election Results</h1>
        <p>Live vote counts and standings for all positions.</p>
    </div>

    <div class="stats-grid" style="max-width:400px;">
        <div class="stat-card">
            <div class="stat-number"><?= $total_cast ?></div>
            <div class="stat-label">Votes Cast</div>
        </div>
    </div>

    <?php foreach ($positions_data as $pos): ?>
    <div class="results-position card">
        <div class="card-header">
            <h3><?= htmlspecialchars($pos['position_name']) ?></h3>
            <span class="text-muted" style="font-size:0.82rem;"><?= $pos['total_votes'] ?> vote(s)</span>
        </div>

        <?php if (empty($pos['candidates'])): ?>
            <p class="text-muted">No candidates.</p>
        <?php else: ?>
            <?php foreach ($pos['candidates'] as $i => $c): ?>
            <div class="result-candidate">
                <?php if ($c['photo'] && $c['photo'] !== 'default.png' && file_exists('uploads/candidates/' . $c['photo'])): ?>
                    <img src="uploads/candidates/<?= htmlspecialchars($c['photo']) ?>" class="result-candidate-photo" alt="">
                <?php else: ?>
                    <div class="result-candidate-photo" style="display:flex;align-items:center;justify-content:center;font-size:1.2rem;">👤</div>
                <?php endif; ?>
                <div class="result-candidate-info">
                    <div class="result-candidate-name">
                        <?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?>
                        <?php if ($i === 0 && $c['vote_count'] > 0): ?>
                            <span class="badge badge-success">LEADING</span>
                        <?php endif; ?>
                    </div>
                    <div class="result-bar-wrap">
                        <div class="progress">
                            <div class="progress-bar" style="width:<?= $c['percentage'] ?>%"></div>
                        </div>
                        <span class="result-votes"><?= $c['vote_count'] ?> vote(s) — <?= $c['percentage'] ?>%</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>
</body>
</html>
