<?php
session_start();

include_once 'config.php';
include_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get user input from the form
    $username = htmlspecialchars($_POST["username"]);
    $password = $_POST["password"];

    // Call the registerUser function from function.php
    $result = loginUser($username, $password);

    // Check the result of registration
    if ($result === "Login successful") {
        // Redirect to the profile page on success
        header("Location: ../profile");
        $_SESSION['error_message'] = "";
        exit;
    } else {
        // Registration failed, set an error message
        $_SESSION['error_message'] = $result;
        header("Location: /login"); // Redirect back to the registration form
        exit;
    }
} else {
    // Redirect to the registration form if the request method is not POST
    header("Location: /login");
    exit;
}
?>
