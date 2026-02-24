<?php
session_start();
include('include/config.php');

if(strlen($_SESSION['alogin'])==0) {
    header('location:index.php');
    exit();
}

// Function to check if image exists
function checkImageExists($productId, $imageName) {
    $imagePath = "productimages/$productId/$imageName";
    return file_exists($imagePath);
}

// Function to get all image files in a product directory
function getProductImages($productId) {
    $dir = "productimages/$productId";
    if (!is_dir($dir)) {
        return array();
    }
    
    $files = scandir($dir);
    $images = array();
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && !is_dir($dir . '/' . $file)) {
            $images[] = $file;
        }
    }
    return $images;
}

$message = '';
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'fix') {
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($productId > 0) {
        // Get product info
        $query = mysqli_query($con, "SELECT * FROM products WHERE id = '$productId'");
        if ($row = mysqli_fetch_array($query)) {
            $availableImages = getProductImages($productId);
            
            // Update database with first available image if current image doesn't exist
            if (!empty($availableImages)) {
                $firstImage = $availableImages[0];
                
                if (!checkImageExists($productId, $row['productImage1']) && !empty($row['productImage1'])) {
                    mysqli_query($con, "UPDATE products SET productImage1 = '$firstImage' WHERE id = '$productId'");
                    $message .= "Fixed productImage1 for product ID $productId<br>";
                }
                
                if (!checkImageExists($productId, $row['productImage2']) && !empty($row['productImage2'])) {
                    $secondImage = isset($availableImages[1]) ? $availableImages[1] : $firstImage;
                    mysqli_query($con, "UPDATE products SET productImage2 = '$secondImage' WHERE id = '$productId'");
                    $message .= "Fixed productImage2 for product ID $productId<br>";
                }
                
                if (!checkImageExists($productId, $row['productImage3']) && !empty($row['productImage3'])) {
                    $thirdImage = isset($availableImages[2]) ? $availableImages[2] : $firstImage;
                    mysqli_query($con, "UPDATE products SET productImage3 = '$thirdImage' WHERE id = '$productId'");
                    $message .= "Fixed productImage3 for product ID $productId<br>";
                }
            }
        }
    }
}

// Get all products with their image status
$query = mysqli_query($con, "SELECT id, productName, productImage1, productImage2, productImage3 FROM products ORDER BY id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin| Fix Product Images</title>
    <link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link type="text/css" href="css/theme.css" rel="stylesheet">
    <link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
    <link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
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
                            <h3>Fix Product Images</h3>
                        </div>
                        <div class="module-body">
                            <?php if (!empty($message)): ?>
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>Success!</strong> <?php echo $message; ?>
                                </div>
                            <?php endif; ?>

                            <div class="alert alert-info">
                                <strong>Info:</strong> This tool helps diagnose and fix product image issues. It will check if the images referenced in the database actually exist in the file system.
                            </div>

                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product ID</th>
                                        <th>Product Name</th>
                                        <th>Image 1</th>
                                        <th>Image 2</th>
                                        <th>Image 3</th>
                                        <th>Available Images</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_array($query)): ?>
                                        <?php 
                                        $productId = $row['id'];
                                        $image1Exists = checkImageExists($productId, $row['productImage1']);
                                        $image2Exists = checkImageExists($productId, $row['productImage2']);
                                        $image3Exists = checkImageExists($productId, $row['productImage3']);
                                        $availableImages = getProductImages($productId);
                                        $hasIssues = (!$image1Exists && !empty($row['productImage1'])) || 
                                                   (!$image2Exists && !empty($row['productImage2'])) || 
                                                   (!$image3Exists && !empty($row['productImage3']));
                                        ?>
                                        <tr <?php echo $hasIssues ? 'class="error"' : ''; ?>>
                                            <td><?php echo $productId; ?></td>
                                            <td><?php echo htmlentities($row['productName']); ?></td>
                                            <td>
                                                <?php if ($image1Exists): ?>
                                                    <span class="label label-success">✓ Exists</span>
                                                <?php elseif (!empty($row['productImage1'])): ?>
                                                    <span class="label label-important">✗ Missing</span>
                                                <?php else: ?>
                                                    <span class="label label-warning">Empty</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($image2Exists): ?>
                                                    <span class="label label-success">✓ Exists</span>
                                                <?php elseif (!empty($row['productImage2'])): ?>
                                                    <span class="label label-important">✗ Missing</span>
                                                <?php else: ?>
                                                    <span class="label label-warning">Empty</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($image3Exists): ?>
                                                    <span class="label label-success">✓ Exists</span>
                                                <?php elseif (!empty($row['productImage3'])): ?>
                                                    <span class="label label-important">✗ Missing</span>
                                                <?php else: ?>
                                                    <span class="label label-warning">Empty</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($availableImages)): ?>
                                                    <?php echo implode(', ', array_slice($availableImages, 0, 3)); ?>
                                                    <?php if (count($availableImages) > 3): ?>
                                                        <small>(+<?php echo count($availableImages) - 3; ?> more)</small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="label label-important">No images</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($hasIssues && !empty($availableImages)): ?>
                                                    <a href="?action=fix&id=<?php echo $productId; ?>" class="btn btn-small btn-primary">Fix</a>
                                                <?php else: ?>
                                                    <span class="btn btn-small btn-success" disabled>OK</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('include/footer.php');?>

<script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
<script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>

