<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Connexion</title>
  <link rel="stylesheet" href="stil.css"> 
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("mdp");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>
</head>
<body>

    <form method="POST" action="logphp.php">
        <label for="login">Votre e-mail ou pseudo</label>
        <input type="text" id="email" name="email" placeholder="Entrez votre e-mail ou pseudo" required>
        <br>
        <label for="mdp">Votre mot de passe</label>
        <input type="password" id="mdp" name="mdp" placeholder="Entrez votre mot de passe" required>
        <br>
        
        <input type="checkbox" id="showPassword" onclick="togglePasswordVisibility()"> 
        <label for="showPassword">Afficher le mot de passe</label>
        <br>
        <input type="submit" value="Se connecter" name="ok">
        <br><br>
        <a href="inscription.php">s'inscrire</a>
    </form>
    
   
</body>
</html>
