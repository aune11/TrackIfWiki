<br />
<span>
	<form action='searchResult.php' method="post">
		<input type="text" name="search" value="<?php if (isset($_POST['search'])) { echo htmlentities($_POST['search']); } ?>" />
		<input type="submit" value="Search!" />
	</form>	
</span>


