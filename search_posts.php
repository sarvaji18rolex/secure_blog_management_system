<?php
require 'db.php';

$search = "";     // Initialize search variable
$result = null;   // Initialize result variable

if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);

    $sql = "SELECT * FROM posts 
            WHERE title LIKE '%$search%' OR content LIKE '%$search%' 
            ORDER BY created_at DESC";

    $result = $conn->query($sql);
}
?>

<h2>Search Posts</h2>
<style>
    /* GENERAL PAGE STYLES */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f6f8;
    margin: 0;
    padding: 30px 20px;
}

/* Header */
h2 {
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

/* SEARCH FORM */
form {
    max-width: 500px;
    margin: 0 auto 40px;
    display: flex;
    gap: 10px;
    justify-content: center;
}

input[type="text"] {
    flex: 1;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 15px;
}

input[type="submit"] {
    padding: 10px 20px;
    background-color: #007bff;
    border: none;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    font-size: 15px;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

/* POST LIST RESULTS */
ul {
    list-style-type: none;
    padding: 0;
    max-width: 800px;
    margin: 0 auto;
}

li {
    background-color: #ffffff;
    margin-bottom: 20px;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

li h3 {
    margin: 0 0 10px;
    color: #007bff;
}

li p {
    margin: 10px 0;
    line-height: 1.6;
    color: #444;
}

li small {
    color: #777;
    font-size: 0.9em;
}

li a {
    color: #007bff;
    text-decoration: none;
    font-size: 0.9em;
    margin-right: 10px;
}

li a:hover {
    text-decoration: underline;
}

/* No Results Message */
p {
    text-align: center;
    color: #999;
    font-style: italic;
}

</style>
<form method="get" action="">
    <input type="text" name="search" placeholder="Enter keyword..." value="<?php echo htmlspecialchars($search); ?>" required>
    <input type="submit" value="Search">
</form>

<?php if ($result): ?>
    <h2>Blog Posts</h2>
    
    <?php if ($result->num_rows > 0): ?>
        <ul>
        <?php while($row = $result->fetch_assoc()): ?>
            <li>
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                <small>Posted on: <?php echo $row['created_at']; ?></small><br>
                <a href="edit_post.php?id=<?php echo $row['id']; ?>">Edit</a> |
                <a href="delete_post.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                <hr>
            </li>
        <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No posts found for "<?php echo htmlspecialchars($search); ?>"</p>
    <?php endif; ?>
<?php endif; ?>
