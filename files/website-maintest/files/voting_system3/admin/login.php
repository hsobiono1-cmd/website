<?php
session_start();
require_once '../includes/db.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $error = 'Please enter your credentials.';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT * FROM admins WHERE username = ?");
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $admin = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_user'] = $admin['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid admin credentials.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Voting System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-box">
        <div class="auth-logo">
            <span class="logo-icon">⚙️</span>
            <h1>ADMIN PANEL</h1>
            <p>Voting System Administration</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Admin Username</label>
                <input type="text" name="username" class="form-control" required autofocus placeholder="Enter admin username">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Enter admin password">
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg">Login as Admin</button>
        </form>

        <p class="text-center mt-3" style="font-size:0.82rem; color:var(--text-dim);">
            Default: admin / password
        </p>
        <p class="text-center mt-1" style="font-size:0.8rem;">
            <a href="../index.php" style="color:var(--text-dim);">← Back to Home</a>
        </p>
    </div>
</div>
</body>
</html>
