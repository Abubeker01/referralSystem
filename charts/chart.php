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

$pdo = new PDO('mysql:host=localhost;dbname=referal_system', 'root', '');


$query = $pdo->prepare("
    SELECT referral_date AS date, SUM(points_earned) AS total_points
    FROM referrals
    WHERE referral_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    AND referrer_id = ?  -- Filter by ID
    GROUP BY referral_date
    ORDER BY referral_date ASC
");
$query->execute([$user_id]);
$results = $query->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
?>
