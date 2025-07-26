<?php
// 1. Start PHP and connect to DB
$host = "localhost";
$user = "root";
$password = "";
$dbname = "blog1";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Fetch all posts
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blog Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<h2>Blog Posts</h2>

<?php 
if ($result->num_rows > 0): ?>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                <small>Posted on: <?php echo $row['created_at']; ?></small>
                <a href="edit_posts.php?id=<?php echo $row['id']; ?>">Edit</a>
                <a href="delete_posts.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                <hr>
            </li>
        <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>No posts found.</p>
<?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
