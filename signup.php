<?php
// Function to save user data to a text file
function saveUser($username, $password, $filename) {
    // Prepare user data
    $userData = "$username,$password\n";

    // Open the file for writing (create if not exists)
    $file = fopen($filename, 'a'); // 'a' mode appends to the file

    // Write user data to the file
    fwrite($file, $userData);

    // Close the file handle
    fclose($file);
}

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

// Sign-up form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $filename = "users.txt.php";

    if (!findUserByUsername($username, $filename)) {
        saveUser($username, $password, $filename);
        echo "Sign-up successful!";
    } else {
        echo "Username is already taken. Please choose another one.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Sign Up</h1>
    <form method="post" action="signup.php">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Sign Up">
    </form>
</body>
</html>
