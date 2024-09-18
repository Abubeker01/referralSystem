<?php
session_start(); 

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'referal_system';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$email = $_POST['email'];
$password = $_POST['password'];

// Check if the email exists
$query = "SELECT id, username, password FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the user data
    $row = $result->fetch_assoc();
    $hashed_password = $row['password'];

    if (password_verify($password, $hashed_password)) {
        
        $_SESSION['id'] = $row['id'];          
        $_SESSION['username'] = $row['username']; 

        header("Location: /REFSYS/index.php");
        exit();
    } else {
        
        echo json_encode(['status' => 'error', 'message' => 'Incorrect password.']);
    }
} else {
   
    echo json_encode(['status' => 'error', 'message' => 'Email not found.']);
}

$conn->close();
?>
