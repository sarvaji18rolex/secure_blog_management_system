<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "blog1";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 1: Get post ID from URL
if (!isset($_GET['id'])) {
    die("No post ID specified.");
}

$post_id = intval($_GET['id']);

// Step 2: Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST["title"]);
    $content = $conn->real_escape_string($_POST["content"]);

    $sql = "UPDATE posts SET title='$title', content='$content' WHERE id=$post_id";
    if ($conn->query($sql) === TRUE) {
        echo "<p>Post updated successfully. <a href='list_posts.php'>Back to list</a></p>";
        exit();
    } else {
        echo "Error updating post: " . $conn->error;
    }
}

// Step 3: Fetch existing post data
$sql = "SELECT * FROM posts WHERE id = $post_id";
$result = $conn->query($sql);

if ($result->num_rows != 1) {
    die("Post not found.");
}

$post = $result->fetch_assoc();
?>

<!-- Edit Form -->
<style>
    /* Page background and font */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f2f4f7;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Form container */
form {
    background-color: #fff;
    padding: 30px 40px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 500px;
}

/* Heading */
h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
}

/* Labels */
label {
    font-weight: bold;
    display: block;
    margin-top: 15px;
    color: #444;
}

/* Text inputs and textarea */
input[type="text"],
textarea {
    width: 100%;
    padding: 10px;
    margin-top: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 16px;
    box-sizing: border-box;
}

/* Submit button */
input[type="submit"] {
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #28a745;
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 6px;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #218838;
}

</style>

<form method="post" action="">
    <h2>Edit Post</h2>
    <label>Title:</label><br>
    <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required><br><br>

    <label>Content:</label><br>
    <textarea name="content" rows="5" cols="40" required><?php echo htmlspecialchars($post['content']); ?></textarea><br><br>

    <input type="submit" value="Update Post">
</form>