<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Failed</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; text-align: center; padding: 50px; background: #fff5f5; }
        .card { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); display: inline-block; }
        h1 { color: #e53e3e; }
        .btn { display: inline-block; padding: 10px 20px; background: #e53e3e; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .btn-home { background: #718096; margin-left: 10px; }
    </style>
</head>
<body>
    <div class='card'>
        <h1>✘ Payment Cancelled or Failed</h1>
        <p>Something went wrong with your eSewa transaction, or the payment was cancelled.</p>
        <p>Don't worry, no money has been deducted from your account.</p>
        <a href='payment-method.php' class='btn'>Try Again</a>
        <a href='index.php' class='btn btn-home'>Back to Shopping</a>
    </div>
</body>
</html>
