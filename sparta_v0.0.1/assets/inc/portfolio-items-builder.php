<?php 
    // Load required files
    require 'db/config.php';
    require_once 'vendor/vendor/autoload.php';
    // Database table call
	$showPortfolioDBItems = $db_conn->JsonBuilder()->rawQuery("SELECT id, portfolioItemTitle, portfolioItemCategory, portfolioItemTechnology, portfolioThumbImage,portfolioItemDescription,portfolioItemCredits,portfolioItemDemoLink,portfolioThumbImage FROM portfolio_items");
    // Declare variables 
	$portfolioImageDir = '../img/portfolio/';
	$itemsLength = count($showPortfolioDBItems);

?>
<!DOCTYPE html>
<html>
<head>
	<title>The Portfolio Image Builder</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<style type="text/css">
		section.portfolio.content.module .portfolio-bloc {
			transition: all 1s;
		}
		section.portfolio.content.module .portfolio-bloc figure {
			position: relative;
			cursor: pointer;
			text-align: center;
			overflow: hidden;
			background: #212121;
		}
		section.portfolio.content.module .portfolio-bloc figure:hover img {
			opacity: 0.03;
		}
		section.portfolio.content.module .portfolio-bloc figure img {
			transition: opacity 0.35s;
			opacity: 0.99;
			min-height: 100%;
		}
		section.portfolio.content.module .portfolio-bloc figure figcaption a {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			opacity: 0;
			font-size: 0;
			z-index: 10;
		} 
		section.portfolio.content.module .portfolio-bloc figure figcaption::before {
			top: 15px; 
			right: 15px;
			bottom: 15px;
			position: absolute;
			left: 15px;
			border: 3px solid #fff;
			content: '';
			box-shadow: 0 0 0 15px rgba(255,255,255,0.2);
			opacity: 0;
			transition: opacity 0.35s, transform 0.35s;
			transform: scale3d(1.15,1.15,1);
			-moz-transform: scale3d(1.15,1.15,1);
			-webkit-transform: scale3d(1.15,1.15,1);
		}
		section.portfolio.content.module .portfolio-bloc figure:hover figcaption::before {
			opacity: 1; 
			transform: scale3d(1,1,1);
		}
		section.portfolio.content.module .portfolio-bloc figure .portfolioTitle {
			opacity: 0;
			transition: opacity 0.15s, top 0.35s;
		    position: absolute;
		    top: 90%;
			left: 50%;
			-webkit-transform: translateX(-50%);
			transform: translateX(-50%);
		}
		section.portfolio.content.module .portfolio-bloc figure:hover .portfolioTitle {
			opacity: 1;
			top: 25%;
			color: #fff;
		}
		section.portfolio.content.module .portfolio-bloc figure span.portfolioLearnMore {
			font-size: 18px;
			opacity: 0;
			transition: opacity 1.1s;
			position: absolute;
			top: 60%;
			margin: auto;
			-webkit-transform: translateX(-50%);
			transform: translateX(-50%);
		}
		section.portfolio.content.module .portfolio-bloc figure:hover  span.portfolioLearnMore {
			opacity: 1;
		}
		fieldset {
			padding: 5em;
			border: #4c4c4c 3px solid;
		}
		form {
			margin: 15px auto;
		}
		form .form-control {
			border-radius: 1px;
		}
		form button.h5 {
			color: #fff;
		}
		.serverResults {
			margin: 15px auto;
		    border: #4c4c4c 3px solid;
		    padding: 15px;
		}
		input[type*=file].fileUpload {
			width: 0.1px;
			height: 0.1px;
			opacity: 0;
			overflow: hidden;
			position: absolute;
			z-index: -1;
		}
		.fileUpload + label {
			display: inline-block;
			color:#FFF;
			background-color: #ED4949;
			cursor: pointer;
		    width: 100%;
		    padding: 15px;
		    border-radius: 2px;
		    text-align: center;
		    transition: all 350ms;
		}
		.fileUpload:focus + label, 
		.fileUpload:hover + label {
			background: #4C4C4C;
		}
		.modal-dialog.full-page-dialog {
			width: 98%;
			margin: 15px auto;
			-webkit-box-shadow: none;
			box-shadow: none;
			background: none;
		}
		.modal-dialog.full-page-dialog .modal-content {
			box-shadow: none;
			-webkit-box-shadow: none;
			border-radius: 2px;
		    border: 5px solid rgba(0, 0, 0, 0);
		}
		.modal-dialog.full-page-dialog .modal-content button.close {
			position: absolute;
			z-index: 100;
		    float: none;
		    left: 96.5%;
		    top: 1%;
		    color: #393939;
		}
		.modal-dialog.full-page-dialog .modal-content .modal-body {
			padding: 0;
		}
		.modal-dialog.full-page-dialog .modal-content .modal-body {
			background: #393939;
		}
		.modal-dialog.full-page-dialog .modal-content .modal-body .container-fluid .portfolioItemInfoWrapper {
			background: #393939;
		}
		.modal-dialog.full-page-dialog .modal-content .modal-body .container-fluid .portfolioItemContentWrapper {
			background: #FFF;
			min-height: 100vh;
		}
		.modal-dialog.full-page-dialog .modal-content .modal-body .container-fluid .portfolioItemContentWrapper .portfolioDescriptionContent {
			padding: 5% 0 5% 0;
		}
		.portfolioThumbnail.thumbnail {
			padding: 5px;
			border-radius: 2px; 
			max-width: 85%;
		}
		.portfolioTitle {
			margin: 7% auto;
			color: #fff;
		}
		.portfolioMetaInfo p {
			color: #fff;
		}

		.modal-dialog.full-page-dialog .modal-content .modalOverlay {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: #353535;
			z-index: 1;
		}
		.modal-dialog.full-page-dialog .modal-content .modalOverlay .modalStatusMsg{
			color: #FFF;
			position: relative;
			top: 40%;
			text-align: center;
		}
	</style>
</head>
<body>
	<section class="content module">
		<div class="container">
			<div class="row">
				<div class="col-md-10 center-block">
					<h4>Server Results:</h4>
					<div class="serverResults">						
						<?php 
							$showItems = json_decode($showPortfolioDBItems);

							$arrLength = count($showItems);
							for ($i=0; $i < $arrLength; $i++) { 
								// print_r($showItems[$i]); portfolioItemCategory
								echo "<p>ID: ".$showItems[$i]->id."</p>";
								echo "<p>Title: ".$showItems[$i]->portfolioItemTitle."</p>";
							} 
						?>
					</div>
					<h4>Send To Server:</h4>
					<form enctype="multipart/form-data" accept-charset="utf-8" method="POST" action="" id="newPortfolioItemForm">
						<fieldset>
							<div class="form-group">
								<label for="portfolioItemTitle">Portifolio Item Name: </label>
								<input id="portfolioItemTitle" placeholder="Enter Name" type="text" name="portfolioItemTitle" class="form-control">
							</div>
							<div class="form-group">
								<label for="portfolioItemCategory">Portifolio Item Category: </label>
								<select id="portfolioItemCategory" name="portfolioItemCategory" class="form-control">
									<option value="">Select A Category</option>
									<option value="landingPage">Landing Page</option>
									<option value="wordpress">Wordpress</option>
									<option value="email">Email</option>
									<option value="website">Website</option>
									<option value="customWebScripts">Custom Web Scripts</option>
								</select>
							</div>
							<div class="form-group">
								<label for="portfolioItemTechnology">Portifolio Technology Used: </label>
								<input id="portfolioItemTechnology" placeholder="Enter Technology" type="text" name="portfolioItemTechnology" class="form-control">
							</div>
							<div class="form-group">
								<label for="portfolioItemCredits">Portifolio Credits: </label>
								<input id="portfolioItemCredits" placeholder="Enter Credits" type="text" name="portfolioItemCredits" class="form-control">
							</div>
							<div class="form-group">
								<label for="portfolioItemDemoLink">Portifolio Link To Demo Page: </label>
								<input id="portfolioItemDemoLink" placeholder="Enter Link To Demo Page" type="text" name="portfolioItemDemoLink" class="form-control">
							</div>	
							<div class="form-group">
								<p><strong>Portifolio Item Image:</strong></p>
								<div class="thumbnail">
									<img id="imagePreview" src="../img/placeholder150x150.png" width="150" height="150" alt="File Preview" />
									<div id="imageInfo" class="caption"></div>
								</div>
								<input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
								<input id="portfolioImageDir" placeholder="Enter Link To Demo Page" type="file" name="portfolioImageDir" class="form-control fileUpload">
								<label for="portfolioImageDir">Click To Select File <i class="fa fa-cloud-upload"></i></label>
							</div>					
							<div class="form-group">
								<label for="portfolioItemDescription">Portfolio Item Description: </label>
								<textarea id="portfolioItemDescription" placeholder="Enter Description" name="portfolioItemDescription" class="portfolioItemDescription form-control"></textarea>
							</div>
							<div class="form-group">
								<button id="addNewItemSubmitBtn" class="btn btn-primary h5">Submit The Portfolio Item</button>
							</div>
							<div class="form-group"> 
								<h5>Upload Progress: </h5>
								<div class="progress">
								  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="70"
								  aria-valuemin="0" aria-valuemax="100" style="width:0%">
								    0% Complete
								  </div>
								</div>
							</div>
						</fieldset>
					</form>

				</div>
			</div>
		</div>
	</section>
	<script type="text/javascript" src="../js/scripts.min.js"></script>
	<script src="http://cdn.ckeditor.com/4.5.11/standard-all/ckeditor.js"></script>
	<script>
		// Globals
   	  	var $progressBar = $(".progress .progress-bar.progress-bar-striped");
		var $textEditor = CKEDITOR;
		var $newPortfolioItemForm = $("#newPortfolioItemForm");

		// The Text editor functions, the following will allow element resize when the user inputs text into the editor. As well as spellcheck along with the CKEditor standard options. 
		$textEditor.replace( 'portfolioItemDescription',{
			extraPlugins: 'autogrow,docprops',
			fullPage: false,
			removePlugins: 'resize,wsc',
			scayt_autoStartup: true, 
			scayt_maxSuggestions: 3
		}); 

		function processNewPortfolioData(form) {

			var $form_data = new FormData(form);
			$.ajax({
			    xhr: function(){
			       var xhr = new window.XMLHttpRequest();
			       // Handle upload progress
			       // http://stackoverflow.com/questions/38386449/upload-files-with-progress-bar
			       // helped me try to grasp the percentage left during an upload event. 
			       xhr.upload.addEventListener("progress", function(event){
			           if (event.lengthComputable) {
			              var $percentComplete = Math.round((event.loaded / event.total)*100);
			              //While the upload is in progress update the progress bar on the page. 
			              $progressBar.css('width', $percentComplete + '%');
			              $progressBar.html($percentComplete + '%');
			              // if the percent complete var has a value of 100 then add the progress bar success bar
			              if ($percentComplete == 100){
				              $progressBar.addClass('progress-bar-success');
			              }
			           }
			       }, false);

			       return xhr;
			    },
				url: 'portfolio-items-uploader.php',
				type: 'POST',	
				data: $form_data,
				dataType: 'json',
				processData: false,
				contentType: false,
			}).done(function (data) {
				var $serverResponse = data;
				if ($serverResponse){
					setTimeout(function() {
						// Resets the all the elements on the form. 
						$newPortfolioItemForm[0].reset();
				        $progressBar.css('width', '0%');
				        $progressBar.html('0%');
				        $progressBar.removeClass('progress-bar-success');
				        $textEditor.instances['portfolioItemDescription'].setData('');
				        $("#imagePreview").attr('src', '../img/placeholder150x150.png');
				        $("#imageInfo").remove();
				        window.location.reload();
					}, 3000);
				} else {
					console.log($serverResponse);
				}
			});
			
		}

		function readImage(input) {
			// console.log("input data: ",input.files);
			console.log("input data: ",input.files[0]);
			var fileName = input.files[0].name;
			var fileSize = Math.round(input.files[0].size / 1024);
			var fileType = input.files[0].type;
			if (input.files && input.files[0]){
				var previewImage = new FileReader();
				
				previewImage.onload = function (event) {

					$("#imagePreview").attr('src', event.target.result);	
				}
				previewImage.readAsDataURL(input.files[0]);
				$("#imageInfo").html('<p>File Name: '+fileName+'</p><p>File Size: '+fileSize+'KB</p><p>File Type: '+fileType+'</p>');
			}
		}
		$("#portfolioImageDir").change(function() {
			readImage(this);
		});

		// Prevents form's default sudmit action 
		$newPortfolioItemForm.on('submit', function(event) {
		    event.preventDefault();
		    for (instance in $textEditor.instances) {
		        $textEditor.instances['portfolioItemDescription'].updateElement();
		    }
			processNewPortfolioData(this);
		});	
// --------------------------------------------------------------------	
</script>
</body>
</html>

 
