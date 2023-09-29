<?php
session_start();
include 'includes/functions.php';

if (!isset($_SESSION['username']) || !isset($_GET['id'])){
    header("Location: index");
    die;
}

$lock = getLocksById($_GET['id'])[0];
$keyholder = getUserById($lock['keyholder'])[0];

if ($keyholder['username'] != $_SESSION['username']){
    header("Location: lock?id=".$lock["id"]);
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keyholder</title>
    <link rel="shortcut icon" href="/includes/img/closed_lock.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/keyholder.css">
</head>

<body>
    <!-- Header -->
    <?php include("templates/header.php"); ?>

    <!-- Main Content -->
    <div class="box">
        <div class="container">
            <h2>User List</h2>

            <form method='POST' action='includes/assign_keyholder.php'>
                <input type="hidden" name="lock_id" value="<?php echo $_GET['id'];?>">
                <input type=" text" id="searchInput" placeholder="Search by username">
                <div id="userContainer">

                </div>
            </form>
        </div>
    </div>
    <script src="assets/js/search_users.js"></script>
</body>

</html>