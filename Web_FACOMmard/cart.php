<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("location: login.php");
    exit();
}

include 'config.php'; // Connexion à la base de données

$utilisateur_id = $_SESSION['utilisateur_id']; // ID de l'utilisateur

// Fonction pour supprimer un produit du panier
function removeProduct($utilisateur_id, $produit_id) {
    global $pdo;
    $query_remove = $pdo->prepare("DELETE FROM panier WHERE utilisateur_id = ? AND produit_id = ?");
    $query_remove->execute([$utilisateur_id, $produit_id]);
}

// Fonction pour mettre à jour la quantité d'un produit
function updateQuantity($utilisateur_id, $produit_id, $quantite) {
    global $pdo;
    // Validation de la quantité (doit être un entier positif)
    if ($quantite > 0) {
        $query_update = $pdo->prepare("UPDATE panier SET quantite = ? WHERE utilisateur_id = ? AND produit_id = ?");
        $query_update->execute([$quantite, $utilisateur_id, $produit_id]);
    } else {
        echo "Quantité invalide.";
    }
}

// Supprimer un produit
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $produit_id = $_GET['remove'];
    removeProduct($utilisateur_id, $produit_id);
    header("Location: cart.php"); // Rediriger après la suppression
    exit();
}

// Mettre à jour la quantité d'un produit
if (isset($_POST['update_quantity']) && isset($_POST['produit_id']) && isset($_POST['quantite'])) {
    $produit_id = $_POST['produit_id'];
    $quantite = $_POST['quantite'];
    updateQuantity($utilisateur_id, $produit_id, $quantite);
}

// Vider le panier
if (isset($_GET['clear'])) {
    $query_clear = $pdo->prepare("DELETE FROM panier WHERE utilisateur_id = ?");
    $query_clear->execute([$utilisateur_id]);
    header("Location: cart.php"); // Rediriger après avoir vidé le panier
    exit();
}

// Récupérer les produits dans le panier
$query = $pdo->prepare("SELECT p.*, pa.quantite FROM panier pa INNER JOIN produits p ON pa.produit_id = p.produit_id WHERE pa.utilisateur_id = ?");
$query->execute([$utilisateur_id]);
$panier = $query->fetchAll(PDO::FETCH_ASSOC);

// Calcul du total du panier et de la TVA
$total_panier = 0;
$taxe = 0.2; // Exemple de TVA de 20%
foreach ($panier as $item) {
    $total_panier += $item['prix'] * $item['quantite'];
}
$montant_taxe = $total_panier * $taxe;
$total_avec_taxe = $total_panier + $montant_taxe;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Votre panier | Commerciale Industrielle</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="outillage industriel, outils professionnels, livraison rapide, produits Facom" name="keywords">
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
    
    <!-- Styles personnalisés -->
    <style>
        /* Limiter la taille des images du panier */
        .cart-image {
            max-width: 50px;
            max-height: 50px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<!-- Page Header Start -->
<div class="container-fluid page-header py-5 bg-primary">
    <h1 class="text-center text-white display-6">Votre Panier</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="index.php" class="text-white">Accueil</a></li>
        <li class="breadcrumb-item text-white active">Panier</li>
    </ol>
</div>
<!-- Page Header End -->

<div class="container mt-5">
    <h1 class="text-center mb-4">Votre Panier</h1>

    <?php if (count($panier) > 0): ?>
        <form action="cart.php" method="post">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Prix Unitaire</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($panier as $item): ?>
                            <tr>
                                <td>
                                    <!-- Affichage de l'image avec la classe .cart-image pour limiter sa taille -->
                                    <img src="images/<?= htmlspecialchars($item['image_url']); ?>" alt="<?= htmlspecialchars($item['description']); ?>" class="cart-image">
                                    <?= htmlspecialchars($item['description']); ?>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="quantite" value="<?= $item['quantite']; ?>" min="1" required>
                                    <input type="hidden" name="produit_id" value="<?= $item['produit_id']; ?>">
                                    <button type="submit" name="update_quantity" class="btn btn-warning mt-2">Mettre à jour</button>
                                </td>
                                <td><?= htmlspecialchars($item['prix']); ?> €</td>
                                <td><?= htmlspecialchars($item['prix'] * $item['quantite']); ?> €</td>
                                <td>
                                    <a href="cart.php?remove=<?= $item['produit_id']; ?>" class="btn btn-danger">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <h3>Total du Panier : <?= htmlspecialchars($total_panier); ?> €</h3>
            <h4>Montant de la TVA (20%) : <?= htmlspecialchars($montant_taxe); ?> €</h4>
            <h4>Total avec TVA : <?= htmlspecialchars($total_avec_taxe); ?> €</h4>

            <div class="d-flex justify-content-between mt-4">
                <a href="checkout.php" class="btn btn-success btn-lg">Passer à la caisse</a>
                <a href="cart.php?clear=true" class="btn btn-danger btn-lg">Vider le panier</a>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-warning">Votre panier est vide.</div>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
