<?php 
    // Load required files
    require 'db/config.php';
    require_once 'vendor/vendor/autoload.php';
    
    // Declare variables
    $imageUploadStatus = false;
    $errorUploadMessage = null;
    $portfolioImageDir = '../img/portfolio/';
    // - Form inputs
	if ($_POST) {
		$postVars = array('portfolioItemTitle','portfolioItemTechnology','portfolioItemCredits','portfolioItemDemoLink','portfolioItemDescription','portfolioItemCategory','updateItem');
		foreach ($postVars as $post_VarsKey) {	
			$portfolioAddItem["{$post_VarsKey}"] = (!empty($_POST["{$post_VarsKey}"]) ? $db_conn->escape(trim($_POST["{$post_VarsKey}"])) : null );
		}
	}
	
	// - Remove the carriage return from the message output 
	$portfolioAddItem['portfolioItemDescription'] = str_replace('\r\n', "", $portfolioAddItem['portfolioItemDescription']);
	// - Get the name of the file that is being uploaded.
	$portfolioImageFileName =  $_FILES['portfolioImageDir']['name'];
	// - Get the size of the file that is being uploaded. 
	$portfolioImageFileSize =  $_FILES['portfolioImageDir']['size'];
	// - Get the file extension. 
	$fileExt = pathinfo($portfolioImageFileName, PATHINFO_EXTENSION);
	// - The path to the temporary file uploaded to server
	$portfolioImageTmpName =  $_FILES['portfolioImageDir']['tmp_name'];
	// - The path to where the file will be uploaded too. 
	$portfolioImageFileUploadPath = $portfolioImageDir.basename($portfolioImageFileName);

	$findPortfolioItem = $db_conn->rawQuery('SELECT * FROM portfolio_items WHERE portfolioItemTitle = ? AND portfolioThumbImage = ?', array($portfolioAddItem['portfolioItemTitle'],$portfolioImageFileName));

	// Look in the portfolio image directory to make sure the image file exists or not. file_exists is not working for me.   
	if (is_dir($portfolioImageDir)){
		if ($readImageDir = opendir($portfolioImageDir)){
			while (($file = readdir($readImageDir)) !== false){
					$images[] = $file;
			}
			closedir($readImageDir);
		}
		$findImages = preg_grep("/^.*(\.[Jj][Pp][Gg]|\.[Ss][Vv][Gg]|\.[Gg][Ii][Ff]|\.[Jj][Pp][Ee][Gg]|\.[Pp][Nn][Gg])$/", $images);
	}

	$findImages = array_search($portfolioImageFileName, $findImages);

	if ($findImages !== false && !empty($findPortfolioItem)) {
		if ($portfolioAddItem['updateItem'] === 'true') {
			$portfolioItemUpdataData = array(
				'portfolioItemTitle' => $portfolioAddItem['portfolioItemTitle'],
				'portfolioItemDescription' => $portfolioAddItem['portfolioItemDescription'],
				'portfolioItemTechnology' => $portfolioAddItem['portfolioItemTechnology'],
				'portfolioItemCredits' => $portfolioAddItem['portfolioItemCredits'],
				'portfolioItemDemoLink' => $portfolioAddItem['portfolioItemDemoLink'],
				'portfolioThumbImage' => $portfolioImageFileName,
				'portfolioItemCategory' => $portfolioAddItem['portfolioItemCategory']
			);
			
			if ($portfolioItemUpdate = $db_conn->update('portfolio_items',$portfolioItemUpdataData)) {
				$errorUploadMessage['success'] = true;
			} else {
				$errorUploadMessage['error'] = "Database Error: ".$db_conn->getLastError();
			}
		}

		
	} else {
		// Check to see if the file input has been set.
		if (isset($_FILES['portfolioImageDir'])) {
			// Make sure that the file size is not over 5 mb
			if ($portfolioImageFileSize < 5242880) {
				// Make sure the file extensions are just PNG, JPG and SVG
			 	if ($fileExt === "png" || $fileExt === "jpg" || $fileExt === "svg") {
					// Move the file to the server folder path that was specified. 
					if (move_uploaded_file($portfolioImageTmpName, $portfolioImageFileUploadPath)) {
						$imageUploadStatus = true;
					} else {
						// IF there was an error with the upload exit the script and throw back the error message
						$errorUploadMessage['error'] = $_FILES['portfolioImageDir']['error'];
			
					}
			 	} else {
			 		$errorUploadMessage['error'] = "Wrong File Extension";
			 	}
			} else {
				$errorUploadMessage['error'] = "Exceed File Size";
			}
		}
		// As long as the upload status is true and the file does not exist
		if ($imageUploadStatus) {
			$portfolioItemNewData = array(
				'portfolioItemTitle' => $portfolioAddItem['portfolioItemTitle'],
				'portfolioItemCategory' => $portfolioAddItem['portfolioItemCategory'],
				'portfolioItemDescription' => $portfolioAddItem['portfolioItemDescription'],
				'portfolioItemTechnology' => $portfolioAddItem['portfolioItemTechnology'],
				'portfolioItemCredits' => $portfolioAddItem['portfolioItemCredits'],
				'portfolioItemDemoLink' => $portfolioAddItem['portfolioItemDemoLink'],
				'portfolioThumbImage' => $portfolioImageFileName,
				'portfolioItemPostDate' => $db_conn->now()
			);
			if ($insertPortfolioItem = $db_conn->insert('portfolio_items',$portfolioItemNewData)){
				$errorUploadMessage['success'] = true;
	
			} else {
				$errorUploadMessage['error'] = "Database Error: ".$db_conn->getLastError();
			}
		} else { 
			$errorUploadMessage['error'] = "Upload Failure";
		}	
	}

	header("content-type: text/javascript; charset=utf-8");
	echo json_encode($errorUploadMessage);
	unset($errorUploadMessage);
	exit();
?>