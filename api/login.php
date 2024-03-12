<?php
// Function to find a user by username
function findUserByUsername($username, $filename) {
    // Open the file for reading
    $file = fopen($filename, 'r');

    // Read file line by line
    while (!feof($file)) {
        $line = fgets($file);
        $userData = explode(',', $line);
        if ($userData[0] === $username) {
            fclose($file);
            return true;
        }
    }

    // Close the file handle
    fclose($file);
    return false;
}

// Login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $filename = "users.txt.php";

    if (findUserByUsername($username, $filename)) {
        // Set cookies for username and password (not recommended for sensitive data)
        setcookie('username', $username, time() + (86400 * 30), "/"); // 86400 = 1 day
        setcookie('password', $password, time() + (86400 * 30), "/"); // 86400 = 1 day

        header("Location: dashboard.php");
        exit;
    } else {
        echo "Invalid username or password. Please try again.";
    }
}

// Check if username and password are stored in cookies
if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
    // You can use the saved credentials for something here, like automatically logging in the user
    $savedUsername = $_COOKIE['username'];
    $savedPassword = $_COOKIE['password'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="post" action="login.php">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Log In">
    </form>
</body>
</html>
