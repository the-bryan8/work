<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="test.css"> 
</head>
<body>

    <h1>Connexion</h1>
    <div id="form-container">
        <form action="" method="POST">
            <label for="identifiant">Email ou Pseudo :</label>
            <input type="text" name="identifiant" id="identifiant" required><br>

            <label for="mdp">Mot de passe :</label>
            <input type="password" name="mdp" id="mdp" required><br>
            <input type="checkbox" id="show-password"> <label for="show-password">Afficher le mot de passe</label><br>

            <input type="submit" name="login" value="Se connecter">
        </form>
        <p>Pas encore de compte ? <a href="traitement.php">Inscrivez-vous ici</a></p>
    </div>

    <?php
    session_start();
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "utilisateur";

    try {
        // Connexion à la base de données
        $bdd = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }

    if (isset($_POST['login'])) {
        // Récupération des informations
        $identifiant = $_POST['identifiant'];
        $mdp = $_POST['mdp'];

        // Vérification des informations dans la base de données
        $requete = $bdd->prepare("SELECT * FROM users WHERE email = :identifiant OR pseudo = :identifiant");
        $requete->execute(array('identifiant' => $identifiant));
        $user = $requete->fetch();

        if ($user && password_verify($mdp, $user['mdp'])) {
            // Connexion réussie
            $_SESSION['user'] = $user['pseudo'];  // Stocker l'utilisateur en session

            // Redirection vers la page index.html
            echo "<p>Connexion réussie ! Vous allez être redirigé vers la page d'accueil.</p>";
            echo "<meta http-equiv='refresh' content='2;url=index.html'>";
        } else {
            // Erreur de connexion
            echo "<p>Erreur : Email/Pseudo ou mot de passe incorrect.</p>";
        }
    }
    ?>

    <script>
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
