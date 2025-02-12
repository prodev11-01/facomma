<?php
// Démarrer la session
session_start();
include 'config.php'; // Connexion à la base de données

// Vérification de l'utilisateur connecté
session_start();
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: login.php'); // Redirige vers la page de connexion si non connecté
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];

// Vérification des données soumises
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produit_id'])) {
    $produit_id = intval($_POST['produit_id']);

    // Suppression du produit du panier
    $query_delete = $pdo->prepare("
        DELETE FROM panier 
        WHERE utilisateur_id = ? AND produit_id = ?
    ");
    $query_delete->execute([$utilisateur_id, $produit_id]);

    $_SESSION['message'] = "Produit supprimé du panier.";
} else {
    $_SESSION['message'] = "Données invalides.";
}

// Redirection vers la page du panier
header('Location: cart.php');
exit;
?>
