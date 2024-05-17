<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            background-color: #222;
            color: #fff;
            font-family: Arial, sans-serif;
        }
        .container {
            width: 40%;
            margin: 0 auto;
            padding: 20px;
            border: 2px solid #444;
            border-radius: 10px;
            background-color: #333;
        }
        input[type="text"], input[type="password"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #777;
            border-radius: 5px;
            background-color: #444;
            color: #fff;
        }
        input[type="submit"] {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        .error-message {
            color: #ff6363;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form action="process_signup.php" method="post" onsubmit="return validateForm()">
            <label for="userid">User ID:</label>
            <input type="text" id="userid" name="userid" required>
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" required>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <label><input type="checkbox" id="is_admin" name="is_admin" onchange="checkAdmin()"> Is Admin</label>
            <label><input type="checkbox" id="can_add_user" name="can_add_user"> Can Add Users</label>
            <input type="submit" value="Sign Up">
            <div id="error-message" class="error-message"></div>
        </form>
    </div>
    <script>
        function validateForm() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;

            // Password matching
            if (password !== confirmPassword) {
                document.getElementById("error-message").innerText = "Passwords do not match";
                return false;
            }

            // Password strength validation
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!password.match(passwordRegex)) {
                document.getElementById("error-message").innerText = "Password must contain at least one uppercase letter, one lowercase letter, one digit, one special character, and be at least 8 characters long";
                return false;
            }

            return true;
        }

        function checkAdmin() {
            var isAdminChecked = document.getElementById("is_admin").checked;
            if (isAdminChecked) {
                document.getElementById("can_add_user").checked = true;
                document.getElementById("can_add_user").disabled = true;
            } else {
                document.getElementById("can_add_user").checked = false;
                document.getElementById("can_add_user").disabled = false;
            }
        }
    </script>
</body>
</html>
