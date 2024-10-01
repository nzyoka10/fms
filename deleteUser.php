<?php

include 'includes/functions.php';

// Check if 'id' is passed in the query string
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $userId = intval($_GET['id']);

    // Call the function to delete the user
    $isDeleted = deleteUserById($conn, $userId);

    if ($isDeleted) {
        // If the user is successfully deleted, redirect to the users page with a flag for SweetAlert
        header("Location: users.php?status=deleted");
        exit();
    } else {
        // If the deletion failed, redirect with error flag
        header("Location: users.php?status=error");
        exit();
    }
} else {
    // If 'id' is not set or invalid, redirect with error flag
    header("Location: users.php?status=invalid");
    exit();
}
