<?php
session_start();
include 'config.php';
require_once 'vendor/autoload.php';

// Configurez votre clé secrète Stripe
\Stripe\Stripe::setApiKey('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');

if (!isset($_GET['session_id'])) {
    die("Erreur : session non trouvée.");
}

$session_id = $_GET['session_id'];
$session = \Stripe\Checkout\Session::retrieve($session_id);
$panier_token = isset($session->metadata->panier_token) ? $session->metadata->panier_token : null;
$commande = null;

if ($panier_token) {
    $query = $pdo->prepare("SELECT * FROM commandes WHERE panier_token = ?");
    $query->execute([$panier_token]);
    $commande = $query->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de Commande</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <?php if ($commande): ?>
            <h2 class="text-center text-success">Merci pour votre commande !</h2>
            <p class="text-center">Votre commande #<?= htmlspecialchars($commande['commande_id']) ?> a été enregistrée.</p>
            <?php
            $query = $pdo->prepare("SELECT d.*, p.reference 
                                    FROM details_commande d 
                                    JOIN produits p ON d.produit_id = p.produit_id 
                                    WHERE d.commande_id = ?");
            $query->execute([$commande['commande_id']]);
            $details = $query->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <h4>Détails de la commande :</h4>
            <ul>
                <?php foreach ($details as $item): ?>
                    <li><?= htmlspecialchars($item['reference']) ?> (x<?= $item['quantite'] ?>) - <?= number_format($item['prix_unitaire'], 2) ?> €</li>
                <?php endforeach; ?>
            </ul>
            <h4>Total payé : <?= number_format($commande['montant_total'], 2) ?> €</h4>
            <p><strong>Adresse de livraison :</strong> <?= htmlspecialchars($commande['adresse_livraison']) ?></p>
        <?php else: ?>
            <h2 class="text-center text-info">Merci pour votre paiement.</h2>
            <p class="text-center">Votre commande est en cours de traitement. Vous recevrez bientôt une confirmation par email.</p>
        <?php endif; ?>
        <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>
    </div>
</body>
</html>
