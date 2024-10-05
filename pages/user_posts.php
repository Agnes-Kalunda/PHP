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
    <h1 class="text-2xl font-bold mb-4">My Posts</h1>

    <?php if (!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
            <article class="mb-6 border-b pb-4">
                <h2 class="text-xl font-semibold text-blue-600"><?= htmlspecialchars($post['title']) ?></h2>
                <p class="text-gray-700"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <footer class="flex justify-between items-center mt-2">
                    <p class="text-gray-500 text-sm">Date: <?= date('F j, Y', strtotime($post['created_at'])) ?></p>
                    <div>
                        <a href="edit_post.php?id=<?= $post['id'] ?>" class="text-blue-500 hover:underline mr-4">Edit</a>
                        <a href="delete_post.php?id=<?= $post['id'] ?>" class="text-red-500 hover:underline" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                    </div>
                </footer>
            </article>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-gray-700">You haven't created any posts yet.</p>
    <?php endif; ?>

    <p class="mt-4"><a href="add_post.php" class="text-blue-500 hover:underline">Add a New Post</a></p>
</main>

<footer class="bg-white p-4 mt-6">
    <p class="text-center text-gray-600">&copy; <?= date('Y') ?> My CMS. All rights reserved.</p>
</footer>

</body>
</html>
