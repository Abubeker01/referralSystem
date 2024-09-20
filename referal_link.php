<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['id'];

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'referal_system';
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the referral link for the logged-in user
$query = "SELECT referral_code FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['referral_code' => $row['referral_code']]);
} else {
    echo json_encode(['error' => 'Referral link not found']);
}

$stmt->close();
$conn->close();
?>
