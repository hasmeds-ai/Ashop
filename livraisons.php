<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";
require_admin();

$err = "";
$ok  = "";

// Créer livraison
if (isset($_POST['creer'])) {
  $id_commande = (int)($_POST['id_commande'] ?? 0);
  $adresse = trim($_POST['adresse_livraison'] ?? '');

  if ($id_commande <= 0 || $adresse === '') {
    $err = "Commande et adresse sont obligatoires.";
  } else {
    // éviter doublon : une commande = une livraison
    $stmt = $pdo->prepare("SELECT id_livraison FROM livraison WHERE id_commande=? LIMIT 1");
    $stmt->execute([$id_commande]);
    $exist = $stmt->fetch();

    if ($exist) {
      $err = "Une livraison existe déjà pour cette commande.";
    } else {
      $stmt = $pdo->prepare("INSERT INTO livraison (id_commande, adresse_livraison, date_livraison, statut_livraison)
                             VALUES (?, ?, NULL, 'EN_PREPARATION')");
      $stmt->execute([$id_commande, $adresse]);

      // Optionnel : mettre la commande en préparation/livraison
      $stmt = $pdo->prepare("UPDATE commande SET statut_commande='EN_LIVRAISON' WHERE id_commande=?");
      $stmt->execute([$id_commande]);

      $ok = "Livraison créée avec succès.";
    }
  }
}

// Commandes PAYEE et sans livraison
$sqlCmd = "
SELECT c.id_commande, c.total, u.nom, u.email
FROM commande c
JOIN client cl ON cl.id_client = c.id_client
JOIN `user` u ON u.id_user = cl.id_user
LEFT JOIN livraison l ON l.id_commande = c.id_commande
WHERE c.statut_commande IN ('PAYEE','EN_LIVRAISON') AND l.id_livraison IS NULL
ORDER BY c.id_commande DESC
";
$commandes = $pdo->query($sqlCmd)->fetchAll();

// Liste livraisons
$sqlLiv = "
SELECT l.id_livraison, l.id_commande, l.adresse_livraison, l.statut_livraison, l.date_livraison,
       u.nom, u.email
FROM livraison l
JOIN commande c ON c.id_commande = l.id_commande
JOIN client cl ON cl.id_client = c.id_client
JOIN `user` u ON u.id_user = cl.id_user
ORDER BY l.id_livraison DESC
";
$livraisons = $pdo->query($sqlLiv)->fetchAll();

require_once __DIR__ . "/../includes/header.php";
?>
<div class="card">
  <h2>Admin - Livraisons</h2>

  <?php if($err): ?><div class="alert alert-error"><?= htmlspecialchars($err) ?></div><?php endif; ?>
  <?php if($ok): ?><div class="alert alert-ok"><?= htmlspecialchars($ok) ?></div><?php endif; ?>

  <h3>Créer une livraison</h3>

  <?php if (!$commandes): ?>
    <p>Aucune commande payée disponible (ou déjà livrée).</p>
  <?php else: ?>
    <form class="form" method="post">
      <div>
        <label>Commande</label>
        <select name="id_commande" required>
          <option value="">-- choisir --</option>
          <?php foreach ($commandes as $c): ?>
            <option value="<?= (int)$c['id_commande'] ?>">
              #<?= (int)$c['id_commande'] ?> - <?= htmlspecialchars($c['nom']) ?> (<?= number_format((float)$c['total'],2) ?> FCFA)
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label>Adresse de livraison</label>
        <input class="input" name="adresse_livraison" placeholder="Ex: Ouaga, Secteur 15..." required>
      </div>
      <button class="btn" name="creer">Créer livraison</button>
    </form>
  <?php endif; ?>

  <hr style="border:none;border-top:1px solid #eee;margin:16px 0">

  <h3>Historique des livraisons</h3>
  <?php if (!$livraisons): ?>
    <p>Aucune livraison.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Commande</th>
          <th>Client</th>
          <th>Adresse</th>
          <th>Statut</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($livraisons as $l): ?>
          <tr>
            <td><?= (int)$l['id_livraison'] ?></td>
            <td><?= (int)$l['id_commande'] ?></td>
            <td><?= htmlspecialchars($l['nom']) ?> <small>(<?= htmlspecialchars($l['email']) ?>)</small></td>
            <td><?= htmlspecialchars($l['adresse_livraison']) ?></td>
            <td><span class="badge"><?= htmlspecialchars($l['statut_livraison']) ?></span></td>
            <td><?= htmlspecialchars($l['date_livraison'] ?? '') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

  <div style="margin-top:12px">
    <a class="btn btn-ghost" href="/Ashop/admin/paiements.php">← Retour paiements</a>
  </div>
</div>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>