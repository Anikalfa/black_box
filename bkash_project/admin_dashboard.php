<?php
session_start();
include 'config/db.php'; // Ensure this is correct

// Check if the user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "You must be logged in as an admin to access this page.";
    exit();
}

// Fetch all users information for the admin dashboard
$sql = "SELECT id, name, email, phone, nid, dob, address, balance FROM users";
$result = $conn->query($sql);

if ($result === false) {
    die("Error fetching users: " . $conn->error);
}

// Process the result
$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Ensure result set is closed
$result->free();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Add your CSS file -->
</head>
<body>

<h1>Welcome to Admin Dashboard</h1>

<!-- Add Link to View User Feedback -->
<h2><a href="admin_view_feedback.php">View User Feedback</a></h2>

<h2>All Users:</h2>
<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>NID</th>
            <th>Date of Birth</th>
            <th>Address</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Loop through the users array and display user details
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($user['id']) . "</td>";
            echo "<td>" . htmlspecialchars($user['name']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>" . htmlspecialchars($user['phone']) . "</td>";
            echo "<td>" . htmlspecialchars($user['nid']) . "</td>";
            echo "<td>" . htmlspecialchars($user['dob']) . "</td>";
            echo "<td>" . htmlspecialchars($user['address']) . "</td>";
            echo "<td>৳" . number_format($user['balance'], 2) . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<!-- Logout link -->
<a href="logout.php">Logout</a>

</body>
</html>
