<?php
require 'C:/xampp/htdocs/PHPCODE/db_connection.php';


// $customer_number = $_GET['customer_number'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch form inputs
    $item_description = $_POST['item_description'];
    $weight = $_POST['weight'];
    $pickup_address = $_POST['pickup_address'];
    $pickup_suburb = $_POST['pickup_suburb'];
    $pickup_date = $_POST['pickup_date'];
    $receiver_name = $_POST['receiver_name'];
    $delivery_address = $_POST['delivery_address'];
    $delivery_suburb = $_POST['delivery_suburb'];
    $delivery_state = $_POST['delivery_state'];

    // Calculate cost
    $cost = 20 + (($weight - 2) * 3);

    // Insert request into database
    $stmt = $conn->prepare("INSERT INTO requests (customer_number, request_date, item_description, weight, pickup_address, pickup_suburb, pickup_date, receiver_name, delivery_address, delivery_suburb, delivery_state) VALUES (?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isissssss", $customer_number, $item_description, $weight, $pickup_address, $pickup_suburb, $pickup_date, $receiver_name, $delivery_address, $delivery_suburb, $delivery_state);

    if ($stmt->execute()) {
        $request_number = $conn->insert_id;
        $success = "Thank you! Your request number is $request_number. The cost is $$cost. We will pick up the item at $pickup_date.";
        
        // Send confirmation email
        $customer_result = $conn->query("SELECT name, email FROM customers WHERE customer_number = $customer_number");
        $customer_info = $customer_result->fetch_assoc();
        $to = $customer_info['email'];
        $subject = "Shipping Request with ShipOnline";
        $message = "Dear " . $customer_info['name'] . ",\nThank you for using ShipOnline! Your request number is $request_number. The cost is $$cost. We will pick up the item at $pickup_date.";
        $headers = "From: noreply@shiponline.com";
        mail($to, $subject, $message, $headers, "-r 1234567@student.swin.edu.au");
    } else {
        $error = "Request failed. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Shipping</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Make a Shipping Request</h1>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
    <form method="POST">
        <label for="item_description">Item Description:</label>
        <input type="text" id="item_description" name="item_description" required><br>
        <label for="weight">Weight (kg):</label>
        <input type="number" id="weight" name="weight" min="2" max="20" required><br>
        <label for="pickup_address">Pick-up Address:</label>
        <input type="text" id="pickup_address" name="pickup_address" required><br>
        <label for="pickup_suburb">Pick-up Suburb:</label>
        <input type="text" id="pickup_suburb" name="pickup_suburb" required><br>
        <label for="pickup_date">Pick-up Date & Time:</label>
        <input type="datetime-local" id="pickup_date" name="pickup_date" required><br>
        <label for="receiver_name">Receiver Name:</label>
        <input type="text" id="receiver_name" name="receiver_name" required><br>
        <label for="delivery_address">Delivery Address:</label>
        <input type="text" id="delivery_address" name="delivery_address" required><br>
        <label for="delivery_suburb">Delivery Suburb:</label>
        <input type="text" id="delivery_suburb" name="delivery_suburb" required><br>
        <label for="delivery_state">Delivery State:</label>
        <select id="delivery_state" name="delivery_state">
            <option value="VIC">VIC</option>
            <option value="NSW">NSW</option>
            <option value="QLD">QLD</option>
            <option value="SA">SA</option>
            <option value="WA">WA</option>
            <option value="TAS">TAS</option>
            <option value="ACT">ACT</option>
            <option value="NT">NT</option>
        </select><br>
        <input type="submit" value="Request">
    </form>
</body>
</html>
