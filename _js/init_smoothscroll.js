/* SmoothScroll Initialization */
$(document).ready(function() {
	smoothScroll.init({selector: '[data-menu]', speed: 425, easing: 'easeInOutCubic', updateURL: false, offset: $("body").offset().top + 50 });
});
/* Collapse menu fix - https://github.com/twbs/bootstrap/issues/12852 */
$(document).on('click', '.navbar-collapse.in', function(event) {
	if(($(event.target).is('a')) && ($(event.target).attr('class') !== 'dropdown-toggle')){
		$(this).collapse('hide');
	}
});