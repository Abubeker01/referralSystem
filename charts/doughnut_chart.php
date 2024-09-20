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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'referal_system';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch total points for 'direct' and 'indirect' referrals for all time
$totalPointsQuery = "
    SELECT
        IFNULL(SUM(CASE WHEN referral_level = 1 THEN points_earned ELSE 0 END), 0) AS total_direct_points,
        IFNULL(SUM(CASE WHEN referral_level > 1 THEN points_earned ELSE 0 END), 0) AS total_indirect_points
    FROM referrals
    WHERE referrer_id = ?;  -- Filter by logged-in user ID
";

// Query to fetch points earned in the last 7 days for 'direct' and 'indirect' referrals
$last7daysPointsQuery = "
    SELECT
        IFNULL(SUM(CASE WHEN referral_level = 1 THEN points_earned ELSE 0 END), 0) AS direct_points,
        IFNULL(SUM(CASE WHEN referral_level > 1 THEN points_earned ELSE 0 END), 0) AS indirect_points
    FROM referrals
    WHERE referral_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    AND referrer_id = ?;  -- Filter by logged-in user ID
";

// Query to fetch points earned per day for the last 7 days
$last7daysQuery = "
    SELECT
        DATE(referral_date) AS date,
        IFNULL(SUM(points_earned), 0) AS points_per_day
    FROM referrals
    WHERE referral_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    AND referrer_id = ?  -- Filter by logged-in user ID
    GROUP BY DATE(referral_date);
";

$stmt_total = $conn->prepare($totalPointsQuery);
$stmt_total->bind_param("i", $user_id);
$stmt_total->execute();
$result_total = $stmt_total->get_result();

$stmt_last7daysPoints = $conn->prepare($last7daysPointsQuery);
$stmt_last7daysPoints->bind_param("i", $user_id);
$stmt_last7daysPoints->execute();
$result_last7daysPoints = $stmt_last7daysPoints->get_result();

$stmt_last7days = $conn->prepare($last7daysQuery);
$stmt_last7days->bind_param("i", $user_id);
$stmt_last7days->execute();
$result_last7days = $stmt_last7days->get_result();

$response = [];

if ($result_total) {
    $totalData = $result_total->fetch_assoc();
    $response['totalDirectPoints'] = $totalData['total_direct_points'] ?? 0;
    $response['totalIndirectPoints'] = $totalData['total_indirect_points'] ?? 0;
} else {
    $response['error'] = "Query error (total points): " . $conn->error;
}


if ($result_last7daysPoints) {
    $last7daysData = $result_last7daysPoints->fetch_assoc();
    $response['directPoints'] = $last7daysData['direct_points'] ?? 0;
    $response['indirectPoints'] = $last7daysData['indirect_points'] ?? 0;
} else {
    $response['error'] = "Query error (last 7 days points): " . $conn->error;
}

if ($result_last7days) {
    $last7days = [];
    while ($row = $result_last7days->fetch_assoc()) {
        $last7days[] = (int) $row['points_per_day'];
    }
    $response['last7days'] = $last7days;
} else {
    $response['last7days'] = [];
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
