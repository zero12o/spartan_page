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

	function submit_the_form () {
		 /* Initiate variables for processing and data collection */
		 var form_data = $("#contactmeform").serializeArray();
		 $.ajax({
		 	url: 'assets/inc/process.php',
		 	type: 'POST',
		 	dataType: 'text',
		 	data: form_data
		 })
		 .done(function(text) {
		 	if (text.indexOf("SUCCESS") !== -1 ){
		 		console.log(text);
		 		// successActions();
		 	} else if (text.indexOf("ERROR:FNAME") !== -1 ){
		 		$(".first_name_field").addClass('has-error has-danger');
		 		$("div.first_name_field div.help-block.with-errors").html("<ul class=\"list-unstyled text-danger\"><li>The First Name Is Missing.</li></ul>");
				tryagain();
		 		// console.log(text,'Missing First Name');
		 	} else if (text.indexOf("ERROR:MESSAGE0") !== -1 ){
		 		$(".message_field").addClass('has-error has-danger');
		 		$("div.message_field div.help-block.with-errors").html("<ul class=\"list-unstyled text-danger\"><li>The Message Is Missing.</li></ul>");
				tryagain();
		 		// console.log(text,'Missing Message');
		 	} else if (text.indexOf("ERROR:MESSAGE450") !== -1 ){
		 		$(".message_field").addClass('has-error has-danger');
		 		$("div.message_field div.help-block.with-errors").html("<ul class=\"list-unstyled text-danger\"><li>The Message Is Over 450 Words.</li></ul>");
				tryagain();
		 		// console.log(text,'Message Over 450');
		 	} else if (text.indexOf("ERROR:MESSAGE50") !== -1 ){
		 		$(".message_field").addClass('has-error has-danger');
		 		$("div.message_field div.help-block.with-errors").html("<ul class=\"list-unstyled text-danger\"><li>The Message Is Under 50 Words.</li></ul>");
				tryagain();
		 		// console.log(text,'Message Under 50');
		 	} else if (text.indexOf("ERROR:EMAIL") !== -1 ){
		 		$(".email_field").addClass('has-error has-danger');
		 		$("div.email_field div.help-block.with-errors").html("<ul class=\"list-unstyled text-danger\"><li>The Email Address Is Invalid.</li></ul>");
				tryagain();
		 		// console.log(text,'Invalid Email');
		 	} else {
		 		console.log(text);
		 		getServerStatusMsg(text);
				tryagain();
		 	}
		 })
		 .fail(function(text) {
		 	console.log(text);
		 	getServerStatusMsg(text,"Internal Processor Error");
		 })
		 .always(function(text) {
			console.log("Action Complete");
		 });
	}
	function getServerStatusMsg(statusMsg){
		$("#statuscontrol").removeClass('hidden').html(function () {
			return "<p><b>" + statusMsg + "<b></p>";
		});
	}
	function successActions() {
		$("#contactmeform").hide(250,"linear",function(){
			$("#thesuccessmessage").removeClass("hidden");
		});
	}
	function loading() {
		$("#submitbutton").html("Please Wait Processing... <i class='fa fa-cog fa-spin' aria-hidden='true'></i>").prop('disabled', true);
	}
	function tryagain() {
		$("#submitbutton").html("An Error Occurred, Please Try Again!").prop('disabled', false);
	}
});
