<?php
session_start();
require_once 'includes/db.php';

if (isset($_SESSION['voter_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login    = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$login || !$password) {
        $error = 'Please enter your credentials.';
    } else {
        $stmt = mysqli_prepare($conn, "SELECT * FROM voters WHERE username = ? OR voter_id = ?");
        mysqli_stmt_bind_param($stmt, 'ss', $login, $login);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $voter = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($voter && password_verify($password, $voter['password'])) {
            session_regenerate_id(true);
            $_SESSION['voter_id'] = $voter['id'];
            $_SESSION['voter_name'] = $voter['first_name'] . ' ' . $voter['last_name'];
            $_SESSION['voter_uid']  = $voter['voter_id'];
            $_SESSION['has_voted']  = $voter['has_voted'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username/voter ID or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Voting System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-box">
        <div class="auth-logo">
            <span class="logo-icon">🗳️</span>
            <h1>VOTESYSTEM</h1>
            <p>Voter Login Portal</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Username or Voter ID</label>
                <input type="text" name="login" class="form-control" required autofocus placeholder="Enter username or voter ID">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Enter your password">
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg">Login</button>
        </form>

        <div class="auth-divider">OR</div>

        <p class="text-center text-muted" style="font-size:0.9rem;">
            Don't have an account? <a href="register.php">Register here</a>
        </p>

        <p class="text-center mt-3" style="font-size:0.78rem;">
            <a href="index.php" style="color:var(--text-dim);">← Back to Home</a>
            &nbsp;|&nbsp;
            <a href="admin/login.php" style="color:var(--text-dim);">Admin Panel</a>
        </p>
    </div>
</div>
</body>
</html>
