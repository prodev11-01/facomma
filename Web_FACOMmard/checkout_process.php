<?php
session_start();
include 'config.php';
require_once 'vendor/autoload.php';

// Configurez votre clé secrète Stripe
\Stripe\Stripe::setApiKey('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');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $pdo->beginTransaction();

        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur_id'])) {
            die("Erreur : utilisateur non connecté.");
        }
        $utilisateur_id = $_SESSION['utilisateur_id'];

        // Récupération des infos du formulaire
        $prenom         = htmlspecialchars($_POST['first_name']);
        $nom            = htmlspecialchars($_POST['last_name']);
        $email          = htmlspecialchars($_POST['email']);
        $adresse        = htmlspecialchars($_POST['address']);
        $ville          = htmlspecialchars($_POST['city']);
        $code_postal    = htmlspecialchars($_POST['zip']);
        $telephone      = htmlspecialchars($_POST['phone']);

        // Récupérer les produits du panier
        $query = $pdo->prepare("SELECT p.produit_id, p.reference, p.prix, c.quantite 
                                FROM panier c 
                                JOIN produits p ON c.produit_id = p.produit_id 
                                WHERE c.utilisateur_id = ?");
        $query->execute([$utilisateur_id]);
        $produits = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!$produits) {
            die("Erreur : Votre panier est vide.");
        }

        $montant_total = 0;
        $line_items = [];
        foreach ($produits as $produit) {
            $montant_total += $produit['prix'] * $produit['quantite'];

            // Préparez chaque article pour Stripe Checkout
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $produit['prix'] * 100, // montant en centimes
                    'product_data' => [
                        'name' => $produit['reference'],
                    ],
                ],
                'quantity' => $produit['quantite'],
            ];
        }

        // Créer un token unique pour identifier ce panier temporaire
        $panier_token = bin2hex(random_bytes(8));

        // Stocker les informations de commande et du panier dans la table temporaire
        $data = json_encode([
            'first_name' => $prenom,
            'last_name'  => $nom,
            'email'      => $email,
            'address'    => $adresse,
            'city'       => $ville,
            'zip'        => $code_postal,
            'phone'      => $telephone,
            'produits'   => $produits
        ]);
        $stmt = $pdo->prepare("INSERT INTO panier_temp (panier_token, utilisateur_id, data) VALUES (?, ?, ?)");
        $stmt->execute([$panier_token, $utilisateur_id, $data]);

        // Définir votre domaine (modifiez-le en fonction de votre environnement)
        $YOUR_DOMAIN = 'http://localhost/web_facommard';

        // Créer la session Stripe Checkout en incluant le panier_token dans les métadonnées
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'customer_email' => $email,
            'metadata' => [
                'panier_token' => $panier_token,
            ],
            'success_url' => $YOUR_DOMAIN . "/confirmation.php?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => $YOUR_DOMAIN . "/products.php",
        ]);

        $pdo->commit();

        // Rediriger l'utilisateur vers Stripe pour le paiement
        header("Location: " . $session->url);
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erreur : " . $e->getMessage());
    }
} else {
    die("Accès non autorisé.");
}
?>
