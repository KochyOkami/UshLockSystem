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

echo $user['username'];
echo $keyholder['username'];

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="shortcut icon" href="/includes/img/closed_lock.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/locks.css">
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

                <span style="font-size: 35px;">Lock n° <?php echo $lock['id']; ?><div id="dot" class="dot"></div>
                </span>
            </div>
            <div style="display:flex;">
                <div class="locks">

                    <h1>Info</h1>
                    <div style="text-align:left;">
                        <p>Porteur: <?php echo $user['username']; ?></p>
                        <p>Keyholder: <?php echo $keyholder['username']; ?></p>
                        <p class="p_text">Depuis: <span id='temps'><span></p>
                        <p class="p_text">Temps restant: <span id='temps-restant'><span></p>
                        <p>Status: <?php echo $lock['status'] ? "Verrouillé" : "Déverrouillé"; ?></p>
                        <?php

                        if ($username == $keyholder["username"]) {
                            if ($lock['status']) {
                                echo "<a class='submit' href='includes/unlock_device?id=" . $lock["id"] . "'>Déverrouiller</a>";
                            } else {
                                echo "<a class='submit' href='includes/lock_device?id=" . $lock["id"] . "'>Verrouiller</a>";
                            }
                            echo "<a class='submit' href='give_key?id=" . $lock["id"] . "'>Ceder la clé</a>";
                            

                        }
                        ?>
                    </div>

                </div>
                <?php
                if ($username == $keyholder["username"]) {
                    echo "
                    <div class=\"locks\">
                        
                        <form method='POST' action='includes/time_manager?id=" . $lock['id'] . "'>
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
                            <input class='submit' type='submit' name='action' value='Ajouter'>
                            <input class='submit' type='submit' name='action' value='Supprimer'>
                            <input class='submit' type='submit' name='action' value='Set'>
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
<script>
    function updateTempsRestant() {
        const tempsRestantElement = document.getElementById('temps-restant');
        const now = new Date();
        const timerEnd = new Date('<?php echo $lock["timer_end"]; ?>');

        if (now < timerEnd) {
            const diff = Math.abs(timerEnd - now) / 1000; // Time difference in seconds
            const days = Math.floor(diff / 86400);
            const hours = Math.floor((diff % 86400) / 3600);
            const minutes = Math.floor((diff % 3600) / 60);
            const seconds = Math.floor(diff % 60);

            tempsRestantElement.textContent = `${days}j ${hours}h ${minutes}m ${seconds}s`;
        } else {
            tempsRestantElement.textContent = "Fini";
        }
    }

    // Call the update function immediately
    updateTempsRestant();

    // Set an interval to update Temps Restant every second (adjust as needed)
    setInterval(updateTempsRestant, 1000);


    function updateTemps() {
        const tempsElement = document.getElementById('temps');
        const now = new Date();
        const timerStart = new Date('<?php echo $lock["timer_start"]; ?>');

        const diff = Math.abs(now - timerStart) / 1000; // Time difference in seconds
        const days = Math.floor(diff / 86400);
        const hours = Math.floor((diff % 86400) / 3600);
        const minutes = Math.floor((diff % 3600) / 60);

        tempsElement.textContent = `${days}j ${hours}h ${minutes}m`;
    }

    // Call the update function immediately
    updateTemps();

    // Set an interval to update Temps every minute (adjust as needed)
    setInterval(updateTemps, 60000); // 60000 milliseconds = 1 minute
    const dotElement = document.getElementById("dot");

    function updateDotColor() {
        const currentTime = new Date();
        const pingTime = new Date("<?php echo $lock['ping']; ?>"); // Assuming $lock['ping'] contains the ping timestamp

        // Calculate the time difference in seconds
        const timeDifferenceInSeconds = Math.abs((currentTime - pingTime) / 1000);

        // Check if the time difference is less than 10 seconds
        if (timeDifferenceInSeconds < 20) {
            // Set the dot's background color to red
            dotElement.style.backgroundColor = "green";
        } else {
            // Set the dot's background color to green
            dotElement.style.backgroundColor = "red";
        }

        // Schedule the next update (e.g., every second)
        setTimeout(updateDotColor, 1000);
    }

    // Initial call to start updating the dot's color
    updateDotColor();
    // Update the dot's class based on the lock's status
</script>

</html>