<?php
require_once('connexion.php');
require_once('vendor/autoload.php'); // Inclure l'autoloader de Composer

use Firebase\JWT\JWT; // Importer la classe JWT depuis la bibliothèque Firebase JWT

$secret_key = "mfnm2024";

// Récupérer les données du formulaire (JSON)
$input_data = json_decode(file_get_contents('php://input'), true);

// Vérifier la présence des données nécessaires dans le tableau JSON
if (isset($input_data['mail']) && isset($input_data['password'])) {
    $mail = $input_data['mail'];
    $password = $input_data['password'];

    // Requête paramétrée pour récupérer les informations de l'utilisateur
    $sql = "SELECT mail, password FROM personne WHERE mail = :mail";
    $stmt = $connexion->prepare($sql);
    $stmt->execute(['mail' => $mail]);
    $personne = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debugging: Afficher les informations de l'utilisateur récupérées de la base de données
    var_dump($user);

    if ($personne && password_verify($password, $personne['password'])) {
        // Créer un tableau de données à inclure dans le token JWT
        $token_data = array(
            "mail" => trim($personne['mail']),
            "password" => $personne['password']

        );

        // Encoder les données du token en utilisant la clé secrète
        $jwt = JWT::encode($token_data, $secret_key);

        // Retourner le token JWT encodé comme réponse JSON
        echo json_encode(array("success" => true, "message" => "Authentication successful", "token" => $jwt));
    } else {
        // En cas d'échec de l'authentification, retourner une réponse HTTP 401 avec un message d'erreur JSON
        http_response_code(401);
        echo json_encode(array("success" => false, "message" => "Échec de l'authentification."));
    }
} else {
    // Si les données JSON sont incorrectes ou incomplètes, retourner une réponse HTTP 400 avec un message d'erreur JSON
    http_response_code(400);
    echo json_encode(array("success" => false, "message" => "Données JSON invalides."));
}
?>
