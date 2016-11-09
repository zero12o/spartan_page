$(document).ready(function() {
	var masonryElement = $(".masonry-elememts");
	// Per http://masonry.desandro.com/layout.html#imagesloaded. This function calls the masonry layout after each image loads. 
	masonryElement.imagesLoaded( function () {
		masonryElement.masonry({
			itemSelector: '.masonry-item',                                       
			columnWidth: '.masonry-resize',
			percentPosition: true,
			transitionDuration: 500,
		});
		var lazyImage = $("img.msny").lazyload({
			effect: 'fadeIn',
			load: function () {
				masonryElement.masonry('layout');
			}
		});
		// console.log(lazyImage);
	}).progress( function (instance, image) {
			if(!image.isLoaded){
				console.log("Image Not Found" + image.img);
			}
	});
});   