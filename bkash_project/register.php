<?php
include 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $nid = $_POST['nid']; // Added NID
    $dob = $_POST['dob']; // Added Date of Birth
    $address = $_POST['address']; // Added Address
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, phone, nid, dob, address, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $name, $email, $phone, $nid, $dob, $address, $password);
    
    if ($stmt->execute()) {
        echo "Registration successful! <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<form method="post">
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="text" name="phone" placeholder="Phone Number" required><br>
    <input type="text" name="nid" placeholder="NID Number" required><br> <!-- NID Field -->
    <input type="date" name="dob" required><br> <!-- Date of Birth Field -->
    <textarea name="address" placeholder="Enter your address" required></textarea><br> <!-- Address Field -->
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Register</button>
</form> 
