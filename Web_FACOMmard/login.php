<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Outillage Industriel | Livraison Gratuite dès 3 Produits Commandés | Paiement Sécurisé | COMMERCIALE INDUSTIELLE</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

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
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar start -->
    <?php include 'navbar.php' ?>
    <!-- Navbar End -->
    <br><br><br><br><br>

    <div class="container-fluid contact py-5">
        <div class="container py-5">
            <div class="p-5 bg-light rounded">
                <div class="row g-4">

                    <div class="col-lg-11">
                    <form action="trait_login.php" method="POST">
    <label class="w-100 form-control border-danger py-3 bg-white text-primary" style="display: flex; justify-content: center;">
        <strong>Connexion</strong>
    </label>
    
    <!-- Message d'erreur -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger text-center">
            <?= htmlspecialchars($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Champs de connexion -->
    <input type="email" class="w-100 border-danger form-control border-0 py-3 mb-4" name="email" placeholder="Email" required>
    <input type="password" class="w-100 border-danger form-control border-0 py-3 mb-4" name="mot_de_passe" placeholder="Mot de passe" required>
    
    <!-- Boutons -->
    <button class="w-100 btn form-control border-danger py-3 bg-white text-primary" type="submit" name="submit">Se connecter</button>
    <a class="w-100 btn form-control border-danger py-3 bg-white text-primary" href="inscription.php">S'inscrire</a>
</form>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Footer Start -->
    <?php include 'footer.php'?>
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