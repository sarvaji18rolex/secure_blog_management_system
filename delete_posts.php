<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "blog1";

// Connect to MySQL
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate ID
if (!isset($_GET['id'])) {
    die("No post ID specified.");
}

$post_id = intval($_GET['id']);

// Delete the post
$sql = "DELETE FROM posts WHERE id = $post_id";
if ($conn->query($sql) === TRUE) {
    // Redirect back to the list
    header("Location: list_posts.php");
    exit();
} else {
    echo "Error deleting post: " . $conn->error;
}

$conn->close();
