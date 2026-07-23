<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireRole($role)
{
    if (!isset($_SESSION['role'])) {
        header("Location: " . getenv("APP_URL") . "/login.php");
        exit;
    }

    if ($_SESSION['role'] != $role) {
        header("Location: " . getenv("APP_URL") . "/dashboard.php");
        exit;
    }
}

?>