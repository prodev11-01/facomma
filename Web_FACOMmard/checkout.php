<?php
session_start();
include 'config.php';

if (!isset($_SESSION['utilisateur_id'])) {
    die("Vous devez être connecté pour passer à la caisse.");
}

$utilisateur_id = $_SESSION['utilisateur_id'];

// Récupérer les informations de l'utilisateur depuis la table "utilisateurs"
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE utilisateur_id = ?");
$stmt->execute([$utilisateur_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer les produits du panier
$query = $pdo->prepare("SELECT p.*, pa.quantite FROM panier pa INNER JOIN produits p ON pa.produit_id = p.produit_id WHERE pa.utilisateur_id = ?");
$query->execute([$utilisateur_id]);
$panier = $query->fetchAll(PDO::FETCH_ASSOC);

// Calculer le total du panier et la TVA
$total_panier = 0;
$taxe = 0.2; // TVA 20%
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
    <title>Passer à la caisse | Commerciale Industrielle</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="checkout, paiement sécurisé, livraison, commande" name="keywords">
    <meta content="Finalisez votre commande en saisissant vos informations de livraison et en effectuant le paiement." name="description">

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

    <!-- Stripe -->
    <script src="https://js.stripe.com/v3/"></script>

    <!-- PayPal -->
    <script src="https://www.paypal.com/sdk/js?client-id=Adx6kEtQxhi89r4dGv27J-VefYZ9JqeY2NZqsArVje8Mj6K1N8GL0xoesSrMKIW8ARvYZYWBVmewV-jO&currency=EUR"></script>

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

    <!-- Page Header Start -->
    <div class="container-fluid page-header py-5 bg-primary">
        <h1 class="text-center text-white display-6">Passer à la caisse</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="index.php" class="text-white">Accueil</a></li>
            <li class="breadcrumb-item"><a href="cart.php" class="text-white">Panier</a></li>
            <li class="breadcrumb-item text-white active">Caisse</li>
        </ol>
    </div>
    <!-- Page Header End -->

    <!-- Checkout Start -->
    <section class="py-5">
        <div class="container">
            <form action="checkout_process.php" method="POST">
                <div class="row g-4">
                    <!-- Billing Details -->
                    <div class="col-lg-7">
                        <div class="bg-light p-4 rounded shadow">
                            <h3 class="text-primary fw-bold mb-4">Détails de facturation</h3>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">Prénom</label>
                                    <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Votre prénom" required
                                        value="<?= htmlspecialchars($user['prenom']); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Nom</label>
                                    <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Votre nom" required
                                        value="<?= htmlspecialchars($user['nom']); ?>">
                                </div>
                                <div class="col-md-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Votre email" required
                                        value="<?= htmlspecialchars($user['email']); ?>">
                                </div>
                                <div class="col-md-12">
                                    <label for="address" class="form-label">Adresse</label>
                                    <input type="text" id="address" name="address" class="form-control" placeholder="Votre adresse complète" required
                                        value="<?= htmlspecialchars($user['adresse']); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="city" class="form-label">Ville</label>
                                    <input type="text" id="city" name="city" class="form-control" placeholder="Votre ville" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="zip" class="form-label">Code postal</label>
                                    <input type="text" id="zip" name="zip" class="form-control" placeholder="Code postal" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel" id="phone" name="phone" class="form-control" placeholder="Votre numéro de téléphone" required
                                        value="<?= htmlspecialchars($user['telephone']); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-5">
                        <div class="bg-light p-4 rounded shadow">
                            <h3 class="text-primary fw-bold mb-4">Résumé de la commande</h3>
                            <ul class="list-unstyled">
                                <?php foreach ($panier as $item): ?>
                                    <li class="d-flex justify-content-between">
                                        <span><?= htmlspecialchars($item['description']); ?> (x<?= $item['quantite']; ?>)</span>
                                        <span><?= number_format($item['prix'] * $item['quantite'], 2); ?> €</span>
                                    </li>
                                <?php endforeach; ?>
                                <li class="d-flex justify-content-between">
                                    <span>Sous-total :</span>
                                    <span><?= number_format($total_panier, 2); ?> €</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span>TVA (20%) :</span>
                                    <span><?= number_format($montant_taxe, 2); ?> €</span>
                                </li>
                                <li class="d-flex justify-content-between text-danger fw-bold">
                                    <span>Total :</span>
                                    <span><?= number_format($total_avec_taxe, 2); ?> €</span>
                                </li>
                            </ul>

                            <hr>
                            <h4 class="text-primary fw-bold mb-3">Méthode de paiement</h4>
                            <!-- Options de paiement -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="card_payment" value="Carte Bancaire" required>
                                <label class="form-check-label" for="card_payment">
                                    <i class="fas fa-credit-card text-primary"></i> Carte Bancaire
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="paypal_payment" value="PayPal">
                                <label class="form-check-label" for="paypal_payment">
                                    <i class="fab fa-paypal text-primary"></i> PayPal
                                </label>
                            </div>

                            <!-- Conteneur pour le bouton PayPal -->
                            <div id="paypal-button-container" style="display: none; margin-bottom: 1rem;"></div>

                            <!-- Bouton de soumission par défaut (pour paiement par carte) -->
                            <button type="submit" id="default-submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-lock me-2"></i> Valider et payer
                            </button>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- Checkout End -->

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

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var paypalRadio = document.getElementById("paypal_payment");
        var cardRadio = document.getElementById("card_payment");
        var defaultSubmit = document.getElementById("default-submit");
        var paypalContainer = document.getElementById("paypal-button-container");

        // Lorsque l'utilisateur sélectionne PayPal
        paypalRadio.addEventListener("change", function() {
            if (paypalRadio.checked) {
                paypalContainer.style.display = "block";
                defaultSubmit.style.display = "none";
            }
        });

        // Lorsque l'utilisateur sélectionne Carte Bancaire
        cardRadio.addEventListener("change", function() {
            if (cardRadio.checked) {
                paypalContainer.style.display = "none";
                defaultSubmit.style.display = "block";
            }
        });
    });
    </script>

    <script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            // Créez la commande PayPal en utilisant le montant total calculé côté serveur.
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '<?= number_format($total_avec_taxe, 2, '.', '') ?>' // Montant en format "10.00"
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                // Le paiement a été approuvé par PayPal.
                window.location.href = 'confirmation.php?session_id=' + data.orderID;
            });
        },
        onError: function(err) {
            console.error('Erreur lors du paiement PayPal', err);
            alert("Une erreur est survenue lors du paiement PayPal.");
        }
    }).render('#paypal-button-container');
    </script>


</body>

</html>
