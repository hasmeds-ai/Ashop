<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";
require_admin();

// Action valider
if (isset($_POST['valider'])) {
  $id_paiement = (int)($_POST['id_paiement'] ?? 0);

  if ($id_paiement > 0) {
    // récupérer la commande liée
    $stmt = $pdo->prepare("SELECT id_commande FROM paiement WHERE id_paiement=?");
    $stmt->execute([$id_paiement]);
    $p = $stmt->fetch();

    if ($p) {
      $pdo->beginTransaction();
      try {
        $stmt = $pdo->prepare("UPDATE paiement SET statut_pay='VALIDE' WHERE id_paiement=?");
        $stmt->execute([$id_paiement]);

        $stmt = $pdo->prepare("UPDATE commande SET statut_commande='PAYEE' WHERE id_commande=?");
        $stmt->execute([(int)$p['id_commande']]);

        $pdo->commit();
      } catch (Exception $e) {
        $pdo->rollBack();
        die("Erreur validation: " . $e->getMessage());
      }
    }
  }
  header("Location: /Ashop/admin/paiements.php");
  exit;
}

// Liste
$sql = "
SELECT
  p.id_paiement,
  p.id_commande,
  p.date_paiement,
  p.montant,
  p.mode_paiement,
  p.statut_pay,
  u.nom AS nom_client,
  u.email
FROM paiement p
JOIN commande c ON c.id_commande = p.id_commande
JOIN client cl ON cl.id_client = c.id_client
JOIN `user` u ON u.id_user = cl.id_user
ORDER BY p.id_paiement DESC
";
$rows = $pdo->query($sql)->fetchAll();

require_once __DIR__ . "/../includes/header.php";
?>
<div class="card">
  <h2>Admin - Paiements</h2>

  <?php if (!$rows): ?>
    <p>Aucun paiement enregistré.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Paiement</th>
          <th>Commande</th>
          <th>Client</th>
          <th>Montant</th>
          <th>Mode</th>
          <th>Date</th>
          <th>Statut</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= (int)$r['id_paiement'] ?></td>
            <td><?= (int)$r['id_commande'] ?></td>
            <td><?= htmlspecialchars($r['nom_client']) ?> <small>(<?= htmlspecialchars($r['email']) ?>)</small></td>
            <td><?= number_format((float)$r['montant'], 2) ?> FCFA</td>
            <td><?= htmlspecialchars($r['mode_paiement']) ?></td>
            <td><?= htmlspecialchars($r['date_paiement']) ?></td>
            <td><span class="badge"><?= htmlspecialchars($r['statut_pay']) ?></span></td>
            <td>
              <?php if (($r['statut_pay'] ?? '') === 'EN_ATTENTE'): ?>
                <form method="post" style="margin:0">
                  <input type="hidden" name="id_paiement" value="<?= (int)$r['id_paiement'] ?>">
                  <button class="btn" name="valider">Valider</button>
                </form>
              <?php else: ?>
                <small>OK</small>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

  <div style="margin-top:12px">
    <a class="btn btn-ghost" href="/Ashop/admin/livraisons.php">Gérer livraisons</a>
  </div>
</div>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>