<?php
function checkPermission($requiredRole)
{
    session_start();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $requiredRole) {
        header('Location: ../pages-access-denied.php');
        exit();
    }
}
?>