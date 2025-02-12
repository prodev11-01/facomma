<?php
// Démarrer la session
session_start();
include 'config.php'; // Connexion à la base de données

// Récupération des catégories
$query_categories = $pdo->query("SELECT * FROM categories ORDER BY date_ajout DESC");
$categories = $query_categories->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Catégories | Commerciale Industrielle</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="catégories, outillage industriel, outils professionnels" name="keywords">
    <meta content="Explorez nos catégories d'outillage industriel et trouvez les produits adaptés à vos besoins." name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
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
    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <?php include 'navbar.php'; ?>
    <!-- Navbar End -->

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 bg-primary">
        <h1 class="text-center text-white display-6">Catégories</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="index.php" class="text-white">Accueil</a></li>
            <li class="breadcrumb-item text-white active">Catégories</li>
        </ol>
    </div>
    <!-- Page Header End -->

    <!-- Categories Section Start -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-primary fw-bold text-center mb-4">Nos Catégories</h2>
            <div class="row g-4">
                <?php foreach ($categories as $categorie) : ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card shadow h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($categorie['nom']); ?></h5>
                                <p class="card-text"><?= htmlspecialchars($categorie['description']); ?></p>
                                <a href="category.php?id=<?= $categorie['categorie_id']; ?>" class="btn btn-primary">
                                    <i class="fas fa-box-open me-2"></i> Voir les produits
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- Categories Section End -->

    <!-- Footer Start -->
    <?php include 'footer.php'; ?>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>
