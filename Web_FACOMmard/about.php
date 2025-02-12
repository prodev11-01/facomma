<?php // Démarrer la session
session_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>À propos de nous | Solutions Industrielles & Outillage Professionnel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="solutions industrielles, outillage professionnel, équipement industriel, qualité, expertise, Commerciale Industrielle, Facom, innovation, durabilité">
    <meta name="description" content="Commerciale Industrielle est votre partenaire de confiance pour l'outillage industriel de haute qualité. Forts de plus de 40 ans d'expérience, nous proposons des solutions innovantes et durables pour tous vos besoins professionnels.">
    <meta name="author" content="Commerciale Industrielle">
    <meta name="robots" content="index, follow">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">
    
    <!-- CSS & Libraries -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
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
    <!-- Navbar Start -->
    <?php include 'navbar.php'; ?>
    <!-- Navbar End -->

    <!-- Page Header -->
    <div class="container-fluid page-header py-5 bg-primary text-center">
        <h1 class="text-white display-5 fw-bold">À propos de nous</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="index.php" class="text-white">Accueil</a></li>
                <li class="breadcrumb-item text-white active">À propos</li>
            </ol>
        </nav>
    </div>
    
    <!-- About Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4">
                    <img src="img/banner.png" alt="Notre entreprise" class="img-fluid rounded shadow-lg animate__animated animate__fadeInLeft">
                </div>
                <div class="col-lg-6 animate__animated animate__fadeInRight">
                    <h2 class="text-primary fw-bold">Engagement et expertise</h2>
                    <p>Depuis plus de quatre décennies, <strong>Commerciale Industrielle</strong> se distingue par son engagement envers l'innovation, la fiabilité et la durabilité. Nous nous spécialisons dans la distribution d'équipements industriels de haute performance, notamment les produits de la marque <strong>Facom</strong>, reconnue pour son excellence.</p>
                    <p>Nos solutions sont conçues pour optimiser vos performances industrielles tout en garantissant une durabilité maximale.</p>
                    <div class="mt-4">
                        <h5 class="text-danger">Pourquoi nous choisir ?</h5>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check-circle text-success"></i> Produits testés et certifiés pour une qualité irréprochable.</li>
                            <li><i class="fas fa-check-circle text-success"></i> Partenaire officiel de <strong>Facom</strong> et autres marques de renom.</li>
                            <li><i class="fas fa-check-circle text-success"></i> Accompagnement client personnalisé et assistance 24/7.</li>
                            <li><i class="fas fa-check-circle text-success"></i> Engagement envers des pratiques écoresponsables.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="bg-light py-5">
        <div class="container text-center">
            <h2 class="text-primary fw-bold mb-5">Notre parcours et nos réalisations</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="p-4 bg-white rounded shadow-sm border-start border-primary border-4 animate__animated animate__fadeInUp">
                        <h4 class="text-primary">1983</h4>
                        <p>Fondation de <strong>Commerciale Industrielle</strong>, spécialisée en solutions techniques avancées.</p>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="p-4 bg-white rounded shadow-sm border-start border-primary border-4 animate__animated animate__fadeInUp">
                        <h4 class="text-primary">1990</h4>
                        <p>Début du partenariat avec <strong>Facom</strong>, un gage de qualité et d’innovation.</p>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="p-4 bg-white rounded shadow-sm border-start border-primary border-4 animate__animated animate__fadeInUp">
                        <h4 class="text-primary">2010</h4>
                        <p>Lancement de notre boutique en ligne pour un accès facilité à nos produits.</p>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="p-4 bg-white rounded shadow-sm border-start border-primary border-4 animate__animated animate__fadeInUp">
                        <h4 class="text-primary">2023</h4>
                        <p>Investissement dans l'innovation durable pour une industrie plus verte.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Start -->
    <?php include 'footer.php'; ?>
    <!-- Footer End -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
