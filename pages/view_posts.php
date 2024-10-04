<?php 
require 'config.php';

$stmt = $pdo->query('SELECT posts.title, posts.content, users.username, posts.created_at 
                    FROM posts 
                    JOIN users ON posts.author_id = users.id 
                    ORDER BY posts.created_at DESC');
$posts = $stmt->fetchAll();
?>