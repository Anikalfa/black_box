<?php
session_start();
include 'config/db.php'; // Ensure the path is correct

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to view this page.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$sql = "SELECT name, balance FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_name = htmlspecialchars($row['name']);
    $balance = number_format($row['balance'], 2);
} else {
    echo "Error: User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Add your CSS file -->
</head>
<body>

<h2>Welcome, <?php echo $user_name; ?>!</h2>
<p>Your current balance is: ৳<?php echo $balance; ?></p>

<!-- Dashboard Options -->
<div class="dashboard-options">
    <a href="send_money.php" class="btn">Send Money</a>
    <a href="transaction_history.php" class="btn">Transaction History</a>
    <a href="add_money_button.php" class="btn">Add Money</a>
    <a href="mobile_recharge.php" class="btn">Mobile Recharge</a>
    <a href="payment.php" class="btn">Payment</a>
    <a href="pay_bill.php" class="btn">Pay Bill</a>
    <a href="bkash_to_bank.php" class="btn">bKash to Bank Transfer</a> <!-- New Button -->
    <a href="cash_out.php" class="btn">Cash Out</a> <!-- Add this button -->
    <a href="logout.php" class="btn">Logout</a>
</div>

</body>
</html>
