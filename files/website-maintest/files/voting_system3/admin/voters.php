<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireAdminLogin();

$action  = $_GET['action'] ?? 'list';
$edit_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error   = '';
$success = '';

// Handle DELETE
if ($action === 'delete' && $edit_id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM voters WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $edit_id);
    if (mysqli_stmt_execute($stmt)) {
        $success = 'Voter deleted successfully.';
    } else {
        $error = 'Failed to delete voter.';
    }
    mysqli_stmt_close($stmt);
    $action = 'list';
}

// Handle ADD / EDIT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $voter_id   = trim($_POST['voter_id'] ?? '');
    $birthdate  = trim($_POST['birthdate'] ?? '');
    $address    = trim($_POST['address'] ?? '');
    $username   = trim($_POST['username'] ?? '');
    $password   = $_POST['password'] ?? '';
    $form_id    = (int)($_POST['form_id'] ?? 0);

    if (!$first_name || !$last_name || !$voter_id || !$birthdate || !$address || !$username) {
        $error = 'All fields except password are required.';
    } else {
        if ($form_id) {
            // UPDATE
            if ($password) {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = mysqli_prepare($conn, "UPDATE voters SET voter_id=?, first_name=?, last_name=?, birthdate=?, address=?, username=?, password=? WHERE id=?");
                mysqli_stmt_bind_param($stmt, 'sssssssi', $voter_id, $first_name, $last_name, $birthdate, $address, $username, $hashed, $form_id);
            } else {
                $stmt = mysqli_prepare($conn, "UPDATE voters SET voter_id=?, first_name=?, last_name=?, birthdate=?, address=?, username=? WHERE id=?");
                mysqli_stmt_bind_param($stmt, 'ssssssi', $voter_id, $first_name, $last_name, $birthdate, $address, $username, $form_id);
            }
            if (mysqli_stmt_execute($stmt)) {
                $success = 'Voter updated successfully.';
                $action = 'list';
            } else {
                $error = 'Update failed: ' . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            // INSERT
            if (!$password) { 
                $error = 'Password is required for new voter.'; 
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                
                // Check duplicates
                $chk = mysqli_prepare($conn, "SELECT id FROM voters WHERE voter_id = ? OR username = ?");
                mysqli_stmt_bind_param($chk, 'ss', $voter_id, $username);
                mysqli_stmt_execute($chk);
                mysqli_stmt_store_result($chk);
                
                if (mysqli_stmt_num_rows($chk) > 0) {
                    $error = 'Voter ID or username already exists.';
                } else {
                    $stmt = mysqli_prepare($conn, "INSERT INTO voters (voter_id, first_name, last_name, birthdate, address, username, password) VALUES (?,?,?,?,?,?,?)");
                    mysqli_stmt_bind_param($stmt, 'sssssss', $voter_id, $first_name, $last_name, $birthdate, $address, $username, $hashed);
                    if (mysqli_stmt_execute($stmt)) {
                        $success = 'Voter added successfully.';
                        $action = 'list';
                    } else {
                        $error = 'Insert failed: ' . mysqli_error($conn);
                    }
                    mysqli_stmt_close($stmt);
                }
                // Close $chk ONCE here, covering both the if and else conditions above
                if (isset($chk)) {
                    mysqli_stmt_close($chk);
                }
            }
        }
    }
}

// Load edit data
$edit_voter = null;
if ($action === 'edit' && $edit_id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM voters WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $edit_id);
    mysqli_stmt_execute($stmt);
    $edit_voter = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    if (!$edit_voter) { $action = 'list'; }
}

// Search + List
$search = trim($_GET['search'] ?? '');
if ($search) {
    $like = '%' . $search . '%';
    $stmt = mysqli_prepare($conn, "SELECT * FROM voters WHERE first_name LIKE ? OR last_name LIKE ? OR voter_id LIKE ? ORDER BY created_at DESC");
    mysqli_stmt_bind_param($stmt, 'sss', $like, $like, $like);
    mysqli_stmt_execute($stmt);
    $voters_result = mysqli_stmt_get_result($stmt);
} else {
    $voters_result = mysqli_query($conn, "SELECT * FROM voters ORDER BY created_at DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voters - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="admin-wrapper">
    <?php include 'includes/sidebar.php'; ?>
    <main class="admin-main">
        <div class="admin-topbar">
            <h2>Voter Management</h2>
            <a href="voters.php?action=add" class="btn btn-primary btn-sm">+ Add Voter</a>
        </div>
        <div class="admin-body">
            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

            <?php if ($action === 'add' || $action === 'edit'): ?>
            <div class="card">
                <div class="card-header">
                    <h3><?= $action === 'edit' ? 'Edit Voter' : 'Add New Voter' ?></h3>
                    <a href="voters.php" class="btn btn-outline btn-sm">← Back</a>
                </div>
                <form method="POST" action="voters.php">
                    <?php if ($edit_voter): ?><input type="hidden" name="form_id" value="<?= $edit_voter['id'] ?>"><?php endif; ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($edit_voter['first_name'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($edit_voter['last_name'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Voter ID</label>
                            <input type="text" name="voter_id" class="form-control" value="<?= htmlspecialchars($edit_voter['voter_id'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Birthdate</label>
                            <input type="date" name="birthdate" class="form-control" value="<?= htmlspecialchars($edit_voter['birthdate'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control"><?= htmlspecialchars($edit_voter['address'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($edit_voter['username'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Password <?= $action === 'edit' ? '(leave blank to keep current)' : '' ?></label>
                        <input type="password" name="password" class="form-control" <?= $action === 'add' ? 'required' : '' ?> minlength="6">
                    </div>
                    <div style="display:flex; gap:12px;">
                        <button type="submit" class="btn btn-primary"><?= $action === 'edit' ? 'Update Voter' : 'Add Voter' ?></button>
                        <a href="voters.php" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
            <?php else: ?>
            <div class="card">
                <div class="card-header">
                    <h3>All Voters</h3>
                    <form method="GET" action="voters.php" class="search-form" style="margin:0;">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or ID..." value="<?= htmlspecialchars($search) ?>">
                        <button type="submit" class="btn btn-outline btn-sm">Search</button>
                        <?php if ($search): ?><a href="voters.php" class="btn btn-outline btn-sm">Clear</a><?php endif; ?>
                    </form>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Voter ID</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Birthdate</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($voters_result)):
                        ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['voter_id']) ?></td>
                                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= htmlspecialchars($row['birthdate']) ?></td>
                                <td>
                                    <?php if ($row['has_voted']): ?>
                                        <span class="badge badge-success">Voted</span>
                                    <?php else: ?>
                                        <span class="badge badge-info">Not Yet</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="td-actions">
                                        <a href="voters.php?action=edit&id=<?= $row['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
                                        <a href="#confirm-delete-<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                                    </div>
                                    <div class="modal-overlay" id="confirm-delete-<?= $row['id'] ?>">
                                        <div class="modal-box">
                                            <h3>Confirm Delete</h3>
                                            <p>Are you sure you want to delete voter <strong><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></strong>? This will also delete their votes.</p>
                                            <div class="modal-actions">
                                                <a href="#" class="btn btn-outline">Cancel</a>
                                                <a href="voters.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-danger">Yes, Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>
</body>
</html>