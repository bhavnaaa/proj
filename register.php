<?php
require 'C:/xampp/htdocs/PHPCODE/db_connection.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // Check all inputs are given
    if (empty($name) || empty($password) || empty($repassword) || empty($email) || empty($phone_number)) {
        $error = "All fields are required.";
    } elseif ($password !== $repassword) {
        $error = "Passwords do not match.";
    } else {
        // Check if email is unique
        $stmt = $conn->prepare("SELECT * FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // Insert the new customer
            $stmt = $conn->prepare("INSERT INTO customers (name, password, email, phone_number) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $hashed_password, $email, $phone_number);
            if ($stmt->execute()) {
                $customer_number = $conn->insert_id;
                $success = "Dear $name, you are successfully registered into ShipOnline, and your customer number is $customer_number.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Register for ShipOnline</h1>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <label for="repassword">Re-type Password:</label>
        <input type="password" id="repassword" name="repassword" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" required><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>
