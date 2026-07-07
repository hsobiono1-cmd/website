<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireAdminLogin();

$action  = $_GET['action'] ?? 'list';
$edit_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error   = '';
$success = '';

// DELETE
if ($action === 'delete' && $edit_id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM positions WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $edit_id);
    if (mysqli_stmt_execute($stmt)) $success = 'Position deleted.';
    else $error = 'Cannot delete — candidates may be linked to this position.';
    mysqli_stmt_close($stmt);
    $action = 'list';
}

// ADD / EDIT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $position_name = trim($_POST['position_name'] ?? '');
    $form_id = (int)($_POST['form_id'] ?? 0);

    if (!$position_name) {
        $error = 'Position name is required.';
    } elseif ($form_id) {
        $stmt = mysqli_prepare($conn, "UPDATE positions SET position_name = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'si', $position_name, $form_id);
        if (mysqli_stmt_execute($stmt)) { $success = 'Position updated.'; $action = 'list'; }
        else $error = 'Update failed: ' . mysqli_error($conn);
        mysqli_stmt_close($stmt);
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO positions (position_name) VALUES (?)");
        mysqli_stmt_bind_param($stmt, 's', $position_name);
        if (mysqli_stmt_execute($stmt)) { $success = 'Position added.'; $action = 'list'; }
        else $error = 'Insert failed (may already exist).';
        mysqli_stmt_close($stmt);
    }
}

$edit_position = null;
if ($action === 'edit' && $edit_id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM positions WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $edit_id);
    mysqli_stmt_execute($stmt);
    $edit_position = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    if (!$edit_position) $action = 'list';
}

$positions_result = mysqli_query($conn, "
    SELECT p.*, COUNT(c.id) AS candidate_count
    FROM positions p
    LEFT JOIN candidates c ON c.position_id = p.id
    GROUP BY p.id
    ORDER BY p.id ASC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Positions - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="admin-wrapper">
    <?php include 'includes/sidebar.php'; ?>
    <main class="admin-main">
        <div class="admin-topbar">
            <h2>Position Management</h2>
            <a href="positions.php?action=add" class="btn btn-primary btn-sm">+ Add Position</a>
        </div>
        <div class="admin-body">
            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

            <?php if ($action === 'add' || $action === 'edit'): ?>
            <div class="card" style="max-width:500px;">
                <div class="card-header">
                    <h3><?= $action === 'edit' ? 'Edit Position' : 'Add New Position' ?></h3>
                    <a href="positions.php" class="btn btn-outline btn-sm">← Back</a>
                </div>
                <form method="POST" action="positions.php">
                    <?php if ($edit_position): ?><input type="hidden" name="form_id" value="<?= $edit_position['id'] ?>"><?php endif; ?>
                    <div class="form-group">
                        <label>Position Name</label>
                        <input type="text" name="position_name" class="form-control" value="<?= htmlspecialchars($edit_position['position_name'] ?? '') ?>" required placeholder="e.g. President">
                    </div>
                    <div style="display:flex; gap:12px;">
                        <button type="submit" class="btn btn-primary"><?= $action === 'edit' ? 'Update' : 'Add Position' ?></button>
                        <a href="positions.php" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>

            <?php else: ?>
            <div class="card">
                <div class="card-header"><h3>All Positions</h3></div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Position Name</th>
                                <th>Candidates</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($positions_result)):
                        ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['position_name']) ?></td>
                                <td><span class="badge badge-info"><?= $row['candidate_count'] ?></span></td>
                                <td>
                                    <div class="td-actions">
                                        <a href="positions.php?action=edit&id=<?= $row['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
                                        <a href="#pdel-<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                                    </div>
                                    <div class="modal-overlay" id="pdel-<?= $row['id'] ?>">
                                        <div class="modal-box">
                                            <h3>Delete Position</h3>
                                            <p>Delete position <strong><?= htmlspecialchars($row['position_name']) ?></strong>? This will also delete all candidates under this position.</p>
                                            <div class="modal-actions">
                                                <a href="#" class="btn btn-outline">Cancel</a>
                                                <a href="positions.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-danger">Delete</a>
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
