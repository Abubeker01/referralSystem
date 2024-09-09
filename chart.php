<?php
// Connect to your database
$pdo = new PDO('mysql:host=localhost;dbname=referal system', 'root', '');


// Fetch total points grouped by date
$query = $pdo->prepare("
    SELECT referral_date AS date, SUM(points_earned) AS total_points
    FROM referrals
    WHERE referral_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY referral_date
    ORDER BY referral_date ASC
");
$query->execute();
$results = $query->fetchAll(PDO::FETCH_ASSOC);

// Return JSON for JavaScript
echo json_encode($results);
?>
