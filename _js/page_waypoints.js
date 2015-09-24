// Waypoints that will be used on the page to give that reveal effect
// if you want to add more actions based on directions use the conditionals below.
// if (direction == "up") {} else if (direction == "down"){}
// Honestly, I think this can be done using context but the method below is how I have the setup for the landing page.  Visit http://imakewebthings.com/waypoints/ to learn more on the api for Waypoints. For simple examples visit - http://codepen.io/tag/waypoints/

var bonus_sect = document.getElementById("bonus_section");
var bonus = document.getElementById('bonus_1');
var bonus2 = document.getElementById('bonus_2');
var bonus3 = document.getElementById('bonus_3');

var bonus_waypoint_down = new Waypoint ({
	element: bonus_sect, handler: function (direction) {
		bonus.classList.add("bonus_item");
		bonus.classList.remove("hidden");
	},  
	offset: '15%'
});

var bonus_waypoint_down = new Waypoint ({
	element: bonus_sect, handler: function (direction) {
		bonus2.classList.add("bonus_item");
		bonus2.classList.remove("hidden");
	},  
	offset: '15%'
});

var bonus_waypoint_down = new Waypoint ({
	element: bonus_sect, handler: function (direction) {
		bonus3.classList.add("bonus_item");
		bonus3.classList.remove("hidden");
	},  
	offset: '15%'
});