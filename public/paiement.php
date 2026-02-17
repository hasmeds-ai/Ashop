<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";
require_login();

$id_commande = (int)($_GET['id_commande'] ?? 0);
if ($id_commande <= 0) die("Commande invalide.");

$err = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $mode = trim($_POST['mode_paiement'] ?? '');
  if ($mode === '') $err = "Choisis un mode de paiement.";

  else {
    // montant = total commande
    $stmt = $pdo->prepare("SELECT total FROM commande WHERE id_commande=?");
    $stmt->execute([$id_commande]);
    $cmd = $stmt->fetch();
    if (!$cmd) die("Commande introuvable.");

    $stmt = $pdo->prepare("INSERT INTO paiement (id_commande,date_paiement,montant,mode_paiement,statut_pay)
                           VALUES (?, NOW(), ?, ?, 'EN_ATTENTE')");
    $stmt->execute([$id_commande, $cmd['total'], $mode]);

    header("Location: /ashop/public/mes_commandes.php");
    exit;
  }
}
?>

<!doctype html><html><body>

<h2>Paiement</h2>

<?php if($err) echo "<p style='color:red'>$err</p>"; ?>
<form method="post">
  <select name="mode_paiement" required>

    <option value="">-- Choisir --</option>
    <option value="CASH">CASH</option>
    <option value="MOBILE_MONEY">Mobile Money</option>
    <option value="CARTE">Carte</option>
    
  </select>
  <button type="submit">Valider paiement</button>
</form>
</body></html>