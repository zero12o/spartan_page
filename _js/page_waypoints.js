// Waypoints that will be used on the page to give that reveal effect
// Honestly, I think this can be done using context but the method below is how I have the setup for the landing page.  Visit http://imakewebthings.com/waypoints/ to learn more on the api for Waypoints. For simple examples visit - http://codepen.io/tag/waypoints/
// ============================================================================================
// Bonus Section Animations 
// ============================================================================================
var bonus_sect = document.getElementById("bonus_section");
var bonus = document.getElementById('bonus_1');
var bonus2 = document.getElementById('bonus_2');
var bonus3 = document.getElementById('bonus_3');

var bonus_waypoint_down = new Waypoint ({
	element: bonus_sect, handler: function (direction) {
		bonus.classList.add("bonus_item");
		bonus.classList.remove("hidden");
		bonus2.classList.add("bonus_item");
		bonus2.classList.remove("hidden");
		bonus3.classList.add("bonus_item");
		bonus3.classList.remove("hidden");
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
		}
	}
});
