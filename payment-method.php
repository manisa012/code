<?php 
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
    {   
header('location:login.php');
}
else{
	if (isset($_POST['submit'])) {
		$payment_method = $_POST['paymethod'];
		
		if($payment_method == 'eSewa') {
			// Redirect to eSewa hybrid payment page (works with current eSewa system)
			header('location:esewa-hybrid-payment.php');
			exit();
		} else {
			// Update with other payment methods
			mysqli_query($con,"update orders set paymentMethod='$payment_method' where userId='".$_SESSION['id']."' and paymentMethod is null ");
			unset($_SESSION['cart']);
			header('location:order-history.php');
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Meta -->
		<meta charset="utf-8">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<meta name="description" content="">
		<meta name="author" content="">
	    <meta name="keywords" content="MediaCenter, Template, eCommerce">
	    <meta name="robots" content="all">

	    <title>PC Palace | Payment Method</title>
	    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
	    <link rel="stylesheet" href="assets/css/main.css">
	    <link rel="stylesheet" href="assets/css/red.css">
	    <link rel="stylesheet" href="assets/css/owl.carousel.css">
		<link rel="stylesheet" href="assets/css/owl.transitions.css">
		<!--<link rel="stylesheet" href="assets/css/owl.theme.css">-->
		<link href="assets/css/lightbox.css" rel="stylesheet">
		<link rel="stylesheet" href="assets/css/animate.min.css">
		<link rel="stylesheet" href="assets/css/rateit.css">
		<link rel="stylesheet" href="assets/css/bootstrap-select.min.css">
		<link rel="stylesheet" href="assets/css/config.css">
		<link href="assets/css/green.css" rel="alternate stylesheet" title="Green color">
		<link href="assets/css/blue.css" rel="alternate stylesheet" title="Blue color">
		<link href="assets/css/red.css" rel="alternate stylesheet" title="Red color">
		<link href="assets/css/orange.css" rel="alternate stylesheet" title="Orange color">
		<link href="assets/css/dark-green.css" rel="alternate stylesheet" title="Darkgreen color">
		<link rel="stylesheet" href="assets/css/font-awesome.min.css">
		<link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700' rel='stylesheet' type='text/css'>
		<link rel="shortcut icon" href="assets/images/favicon.ico">
		
		<style>
			.payment-options {
				margin: 20px 0;
			}
			
			.payment-option {
				margin: 15px 0;
				padding: 15px;
				border: 2px solid #e0e0e0;
				border-radius: 8px;
				transition: all 0.3s ease;
				cursor: pointer;
			}
			
			.payment-option:hover {
				border-color: #337ab7;
				background-color: #f8f9fa;
			}
			
			.payment-option input[type="radio"] {
				display: none;
			}
			
			.payment-option input[type="radio"]:checked + .payment-label {
				color: #337ab7;
				font-weight: bold;
			}
			
			.payment-option input[type="radio"]:checked + .payment-label .payment-text {
				color: #337ab7;
			}
			
			.payment-label {
				display: flex;
				align-items: center;
				cursor: pointer;
				margin: 0;
				font-size: 16px;
			}
			
			.payment-text {
				margin-left: 10px;
			}
			
			.esewa-label {
				display: flex;
				align-items: center;
			}
			
			.esewa-logo {
				width: 60px;
				height: auto;
				margin-right: 15px;
				border-radius: 4px;
			}
			
			.payment-option input[type="radio"]:checked + .payment-label {
				border-color: #337ab7;
			}
			
			.payment-option input[type="radio"]:checked + .payment-label .esewa-logo {
				box-shadow: 0 0 10px rgba(51, 122, 183, 0.3);
			}
			
			/* Responsive design */
			@media (max-width: 768px) {
				.payment-option {
					padding: 10px;
				}
				
				.esewa-logo {
					width: 50px;
					margin-right: 10px;
				}
				
				.payment-text {
					font-size: 14px;
				}
			}
			
			/* eSewa specific styling */
			.esewa-label {
				background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
				padding: 10px;
				border-radius: 6px;
			}
			
			.payment-option input[type="radio"]:checked + .payment-label.esewa-label {
				background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
				border: 2px solid #2196f3;
			}
		</style>
	</head>
    <body class="cnt-home">
	
		
<header class="header-style-1">
<?php include('includes/top-header.php');?>
<?php include('includes/main-header.php');?>
<?php include('includes/menu-bar.php');?>
</header>
<div class="breadcrumb">
	<div class="container">
		<div class="breadcrumb-inner">
			<ul class="list-inline list-unstyled">
				<li><a href="home.html">Home</a></li>
				<li class='active'>Payment Method</li>
			</ul>
		</div><!-- /.breadcrumb-inner -->
	</div><!-- /.container -->
</div><!-- /.breadcrumb -->

<div class="body-content outer-top-bd">
	<div class="container">
		<div class="checkout-box faq-page inner-bottom-sm">
			<div class="row">
				<div class="col-md-12">
					<h2>Choose Payment Method</h2>
					<div class="panel-group checkout-steps" id="accordion">
						<!-- checkout-step-01  -->
<div class="panel panel-default checkout-step-01">

	<!-- panel-heading -->
		<div class="panel-heading">
    	<h4 class="unicase-checkout-title">
	        <a data-toggle="collapse" class="" data-parent="#accordion" href="#collapseOne">
	         Select your Payment Method
	        </a>
	     </h4>
    </div>
    <!-- panel-heading -->

	<div id="collapseOne" class="panel-collapse collapse in">

		<!-- panel-body  -->
	    <div class="panel-body">
	    <form name="payment" method="post">
	    	<div class="payment-options">
	    		<div class="payment-option">
	    			<input type="radio" name="paymethod" value="COD" checked="checked" id="cod">
	    			<label for="cod" class="payment-label">
	    				<span class="payment-text">Cash on Delivery (COD)</span>
	    			</label>
	    		</div>
	    		
	    		
	    		
	    		<div class="payment-option">
	    			<input type="radio" name="paymethod" value="eSewa" id="esewa">
	    			<label for="esewa" class="payment-label esewa-label">
	    				<img src="https://tse3.mm.bing.net/th/id/OIP.fN2-tKH3zajr_XyM520I7wHaE5?rs=1&pid=ImgDetMain&o=7&rm=3" alt="eSewa" class="esewa-logo">
	    				<span class="payment-text">eSewa Digital Wallet</span>
	    			</label>
	    		</div>
	    	</div>
	    	
	    	<br /><br />
	    	<input type="submit" value="Proceed to Payment" name="submit" class="btn btn-primary">
	    </form>		
		</div>
		<!-- panel-body  -->

	</div><!-- row -->
</div>
<!-- checkout-step-01  -->
					  
					  	
					</div><!-- /.checkout-steps -->
				</div>
			</div><!-- /.row -->
		</div><!-- /.checkout-box -->
		<!-- ============================================== BRANDS CAROUSEL ============================================== -->
<?php echo include('includes/brands-slider.php');?>
<!-- ============================================== BRANDS CAROUSEL : END ============================================== -->	</div><!-- /.container -->
</div><!-- /.body-content -->
<?php include('includes/footer.php');?>
	<script src="assets/js/jquery-1.11.1.min.js"></script>
	
	<script src="assets/js/bootstrap.min.js"></script>
	
	<script src="assets/js/bootstrap-hover-dropdown.min.js"></script>
	<script src="assets/js/owl.carousel.min.js"></script>
	
	<script src="assets/js/echo.min.js"></script>
	<script src="assets/js/jquery.easing-1.3.min.js"></script>
	<script src="assets/js/bootstrap-slider.min.js"></script>
    <script src="assets/js/jquery.rateit.min.js"></script>
    <script type="text/javascript" src="assets/js/lightbox.min.js"></script>
    <script src="assets/js/bootstrap-select.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
	<script src="assets/js/scripts.js"></script>

	
	
	<script src="switchstylesheet/switchstylesheet.js"></script>
	
	<script>
		$(document).ready(function(){ 
			$(".changecolor").switchstylesheet( { seperator:"color"} );
			$('.show-theme-options').click(function(){
				$(this).parent().toggleClass('open');
				return false;
			});
		});

		$(window).bind("load", function() {
		   $('.show-theme-options').delay(2000).trigger('click');
		});
		
		$(document).ready(function() {
			$('.payment-option').click(function() {
				// Remove checked from all radio buttons
				$('.payment-option input[type="radio"]').prop('checked', false);
				// Check the radio button in this option
				$(this).find('input[type="radio"]').prop('checked', true);
			});
		});
	</script>
	

	

</body>
</html>
<?php } ?>