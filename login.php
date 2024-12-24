<?php
session_start();

if (isset($_POST['username']) && isset($_POST['password'])) {
    // Check if the entered credentials match the fixed ones
    if ($_POST['username'] == 'Simon Ngugi' && $_POST['password'] == '@ngugikagiri7209') {
        $_SESSION['admin'] = true;
        header('Location: add_product.php');
        exit();
    } else {
        echo "<p class='error'>Invalid credentials. Please try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Sneak&Chic</title>
     <style>
        /* General Styling */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background-color: #0d1117; /* Dark background */
    color: #c9d1d9; /* Light text */
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Login Container */
.login-container {
    background-color: none; /* Dark background */
    color: #c9d1d9; /* Light text */
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    width: 400px;
    text-align: center;
    border: 1px solid #238636; /* Green border */
}

/* Brand Name */
.brand-name {
    font-size: 36px;
    font-weight: bold;
    color: #4ea8de; /* Light blue color for brand */
    margin-bottom: 20px;
}

/* Login Title */
.login-title {
    font-size: 24px;
    color: #ff6600; /* Orange color for title */
    margin-bottom: 20px;
}

/* Input Fields */
input[type="text"], input[type="password"] {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 6px;
    background-color: #0d1117; /* Dark background */
    border: 1px solid #238636; /* Green border */
    color: #c9d1d9; /* Light text */
    font-size: 16px;
}

input[type="text"]:focus, input[type="password"]:focus {
    border: 1px solid #4ea8de; /* Blue border on focus */
    outline: none;
}

/* Submit Button */
input[type="submit"] {
    width: 100%;
    padding: 12px;
    border-radius: 6px;
    background-color: #4CAF50; /* Green background for button */
    color: white;
    font-size: 18px;
    cursor: pointer;
    border: none;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background:none; /* Darker green on hover */
    border: 1px solid rgb(33, 250, 4);
}

/* Error Message */
.error {
    color: red;
    font-size: 14px;
    margin-top: 10px;
    background-color: #f8d7da;
    color: #721c24;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #f5c6cb;
}

/* Responsive Design */
@media (max-width: 480px) {
    .login-container {
        width: 90%;
        padding: 20px;
    }
}

     </style>
        
     
</head>
<body>
    <div class="login-container">
        <h1 class="brand-name">Sneak&Chic</h1>
        <h2 class="login-title">Admin Login</h2>

        <form action="admin_login.php" method="POST" class="login-form">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required><br><br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br><br>

            <input type="submit" value="Login" name="submit">
        </form>
    </div>

</body>
</html>
