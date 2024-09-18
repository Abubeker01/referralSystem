<?php

function generateReferralCode($userId) {
    
    $uniqueCode = strtoupper(substr(md5(uniqid($userId, true)), 0, 8)); // 8-character code
    return $uniqueCode;
}
?>

