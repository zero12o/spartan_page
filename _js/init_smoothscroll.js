function call_smooth () {
	smoothScroll.init({
		selector: '[data-menu]', 
		speed: 400, 
		easing: 'easeInOutCubic', 
		updateURL: false, 
		offset: $("body").offset().top + 50, 
	}); 
} 
call_smooth();