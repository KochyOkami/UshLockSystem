<?php
session_start();

include_once 'config.php';
include_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get user input from the form
    $username = htmlspecialchars($_POST["username"]);
    $password = $_POST["password"];
    $password2 = $_POST["password2"];

    // Check if passwords match
    if ($password !== $password2) {
        $_SESSION['error_message'] = "Passwords do not match";
        header("Location: /register"); // Redirect back to the registration form
        exit;
    }

    // Call the registerUser function from function.php
    $result = registerUser($username, $password);

    // Check the result of registration
    if ($result === "Registration successful") {
        // Redirect to the profile page on success
        header("Location: /profile");
        $_SESSION['error_message'] = "";
        exit;
    } else {
        // Registration failed, set an error message
        $_SESSION['error_message'] = $result;
        header("Location: /register"); // Redirect back to the registration form
        exit;
    }
} else {
    // Redirect to the registration form if the request method is not POST
    header("Location: /register");
    exit;
}
?>
