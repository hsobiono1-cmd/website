<?php
session_start();
require_once 'includes/db.php';

if (isset($_SESSION['voter_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';
$form = [
    'first_name' => '', 'last_name' => '', 'voter_id' => '',
    'birthdate' => '', 'address' => '', 'username' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $voter_id   = trim($_POST['voter_id'] ?? '');
    $birthdate  = trim($_POST['birthdate'] ?? '');
    $address    = trim($_POST['address'] ?? '');
    $username   = trim($_POST['username'] ?? '');
    $password   = $_POST['password'] ?? '';
    $confirm    = $_POST['confirm_password'] ?? '';

    $form = compact('first_name','last_name','voter_id','birthdate','address','username');

    if (!$first_name || !$last_name || !$voter_id || !$birthdate || !$address || !$username || !$password) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        // Check duplicate voter_id
        $stmt = mysqli_prepare($conn, "SELECT id FROM voters WHERE voter_id = ?");
        mysqli_stmt_bind_param($stmt, 's', $voter_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = 'Voter ID already registered.';
        } else {
            mysqli_stmt_close($stmt);
            // Check duplicate username
            $stmt = mysqli_prepare($conn, "SELECT id FROM voters WHERE username = ?");
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) > 0) {
                $error = 'Username already taken.';
            } else {
                mysqli_stmt_close($stmt);
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = mysqli_prepare($conn, "INSERT INTO voters (voter_id, first_name, last_name, birthdate, address, username, password) VALUES (?,?,?,?,?,?,?)");
                mysqli_stmt_bind_param($stmt, 'sssssss', $voter_id, $first_name, $last_name, $birthdate, $address, $username, $hashed);
                if (mysqli_stmt_execute($stmt)) {
                    $success = 'Registration successful! You can now login.';
                    $form = ['first_name'=>'','last_name'=>'','voter_id'=>'','birthdate'=>'','address'=>'','username'=>''];
                } else {
                    $error = 'Registration failed. Please try again.';
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Voting System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav class="navbar">
    <a href="index.php" class="navbar-brand">VOTE<span>SYSTEM</span></a>
    <ul class="navbar-nav">
        <li><a href="login.php">Login</a></li>
    </ul>
</nav>

<div class="main-content container" style="max-width:700px;">
    <div class="page-header">
        <h1>Voter Registration</h1>
        <p>Create your account to participate in the election.</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?> <a href="login.php">Login now →</a></div>
    <?php endif; ?>

    <div class="card">
        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($form['first_name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($form['last_name']) ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Voter ID</label>
                    <input type="text" name="voter_id" class="form-control" value="<?= htmlspecialchars($form['voter_id']) ?>" required placeholder="e.g. VTR-2024-001">
                    <p class="form-hint">Must be unique. Use your official Voter ID number.</p>
                </div>
                <div class="form-group">
                    <label>Birthdate</label>
                    <input type="date" name="birthdate" class="form-control" value="<?= htmlspecialchars($form['birthdate']) ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2" required><?= htmlspecialchars($form['address']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($form['username']) ?>" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required minlength="6">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg">Register</button>
        </form>
    </div>

    <p class="text-center text-muted mt-2">Already have an account? <a href="login.php">Login here</a></p>
</div>
</body>
</html>
