<?php
session_start(); // Start the session to access the user information

// Check if the user is logged in by verifying the session
if (!isset($_SESSION['id'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: /form/login.php");
    exit();
}

// Store the logged-in user's username to display it on the dashboard
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
        <!-- The profile and the textarea/button section should be in a flex container for alignment -->
        <div class="header-container">
            <!-- Profile of the user -->
            <div class="U-profile">
                <img src="img/refpro.jpg" alt="try">
                <span><?php echo htmlspecialchars($username); ?></span>
                
            </div>
            <!-- Referal generated code -->
            <div class="invite">
                <textarea placeholder="Enter referral link..."></textarea>
                <button>Share</button>
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
        <tr>
            <th>name</th>
            <th>ID</th>
            <th>Point</th>
        </tr>
        <tr>
            <td>abe</td>
            <td>1234</td>
            <td>30</td>
        </tr>
        <tr>
            <td>abe</td>
            <td>1234</td>
            <td>30</td>
        </tr>
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
    <script src="chart.js"></script>
</body>

</html>