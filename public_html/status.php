<?php
// Include your database connection code here (e.g., connecting to MySQL)

// Check if an 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    // Sanitize and validate the 'id' parameter (e.g., use intval to ensure it's an integer)
    $id = intval($_GET['id']);
    include 'includes/functions.php';
    $lock = getLocksById($_GET['id'])[0];
    
    if (count($lock) > 0) {
        $response = array('valeur' => true);
    } else {
        $response = array('valeur' => false);
    }
} else {
    // If 'id' parameter is not present, return an error response
    $response = array('error' => 'No ID provided in the URL');
}

// Set the Content-Type header to JSON
header('Content-Type: application/json');

// Output the JSON response
echo json_encode($response);