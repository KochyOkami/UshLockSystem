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

$lock = getLocksById($_GET['id'])[0];
$user = getUserById($lock['wearer'])[0];
$keyholder = getUserById($lock['keyholder'])[0];

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="shortcut icon" href="/includes/img/closed_lock.png" type="image/x-icon">
    <link rel="stylesheet" href="/public_html/locksystem/public_html/assets/css/style.css">
    <link rel="stylesheet" href="/locksystem/public_html/assets/css/locks.css">
</head>

<body>
    <!-- Header -->
    <?php include("templates/header.php"); ?>

    <!-- Main Content -->
    <div class="box">
        <div class="container">
            <div>
                <!-- Title with lock icon -->
                <?php if (date("Y-m-d H:i:s") < $lock['timer_end']) {
                    echo "<span><img src=\"img/closed_lock.png\" alt=\"lock\" style=\"width:30px;\" /></span>";
                } else {
                    echo "<span><img src=\"img/opened_lock.png\" alt=\"lock\" style=\"width:30px;\" /></span>";
                } ?>

                <span style="font-size: 35px;">Lock n° <?php echo $lock['id']; ?></span>
            </div>
            <div style="display:flex;">
                <div class="locks">

                    <h1>Info</h1>
                    <div style="text-align:left;">
                        <p>Porteur: <?php echo $user['username']; ?></p>
                        <p>Keyholder: <?php echo $keyholder['username']; ?></p>
                        <?php echo "<p class=\"p_text\">Temps: " . date_diff(date_create("now"), date_create($lock['timer_start']))->format("%dj %hh%M") . "</p>";
                        if (date("Y-m-d H:i:s") > $lock["timer_end"]) {
                            echo "<p class=\"p_text\">Temps restant: Fini</p>";
                        } else {
                            echo "<p class=\"p_text\">Temps restant: " . date_diff(date_create("now"), date_create($lock['timer_end']))->format("%dj %hh%M") . "</p>";
                        }

                        echo "<p class=\"p_text\">Status: " . ($lock['status'] ? "Verrouillé" : "Déverrouillé") . "</p>";
                        if ($user["username"] == $keyholder["username"]) {
                            echo "ap";
                        } else {
                            echo "<a class='submit' href='includes/unlock?id=" . $lock["id"] . "'>Déverrouiller</a>";
                        }
                        ?>
                    </div>

                </div>
                <?php
                if ($user["username"] == $keyholder["username"]) {
                    echo "ap";
                } else {
                    echo "
                    <div class=\"locks\">
                        
                        <form method='POST' action='includes/time_manager'>
                            <h2>Modifier</h2>
                            <div class=\"time_manager_box\">
                                <!-- Days -->
                                <div class='time_box'>
                                    <label for='days'>Days:</label>
                                    <button type='button' class='addTime'>+</button>
                                    <input type='number' id='days' name='days' min='0' value='0'>
                                    <button type='button' class='removeTime'>-</button>
                                </div>

                                <!-- Hours -->
                                <div class='time_box'>
                                    <label for='hours'>Hours:</label>
                                    <button type='button' class='addTime'>+</button>
                                    <input type='number' id='hours' name='hours' min='0' max='23' value='0'>
                                    <button type='button' class='removeTime'>-</button>
                                </div>

                                <!-- Minutes -->
                                <div class='time_box'>
                                    <label for='minutes'>Minutes:</label>
                                    <button type='button' class='addTime'>+</button>
                                    <input type='number' id='minutes' name='minutes' min='0' max='59' value='0'>
                                    <button type='button' class='removeTime'>-</button>
                                </div>
                            </div>
                            <input class='submit' type='submit' value='Ajouter'>
                            <input class='submit' type='submit' value='Supprimer'>
                        </form>
                    </div>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const addTimeButtons = document.querySelectorAll('.addTime');
                        const removeTimeButtons = document.querySelectorAll('.removeTime');

                        addTimeButtons.forEach(function(button) {
                            button.addEventListener('click', function() {
                                const input = button.nextElementSibling;

                                if (input.id === 'hours' && parseInt(input.value) >= 23 || input.id === 'minutes' && parseInt(input.value) >= 59) {
                                    input.value = 0;
                                }else {

                                    input.value = parseInt(input.value) + 1;
                                }
                            });
                        });

                        removeTimeButtons.forEach(function(button) {
                            button.addEventListener('click', function() {
                                const input = button.previousElementSibling;
                                if (parseInt(input.value) > 0) {
                                    input.value = parseInt(input.value) - 1;
                                }else if (input.value == 0){
                                    if (input.id === 'hours') {
                                        input.value = 23;
                                    } else if (input.id === 'minutes') {
                                        input.value = 59;
                                    }
                                }
                            });
                        });
                    });
                </script>
                    ";
                }
                ?>


            </div>
        </div>
    </div>
    </div>
</body>

</html>