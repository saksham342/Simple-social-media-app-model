<?php

include 'dbconnect.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin_as'])) {
    // Redirect to login page if not logged in
    header("Location: login.html");
    exit();
}
// A function to generate random value for post_id
function generateRandomID($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomID = '';

    for ($i = 0; $i < $length; $i++) {
        $randomID .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomID;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['loggedin_as'];

$post_title = $_POST['title'];
$post_message = $_POST['message'];
$is_public = $_POST['visibility'];
$is_public_boolean = ($is_public == 'public') ? 1 : 0;
$post_id = generateRandomID();
$currentDateTime = date('Y-m-d H:i:s');

$sql_post_query = "INSERT INTO post(post_id, user_id, post_title, post_content, is_public, created_at) VALUES('$post_id','$user_id','$post_title','$post_message', '$is_public_boolean', '$currentDateTime')";
$result = mysqli_query($conn, $sql_post_query);
if ($result){
    echo '<script>alert("Message Posted successfully ! ! ! "); window.location="dashboard.php";</script>';
}else{
    echo "Message not posted ! ! !";
}
?>