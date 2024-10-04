<?php

$message = include '../config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
</head>
<body>

<header>
    <h1>About Us</h1>

    <nav>
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>Welcome to Our CMS</h2>
    <p>
        This is a basic Content Management System built with PHP and MySQL.
        Here, you can manage your content easily and efficiently.
    </p>
    <p>
        Our goal is to provide a simple yet powerful platform for managing various types of content.
    </p>

    <p><?php echo $message; ?></p>
</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> All rights reserved.</p>
</footer>

</body>
</html>
