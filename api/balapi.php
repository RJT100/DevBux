<?php

// Function to read JSON file and decode it
function readJsonFile($filename) {
    $data = file_get_contents($filename);
    return json_decode($data, true);
}

// Check if username is set in the request
if(isset($_GET['username'])) {
    $username = $_GET['username'];

    // Load bux.json file
    $buxFile = 'bux.json';
    $buxData = readJsonFile($buxFile);

    // Check if the file exists and is readable
    if ($buxData !== null) {
        // Check if the user exists in the bux.json file
        if (isset($buxData[$username])) {
            $balance = $buxData[$username]['balance'];
            echo $balance;
        } else {
            echo '404 user not found';
        }
    } else {
        echo '500 please try later';
    }
} else {
    echo 'Username parameter is not set';
}

?>
