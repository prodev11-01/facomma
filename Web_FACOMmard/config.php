<?php
// Informations de connexion à la base de données
$host = "localhost"; // Serveur MySQL (localhost pour XAMPP)
$dbname = "bd_facommard"; // Nom de la base de données
$username = "root"; // Nom d'utilisateur MySQL (par défaut : root pour XAMPP)
$password = ""; // Mot de passe MySQL (vide par défaut pour XAMPP)
$port = "3306";

try {
    // Création d'une instance de PDO pour la connexion
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Mode d'erreur : exceptions
} catch (PDOException $e) {
    // Affiche une erreur si la connexion échoue
    die("Erreur de connexion : " . $e->getMessage());
}
?>
