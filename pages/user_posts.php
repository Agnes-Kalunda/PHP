<?php
session_start();
require_once '../config.php';

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

try {
    $stmt = $pdo->prepare('SELECT posts.id, posts.title, posts.content, posts.created_at
                           FROM posts
                           WHERE posts.author_id = :user_id
                           ORDER BY posts.created_at DESC');
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Error fetching user posts: ' . $e->getMessage());
    $posts = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts - My CMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="user_posts.php">My Posts</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>My Posts</h1>
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <article>
                    <h2><?= htmlspecialchars($post['title']) ?></h2>
                    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                    <footer>
                        <p>Date: <?= date('F j, Y', strtotime($post['created_at'])) ?></p>
                        <a href="edit_post.php?id=<?= $post['id'] ?>">Edit</a>
                        <a href="delete_post.php?id=<?= $post['id'] ?>" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                    </footer>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You haven't created any posts yet.</p>
        <?php endif; ?>

        <p><a href="add_post.php">Add a New Post</a></p>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> My CMS. All rights reserved.</p>
    </footer>
</body>
</html>