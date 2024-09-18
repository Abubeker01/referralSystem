<?php
session_start(); 

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['id'];

if (!$user_id) {
    echo json_encode(['error' => 'User ID is not set in the session']);
    exit();
}


$host = 'localhost';
$user = 'root';
$password = '';
$database = 'referal_system';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "
    SELECT username, email, points
    FROM users
    WHERE referrer_id = ?; 
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$referrals = [];

while ($row = $result->fetch_assoc()) {
    $referrals[] = $row;
}


$conn->close();

header('Content-Type: application/json');
echo json_encode($referrals);
?>
