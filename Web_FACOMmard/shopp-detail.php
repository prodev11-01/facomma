<?php
// Inclure la connexion à la base de données
include 'cnx.php';

// Vérifier si un ID de produit est passé dans l'URL
if (!isset($_GET['produit_id']) || empty($_GET['produit_id'])) {
    echo "Produit introuvable.";
    exit;
}

$product_id = $conn->real_escape_string($_GET['produit_id']);

// Récupérer les détails du produit
$query = "SELECT * FROM produits WHERE produit_id = '$product_id'";
$result = $conn->query($query);

// Vérifier si le produit existe
if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "Produit introuvable.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
    <title>Outillage Industriel | Livraison Gratuite | COMMERCIALE INDUSTRIELLE</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        /* Effet de zoom sur l'image au survol */
.border img {
    transition: transform 0.3s ease; /* Ajoute une transition fluide */
    cursor: pointer; /* Change le curseur pour indiquer l'interaction */
}

.border img:hover {
    transform: scale(1.6); /* Zoomer l'image à 120% */
}


    </style>
</head>

<body>
    
    <?php include 'navbar.php'; ?>
<!-- En-tête de Page -->
<div class="container-fluid page-header py-5 bg-dark text-white">
        <h1 class="text-center display-6">Boutique</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="#" class="text-white">Accueil</a></li>
            <li class="breadcrumb-item text-white active">Boutique</li>
        </ol>
    </div>
    <!-- En-tête de Page End -->
    <div class="container py-5">
        <div class="row g-4 mb-5">
            <div class="col-lg-8 col-xl-9">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="border rounded">
                                <img src="<?php echo $product['image_url']; ?>" class="img-fluid rounded" alt="Image">
                        </div>
                    </div>
                    <div class="col-lg-6 ">
                        <h4 class="fw-bold mb-3"><?php echo $product['reference']; ?></h4>
                        <h5 class="fw-bold mb-3"><?php echo number_format($product['prix'], 2); ?> €</h5>
                        <div class="d-flex mb-4">
                            <i class="fa fa-star text-secondary"></i>
                            <i class="fa fa-star text-secondary"></i>
                            <i class="fa fa-star text-secondary"></i>
                            <i class="fa fa-star text-secondary"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <p class="mb-4"><?php echo $product['description']; ?></p>
                        
                        <a href="#" class="btn border border-secondary rounded-pill px-4 py-2 mb-4 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Ajouter au panier</a>
                   
                        <nav>
                                    <div class="nav nav-tabs mb-3">
                                        <button class="nav-link active border-white border-bottom-0" type="button" role="tab"
                                            id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about"
                                            aria-controls="nav-about" aria-selected="true">Description</button>
                                        <button class="nav-link border-white border-bottom-0" type="button" role="tab"
                                            id="nav-mission-tab" data-bs-toggle="tab" data-bs-target="#nav-mission"
                                            aria-controls="nav-mission" aria-selected="false">Avis</button>
                                    </div>
                                </nav>
                        <div class="tab-content mb-5">
                            <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                <p>Spécifications</p>
                                <!-- Ajouter d'autres informations supplémentaires comme le poids, la qualité, etc. -->
                                <div class="px-2">
                                    <div class="row g-4">
                                        <div class="col-6">
                                        <div class="row bg-light align-items-center text-center justify-content-center py-2">
                                                <div class="col-6">
                                                    <p class="mb-0">Marque</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-0">Facom</p>
                                                </div>
                                            </div>
                                            <div class="row bg-light align-items-center text-center justify-content-center py-2">
                                                <div class="col-6">
                                                    <p class="mb-0">Poids</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-0">Poids</p>
                                                </div>
                                            </div>
                                            <div class="row bg-light text-center align-items-center justify-content-center py-2">
                                                <div class="col-6">
                                                    <p class="mb-0">longueur</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-0">longueur</p>
                                                </div>
                                            </div>
                                            <div class="row bg-light align-items-center text-center justify-content-center py-2">
                                                <div class="col-6">
                                                    <p class="mb-0">largeur</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-0">largeur</p>
                                                </div>
                                            </div>
                                            <div class="row bg-light align-items-center text-center justify-content-center py-2">
                                                <div class="col-6">
                                                    <p class="mb-0">hauteur</p>
                                                </div>
                                                <div class="col-6">
                                                    <p class="mb-0">hauteur</p>
                                                </div>
                                            </div>
                                            <!-- Vous pouvez ajouter d'autres attributs ici -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="nav-mission" role="tabpanel" aria-labelledby="nav-mission-tab">
                                <!-- Ajouter des avis clients dynamiquement depuis la base de données -->
                                <div class="d-flex">
                                    <img src="img/avatar.jpg" class="img-fluid rounded-circle p-3" style="width: 100px; height: 100px;" alt="">
                                    <div class="">
                                        <p class="mb-2" style="font-size: 14px;">April 12, 2024</p>
                                        <div class="d-flex justify-content-between">
                                            <h5>Jason Smith</h5>
                                            <div class="d-flex mb-3">
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star text-secondary"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                        </div>
                                        <p>The generated Lorem Ipsum is therefore always free from repetition injected humour...</p>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>

        <form action="#">
            <h4 class="mb-5 fw-bold">Laissez un avis</h4>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="border-bottom rounded">
                        <input type="text" class="form-control border-0 me-4" placeholder="Votre nom *">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="border-bottom rounded">
                        <input type="email" class="form-control border-0" placeholder="Votre email *">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="border-bottom rounded my-4">
                        <textarea name="" id="" class="form-control border-0" cols="30" rows="8" placeholder="Votre avis *" spellcheck="false"></textarea>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between py-3 mb-5">
                        <div class="d-flex align-items-center">
                            <p class="mb-0 me-3">Veuillez noter :</p>
                            <div class="d-flex align-items-center" style="font-size: 12px;">
                                <i class="fa fa-star text-muted"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div>
                        </div>
                        <a href="#" class="btn border border-secondary text-primary rounded-pill px-4 py-3">Publier un commentaire</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php include 'footer.php'; ?>
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
