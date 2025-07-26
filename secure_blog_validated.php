<?php
require 'db.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    // Server-side validation
    if (empty($title) || strlen($title) < 3) {
        $error = "❗ Title must be at least 3 characters.";
    } elseif (empty($content) || strlen($content) < 10) {
        $error = "❗ Content must be at least 10 characters.";
    } else {
        // Prepared statement
        $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content);

        if ($stmt->execute()) {
            $success = "✅ Post added successfully!";
        } else {
            $error = "❌ Database error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 700px; }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">🛡️ Secure Blog Post</h1>

    <!-- Show messages -->
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Post form -->
    <form method="post" onsubmit="return validateForm()">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required minlength="3">
        </div>
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control" rows="5" required minlength="10"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Publish Post</button>
    </form>

    <hr class="my-4">

    <!-- Display recent posts -->
    <h3>Recent Posts</h3>
    <?php
    $result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
    if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
    ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                <small class="text-muted">Posted on: <?php echo $row['created_at']; ?></small>
                <a href="edit_posts.php?id=<?php echo $row['id']; ?>">Edit</a>
                <a href="delete_posts.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
            </div>
        </div>
    <?php endwhile; else: ?>
        <p class="text-muted">No posts yet.</p>
    <?php endif; ?>
</div>

<!-- Client-side validation -->
<script>
function validateForm() {
    const title = document.forms[0]["title"].value.trim();
    const content = document.forms[0]["content"].value.trim();

    if (title.length < 3) {
        alert("Title must be at least 3 characters.");
        return false;
    }

    if (content.length < 10) {
        alert("Content must be at least 10 characters.");
        return false;
    }

    return true;
}
</script>

</body>
</html>
