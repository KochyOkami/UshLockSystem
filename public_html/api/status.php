<?php
// Include your database connection code here (e.g., connecting to MySQL)

// Check if an 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    // Sanitize and validate the 'id' parameter (e.g., use intval to ensure it's an integer)
    $id = intval($_GET['id']);
    include '../includes/functions.php';
    $lock = getLocksById($_GET['id'])[0];
    
    if (count($lock) > 0) {
        // Update the 'ping' column with the current date and time
        $currentTime = date('Y-m-d H:i:s');
        // Check if 'timer_end' is less than the current time
        if ($lock['timer_end'] < $currentTime) {
            // Update the status of the lock (assuming setLockStatus is defined)
            updateLockStatus($lock['id'], false); // Set status to false
            $lock['status'] = 0;
        }
        setPingLock($lock['id'], $currentTime);
        
        $response = array('valeur' => $lock["status"]);
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
