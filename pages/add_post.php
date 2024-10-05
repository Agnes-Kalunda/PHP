<?php 
session_start();
require '../config.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$errors = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // Validate data
    if (empty($title)) {
        $errors[] = 'Title is required';
    }
    if (empty($content)) {
        $errors[] = 'Content is required';
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare('INSERT INTO posts (title, content, author_id) VALUES (:title, :content, :author_id)');
            $result = $stmt->execute([
                'title' => $title,
                'content' => $content,
                'author_id' => $_SESSION['user_id'],
            ]);

            if ($result) {
                $success_message = "Post added successfully!";
                // Clear the form data
                $title = $content = '';
            } else {
                $errors[] = "Failed to add the post. Please try again.";
            }
        } catch (PDOException $e) {
            error_log('Error adding post: ' . $e->getMessage());
            $errors[] = "An error occurred while adding the post. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Post</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- Include Tailwind CSS -->
</head>
<body class="bg-gray-100">

<header class="bg-white shadow">
    <nav class="max-w-4xl mx-auto p-4">
        <ul class="flex justify-between">
            <li><a href="../index.php" class="text-blue-500 hover:underline">Home</a></li>
            <li><a href="user_posts.php" class="text-blue-500 hover:underline">My Posts</a></li>
            <li><a href="logout.php" class="text-blue-500 hover:underline">Logout</a></li>
        </ul>
    </nav>
</header>

<main class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md mt-8">
    <h2 class="text-2xl font-bold mb-6">Add New Post</h2>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 text-red-600 p-4 rounded mb-4">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success_message): ?>
        <div class="bg-green-100 text-green-600 p-4 rounded mb-4">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>

    <form action="add_post.php" method="POST" class="space-y-6">
        <div>
            <label for="title" class="block text-gray-700 font-medium">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title ?? ''); ?>" required class="w-full p-2 border border-gray-300 rounded mt-1">
        </div>

        <div>
            <label for="content" class="block text-gray-700 font-medium">Content:</label>
            <textarea id="content" name="content" rows="5" required class="w-full p-2 border border-gray-300 rounded mt-1"><?php echo htmlspecialchars($content ?? ''); ?></textarea>
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition">Add Post</button>
    </form>
</main>

<footer class="bg-white p-4 mt-6">
    <p class="text-center text-gray-600">&copy; <?php echo date('Y'); ?> My CMS. All rights reserved.</p>
</footer>

</body>
</html>
