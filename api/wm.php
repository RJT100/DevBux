<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counter</title>
</head>
<body>
    <h1>Counter:</h1>
    <div id="counter">0</div>

    <script>
        // Function to retrieve cookie value by name
        function getCookie(name) {
            const cookies = document.cookie.split(';');
            for (let i = 0; i < cookies.length; i++) {
                const cookie = cookies[i].trim();
                if (cookie.startsWith(name + '=')) {
                    return cookie.substring(name.length + 1);
                }
            }
            return null;
        }

        // Get the username from the cookie
        const username = getCookie('username');

        // Check if username is set
        if (username !== null) {
            let counter = 0; // Initialize counter

            // Function to send HTTP request every 20 milliseconds
            setInterval(() => {
                // Send HTTP request to /miner.php
                fetch(`/miner.php?username=${username}`)
                    .then(response => response.text())
                    .then(data => {
                        // Check if response is true
                        if (data.trim() === 'yes') {
                            counter++; // Increment counter
                            document.getElementById('counter').innerText = counter; // Update counter on screen
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }, 20);
        } else {
            console.log("Username cookie not set.");
        }
    </script>


</body>
</html>
