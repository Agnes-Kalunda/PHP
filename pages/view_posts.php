<?php
require 'config.php';

$stmt = $pdo->query('SELECT posts.title, posts.content, users.username, posts.created_at 
                    FROM posts 
                    JOIN users ON posts.author_id = users.id 
                    ORDER BY posts.created_at DESC');
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Posts</title>
</head>
<body>

<h2>All Posts</h2>

<?php if (empty($posts)): ?>
    <p>No posts available.</p>
<?php else: ?>
    <?php foreach ($posts as $post): ?>
        <div>
            <h3><?php echo htmlspecialchars($post['title']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($post['username']); ?> | <strong>Date:</strong> <?php echo $post['created_at']; ?></p>
            <hr>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (isset($_SESSION['user_id'])): ?>
    <p><a href="add_post.php">Add a New Post</a></p>
<?php else: ?>
    <p><a href="login.php">Login to Add a Post</a></p>
<?php endif; ?>

</body>
</html>
