<?php
include 'config.php'; // Connexion à la base de données

// Démarrer la session
session_start();

// Récupération de l'ID du produit
$id_produit = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupération des détails du produit
$query_product = $pdo->prepare("SELECT * FROM produits WHERE produit_id = ?");
$query_product->execute([$id_produit]);
$produit = $query_product->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    die("Produit introuvable.");
}

// Gestion des formulaires POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si l'utilisateur soumet un avis
    if (isset($_POST['review_submit'])) {
        // Vérification de la connexion de l'utilisateur
        if (!isset($_SESSION['utilisateur_id'])) {
            die("Veuillez vous connecter pour laisser un avis.");
        }
        $rating = intval($_POST['rating']);
        $commentaire = trim($_POST['commentaire']);
        
        // Vérification de la note
        if ($rating < 1 || $rating > 5) {
            $rating = 5;
        }
        // Vérification du commentaire
        if (empty($commentaire)) {
            $commentaire = "Aucun commentaire.";
        }
        $utilisateur_id = $_SESSION['utilisateur_id'];
        
        // Insertion de l'avis dans la table 'avis'
        $query_insert_review = $pdo->prepare("INSERT INTO avis (produit_id, utilisateur_id, rating, commentaire, date_ajout) VALUES (?, ?, ?, ?, NOW())");
        $query_insert_review->execute([$id_produit, $utilisateur_id, $rating, $commentaire]);
        
        // Redirection pour actualiser la page et afficher le nouvel avis
        header("Location: product.php?id=" . $id_produit);
        exit();
    }
    // Traitement du formulaire d'ajout au panier (déjà présent)
    else {
        $produit_id = intval($_POST['produit_id']);
        $quantite = intval($_POST['quantite']);
        $utilisateur_id = $_SESSION['utilisateur_id']; // ID de l'utilisateur connecté

        // Vérifier si le produit est déjà dans le panier de l'utilisateur
        $query_check = $pdo->prepare("SELECT * FROM panier WHERE utilisateur_id = ? AND produit_id = ?");
        $query_check->execute([$utilisateur_id, $produit_id]);
        $existing_product = $query_check->fetch(PDO::FETCH_ASSOC);

        if ($existing_product) {
            // Si le produit existe déjà, on met à jour la quantité
            $new_quantite = $existing_product['quantite'] + $quantite;
            $query_update = $pdo->prepare("UPDATE panier SET quantite = ? WHERE panier_id = ?");
            $query_update->execute([$new_quantite, $existing_product['panier_id']]);
        } else {
            // Sinon, on ajoute le produit au panier
            $query_add = $pdo->prepare("INSERT INTO panier (utilisateur_id, produit_id, quantite, date_ajout) VALUES (?, ?, ?, NOW())");
            $query_add->execute([$utilisateur_id, $produit_id, $quantite]);
        }

        // Rediriger vers la page du panier
        header('Location: cart.php');
        exit();
    }
}

// Récupération de la catégorie associée au produit
$query_categorie = $pdo->prepare("SELECT description FROM categories WHERE categorie_id = ?");
$query_categorie->execute([$produit['categorie_id']]);
$categorie = $query_categorie->fetch(PDO::FETCH_ASSOC);

// Récupération des avis pour ce produit
$query_reviews = $pdo->prepare("
    SELECT a.*, u.nom, u.prenom 
    FROM avis a 
    LEFT JOIN utilisateurs u ON a.utilisateur_id = u.utilisateur_id 
    WHERE a.produit_id = ? 
    ORDER BY a.date_ajout DESC
");
$query_reviews->execute([$id_produit]);
$avis = $query_reviews->fetchAll(PDO::FETCH_ASSOC);

// Récupération de quelques produits aléatoires (exclure le produit affiché, par exemple)
$query_random = $pdo->prepare("SELECT * FROM produits WHERE produit_id != ? ORDER BY RAND() LIMIT 4");
$query_random->execute([$id_produit]);
$produits_aleatoires = $query_random->fetchAll(PDO::FETCH_ASSOC);

// Préparer les données structurées pour le produit en JSON-LD
$productStructuredData = [
    "@context"       => "https://schema.org/",
    "@type"          => "Product",
    "name"           => $produit['description'],
    "image"          => htmlspecialchars($produit['image_url']),
    "description"    => strip_tags($produit['description']),
    "sku"            => $produit['reference'],
    "mpn"            => $produit['code_barre'],
    "brand"          => [
        "@type" => "Thing",
        "name"  => "Facom" // À personnaliser selon votre base de données
    ],
    "offers"         => [
        "@type"         => "Offer",
        "url"           => "https://www.votresite.com/product.php?id=" . $produit['produit_id'],
        "priceCurrency" => "EUR",
        "price"         => $produit['prix'],
        "availability"  => "https://schema.org/InStock"
    ]
];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($produit['description']); ?> | Commerciale Industrielle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Meta SEO -->
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="https://www.votresite.com/product.php?id=<?= $produit['produit_id']; ?>">
    <meta name="keywords" content="<?= htmlspecialchars($produit['description']); ?>, produit industriel, <?= htmlspecialchars($categorie['description']); ?>">
    <meta name="description" content="Découvrez <?= htmlspecialchars($produit['description']); ?>, un produit de qualité industrielle dans la catégorie <?= htmlspecialchars($categorie['description']); ?>.">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:title" content="<?= htmlspecialchars($produit['description']); ?> | Commerciale Industrielle">
    <meta property="og:description" content="Découvrez <?= htmlspecialchars($produit['description']); ?>, un produit de qualité industrielle dans la catégorie <?= htmlspecialchars($categorie['description']); ?>.">
    <meta property="og:image" content="<?= htmlspecialchars($produit['image_url']); ?>">
    <meta property="og:url" content="https://www.votresite.com/product.php?id=<?= $produit['produit_id']; ?>">
    <meta property="og:type" content="product">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($produit['description']); ?> | Commerciale Industrielle">
    <meta name="twitter:description" content="Découvrez <?= htmlspecialchars($produit['description']); ?>, un produit de qualité industrielle dans la catégorie <?= htmlspecialchars($categorie['description']); ?>.">
    <meta name="twitter:image" content="<?= htmlspecialchars($produit['image_url']); ?>">
    
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
    
    <!-- Structured Data JSON-LD -->
    <script type="application/ld+json">
        <?= json_encode($productStructuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
    </script>
    <style>
        /* Style pour la notation par étoiles */
        .star-rating {
            direction: rtl; /* Inverse l'ordre pour permettre le survol de droite à gauche */
            display: inline-flex;
            font-size: 2rem;
        }

        body {
            background: linear-gradient(135deg, #fff 40%, #b71c1c 100%);
            font-family: 'Raleway', sans-serif;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
            margin: 0 2px;
        }

        /* Affiche l'étoile en jaune si sélectionnée */
        .star-rating input[type="radio"]:checked ~ label {
            color: #ffc107;
        }

        /* Au survol, affiche toutes les étoiles avant celle survolée en jaune */
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #ffc107;
        }

        /* Conteneur de l'image pour masquer le débordement lors du zoom */
.img-zoom {
  overflow: hidden;
  position: relative;
}

/* L'image avec une transition pour le zoom */
.img-zoom img {
  transition: transform 0.3s ease;
}

/* Au survol, l'image s'agrandit */
.img-zoom:hover img {
  transform: scale(1.3);
}

    </style>
</head>

<body>
    <!-- Header / Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Page Header Start -->
    <header class="container-fluid page-header py-5 bg-primary">
        <div class="container">
            <h1 class="text-center text-white display-6"><?= htmlspecialchars($produit['description']); ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="index.php" class="text-white">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="categories.php" class="text-white">Catégories</a></li>
                    <li class="breadcrumb-item"><a href="category.php?id=<?= $produit['categorie_id']; ?>" class="text-white"><?= htmlspecialchars($categorie['description']); ?></a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page"><?= htmlspecialchars($produit['description']); ?></li>
                </ol>
            </nav>
        </div>
    </header>
    <!-- Page Header End -->

    <!-- Main Content -->
    <main>
        <!-- Product Details Start -->
        <section class="py-5">
            <div class="container">
                <div class="row g-5">
                    <!-- Product Image -->
                    <div class="col-lg-6 img-zoom">
                        <img src="<?= htmlspecialchars($produit['image_url']); ?>" alt="<?= htmlspecialchars($produit['description']); ?>" class="img-fluid rounded shadow mb-3">
                    </div>

                    <!-- Product Info -->
                    <div class="col-lg-6">
                        <article>
                            <h2 class="text-primary fw-bold"><?= htmlspecialchars($produit['description']); ?></h2>
                            <p class="text-primary"><strong>Référence :</strong> <?= htmlspecialchars($produit['reference']); ?></p>
                            <p class="text-primary"><strong>Code-barre :</strong> <?= htmlspecialchars($produit['code_barre']); ?></p>
                            <p class="lead text-danger fw-bold">Prix : <?= htmlspecialchars($produit['prix']); ?> €</p>
                            <p><?= htmlspecialchars($produit['description']); ?></p>

                            <h3 class="mt-4 text-primary">Caractéristiques :</h3>
                            <ul>
                                <li><strong>Marque :</strong> Facom</li>
                                <li><strong>Poids :</strong> <?= htmlspecialchars($produit['poids']); ?> kg</li>
                                <li><strong>Hauteur :</strong> <?= htmlspecialchars($produit['hauteur']); ?> cm</li>
                                <li><strong>Largeur :</strong> <?= htmlspecialchars($produit['largeur']); ?> cm</li>
                                <li><strong>Longueur :</strong> <?= htmlspecialchars($produit['longueur']); ?> cm</li>
                            </ul>
                        </article>

                        <!-- Form to Add to Cart -->
                        <form action="" method="POST" class="mt-4" aria-label="Ajouter au panier">
                            <input type="hidden" name="produit_id" value="<?= $produit['produit_id']; ?>">
                            <div class="d-flex align-items-center">
                                <input type="number" name="quantite" class="form-control w-25 me-3" min="1" value="1" required aria-label="Quantité">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-shopping-cart me-2"></i> Ajouter au panier
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- Product Details End -->

        <!-- Section d'avis -->
        <?php if(isset($_SESSION['utilisateur_id'])): ?>
            <section class="py-5">
                <div class="container">
                    <h2 class="text-center mb-4">Laissez un avis</h2>
                    <form action="" method="POST">
                        <input type="hidden" name="produit_id" value="<?= $produit['produit_id']; ?>">
                        
                        <!-- Section pour la notation par étoiles -->
                        <div class="mb-3">
                            <label class="form-label d-block">Note (1 à 5)</label>
                            <div class="star-rating">
                                <input id="star5" type="radio" name="rating" value="5" required>
                                <label for="star5" title="5 étoiles"><i class="fas fa-star"></i></label>
                                
                                <input id="star4" type="radio" name="rating" value="4">
                                <label for="star4" title="4 étoiles"><i class="fas fa-star"></i></label>
                                
                                <input id="star3" type="radio" name="rating" value="3">
                                <label for="star3" title="3 étoiles"><i class="fas fa-star"></i></label>
                                
                                <input id="star2" type="radio" name="rating" value="2">
                                <label for="star2" title="2 étoiles"><i class="fas fa-star"></i></label>
                                
                                <input id="star1" type="radio" name="rating" value="1">
                                <label for="star1" title="1 étoile"><i class="fas fa-star"></i></label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="commentaire" class="form-label">Votre commentaire</label>
                            <textarea name="commentaire" id="commentaire" class="form-control" rows="4" required></textarea>
                        </div>
                        <button type="submit" name="review_submit" class="btn btn-primary">Envoyer l'avis</button>
                    </form>
                </div>
            </section>
        <?php else: ?>
            <section class="py-5">
                <div class="container text-center">
                    <p>Veuillez vous connecter pour laisser un avis.</p>
                </div>
            </section>
        <?php endif; ?>

        <!-- Section des avis clients -->
        <section class="py-5 bg-light">
            <div class="container">
                <h2 class="text-center mb-5">Avis Clients</h2>
                <?php if(count($avis) > 0) { ?>
                    <div id="reviewsCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach($avis as $index => $review) { ?>
                                <div class="carousel-item <?php if($index == 0) echo 'active'; ?>">
                                    <div class="card border-0 shadow-sm mx-auto" style="max-width: 600px;">
                                        <div class="card-body">
                                            <h3 class="card-title">
                                                <?= (isset($review['nom']) && isset($review['prenom'])) 
                                                    ? htmlspecialchars($review['nom'] . ' ' . $review['prenom']) 
                                                    : 'Utilisateur anonyme'; ?>
                                            </h3>
                                            <p class="card-text"><?= htmlspecialchars($review['commentaire']); ?></p>
                                            <div aria-label="Note">
                                                <?php
                                                $rating = intval($review['rating']);
                                                for ($i = 1; $i <= 5; $i++) {
                                                    echo ($i <= $rating)
                                                        ? '<i class="fas fa-star text-warning" aria-hidden="true"></i>'
                                                        : '<i class="far fa-star text-warning" aria-hidden="true"></i>';
                                                }
                                                ?>
                                            </div>
                                            <p class="text-muted mt-2"><?= date("d/m/Y", strtotime($review['date_ajout'])); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#reviewsCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Précédent</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#reviewsCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Suivant</span>
                        </button>
                    </div>
                <?php } else { ?>
                    <p class="text-center">Aucun avis pour ce produit. Soyez le premier à donner votre avis !</p>
                <?php } ?>
            </div>
        </section>
        <!-- Avis Clients End -->

        <!-- Section des produits aléatoires -->
        <section class="py-5">
            <div class="container">
                <h2 class="text-center mb-5">Produits Aléatoires</h2>
                <div class="row">
                    <?php if(!empty($produits_aleatoires)) : ?>
                        <?php foreach($produits_aleatoires as $prod) : ?>
                            <div class="col-md-3 mb-4">
                                <div class="card h-100">
                                    <img src="<?= htmlspecialchars($prod['image_url']); ?>" class="card-img-top" alt="<?= htmlspecialchars($prod['description']); ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($prod['description']); ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($prod['prix']); ?> €</p>
                                        <a href="product.php?id=<?= $prod['produit_id']; ?>" class="btn btn-primary btn-sm">Voir le produit</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">Aucun produit aléatoire à afficher.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <!-- Produits Aléatoires End -->
    </main>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top" aria-label="Back to top">
        <i class="fa fa-arrow-up"></i>
    </a>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>
