<?php
    include('Parsedown/Parsedown.php');
    $parsedown = new Parsedown();

    $page = '';
    if (! empty($_GET['page'])) {
        $page = $_GET['page'];
    }
?><!doctype html>

<html>

    <head>
        <title>TrackIf Resource Page</title>
        <link rel="stylesheet" type="text/css" href="styles/styles.css">
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
        
    </head>
    
    <body>
        
        <div id="main">
            <?php
                include("header.php");
            ?>
            
            <nav class="navigation">
                
                <?php
                    include("navMenu.php");
                ?>
                <script>
                    //JQuery for the collapsible nav menu; currently non-functioning
                    $(document).ready(function() { 
                        var allPanels = $('.navigationContainer > .file').hide();
/*
                        $('.navigationContainer > ul > .dir').click(function() {
                            allPanels.slideUp();
                            $(this).parent().next().slideDown();
                            return false;
                        });
*/
                    });
                </script>
            </nav>
            
            <div class="content">
                <span class="editButton"><a href="/submitDocument.php?page=<?php echo rawurlencode($page); ?>">Edit This Document</a></span>
                <?php

                    if ( ! isset($_GET['page'])) { 
                        include('includes/Home.html');
                    } 
                    elseif (preg_match('@\.md$@', $_GET['page'])) {
                        $file_to_read = file_get_contents($_GET['page']);
                        echo $parsedown->text($file_to_read);
                    } 
                    else {
                        if (file_exists($_GET['page'])) {
                            include($_GET['page']);
                        } 
                        else {
                            header('Location: /index.php');
                        }
                    }
                ?>
            </div>

            <div style="clear: both;"></div>
        </div>
        
    </body>
        
</html>

