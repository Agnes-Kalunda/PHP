<?php

require 'config.php';

try {
    #query to fetch posts from db
    $stmt = $pdo->query('SELECT posts.id, posts.title, posts.content, users.username, posts.created_at 
                         FROM posts 
                         JOIN users ON posts.author_id = users.id 
                         ORDER BY posts.created_at DESC');
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    
    error_log('Error fetching posts: ' . $e->getMessage());

    $posts = [];
}

// Start the session to check if the user is logged in
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My CMS - Home</title>
</head>
<body>

<header>
    <h1>Hello!</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="pages/about.php">About</a>
        <a href="pages/login.php">Login</a>
    </nav>
</header>

<main>
    <h2>Latest Posts</h2>

    
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

    <!-- show add post is user is logged in -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <p><a href="pages/add_post.php">Add a New Post</a></p>
    <?php else: ?>
        <p><a href="pages/login.php">Login to Add a Post</a></p>
    <?php endif; ?>
</main>

<footer>
    <p>&copy; 2024 My CMS. All rights reserved.</p>
</footer>

</body>
</html>
