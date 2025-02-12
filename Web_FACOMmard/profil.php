<?php
session_start();
include 'config.php'; // Connexion à la base de données

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

// Récupérer les commandes de l'utilisateur
$stmt_orders = $pdo->prepare("SELECT * FROM commandes WHERE utilisateur_id = ? ORDER BY date_commande DESC");
$stmt_orders->execute([$user_id]);
$commandes = $stmt_orders->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil | Commerciale Industrielle</title>
    
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>

    <!-- Custom Styles -->
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        /* Effet 5D dynamique */
        body {
            background: linear-gradient(135deg, #fff 40%, #b71c1c 100%);
            font-family: 'Raleway', sans-serif;
        }

        .profile-container {
            max-width: 800px;
            margin: 50px auto;
            text-align: center;
            position: relative;
        }

        /* Carte utilisateur animée */
        .profile-card {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(183, 28, 28, 0.3);
            transform: perspective(1000px) rotateX(0deg) rotateY(0deg);
            transition: transform 0.5s ease, box-shadow 0.5s ease;
        }

        .profile-card:hover {
            transform: translateY(-10px) rotateX(5deg) rotateY(5deg);
            box-shadow: 0 20px 50px rgba(183, 28, 28, 0.5);
        }

        /* Animation lumière */
        .profile-card::before {
            content: "";
            position: absolute;
            top: -20px;
            left: 50%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255,255,255,0.3), transparent);
            transform: translateX(-50%);
            transition: opacity 0.5s ease;
        }

        .profile-card:hover::before {
            opacity: 1;
        }

        /* Bouton animé */
        .btn-red {
            background: #b71c1c;
            color: white;
            padding: 12px 25px;
            border-radius: 50px;
            transition: all 0.3s ease-in-out;
        }

        .btn-red:hover {
            background: #ff3d00;
            box-shadow: 0px 10px 20px rgba(183, 28, 28, 0.3);
        }

        /* Tableau des commandes */
        .orders-table {
            margin-top: 30px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .orders-table th {
            background: #b71c1c;
            color: white;
        }

        .orders-table tbody tr:hover {
            background: rgba(183, 28, 28, 0.1);
        }

        
        /* Responsive */
        @media (max-width: 768px) {
            .profile-container {
                margin: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <?php include 'navbar.php'; ?>
<br><br><br><br><br>
    <!-- Section Profil -->
    <div class="profile-container">
        <div class="profile-card p-4">
            <h2 class="text-danger fw-bold"><i class="fas fa-user-circle"></i> Mon Profil</h2>
            <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom']); ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($user['email']); ?></p>
            <p><strong>Téléphone :</strong> <?= htmlspecialchars($user['telephone']); ?></p>
            <p><strong>Adresse :</strong> <?= htmlspecialchars($user['adresse']); ?></p>
            <a href="edit_profile.php" class="btn btn-red mt-3"><i class="fas fa-edit"></i> Modifier Profil</a>
        </div>

        <!-- Section Commandes -->
        <div class="orders-table table-responsive mt-5">
            <h3 class="text-danger fw-bold text-center"><i class="fas fa-box"></i> Mes Commandes</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($commandes) > 0): ?>
                        <?php foreach ($commandes as $commande): ?>
                            <tr>
                                <td><?= $commande['commande_id']; ?></td>
                                <td><?= date("d/m/Y", strtotime($commande['date_commande'])); ?></td>
                                <td class="text-danger fw-bold"><?= number_format($commande['total'], 2); ?> €</td>
                                <td><span class="badge <?= $commande['statut'] === 'Livré' ? 'bg-success' : 'bg-warning'; ?>">
                                    <?= htmlspecialchars($commande['statut']); ?></span></td>
                                <td><a href="order_details.php?id=<?= $commande['commande_id']; ?>" class="btn btn-sm btn-red">Voir</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Aucune commande trouvée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
