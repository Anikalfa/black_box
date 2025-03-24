<?php
session_start();
include 'config/db.php'; // Ensure this file correctly connects to your database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']); // Trim spaces
    $password = $_POST['password'];

    // Check if email exists in the database
    $sql = "SELECT id, name, password, balance FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Query failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // If user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password, $balance);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Store user info in session
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['balance'] = $balance; // ✅ Store balance

            header("Location: dashboard.php");
            exit();
        } else {
            echo "<p style='color:red;'>Invalid password!</p>";
        }
    } else {
        echo "<p style='color:red;'>Email not found!</p>";
    }

    $stmt->close();
}
?>

<!-- Simple Login Form -->
<form method="post">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>
