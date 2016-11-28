<?php 
    // - Load required files
    include_once 'vendor/vendor/autoload.php';
    include_once 'class/portfolioItemActionClass.php';

    // - Initialize MZ Database class
	$portfolioItem = new portfolioItemAction;

    // - Form inputs
	if ($_POST) {
		$postVars = array('portfolioItemTitle','portfolioItemTechnology','portfolioItemCredits','portfolioItemDemoLink','portfolioItemDescription','portfolioItemCategory');

		foreach ($postVars as $post_VarsKey) {	
			if ($post_VarsKey == "portfolioItemDescription") {
				// - Remove the carriage return from the message output 
				$portfolioAddItem['portfolioItemDescription'] = (!empty($_POST["portfolioItemDescription"]) ? trim(str_replace('\r\n', "", $_POST["portfolioItemDescription"])) : null );				
			} else {
				$portfolioAddItem["{$post_VarsKey}"] = (!empty($_POST["{$post_VarsKey}"]) ? trim($_POST["{$post_VarsKey}"]) : null );
			}
		}
	} elseif ($_POST['updateItemId'] != null) {
		$postVars = array('portfolioItemTitle','portfolioItemTechnology','portfolioItemCredits','portfolioItemDemoLink','portfolioItemDescription','portfolioItemCategory','updateItemId');
		foreach ($postVars as $postVarsKey) {
			$portfolioAddItem["{$post_VarsKey}"] = (!empty($_POST["{$post_VarsKey}"]) ? trim($_POST["{$post_VarsKey}"]) : null );
		}

	} elseif ($_POST['deleteItemId'] != null) {
		$portfolioAddItem = (is_int($_POST['deleteItemId']) ? $_POST['deleteItemId'] : exit("Malformed Request"));
	}
	// - Process Files 
	echo "Image File: <br>";
	var_dump($_FILES);
	echo $_FILES['portfolioImageDir']['name'];
 	echo "<br>Porfolio Data: <br>";
 	print_r($portfolioAddItem);
	// print_r($portfolioItem->addItem($portfolioAddItem,$imageFile));


?>