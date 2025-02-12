<?php
// Démarrer la session
session_start();
include 'config.php'; // Connexion à la base de données

// Récupération des catégories
$query_categories = $pdo->query("SELECT * FROM categories LIMIT 3");
$categories = $query_categories->fetchAll(PDO::FETCH_ASSOC);

// Récupération des produits populaires
$query_products = $pdo->query("SELECT * FROM produits WHERE statut = 'actif' AND reference LIKE '%mod%' ORDER BY date_ajout DESC LIMIT 15;");
$produits = $query_products->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Accueil | Commerciale Industrielle</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="outillage industriel, outils professionnels, livraison rapide, produits Facom, Facom" name="keywords">
    <meta content="Découvrez Commerciale Industrielle, votre spécialiste en outillage industriel, avec livraison rapide et produits de qualité." name="description">

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
        /* Effet 3D dynamique pour les cartes */
.card {
    position: relative;
    border: none;
    border-radius: 15px;
    overflow: hidden;
    background: #fff;
    /* Préparer la perspective pour l'effet 3D */
    transform-style: preserve-3d;
    perspective: 1000px;
    transition: transform 0.5s ease, box-shadow 0.5s ease;
}

/* Effet de survol (hover) : translation, rotation et légère mise à l'échelle */
.card:hover {
    transform: translateY(-10px) rotateY(10deg) scale(1.03);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

/* Optionnel : ajouter un effet de lumière subtile sur le survol */
.card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, rgba(255,255,255,0.2), rgba(0,0,0,0.1));
    opacity: 0;
    transition: opacity 0.5s ease;
    pointer-events: none;
    transform: translateZ(1px);
}
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
.card:hover::before {
    opacity: 1;
}

/* Améliorations optionnelles pour l'image de la carte */
.card-img-top {
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    transition: transform 0.5s ease;
}

.card:hover .card-img-top {
    transform: scale(1.05);
}

/* Améliorations optionnelles pour le contenu de la carte */
.card-body {
    padding: 1.5rem;
    transition: transform 0.5s ease;
    backface-visibility: hidden;
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
    <!-- Hero Section Start -->
    <div class="container-fluid py-5 bg-primary  page-header text-white text-center">
        <h1 class="text-danger">Bienvenue chez <span class="text-danger">Commerciale Industrielle</span></h1>
        <p class="lead">Votre partenaire en solutions d'outillage industriel de qualité.</p>
        <a href="categories.php" class="btn btn-light btn-lg mt-3"><i class="fas fa-tools me-2"></i> Découvrez nos produits</a>
    </div>
    <!-- Hero Section End -->

    <!-- Categories Start -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-primary fw-bold text-center mb-4">Nos Catégories Principales</h2>
            <div class="row g-4">
                <?php foreach ($categories as $categorie) : ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card shadow h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($categorie['nom']); ?></h5>
                                <p class="card-text"><?= htmlspecialchars($categorie['description']); ?></p>
                                <a href="category.php?id=<?= $categorie['categorie_id']; ?>" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Voir plus</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- Categories End -->

    <!-- Products Start -->
    <section class="bg-light py-5">
        <div class="container">
            <h2 class="text-primary fw-bold text-center mb-4">Produits Récemment Ajoutés</h2>
            <div class="row g-4">
                <?php foreach ($produits as $produit) : ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card shadow h-100 border border-danger">
                            <img src="<?= htmlspecialchars($produit['image_url']); ?>" class="card-img-top" alt="<?= htmlspecialchars($produit['reference']); ?>">
                            <div class="card-body text-center">
                                <p class="card-text"><?= htmlspecialchars(substr($produit['description'], 0, 50)); ?>...</p>
                                <p class="text-danger fw-bold"><?= htmlspecialchars($produit['prix']); ?> €</p>
                                <a href="product.php?id=<?= $produit['produit_id']; ?>" class="btn btn-primary"><i class="fas fa-info-circle"></i> Détails</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- Products End -->

    <!-- Section Témoignages Clients -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-primary fw-bold text-center mb-4">Ce que disent nos clients</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <p class="card-text">"Des produits de qualité, un service client réactif et des conseils toujours pertinents. Je recommande vivement Commerciale Industrielle."</p>
                            <h6 class="fw-bold">– Jean Dupont</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <p class="card-text">"Nous avons constaté une réelle amélioration de notre productivité grâce aux solutions d'outillage proposées. Un partenaire de confiance."</p>
                            <h6 class="fw-bold">– Sophie Martin</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <p class="card-text">"La qualité des produits et le suivi personnalisé font toute la différence. Merci à toute l'équipe de Commerciale Industrielle."</p>
                            <h6 class="fw-bold">– Ahmed Benali</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Section Témoignages Clients End -->

    <!-- Call to Action Start -->
    <section class="py-5 text-center bg-primary text-white">
        <h2 class="fw-bold">Besoin d'assistance ?</h2>
        <p class="lead">Contactez-nous pour des conseils personnalisés et des solutions adaptées.</p>
        <a href="contact.php" class="btn btn-light btn-lg"><i class="fas fa-phone me-2"></i> Nous contacter</a>
    </section>
    <!-- Call to Action End -->

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
