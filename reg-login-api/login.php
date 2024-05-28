<?php
session_start(); // Start session

require_once 'config.php';

header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    $email = $input['email'];
    $password = $input['password'];

    $conn = db_connect();

    $sql = "SELECT * FROM user WHERE email = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                if ($password == $row['password']) {
                    // Set session variables
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    echo json_encode(["status" => "success", "message" => "Login successful."]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Incorrect password."]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "User not found."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Could not execute statement."]);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(["status" => "error", "message" => "Could not prepare statement."]);
    }
    db_close($conn);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
