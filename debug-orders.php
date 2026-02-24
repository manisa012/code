<?php
session_start();
include('includes/config.php');

echo "<h2>Debug Order Information</h2>";

if(strlen($_SESSION['login'])==0) {
    echo "<p>User not logged in</p>";
    exit();
}

$user_id = $_SESSION['id'];
echo "<p><strong>User ID:</strong> $user_id</p>";

// Check all orders for this user
$query = mysqli_query($con, "SELECT * FROM orders WHERE userId='$user_id' ORDER BY id DESC LIMIT 5");
echo "<h3>All Orders for User:</h3>";
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>ID</th><th>Product ID</th><th>Quantity</th><th>Product Price</th><th>Shipping Charge</th><th>Payment Method</th><th>Payment Status</th></tr>";

while($row = mysqli_fetch_array($query)) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['productId'] . "</td>";
    echo "<td>" . $row['quantity'] . "</td>";
    echo "<td>" . $row['productPrice'] . "</td>";
    echo "<td>" . $row['shippingCharge'] . "</td>";
    echo "<td>" . ($row['paymentMethod'] ?: 'NULL') . "</td>";
    echo "<td>" . ($row['paymentStatus'] ?: 'NULL') . "</td>";
    echo "</tr>";
}
echo "</table>";

// Check pending orders
echo "<h3>Pending Orders (paymentMethod IS NULL):</h3>";
$pending_query = mysqli_query($con, "SELECT orders.*, products.productName, products.productPrice FROM orders 
                                   JOIN products ON orders.productId = products.id 
                                   WHERE orders.userId='$user_id' AND orders.paymentMethod IS NULL 
                                   ORDER BY orders.id DESC");

if(mysqli_num_rows($pending_query) > 0) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Order ID</th><th>Product Name</th><th>Quantity</th><th>Product Price</th><th>Shipping Charge</th><th>Total Amount</th></tr>";
    
    while($row = mysqli_fetch_array($pending_query)) {
        $amount = $row['quantity'] * $row['productPrice'] + $row['shippingCharge'];
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlentities($row['productName']) . "</td>";
        echo "<td>" . $row['quantity'] . "</td>";
        echo "<td>NPR " . number_format($row['productPrice'], 2) . "</td>";
        echo "<td>NPR " . number_format($row['shippingCharge'], 2) . "</td>";
        echo "<td>NPR " . number_format($amount, 2) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No pending orders found.</p>";
}

// Check if paymentStatus and transactionId columns exist
echo "<h3>Database Schema Check:</h3>";
$columns_query = mysqli_query($con, "SHOW COLUMNS FROM orders");
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Column Name</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";

while($row = mysqli_fetch_array($columns_query)) {
    echo "<tr>";
    echo "<td>" . $row['Field'] . "</td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . $row['Key'] . "</td>";
    echo "<td>" . ($row['Default'] ?: 'NULL') . "</td>";
    echo "</tr>";
}
echo "</table>";
?>
