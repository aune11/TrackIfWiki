<?php
    
    $directory = $_POST['directory'];
    
    $filename = $_POST['filename'];
    $contents = $_POST['contents'];
    
    if (empty($filename)) { 
        echo "No file name entered.  File not submitted";

        die();
    }
    
    //the data
    //open the file and choose the mode; since file shouldn't exist, will create the file
    $filePath = $directory . DIRECTORY_SEPARATOR . $filename . '.md';
    file_put_contents($filePath, $contents);

    header('Location: /index.php?page=' . rawurlencode($filePath));

?>