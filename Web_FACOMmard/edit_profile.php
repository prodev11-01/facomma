<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['utilisateur_id'];

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT nom, email, telephone, adresse FROM utilisateurs WHERE utilisateur_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Gestion du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $adresse = trim($_POST['adresse']);

    // Mettre à jour les informations dans la base de données
    $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, email = ?, telephone = ?, adresse = ? WHERE utilisateur_id = ?");
    $stmt->execute([$nom, $email, $telephone, $adresse, $user_id]);

    // Mise à jour réussie
    $_SESSION['success'] = "Profil mis à jour avec succès !";
    header("Location: profil.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Profil</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>

    <style>
        body {
            background: linear-gradient(135deg, #fff, #b71c1c);
        }

        .profile-container {
            max-width: 600px;
            margin: 50px auto;
        }

        .profile-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(183, 28, 28, 0.3);
            transition: transform 0.5s ease, box-shadow 0.5s ease;
        }

        .profile-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(183, 28, 28, 0.5);
        }

        .btn-red {
            background: #b71c1c;
            color: white;
            transition: all 0.3s ease-in-out;
        }

        .btn-red:hover {
            background: #ff3d00;
            box-shadow: 0px 10px 20px rgba(183, 28, 28, 0.3);
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="profile-container">
        <div class="profile-card p-4">
            <h2 class="text-danger fw-bold"><i class="fas fa-user-edit"></i> Modifier Profil</h2>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($user['telephone']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="adresse" class="form-control" value="<?= htmlspecialchars($user['adresse']); ?>" required>
                </div>
                <button type="submit" class="btn btn-red w-100">Mettre à jour</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

</body>
</html>
