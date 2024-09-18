<?php
include 'referral_code.php';
include 'multilevel_point.php';

// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'referal_system';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data from the POST request
$user_name = $_POST['username'];
$email = $_POST['email'];
$raw_password = $_POST['password'];
$referral_code_entered = $_POST['referral_code'] ?? null; // The referral code entered by the new user

// Hash the password
$hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

// Insert new user and get their user ID
$query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $user_name, $email, $hashed_password);

if ($stmt->execute()) {
    $userId = $stmt->insert_id;  // Get the new user's ID

    // Step 1: Check if a referral code was entered and get the referrer ID
    if (!empty($referral_code_entered)) {
        $referrerQuery = "SELECT id FROM users WHERE referral_code = ?";
        $referrerStmt = $conn->prepare($referrerQuery);
        $referrerStmt->bind_param("s", $referral_code_entered);
        $referrerStmt->execute();
        $referrerResult = $referrerStmt->get_result();

        if ($referrerResult->num_rows > 0) {
            $referrerRow = $referrerResult->fetch_assoc();
            $referrerId = $referrerRow['id'];

            $referralPoints = 100; // Example: Assign 100 points for a direct referral

            // Step 2: Assign points immediately after signup
            assignReferralPoints($referrerId, $referralPoints, $conn);

            // Step 3: Update the new user record with their referrer's ID 
            $updateReferrerQuery = "UPDATE users SET referrer_id = ? WHERE id = ?";
            $updateReferrerStmt = $conn->prepare($updateReferrerQuery);
            $updateReferrerStmt->bind_param("ii", $referrerId, $userId);
            $updateReferrerStmt->execute();

            // Step 4: Insert into the referrals table immediately
            $insertReferral = "INSERT INTO referrals (referrer_id, referred_user_id, points_earned, referral_level, referral_date) 
                               VALUES (?, ?, ?, 1, NOW())"; // Level 1 referral
            $insertReferralStmt = $conn->prepare($insertReferral);
            $insertReferralStmt->bind_param("iii", $referrerId, $userId, $referralPoints);
            $insertReferralStmt->execute();
        }
    }

    // Step 5: Generate a unique referral code for the new user
    $newReferralCode = generateReferralCode($userId, $conn);

    // Step 6: Update the new user with their unique referral code
    $updateQuery = "UPDATE users SET referral_code = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("si", $newReferralCode, $userId);
    $updateStmt->execute();

    // Step 7: Redirect to the homepage after successful registration
   // header("Location: /REFSYS/index.php");
   // exit();
} else {
    // Display an error message if the registration fails
    echo "Error: " . $stmt->error;
}

// Close the database connection
$conn->close();
?>
