<?php

require_once __DIR__ . "/../config/db.php";
$cats = $pdo->query("SELECT * FROM categorie ORDER BY nom_cat")->fetchAll();
?>

<!doctype html><html><body>

<h2>Catégories</h2>

<ul>
<?php foreach($cats as $c): ?>
  <li><a href="/Ashop/public/produits.php?id_categorie=<?= (int)$c['id_categorie'] ?>">
    <?= htmlspecialchars($c['nom_cat']) ?></a></li>
<?php endforeach; ?>
</ul>
</body></html>
