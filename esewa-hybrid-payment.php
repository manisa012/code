<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/config.php');

if(strlen($_SESSION['login'])==0) {   
    header('location:login.php');
    exit();
}

// Get total amount for the current user's pending eSewa orders
$userId = $_SESSION['id'];
$query = mysqli_query($con, "SELECT SUM(products.productPrice * orders.quantity + products.shippingCharge) as total 
                             FROM orders 
                             JOIN products ON orders.productId = products.id 
                             WHERE orders.userId = '$userId' AND orders.paymentMethod = 'eSewa' AND (orders.paymentStatus IS NULL OR orders.paymentStatus = 'Pending')");

$row = mysqli_fetch_array($query);
$totalAmount = $row['total'];

if(!$totalAmount || $totalAmount <= 0) {
    echo "<script>alert('No pending orders found for eSewa payment.'); window.location.href='index.php';</script>";
    exit();
}

// Generate a unique Transaction/Invoice ID
$transactionId = "EPAY-" . time() . "-" . $userId;

// Update orders with this transaction ID
mysqli_query($con, "UPDATE orders SET transactionId = '$transactionId' WHERE userId = '$userId' AND paymentMethod = 'eSewa' AND (paymentStatus IS NULL OR paymentStatus = 'Pending')");

// eSewa Configuration (Using Sandbox/Test credentials)
$epay_url = "https://uat.esewa.com.np/epay/main";
$merchant_code = "EPAYTEST"; // Default TEST merchant code
// Dynamically determine the base URL
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$base_url = $protocol . "://" . $host . rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

$success_url = $base_url . "/esewa-success.php";
$failure_url = $base_url . "/esewa-failure.php";

?>
<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to eSewa...</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f4f7f6; margin: 0; }
        .loader-container { text-align: center; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .spinner { border: 4px solid #f3f3f3; border-top: 4px solid #41a124; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto 20px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        h2 { color: #333; margin-bottom: 10px; }
        p { color: #666; }
    </style>
</head>
<body>
    <div class="loader-container">
        <div class="spinner"></div>
        <h2>Processing Payment</h2>
        <p>Please wait while we redirect you to eSewa Digital Wallet...</p>
        
        <form id="esewaForm" action="<?php echo $epay_url; ?>" method="POST">
            <input value="<?php echo $totalAmount; ?>" name="tAmt" type="hidden">
            <input value="<?php echo $totalAmount; ?>" name="amt" type="hidden">
            <input value="0" name="txAmt" type="hidden">
            <input value="0" name="psc" type="hidden">
            <input value="0" name="pdc" type="hidden">
            <input value="<?php echo $merchant_code; ?>" name="scd" type="hidden">
            <input value="<?php echo $transactionId; ?>" name="pid" type="hidden">
            <input value="<?php echo $success_url; ?>" name="su" type="hidden">
            <input value="<?php echo $failure_url; ?>" name="fu" type="hidden">
        </form>
    </div>

    <script type="text/javascript">
        setTimeout(function() {
            document.getElementById('esewaForm').submit();
        }, 1500);
    </script>
</body>
</html>
