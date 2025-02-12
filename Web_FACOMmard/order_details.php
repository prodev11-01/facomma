<?php
session_start();
include 'config.php';

if (!isset($_SESSION['utilisateur_id']) || !isset($_GET['id'])) {
    header("Location: profil.php");
    exit();
}

$commande_id = $_GET['id'];
$user_id = $_SESSION['utilisateur_id'];

// Récupérer les détails de la commande
$stmt = $pdo->prepare("SELECT * FROM commandes WHERE commande_id = ? AND utilisateur_id = ?");
$stmt->execute([$commande_id, $user_id]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    header("Location: profil.php");
    exit();
}

// Récupérer les produits associés
$stmt_products = $pdo->prepare("SELECT p.nom, p.image_url, p.prix, pc.quantite 
                                FROM produits p 
                                JOIN panier_commandes pc ON p.produit_id = pc.produit_id 
                                WHERE pc.commande_id = ?");
$stmt_products->execute([$commande_id]);
$produits = $stmt_products->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la commande</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>

    <style>
        body {
            background: linear-gradient(135deg, #fff, #b71c1c);
        }

        .order-container {
            max-width: 800px;
            margin: 50px auto;
            text-align: center;
        }

        .order-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(183, 28, 28, 0.3);
        }

        .badge-status {
            font-size: 1rem;
            padding: 10px;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="order-container">
        <div class="order-card p-4">
            <h2 class="text-danger fw-bold"><i class="fas fa-receipt"></i> Commande #<?= $commande_id ?></h2>
            <p><strong>Date :</strong> <?= date("d/m/Y", strtotime($commande['date_commande'])); ?></p>
            <p><strong>Total :</strong> <span class="text-danger"><?= number_format($commande['total'], 2); ?> €</span></p>
            <p><strong>Statut :</strong> <span class="badge badge-status <?= $commande['statut'] === 'Livré' ? 'bg-success' : 'bg-warning'; ?>">
                <?= htmlspecialchars($commande['statut']); ?></span></p>
        </div>

        <h3 class="mt-4 text-danger fw-bold">Produits</h3>
        <div class="row g-3">
            <?php foreach ($produits as $produit) : ?>
                <div class="col-md-6">
                    <div class="card p-3 shadow">
                        <img src="<?= $produit['image_url']; ?>" class="card-img-top">
                        <h5><?= $produit['nom']; ?></h5>
                        <p><?= $produit['prix']; ?> € x <?= $produit['quantite']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>
