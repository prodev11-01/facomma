<?php
// Démarrer la session
session_start();
require_once 'vendor/autoload.php';
include 'config.php';

// Configurez votre clé API Stripe
\Stripe\Stripe::setApiKey('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');

// Récupérer l'évènement brut envoyé par Stripe
$payload = @file_get_contents('php://input');
$sig_header = isset($_SERVER['HTTP_STRIPE_SIGNATURE']) ? $_SERVER['HTTP_STRIPE_SIGNATURE'] : '';
$endpoint_secret = 'votre_endpoint_secret'; // Remplacez par votre endpoint secret fourni par Stripe

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, 
        $sig_header, 
        $endpoint_secret
    );
} catch (\UnexpectedValueException $e) {
    http_response_code(400);
    exit('Invalid payload');
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    http_response_code(400);
    exit('Invalid signature');
}

// Traiter l'évènement de paiement réussi
if ($event->type === 'checkout.session.completed') {
    $session = $event->data->object;

    // Récupérer le panier_token depuis les métadonnées
    if (isset($session->metadata->panier_token)) {
        $panier_token = $session->metadata->panier_token;

        // Récupérer les données stockées dans la table temporaire
        $stmt = $pdo->prepare("SELECT * FROM panier_temp WHERE panier_token = ?");
        $stmt->execute([$panier_token]);
        $panier_temp = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($panier_temp) {
            $data = json_decode($panier_temp['data'], true);
            $utilisateur_id = $panier_temp['utilisateur_id'];
            $date_commande  = date("Y-m-d H:i:s");

            // Calculer le montant total
            $montant_total = 0;
            foreach ($data['produits'] as $produit) {
                $montant_total += $produit['prix'] * $produit['quantite'];
            }
            // Construire l'adresse de livraison
            $adresse_livraison = "{$data['address']}, {$data['city']}, {$data['zip']}";

            // Insérer la commande dans la table commandes (en incluant le panier_token pour le lien)
            $query = $pdo->prepare("INSERT INTO commandes (utilisateur_id, date_commande, statut_commande, adresse_livraison, montant_total, panier_token) VALUES (?, ?, ?, ?, ?, ?)");
            $query->execute([$utilisateur_id, $date_commande, 'Payé', $adresse_livraison, $montant_total, $panier_token]);
            $commande_id = $pdo->lastInsertId();

            // Insérer les détails de commande dans details_commande
            $query_detail = $pdo->prepare("INSERT INTO details_commande (commande_id, produit_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
            foreach ($data['produits'] as $produit) {
                $query_detail->execute([$commande_id, $produit['produit_id'], $produit['quantite'], $produit['prix']]);
            }

            // Enregistrer le paiement dans la table paiements
            $montant = $session->amount_total / 100; // conversion en euros
            $date_paiement = date("Y-m-d H:i:s");
            $query2 = $pdo->prepare("INSERT INTO paiements (commande_id, montant, date_paiement, statut_paiement) VALUES (?, ?, ?, ?)");
            $query2->execute([$commande_id, $montant, $date_paiement, 'Payé']);

            // Supprimer l'enregistrement temporaire
            $stmt = $pdo->prepare("DELETE FROM panier_temp WHERE panier_token = ?");
            $stmt->execute([$panier_token]);

            // Vider le panier de l'utilisateur
            $stmt = $pdo->prepare("DELETE FROM panier WHERE utilisateur_id = ?");
            $stmt->execute([$utilisateur_id]);
        } else {
            error_log("Webhook error: No temporary panier found for token {$panier_token}");
        }
    } else {
        error_log("Webhook error: panier_token not found in session metadata.");
    }
}

http_response_code(200);
?>
