<?php
require_once 'config.php';

header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Function to validate user input
function validate_input($data)
{
    $data = trim($data);             // Remove whitespace from the beginning and end
    $data = stripslashes($data);     // Remove backslashes
    $data = htmlspecialchars($data); // Convert special characters to HTML entities
    return $data;                    // Return the sanitized data
}

// Function to check if email is already registered
function is_email_registered($email)
{
    $conn = db_connect();
    $sql = "SELECT id FROM user WHERE email = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $param_email);
        $param_email = $email;

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                return true; // Email is already registered
            } else {
                return false; // Email is not registered
            }
        }

        mysqli_stmt_close($stmt);
    }

    db_close($conn);
    return false;
}

// Function to register a new user
function register_user($username, $email, $password)
{
    $conn = db_connect();

    // Prepare an insert statement
    $sql = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);

        // Set parameters
        $param_username = $username;
        $param_email = $email;
        $param_password = $password; // Creates a password hash

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(["status" => "success", "message" => "Registration successful."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Something went wrong. Please try again later."]);
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(["status" => "error", "message" => "Could not prepare statement."]);
    }

    // Close connection
    db_close($conn);
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get JSON input
    $input = json_decode(file_get_contents("php://input"), true);

    // Validate input
    if (isset($input['username']) && isset($input['email']) && isset($input['password']) && isset($input['confirm_password'])) {
        $username = validate_input($input['username']);
        $email = validate_input($input['email']);
        $password = validate_input($input['password']);
        $confirm_password = validate_input($input['confirm_password']);

        // Check if passwords match
        if ($password !== $confirm_password) {
            echo json_encode(["status" => "error", "message" => "Passwords do not match."]);
            exit;
        }

        // Check if email is already registered
        if (is_email_registered($email)) {
            echo json_encode(["status" => "error", "message" => "Email is already registered."]);
            exit;
        }

        // Register user
        register_user($username, $email, $password);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid input."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
