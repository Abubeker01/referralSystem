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

// Query to fetch total points for 'direct' and 'indirect' referrals for the logged-in user
$query = "
    SELECT
        IFNULL(SUM(CASE WHEN referral_level = 1 THEN points_earned ELSE 0 END), 0) AS direct_points,
        IFNULL(SUM(CASE WHEN referral_level > 1 THEN points_earned ELSE 0 END), 0) AS indirect_points
    FROM referrals
    WHERE referral_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    AND referrer_id = ?;  -- Filter by logged-in user ID
";

// Query to fetch points earned per day for the last 7 days
$last7days_query = "
    SELECT
        DATE(referral_date) AS date,
        IFNULL(SUM(points_earned), 0) AS points_per_day
    FROM referrals
    WHERE referral_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    AND referrer_id = ?  -- Filter by logged-in user ID
    GROUP BY DATE(referral_date);
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id); // Bind the user ID to the query
$stmt->execute();
$result = $stmt->get_result();

// for the second query (points per day)
$stmt_last7days = $conn->prepare($last7days_query);
$stmt_last7days->bind_param("i", $user_id);
$stmt_last7days->execute();
$result_last7days = $stmt_last7days->get_result();

$response = [];

// Handle direct and indirect points
if ($result) {
    $data = $result->fetch_assoc();
    $response['directPoints'] = $data['direct_points'] ?? 0;
    $response['indirectPoints'] = $data['indirect_points'] ?? 0;
} else {
    $response['error'] = "Query error: " . $conn->error;
}

// Handle points per day for the last 7 days
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
