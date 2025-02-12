<?php
// Démarrer la session
session_start();
include 'config.php'; // Connexion à la base de données

// Récupération de l'ID de la catégorie
$id_categorie = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupération des informations de la catégorie
$query_categorie = $pdo->prepare("SELECT nom, description FROM categories WHERE categorie_id = ?");
$query_categorie->execute([$id_categorie]);
$categorie = $query_categorie->fetch(PDO::FETCH_ASSOC);

if (!$categorie) {
    die("Catégorie introuvable.");
}

// Récupération des produits de cette catégorie
$query_products = $pdo->prepare("SELECT * FROM produits WHERE categorie_id = ? AND statut = 'actif'");
$query_products->execute([$id_categorie]);
$produits = $query_products->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($categorie['nom']); ?> | Commerciale Industrielle</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="<?= htmlspecialchars($categorie['nom']); ?>, produits industriels, outillage" name="keywords">
    <meta content="Découvrez nos produits dans la catégorie <?= htmlspecialchars($categorie['nom']); ?>." name="description">

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
    <!-- Navbar Start -->
    <?php include 'navbar.php'; ?>
    <!-- Navbar End -->

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 bg-primary">
        <h1 class="text-center text-white display-6"><?= htmlspecialchars($categorie['nom']); ?></h1>
        <p class="text-center text-white"><?= htmlspecialchars($categorie['description']); ?></p>
    </div>
    <!-- Page Header End -->

    <!-- Products Section Start -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <?php foreach ($produits as $produit) : ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card shadow h-100">
                            <img src="<?= htmlspecialchars($produit['image_url']); ?>" class="card-img-top" alt="<?= htmlspecialchars($produit['reference']); ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($produit['reference']); ?></h5>
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
    <!-- Products Section End -->

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
