
<!--<ul>-->
				
	<?php
		
		function printDirectory($directory=[], $path=[]) {
			echo "<ul>";
			foreach($directory as $key => $value) {
				echo "<li>";
				if (! empty($value)) {
					// We're in a directory
					echo '<span class="directory">', $key, '</span>';
					$newPath = $path;
					$newPath[] = $key;
					printDirectory($value, $newPath);
				} else {
					// We're in a file
					echo '<a href="?page=includes/', implode('/', $path), '/', rawurlencode($key), '">', preg_replace('@\..*$@i', '', $key), '</a>';
				}
				echo "</li>";
			}
			echo "</ul>\n";
		}
		
		$DirectoryIterator = new RecursiveDirectoryIterator(__DIR__ . '\\includes', RecursiveDirectoryIterator::SKIP_DOTS);
		$FileStructure = new RecursiveIteratorIterator($DirectoryIterator);
//		print_r($FileStructure);
//		die();
		
		$fileStructure = [];
		
		foreach ($DirectoryIterator as $Directory) {
//			echo "<em>", $Directory, "</em><br />\n";
//			$directoryName = preg_replace('@^.*?\\\\([^\\\\]+)\\\\[^\\\\]*$@', '$1', $Directory);
//			echo "<strong>", $directoryName, "</strong><br />\n";
			
			//Remove the directory from the filename
			$DisplayDir = preg_replace("@^.*?\\\\([^\\\\]+)$@", '$1', $Directory);
			
			//Remove the file extension from the filename
			$DisplayDir = preg_replace('@\.[^.]*$@', '', $DisplayDir);
			$FileDir = preg_replace('@^.+?PHPDirectory\\\\@i', '', $Directory);

			foreach($FileStructure as $file) {
				$fileString = preg_replace('@^.+?\\\\includes\\\\@', '', $file);
				$fileParts  = explode('\\', $fileString);
				
				$filePointer = &$fileStructure;
				foreach($fileParts as $part) {
					if (empty($filePointer[$part])) {
						$filePointer[$part] = [];
					}
					$filePointer = &$filePointer[$part];
				}
/*
				//Remove the directory from the filename
				$DisplayName = preg_replace("@^.*?\\\\([^\\\\]+)$@", '$1', $file);
				
				//Remove the file extension from the filename
				$DisplayName = preg_replace('@\.[^.]*$@', '', $DisplayName);
				
				//Strip off the start of the path because it's relating to the local filesystem, not to the server
				//PHPDirectory will be replaced with the actual directory name when the real file system is built
				$FilePath = preg_replace('@^.+?PHPDirectory\\\\@i', '', $file);
				$FilePath = str_replace('\\', '/', $FilePath);
				
				//alternatively this is done as a UL list with style set to <li style="list-style-type: none;"> (see outside of PHP tags for <ul> tags)
//		    	echo "<span><a href='?page=", $FilePath, "'>", $DisplayName, "</a></span><br />";
*/
			}
		}

		printDirectory($fileStructure);
		
//		echo "<pre>"; print_r($fileStructure); die;

	?>

<!--</ul>-->








<!--
$.ajax(
		url
		).done(
				).success(
							).error();

$.ajax(url).done$('#content').html

$('#a')
.show()
.css)"background-color: white");   <-can also be put in a line

in index file, add jquery link or file to be able to script
-need to break nav into its own include, same with index, and then include both into a master page

add: jquery, bootstrap; bootstrap can be linked with two lines or something
-->