<?php
require '../config.php';


// initializing variables
$username = $email = $password = $confirm_password= '';
$errors =[];
// get dta
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
}

// validate form data
if (empty($username) || empty($email) || empty($password)){
    $errors[] = "All fields are required.";
}

    elseif($password !== $confirm_password){
        $errors[] = "Passwords do not match";
    }

    else{
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username OR email = :email');
        $stmt ->execute(['username' => $username,'email'=> $email]);

        if($stmt->rowCount() >0){
            $errors[] = "username or email already exists.";
        }
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    
</body>
</html>