<?php 
    
    function search($query='') { 
        $DirectoryIterator = new RecursiveDirectoryIterator(__DIR__ . DIRECTORY_SEPARATOR . 'includes', FilesystemIterator::SKIP_DOTS);
        $FileStructure = new RecursiveIteratorIterator($DirectoryIterator);
        
        $results = array();
        
        foreach($FileStructure as $file) {
            $fileString = preg_replace('@^.+?' . preg_quote(DIRECTORY_SEPARATOR, '@') . 'includes' . preg_quote(DIRECTORY_SEPARATOR, '@') . '@', '', $file);
            $content = file_get_contents($file);
            if (stripos($content, $query) !== false) { 
                $results[] = $fileString;
            }
        }
        
        return $results;
    }
    
?>
<!doctype html>

<html>

    <head>
        <title>TrackIf Resource Page</title>
        <link rel="stylesheet" type="text/css" href="styles/styles.css">
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    </head>
    
    <body>
        
        <div id="main">
            
            <?php
                include('Parsedown/Parsedown.php');
                $Parsedown = new Parsedown();
            ?>
            
            <?php
                include("header.php");
            ?>
            
            <nav class="navigation">
                <?php
                    include("navMenu.php");
                ?>
            </nav>
            
            <div class="content">
                <?php 
                
                    if (isset($_POST['search'])) {     
                        $results = search($_POST['search']);
                        
                        if (empty($results)) { 
                            echo "No results";
                        }
                        else { 
                            echo "<br />Found " . count($results) . " results<br /><br />";
                            
                            foreach ($results as $result) { 
                                echo "-<a href='/index.php?page=" . rawurlencode($result) . "'>" . htmlentities(preg_replace('@\.[^' . preg_quote(DIRECTORY_SEPARATOR, '@') . ']+$@', '', $result)) . "</a><br />";
                            }    
                        }
                    }
                
                ?>
            </div>
            
            <div style="clear: both;"></div>
        </div>
        
    </body>
        
</html>
