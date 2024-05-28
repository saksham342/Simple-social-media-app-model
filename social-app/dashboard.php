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

// Get user's ID from session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['loggedin_as'];
// Fetch user's information from the database
$user_query = "SELECT * FROM user WHERE user_id = '$user_id'";
$user_result = mysqli_query($conn, $user_query);
if ($user_result->num_rows > 0) {
    $user_data = $user_result->fetch_assoc();
} else {
    echo "error";
}

// Fetch public posts from the post table of database
$public_posts_query = "SELECT * FROM post WHERE is_public = 1 ORDER BY created_at DESC";
$public_posts_result = mysqli_query($conn, $public_posts_query);

// Fetch private posts from current user from post table of database
$private_posts_query = "SELECT * FROM post WHERE user_id='$user_id' AND is_public = 0 ORDER BY created_at DESC";
$private_posts_result = mysqli_query($conn, $private_posts_query);
// Check for errors in SQL query execution
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $user_data['fullname'] ?></title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
        }
        .container {
            display: flex;
            height: max-content;
        }
        .left, .right {
            width: 25%;
            background-color: #535353;
            padding: 20px;
        }
        .center {
            width: 50%;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px 10px rgba(255, 255, 255, 1); 
        }
        .post-form {
            margin-bottom: 20px;
        }
        .post-form h2 {
            color: rgb(0, 192, 192);
        }
        .post-form input[type="text"],
        .post-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        .post-form input[type="file"] {
            margin-bottom: 10px;
        }
        .post-form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 15px;
        }
        .post-form input[type="submit"]:hover {
            background-color: rgb(26, 243, 232);
        }
        .divider {
            text-align: center;
            margin-bottom: 2px;
            position: relative;
            margin-top: 70px;
        }
        .divider hr {
            border: none;
            height: 2px;
            background-color: #ccc;
            margin-top: 0;
        }
        .divider .circle {
            position: absolute;
            top: -18px;
            background-color: #fff;
            border: 2px solid #ccc;
            border-radius: 20%;
            width: 160px;
            height: 40px;
            line-height: 33px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #0178a7;
        }
        .welcome {
            color: white;
        }
        .top {
            display: block;
            background-color: gray;
            padding: 5px;
            left: 80%;
        }
        .logout {
            justify-content: right;
            display: flex;
            margin-top: -40px;
        }
        .logout a {
            text-decoration: none;
            color: #0178a7;
            background-color: white;
            padding: 4px;
            border-radius: 10px;
        }
        .main_divider hr {
            height: 10px;
            width: auto;
            background-color: #535353;
        }
        .private-post {
            background-color: #f2f2f2;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .private-post h3 {
            color: #333;
        }
        .private-post p {
            color: #666;
        }
        .small-text {
            font-size: 12px;
            color: #999;
        }
        .public-post {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }
        .public-post h3 {
            color: #333;
            margin-bottom: 5px;
        }
        .public-post p {
            color: #666;
            margin-bottom: 5px;
        }
        .view_public_posts{
            align-content: right;
            position: absolute;
            margin: 10px 0px 0px 250px;
        }
        .view_public_posts:hover{
            background-color: gray;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <!-- Left Content -->
        </div>
        <div class="center">
            <div class="top">
                <div class="welcome"><h2>Welcome, <?php echo $user_data['fullname']; ?>    !</h2></div>
                <div class="logout"><a href='logout.php'>Log Out</a></div>
            </div>
            
            <!-- Center Content -->
            <div class="post-form">
                <h2>Post a Message</h2>
                <form action="post_message.php" method="post" enctype="multipart/form-data">
                    <label for="title">Title:</label><br>
                    <input type="text" id="title" name="title" required><br>
                    <label for="message">Message:</label><br>
                    <textarea id="message" name="message" rows="4" required></textarea><br>
                    <label for="image">Image:</label><br>
                    <input type="file" id="image" name="image" accept="image/*"><br>
                    <label for="visibility">Visibility:</label><br>
                    <select id="visibility" name="visibility">
                        <option value="public">Public</option>
                        <option value="private">Private</option>
                    </select><br><br>
                    <input type="submit" value="Post">
                </form>
            </div>

            <div class="main_divider"><hr></div>
            <div class="divider">
                <div class="circle">Private Contents</div>
                <hr>
            </div>
            <br><br>
            
            <?php
            // Display private posts of the current user
            if ($private_posts_result->num_rows > 0) {
                // Loop through each row in the result set
                while ($row = $private_posts_result->fetch_assoc()) {
                    // Output post title and content
                    echo "<div class='private-post'>";
                    echo "<h3>" . $row['post_title'] . "</h3>";
                    echo "<p>" . $row['post_content'] . "</p>";
                    echo "<p class='small-text'>Posted by: <strong>" . $user_data['fullname'] . "</strong></p>";
                    echo "<p class='small-text'>Created at: <strong>" . $row['created_at'] . "</strong></p>";
                    
                    // Delete Button
                    echo "<form action='delete_post.php' method='post'>";
                    echo "<input type='hidden' name='post_id' value='" . $row['post_id'] . "'>";
                    echo "<input type='submit' value='Delete This Post'>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                // No private posts found
                echo "<p>No private posts found.</p>";
            }
            ?>
            <!-- Divider for Public Contents -->
            <div class="main_divider"><hr></div>
           <!-- Inside the existing HTML code -->
            <div class="divider">
                <div class="circle">Public Contents</div>
                <button onclick="location.href='my_public_posts.php'" class="view_public_posts">My Public Posts</button>
                <hr>
            </div>

            <br><br>

            <?php
            // Display public posts
            if ($public_posts_result->num_rows > 0) {
                // Loop through each row in the result set
                while ($row = $public_posts_result->fetch_assoc()) {
                    // Output public post title and content
                    echo "<div class='public-post'>";
                    echo "<h3>" . $row['post_title'] . "</h3>";
                    echo "<p>" . $row['post_content'] . "</p>";
                    $public_post_user_id = $row['user_id'];
                    $query_to_fetch_fullname_from_userid = "SELECT fullname FROM user WHERE user_id='$public_post_user_id'";
                    $result_fullname = mysqli_query($conn, $query_to_fetch_fullname_from_userid);
                    if ($result_fullname) {
                        $row_fullname = mysqli_fetch_assoc($result_fullname);
                        $fullname = $row_fullname['fullname'];
                    }
                    echo "<p class='small-text'>Posted by: <strong>" . $fullname . "</strong></p>";
                    echo "<p class='small-text'>Created at: <strong>" . $row['created_at'] . "</strong></p>";
                    // No delete button for public posts
                    echo "</div>";
                }
            } else {
                // No public posts found
                echo "<p>No public posts found.</p>";
            }
            ?>
            <!-- Future user posts will be displayed here -->
        </div>
        <div class="right">
            <!-- Right Content -->
        </div>
    </div>
</body>
</html>
