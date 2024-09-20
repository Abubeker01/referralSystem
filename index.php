<?php
session_start();

if (!isset($_SESSION['id'])) {

    header("Location: /form/login.php");
    exit();
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">

</head>
<title>Referal System</title>

<body>
    <div class="uperDiv">
        <h1>Referal Dashboard</h1>
        <div class="header-container">
            <!-- Profile of the user -->
            <div class="U-profile">
                <img src="img/refpro.jpg" alt="try">
                <span><?php echo htmlspecialchars($username); ?></span>

            </div>
            <!-- Referal generated code -->
            <!-- Referral generated code -->
            <div class="invite">
                <label>Share your referral link:</label>
                <!-- Hidden span containing the referral link -->
                <span class="referral-link" id="referralLink" style="display:none;"></span>
                <!-- Button to copy the referral link -->
                <button class="copy-btn" id="copy-link">Copy Link</button>
            </div>

        </div>
    </div>

    <!--referal monthly chart-->
    <div class="ref-graph">
        <h3>Credit Earnings Over the Last 30 Days</h3>
        <canvas id="referralChart"></canvas>
    </div>


    <!--table of referals-->
    <table class="ref-table" style="width:60%">
        <thead>
            <tr>
                <th>Name</th>
                <th>email</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($referrals)): ?>
                <?php foreach ($referrals as $referral): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($referral['username']); ?></td>
                        <td><?php echo htmlspecialchars($referral['email']); ?></td>
                        <td><?php echo htmlspecialchars($referral['points']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No referrals found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>


    <!--referal chart-->
    <div class="referal-stats">
        <h3>Credit Earnings Over the Last 7 Days</h3>
        <div class="chart-container">
            <canvas class="chart"></canvas>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/moment"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment"></script>
    <script src="referal_code.js"></script>
    <script src="table.js"></script>
    <script src="chart.js"></script>
</body>

</html>