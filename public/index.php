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
    <a class="brand" href="/Ashop/public/index.php">A-Shop</a>

    <nav class="nav">
      <a href="/Ashop/public/produits.php">Produits</a>
      <a href="/Ashop/public/categories.php">Catégories</a>
      <a href="/Ashop/public/panier.php">Panier</a>

      <?php if (is_logged_in()): ?>
        <a href="/Ashop/public/mes_commandes.php">Mes commandes</a>
        <a href="/Ashop/public/logout.php" class="btn btn-ghost">Déconnexion</a>
        <?php if (is_admin()): ?>
          <a href="/Ashop/admin/dashboard.php" class="btn">Admin</a>
        <?php endif; ?>

      <?php else: ?>
        <a href="/Ashop/public/login.php" class="btn btn-ghost">Connexion</a>
        <a href="/Ashop/public/register.php" class="btn">Créer compte</a>
      <?php endif; ?>
      
    </nav>
  </div>
</header>

<main class="container">

    </main>
<footer class="footer">
  <div class="container">
    <small>© <?= date('Y') ?> A-Shop</small>
  </div>
</footer>
</body>
</html>