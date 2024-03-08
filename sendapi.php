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

// Function to authenticate user with password
function authenticateUser($username, $password) {
    // Read users.txt.php to authenticate user
    $usersFile = 'users.txt.php';
    $usersData = readUsersFile($usersFile);

    // Check if the user exists in the users file and the password matches
    if (isset($usersData[$username]) && $usersData[$username] === $password) {
        return true;
    } else {
        return false;
    }
}

// Function to read users file and decode it
function readUsersFile($filename) {
    $data = file_get_contents($filename);
    $lines = explode("\n", $data);
    $users = array();

    foreach ($lines as $line) {
        $parts = explode(",", $line);
        if (count($parts) == 2) {
            $username = trim($parts[0]);
            $password = trim($parts[1]);
            $users[$username] = $password;
        }
    }

    return $users;
}

// Check if the request method is GET and the required parameters are set in the URL
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['username']) && isset($_GET['password']) && isset($_GET['sendto']) && isset($_GET['amount'])) {
    $username = $_GET['username'];
    $password = $_GET['password'];
    $sendto = $_GET['sendto'];
    $amount = floatval($_GET['amount']);

    // Authenticate user with username and password
    if (authenticateUser($username, $password)) {
        // Load bux.json file
        $buxFile = 'bux.json';
        $buxData = readJsonFile($buxFile);

        // Check if the file exists and is readable
        if ($buxData !== null) {
            // Check if the user and recipient exist in the bux.json file
            if (isset($buxData[$username]) && isset($buxData[$sendto])) {
                // Check if the user has sufficient balance to transfer
                if ($buxData[$username]['balance'] >= $amount) {
                    // Deduct amount from the user's balance and add it to the recipient's balance
                    $buxData[$username]['balance'] -= $amount;
                    $buxData[$sendto]['balance'] += $amount;
                    writeJsonFile($buxFile, $buxData); // Save changes to JSON file
                    echo json_encode(array('message' => 'Transfer successful!', 'amount' => $amount));
                } else {
                    echo json_encode(array('error' => 'Insufficient balance to make the transfer.'));
                }
            } else {
                echo json_encode(array('error' => 'User or recipient not found in the system.'));
            }
        } else {
            echo json_encode(array('error' => 'Error reading bux.json file.'));
        }
    } else {
        echo json_encode(array('error' => 'Authentication failed. Incorrect username or password.'));
    }
} else {
    echo json_encode(array('error' => 'Invalid request.'));
}

?>
