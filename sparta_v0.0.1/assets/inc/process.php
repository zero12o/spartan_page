<?php 
    // Set timezone
    // -------------------------------
    date_default_timezone_set("America/New_York");
    // Load required files
    // -------------------------------
    require 'db/config.php';
    require 'functions/functions.php';
    // Declared Variables
    // --------------------------------
    $status_msg = null;
    $status = true;
    // Get ip address of the request been made from
    // --------------------------------
    $ip_address = $db_conn->escape(getIPAddress());
    // --------------------------------
    // Get date and ouput in 20XX-DD-MM 00:00:00 format
    $today_date = output_datetime(null,"now");
    // --------------------------------
    $ip_db_check = $db_conn->rawQueryOne('select * from ip_recorder where ip = ?',array($ip_address)); 
    $postVars = array('email','first_name','message','message_subject','formtoken','g-recaptcha-response');
	if ($_POST) {
		foreach ($postVars as $post_VarsKey) {
			if ($post_VarsKey === "email") {
				${$post_VarsKey} = (filter_var($_POST["{$post_VarsKey}"],FILTER_VALIDATE_EMAIL) ? $_POST["{$post_VarsKey}"] : null );

			} elseif ($post_VarsKey === "g-recaptcha-response") {
				$recaptcha = (isset($_POST["{$post_VarsKey}"]) ? $_POST["{$post_VarsKey}"] : null);
                
			} else {
				${$post_VarsKey} = (isset($_POST["{$post_VarsKey}"]) ? filter_var($_POST["{$post_VarsKey}"],FILTER_SANITIZE_ENCODED) : null );
			}
		}
	}
	 
    // -------------------------------- 
    // Make sure that the IP that comes in is not null, the subject is null and token key is not null < - less then > - greater then 
    if ($ip_address != null && empty($message_subject) && $formtoken != null) {
        // Make sure the IP that is making the request is in database and not flagged
        if ($ip_db_check['ip'] === $ip_address && $ip_db_check['flagged'] != 1 || ($ip_db_check['ip'] != $ip_address) {





    
        } else {
            // rerturn 403 if IP is flagged
            header("HTTP/1.0 403 Forbidden");
        }
    } else {
        $status_msg = "ERROR:NULL";
    }   
    // exit($status_msg);
    $status_msg = json_encode($status_msg);
    exit($status_msg);
?> 
