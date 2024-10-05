<?php
require '../config.php';

// Initializing variables
$username = $email = $password = $confirm_password = '';
$errors = [];

// Get data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
}

// Validate form data
if (empty($username) || empty($email) || empty($password)) {
    $errors[] = "All fields are required.";
} elseif ($password !== $confirm_password) {
    $errors[] = "Passwords do not match";
} else {
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username OR email = :email');
    $stmt->execute(['username' => $username, 'email' => $email]);

    if ($stmt->rowCount() > 0) {
        $errors[] = "Username or email already exists.";
    }
}

if (empty($errors)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES(:username, :email, :password)");
    if ($stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashed_password])) {
        header("Location: login.php?success=1");
        exit();
    } else {
        $errors[] = "Something went wrong. Please retry.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script> 
</head>
<body class="bg-gray-100">

<!-- Main Container -->
<div class="flex justify-center items-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>

        
        <?php if (!empty($errors)): ?>
            <ul class="bg-red-100 text-red-600 p-4 rounded mb-4">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <!-- Registration Form -->
        <form action="register.php" method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-gray-700 font-medium">Username:</label>
                <input type="text" name="username" id="username" class="w-full p-2 border border-gray-300 rounded mt-1" required>
            </div>

            <div>
                <label for="email" class="block text-gray-700 font-medium">Email:</label>
                <input type="email" name="email" id="email" class="w-full p-2 border border-gray-300 rounded mt-1" required>
            </div>

            <div>
                <label for="password" class="block text-gray-700 font-medium">Password:</label>
                <input type="password" name="password" id="password" class="w-full p-2 border border-gray-300 rounded mt-1" required>
            </div>

            <div>
                <label for="confirm_password" class="block text-gray-700 font-medium">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="w-full p-2 border border-gray-300 rounded mt-1" required>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition">Register</button>
        </form>

        <p class="text-gray-600 text-sm text-center mt-4">Already have an account? 
            <a href="login.php" class="text-blue-500 hover:underline">Login here</a>
        </p>
    </div>
</div>

</body>
</html>
