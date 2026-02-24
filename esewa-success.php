<?php
session_start();
include('includes/config.php');

// In eSewa V2, success redirect includes a base64 encoded 'data' parameter
$data = $_GET['data'] ?? '';

if (empty($data)) {
    die("Invalid request from eSewa. No data received.");
}

// Decode the data
$decoded_data = base64_decode($data);
$result = json_decode($decoded_data, true);

if (!$result) {
    die("Failed to decode eSewa response.");
}

/*
Example decoded result:
{
  "status": "COMPLETE",
  "signature": "...",
  "transaction_code": "...",
  "total_amount": "...",
  "transaction_uuid": "...",
  "product_code": "...",
  "success_url": "...",
  "signed_field_names": "..."
}
*/

$transaction_uuid = $result['transaction_uuid'] ?? '';
$status = $result['status'] ?? '';
$total_amount = $result['total_amount'] ?? '';
$transaction_code = $result['transaction_code'] ?? '';

if ($status === "COMPLETE") {
    // Payment Verified Successfully (You can also verify the signature here for extra security)
    
    // Update the orders table
    $updateQuery = "UPDATE orders SET paymentStatus = 'Completed', referenceCode = '$transaction_code' WHERE transactionId = '$transaction_uuid'";
    
    if (mysqli_query($con, $updateQuery)) {
        unset($_SESSION['cart']);
        
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Payment Successful</title>
            <style>
                body { font-family: 'Segoe UI', sans-serif; text-align: center; padding: 50px; background: #f9f9f9; }
                .card { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); display: inline-block; }
                h1 { color: #41a124; }
                .btn { display: inline-block; padding: 10px 20px; background: #41a124; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class='card'>
                <h1>✔ Payment Successful!</h1>
                <p>Thank you for your purchase. Your payment of Rs. $total_amount has been completed.</p>
                <p>Order ID: $transaction_uuid</p>
                <p>eSewa Transaction Code: $transaction_code</p>
                <a href='order-history.php' class='btn'>View Order History</a>
            </div>
        </body>
        </html>";
    } else {
        echo "Error updating order: " . mysqli_error($con);
    }
} else {
    // Status is not COMPLETE
    echo "<h1>Payment Verification Failed</h1>";
    echo "<p>Status: " . htmlentities($status) . "</p>";
    echo "<a href='index.php'>Back to Home</a>";
}
?>
