/*  
	Waypoints that will be used on the page to give that reveal effect
    Visit http://imakewebthings.com/waypoints/ to learn more on the api for Waypoints. 
	For simple examples visit - http://codepen.io/tag/waypoints/
*/
// ============================================================================================
// Sticky Menu Actions
// ============================================================================================
	var sticky_menu = $("#content_section").waypoint({
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
// Article Section Animations 
// ============================================================================================
	var article_section = $("#articles_section").waypoint({
		handler: function (direction) {
			$(".article_waypoint").addClass("article_box").removeClass("hidden");
		},
		offset: "15%"
	});
