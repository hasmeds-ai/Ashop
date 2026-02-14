<?php

require_once __DIR__ . "/../config/db.php";
$id_cat = isset($_GET['id_categorie']) ? (int)$_GET['id_categorie'] : 0;

if ($id_cat > 0) {
  $stmt = $pdo->prepare("SELECT * FROM produit WHERE id_categorie=? ORDER BY id_produit DESC");
  $stmt->execute([$id_cat]);
  $prods = $stmt->fetchAll();
} else {
  $prods = $pdo->query("SELECT * FROM produit ORDER BY id_produit DESC")->fetchAll();
}
?>

<!doctype html><html><body>

<h2>Produits</h2>

<a href="/ashop/public/panier.php">Voir panier</a>

<ul>
<?php foreach($prods as $p): ?>
  <li>
    <b><?= htmlspecialchars($p['libelle']) ?></b> -
    <?= number_format((float)$p['prix'], 2) ?> FCFA
    <form method="post" action="/ashop/public/panier.php">
      <input type="hidden" name="id_produit" value="<?= (int)$p['id_produit'] ?>">
      <input type="number" name="quantite" value="1" min="1">

      <button type="submit" name="add">Ajouter au panier</button>
      
    </form>
  </li>
<?php endforeach; ?>
</ul>
</body></html>