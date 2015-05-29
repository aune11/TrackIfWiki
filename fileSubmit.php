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
	file_put_contents('includes' . $directory . '/' . $filename . '.md', $contents);

	//print "File Submitted";
    header('Location: index.php');

?>