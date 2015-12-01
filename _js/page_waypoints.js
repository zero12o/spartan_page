/*  
	Waypoints that will be used on the page to give that reveal effect
    Visit http://imakewebthings.com/waypoints/ to learn more on the api for Waypoints. 
	For simple examples visit - http://codepen.io/tag/waypoints/
*/
// ============================================================================================
// Sticky Menu Actions
// ============================================================================================
	var sticky_menu = $("#about").waypoint({
		handler: function (direction) {
			if (direction == "down") {
				$("#main_menu").addClass("stickly_navbar navbar-content-view").removeClass("navbar-default");
			} else {
				$("#main_menu").removeClass("stickly_navbar navbar-content-view").addClass("navbar-default");
			}
		},
		offset: "38%"
	});
// ============================================================================================
// Portfolio Section Animations 
// ============================================================================================
	// Find the class then add a class then remove a class within the the section.
	var portfolio_section = $("#portfolio").waypoint({
		handler: function (direction) {
			$(".waypoint").addClass("portfolio_item").removeClass("hidden");
		},
		offset: "30%"
	});
