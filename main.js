$(document).ready(function(){
	//check to see if the directory listing has sublists
	$('.cssMenu > li:has(ul)').addClass("has-sub");
	
	$('.cssMenu > li > a').click(function() {

		var checkElement = $(this).next();

		$('.cssMenu li').removeClass('active');
		$(this).closest('li').addClass('active');	

		if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
			$(this).closest('li').removeClass('active');
			checkElement.slideUp('normal');
		}

		if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
			$('.cssMenu ul ul:visible').slideUp('normal');
			checkElement.slideDown('normal');
		}

		if (checkElement.is('ul')) {
			return false;
		} else {
			return true;	
		}
	});
});