<?php 
    // Load required files
    // -------------------------------
    require 'db/config.php';
    require_once 'vendor/vendor/autoload.php';
    require 'functions/functions.php';
    // Declared Variables
    // --------------------------------
    $status_msg = array();
    // Get ip address of the request been made from
    // --------------------------------
    $ip_address = $db_conn->escape(getIPAddress());
    // --------------------------------
    // Get date and ouput in 20XX-DD-MM 00:00:00 format
    $today_date = $db_conn->escape(output_datetime(null,"now"));
    // --------------------------------
    $ip_db_check = $db_conn->rawQueryOne('select * from ip_recorder where ip = ?',array($ip_address)); 
    $postVars = array('email','first_name','message','message_subject','formtoken','g-recaptcha-response');
	if ($_POST) {
		foreach ($postVars as $post_VarsKey) {
			if ($post_VarsKey === "email") {
				${$post_VarsKey} = (filter_var($_POST["{$post_VarsKey}"],FILTER_VALIDATE_EMAIL) ? $_POST["{$post_VarsKey}"] : null );
                $email = (domain_exists(${$post_VarsKey},"MX") ? ${$post_VarsKey} : null);

			} elseif ($post_VarsKey === "g-recaptcha-response") {
				$recaptcha = (isset($_POST["{$post_VarsKey}"]) ? $_POST["{$post_VarsKey}"] : null);
                
			} else {
				${$post_VarsKey} = (!empty($_POST["{$post_VarsKey}"]) ? $db_conn->escape(filter_var($_POST["{$post_VarsKey}"],FILTER_SANITIZE_ENCODED)) : null );
			}
		}
	}
	 
    // -------------------------------- 
    // Make sure that the IP that comes in is not null, the subject is null and token key is not null < - less then > - greater then 
    if ($ip_address != null && $message_subject === null && $formtoken != null) {
        // Make sure the IP that is making the request is in database and not flagged
        if ($ip_db_check['ip'] === $ip_address && $ip_db_check['flagged'] != 1) {
            /*
             * Email Address Check Block
             * ------------------------
            */
            if ($email === null) {
                $status_msg["email_field"] = "Email Invalid";
            } 
            /*
             * First Name Check Block
             * ------------------------
            */            
            if ($first_name === null){
                $status_msg["first_name_field"] = "Firstname Required";
            } elseif (strlen($first_name) > 150){
                $status_msg["first_name_field"] = "Firstname over 150 characters, currently: ".strlen($first_name);
            } elseif (strlen($first_name) <= 1){
                $status_msg["first_name_field"] = "Firstname under 1 characters, currently: ".strlen($first_name);
            }
            /*
             * Message Check Block
             * ------------------------
            */ 
            if ($message === null){
                $status_msg["message_field"] = "Message Required";
            } elseif (strlen($message) > 3850){
                $status_msg["message_field"] = "Message over 3850 characters, currently: ".strlen($message);
            } elseif (strlen($message) < 25){
                $status_msg["message_field"] = "Message under 25 characters, currently: ".strlen($message);
            }  
    
        } else {
            // rerturn 403 if IP is flagged or not in the data table
            header("HTTP/1.1 403 Unauthorized");
            header("Location: ../403.html");
        }
    } else {
        $status_msg = "ERROR:NULL";
    }
    // If status message is empty then reassign the value to success. 
    if (empty($status_msg)){
        $status_msg["success"] = true;
        $message_data = array(
            'email' => $email, 
            'message' => $message, 
            'firstname' => $firstname, 
            'time' => $time, 
        );
        deploymessage($message_data);
    }
    header("content-type: text/javascript; charset=utf-8");
    echo json_encode($status_msg);
    unset($status_msg);
    exit();
?> 
