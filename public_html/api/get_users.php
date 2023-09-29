<?php
// Include your database connection code here

// Function to get all users from the database
include '../includes/functions.php';

// Set the Content-Type header to JSON
header('Content-Type: application/json');

// Output the JSON response with the list of users
echo json_encode(getAllUsers());
