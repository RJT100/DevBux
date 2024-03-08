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

// Check if the username query parameter is set
if(isset($_GET['username'])) {
    $username = $_GET['username'];

    // Load bux.json file
    $buxFile = 'bux.json';
    $buxData = readJsonFile($buxFile);

    // Check if the file exists and is readable
    if ($buxData !== null) {
        // Check if the user exists in the bux.json file
        if (isset($buxData[$username])) {
            // Add 0.1 DevBux to the user's balance
            $buxData[$username]['balance'] += 0.1;
            writeJsonFile($buxFile, $buxData); // Save changes to JSON file
            echo "yes";
        } else {
            // User not found in the system
            echo "404";
        }
    } else {
        // Error reading bux.json file
        echo "403";
    }
} else {
    // Username query parameter is not set
    echo "401";
}

?>
