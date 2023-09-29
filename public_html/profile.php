<?php
session_start();

// Check if the user is logged in, if not, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Include your database connection and functions for fetching user data
include 'includes/functions.php'; // Modify this to include your database configuration

// Fetch user data based on the user's session
$username = $_SESSION['username'];

$user = getUser($username);

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="shortcut icon" href="includes/img/closed_lock.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/profile.css">
</head>


<body>
    <!-- Header -->
    <?php include("templates/header.php"); ?>

    <!-- Main Content -->
    <div class="box">
        <h1>Profil de <?php echo $user['username']; ?></h1>
        <div class="container">
            <div style="display: flex;width: 100%;">

                <div class="detail" style="width: 50%;">
                    <p>Nom d'utilisateur : <?php echo $user['username']; ?></p>
                </div>
                <div class="locks">
                    <div class="main-block">
                        <div class="text-block">
                            <h2>Tous mes locks</h2>
                        </div>
                        <div class="button-block">
                            <!--<a class="sign-in-button" href="create_lock">Créer</a>-->
                        </div>
                    </div>

                    <div class="lock_text">
                        <p>Durée</p>
                        <p>Depuis</p>
                        <p>Status</p>
                        <p>Porteur</p>
                        <p>Keyholder</p>
                    </div>
                    <?php
                    $time = date("Y-m-d H:i:s");
                    //get all lock available:
                    $locks = getKeyholderLocks($user['id']);
                    
                    foreach ($locks as $key => $value) {

                        echo "<a class=\"lock\" href=\"lock?id={$value['id']}\">";
                        // for all locks:   

                        if ($time < $value['timer_end']) {
                            echo "<p style=\"text-align:center;\"><img src=\"img/closed_lock.png\" alt=\"lock\" style=\"width:30px;\"/></p>";
                        } else {
                            echo "<p style=\"text-align:center;\"><img src=\"img/opened_lock.png\" alt=\"lock\" style=\"width:30px;\"/></p>";
                        }

                        echo "<p class=\"p_text\">" . date_diff(date_create("now"), date_create($value['timer_start']))->format("%dj %hh%M") . "</p>";
                        if ($time > $value["timer_end"]) {
                            echo "<p class=\"p_text\">Fini</p>";
                        } else {
                            echo "<p class=\"p_text\">" . date_diff(date_create("now"), date_create($value['timer_end']))->format("%dj %hh%M") . "</p>";
                        }
                        $wearer = getUserById($value['wearer'])[0];
                        echo "<p class=\"p_text\">" .($wearer['username'] == $user['username'] ? "Moi": $wearer['username']). "</p>";

                        $keyholder = getUserById($value['keyholder'])[0];
                        echo "<p class=\"p_text\">" .($keyholder['username'] == $user['username'] ? "Moi": $keyholder['username']) . "</p>";
                        echo "</a>";
                    }

                    ?>
                </div>
                
            </div>
            <a href="includes/logout.php" class='login-button'>Se déconnecter</a>
        </div>
    </div>
</body>

</html>