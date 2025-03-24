<?php
session_start();
include 'config/db.php'; // Ensure the path is correct

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to transfer money.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user balance from the database
$sql = "SELECT balance FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists and has a balance
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $balance = $row['balance'];
} else {
    echo "User not found.";
    exit();
}

// Handle transfer request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transfer_amount = $_POST['amount'];

    // Check if the transfer amount is valid
    if ($transfer_amount > 0 && $transfer_amount <= $balance) {
        // Update user balance (deduct from bKash)
        $new_balance = $balance - $transfer_amount;
        $sql = "UPDATE users SET balance = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $new_balance, $user_id);

        if ($stmt->execute()) {
            // Now insert the transaction into the transactions table
            // Set the bank's or merchant's ID as the receiver_id (you should fetch this from your merchants table)
            $receiver_id = 1;  // Example: Replace with actual bank or merchant ID (e.g., from merchants table)
            $transaction_type = 'add_money';  // Set the type of transaction
            $description = 'Transferred from bKash to Bank';  // Transaction description

            // Insert into transactions table
            $transaction_sql = "INSERT INTO transactions (sender_id, receiver_id, amount, type, description) 
                                VALUES (?, ?, ?, ?, ?)";
            $transaction_stmt = $conn->prepare($transaction_sql);
            $transaction_stmt->bind_param("iiiss", $user_id, $receiver_id, $transfer_amount, $transaction_type, $description);

            if ($transaction_stmt->execute()) {
                echo "Transfer successful. Your new balance is: ৳" . number_format($new_balance, 2);
            } else {
                echo "Error inserting transaction record: " . $transaction_stmt->error;
            }
        } else {
            echo "Error updating balance.";
        }
    } else {
        echo "Insufficient balance or invalid amount.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bKash to Bank Transfer</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Add your CSS file -->
</head>
<body>

<h2>Transfer from bKash to Bank</h2>

<form method="POST" action="">
<label for="amount">Amount to transfer (in ৳):</label>
    <input type="number" id="amount" name="amount" min="1" max="<?php echo $balance; ?>" required><br><br>

    <label for="bank_name">Select Bank:</label>
    <select id="bank_name" name="bank_name" required>
        <option value="DBBL">Dutch-Bangla Bank Ltd (DBBL)</option>
        <option value="BRAC">BRAC Bank</option>
        <option value="City">City Bank</option>
        <option value="IFIC">IFIC Bank</option>
    </select><br><br>

    <label for="bank_account">Bank Account Number:</label>
    <input type="text" id="bank_account" name="bank_account" required><br><br>
    <label for="pin">Enter Your bKash PIN:</label>
    <input type="password" id="pin" name="pin" required><br><br>
    <button type="submit">Transfer</button>
</form>

<a href="dashboard.php">Back to Dashboard</a>

</body>
</html>
