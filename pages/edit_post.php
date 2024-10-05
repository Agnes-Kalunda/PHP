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
    <h1 class="text-2xl font-bold mb-4">Edit Post</h1>

    <?php if (isset($error)): ?>
        <p class="text-red-500 mb-4"><?= $error ?></p>
    <?php endif; ?>

    <form action="edit_post.php?id=<?= $post_id ?>" method="post">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required class="mt-1 p-2 border border-gray-300 rounded w-full">
        </div>

        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-700">Content:</label>
            <textarea id="content" name="content" required class="mt-1 p-2 border border-gray-300 rounded w-full" rows="6"><?= htmlspecialchars($post['content']) ?></textarea>
        </div>

        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Update Post</button>
    </form>
</main>

<footer class="bg-white p-4 mt-6">
    <p class="text-center text-gray-600">&copy; <?= date('Y') ?> My CMS. All rights reserved.</p>
</footer>

</body>
</html>
