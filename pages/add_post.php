<?php 
session_start();
require '../config.php';

// Redirect to login user not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$errors =[];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // validat data

    if (empty($title) || empty($content)) {
        $errors[] = 'Both title and content are required';
}

    if (empty($errors)){
        $stmt = $pdo->prepare('INSERT INTO posts (title, content, author_id) VALUES (:title, :content, :author_id)');
        if ($stmt->execute([
            'title' => $title,
            'content'=> $content,
            'author_id'=> $_SESSION['user_id'],
        ])){
            header('Location:view_posts.php');
            exit();
        } else{
            $errors[] = "Something went wrong. Please try again";

        }

    }



}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add new post</title>
</head>
<body>
    <h2>Add new post</h2>

    <?php if (!empty($errors)): ?>
    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?php echo $error; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

    <form action="add_post.php" method="POST">
    <label for="title">Title:</label>
    <input type="text" name="title" required><br>
    
    <label for="content">Content:</label>
    <textarea name="content" rows="5" required></textarea><br>
    
    <button type="submit">Add Post</button>
</form>

<a href="../index.php">View All Posts</a>
<a href="user_posts.php">My Posts</a>

</body>
</html>