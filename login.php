<?php
session_start();
include 'db_connection.php'; // Ensure this file correctly connects to MySQL

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        die("Error: Please enter both username and password.");
    }

    // Fetch user details
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password']; 

        echo "DEBUG: Stored Hashed Password → " . $hashed_password . "<br>";
        echo "DEBUG: Entered Plain Password → " . $password . "<br>";

        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $row['username'];
            echo "<script>alert('Login Successful! Redirecting...'); window.location.href='suppliers.html';</script>";
            exit();
        } else {
            die("Error: Password did not match!");
        }
    } else {
        die("Error: Username not found!");
    }

    $stmt->close();
    $conn->close();
}
?>
