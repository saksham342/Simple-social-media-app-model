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

// Fetch user's ID from session
$user_id = $_SESSION['user_id'];

// Fetch user's information
$user_query = "SELECT * FROM user WHERE user_id = '$user_id'";
$user_result = mysqli_query($conn, $user_query);
if ($user_result->num_rows > 0) {
    $user_data = $user_result->fetch_assoc();
} else {
    $user_data['fullname'] = "User";
}

// Fetch user's public posts from the database
$public_posts_query = "SELECT * FROM post WHERE user_id='$user_id' AND is_public = 1 ORDER BY created_at DESC";
$public_posts_result = mysqli_query($conn, $public_posts_query);

// Check for errors in SQL query execution
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Public Posts</title>
    <style>
        body {
            background-color: #ccc; /* Gray background */
            color: #333; /* Dark text */
            font-family: Arial, sans-serif;
            padding: 20px;
            margin: 0;
            display: flex;
            justify-content: center; /* Center content horizontally */
            align-items: center; /* Center content vertically */
            height: 100vh;
        }
        .container {
            background-color: #fff; /* White box background */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
        }
        .post {
            background-color: #f9f9f9; /* Light gray box background */
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .post h3 {
            color: #333; /* Dark text */
            margin-bottom: 5px;
        }
        .post p {
            color: #666; /* Medium dark text */
            margin-bottom: 5px;
        }
        .small-text {
            font-size: 12px;
            color: #999; /* Lighter text */
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Hover effect for the button */
        button:hover {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $user_data['fullname']; ?>'s Public Posts</h1>

        <?php
        // Display user's public posts
        if ($public_posts_result->num_rows > 0) {
            // Loop through each row in the result set
            while ($row = $public_posts_result->fetch_assoc()) {
                // Output post title and content
                echo "<div class='post'>";
                echo "<h3>" . $row['post_title'] . "</h3>";
                echo "<p>" . $row['post_content'] . "</p>";
                echo "<p class='small-text'>Posted at: <strong>" . $row['created_at'] . "</strong></p>";
                // Delete Button
                echo "<form action='delete_post.php' method='post'>";
                echo "<input type='hidden' name='post_id' value='" . $row['post_id'] . "'>";
                echo "<input type='submit' value='Delete This Post'>";
                echo "</form>";
                echo "</div>";

            }
        } else {
            // No public posts found
            echo "<p>No public posts found.</p>";
        }
        ?>
        
        <!-- Add the button here -->
        <form action="dashboard.php">
            <button type="submit">Go to Dashboard</button>
        </form>
    </div>
</body>
</html>
