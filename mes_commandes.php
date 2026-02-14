<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";
require_login();

$id_client = (int)($_SESSION['user']['id_client'] ?? 0);
if ($id_client <= 0) die("Client introuvable.");

$sql = "
SELECT
  c.id_commande,
  c.date_commande,
  c.total,
  c.statut_commande,
  p.statut_pay,
  p.mode_paiement,
  l.statut_livraison
FROM commande c
LEFT JOIN paiement p ON p.id_commande = c.id_commande
LEFT JOIN livraison l ON l.id_commande = c.id_commande
WHERE c.id_client = ?
ORDER BY c.id_commande DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_client]);
$rows = $stmt->fetchAll();

require_once __DIR__ . "/../includes/header.php";
?>
<div class="card">
  <h2>Mes commandes</h2>

  <?php if (!$rows): ?>
    <p>Aucune commande pour le moment.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Date</th>
          <th>Total</th>
          <th>Statut commande</th>
          <th>Paiement</th>
          <th>Livraison</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= (int)$r['id_commande'] ?></td>
            <td><?= htmlspecialchars($r['date_commande']) ?></td>
            <td><?= number_format((float)$r['total'], 2) ?> FCFA</td>
            <td><span class="badge"><?= htmlspecialchars($r['statut_commande'] ?? 'N/A') ?></span></td>
            <td>
              <span class="badge"><?= htmlspecialchars($r['statut_pay'] ?? 'NON_PAYE') ?></span>
              <?php if (!empty($r['mode_paiement'])): ?>
                <small>(<?= htmlspecialchars($r['mode_paiement']) ?>)</small>
              <?php endif; ?>
            </td>
            <td><span class="badge"><?= htmlspecialchars($r['statut_livraison'] ?? 'NON_LIVREE') ?></span></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>