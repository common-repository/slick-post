jQuery(document).ready(function($) {		


	$(window).scroll(function() {
		var height = $(window).scrollTop();
		if(height  > 100) {
			$('.slick-content').addClass( $('.slick-content').data('animation') );
		}
	});

	$('.slick-close').on('click',function(){
		$('.slick-content').hide();
	})
	

});