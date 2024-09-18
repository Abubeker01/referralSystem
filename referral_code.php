<?php
// referral_code_generator.php

function generateReferralCode($userId) {
    // Create a unique referral code by hashing the user's ID with some randomness
    $uniqueCode = strtoupper(substr(md5(uniqid($userId, true)), 0, 8)); // 8-character code
    return $uniqueCode;
}
?>

