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
			
			<header>
				<?php
					include("header.php");
				?>
			</header>
			
			<div class="greenBar"></div>
			
			<div style="clear: both;"></div>
			
			<nav class="navigation">
				
				<?php
					include("search.php");
					include("navMenu.php");
				?>
				<script>
					//JQuery for the collapsible nav menu; currently non-functioning
					//Only present in index.php currently
					$(document).ready(function($)) { 
						var allPanels = $('.navigationContainer > .file').hide();

						$('.navigationContainer > ul > .dir').click(function() {
							allPanels.slideUp();
							$(this).parent().next().slideDown();
							return false;
						});
					}); //(jQuery);
				</script>
			</nav>
			
			<div class="content">
				<span class="editButton"><a href="editFile.php">Edit This Document</a></span>
				<?php

					if ( ! isset($_GET['page'])) { 
						include('includes/Home.html');
					} 
					elseif (preg_match('@\.md$@', $_GET['page'])) {
						$file_to_read = file_get_contents($_GET['page']);
						echo $Parsedown->text($file_to_read);
					} 
					else {
						if (file_exists($_GET['page'])) {
							include($_GET['page']);
						} 
						else {
							header('Location: /PHPDirectory/index.php');
						}
					}
				?>
			</div>
			
			<div style="clear: both;"></div>
			
			<footer>
				<?php
					include("footer.php");
				?>
			</footer>
			
		</div>
		
	</body>
		
</html>

