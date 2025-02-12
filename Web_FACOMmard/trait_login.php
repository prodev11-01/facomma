<?php
session_start(); // Démarre la session

// Paramètres de connexion à la base de données
include 'config.php';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Récupération et nettoyage des données du formulaire
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    // Vérification des champs vides
    if (empty($email) || empty($mot_de_passe)) {
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
        header("Location: login.php");
        exit();
    }

    try {
        // Préparation de la requête pour récupérer l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
        $stmt->execute(['email' => $email]);

        // Vérification si un utilisateur a été trouvé
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérification du mot de passe
            if (password_verify($mot_de_passe, $user['mot_de_passe'])) {
                // Stocke les informations de l'utilisateur dans la session
                $_SESSION['utilisateur_id'] = $user['utilisateur_id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Redirection après connexion réussie
                header("Location: index.php");
                exit();
            } else {
                // Mot de passe incorrect
                $_SESSION['error'] = "Mot de passe incorrect.";
            }
        } else {
            // Aucun utilisateur trouvé avec cet email
            $_SESSION['error'] = "Email incorrect.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la requête SQL : " . $e->getMessage();
    }

    // Redirection en cas d'échec de connexion
    header("Location: login.php");
    exit();
}
?>
