<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['admin_id'])) {
    echo "Access denied.";
    exit();
}

$sql = "SELECT f.id, u.name, f.message, f.created_at 
        FROM feedback f
        JOIN users u ON f.user_id = u.id
        ORDER BY f.created_at DESC";
$result = $conn->query($sql);
?>

<h2>User Feedback</h2>

<?php while ($row = $result->fetch_assoc()): ?>
    <div style="border:1px solid #ccc; padding:10px; margin:10px;">
        <strong>User:</strong> <?php echo htmlspecialchars($row['name']); ?><br>
        <strong>Submitted At:</strong> <?php echo $row['created_at']; ?><br>
        <strong>Message:</strong> <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
    </div>
<?php endwhile; ?>

<a href="admin_dashboard.php">Back to Admin Dashboard</a>
