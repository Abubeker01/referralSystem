<?php
function assignReferralPoints($userId, $referralPoints, $conn) {
    // Define the point distribution for each level
    $levels = [
        1 => 100,  // Direct referral points
        2 => 50,   // Indirect (2nd level referral) points
        3 => 25,   // Third level referral points
    ];

    // First, assign points to the referred user (the one who just referred someone)
    $currentUserId = $userId; // Start with the referred user (userId)
    $currentLevel = 1;

    // Debug: Check user ID for points assignment
    echo "Assigning points for referral of User ID: " . $userId . "<br>";

    // Assign points to the referred user (the one who made the referral)
    if ($currentLevel <= count($levels)) {
        // Points for the referred user (level 0 or level 1)
        $pointsForLevel = $levels[$currentLevel];
        echo "Assigning " . $pointsForLevel . " points to User ID " . $currentUserId . " at level " . $currentLevel . "<br>";

        // Update the referred user’s points
        $updateQuery = "UPDATE users SET points = points + ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ii", $pointsForLevel, $currentUserId);
        $updateStmt->execute();

        // Insert into referrals table to track
        $insertReferral = "INSERT INTO referrals (referrer_id, referred_user_id, points_earned, referral_level, referral_date) 
                           VALUES (?, ?, ?, ?, NOW())";
        $insertReferralStmt = $conn->prepare($insertReferral);
        $insertReferralStmt->bind_param("iiii", $currentUserId, $userId, $pointsForLevel, $currentLevel);
        $insertReferralStmt->execute();

        $currentLevel++; // Move to the next level (referrer chain)
    }

    // Now, assign points to the referrers in the chain
    while ($currentLevel <= count($levels)) {
        // Fetch the user's referrer (if any)
        $query = "SELECT referrer_id FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $currentUserId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $referrerId = $row['referrer_id'];

            // Debug: Output the referrer ID at each level
            echo "Current Level: " . $currentLevel . " - Referrer ID: " . $referrerId . "<br>";

            // If the referrer exists, assign points for this level
            if ($referrerId) {
                $pointsForLevel = $levels[$currentLevel];
                echo "Assigning " . $pointsForLevel . " points to referrer ID " . $referrerId . " at level " . $currentLevel . "<br>";

                // Update the referrer's points
                $updateQuery = "UPDATE users SET points = points + ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("ii", $pointsForLevel, $referrerId);
                $updateStmt->execute();

                // Insert into referrals table
                $insertReferral = "INSERT INTO referrals (referrer_id, referred_user_id, points_earned, referral_level, referral_date) 
                                   VALUES (?, ?, ?, ?, NOW())";
                $insertReferralStmt = $conn->prepare($insertReferral);
                $insertReferralStmt->bind_param("iiii", $referrerId, $currentUserId, $pointsForLevel, $currentLevel);
                $insertReferralStmt->execute();

                // Move to the next referrer
                $currentUserId = $referrerId;
                $currentLevel++;
            } else {
                // No more referrers, stop the loop
                break;
            }
        } else {
            // No referrer found, exit the loop
            break;
        }
    }

    // Debug: Final output
    echo "Points assigned successfully!<br>";
}


?>
