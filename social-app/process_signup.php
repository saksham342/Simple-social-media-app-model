<?php
include 'dbconnect.php';
$userid = $_POST["userid"];
$fullname = $_POST["fullname"];
$username = $_POST["username"];
$password = $_POST["confirm_password"];
$is_admin = isset($_POST["is_admin"]) ? 1 : 0; // Convert to boolean value
$can_add_user = isset($_POST["can_add_users"]) ? 1 : 0; // Convert to boolean value
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sign_up_sql = "INSERT INTO user(user_id, fullname, username, password, is_admin, can_add_users) VALUES ('$userid', '$fullname', '$username', '$hashed_password', '$is_admin', '$can_add_user')";
$result = mysqli_query($conn, $sign_up_sql);
if ($result){
    header('Location:login.html');
}
else{
    echo "Unknown Error ! ! !";
}
?>