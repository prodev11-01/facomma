<?php
// Démarrer la session
session_start();
include 'config.php'; // Connexion à la base de données

// Récupération des catégories pour le filtrage
$query_categories = $pdo->query("SELECT * FROM categories ORDER BY nom ASC");
$categories = $query_categories->fetchAll(PDO::FETCH_ASSOC);

// Initialisation des variables pour les filtres
$search       = isset($_GET['search']) ? trim($_GET['search']) : '';
$categorie_id = isset($_GET['categorie']) ? intval($_GET['categorie']) : 0;
$prix_min     = isset($_GET['prix_min']) ? floatval($_GET['prix_min']) : 0;
$prix_max     = isset($_GET['prix_max']) ? floatval($_GET['prix_max']) : 0;

// Définition du nombre de produits par page
$limit = 50; 

// Détermination de la page actuelle
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
    $page = 1;
}
$offset = ($page - 1) * $limit;

// 1. Requête pour compter le nombre total de produits correspondant aux filtres
$sql_count = "SELECT COUNT(*) FROM produits WHERE statut = 'actif'";
$params_count = [];

// Ajout des filtres pour le comptage
if (!empty($search)) {
    $sql_count .= " AND (description LIKE ? OR reference LIKE ? OR code_barre LIKE ?)";
    $params_count[] = "%$search%";
    $params_count[] = "%$search%";
    $params_count[] = "%$search%";
}
if ($categorie_id > 0) {
    $sql_count .= " AND categorie_id = ?";
    $params_count[] = $categorie_id;
}
if ($prix_min > 0) {
    $sql_count .= " AND prix >= ?";
    $params_count[] = $prix_min;
}
if ($prix_max > 0) {
    $sql_count .= " AND prix <= ?";
    $params_count[] = $prix_max;
}

$stmt_count = $pdo->prepare($sql_count);
$stmt_count->execute($params_count);
$total_products = $stmt_count->fetchColumn();
$total_pages = ceil($total_products / $limit);

// 2. Requête principale pour récupérer les produits avec les filtres et la pagination
$sql = "SELECT * FROM produits WHERE statut = 'actif'";
$params = [];

// Ajout des filtres
if (!empty($search)) {
    $sql .= " AND (description LIKE ? OR reference LIKE ? OR code_barre LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($categorie_id > 0) {
    $sql .= " AND categorie_id = ?";
    $params[] = $categorie_id;
}
if ($prix_min > 0) {
    $sql .= " AND prix >= ?";
    $params[] = $prix_min;
}
if ($prix_max > 0) {
    $sql .= " AND prix <= ?";
    $params[] = $prix_max;
}

$sql .= " ORDER BY RAND()";
// Ajout de la clause LIMIT et OFFSET (les valeurs sont sûres après cast en int)
$sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

$query_products = $pdo->prepare($sql);
$query_products->execute($params);
$produits = $query_products->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Produits | Commerciale Industrielle</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="produits, outillage industriel, outils professionnels" name="keywords">
    <meta content="Découvrez tous nos produits d'outillage industriel avec des options de recherche et de filtrage." name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Styles personnalisés -->
    <style>
        /* Fixe la hauteur des images et adapte l'image pour remplir sans déformer */
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        /* Assure que toutes les cartes aient la même hauteur */
        .card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        /* Force le contenu de la carte à occuper tout l'espace restant */
        .card-body {
            flex: 1 1 auto;
        }

        /* Style de la colonne du filtre (sticky ou fixe selon vos préférences) */
        .filter-sidebar {
            position: fixed;
            bottom: 10px; /* 10px du bas de l'écran */
            left: 20px;   /* 20px de la gauche */
            width: 250px; /* Largeur fixe */
            z-index: 1000;
            background: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
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
        /* Décalage du conteneur des produits pour ne pas être couvert par la barre de filtre */
        .products-container {
            margin-left: 290px; /* 250px de largeur + 40px d'espacement */
        }

        /* Style pour la pagination */
        /* Assurez-vous que la pagination utilise flexbox pour un alignement horizontal */
.pagination {
    display: flex;
    flex-wrap: wrap;  /* Permet de passer à la ligne si nécessaire */
    justify-content: center; /* Centre les liens horizontalement */
    list-style: none; /* Supprime les puces */
    padding-left: 0;  /* Retire le padding par défaut */
}

/* Affiche chaque élément de pagination en ligne */
.pagination .page-item {
    display: inline-block;
    margin: 0 5px; /* Espacement horizontal entre les liens */
}

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
    <!-- Navbar Start -->
    <?php include 'navbar.php'; ?>
    <!-- Navbar End -->

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-4 bg-primary">
        <h1 class="text-center text-white display-6">Tous les Produits</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="index.php" class="text-white">Accueil</a></li>
            <li class="breadcrumb-item text-white active">Produits</li>
        </ol>
    </div>
    <!-- Page Header End -->

    <!-- Filter & Search Start -->
    <section class="py-5">
        <div class="container-fluid">
            <div class="row">
                <!-- Colonne des filtres (à gauche) -->
                <div class="col-md-3">
                    <div class="filter-sidebar">
                        
                        <form method="GET" class="mb-4">
                        <img src="img/logo_facom.svg" alt="logo_facom" style="width: 80%; height: auto;">
                            <div class="row g-3 align-items-end">
                                <!-- Barre de recherche -->
                                <div class="col-12">
                                    <label for="search" class="form-label">Recherche :</label>
                                    <input type="text" id="search" name="search" class="form-control" placeholder="Nom, Référence, ou Code-barre" value="<?= htmlspecialchars($search); ?>">
                                </div>
                                <!-- Filtre par catégorie -->
                                <div class="col-12">
                                    <label for="categorie" class="form-label">Catégorie :</label>
                                    <select id="categorie" name="categorie" class="form-select">
                                        <option value="0">Toutes les catégories</option>
                                        <?php foreach ($categories as $categorie) : ?>
                                            <option value="<?= $categorie['categorie_id']; ?>" <?= ($categorie_id == $categorie['categorie_id']) ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($categorie['nom']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- Filtre par prix -->
                                <div class="col-12">
                                    <label for="prix_min" class="form-label">Prix Min :</label>
                                    <input type="number" step="10" id="prix_min" name="prix_min" class="form-control" placeholder="0" value="<?= htmlspecialchars($prix_min); ?>">
                                </div>
                                <div class="col-12">
                                    <label for="prix_max" class="form-label">Prix Max :</label>
                                    <input type="number" step="10" id="prix_max" name="prix_max" class="form-control" placeholder="0" value="<?= htmlspecialchars($prix_max); ?>">
                                </div>
                                <!-- Bouton de recherche -->
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i> Rechercher
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Colonne des produits (à droite) -->
                <div class="col-md-9 products-container">
                    <div class="row g-4">
                        <?php if (count($produits) > 0): ?>
                            <?php foreach ($produits as $produit): ?>
                                <?php if (!empty($produit['image_url'])): ?>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="card shadow h-100 border border-danger">
                                            <img src="<?= htmlspecialchars($produit['image_url']); ?>" class="card-img-top" alt="<?= htmlspecialchars($produit['reference']); ?>">
                                            <div class="card-body text-center border-2 border-danger">
                                                <h5 class="card-title"><?= htmlspecialchars($produit['reference']); ?></h5>
                                                <p class="card-text"><?= htmlspecialchars(substr($produit['description'], 0, 50)); ?>...</p>
                                                <p class="text-danger fw-bold"><?= htmlspecialchars($produit['prix']); ?> €</p>
                                            </div>
                                            <div class="card-footer text-center bg-white border-0 pb-3">
                                                <a href="product.php?id=<?= $produit['produit_id']; ?>" class="btn btn-primary">
                                                    <i class="fas fa-info-circle"></i> Détails
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <p class="text-center text-muted">Aucun produit ne correspond à votre recherche.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination">
                                <!-- Lien vers la page précédente -->
                                <?php if ($page > 1): ?>
                                    <?php 
                                    $prev_query = http_build_query(array_merge($_GET, ['page' => $page - 1]));
                                    ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?<?= $prev_query; ?>" aria-label="Précédent">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">&laquo;</span>
                                    </li>
                                <?php endif; ?>

                                <!-- Liens vers chaque page -->
                                <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                                    <?php 
                                    $page_query = http_build_query(array_merge($_GET, ['page' => $p]));
                                    $activeClass = ($p == $page) ? ' active' : '';
                                    ?>
                                    <li class="page-item<?= $activeClass; ?>">
                                        <a class="page-link" href="?<?= $page_query; ?>"><?= $p; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Lien vers la page suivante -->
                                <?php if ($page < $total_pages): ?>
                                    <?php 
                                    $next_query = http_build_query(array_merge($_GET, ['page' => $page + 1]));
                                    ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?<?= $next_query; ?>" aria-label="Suivant">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">&raquo;</span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <!-- Filter & Search End -->

    <!-- Footer Start -->
    <?php include 'footer.php'; ?>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top">
        <i class="fa fa-arrow-up"></i>
    </a>

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
