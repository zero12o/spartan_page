// Waypoints that will be used on the page to give that reveal effect
var bonus_sect = document.getElementById("bonus_section");
var bonus_1 = document.getElementById('bonus_1');

var bonus_waypoint_up = new Waypoint ({
	element: bonus_sect,
	handler: function (direction) {
		if (direction == 'up') {
			bonus_1.classList.add("hidden");
			bonus_1.classList.remove("bonus_item");
		}
	}, 
	offset: '2.5%'
});

var bonus_waypoint_down = new Waypoint ({
	element: bonus_sect, handler: function (direction) {
		if (direction == 'down') {
			bonus_1.classList.add("bonus_item");
			bonus_1.classList.remove("hidden");
		}
	}, 
	offset: '3.8%'
});