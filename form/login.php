<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <form id="loginForm" method="POST" action="/REFSYS/login_user.php">
        <input type="email" id="email" name="email" placeholder="Email" required><br>
        <input type="password" id="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
    <p id="loginMessage"></p>
    <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
</div>

<script type="module" src="login.js"></script>
</body>
</html>
