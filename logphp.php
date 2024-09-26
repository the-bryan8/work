<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "utilisateur";

try {
    $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if (isset($_POST['ok'])) {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $mdp = isset($_POST['mdp']) ? trim($_POST['mdp']) : '';

  

    if ($email && password_verify($mdp, $email['mdp'])) {
        // Connexion réussie, redirection vers la page souhaitée
        header("Location: index.html");
        exit();
    } else {
        echo "Identifiants incorrects. Veuillez réessayer.";
    }
}
?>
