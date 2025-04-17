<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to submit feedback.";
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $sql = "INSERT INTO feedback (user_id, message) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $message);

        if ($stmt->execute()) {
            echo "<p style='color:green;'>Feedback submitted successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error submitting feedback.</p>";
        }
    } else {
        echo "<p style='color:red;'>Please write your feedback.</p>";
    }
}
?>

<h2>Submit Feedback / Complaint</h2>
<form method="POST">
    <textarea name="message" rows="5" cols="40" placeholder="Enter your feedback here..." required></textarea><br><br>
    <button type="submit">Submit</button>
</form>
<a href="dashboard.php">Back to Dashboard</a>
