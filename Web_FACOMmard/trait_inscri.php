<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];

    // Validation des données
    if (empty($nom) || empty($prenom) || empty($email) || empty($mot_de_passe) || empty($adresse) || empty($telephone)) {
        die("Tous les champs doivent être remplis.");
    }

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bd_facommard";
    $port = "3306";

    // Créer la connexion
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Échec de la connexion à la base de données : " . $conn->connect_error);
    }

    // Hacher le mot de passe avant de l'enregistrer
    $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Préparer la requête SQL pour insérer les données
    $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, adresse, telephone) 
            VALUES (?, ?, ?, ?, ?, ?)";

    // Vérifier si la requête est préparée correctement
    if ($stmt = $conn->prepare($sql)) {
        // Lier les paramètres
        $stmt->bind_param("ssssss", $nom, $prenom, $email, $hashed_password, $adresse, $telephone);

        // Exécuter la requête
        if ($stmt->execute()) {
            echo "<script>
                    alert('Inscription réussie !');
                    setTimeout(function(){
                        window.location.href = 'login.php';
                    }, 3000);
                  </script>";
        } else {
            echo "Erreur lors de l'exécution de la requête : " . $stmt->error;
        }

        // Fermer le statement
        $stmt->close();
    } else {
        // Si la préparation échoue, afficher l'erreur SQL
        die("Erreur lors de la préparation de la requête SQL : " . $conn->error);
    }

    // Fermer la connexion
    $conn->close();
}
