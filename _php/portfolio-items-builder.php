<?php 
    // Load required files
    require 'db/cms_config.php';
    require_once 'vendor/vendor/autoload.php';
    // Database table call
	$showPortfolioDBItems = $db_conn->JsonBuilder()->rawQuery("SELECT id, portfolioItemTitle, portfolioItemCategory, portfolioItemTechnology, portfolioThumbImage,portfolioItemDescription,portfolioItemCredits,portfolioItemDemoLink FROM portfolio_items");

?>
<!DOCTYPE html>
<html>
<head>
	<title>The Portfolio Image Builder</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<style type="text/css">
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
	</style>
</head>
<body>
	<section class="content module">
		<div class="container">
			<div class="row"> 
				<div class="col-md-10 center-block">
					<h4>Server Results:</h4>
					<div class="serverResults row">
						<?php 
							$showItems = json_decode($showPortfolioDBItems);
							$arrLength = count($showItems);
							for ($i=0; $i < $arrLength; $i++) { 
								// print_r($showItems[$i]); portfolioItemCategory
								echo "<div class=\"col-md-4\">";
								echo "<div class=\"thumbnail\">";
								echo "<img src=\"../img/portfolio/".$showItems[$i]->portfolioThumbImage."\" width=\"250\" />";
								echo "<div class=\"caption\">";
								echo "<p>Title: ".$showItems[$i]->portfolioItemTitle."</p>";
								echo "<p>Category: ".$showItems[$i]->portfolioItemCategory."</p>";

								echo "<p><a id=\"{$showItems[$i]->id}\" class=\"edit-content\" href=\"#edit\"><i class=\"fa fa-pencil\" aria-hidden=\"true\"></i> Edit</a></p>";
								echo "<p><a id=\"{$showItems[$i]->id}\" class=\"delete-content\" href=\"#delete\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i> Delete</a></p>";
								echo "</div>";
								echo "</div>";
								echo "</div>";

							} 
						?>
					</div>
					<h4>Send To Server:</h4>
					<form enctype="multipart/form-data" accept-charset="utf-8" method="POST" action="portfolio-items-uploader.php" id="newPortfolioItemForm">
						<input id="updateItemId" type="hidden" name="updateItemId" value="" />
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
								<button id="resetForm" class="btn btn-info h5">Cancel</button>
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
		var $deletePortfolioItem = $(".delete-content");
		var $editPortfolioItem = $(".edit-content");
		var $resetFormBtn = $("#resetForm");
		// The Text editor functions, the following will allow element resize when the user inputs text into the editor. As well as spellcheck along with the CKEditor standard options. 
		$textEditor.replace( 'portfolioItemDescription',{
			extraPlugins: 'autogrow,docprops',
			fullPage: false,
			removePlugins: 'resize,wsc',
			scayt_autoStartup: true, 
			scayt_maxSuggestions: 3
		}); 

// 		function processNewPortfolioData(form) {

// 			var $form_data = new FormData(form);
// 			$.ajax({
// 			    xhr: function(){
// 			       var xhr = new window.XMLHttpRequest();
// 			       // Handle upload progress
// 			       // http://stackoverflow.com/questions/38386449/upload-files-with-progress-bar
// 			       // helped me try to grasp the percentage left during an upload event. 
// 			       xhr.upload.addEventListener("progress", function(event){
// 			           if (event.lengthComputable) {
// 			              var $percentComplete = Math.round((event.loaded / event.total)*100);
// 			              //While the upload is in progress update the progress bar on the page. 
// 			              $progressBar.css('width', $percentComplete + '%');
// 			              $progressBar.html($percentComplete + '%');
// 			              // if the percent complete var has a value of 100 then add the progress bar success bar
// 			              if ($percentComplete == 100){
// 				              $progressBar.addClass('progress-bar-success');
// 			              }
// 			           }
// 			       }, false);

// 			       return xhr;
// 			    },
// 				url: 'portfolio-items-uploader.php',
// 				type: 'POST',	
// 				data: $form_data,
// 				dataType: 'json',
// 				processData: false,
// 				contentType: false,
// 			}).done(function (data) {
// 				var $serverResponse = data;
// 				if ($serverResponse){
// 					setTimeout(function() {
// 						// Resets the all the elements on the form. 
// 						$newPortfolioItemForm[0].reset();
// 				        $progressBar.css('width', '0%');
// 				        $progressBar.html('0%');
// 				        $progressBar.removeClass('progress-bar-success');
// 				        $textEditor.instances['portfolioItemDescription'].setData('');
// 				        $("#imagePreview").attr('src', '../img/placeholder150x150.png');
// 				        $("#imageInfo").remove();
// 				        window.location.reload();
// 					}, 3000);
// 				} else {
// 					console.log($serverResponse);
// 				}
// 			});
			
// 		}

// 		function readImage(input) {
// 			// console.log("input data: ",input.files);
// 			console.log("input data: ",input.files[0]);
// 			var fileName = input.files[0].name;
// 			var fileSize = Math.round(input.files[0].size / 1024);
// 			var fileType = input.files[0].type;
// 			if (input.files && input.files[0]){
// 				var previewImage = new FileReader();
				
// 				previewImage.onload = function (event) {

// 					$("#imagePreview").attr('src', event.target.result);	
// 				}
// 				previewImage.readAsDataURL(input.files[0]);
// 				$("#imageInfo").html('<p>File Name: '+fileName+'</p><p>File Size: '+fileSize+'KB</p><p>File Type: '+fileType+'</p>');
// 			}
// 		}
// 		$("#portfolioImageDir").change(function() {
// 			readImage(this);
// 		});

// 		// Prevents form's default sudmit action 
// 		$newPortfolioItemForm.on('submit', function(event) {
// 		    event.preventDefault();
// 		    for (instance in $textEditor.instances) {
// 		        $textEditor.instances['portfolioItemDescription'].updateElement();
// 		    } 

// 			processNewPortfolioData(this);
// 		});	
// // --------------------------------------------------------------------	
$deletePortfolioItem.on('click', function(event) {
	event.preventDefault();
	console.log(event.target.id);
});

$editPortfolioItem.on('click', function(event) {
	var editLinkId = event.target.id;
	$.ajax({
		url: 'call-portfolio-items.php',
		dataType: 'json',
		type: 'POST',
		data: {
			portfolioItemId: editLinkId
		}
	}).done(function(data){
		console.log(data[0]);
		$("#updateItemId").val(data[0].id);
		$("#portfolioItemTitle").val(data[0].portfolioItemTitle);
		$("#portfolioItemTechnology").val(data[0].portfolioItemTechnology);
		$("#portfolioItemCredits").val(data[0].portfolioItemCredits);
		$("#portfolioItemDemoLink").val(data[0].portfolioItemDemoLink);
		$textEditor.instances['portfolioItemDescription'].setData(data[0].portfolioItemDescription);
		$("#portfolioItemCategory").val(data[0].portfolioItemCategory);
	});
});
$resetFormBtn.on('click', function(event) {
	event.preventDefault();
	$newPortfolioItemForm[0].reset();
    $progressBar.css('width', '0%');
    $progressBar.html('0%');
    $progressBar.removeClass('progress-bar-success');
    $textEditor.instances['portfolioItemDescription'].setData('');
    $("#imagePreview").attr('src', '../img/placeholder150x150.png');
    $("#imageInfo").remove();
    window.location.reload();
});


</script>
</body>
</html>