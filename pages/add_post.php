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