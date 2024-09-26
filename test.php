<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'inscription</title>
   
</head>
<body>

    <h1>Inscription</h1>
    <form action="" method="POST">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" required><br>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom" required><br>

        <label for="pseudo">Pseudo :</label>
        <input type="text" name="pseudo" id="pseudo" required><br>

        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required><br>

        <label for="mdp">Mot de passe :</label>
        <input type="password" name="mdp" id="mdp" required><br>

        <label for="confirm_mdp">Confirmez le mot de passe :</label>
        <input type="password" name="confirm_mdp" id="confirm_mdp" required><br>

        <input type="submit" name="ok" value="S'inscrire">
    </form>

    <?php
    if (isset($_POST['ok'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $pseudo = $_POST['pseudo'];
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];
        $confirm_mdp = $_POST['confirm_mdp'];

        // Validation du mot de passe
        if ($mdp !== $confirm_mdp) {
            echo "Erreur : Les mots de passe ne correspondent pas.<br>";
        } else {
            try {
                // Connexion à la base de données (déjà incluse ici pour l'exemple)
                $bdd = new PDO("mysql:host=localhost;dbname=utilisateur", "root", "");
                $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Vérification de l'existence de l'utilisateur
                $checkUser = $bdd->prepare("SELECT * FROM users WHERE email = :email OR pseudo = :pseudo");
                $checkUser->execute(['email' => $email, 'pseudo' => $pseudo]);
                $existingUser = $checkUser->fetch();

                if ($existingUser) {
                    echo "Un compte avec cet email ou ce pseudo existe déjà. Veuillez vous connecter.<br>";
                } else {
                    // Insertion dans la base de données
                    $requete = $bdd->prepare("INSERT INTO users (nom, prenom, pseudo, email, mdp) VALUES (:nom, :prenom, :pseudo, :email, :mdp)");
                    $requete->execute([
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'pseudo' => $pseudo,
                        'email' => $email,
                        'mdp' => password_hash($mdp, PASSWORD_DEFAULT) // Hachage du mot de passe
                    ]);

                    // Affichage des informations enregistrées
                    echo "Informations enregistrées avec succès, inscription réussie !<br><br>";
                    echo "<strong>Informations enregistrées :</strong><br>";
                    echo "Nom : " . htmlspecialchars($nom) . "<br>";
                    echo "Prénom : " . htmlspecialchars($prenom) . "<br>";
                    echo "Pseudo : " . htmlspecialchars($pseudo) . "<br>";
                    echo "Email : " . htmlspecialchars($email) . "<br>";

                    // Redirection vers la page index.html après 30 secondes
                    echo "<meta http-equiv='refresh' content='30;url=index.html'>";
                    echo "Vous serez redirigé vers la page d'accueil dans 30 secondes.<br>";
                }
            } catch (PDOException $e) {
                echo "Erreur lors de l'insertion : " . $e->getMessage();
            }
        }
    }
    ?>

</body>
</html>
