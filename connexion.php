<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";

$err = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['mot_de_passe'] ?? '';

  $stmt = $pdo->prepare("SELECT * FROM `user` WHERE email=? LIMIT 1");
  $stmt->execute([$email]);
  $u = $stmt->fetch();

  if (!$u || !password_verify($pass, $u['mot_de_passe'])) {
    $err = "Email ou mot de passe incorrect.";
  } else {
    // récupérer id_client
    $stmt = $pdo->prepare("SELECT id_client FROM client WHERE id_user=? LIMIT 1");
    $stmt->execute([$u['id_user']]);
    $c = $stmt->fetch();

    $_SESSION['user'] = [
      'id_user' => (int)$u['id_user'],
      'role' => $u['role'],
      'nom' => $u['nom'],
      'email' => $u['email'],
      'id_client' => $c ? (int)$c['id_client'] : null
    ];
    header("Location: /ashop/public/index.php");
    exit;
  }
}
?>

<!doctype html><html><body>

<h2>Connexion</h2>

<?php if($err) echo "<p style='color:red'>$err</p>"; ?>

<form method="post">

  <input name="email" type="email" placeholder="Email" required>
  <input name="mot_de_passe" type="password" placeholder="Mot de passe" required>

  <button type="submit">Se connecter</button>

</form>

<a href="/ashop/public/register.php">Créer un compte</a>

</body></html>