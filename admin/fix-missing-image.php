<?php
session_start();
include('include/config.php');

if(strlen($_SESSION['alogin'])==0) {
    header('location:index.php');
    exit();
}

$message = '';
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'fix27') {
    $pid = 27;
    
    // Check what's in the database for product 27
    $query = mysqli_query($con, "SELECT * FROM products WHERE id = '$pid'");
    if ($row = mysqli_fetch_array($query)) {
        $message .= "Product 27 info:<br>";
        $message .= "Name: " . $row['productName'] . "<br>";
        $message .= "Image1: " . $row['productImage1'] . "<br>";
        $message .= "Image2: " . $row['productImage2'] . "<br>";
        $message .= "Image3: " . $row['productImage3'] . "<br>";
        
        // Check if directory exists
        $dir = "productimages/$pid";
        $fullDir = __DIR__ . "/" . $dir;
        $message .= "<br>Directory check:<br>";
        $message .= "Directory path: " . $fullDir . "<br>";
        $message .= "Directory exists: " . (is_dir($fullDir) ? 'Yes' : 'No') . "<br>";
        
        if (!is_dir($fullDir)) {
            if (mkdir($fullDir, 0755, true)) {
                $message .= "Created directory successfully<br>";
            } else {
                $message .= "Failed to create directory<br>";
            }
        }
        
        // Look for any image files that might belong to this product
        $message .= "<br>Looking for image files...<br>";
        $mainDir = __DIR__ . "/productimages";
        $files = scandir($mainDir);
        $imageFiles = array();
        
        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && !is_dir($mainDir . '/' . $file)) {
                $imageFiles[] = $file;
            }
        }
        
        $message .= "Found " . count($imageFiles) . " image files in main directory<br>";
        
        // Try to find a suitable image file
        $suitableImage = null;
        foreach ($imageFiles as $file) {
            if (strpos($file, '.jpg') !== false || strpos($file, '.jpeg') !== false || strpos($file, '.png') !== false) {
                $suitableImage = $file;
                break;
            }
        }
        
        if ($suitableImage) {
            $message .= "Found suitable image: " . $suitableImage . "<br>";
            
            // Copy the image to the product directory
            $sourceFile = $mainDir . '/' . $suitableImage;
            $targetFile = $fullDir . '/' . $suitableImage;
            
            if (copy($sourceFile, $targetFile)) {
                $message .= "Copied image to product directory<br>";
                
                // Update database
                $sql = mysqli_query($con, "UPDATE products SET productImage1 = '$suitableImage' WHERE id = '$pid'");
                if ($sql) {
                    $message .= "Updated database successfully<br>";
                } else {
                    $message .= "Failed to update database: " . mysqli_error($con) . "<br>";
                }
            } else {
                $message .= "Failed to copy image<br>";
            }
        } else {
            $message .= "No suitable image found<br>";
        }
    } else {
        $message .= "Product 27 not found in database<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix Missing Image</title>
    <link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link type="text/css" href="css/theme.css" rel="stylesheet">
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
                            <h3>Fix Missing Image</h3>
                        </div>
                        <div class="module-body">
                            <div class="alert alert-info">
                                <strong>Info:</strong> This tool will fix the missing image issue for product 27.
                            </div>
                            
                            <a href="?action=fix27" class="btn btn-primary">Fix Product 27 Image</a>
                            
                            <?php if (!empty($message)): ?>
                                <div class="alert alert-success">
                                    <pre><?php echo htmlspecialchars($message); ?></pre>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('include/footer.php');?>
</body>
</html>

