<?php
function requireVoterLogin() {
    if (!isset($_SESSION['voter_id'])) {
        header('Location: ../login.php');
        exit;
    }
}

function requireAdminLogin() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ../admin/login.php');
        exit;
    }
}

function getElectionStatus($conn) {
    $result = mysqli_query($conn, "SELECT status FROM election_status ORDER BY id DESC LIMIT 1");
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['status'];
    }
    return 'inactive';
}

function sanitize($conn, $value) {
    return mysqli_real_escape_string($conn, trim($value));
}
