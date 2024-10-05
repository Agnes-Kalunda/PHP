<?php 
session_start();
require '../config.php';

// Redirect to login if user not logged in
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
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="user_posts.php">My Posts</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Add New Post</h2>

        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <form action="add_post.php" method="POST">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title ?? ''); ?>" required>

            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="5" required><?php echo htmlspecialchars($content ?? ''); ?></textarea>

            <button type="submit">Add Post</button>
        </form>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> My CMS. All rights reserved.</p>
    </footer>
</body>
</html>