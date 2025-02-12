<?php
include 'config.php'; // Connexion à la base de données

// Vérification de l'utilisateur connecté
session_start();
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: login.php'); // Redirige vers la page de connexion si non connecté
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];

// Vérification des données soumises
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produit_id'], $_POST['quantite'])) {
    $produit_id = intval($_POST['produit_id']);
    $quantite = intval($_POST['quantite']);

    // Si la quantité est valide, on met à jour
    if ($quantite > 0) {
        $query_update = $pdo->prepare("
            UPDATE panier 
            SET quantite = ? 
            WHERE utilisateur_id = ? AND produit_id = ?
        ");
        $query_update->execute([$quantite, $utilisateur_id, $produit_id]);

        $_SESSION['message'] = "Quantité mise à jour avec succès.";
    } else {
        $_SESSION['message'] = "La quantité doit être supérieure à 0.";
    }
} else {
    $_SESSION['message'] = "Données invalides.";
}

// Redirection vers la page du panier
header('Location: cart.php');
exit;
?>
