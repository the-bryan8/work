<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "utilisateur";

try {
    // Connexion database
    $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Gestion de la connexion utilisateur
if (isset($_POST['ok']) && isset($_POST['login']) && isset($_POST['mdp'])) {
    $login = trim($_POST['login']);
    $mdp = trim($_POST['mdp']);

    // Rechercher l'utilisateur par e-mail ou pseudo
    $sql = "SELECT * FROM users WHERE pseudo = :login OR email = :login";
    $stmt = $bdd->prepare($sql);
    $stmt->execute(['login' => $login]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($mdp, $user['mdp'])) {
        // Connexion réussie
        header("Location: index.html");
        exit();
    } else {
        echo "Identifiants incorrects. Veuillez réessayer.";
    }
}

// inscription utilisateur
if (isset($_POST['ok']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['pseudo']) && isset($_POST['email']) && isset($_POST['mdp']) && isset($_POST['confirm_mdp'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];
    $confirm_mdp = $_POST['confirm_mdp'];

    // confirmation du mots de passe 
    if ($mdp !== $confirm_mdp) {
        echo "Erreur : Les mots de passe ne correspondent pas.<br>";
    } else {
        try {
            // Vérification de l'existance de l'utilisateur dans la base de données
            $checkUser = $bdd->prepare("SELECT * FROM users WHERE email = :email OR pseudo = :pseudo");
            $checkUser->execute(['email' => $email, 'pseudo' => $pseudo]);
            $existingUser = $checkUser->fetch();

            if ($existingUser) {
                echo "Un compte avec cet email ou ce pseudo existe déjà. Veuillez vous connecter.<br>";
            } else {
                // Insertion des informations dans la base de données
                $requete = $bdd->prepare("INSERT INTO users (nom, prenom, pseudo, email, mdp) VALUES (:nom, :prenom, :pseudo, :email, :mdp)");
                $requete->execute([
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'pseudo' => $pseudo,
                    'email' => $email,
                    'mdp' => password_hash($mdp, PASSWORD_DEFAULT) // Hachage du mot de passe
                ]);

                echo "Informations enregistrées avec succès, inscription réussie !<br><br>";

                // Affichage des informations enregistrés
                echo "<strong>Informations enregistrées :</strong><br>";
                echo "Nom : " . htmlspecialchars($nom) . "<br>";
                echo "Prénom : " . htmlspecialchars($prenom) . "<br>";
                echo "Pseudo : " . htmlspecialchars($pseudo) . "<br>";
                echo "Email : " . htmlspecialchars($email) . "<br>";
            }
        } catch (PDOException $e) {
            echo "Erreur lors de l'insertion : " . $e->getMessage();
        }
    }
}
?>
