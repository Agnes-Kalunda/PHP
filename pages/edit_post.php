<?php
session_start();
require_once '../config.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $title = $_POST['title'];
    $content = $_POST['content'];

    try {
        $stmt = $pdo->prepare('UPDATE posts SET title = :title, content = :content WHERE id = :id AND author_id = :author_id');
        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'id' => $post_id,
            'author_id' => $_SESSION['user_id']
        ]);
        header('Location: user_posts.php');
        exit();
    } catch (PDOException $e) {
        error_log('Error updating post: ' . $e->getMessage());
        $error = 'An error occurred while updating the post.';
    }
} else {
    // Fetch the post data
    try {
        $stmt = $pdo->prepare('SELECT title, content FROM posts WHERE id = :id AND author_id = :author_id');
        $stmt->execute([
            'id' => $post_id,
            'author_id' => $_SESSION['user_id']
        ]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post) {
            
            header('Location: user_posts.php');
            exit();
        }
    } catch (PDOException $e) {
        error_log('Error fetching post: ' . $e->getMessage());
        $error = 'An error occurred while fetching the post.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post - My CMS</title>
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
        <h1>Edit Post</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form action="edit_post.php?id=<?= $post_id ?>" method="post">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>

            <label for="content">Content:</label>
            <textarea id="content" name="content" required><?= htmlspecialchars($post['content']) ?></textarea>

            <button type="submit">Update Post</button>
        </form>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> My CMS. All rights reserved.</p>
    </footer>
</body>
</html>