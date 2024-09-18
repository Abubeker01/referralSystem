<?php
include 'referral_code.php';
include 'multilevel_point.php';

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'referal_system';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_name = $_POST['username'];
$email = $_POST['email'];
$raw_password = $_POST['password'];
$referral_code_entered = $_POST['referral_code'] ?? null; 

$hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

$query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $user_name, $email, $hashed_password);

if ($stmt->execute()) {
    $userId = $stmt->insert_id; 

    if (!empty($referral_code_entered)) {
        $referrerQuery = "SELECT id FROM users WHERE referral_code = ?";
        $referrerStmt = $conn->prepare($referrerQuery);
        $referrerStmt->bind_param("s", $referral_code_entered);
        $referrerStmt->execute();
        $referrerResult = $referrerStmt->get_result();

        if ($referrerResult->num_rows > 0) {
            $referrerRow = $referrerResult->fetch_assoc();
            $referrerId = $referrerRow['id'];

            $referralPoints = 100;
            assignReferralPoints($referrerId, $referralPoints, $conn);

            // Updating the new user record with their referrer's ID 
            $updateReferrerQuery = "UPDATE users SET referrer_id = ? WHERE id = ?";
            $updateReferrerStmt = $conn->prepare($updateReferrerQuery);
            $updateReferrerStmt->bind_param("ii", $referrerId, $userId);
            $updateReferrerStmt->execute();

            $insertReferral = "INSERT INTO referrals (referrer_id, referred_user_id, points_earned, referral_level, referral_date) 
                               VALUES (?, ?, ?, 1, NOW())";
            $insertReferralStmt = $conn->prepare($insertReferral);
            $insertReferralStmt->bind_param("iii", $referrerId, $userId, $referralPoints);
            $insertReferralStmt->execute();
        }
    }

    //Generating a unique referral code for the new user
    $newReferralCode = generateReferralCode($userId, $conn);

    // Update the new user with their unique referral code
    $updateQuery = "UPDATE users SET referral_code = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("si", $newReferralCode, $userId);
    $updateStmt->execute();

    header("Location: /REFSYS/index.php");
    exit();
} else {
    
    echo "Error: " . $stmt->error;
}

$conn->close();
?>
