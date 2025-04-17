<?php
include 'config/db.php'; // Ensure the path is correct

// Insert admin details (use password_hash for secure storage)
$name = 'Admin Name';
$email = 'admin@example.com';
$phone = '1234567890';
$nid = '123456789';
$dob = '1980-01-01';
$address = 'Admin Address';
$password = password_hash('admin123', PASSWORD_DEFAULT); // Use your desired password

// Insert the admin user
$sql = "INSERT INTO users (name, email, phone, nid, dob, address, password, role) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'admin')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $name, $email, $phone, $nid, $dob, $address, $password);

if ($stmt->execute()) {
    echo "Admin account created successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
?>

