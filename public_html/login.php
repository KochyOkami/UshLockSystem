<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="shortcut icon" href="includes/img/closed_lock.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/register.css">
</head>

<body>
    <!-- Header -->
    <?php include_once("templates/header.php"); ?>

    <!-- Main Content -->
    <div class="box">
        <div class="container">
            <h1>Connexion à LockSystem</h1>
            <form action="includes/login_process.php" method="POST">
                <p><?php if (array_key_exists('error_message', $_SESSION)) {
                        echo $_SESSION['error_message'];
                    } ?></p>
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary">Se connecter</button>
            </form>
        </div>
    </div>
</body>

</html>