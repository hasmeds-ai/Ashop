<?php

if (session_status() === PHP_SESSION_NONE) session_start();

function is_logged_in(): bool {
  return isset($_SESSION['user']);
}

function require_login(): void {
  if (!is_logged_in()) {
    header("Location: /Ashop/public/login.php");
    exit;
  }
}

function is_admin(): bool {
  return is_logged_in() && ($_SESSION['user']['role'] ?? '') === 'ADMIN';
}

function require_admin(): void {
  if (!is_admin()) {
    header("Location: /Ashop/admin/login.php");
    exit;
  }
}

?>