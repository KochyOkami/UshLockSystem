<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the values from the form

    include 'functions.php';

    $days = intval($_POST["days"]);
    $hours = intval($_POST["hours"]);
    $minutes = intval($_POST["minutes"]);

    // Calculate the total time in minutes
    $total = ($days * 24 * 60 * 60) + ($hours * 60 * 60) + ($minutes * 60);
    echo $total;

    $action = $_POST['action'];

    $lock = getLocksById($_GET['id'])[0];
    if ($action === 'Ajouter') {
        // Handle the 'Ajouter' button click
        $newTime = new DateTime($lock['timer_end']);
        $newTime->modify("+$total seconds");

        updateLockStatus($_GET['id'], true);

    } elseif ($action === 'Supprimer') {
        $newTime = new DateTime($lock['timer_end']);
        $newTime->modify("-$total seconds");
        if ($newTime < new DateTime()) {
            // Update the status of the lock to false
            updateLockStatus($_GET['id'], false);
        }

    }else{
        // Handle the 'Set' button click
        setLockStartTime($_GET['id'], (new DateTime())->format('Y-m-d H:i:s'));
        $newTime = new DateTime();
        $newTime->modify("+$total seconds");
        updateLockStatus($_GET['id'], true);
    }



    // Format the new timestamp as desired (e.g., in a human-readable format)
    $formattedNewTime = $newTime->format('Y-m-d H:i:s');
    echo $formattedNewTime;
    setLockEndTime($_GET['id'], $formattedNewTime);
    // Perform your desired action with $total
    // For example, you can update a database record with this value.

    // Redirect to a success page or display a success message
    header("Location: ../lock?id=" . $_GET['id']);
    exit;
}
