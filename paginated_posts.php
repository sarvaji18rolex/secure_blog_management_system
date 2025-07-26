<?php
require 'db.php';  // Include your database connection

$postsPerPage = 5;

// Get current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $postsPerPage;

$search = '';
$searchSql = '';
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $searchSql = "WHERE title LIKE '%$search%' OR content LIKE '%$search%'";
}

// Count total matching posts
$countSql = "SELECT COUNT(*) AS total FROM posts $searchSql";
$countResult = $conn->query($countSql);
$totalPosts = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalPosts / $postsPerPage);

// Fetch posts for current page
$sql = "SELECT * FROM posts $searchSql ORDER BY created_at DESC LIMIT $offset, $postsPerPage";
$result = $conn->query($sql);
?>

<!-- Search Form -->
<h2>Search Posts</h2>
<form method="get" action="">
    <input type="text" name="search" placeholder="Enter keyword..." value="<?php echo htmlspecialchars($search); ?>">
    <input type="submit" value="Search">
</form>

<!-- Blog Posts -->
<h2>Blog Posts</h2>
<?php if ($result && $result->num_rows > 0): ?>
    <ul>
    <?php while ($row = $result->fetch_assoc()): ?>
        <li>
            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
            <small>Posted on: <?php echo $row['created_at']; ?></small>
            <hr>
        </li>
    <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>No posts found<?php echo $search ? " for \"" . htmlspecialchars($search) . "\"" : ""; ?>.</p>
<?php endif; ?>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
    <div>
        <?php if ($page > 1): ?>
            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">« Prev</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" <?php if ($i == $page) echo 'style="font-weight:bold"'; ?>>
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">Next »</a>
        <?php endif; ?>
    </div>
<?php endif; ?>
