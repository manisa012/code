<?php
include('includes/config.php');

// Add paymentStatus column if it doesn't exist
$check_payment_status = mysqli_query($con, "SHOW COLUMNS FROM orders LIKE 'paymentStatus'");
if(mysqli_num_rows($check_payment_status) == 0) {
    mysqli_query($con, "ALTER TABLE orders ADD COLUMN paymentStatus VARCHAR(50) DEFAULT 'Pending'");
    echo "Added paymentStatus column to orders table<br>";
} else {
    echo "paymentStatus column already exists<br>";
}

// Add transactionId column if it doesn't exist
$check_transaction_id = mysqli_query($con, "SHOW COLUMNS FROM orders LIKE 'transactionId'");
if(mysqli_num_rows($check_transaction_id) == 0) {
    mysqli_query($con, "ALTER TABLE orders ADD COLUMN transactionId VARCHAR(100) DEFAULT NULL");
    echo "Added transactionId column to orders table<br>";
} else {
    echo "transactionId column already exists<br>";
}

// Add paymentToken column for token-based API
$check_payment_token = mysqli_query($con, "SHOW COLUMNS FROM orders LIKE 'paymentToken'");
if(mysqli_num_rows($check_payment_token) == 0) {
    mysqli_query($con, "ALTER TABLE orders ADD COLUMN paymentToken VARCHAR(255) DEFAULT NULL");
    echo "Added paymentToken column to orders table<br>";
} else {
    echo "paymentToken column already exists<br>";
}

// Add referenceCode column for token-based API
$check_reference_code = mysqli_query($con, "SHOW COLUMNS FROM orders LIKE 'referenceCode'");
if(mysqli_num_rows($check_reference_code) == 0) {
    mysqli_query($con, "ALTER TABLE orders ADD COLUMN referenceCode VARCHAR(100) DEFAULT NULL");
    echo "Added referenceCode column to orders table<br>";
} else {
    echo "referenceCode column already exists<br>";
}

echo "<br>Database update completed successfully!";
?>
