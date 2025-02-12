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
        .product-card img {
            height: 200px;
            /* Hauteur uniforme pour toutes les images */
            object-fit: cover;
            /* Coupe l'image pour qu'elle s'adapte */
            width: 100%;
            /* Prend toute la largeur disponible */
        }
        /* Effet de zoom sur l'image au survol */
.product-card img {
    transition: transform 0.3s ease; /* Ajoute une transition fluide */
    cursor: pointer; /* Change le curseur pour indiquer l'interaction */
}

.product-card img:hover {
    transform: scale(1.2); /* Zoomer l'image à 120% */
}


    </style>
</head>

<body>
<?php
// Connexion à la base de données
include 'config.php';

// Initialisation de la requête
$query = "SELECT * FROM produits";
$conditions = [];

// Vérifier si une recherche est effectuée
if (!empty($_GET['rech'])) {
    $rech = $conn->real_escape_string($_GET['rech']);
    $conditions[] = "(code_barre LIKE '%$rech%' OR reference LIKE '%$rech%')";
}

// Vérifier si un filtre de catégorie est appliqué
if (!empty($_GET['category'])) {
    $category = $conn->real_escape_string($_GET['category']);
    $conditions[] = "categorie = '$category'";
}

// Ajouter les conditions à la requête si elles existent
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// Exécuter la requête pour récupérer les produits
$result = $conn->query($query);

// Exécuter une requête pour compter les produits
$countQuery = "SELECT COUNT(*) AS total FROM produits";
if (!empty($conditions)) {
    $countQuery .= " WHERE " . implode(" AND ", $conditions);
}
$countResult = $conn->query($countQuery);
$countRow = $countResult->fetch_assoc();
$totalProducts = $countRow['total'];
?>

    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <?php include 'navbar.php'; ?>
    <!-- Navbar End -->


    <!-- En-tête de Page -->
    <div class="container-fluid page-header py-5 bg-dark text-white">
        <h1 class="text-center display-6">Boutique</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="#" class="text-white">Accueil</a></li>
            <li class="breadcrumb-item text-white active">Boutique</li>
        </ol>
    </div>
    <!-- En-tête de Page End -->

    <!-- Liste des Produits -->
    <div class="container py-5">
        <div class="position-relative mx-auto">
        <h4 class="mb-4">Nombre de produits : <span class="text-primary"><?php echo $totalProducts; ?></span></h4>
            <form action=""> 
                <div style="display: flex; ">
                <input class="form-control border-2 border-danger w-50 py-2 px-3 rounded-pill" name="rech" type="text" placeholder="Recherche par code barre ou par Réference">
                <button type="submit" class="btn btn-danger border-2 border-danger py-2 px-3 rounded-pill text-white h-60" style="top: 0; right: 25%;"><i class="fas fa-search text-primary"></i>Rechercher</button>
        </div></form>
            <div style="justify-content: center;">
            <form method="GET" class="filter-form">
                <label for="category">Produits :</label>
                <select name="category" id="category" class="form-control border-2 border-danger w-50 py-2 px-3 rounded-pill">
                    <option value="">Général</option>
                    <option value="outillage">Promotion</option>
                    <option value="électrique">Expert (moins cher)</option>
                    <option value="mécanique">Distockage</option>
                </select>
            </form>
            </div>
        </div><br>
            <div class="row">
                <?php
                if ($result->num_rows > 0) {
                    while ($product = $result->fetch_assoc()) {
                        if ($product['image_url'] != null) {
                            echo '<div class="col-md-4 mb-4 border-2 border-danger">';
                            echo '    <div class="product-card ">';
                            echo '        <a href="shopp-detail.php?produit_id=' . $product['produit_id']. '" target="_blank"><img src=" ' . $product['image_url'] . ' " class="card-img-top" alt=""></a>';
                            echo '        <div class="card-body">';
                            echo '            <h5 class="card-title">Reference : ' . $product['reference'] . '</h5>';
                            echo '            <p class="card-text">Code barre : ' . $product['code_barre'] . '</p>';
                            echo '            <p class="text-primary">Prix : ' . number_format($product['prix'], 2) . ' €</p>';
                            echo '            <a href="#" class="btn btn-primary">Ajouter au panier</a>';
                            echo '        </div>';
                            echo '    </div>';
                            echo '</div>';
                        }
                    }
                } else {
                    echo '<p class="text-center">Aucun produit trouvé.</p>';
                }
                ?>
            </div>
        
    </div>
    <!-- Liste des Produits End -->

    <!-- Footer Start -->
    <?php include 'footer.php'; ?>
    <!-- Footer End -->

    <!-- JavaScript Libraries -->
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

</body>

</html>