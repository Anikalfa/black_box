<?php
session_start();
include 'config/db.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "You must be logged in as an admin to access this page.";
    exit();
}

// Fetch all feedback
$sql = "SELECT f.id, f.message, f.created_at, u.name as user_name 
        FROM feedback f
        JOIN users u ON f.user_id = u.id
        ORDER BY f.created_at DESC"; // Order feedback by submission date (latest first)
$result = $conn->query($sql);

if ($result === false) {
    die("Error fetching feedback: " . $conn->error);
}

// Process the result
$feedback = [];
while ($row = $result->fetch_assoc()) {
    $feedback[] = $row;
}

// Ensure result set is closed
$result->free();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View User Feedback</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Add your CSS file -->
</head>
<body>

<h1>Welcome to Admin Dashboard</h1>

<h2>All User Feedback:</h2>
<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>User Name</th>
            <th>Feedback Message</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Loop through the feedback array and display feedback details
        foreach ($feedback as $fb) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($fb['user_name']) . "</td>";
            echo "<td>" . nl2br(htmlspecialchars($fb['message'])) . "</td>"; // nl2br() to handle line breaks in feedback
            echo "<td>" . htmlspecialchars($fb['created_at']) . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<a href="admin_dashboard.php">Back to Admin Dashboard</a>
<a href="logout.php">Logout</a>

</body>
</html>
