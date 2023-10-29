<?php
session_start();

if (isset($_POST['register'])) {
    $username = filter_input(INPUT_POST, 'username', FILTER_UNSAFE_RAW);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if ($username && $email && $password) {
        // Create or open the users.txt file for appending
        $file = fopen("data/users.txt", "a");

        if ($file) {
            // Write the user's registration data to the file
            fwrite($file, "$username,$email,$password" . PHP_EOL);

            // Close the file
            fclose($file);

            // Redirect to the login page or any other page you want
            header('location:auth.php');
        } else {
            // Handle the error, e.g., display an error message
            echo "Error: Unable to open the user data file.";
        }
    } else {
        // Handle validation errors, e.g., display an error message
        echo "Error: Invalid input data.";
    }
}
?>
