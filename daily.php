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

// Check if the username cookie is set
if(isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];

    // Load bux.json file
    $buxFile = 'bux.json';
    $buxData = readJsonFile($buxFile);

    // Check if the file exists and is readable
    if ($buxData !== null) {
        // Check if the user exists in the bux.json file
        if (isset($buxData[$username])) {
            // Check last claimed timestamp
            $lastClaimed = isset($buxData[$username]['last_claimed']) ? $buxData[$username]['last_claimed'] : 0;
            $currentTimestamp = time();
            $oneDay = 24 * 60 * 60; // One day in seconds

            if ($currentTimestamp - $lastClaimed >= $oneDay) {
                // User can claim a DevBux
                $buxData[$username]['balance'] += 1; // Increment balance by 1
                $buxData[$username]['last_claimed'] = $currentTimestamp; // Update last claimed timestamp
                writeJsonFile($buxFile, $buxData); // Save changes to JSON file
                echo "You've successfully claimed 1 DevBux!";
            } else {
                // User already claimed a DevBux within the last 24 hours
                echo "You can only claim 1 DevBux per day. Please try again later.";
            }
        } else {
            // User not found in the system, add them with initial data
            $buxData[$username] = array('balance' => 0, 'last_claimed' => 0);
            writeJsonFile($buxFile, $buxData);
            echo "User added to the system with an initial balance of 0 DevBux.";
        }
    } else {
        // Error reading bux.json file
        echo "Error reading bux.json file.";
    }
} else {
    // Username cookie is not set
    echo "Username cookie is not set.";
}

?>
