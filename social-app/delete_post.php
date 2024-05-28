<?php
// Include database connection
include 'dbconnect.php';
// Start session to access user data
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin_as'])) {
    // Redirect to login page if not logged in
    header("Location: login.html");
    exit();
}

// Check if post_id is set in POST data
if(isset($_POST['post_id'])) {
    // Get post_id from POST data
    $post_id = $_POST['post_id'];

    // Prepare DELETE query
    $delete_query = "DELETE FROM post WHERE post_id='$post_id'"; 

    // Execute DELETE query
    mysqli_query($conn, $delete_query);

    // Check for referring page URL
    $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'dashboard.php';

    // Redirect back to the referring page
    header("Location: $referrer");
    exit(); // Exit script after redirection
} else {
    // If post_id is not set in POST data, redirect to dashboard.php
    header("Location: dashboard.php");
    exit(); // Exit script after redirection
}
?>
