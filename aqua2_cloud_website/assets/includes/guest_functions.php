<?php

/**
 * Checks if username is one of the guest accounts
 * @param string $username The username to check
 * @return boolean True if guest account, false otherwise
 */
function is_guest_account($username) {
    $guestUsers = ["guest1", "guest2", "guest3", "guest4", "guest5"];
    return in_array($username, $guestUsers);
}

/**
 * Cleans up guest user data by removing their directory and database entries
 * @param string $username The guest username
 * @return boolean True if successful, false otherwise
 */
function cleanup_guest_data($username) {
    if (!is_guest_account($username)) {
        return false;
    }
    
    // Delete AQuA instances from database
    require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/setup/env.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/assets/setup/db.inc.php';
    
    $success = true;
    
    // Delete from AQuA instances registry
    $sql = "DELETE FROM aqua_instances WHERE username=?;";
    $stmt = mysqli_stmt_init($conn);
    
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        error_log("Failed to prepare SQL statement for guest data cleanup: " . mysqli_error($conn));
        $success = false;
    } else {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($conn);
    
    // Remove user directory
    $userdatadirectory = "/opt/a2ud/user_data/" . $username;
    
    // Remove existing directory if it exists
    if (is_dir($userdatadirectory)) {
        exec("rm -rf " . escapeshellarg($userdatadirectory));
    }
    
    return $success;
}