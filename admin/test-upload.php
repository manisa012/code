<?php
session_start();
include('include/config.php');

if(strlen($_SESSION['alogin'])==0) {
    header('location:index.php');
    exit();
}

$message = '';
if(isset($_POST['submit'])) {
    $message = "Upload test results:<br>";
    $message .= "POST data: " . print_r($_POST, true) . "<br>";
    $message .= "FILES data: " . print_r($_FILES, true) . "<br>";
    
    if(isset($_FILES['testfile'])) {
        $file = $_FILES['testfile'];
        $message .= "File upload error code: " . $file['error'] . "<br>";
        $message .= "File size: " . $file['size'] . "<br>";
        $message .= "File type: " . $file['type'] . "<br>";
        $message .= "Temporary file: " . $file['tmp_name'] . "<br>";
        $message .= "Temporary file exists: " . (file_exists($file['tmp_name']) ? 'Yes' : 'No') . "<br>";
    }
    
    // Check PHP upload settings
    $message .= "<br>PHP Upload Settings:<br>";
    $message .= "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
    $message .= "post_max_size: " . ini_get('post_max_size') . "<br>";
    $message .= "max_file_uploads: " . ini_get('max_file_uploads') . "<br>";
    $message .= "file_uploads: " . (ini_get('file_uploads') ? 'On' : 'Off') . "<br>";
}

// Check directory permissions
$testDir = "productimages/test";
$fullTestDir = __DIR__ . "/" . $testDir;
$message .= "<br>Directory Test:<br>";
$message .= "Current directory: " . __DIR__ . "<br>";
$message .= "Test directory: " . $fullTestDir . "<br>";
$message .= "Directory exists: " . (is_dir($fullTestDir) ? 'Yes' : 'No') . "<br>";
$message .= "Directory writable: " . (is_writable($fullTestDir) ? 'Yes' : 'No') . "<br>";

// Try to create directory
if (!is_dir($fullTestDir)) {
    if (mkdir($fullTestDir, 0755, true)) {
        $message .= "Successfully created test directory<br>";
    } else {
        $message .= "Failed to create test directory<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Test</title>
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
                            <h3>Upload Test</h3>
                        </div>
                        <div class="module-body">
                            <form method="post" enctype="multipart/form-data">
                                <div class="control-group">
                                    <label class="control-label">Test File</label>
                                    <div class="controls">
                                        <input type="file" name="testfile" class="span8" required>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <button type="submit" name="submit" class="btn">Test Upload</button>
                                    </div>
                                </div>
                            </form>
                            
                            <?php if (!empty($message)): ?>
                                <div class="alert alert-info">
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

