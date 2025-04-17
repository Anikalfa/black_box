<?php
session_start();
include 'config/db.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check if email exists and the role is admin
    $sql = "SELECT id, name, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password, $role);
        $stmt->fetch();

        // Verify password and check if the user is an admin
        if (password_verify($password, $hashed_password) && $role === 'admin') {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['role'] = 'admin'; // Set admin role in session
            header("Location: admin_dashboard.php"); // Redirect to admin dashboard
            exit();
        } else {
            echo "<p style='color:red;'>Invalid password or not an admin!</p>";
        }
    } else {
        echo "<p style='color:red;'>Admin not found! Please register first.</p>";
    }

    $stmt->close();
}
?>

<!-- Admin Login Form -->
<form method="post">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>
