<?php
// Get the error code from the query parameter
$errorCode = isset($_GET['code']) ? $_GET['code'] : '';

// Define custom error messages based on error codes
$errorMessages = [
    '404' => 'Page not found. The requested page does not exist.',
    '500' => 'Internal Server Error. An unexpected error occurred.',
    '403' => 'Petit coquin tu n as pas le droit ;p.',
];

// Display the custom error message
if (array_key_exists($errorCode, $errorMessages)) {
    $errorMessage = $errorMessages[$errorCode];
} else {
    $errorMessage = 'Unknown Error';
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur</title>
    <link rel="shortcut icon" href="img/closed_lock.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/register.css">
    
</head>

<body>
    <!-- Header -->
    <?php include_once("templates/header.php"); ?>

    <!-- Main Content -->
    <div class="box">
        <div class="container">
            <h1>Error <?php echo $errorCode; ?></h1>
            <p><?php echo $errorMessage; ?></p>
        </div>
    </div>
</body>

</html>