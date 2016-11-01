// $(document).ready(function() {
	// /* Globals Vars  */
	// var contact_form = $("#contactmeform");
	// var form_data = $("#contactmeform").serializeArray();
	// /* Functions */
	// var showErrors = {
		// printError: function(errorMessage){
			// return this.html("<ul class=\"list-unstyled text-danger\"><li>"+ errorMessage +"</li></ul>");
		// }
	// } 
	// function getServerStatusMsg(statusMsg){
		// $("#statuscontrol").removeClass('hidden').html(function () {
			// return "<p><b>" + statusMsg + "<b></p>";
		// });
	// }
	// function successActions() {
		// $("#contactmeform").hide(250,"linear",function(){
			// $("#thesuccessmessage").removeClass("hidden");
		// });
	// }
	// function loading() {
		// $("#submitbutton").html("Please Wait Processing... <i class='fa fa-cog fa-spin' aria-hidden='true'></i>").prop('disabled', true);
	// }
	// function tryagain() {
		// $("#submitbutton").html("An Error Occurred, Please Try Again!").prop('disabled', false);
	// }
// 	
	// function submit_the_form () {
		 // /* Initiate variables for processing and data collection */
		 // $.ajax({
		 	// url: 'assets/inc/process.php',
		 	// type: 'POST',
		 	// dataType: 'text',
		 	// data: form_data
		 // })
		 // .done(function(text) {
		 	// console.log(text);
		 	// // if (text.indexOf("SUCCESS") !== -1 ){
// // 		 		
		 	// // }
		 	// // else if (text.indexOf("ERROR:FNAME") !== -1 ){
		 	// // 	$(".first_name_field").addClass('has-error has-danger');
		 	// // 	$("div.first_name_field div.help-block.with-errors").html("<ul class=\"list-unstyled text-danger\"><li>The First Name Is Missing.</li></ul>");
				// // tryagain();
		 	// // } else if (text.indexOf("ERROR:MESSAGE0") !== -1 ){
		 	// // 	$(".message_field").addClass('has-error has-danger');
		 	// // 	$("div.message_field div.help-block.with-errors").html("<ul class=\"list-unstyled text-danger\"><li>The Message Is Missing.</li></ul>");
				// // tryagain();
		 	// // } else if (text.indexOf("ERROR:MESSAGE450") !== -1 ){
		 	// // 	$(".message_field").addClass('has-error has-danger');
		 	// // 	$("div.message_field div.help-block.with-errors").html("<ul class=\"list-unstyled text-danger\"><li>The Message Is Over 450 Words.</li></ul>");
				// // tryagain();
		 	// // } else if (text.indexOf("ERROR:MESSAGE50") !== -1 ){
		 	// // 	$(".message_field").addClass('has-error has-danger');
		 	// // 	$("div.message_field div.help-block.with-errors").html("<ul class=\"list-unstyled text-danger\"><li>The Message Is Under 50 Words.</li></ul>");
				// // tryagain();
		 	// // } else if (text.indexOf("ERROR:EMAIL") !== -1 ){
		 	// // 	$(".email_field").addClass('has-error has-danger');
		 	// // 	$("div.email_field div.help-block.with-errors").html("<ul class=\"list-unstyled text-danger\"><li>The Email Address Is Invalid.</li></ul>");
				// // tryagain();
		 	// // } else {
		 	// // 	console.log(text);
		 	// // 	getServerStatusMsg(text);
				// // tryagain();
		 	// // }
		 // })
		 // .fail(function(text) {
		 	// console.log(text);
		 	// getServerStatusMsg(text,"Internal Processor Error");
		 // })
		 // .always(function(text) {
			// console.log("Action Complete");
		 // });
	// }
// 	
	// contact_form.validator().on("submit", function (event) {
		// if(event.isDefaultPrevented()){
			// /* Handle the failure actions  */
			// tryagain();
		// } else {
			// /* Handle the success actions */
			// event.preventDefault();
			// loading();
			// submit_the_form();
		// }
	// });
// 
// });
