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
            $balance = $buxData[$username]['balance'];
            echo '<link rel="stylesheet" href="style.css">';
            echo "Your DevBux balance: $balance";
            echo '<br><a href="wm.php">webminer buggy</a>';
            echo '<br><a href="send.php">send</a>';
            echo '<br><a href="daily.php">claim daily</a>';
            
          
        } else {
            // Add the user to the JSON file with an initial balance of 0
            $buxData[$username] = array('balance' => 0);
            writeJsonFile($buxFile, $buxData);
            echo 'You have been added to the system with an initial balance of 0 DevBux.<br><a href="dashboard.php">go to dashboard</a>';
        
        }
    } else {
        echo "Error reading bux.json file.";
    }
} else {
    echo "Username cookie is not set.";
}
?>
