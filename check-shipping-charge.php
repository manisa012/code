<?php
session_start();
include('includes/config.php');

echo "<h2>Database Field Check</h2>";

// Check if shippingCharge field exists in products table
echo "<h3>1. Checking Products Table</h3>";
$result = mysqli_query($con, "DESCRIBE products");
if ($result) {
    $fields = array();
    while ($row = mysqli_fetch_array($result)) {
        $fields[] = $row['Field'];
    }
    
    if (in_array('shippingCharge', $fields)) {
        echo "<p style='color: green;'>✅ shippingCharge field exists in products table</p>";
    } else {
        echo "<p style='color: red;'>❌ shippingCharge field does NOT exist in products table</p>";
        echo "<p>Available fields: " . implode(', ', $fields) . "</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Error checking products table: " . mysqli_error($con) . "</p>";
}

// Check if shippingCharge field exists in orders table
echo "<h3>2. Checking Orders Table</h3>";
$result = mysqli_query($con, "DESCRIBE orders");
if ($result) {
    $fields = array();
    while ($row = mysqli_fetch_array($result)) {
        $fields[] = $row['Field'];
    }
    
    if (in_array('shippingCharge', $fields)) {
        echo "<p style='color: green;'>✅ shippingCharge field exists in orders table</p>";
    } else {
        echo "<p style='color: red;'>❌ shippingCharge field does NOT exist in orders table</p>";
        echo "<p>Available fields: " . implode(', ', $fields) . "</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Error checking orders table: " . mysqli_error($con) . "</p>";
}

// Check sample data
echo "<h3>3. Sample Data Check</h3>";
$result = mysqli_query($con, "SELECT id, productName, shippingCharge FROM products LIMIT 5");
if ($result) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Product Name</th><th>Shipping Charge</th></tr>";
    while ($row = mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlentities($row['productName']) . "</td>";
        echo "<td>" . ($row['shippingCharge'] ?? 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>❌ Error fetching sample data: " . mysqli_error($con) . "</p>";
}

// Check orders with shipping charge
echo "<h3>4. Orders with Shipping Charge</h3>";
$result = mysqli_query($con, "SELECT orders.id, products.productName, orders.quantity, products.productPrice, products.shippingCharge 
                              FROM orders 
                              JOIN products ON orders.productId = products.id 
                              LIMIT 5");
if ($result) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Order ID</th><th>Product</th><th>Quantity</th><th>Price</th><th>Shipping</th><th>Total</th></tr>";
    while ($row = mysqli_fetch_array($result)) {
        $shipping = $row['shippingCharge'] ?? 0;
        $total = ($row['quantity'] * $row['productPrice']) + $shipping;
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlentities($row['productName']) . "</td>";
        echo "<td>" . $row['quantity'] . "</td>";
        echo "<td>NPR " . number_format($row['productPrice'], 2) . "</td>";
        echo "<td>NPR " . number_format($shipping, 2) . "</td>";
        echo "<td>NPR " . number_format($total, 2) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>❌ Error fetching orders: " . mysqli_error($con) . "</p>";
}

echo "<h3>5. Recommendations</h3>";
echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
echo "<p>If shippingCharge field is missing:</p>";
echo "<ol>";
echo "<li>Add the field to the products table: <code>ALTER TABLE products ADD COLUMN shippingCharge DECIMAL(10,2) DEFAULT 0.00;</code></li>";
echo "<li>Update existing products with shipping charges</li>";
echo "<li>Test the payment flow again</li>";
echo "</ol>";
echo "</div>";
?>
