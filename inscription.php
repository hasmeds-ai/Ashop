<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";

$err = "";
$ok = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nom = trim($_POST['nom'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['mot_de_passe'] ?? '';

  if ($nom === '' || $email === '' || $pass === '') {
    $err = "Tous les champs sont obligatoires.";
  } else {
    try {
      // Vérifier si email existe
      $stmt = $pdo->prepare("SELECT id_user FROM `user` WHERE email=? LIMIT 1");
      $stmt->execute([$email]);
      if ($stmt->fetch()) {
        $err = "Cet email existe déjà.";
      } else {
        $hash = password_hash($pass, PASSWORD_BCRYPT);

        $pdo->beginTransaction();

        // Créer user
        $stmt = $pdo->prepare("INSERT INTO `user` (nom,email,mot_de_passe,role) VALUES (?,?,?,'CLIENT')");
        $stmt->execute([$nom, $email, $hash]);
        $id_user = (int)$pdo->lastInsertId();

        // Créer client lié
        $stmt = $pdo->prepare("INSERT INTO client (id_user, adresse, ville, pays) VALUES (?, '', '', '')");
        $stmt->execute([$id_user]);

        $pdo->commit();

        header("Location: /Ashop/public/login.php");
        exit;
      }
    } catch (Exception $e) {
      if ($pdo->inTransaction()) $pdo->rollBack();
      $err = "Erreur inscription : " . $e->getMessage();
    }
  }
}

require_once __DIR__ . "/../includes/header.php";
?>
<div class="card" style="max-width:520px;margin:0 auto">
  <h2>Créer un compte</h2>

  <?php if($err): ?>
    <div class="alert alert-error"><?= htmlspecialchars($err) ?></div>
  <?php endif; ?>

  <form method="post" class="form">
    <div>
      <label>Nom</label>
      <input class="input" name="nom" required>
    </div>
    <div>
      <label>Email</label>
      <input class="input" type="email" name="email" required>
    </div>
    <div>
      <label>Mot de passe</label>
      <input class="input" type="password" name="mot_de_passe" required>
    </div>
    <button class="btn" type="submit">S'inscrire</button>
  </form>

  <p style="margin-top:10px">
    Déjà un compte ? <a href="/Ashop/public/login.php"><b>Se connecter</b></a>
  </p>
</div>
<?php require_once __DIR__ . "/../includes/footer.php"; ?>