<?php
    function printDirectory($directory=[], $path=[]) {
        $content = "<ul>";
        $files = '';
        $directories = '';
        
        foreach($directory as $key => $value) {
            $pathString = preg_replace('@/+@', '/', '/' . implode('/', $path) . '/');
            $class = '';
            if (! empty($value)) {
                // We're in a directory
                $dirname = '/submitDocument.php?directory=' . rawurlencode($pathString . $key);
                if (rawurldecode($_SERVER['REQUEST_URI']) == rawurldecode($dirname)) { 
                    $class = ' current';
                }
                
                $directories .= "<li class='dir" . $class . "'><div class='icon'></div>";
                $directories .= '<span class="directory"><a href="' . $dirname . '">' . $key . '</a></span>';
                $newPath = $path;
                $newPath[] = $key;
                $directories .= printDirectory($value, $newPath);
                $directories .= "</li>";
            } 
            else {
                // We're in a file
                //tells loop to ignore any files named . or ..
                if ($key == '.' || $key == '..') {
                    continue;
                }
                $filename = "/index.php?page=includes" . $pathString . rawurlencode($key);
                
                if (rawurldecode($_SERVER['REQUEST_URI']) == rawurldecode($filename)) { 
                    $class = ' current';
                }
                
                $files .= "<li class='file" . $class . "'><div class='icon'></div>";
                $files .= '<a href="' . $filename . '">' . preg_replace('@\..*$@i', '', $key) . '</a>';
                $files .= "</li>";
            }
        }
        $content .= $files . $directories . "</ul>\n";
        
        return $content;
    }
    
    $DirectoryIterator = new RecursiveDirectoryIterator(__DIR__ . DIRECTORY_SEPARATOR . 'includes');
    $FileStructure = new RecursiveIteratorIterator($DirectoryIterator);
    
    
    $fileStructure = [];

    foreach($FileStructure as $file) {
        $fileString = preg_replace('@^.+?' . preg_quote(DIRECTORY_SEPARATOR, '@') . 'includes'. preg_quote(DIRECTORY_SEPARATOR, '@') . '@', '', $file);
        $fileParts  = explode(preg_quote(DIRECTORY_SEPARATOR, '@'), $fileString);

        $filePointer = &$fileStructure;
        foreach($fileParts as $part) {
            if (empty($filePointer[$part])) {
                $filePointer[$part] = [];
            }
            $filePointer = &$filePointer[$part];
        }
    }
?>
<div class="navigationContainer">
    <?php
        echo printDirectory($fileStructure);
    ?>
</div>
