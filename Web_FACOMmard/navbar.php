<?php

// Initialisation du total d'articles à zéro
$cartTotal = 0;

// Si l'utilisateur est connecté, on tente de récupérer le total des quantités dans son panier depuis la base de données
if (isset($_SESSION['utilisateur_id'])) {
    include 'config.php'; // Assurez-vous que ce fichier définit bien la variable $pdo (l'objet PDO)

    // Préparer la requête pour sommer la quantité de tous les produits dans le panier pour cet utilisateur
    $stmt = $pdo->prepare("SELECT SUM(quantite) AS total FROM panier WHERE utilisateur_id = ?");
    $stmt->execute([$_SESSION['utilisateur_id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si le résultat existe et n'est pas nul, on l'affecte à $cartTotal
    $cartTotal = $result['total'] ? $result['total'] : 0;
}
?>
<!-- Navbar Start -->
<div class="container-fluid fixed-top px-0">
    <!-- Topbar -->
    <div class="container-fluid bg-danger d-none d-lg-block py-2">
        <div class="container d-flex justify-content-between text-white">
            <div>
                <small>
                    <i class="fas fa-map-marker-alt text-danger"></i>
                    <a href="#" class="text-white text-decoration-none">66 RUE DE MONTRIEUL, 75011, PARIS</a>
                </small>
                <small class="ms-3">
                    <i class="fas fa-envelope text-danger"></i>
                    <a href="mailto:commerciale.fi@wanadoo.fr" class="text-white text-decoration-none">commerciale.fi@wanadoo.fr</a>
                </small>
            </div>
            <div>
                <a href="#" class="text-white text-decoration-none me-2">Privacy Policy</a> |
                <a href="#" class="text-white text-decoration-none mx-2">Terms of Use</a> |
                <a href="#" class="text-white text-decoration-none ms-2">Sales and Refunds</a>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-xl bg-white shadow">
        <div class="container d-flex justify-content-between align-items-center">
            <!-- Partie gauche : Logo et texte -->
            <a href="index.php" class="navbar-brand d-flex align-items-center">
                <!-- Logo "COFI" avec SVG -->
                <div class="me-2" style="display: inline-flex; align-items: center;">
                    <span class="h1 m-0 text-danger fw-bold" style="line-height: 1;"><strong>C</strong></span>
                    <!-- SVG de l'écrou pour le "O" -->
                    <svg width="2em" height="2em"  viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" style="vertical-align: middle; color:black;">
                        <!-- Hexagone pour représenter l'écrou -->
                        <polygon points="50,5 90,25 90,75 50,95 10,75 10,25" fill="currentColor" />
                        <!-- Cercle central -->
                        <circle cx="50" cy="50" r="20" fill="#fff" />
                    </svg>
                    <span class="h1 m-0 text-danger fw-bold" style="line-height: 1;"><strong>FI</strong></span>
                </div>
                <!-- Texte complémentaire -->
                <div>
                    <h5 class="h1 m-0 text-danger fw-bold">Commerciale Industrielle</h5>
                </div>
            </a>

            <!-- Bouton Toggler pour mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-bars text-danger"></span>
            </button>

            <!-- Partie centrale : Menu -->
            <div class="collapse navbar-collapse justify-content-center" id="navbarCollapse">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">
                            <i class="fas fa-home text-danger"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="about.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'about.php' ? 'active' : '' ?>">
                            <i class="fas fa-info-circle text-danger"></i> À propos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="categories.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'categories.php' ? 'active' : '' ?>">
                            <i class="fas fa-th-large text-danger"></i> Catégories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="products.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'products.php' ? 'active' : '' ?>">
                            <i class="fas fa-box-open text-danger"></i> Produits
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="cart.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'cart.php' ? 'active' : '' ?>">
                            <i class="fas fa-shopping-cart text-danger"><span id="cart-count" class="badge bg-danger ms-1"><?= $cartTotal ?></span></i> Panier
                            <!-- Affichage du nombre total d'articles dans le panier (affiche 0 si vide) -->
                            
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="contact.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'contact.php' ? 'active' : '' ?>">
                            <i class="fas fa-envelope text-danger"></i> Contact
                        </a>
                    </li>
                </ul>
            </div>

            <!-- profil -->
            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['email'])) { ?>
                    <a href="profil.php" class="btn btn-outline-danger">
                        <i class="fas fa-user-alt"></i> Profil
                    </a>
                <?php } ?>
            </div>
            <!-- Partie droite : Boutons utilisateur -->
            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['email'])) { ?>
                    <a href="logout.php" class="btn btn-outline-danger">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                <?php } else { ?>
                    <a href="login.php" class="btn btn-outline-danger">
                        <i class="fas fa-user"></i> Connexion
                    </a>
                <?php } ?>
            </div>
        </div>
    </nav>
</div>
<!-- Navbar End -->