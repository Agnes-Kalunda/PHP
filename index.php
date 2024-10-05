<?php

require 'config.php';

try {
    $stmt = $pdo->query('SELECT posts.id, posts.title, posts.content, users.username, posts.created_at 
                         FROM posts 
                         JOIN users ON posts.author_id = users.id 
                         ORDER BY posts.created_at DESC');
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Error fetching posts: ' . $e->getMessage());
    $posts = [];
}

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My CMS - Home</title>
    <script src="https://cdn.tailwindcss.com"></script> 
</head>
<body class="bg-gray-100 text-gray-800">

<header class="bg-blue-600 text-white p-4">

    <nav class="flex gap-4 mt-2">
        <a href="index.php" class="hover:text-yellow-300">Home</a>
        <a href="pages/about.php" class="hover:text-yellow-300">About</a>
        <a href="pages/login.php" class="hover:text-yellow-300">Login</a>
    </nav>
</header>

<main class="container mx-auto mt-8 p-4">
    <h2 class="text-2xl font-semibold mb-6">Latest Posts</h2>

    <?php if (empty($posts)): ?>
        <p class="text-red-500">No posts available.</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div class="bg-white shadow-md rounded-md p-4 mb-6">
                <h3 class="text-xl font-bold text-blue-600"><?php echo htmlspecialchars($post['title']); ?></h3>
                <p class="mt-2 text-gray-700"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                <p class="text-sm text-gray-500 mt-4"><strong>Author:</strong> <?php echo htmlspecialchars($post['username']); ?> | <strong>Date:</strong> <?php echo $post['created_at']; ?></p>
                <hr class="my-4">
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p><a href="pages/add_post.php" class="text-blue-600 underline">Add a New Post</a></p>
    <?php else: ?>
        <p><a href="pages/login.php" class="text-blue-600 underline">Login to Add a Post</a></p>
    <?php endif; ?>
</main>

<footer class="bg-blue-600 text-white p-4 text-center mt-12">
    <p>&copy; 2024 My CMS. All rights reserved.</p>
</footer>

</body>
</html>
