// Waypoints that will be used on the page to give that reveal effect
// Honestly, I think this can be done using context but the method below is how I have the setup for the landing page.  Visit http://imakewebthings.com/waypoints/ to learn more on the api for Waypoints. For simple examples visit - http://codepen.io/tag/waypoints/
// ============================================================================================
// Bonus Section Animations 
// ============================================================================================
var article_sect = document.getElementById("articles_section");
var article1 = document.getElementById('article_1');
var article2 = document.getElementById('article_2');
var article3 = document.getElementById('article_3');

var acticle_waypoint = new Waypoint ({
	element: article_sect, handler: function (direction) {
		article1.classList.add("article_item");
		article1.classList.remove("hidden");
		article2.classList.add("article_item");
		article2.classList.remove("hidden");
		article3.classList.add("article_item");
		article3.classList.remove("hidden");
	},  
	offset: '15%'
});

// ============================================================================================
// Sticky Menu Actions
// ============================================================================================
var sticky_menu_trigger = document.getElementById('content_section');
var menu = document.getElementById('main_menu');

var sticky_menu = new Waypoint ({
	element: sticky_menu_trigger, handler: function (direction) {
		if (direction == 'down') {
			menu.classList.add("stickly_navbar","navbar-content-view");
			menu.classList.remove("navbar-default");
		}
		else {
			menu.classList.remove("stickly_navbar","navbar-content-view");
			menu.classList.add("navbar-default");
		}
	}
});
