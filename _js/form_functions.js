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
		 var form_data = $("#contactmeform").serialize();

		 $.ajax({
		 	url: 'assets/inc/process.php',
		 	type: 'POST',
		 	dataType: 'text',
		 	data: form_data
		 })
		 .done(function(text) {
		 	if (text == "SUCCESS"){
		 		console.log(text);
		 		successActions();
		 	} else {
		 		console.log(text);
		 		getServerStatusMsg(text);
		 	}
		 })
		 .fail(function(text) {
		 	console.log(text);
		 	console.log("Internal Processor Error");
		 })
		 .always(function(text) {
			console.log("Action Complete");
		 });
	}
	function getServerStatusMsg(statusMsg){
		$("#statuscontrol").removeClass('hidden').html(function () {
			var themessage = "<b>" + statusMsg + "</b>";
			return "<p>" + themessage + "</p>";
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
		$("#submitbutton").html("Please Try Again!").prop('disabled', false);
	}
});
