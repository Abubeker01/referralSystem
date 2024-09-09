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
        <!--profile of the user-->
        <div class="U-profile">
            <img src="img/refpro.jpg" alt="try">
            <h3>user-name</h3>
        </div>
    </div>


    <!--referal generated code-->
    <div class="invite">
        <textarea name="" id=""></textarea>
        <button>Share</button>
    </div>

    <!--referal monthly chart-->

    <h1>Credit Earnings Over the Last 7 Days</h1>
    <canvas class="referralChart"></canvas>

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