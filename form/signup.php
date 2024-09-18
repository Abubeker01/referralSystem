<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="R_style.css">
</head>
<body>
<div class="form-container">
        <h2>Sign Up</h2>
        <form id="signupform" method="POST" action="/REFSYS/register_user.php">
        <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="referral_code">Referral Code (optional):</label>
            <input type="text" id="referral_code" name="referral_code">

            <button type="submit">Sign Up</button>
            <p id="message" class="message"></p>
        </form>
        <p>If you have an account, <a href="login.php" class="login-link">log in here</a>.</p>
    </div>


<script type="module" src="form.js"></script>
</body>
</html>