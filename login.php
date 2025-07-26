<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            header("Location: secure_blog_validated.php");
            exit();
        } else {
            echo "<p class='message'>❌ Invalid password.</p>";
        }
    } else {
        echo "<p class='message'>❌ User not found.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        /* Reset default browser styling */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Full page background */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(to right, #c9d6ff, #e2e2e2);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Centered login form box */
form {
    background-color: #ffffff;
    padding: 40px 30px;
    border-radius: 10px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    width: 350px;
    text-align: center;
}

/* Heading */
h2 {
    margin-bottom: 25px;
    color: #333;
    font-size: 26px;
}

/* Input fields */
input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #cccccc;
    border-radius: 6px;
    font-size: 16px;
    background-color: #f9f9f9;
    transition: 0.3s;
}

input[type="text"]:focus,
input[type="password"]:focus {
    border-color: #007bff;
    outline: none;
}

/* Submit button */
input[type="submit"] {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: white;
    font-weight: bold;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

/* Optional error/success messages */
.message {
    margin-top: 15px;
    font-weight: bold;
    color: red;
}

.success {
    color: green;
}

    </style>
</head>
<body>

    <form method="post">
        <h2>🔐 Login</h2>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
    </form>

</body>
</html>
