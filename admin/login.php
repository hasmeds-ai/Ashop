<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";

$err = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['mot_de_passe'] ?? '';

  $stmt = $pdo->prepare("SELECT * FROM `user` WHERE email=? LIMIT 1");
  $stmt->execute([$email]);
  $u = $stmt->fetch();

  if (!$u || !password_verify($pass, $u['mot_de_passe']) || ($u['role'] ?? '') !== 'ADMIN') {
    $err = "Accès refusé (admin uniquement).";
  } else {
    $_SESSION['user'] = [
      'id_user' => (int)$u['id_user'],
      'role' => $u['role'],
      'nom' => $u['nom'],
      'email' => $u['email'],
      'id_client' => null
    ];
    header("Location: /Ashop/admin/dashboard.php"); exit;
  }
}

require_once __DIR__ . "/../includes/header.php";
?>
<div class="card" style="max-width:480px;margin:0 auto">
  <h2>Connexion Admin</h2>
  <?php if($err): ?><div class="alert alert-error"><?= htmlspecialchars($err) ?></div><?php endif; ?>
  <form class="form" method="post">
    <div>
      <label>Email</label>
      <input class="input" type="email" name="email" required>
    </div>
    <div>
      <label>Mot de passe</label>
      <input class="input" type="password" name="mot_de_passe" required>
    </div>
    <button class="btn" type="submit">Se connecter</button>
  </form>
</div>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>