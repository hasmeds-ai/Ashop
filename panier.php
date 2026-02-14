<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = []; // id_produit => qty

// Ajouter
if (isset($_POST['add'])) {
  $id = (int)($_POST['id_produit'] ?? 0);
  $qty = max(1, (int)($_POST['quantite'] ?? 1));
  if ($id > 0) {
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
  }
  header("Location: /ashop/public/panier.php");
  exit;
}

// Retirer
if (isset($_POST['remove'])) {
  $id = (int)($_POST['id_produit'] ?? 0);
  unset($_SESSION['cart'][$id]);
  header("Location: /ashop/public/panier.php");
  exit;
}

// Charger détails produits
$items = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
  $ids = array_keys($_SESSION['cart']);
  $in = implode(',', array_fill(0, count($ids), '?'));
  $stmt = $pdo->prepare("SELECT id_produit, libelle, prix FROM produit WHERE id_produit IN ($in)");
  $stmt->execute($ids);
  $prods = $stmt->fetchAll();
  foreach ($prods as $p) {
    $qty = (int)$_SESSION['cart'][(int)$p['id_produit']];
    $sub = $qty * (float)$p['prix'];
    $total += $sub;
    $items[] = ['p'=>$p, 'qty'=>$qty, 'sub'=>$sub];
  }
}
?>

<!doctype html><html><body>

<h2>Panier</h2>

<a href="/ashop/public/produits.php">Continuer achats</a>
<table border="1" cellpadding="6">
<tr><th>Produit</th><th>Qté</th><th>Prix</th><th>Sous-total</th><th></th></tr>
<?php foreach($items as $it): ?>
<tr>
  <td><?= htmlspecialchars($it['p']['libelle']) ?></td>
  <td><?= (int)$it['qty'] ?></td>
  <td><?= number_format((float)$it['p']['prix'],2) ?></td>
  <td><?= number_format((float)$it['sub'],2) ?></td>
  <td>
    <form method="post">
      <input type="hidden" name="id_produit" value="<?= (int)$it['p']['id_produit'] ?>">
      <button name="remove">Supprimer</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</table>
<h3>Total : <?= number_format((float)$total,2) ?> FCFA</h3>

<?php if ($total > 0): ?>
  <a href="/ashop/public/checkout.php">Passer commande</a>
<?php endif; ?>
</body></html>