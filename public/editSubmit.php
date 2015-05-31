<?php

    $directory = $_POST['directory'];
    
    /*$filename = $_POST['filename'];
    $contents = $_POST['contents'];
    
    if (empty($filename)) { 
        echo "No file name entered.  File not submitted";

        die();
    }
    
    //the data
    //open the file and choose the mode; since file shouldn't exist, will create the file
    file_put_contents('includes' . $directory . '/' . $filename . '.md', $contents);

    //print "File Submitted";
    header('Location: index.php');*/
    

    
    /*$filename = "includes" . $directory . "/" . $filename . ".md", $contents; //need directory here; use PID of page that was used to get here
    $fd = fopen($filename,"r");
    $textFileContents = fread($fd, filesize($filename));
    fclose($fd);

    echo "$textFileContents";*/

    $writedata = $_POST['contents'];

    if ($_POST['saveEdit']) {
        $fd=fopen("includes" . $directory . "/" . $filename . ".md", $contents,"w");  //need directory here; use PID of page that was used to get here
        fwrite($fd, $writedata);
        fclose($fd);
    }
    
?>