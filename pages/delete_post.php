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
    <script src="https://cdn.tailwindcss.com"></script> <!-- Include Tailwind CSS -->
</head>
<body class="bg-gray-100">

<header class="bg-white shadow">
    <nav class="max-w-4xl mx-auto p-4">
        <ul class="flex justify-between">
            <li><a href="index.php" class="text-blue-500 hover:underline">Home</a></li>
            <li><a href="about.php" class="text-blue-500 hover:underline">About</a></li>
            <li><a href="user_posts.php" class="text-blue-500 hover:underline">My Posts</a></li>
            <li><a href="logout.php" class="text-blue-500 hover:underline">Logout</a></li>
        </ul>
    </nav>
</header>

<main class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md mt-8">
    <h1 class="text-2xl font-bold mb-4">Delete Post</h1>

    <?php if (isset($error)): ?>
        <p class="text-red-500 mb-4"><?= $error ?></p>
    <?php endif; ?>

    <p class="mb-4">Your post has been successfully deleted.</p>
    <p>
        <a href="user_posts.php" class="text-blue-500 hover:underline">Return to My Posts</a>
    </p>
</main>

<footer class="bg-white p-4 mt-6">
    <p class="text-center text-gray-600">&copy; <?= date('Y') ?> My CMS. All rights reserved.</p>
</footer>

</body>
</html>
