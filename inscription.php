<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'inscription</title>
    <link rel="stylesheet" href="stil.css"> 
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("mdp");
            var confirmPasswordInput = document.getElementById("confirm_mdp");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                confirmPasswordInput.type = "text";
            } else {
                passwordInput.type = "password";
                confirmPasswordInput.type = "password";
            }
        }
    </script>
</head>
<body>
    <form method="POST" action="traitement.php">
        <label for="nom">Votre nom</label>
        <input type="text" id="nom" name="nom" placeholder="Entrez votre nom" required>
        <br>
        <label for="prenom">Votre prénom</label>
        <input type="text" id="prenom" name="prenom" placeholder="Entrez votre prénom" required>
        <br>
        <label for="pseudo">Votre pseudo</label>
        <input type="text" id="pseudo" name="pseudo" placeholder="Entrez votre pseudo" required>
        <br>
        <label for="email">Votre E-mail</label>
        <input type="email" id="email" name="email" placeholder="Entrez votre email" required>
        <br>
        <label for="mdp">Votre mot de passe</label>
        <input type="password" id="mdp" name="mdp" placeholder="Entrez votre mot de passe" required>
        <br>
        <label for="confirm_mdp">Confirmez votre mot de passe</label>
        <input type="password" id="confirm_mdp" name="confirm_mdp" placeholder="Confirmez votre mot de passe" required>
        <br>
        <!-- visibilité du mot de passe -->
        <input type="checkbox" id="showPassword" onclick="togglePasswordVisibility()"> 
        <label for="showPassword">Afficher le mot de passe</label>
        <br>
        <input type="submit" value="M'inscrire" name="ok">
        <a href="log.php">Me connecté</a>
        
    </form>
</body>
</html>
