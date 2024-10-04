<?php
require 'vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? '127.0.0.1'; 
$dbname = $_ENV['DB_NAME'] ?? '';         
$user = $_ENV['DB_USER'] ?? '';           
$password = $_ENV['DB_PASSWORD'] ?? '';   

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
    return "Database connection successful!";
} catch (PDOException $e) {
    
    return 'Connection failed: ' . $e->getMessage();
}