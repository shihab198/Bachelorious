<?php
require_once '../includes/config.php';
require_once '../includes/db.php';

// Check if user is admin
if(!isset($_SESSION['user_id']) || $_SESSION['username'] != 'admin') {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

if(!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$database = new Database();
$conn = $database->getConnection();

$property_id = $_GET['id'];

// Approve the property
$query = "UPDATE properties SET is_approved = 1 WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$property_id]);

header("Location: dashboard.php?approved=1");
exit();
?>