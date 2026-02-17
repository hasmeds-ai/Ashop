<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";
require_admin();

$nb_clients = (int)$pdo->query("SELECT COUNT(*) c FROM client")->fetch()['c'];
$nb_cmd = (int)$pdo->query("SELECT COUNT(*) c FROM commande")->fetch()['c'];
$nb_pay_wait = (int)$pdo->query("SELECT COUNT(*) c FROM paiement WHERE statut_pay='EN_ATTENTE'")->fetch()['c'];

require_once __DIR__ . "/../includes/header.php";
?>
<div class="card">
  <h2>Admin - Dashboard</h2>
  <div class="grid">
    <div class="card"><b>Clients</b><div><?= $nb_clients ?></div></div>
    <div class="card"><b>Commandes</b><div><?= $nb_cmd ?></div></div>
    <div class="card"><b>Paiements en attente</b><div><?= $nb_pay_wait ?></div></div>
  </div>
  <hr style="border:none;border-top:1px solid #eee;margin:16px 0">
  <div class="nav">

    <a class="btn" href="/Ashop/admin/produits.php">Produits</a>
    <a class="btn" href="/Ashop/admin/categories.php">Catégories</a>
    <a class="btn" href="/Ashop/admin/commandes.php">Commandes</a>
    <a class="btn" href="/Ashop/admin/paiements.php">Valider paiements</a>
    <a class="btn" href="/Ashop/admin/livraisons.php">Livraisons</a>
    <a class="btn" href="/Ashop/admin/clients.php">Clients</a>
    
  </div>
</div>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>