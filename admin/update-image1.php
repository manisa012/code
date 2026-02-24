
<?php
session_start();
include('include/config.php');
if(strlen($_SESSION['alogin'])==0)
	{	
header('location:index.php');
}
else{
	$pid=intval($_GET['id']);// product id
if(isset($_POST['submit']))
{
	$productname=$_POST['productName'];
	$productimage1=$_FILES["productimage1"]["name"];
	
	// Debug information
	$uploadDir = "productimages/$pid";
	$fullUploadDir = __DIR__ . "/" . $uploadDir;
	$targetFile = $fullUploadDir . "/" . $productimage1;
	
	// Check if file was actually uploaded
	if (!isset($_FILES["productimage1"]) || $_FILES["productimage1"]["error"] !== UPLOAD_ERR_OK) {
		$uploadError = $_FILES["productimage1"]["error"] ?? 'Unknown error';
		$_SESSION['msg'] = "Upload error: " . $uploadError;
	} else {
		// Create directory if it doesn't exist
		if (!is_dir($fullUploadDir)) {
			if (!mkdir($fullUploadDir, 0755, true)) {
				$_SESSION['msg'] = "Error creating directory: " . $fullUploadDir;
			}
		}
		
		// Check if directory is writable
		if (!is_writable($fullUploadDir)) {
			$_SESSION['msg'] = "Directory not writable: " . $fullUploadDir;
		} else {
			// Try to move the uploaded file
			if (move_uploaded_file($_FILES["productimage1"]["tmp_name"], $targetFile)) {
				$sql = mysqli_query($con, "update products set productImage1='$productimage1' where id='$pid' ");
				if ($sql) {
					$_SESSION['msg'] = "Product Image Updated Successfully !!";
				} else {
					$_SESSION['msg'] = "Error updating database: " . mysqli_error($con);
				}
			} else {
				$_SESSION['msg'] = "Error moving uploaded file. Source: " . $_FILES["productimage1"]["tmp_name"] . " Target: " . $targetFile;
			}
		}
	}
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin| Update Product Image</title>
	<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link type="text/css" href="css/theme.css" rel="stylesheet">
	<link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
	<link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>

   <script>
function getSubcat(val) {
	$.ajax({
	type: "POST",
	url: "get_subcat.php",
	data:'cat_id='+val,
	success: function(data){
		$("#subcategory").html(data);
	}
	});
}
function selectCountry(val) {
$("#search-box").val(val);
$("#suggesstion-box").hide();
}
</script>	


</head>
<body>
<?php include('include/header.php');?>

	<div class="wrapper">
		<div class="container">
			<div class="row">
<?php include('include/sidebar.php');?>				
			<div class="span9">
					<div class="content">

						<div class="module">
							<div class="module-head">
								<h3>Update Product Image 1</h3>
							</div>
							<div class="module-body">

									<?php if(isset($_POST['submit']))
{?>
									<div class="alert alert-success">
										<button type="button" class="close" data-dismiss="alert">×</button>
									<strong>Well done!</strong>	<?php echo htmlentities($_SESSION['msg']);?><?php echo htmlentities($_SESSION['msg']="");?>
									</div>
<?php } ?>



									<br />

			<form class="form-horizontal row-fluid" name="insertproduct" method="post" enctype="multipart/form-data">

<?php 

$query=mysqli_query($con,"select productName,productImage1 from products where id='$pid'");
$cnt=1;
while($row=mysqli_fetch_array($query))
{
  


?>


<div class="control-group">
<label class="control-label" for="basicinput">Product Name</label>
<div class="controls">
<input type="text"    name="productName"  readonly value="<?php echo htmlentities($row['productName']);?>" class="span8 tip" required>
</div>
</div>


<div class="control-group">
<label class="control-label" for="basicinput">Current Product Image1</label>
<div class="controls">
<?php 
$imagePath = "productimages/" . htmlentities($pid) . "/" . htmlentities($row['productImage1']);
$fullImagePath = __DIR__ . "/" . $imagePath;
if (file_exists($fullImagePath) && !empty($row['productImage1'])) {
    echo '<img src="' . $imagePath . '" width="200" height="100" alt="Product Image">';
} else {
    echo '<div style="width:200px; height:100px; background-color:#f0f0f0; border:1px solid #ccc; display:flex; align-items:center; justify-content:center; color:#666;">';
    echo 'Image not found<br>';
    echo '<small>Path: ' . $imagePath . '</small>';
    echo '</div>';
}
?>
</div>
</div>



<div class="control-group">
<label class="control-label" for="basicinput">New Product Image1</label>
<div class="controls">
<input type="file" name="productimage1" id="productimage1" value="" class="span8 tip" required>
</div>
</div>


<?php } ?>

	<div class="control-group">
											<div class="controls">
												<button type="submit" name="submit" class="btn">Update</button>
											</div>
										</div>
									</form>
							</div>
						</div>


	
						
						
					</div><!--/.content-->
				</div><!--/.span9-->
			</div>
		</div><!--/.container-->
	</div><!--/.wrapper-->

<?php include('include/footer.php');?>

	<script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
	<script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
	<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="scripts/flot/jquery.flot.js" type="text/javascript"></script>
	<script src="scripts/datatables/jquery.dataTables.js"></script>
	<script>
		$(document).ready(function() {
			$('.datatable-1').dataTable();
			$('.dataTables_paginate').addClass("btn-group datatable-pagination");
			$('.dataTables_paginate > a').wrapInner('<span />');
			$('.dataTables_paginate > a:first-child').append('<i class="icon-chevron-left shaded"></i>');
			$('.dataTables_paginate > a:last-child').append('<i class="icon-chevron-right shaded"></i>');
		} );
	</script>
</body>
<?php } ?>