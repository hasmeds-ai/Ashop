A-Shop – Application E-commerce PHP

 Description

A-Shop est une application web e-commerce développée en **PHP procédural** avec **MySQL**.
Elle permet la gestion complète du processus d’achat : inscription, connexion, panier, commande, paiement et livraison.

Structure de la base de données

La base `ashop_database` contient les tables suivantes :

* `user` : gestion des comptes (ADMIN / CLIENT)
* `client` : informations complémentaires du client
* `categorie` : organisation des produits
* `produit` : catalogue des articles
* `commande` : en-tête des commandes
* `ligne_commande` : détails des produits commandés
* `paiement` : gestion des paiements
* `livraison` : suivi logistique

Les tables sont liées par des clés étrangères assurant l’intégrité des données.

 Technologies utilisées

* PHP procédural
* MySQL
* HTML5
* CSS3
* Sessions PHP

 Installation du projet

1️⃣ Télécharger le projet

Cloner le repository :

```bash
git clone https://github.com/hasmeds-ai/Ashop.git
```

Ou télécharger le ZIP depuis GitHub.

---

2️⃣ Placer le dossier dans :

```
C:\xampp\htdocs\
```

3️⃣ Importer la base de données

1. Ouvrir : [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Créer une base nommée : `ashop_database`
3. Cliquer sur **Importer**
4. Sélectionner le fichier : `ashop_database.sql`
5. Exécuter

4️⃣ Configurer la connexion à la base

Dans le fichier `config/db.php` :

```php
$DB_HOST = "localhost";
$DB_NAME = "ashop_database";
$DB_USER = "root";
$DB_PASS = "";
```
 Comptes de test
Admin

Email : [admin@ashop.com](mailto:admin@ashop.com)
Mot de passe : Admin@123

Client

Email : [client1@test.com](mailto:client1@test.com)
Mot de passe : Client1@123

 Scénario fonctionnel validé

L’application permet le scénario suivant :

1. Un client crée un compte
2. Il se connecte
3. Il ajoute 2 produits au panier
4. Il passe une commande
5. Il effectue le paiement
6. L’admin valide le paiement
7. L’admin crée une livraison
8. Le client consulte le statut de sa commande

 Fonctionnement général

 Panier

Le panier est géré en session PHP via `$_SESSION['cart']`.
Les produits ajoutés sont stockés temporairement avant validation.

 Commande

Lors de la validation :

* Une entrée est créée dans `commande`
* Les produits sont enregistrés dans `ligne_commande`

 Paiement

Un enregistrement est créé dans `paiement` avec statut `EN_ATTENTE`.
L’admin valide ensuite le paiement (statut `VALIDE`).

 Livraison

Après validation du paiement, l’admin crée une livraison.
Le client peut consulter les statuts dans l’espace **Mes commandes**.

---

Livrables inclus

* Application Web (PHP + CSS)
* Base de données : `ashop_database.sql`
* Documentation : `Documentation_Ashop.pdf`
* README.md


