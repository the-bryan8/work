<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'inscription</title>
    <link rel="stylesheet" href="test.css"> 

    <style>
        .hidden {
            display: none;
        }
    </style>
   
</head>
<body>

    <h1>Inscription</h1>
    <div id="form-container">
        <form action="" method="POST">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" required><br>

            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" id="prenom" required><br>

            <label for="pseudo">Pseudo :</label>
            <input type="text" name="pseudo" id="pseudo" required><br>

            <label for="email">Email :</label>
            <input type="email" name="email" id="email" required><br>

            <label for="mdp">Mot de passe (au moins 8 caractères) :</label>
            <input type="password" name="mdp" id="mdp" minlength="8" required><br>
            <input type="checkbox" id="show-password"> <label for="show-password">Afficher le mot de passe</label><br>

            <label for="confirm_mdp">Confirmez le mot de passe :</label>
            <input type="password" name="confirm_mdp" id="confirm_mdp" minlength="8" required><br>

            <input type="submit" name="ok" value="S'inscrire">
        </form>
        <p>j'ai un compte               <a href="conex.php">me connecté(e)</a></p>
    </div>

<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "utilisateur";

try {
    // Connexion à la database
    $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connexion réussie !^-^<br>";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

if (isset($_POST['ok'])) {
    // Récupération des informations
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];
    $confirm_mdp = $_POST['confirm_mdp'];

    // confirmation du mot de passe 
    if ($mdp !== $confirm_mdp) {
        echo "Erreur : Les mots de passe ne correspondent pas.<br>";
    } else if (strlen($mdp) < 8) {
        echo "Erreur : Le mot de passe doit comporter au moins 8 caractères.<br>";
    } else {
        try {
            // Vérification de l'existence de l'utilisateur dans la base de données
            $checkUser = $bdd->prepare("SELECT * FROM users WHERE email = :email OR pseudo = :pseudo");
            $checkUser->execute(array('email' => $email, 'pseudo' => $pseudo));
            $existingUser = $checkUser->fetch();

            if ($existingUser) {
                echo "Un compte avec cet email ou ce pseudo existe déjà. Veuillez vous connecter.<br>";
            } else {
                // Insertion des informations dans la base de données
                $requete = $bdd->prepare("INSERT INTO users (nom, prenom, pseudo, email, mdp) VALUES (:nom, :prenom, :pseudo, :email, :mdp)");
                $requete->execute(array(
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'pseudo' => $pseudo,
                    'email' => $email,
                    'mdp' => password_hash($mdp, PASSWORD_DEFAULT),  // Hachage du mot de passe
                ));

                echo "Informations enregistrées avec succès, inscription réussie !<br><br>";

                // Affichage des informations enregistrées
                echo "<strong>Informations enregistrées :</strong><br>";
                echo "Nom : " . htmlspecialchars($nom) . "<br>";
                echo "Prénom : " . htmlspecialchars($prenom) . "<br>";
                echo "Pseudo : " . htmlspecialchars($pseudo) . "<br>";
                echo "Email : " . htmlspecialchars($email) . "<br>";
              
                // Ajout du script pour afficher le décompte de 30 secondes
                echo "<p>Vous serez redirigé vers la page d'accueil dans <span id='countdown'>30</span> secondes.</p>";
                echo "<meta http-equiv='refresh' content='30;url=exit.php'>";
            }
        } catch (PDOException $e) {
            echo "Erreur lors de l'insertion : " . $e->getMessage();
        }
    }
}
?>

<script>
    // Fonction pour gérer le décompte
    var countdown = 30;
    var countdownElement = document.getElementById('countdown');

    var interval = setInterval(function() {
        countdown--;
        countdownElement.textContent = countdown;

        // Quand le décompte arrive à 0, on arrête l'intervalle
        if (countdown <= 0) {
            clearInterval(interval);
        }
    }, 1000); // Le décompte est mis à jour toutes les secondes

    // Masquer le formulaire une fois l'inscription réussie
    <?php if (isset($requete)) { ?>
        document.getElementById('form-container').classList.add('hidden');
    <?php } ?>

    // Fonction pour afficher/masquer le mot de passe
    document.getElementById('show-password').addEventListener('change', function() {
        var passwordField = document.getElementById('mdp');
        if (this.checked) {
            passwordField.type = 'text';
        } else {
            passwordField.type = 'password';
        }
    });
</script>

</body>
</html>
