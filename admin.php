<?php
require 'C:/xampp/htdocs/PHPCODE/db_connection.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_type = $_POST['date_type'];
    $date = $_POST['date'];
    
    if ($date_type == "request_date") {
        $stmt = $conn->prepare("SELECT customer_number, request_number, item_description, weight, pickup_suburb, pickup_date, delivery_suburb, delivery_state FROM requests WHERE request_date = ?");
        $stmt->bind_param("s", $date);
    } elseif ($date_type == "pickup_date") {
        $stmt = $conn->prepare("SELECT r.customer_number, c.name, c.phone_number, r.request_number, r.item_description, r.weight, r.pickup_address, r.pickup_suburb, r.pickup_date, r.delivery_suburb, r.delivery_state FROM requests r JOIN customers c ON r.customer_number = c.customer_number WHERE r.pickup_date LIKE ? ORDER BY r.pickup_suburb, r.delivery_state, r.delivery_suburb");
        $stmt->bind_param("s", "$date%");
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $requests = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Admin - View Requests</h1>
    <form method="POST">
        <label>
            <input type="radio" name="date_type" value="request_date" required> Request Date
        </label>
        <label>
            <input type="radio" name="date_type" value="pickup_date" required> Pick-up Date
        </label>
        <input type="date" name="date" required><br>
        <input type="submit" value="Show Requests">
    </form>

    <?php if (isset($requests) && !empty($requests)): ?>
        <table>
            <tr>
                <?php if ($date_type == "request_date"): ?>
                    <th>Customer Number</th>
                    <th>Request Number</th>
                    <th>Item Description</th>
                    <th>Weight</th>
                    <th>Pick-up Suburb</th>
                    <th>Pick-up Date</th>
                    <th>Delivery Suburb</th>
                    <th>Delivery State</th>
                <?php else: ?>
                    <th>Customer Number</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Request Number</th>
                    <th>Item Description</th>
                    <th>Weight</th>
                    <th>Pick-up Address</th>
                    <th>Pick-up Suburb</th>
                    <th>Pick-up Date</th>
                    <th>Delivery Suburb</th>
                    <th>Delivery State</th>
                <?php endif; ?>
            </tr>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <?php foreach ($request as $field): ?>
                        <td><?php echo htmlspecialchars($field); ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
