<?php
session_start();
require_once '../config.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    $stmt = $pdo->prepare('DELETE FROM posts WHERE id = :id AND author_id = :author_id');
    $stmt->execute([
        'id' => $post_id,
        'author_id' => $_SESSION['user_id']
    ]);

    
    if ($stmt->rowCount() > 0) {
        
        header('Location: user_posts.php');
        exit();
    } else {

        $error = 'No post found to delete.';
    }
} catch (PDOException $e) {
    error_log('Error deleting post: ' . $e->getMessage());
    $error = 'An error occurred while deleting the post.';
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Post - My CMS</title>
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
        <h1>Delete Post</h1>
        <p class="error"><?= $error ?></p>
        <p><a href="user_posts.php">Return to My Posts</a></p>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> My CMS. All rights reserved.</p>
    </footer>
</body>
</html>