<?php
function assignReferralPoints($userId, $referralPoints, $conn) {
    // Define the point distribution for each level
    $levels = [
        1 => 100,  // Direct referral points
        2 => 50,   // Indirect (2nd level referral) points
        3 => 25,   // Third level referral points
    ];

    // Start with the new user's referrer (who referred this userId)
    $query = "SELECT referrer_id FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentUserId = $row['referrer_id']; // Start from the referrer of the newly registered user

        // Track the current level and referrer
        $currentLevel = 1;

        // Debug: Check user ID for points assignment
        echo "Assigning points for referral of User ID: " . $userId . "<br>";

        while ($currentLevel <= count($levels)) {
            // Check if there's a valid referrer at this level
            if ($currentUserId) {
                // Debug: Output the referrer ID at each level
                echo "Current Level: " . $currentLevel . " - Referrer ID: " . $currentUserId . "<br>";

                // Assign points for this level
                $pointsForLevel = $levels[$currentLevel];
                echo "Assigning " . $pointsForLevel . " points to referrer ID " . $currentUserId . " at level " . $currentLevel . "<br>";

                // Update referrer’s points
                $updateQuery = "UPDATE users SET points = points + ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("ii", $pointsForLevel, $currentUserId);
                $updateStmt->execute();

                // Insert a new entry in the referrals table for tracking
                $insertReferral = "INSERT INTO referrals (referrer_id, referred_user_id, points_earned, referral_level, referral_date) 
                                   VALUES (?, ?, ?, ?, NOW())";
                $insertReferralStmt = $conn->prepare($insertReferral);
                $insertReferralStmt->bind_param("iiii", $currentUserId, $userId, $pointsForLevel, $currentLevel);
                $insertReferralStmt->execute();

                // Move to the next referrer up in the chain (indirect referral)
                $query = "SELECT referrer_id FROM users WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $currentUserId);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                $currentUserId = $row['referrer_id']; // Move up the chain
                $currentLevel++;
            } else {
                // No more referrers, stop the loop
                break;
            }
        }

        // Debug: Final output
        echo "Points assigned successfully!<br>";
    }
}

?>
