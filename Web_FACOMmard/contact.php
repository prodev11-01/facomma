<?php // Démarrer la session
session_start();
 ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Contactez-nous | Commerciale Industrielle</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="contactez-nous, assistance, support client, commerciale industrielle" name="keywords">
    <meta content="Contactez Commerciale Industrielle pour toute question ou demande d'assistance. Nous sommes disponibles pour répondre à vos besoins." name="description">

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
</head>

<body>

    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar start -->
    <?php include 'navbar.php' ?>
    <!-- Navbar End -->

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 bg-primary">
        <h1 class="text-center text-white display-6">Contactez-nous</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="index.php" class="text-white">Accueil</a></li>
            <li class="breadcrumb-item text-white active">Contact</li>
        </ol>
    </div>
    <!-- Page Header End -->

    <!-- Contact Start -->
    <div class="container-fluid contact py-5">
        <div class="container py-5">
            <div class="p-5 bg-light rounded shadow">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <h2 class="text-primary mb-4">Envoyez-nous un message</h2>
                        <form action="contact_handler.php" method="POST">
                            <input type="text" class="form-control border-0 py-3 mb-4" placeholder="Nom complet" name="name" required>
                            <input type="email" class="form-control border-0 py-3 mb-4" placeholder="Email" name="email" required>
                            <textarea class="form-control border-0 mb-4" rows="5" placeholder="Votre message" name="message" required></textarea>
                            <button type="submit" class="btn btn-primary w-100 py-3">Envoyer le message</button>
                        </form>
                    </div>
                    <div class="col-lg-5">
                        <h2 class="text-primary mb-4">Contactez-nous</h2>
                        <div class="d-flex align-items-center mb-4">
                            <i class="fas fa-map-marker-alt fa-2x text-primary me-4"></i>
                            <div>
                                <h5 class="mb-1">Adresse</h5>
                                <p class="mb-0">66 Rue de Montreuil, 75011 Paris, France</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-4">
                            <i class="fas fa-envelope fa-2x text-primary me-4"></i>
                            <div>
                                <h5 class="mb-1">Email</h5>
                                <p class="mb-0">commerciale.fi@wanadoo.fr</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-phone-alt fa-2x text-primary me-4"></i>
                            <div>
                                <h5 class="mb-1">Téléphone</h5>
                                <p class="mb-0">+33 (0)1 43 64 42 00</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <iframe class="w-100 rounded shadow" 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.410377168555!2d2.3885276770505084!3d48.85038447133103!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e672749d6a9599%3A0x6db625a51882b13b!2s66%20Rue%20de%20Montreuil%2C%2075011%20Paris!5e0!3m2!1sfr!2sfr!4v1736959643556!5m2!1sfr!2sfr"
                        style="height: 400px; border:0;" allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->

    <!-- Footer Start -->
    <?php include 'footer.php' ?>
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
