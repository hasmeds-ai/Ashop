<?php
require_once __DIR__ . "/../config/auth.php";
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>A-Shop</title>
  <link rel="stylesheet" href="/Ashop/assets/css/style.css">
</head>
<body>
<header class="topbar">
  <div class="container row">
    <a class="brand" href="/Ashop/public/produits.php">A-Shop</a>

    <nav class="nav">
      <a href="/Ashop/public/produits.php">Produits</a>
      <a href="/Ashop/public/panier.php">Panier</a>

      <?php if (is_logged_in()): ?>
        <a href="/Ashop/public/mes_commandes.php">Mes commandes</a>
        <a class="btn btn-ghost" href="/Ashop/public/logout.php">Déconnexion</a>
        <?php if (is_admin()): ?>
          <a class="btn" href="/Ashop/admin/paiements.php">Admin</a>
        <?php endif; ?>
      <?php else: ?>
        <a class="btn btn-ghost" href="/Ashop/public/login.php">Connexion</a>
        <a class="btn" href="/Ashop/public/inscription.php">Créer compte</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<main class="container">