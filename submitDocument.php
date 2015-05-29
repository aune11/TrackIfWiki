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
					include ("search.php");
					include("navMenu.php");
				?>
			</nav>
			
			<div class="content">
				<h3>Submit a Document</h3>

				<p>Use the text field below to build your document for submission.  All documents built on this page will be saved as a Markdown 
				file, so be sure to enter in your styling rules as you build your document.  If you are unsure of what a 
				particular Markdown syntax looks like, please reference <a href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet">this guide</a>.  If your file includes video or other 
				components that do not have Markdown syntax, use normal HTML styling rules.</p>
				
				<p>If your file includes code samples (from a parser, for example), please include the date you pulled them on.</p>

				<p>If there are images in your document, please submit the image files via the submission form at the bottom of the page.</p>

				<form action="fileSubmit.php" method="post">
					<input type="hidden" name="directory" value="<?php echo (empty($_GET['directory']) ? '' : htmlentities(trim($_GET['directory']))); ?>" />
					<span><input type="text" name="filename" value="" class="filename" />.md</span><br />
					<span class="note">Note: Yes, spaces are acceptable; enter your filename as you want it to appear in the site navigation.</span>
					<br />
					<br />
					<textarea name="contents" rows="40" cols="80"></textarea>
					<br />
					<input type="submit" value="Submit New File" class="submitFile" /> <span id="noName"></span>
					<br />
					<span>If you have images to submit, use this field to submit the images to the site directory.</span> 
					
				</form>
				<script>
					//$('#noName').hide();
					$('.submitFile').on('click', function(e) {
						if ($('.filename').val() == '') { 
							//alert('No file name entered.  File will not submit until one is');
							$('#noName')
								.css('color', 'red')
								.css('font-style', 'italic')
								.text('No file name entered.  File will not submit until one is.');
							e.preventDefault();
						}
					});
				</script>
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

