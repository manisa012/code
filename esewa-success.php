<?php
session_start();
include('includes/config.php');

// eSewa sends oid, amt, and refId in the URL parameters upon success
$oid = $_GET['oid'] ?? '';
$amt = $_GET['amt'] ?? '';
$refId = $_GET['refId'] ?? '';

if (empty($oid) || empty($amt) || empty($refId)) {
    die("Invalid request from eSewa.");
}

// Verification URL (Sandbox/UAT)
$url = "https://uat.esewa.com.np/epay/transrec";
$data = [
    'amt' => $amt,
    'rid' => $refId,
    'pid' => $oid,
    'scd' => 'EPAYTEST' // Same merchant code used in payment request
];

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);

// eSewa response is XML. If successful, it contains <response_code>Success</response_code>
if (strpos($response, "Success") !== false) {
    // Payment Verified Successfully
    // Update the orders table
    $updateQuery = "UPDATE orders SET paymentStatus = 'Completed', referenceCode = '$refId' WHERE transactionId = '$oid'";
    if (mysqli_query($con, $updateQuery)) {
        // Clear cart if not already cleared (depends on project flow, usually cart is cleared at order creation)
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
                <p>Thank you for your purchase. Your payment of Rs. $amt has been verified.</p>
                <p>Transaction ID: $oid</p>
                <p>eSewa Reference: $refId</p>
                <a href='order-history.php' class='btn'>View Order History</a>
            </div>
        </body>
        </html>";
    } else {
        echo "Error updating order: " . mysqli_error($con);
    }
} else {
    // Verification failed
    echo "<h1>Payment Verification Failed</h1>";
    echo "<p>The payment could not be verified by eSewa. Please contact support.</p>";
    echo "<a href='index.php'>Back to Home</a>";
}
?>
