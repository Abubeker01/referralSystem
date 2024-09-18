<?php
session_start(); 

// Connect to the database
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'referal_system';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get email and password from POST request
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

    // Verify the password
    if (password_verify($password, $hashed_password)) {
        // Successful login
        $_SESSION['id'] = $row['id'];          // Store user ID
        $_SESSION['username'] = $row['username']; // Store the username in session

        header("Location: /REFSYS/index.php");
        exit();
    } else {
        // Invalid password
        echo json_encode(['status' => 'error', 'message' => 'Incorrect password.']);
    }
} else {
    // Email not found
    echo json_encode(['status' => 'error', 'message' => 'Email not found.']);
}

$conn->close();
?>
