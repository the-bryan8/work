<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="test.css">
    <style>
        /* Add styles for the welcome message */
        #welcome-message {
            display: none;
            background-color: white;
            color: black;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            border: 2px solid black;
            text-align: center;
            z-index: 1000;
        }

        /* Style the logout button */
        #logout-btn {
            margin-top: 20px;
        }
    </style>
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
        <p>Pas encore de compte ? <a href="sign.php">Inscrivez-vous ici</a></p>
    </div>

    <!-- Welcome Message -->
    <div id="welcome-message">
        Welcome onboard!
    </div>

    <!-- Logout Button -->
    <?php if (isset($_SESSION['user'])): ?>
        <form action="" method="POST">
            <input type="submit" name="logout" id="logout-btn" value="Se déconnecter">
        </form>
    <?php endif; ?>

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

            // Afficher le message de bienvenue
            echo "<script>
                    document.getElementById('welcome-message').style.display = 'block';
                    setTimeout(function() {
                        document.getElementById('welcome-message').style.display = 'none';
                    }, 30000);
                  </script>";

            // Redirection vers la page index.html après 30 secondes
            echo "<meta http-equiv='refresh' content='30;url=index.php'>";
        } else {
            // Erreur de connexion
            echo "<p>Erreur : Email/Pseudo ou mot de passe incorrect.</p>";
        }
    }

    // Gérer la déconnexion
    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: conex.php");  // Rediriger vers la page de connexion
        exit();
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
