<?php 
    // Load required files
    require 'db/config.php';
    require_once 'vendor/vendor/autoload.php';
    // Declare Variables
    $portfolioItemId = (filter_var($_REQUEST['portfolioItemId'],FILTER_VALIDATE_INT) ? $_REQUEST['portfolioItemId'] : null );
    // Call the database if the id is not null
	if ($portfolioItemId !== null ) {
	    // Database table call
		$showPortfolioDBItems = $db_conn->JsonBuilder()->rawQuery("SELECT id, portfolioItemTitle, portfolioItemTechnology, portfolioThumbImage,portfolioItemDescription,portfolioItemCredits,portfolioItemDemoLink FROM portfolio_items WHERE id = ?",array($portfolioItemId));
		echo $showPortfolioDBItems;
	} else {
		echo 'malformed request';
	}
?>