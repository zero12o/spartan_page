$(document).ready(function() {
	/* Globals  */
	var contact_form = $("#contactmeform");

	contact_form.validator().on("submit", function (event) {
		if(event.isDefaultPrevented()){
			/* Handle the failure actions  */
			tryagain();
		} else {
			/* Handle the success actions */
			event.preventDefault();
			loading();
			submit_the_form();
		}
	});

	function submit_the_form() {
		 /* Initiate variables for processing and data collection */
		 var form_data = $("#contactmeform").serializeArray();
		 $.ajax({
		 	url: 'assets/inc/process.php',
		 	type: 'POST',
		 	dataType: 'json',
		 	data: form_data
		 })
		 .done(function(text) {
		 	var fieldRespValue = text;
		 	if (fieldRespValue.success){
		 		// Run the success message to the user
		 		successActions();
		 	} else {
		 		// Print the vaildation error fields
			 	checkServerValidation(fieldRespValue);
		 	}

		 })
		 .fail(function(text,response) {
		 	console.log(text, response);
		 	getServerStatusMsg(text,"Internal Processor Error");
		 })

	}
	function getServerStatusMsg(statusMsg){
		$("#statuscontrol").removeClass('hidden').html(function () {
			return "<p><b>" + statusMsg + "<b></p>";
		});
	}
	function successActions() {
		contact_form.fadeOut(300, function() {
			$(this).addClass('hidden');
			$(this).remove();			
		});
		$("#robotStatusMsg").fadeIn(450, function () {
			$(this).removeClass('hidden');
		});
		
	}
	function loading() {
		$("#submitbutton").html("Please Wait Processing... <i class='fa fa-cog fa-spin' aria-hidden='true'></i>").prop('disabled', true);
	}
	function tryagain() {
		var submitBtn = $("#submitbutton");
		submitBtn.html("An Error Occurred, Please Try Again!").prop('disabled', false);
		submitBtn.on('click', function(event) {
		 	$("ul.list-unstyled.text-danger").remove();
		});
	}
	function checkServerValidation(fieldRespValue) {
	 	// The server will return back an array of errors if there are well, errors. 
	 	// The loop goes through the array object from the server
	 	for (var field in fieldRespValue){
	 		// This saves the array keyes as the selectors to look for in the document
		 	var inputSelectorClassField = $("."+field);
		 	// This adds the class 'has-error has-danger' to the apporiate fields that has the error.
			var addErrClass = $(inputSelectorClassField).addClass("has-error has-danger");
			// This assigns all the elements that has the 'div.help-block.with-errors' class names.
			var errorBlock = $("div.help-block.with-errors");
			// This looks in the object addErrClass and looks at all the children the element with the class has.
	 		var childElem = addErrClass.children(errorBlock);
				// This collects the last child element in the parent 'nameOfField_field has-error has-danger'.
	 		$(childElem[2]).html("<ul class=\"list-unstyled text-danger\"><li>"+ fieldRespValue[field] +"</li></ul>");
	 	}
	 	tryagain();
	}

});
