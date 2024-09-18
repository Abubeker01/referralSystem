<?php
// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'referal_system';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch total points for 'direct' and 'indirect' referrals
$query = "
    SELECT
        IFNULL(SUM(CASE WHEN referral_level = 1 THEN points_earned ELSE 0 END), 0) AS direct_points,
        IFNULL(SUM(CASE WHEN referral_level > 1 THEN points_earned ELSE 0 END), 0) AS indirect_points
    FROM referrals
    WHERE referral_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY);
";

// Query to fetch points earned per day for the last 7 days
$last7days_query = "
    SELECT
        DATE(referral_date) AS date,
        IFNULL(SUM(points_earned), 0) AS points_per_day
    FROM referrals
    WHERE referral_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(referral_date);
";

$result = $conn->query($query);
$result_last7days = $conn->query($last7days_query);

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

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
