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
    // Get photo to delete
    $stmt = mysqli_prepare($conn, "SELECT photo FROM candidates WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $edit_id);
    mysqli_stmt_execute($stmt);
    $r = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    if ($r && $r['photo'] && $r['photo'] !== 'default.png') {
        $photo_path = '../uploads/candidates/' . $r['photo'];
        if (file_exists($photo_path)) unlink($photo_path);
    }
    $stmt = mysqli_prepare($conn, "DELETE FROM candidates WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $edit_id);
    if (mysqli_stmt_execute($stmt)) $success = 'Candidate deleted.';
    else $error = 'Delete failed.';
    mysqli_stmt_close($stmt);
    $action = 'list';
}

// Handle ADD / EDIT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name  = trim($_POST['first_name'] ?? '');
    $last_name   = trim($_POST['last_name'] ?? '');
    $position_id = (int)($_POST['position_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $form_id     = (int)($_POST['form_id'] ?? 0);

    if (!$first_name || !$last_name || !$position_id) {
        $error = 'First name, last name, and position are required.';
    } else {
        // Handle photo upload
        $photo_name = 'default.png';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $ftype   = $_FILES['photo']['type'];
            $fsize   = $_FILES['photo']['size'];
            if (!in_array($ftype, $allowed)) {
                $error = 'Invalid image type. Use JPG, PNG, GIF, or WEBP.';
            } elseif ($fsize > 5 * 1024 * 1024) {
                $error = 'Image too large (max 5MB).';
            } else {
                $ext       = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $photo_name = 'cand_' . time() . '_' . rand(100,999) . '.' . $ext;
                $dest      = '../uploads/candidates/' . $photo_name;
                if (!move_uploaded_file($_FILES['photo']['tmp_name'], $dest)) {
                    $error = 'Photo upload failed.';
                    $photo_name = 'default.png';
                }
            }
        }

        if (!$error) {
            if ($form_id) {
                // Get old photo
                $old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT photo FROM candidates WHERE id=$form_id"));
                if ($photo_name !== 'default.png') {
                    // Delete old photo
                    if ($old && $old['photo'] && $old['photo'] !== 'default.png') {
                        $old_path = '../uploads/candidates/' . $old['photo'];
                        if (file_exists($old_path)) unlink($old_path);
                    }
                    $stmt = mysqli_prepare($conn, "UPDATE candidates SET first_name=?, last_name=?, position_id=?, description=?, photo=? WHERE id=?");
                    mysqli_stmt_bind_param($stmt, 'ssissi', $first_name, $last_name, $position_id, $description, $photo_name, $form_id);
                } else {
                    $stmt = mysqli_prepare($conn, "UPDATE candidates SET first_name=?, last_name=?, position_id=?, description=? WHERE id=?");
                    mysqli_stmt_bind_param($stmt, 'ssisi', $first_name, $last_name, $position_id, $description, $form_id);
                }
                if (mysqli_stmt_execute($stmt)) { $success = 'Candidate updated.'; $action = 'list'; }
                else $error = 'Update failed: ' . mysqli_error($conn);
                mysqli_stmt_close($stmt);
            } else {
                $stmt = mysqli_prepare($conn, "INSERT INTO candidates (first_name, last_name, position_id, description, photo) VALUES (?,?,?,?,?)");
                mysqli_stmt_bind_param($stmt, 'ssiss', $first_name, $last_name, $position_id, $description, $photo_name);
                if (mysqli_stmt_execute($stmt)) { $success = 'Candidate added.'; $action = 'list'; }
                else $error = 'Insert failed: ' . mysqli_error($conn);
                mysqli_stmt_close($stmt);
            }
        }
    }
}

// Load edit data
$edit_candidate = null;
if ($action === 'edit' && $edit_id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM candidates WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $edit_id);
    mysqli_stmt_execute($stmt);
    $edit_candidate = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    if (!$edit_candidate) $action = 'list';
}

// Fetch positions for dropdown
$positions_result = mysqli_query($conn, "SELECT * FROM positions ORDER BY position_name ASC");
$positions_list = [];
while ($p = mysqli_fetch_assoc($positions_result)) $positions_list[] = $p;

// List with position name
$candidates_result = mysqli_query($conn, "
    SELECT c.*, p.position_name
    FROM candidates c
    JOIN positions p ON p.id = c.position_id
    ORDER BY p.position_name, c.last_name
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidates - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="admin-wrapper">
    <?php include 'includes/sidebar.php'; ?>
    <main class="admin-main">
        <div class="admin-topbar">
            <h2>Candidate Management</h2>
            <a href="candidates.php?action=add" class="btn btn-primary btn-sm">+ Add Candidate</a>
        </div>
        <div class="admin-body">
            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

            <?php if ($action === 'add' || $action === 'edit'): ?>
            <div class="card">
                <div class="card-header">
                    <h3><?= $action === 'edit' ? 'Edit Candidate' : 'Add New Candidate' ?></h3>
                    <a href="candidates.php" class="btn btn-outline btn-sm">← Back</a>
                </div>
                <form method="POST" action="candidates.php" enctype="multipart/form-data">
                    <?php if ($edit_candidate): ?><input type="hidden" name="form_id" value="<?= $edit_candidate['id'] ?>"><?php endif; ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($edit_candidate['first_name'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($edit_candidate['last_name'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Position</label>
                        <select name="position_id" class="form-control" required>
                            <option value="">Select Position</option>
                            <?php foreach ($positions_list as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= (isset($edit_candidate['position_id']) && $edit_candidate['position_id'] == $p['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['position_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Photo <?= $action === 'edit' ? '(leave blank to keep current)' : '' ?></label>
                        <?php if ($action === 'edit' && $edit_candidate['photo'] && $edit_candidate['photo'] !== 'default.png'): ?>
                            <div style="margin-bottom:10px;">
                                <img src="../uploads/candidates/<?= htmlspecialchars($edit_candidate['photo']) ?>" class="photo-preview" alt="">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                        <p class="form-hint">Max 5MB. JPG, PNG, GIF, WEBP.</p>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control"><?= htmlspecialchars($edit_candidate['description'] ?? '') ?></textarea>
                    </div>
                    <div style="display:flex; gap:12px;">
                        <button type="submit" class="btn btn-primary"><?= $action === 'edit' ? 'Update' : 'Add Candidate' ?></button>
                        <a href="candidates.php" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>

            <?php else: ?>
            <div class="card">
                <div class="card-header"><h3>All Candidates</h3></div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($candidates_result)):
                        ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td>
                                    <?php if ($row['photo'] && $row['photo'] !== 'default.png' && file_exists('../uploads/candidates/' . $row['photo'])): ?>
                                        <img src="../uploads/candidates/<?= htmlspecialchars($row['photo']) ?>" style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:1px solid var(--border);" alt="">
                                    <?php else: ?>
                                        <div style="width:40px;height:40px;border-radius:50%;background:var(--surface3);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;">👤</div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                                <td><span class="badge badge-info"><?= htmlspecialchars($row['position_name']) ?></span></td>
                                <td style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?= htmlspecialchars($row['description'] ?? '') ?></td>
                                <td>
                                    <div class="td-actions">
                                        <a href="candidates.php?action=edit&id=<?= $row['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
                                        <a href="#cdel-<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                                    </div>
                                    <div class="modal-overlay" id="cdel-<?= $row['id'] ?>">
                                        <div class="modal-box">
                                            <h3>Delete Candidate</h3>
                                            <p>Delete <strong><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></strong>? Their votes will also be removed.</p>
                                            <div class="modal-actions">
                                                <a href="#" class="btn btn-outline">Cancel</a>
                                                <a href="candidates.php?action=delete&id=<?= $row['id'] ?>" class="btn btn-danger">Delete</a>
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
