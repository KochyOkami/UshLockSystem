<?php
// Include the database connection configuration
include_once 'config.php';

// Function to register a new user
function registerUser($username, $password)
{
    global $db; // Access the global database connection

    // Check if the username already exists
    if (usernameAlreadyUsed($username)) {
        return "Username déjà utilisé.";
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert the new user into the database
    $query = "INSERT INTO Users (username, password) VALUES (:username, :password)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

    if ($stmt->execute()) {
        return "Registration successful";
    } else {
        return "Registration failed";
    }
}

// Function to log in a user
function loginUser($username, $password)
{
    global $db; // Access the global database connection

    // Retrieve the user's hashed password from the database
    $query = "SELECT id, username, password FROM Users WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, set up a session
            session_start();
            $_SESSION['username'] = $user['username'];

            return "Login successful";
        } else {
            return "Mot de passe ou identifiant incorrect";
        }
    } else {
        return "Mot de passe ou identifiant incorrect";
    }
}

// Function to check if a user is logged in
function isLoggedIn()
{
    return isset($_SESSION['username']);
}

function usernameAlreadyUsed($username)
{
    global $db; // Access the global database connection

    $query = "SELECT username FROM Users WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    // Return true if the username is already used.
    return $stmt->rowCount() != 0;
}

// Function to log out a user
function logoutUser()
{
    session_start();
    session_destroy();
    header("Location: login.php"); // Redirect to the login page
    exit;
}

function getUser($username)
{
    global $db; // Access the global database connection

    $query = "SELECT * FROM Users WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch();
}

function getUserById($id)
{
    global $db; // Access the global database connection

    $query = "SELECT * FROM Users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll();
}

// Function to get the lock of a user
function getLocksById($lock_id)
{
    global $db; // Access the global database connection
    
    $query = "SELECT * FROM Locks WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $lock_id, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll();
}

// Function to get the lock of a user
function getLocks($user_id)
{
    global $db; // Access the global database connection
    
    $query = "SELECT * FROM Locks WHERE wearer = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll();
}
function getKeyholderLocks($user_id)
{
    global $db; // Access the global database connection
    
    $query = "SELECT * FROM Locks WHERE keyholder = :id OR wearer = :id2";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_STR);
    $stmt->bindParam(':id2', $user_id, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll();
}

function updateLockStatus($lockId, $newStatus)
{
    global $db; // Access the global database connection

    // Check if the new status is a valid boolean value (true or false)
    if (!is_bool($newStatus)) {
        return "Invalid status value";
    }

    // Update the lock status in the database
    $query = "UPDATE Locks SET status = :status WHERE id = :lockId";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':status', $newStatus, PDO::PARAM_BOOL);
    $stmt->bindParam(':lockId', $lockId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        return true;
    } else {
        return "Failed to update lock status";
    }
}

function setLockEndTime($lockId, $newTime)
{
    global $db; // Access the global database connection

    // Update the lock's timer_end in the database
    $query = "UPDATE Locks SET timer_end = :newTimerEnd WHERE id = :lockId";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':newTimerEnd', $newTime, PDO::PARAM_STR);
    $stmt->bindParam(':lockId', $lockId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        return true;
    } else {
        return "Failed to update lock timer_end";
    }
}

function setLockStartTime($lockId, $newTime)
{
    global $db; // Access the global database connection

    // Update the lock's timer_end in the database
    $query = "UPDATE Locks SET timer_start = :newTimerEnd WHERE id = :lockId";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':newTimerEnd', $newTime, PDO::PARAM_STR);
    $stmt->bindParam(':lockId', $lockId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        return true;
    } else {
        return "Failed to update lock timer_start";
    }
}
function setPingLock($lockId, $pingTime)
{
    global $db; // Access the global database connection

    // Update the lock's timer_end in the database
    $query = "UPDATE Locks SET ping = :pingTime WHERE id = :lockId";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':pingTime', $pingTime, PDO::PARAM_STR);
    $stmt->bindParam(':lockId', $lockId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        return true;
    } else {
        return "Failed to update lock pingTime";
    }
}

// Function to fetch a list of all registered users
function getAllUsers()
{
    global $db; // Access the global database connection

    $query = "SELECT id, username FROM Users";
    $stmt = $db->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll();
}

// Function to assign a user as the keyholder for a lock
function assignKeyholder($lock_id, $username)
{
    global $db; // Access the global database connection

    // Check if the lock exists and is owned by the current user (you can add this check if needed)

    // Update the lock's keyholder field with the selected user's ID
    $query = "UPDATE Locks SET keyholder = :username WHERE id = :lock_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':lock_id', $lock_id, PDO::PARAM_INT);

    return $stmt->execute();
}
