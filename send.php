<?php

// Function to read JSON file and decode it
function readJsonFile($filename) {
    $data = file_get_contents($filename);
    return json_decode($data, true);
}

// Function to write data to JSON file
function writeJsonFile($filename, $data) {
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
}

// Function to authenticate user with cookie
function authenticateUser() {
    // Replace with your own authentication logic
    // For demonstration purposes, let's assume authentication is successful if username cookie is set
    return isset($_COOKIE['username']);
}

// Check if the request method is POST and the user is authenticated
if($_SERVER['REQUEST_METHOD'] === 'POST' && authenticateUser()) {
    // Check if username, recipient, and amount are set in the request
    if(isset($_POST['recipient']) && isset($_POST['amount'])) {
        $username = $_COOKIE['username'];
        $recipient = $_POST['recipient'];
        $amount = floatval($_POST['amount']);

        // Load bux.json file
        $buxFile = 'bux.json';
        $buxData = readJsonFile($buxFile);

        // Check if the file exists and is readable
        if ($buxData !== null) {
            // Check if the user and recipient exist in the bux.json file
            if (isset($buxData[$username]) && isset($buxData[$recipient])) {
                // Check if the user has sufficient balance to transfer
                if ($buxData[$username]['balance'] >= $amount) {
                    // Deduct amount from the user's balance and add it to the recipient's balance
                    $buxData[$username]['balance'] -= $amount;
                    $buxData[$recipient]['balance'] += $amount;
                    writeJsonFile($buxFile, $buxData); // Save changes to JSON file
                    echo "Transfer successful! $amount DevBux has been transferred to $recipient.";
                } else {
                    echo "Insufficient balance to make the transfer.";
                }
            } else {
                echo "User or recipient not found in the system.";
            }
        } else {
            echo "Error reading bux.json file.";
        }
    } else {
        echo "Incomplete request parameters.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer DevBux</title>
</head>
<body>
    <h2>Transfer DevBux</h2>
    <form method="post" action="">
        <label for="recipient">Recipient:</label><br>
        <input type="text" id="recipient" name="recipient" required><br>
        <label for="amount">Amount:</label><br>
        <input type="number" id="amount" name="amount" step="0.1" min="0.1" required><br>
        <button type="submit">Transfer</button>
    </form>
</body>
</html>
