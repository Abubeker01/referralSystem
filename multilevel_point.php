<?php
function assignReferralPoints($userId, $referralPoints, $conn) {
    $levels = [
        1 => 100,  // Direct referral points
        2 => 50,   // Indirect (2nd level referral) points
        3 => 25,   // Third level referral points
    ];

    $currentUserId = $userId;
    $currentLevel = 1;

    echo "Assigning points for referral of User ID: " . $userId . "<br>";

    // Assign points to the one who made the referral
    if ($currentLevel <= count($levels)) {
        $pointsForLevel = $levels[$currentLevel];
        echo "Assigning " . $pointsForLevel . " points to User ID " . $currentUserId . " at level " . $currentLevel . "<br>";

        // Update the referred user’s points
        $updateQuery = "UPDATE users SET points = points + ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ii", $pointsForLevel, $currentUserId);
        $updateStmt->execute();

        $currentLevel++;
    }

    //assign points to the referrers in the chain
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

        
            echo "Current Level: " . $currentLevel . " - Referrer ID: " . $referrerId . "<br>";

            
            if ($referrerId) {
                $pointsForLevel = $levels[$currentLevel];
                echo "Assigning " . $pointsForLevel . " points to referrer ID " . $referrerId . " at level " . $currentLevel . "<br>";

                $updateQuery = "UPDATE users SET points = points + ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("ii", $pointsForLevel, $referrerId);
                $updateStmt->execute();

                $insertReferral = "INSERT INTO referrals (referrer_id, referred_user_id, points_earned, referral_level, referral_date) 
                                   VALUES (?, ?, ?, ?, NOW())";
                $insertReferralStmt = $conn->prepare($insertReferral);
                $insertReferralStmt->bind_param("iiii", $referrerId, $currentUserId, $pointsForLevel, $currentLevel);
                $insertReferralStmt->execute();

                
                $currentUserId = $referrerId;
                $currentLevel++;
            } else {
                
                break;
            }
        } else {
           
            break;
        }
    }

    echo "Points assigned successfully!<br>";
}


?>
