<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireRole($role)
{
    if (!isset($_SESSION['role'])) {
        header("Location: /index.php");
        exit;
    }

    if ($_SESSION['role'] != $role) {
        header("Location: /dashboard.php");
        exit;
    }
}

?>