<?php
$message = include '../config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <script src="https://cdn.tailwindcss.com"></script> 
</head>
<body class="bg-gray-100 text-gray-800">

<!-- Header Section -->
<header class="bg-blue-600 text-white p-6">
    <h1 class="text-4xl font-bold">About Us</h1>
    <nav class="mt-4">
        <ul class="flex gap-6">
            <li><a href="../index.php" class="hover:text-yellow-300 transition">Home</a></li>
            <li><a href="about.php" class="hover:text-yellow-300 transition">About</a></li>
        </ul>
    </nav>
</header>

<!-- Main Content -->
<main class="container mx-auto p-8 mt-6">
    <h2 class="text-3xl font-semibold mb-4">Welcome to Our CMS</h2>
    <p class="text-lg mb-4">
        This is a basic Content Management System built with PHP and MySQL.
        Here, you can manage your content easily and efficiently.
    </p>
    <p class="text-lg mb-4">
        Our goal is to provide a simple yet powerful platform for managing various types of content.
    </p>

    
    <p class="text-xl text-green-600 mt-6">
        <?php echo $message; ?>
    </p>
</main>


<footer class="bg-blue-600 text-white p-4 text-center mt-8">
    <p>&copy; <?php echo date("Y"); ?> All rights reserved.</p>
</footer>

</body>
</html>
