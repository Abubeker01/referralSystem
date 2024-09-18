<?php
session_start(); // Start session to access user data

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['id']; // Fetch user ID from the session

// Debug: Check if user ID is set
if (!$user_id) {
    echo json_encode(['error' => 'User ID is not set in the session']);
    exit();
}

// Connect to your database
$pdo = new PDO('mysql:host=localhost;dbname=referal_system', 'root', '');

// Fetch total points grouped by date for the logged-in user
$query = $pdo->prepare("
    SELECT referral_date AS date, SUM(points_earned) AS total_points
    FROM referrals
    WHERE referral_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    AND id = ?  -- Filter by ID
    GROUP BY referral_date
    ORDER BY referral_date ASC
");
$query->execute([$user_id]);
$results = $query->fetchAll(PDO::FETCH_ASSOC);

// Return JSON for JavaScript
echo json_encode($results);
?>
