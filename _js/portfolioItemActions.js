// Globals
// ---------------------------------------------------------------------
var $masonryPortfolioImageItem = $("img.img-responsive.msny");
var $modalPortfolioBtn = $('.modal-call');
var $portfolioModal = $('#portfolioItemDisplay');
var $modalPortfolioItemTitle = $(".modal .portfolioTitle");
var $modalPortfolioMetaInfo = $(".modal .portfolioMetaInfo");
var $modalPortfolioThumbnail = $(".modal .portfolioThumbnail img");		
var $modalPortfolioDescriptionContent = $(".modal .portfolioDescriptionContent");
var $modalOverlay = $("#modalOverlay");
var $portfolioItemsContainer = $("#portifolioImgHolder");

// Initialize Masonry
// ---------------------------------------------------------------------
	// console.log("Called Images: ",$masonryPortfolioImageItem);
	$portfolioItemsContainer.imagesLoaded(function(){ 
		$portfolioItemsContainer.masonry({
			itemSelector: '.masonry-item',                                       
			columnWidth: '.masonry-resize',
			percentPosition: true,
			transitionDuration: 200
		});
		$portfolioItemsContainer.masonry('layout');
	}); 

// Modal ajax call actions 
// ---------------------------------------------------------------------
$modalPortfolioBtn.on('click', function(event) {
	// console.log("Item Btns: ",$modalPortfolioBtn);
	var $modalBtnId = event.target.id;
	// console.log("Btn ID: ", $modalBtnId);
	// event.stopPropagation();
	// Call the portifolio items to the page
	$.ajax({
		url: 'assets/inc/call-portfolio-items.php',
		dataType: 'json',
		type: 'POST',
		data: {
			portfolioItemId: $modalBtnId
		}
	})
	.done(function(data) {
		// console.log(data[0]);
		$modalPortfolioItemTitle.html(data[0].portfolioItemTitle);
		if (data[0].portfolioItemCredits == null) {
			$modalPortfolioMetaInfo.html(
				"<p>Technology: "+data[0].portfolioItemTechnology+"</p>"+
				"<p>Demo: "+data[0].portfolioItemDemoLink+"</p>"
			);
		} else {
			$modalPortfolioMetaInfo.html(
				"<p>Technology: "+data[0].portfolioItemTechnology+"</p>"+
				"<p>Demo: "+data[0].portfolioItemDemoLink+"</p>"+
				"<p>Credits: "+data[0].portfolioItemCredits+"</p>"
			);
		}

		$modalPortfolioDescriptionContent.html(data[0].portfolioItemDescription);
		$modalPortfolioThumbnail.attr('src', 'assets/img/portfolio/'+data[0].portfolioThumbImage);
		$portfolioModal.modal('handleUpdate');
		$modalOverlay.css('display', 'none');
	});	
});
$portfolioModal.on('hidden.bs.modal', function(event) {
	$modalOverlay.css('display', 'block');
	$modalPortfolioItemTitle.html('');
	$modalPortfolioMetaInfo.html('');
	$modalPortfolioDescriptionContent.html('');
});