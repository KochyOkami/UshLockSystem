<?php
session_start();

try {
    //Connextion to the database.
    include 'includes/config.php';
    global $db;

    $request = 'SELECT * FROM Locks;';
    $locks = $db->prepare($request);
    $locks->execute(array());
    $locks = $locks->fetchAll();

    $lock = $locks[0];
    $lock = array("status" => "closed",);
} catch (Error $e) {
    $lock = array("status" => "closed",);
    echo $e;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lock System</title>
    <link rel="shortcut icon" href="img/closed_lock.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<?php include_once("templates/header.php"); ?>

<!-- Main Content -->
<div class="container">
    <!-- Left side: Image with darker background -->
    <div class="left-side">
        <img src="img/<?php try {
                            if (!$lock['status']) {
                                echo "opened";
                            } else {
                                echo "closed";
                            }
                        } catch (Exception $e) {
                            echo "opened";
                        } ?>_lock.png" alt="a lock">
    </div>

    <!-- Right side: Title and description text -->
    <div class="right-side">
        <h1 style="font-size: 35px">Ush Lock</h1>
        <div id="timer">
            <p>Temps restant: <span id="time_left"></span></p>
            <p>Lock depuis: <span id="time_untils"></span></p>
        </div>
    </div>
</div>

<!-- Footer -->
<!-- You can add a footer here if needed -->

</body>

</html>
<script>
    var deadline = new Date(<?php echo '"' . $lock["timer_end"] . '"' ?>).getTime();
    var x = setInterval(function() {
        var now = new Date().getTime();
        var t = deadline - now;
        var days = Math.floor(t / (1000 * 60 * 60 * 24));
        var hours = Math.floor((t % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((t % (1000 * 60)) / 1000);
        document.getElementById("time_left").innerHTML = days + "d " +
            hours + "h " + minutes + "m " + seconds + "s ";
        if (t < 0) {
            clearInterval(x);
            document.getElementById("time_left").innerHTML = "Finished";
        }
    }, 1000);
    var timer_start = new Date(<?php echo '"' . $lock["timer_start"] . '"' ?>).getTime();
    var now = new Date().getTime();
    var days = now - timer_start;


    document.getElementById("time_untils").innerHTML = Math.ceil(days / (1000 * 60 * 60 * 24)) + " jours";
</script>