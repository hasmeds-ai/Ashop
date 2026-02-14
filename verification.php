<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/auth.php";
require_login();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
  header("Location: /ashop/public/panier.php"); exit;
}

$id_client = (int)($_SESSION['user']['id_client'] ?? 0);
if ($id_client <= 0) die("Client introuvable.");

$ids = array_keys($_SESSION['cart']);
$in = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT id_produit, prix FROM produit WHERE id_produit IN ($in)");
$stmt->execute($ids);
$prods = $stmt->fetchAll();

$priceMap = [];
foreach ($prods as $p) $priceMap[(int)$p['id_produit']] = (float)$p['prix'];

$total = 0;
foreach ($_SESSION['cart'] as $pid => $qty) {
  $total += ($priceMap[(int)$pid] ?? 0) * (int)$qty;
}

$pdo->beginTransaction();
try {
  $stmt = $pdo->prepare("INSERT INTO commande (id_client,total,statut_commande) VALUES (?,?, 'EN_ATTENTE')");
  $stmt->execute([$id_client, $total]);
  $id_commande = (int)$pdo->lastInsertId();

  $stmt = $pdo->prepare("INSERT INTO ligne_commande (id_commande,id_produit,quantite,prix_unitaire) VALUES (?,?,?,?)");
  foreach ($_SESSION['cart'] as $pid => $qty) {
    $prix = $priceMap[(int)$pid] ?? 0;
    $stmt->execute([$id_commande, (int)$pid, (int)$qty, $prix]);
  }

  $pdo->commit();
  $_SESSION['cart'] = []; // vider panier
  header("Location: /ashop/public/paiement.php?id_commande=" . $id_commande);
  exit;
} catch(Exception $e) {
  $pdo->rollBack();
  die("Erreur checkout: " . $e->getMessage());
}