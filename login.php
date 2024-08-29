<?php
require 'C:/xampp/htdocs/PHPCODE/db_connection.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_number = $_POST['customer_number'];
    $password = $_POST['password'];

    // Check if customer exists
    $stmt = $conn->prepare("SELECT * FROM customers WHERE customer_number = ?");
    $stmt->bind_param("i", $customer_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();

    if ($customer && password_verify($password, $customer['password'])) {
        // Redirect to request page
        header("Location: request.php?customer_number=$customer_number");
        exit();
    } else {
        $error = "Invalid customer number or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Login to ShipOnline</h1>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <label for="customer_number">Customer Number:</label>
        <input type="text" id="customer_number" name="customer_number" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
