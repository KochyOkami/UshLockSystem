<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the values from the form
    $lock_id = intval($_POST["lock_id"]);
    $username = intval($_POST['id']);

    echo $username;
    // Include your database connection code here
    include 'functions.php';

    // Assign the selected user as the keyholder for the lock
    if (assignKeyholder($lock_id, $username)) {
        // Redirect to a success page or display a success message
        header("Location: ../lock?id=".$lock_id);
        exit;
    } else {
        // Handle the assignment failure (e.g., display an error message)
        echo "Keyholder assignment failed.";
    }
}
?>
