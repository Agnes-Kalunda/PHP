<?php
session_start();
require 'config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $stmt = $pdo->query('SELECT posts.id, posts.title, posts.content, users.username, posts.created_at 
                         FROM posts 
                         JOIN users ON posts.author_id = users.id 
                         ORDER BY posts.created_at DESC');
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Log the error instead of displaying it
    error_log('Error fetching posts: ' . $e->getMessage());
    $posts = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Posts</title>
    <link rel="stylesheet" href="styles.css"> <!-- Consider adding a CSS file -->
</head>
<body>

<header>
    <h1>All Posts</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a> <!-- Consider adding a registration link -->
        <?php endif; ?>
    </nav>
</header>

<main>
    <h2>Latest Posts</h2>

    <?php if (empty($posts)): ?>
        <p>No posts available.</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <article>
                <h3><?= htmlspecialchars($post['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <p>
                    <strong>Author:</strong> <?= htmlspecialchars($post['username']) ?> | 
                    <strong>Date:</strong> <?= date('F j, Y, g:i a', strtotime($post['created_at'])) ?>
                </p>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['author_id']): ?>
                    <a href="edit_post.php?id=<?= $post['id'] ?>">Edit</a>
                    <a href="delete_post.php?id=<?= $post['id'] ?>" onclick="return confirm('Are you sure?');">Delete</a>
                <?php endif; ?>
                <hr>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p><a href="add_post.php">Add a New Post</a></p>
    <?php else: ?>
        <p><a href="login.php">Login to Add a Post</a></p>
    <?php endif; ?>
</main>

<footer>
    <p>&copy; <?= date('Y') ?> My CMS. All rights reserved.</p>
</footer>

</body>
</html>