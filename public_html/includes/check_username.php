<?php

if ($_SERVER["REQUEST_METHOD"] === "POST" && array_key_exists("username", $_POST)) {
    include "functions.php";

    if(usernameAlreadyUsed($_POST["username"])) {
        echo "taken"; // Send "exists" if the username is taken
    } else {
        echo "available"; // Send "available" if the username is available
    }
}else{  
    header("Location: ../register.php");
    die();
}
?>