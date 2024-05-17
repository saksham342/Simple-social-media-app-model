<?php
include 'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    // Fetch hashed password from the database based on the provided username
    $sql_get_username = "SELECT * FROM user WHERE username='$username'";
    $result = mysqli_query($conn, $sql_get_username);
    
    if ($result->num_rows > 0){
        // User found, check password
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];
        $user_id = $row['user_id'];
        // Verify the provided password against the hashed password
        if (password_verify($password, $hashed_password)) {
            // Passwords match, redirect to dashboard
            session_start();
            $_SESSION["loggedin_as"] = $username;
            $_SESSION['user_id']= $user_id;
            if (isset($_SESSION["loggedin_as"])){
            header("Location: dashboard.php");
            }
            else{
                header("Location: login.html");
            }
        } else {
            // Passwords don't match, redirect to login page
            header("Location: login.html");
        }
    } else {
        // User not found, redirect to login page
        header("Location: login.html");
    }
}
?>
