// ============================================================================================
//	Initialize smooth scroll
// ============================================================================================
smoothScroll.init({
    selector: '[data-menu]', // Selector for links (must be a valid CSS selector)
    speed: 500, // Integer. How fast to complete the scroll in milliseconds
    easing: 'easeInOutCubic', // Easing pattern to use
    updateURL: false, // Boolean. Whether or not to update the URL with the anchor hash on scroll
    offset: 110, // Integer. How far to offset the scrolling anchor location in pixels
}); 