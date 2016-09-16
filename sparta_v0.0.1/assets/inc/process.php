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
    $email = (isset($_POST['email']) ? urldecode(trim(filter_var($_POST['email'],FILTER_SANITIZE_EMAIL))) : null);
    $firstname = (isset($_POST['first_name']) ? trim(filter_var($_POST['first_name'],FILTER_SANITIZE_SPECIAL_CHARS)) : null);
    $message_subject = (isset($_POST['message_subject']) ? filter_var($_POST['message_subject'],FILTER_SANITIZE_SPECIAL_CHARS) : null);
    $message = (isset($_POST['message']) ? trim(filter_var($_POST['message'],FILTER_SANITIZE_SPECIAL_CHARS)) : null);
    $ip_token_key = (isset($_POST['formtoken']) ? trim(filter_var($_POST['formtoken'],FILTER_SANITIZE_SPECIAL_CHARS)) : null);
    $recaptcha = (isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : null);
    $ip_address = getIPAddress();
    $today_date = output_datetime(null,"now");
    // -------------------------------- 
    // Make sure that the IP that comes in is not null, the subject is null and token key is not null < - less then > - greater then 
    if ($ip_address != null && $message_subject == null && $ip_token_key != null) {
        $ip_key_check = $db_conn->rawQueryOne('select * from ip_recorder where ip = ?',array($ip_address)); 
        // Make sure that we have a connection and the IP that is making the request is in database
        if ($ip_key_check) {
            // Make sure the IP is not flagged
            if ($ip_key_check['ip'] === $ip_address && $ip_key_check['flagged'] != 1) {
                // Make sure that the expiry date does not exceed today's date.
                if ($ip_key_check['expire_date'] < $today_date) {
                    $status_msg = "ERROR: EXPIRY EXCEEDED. REFRESH BROWSER.";
                    $status = false;
                }   
                // Make sure that the message check does not 
                if ($ip_key_check['msg_sent'] > 3 ) {
                    $status_msg = "ERROR: SUBMIT EXCEEDED";
                    $status = false;
                } 
                // Check to make sure the message elements are not malformed or missing.
                if ($firstname == null) {
                    $status_msg = "You forgot to fill out your first name!\n";
                    $status = false;
                }
                if ($message == null) {
                    $status_msg .= "You forgot to write your message!\n";
                    $status = false;
                }
                if (str_word_count($message) > 450) {
                    $status_msg .= "The message is ".str_word_count($message)." words long. Please make sure its under 450 words.\n";
                    $status = false;
                } 
                if (str_word_count($message) < 50){
                    $status_msg .= "The message is ".str_word_count($message)." words long. Please make sure its over 50 words.\n";
                    $status = false;
                }
                if ($email == null && !domain_exists($email,"MX") && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $status_msg .= "The email address is invalid. It should be in this format user123@example.com\n";
                    $status = false;
                }         
                // If the status is still true then send out the message and update the database. 
                if ($status != false && $ip_key_check['cookie_set'] < 7){
                    $sendmsg = array(                          
                        'email'=> $email,                          
                        'message'=> $message,
                        'firstname'=> $firstname,
                        'time'=>$today_date
                    );
                    if(deploymessage($sendmsg)) {
                        $status_msg = "SUCCESS";
                    } else {
                        $status_msg = deploymessage($sendmsg);
                    }
                } elseif ($status != false && $ip_key_check['cookie_set'] > 7) {
                    if (googleRecaptcha($recaptcha,$ip_address)) {
                        $sendmsg = array(                          
                            'email'=> $email,                          
                            'message'=> $message,
                            'firstname'=> $firstname,
                            'time'=>$today_date
                        );
                        if(deploymessage($sendmsg)) {
                            $status_msg = "RECAPTCHA SUCCESS";
                        } else {
                            $status_msg = deploymessage($sendmsg);
                        }
                    } else {
                        $status_msg .= googleRecaptcha($recaptcha,$ip_address)."\n";
                        $flagged = array( 
                            'flagged'=> $db_conn->inc(1),
                        );
                        $db_conn->where('ip',$ip_address);
                        /* Return error if failed */
                        if(!$db_conn->update('ip_recorder',$flagged)){
                            $status_msg .= 'update failed: ' . $db_conn->getLastError();  
                        }
                    }
                } 
            }
            
        } else {
            $status_msg = "ERROR: ".$db_conn->getLastError();
        }
    } else {
        $status_msg = "ERROR: NULL VALUES";
    }
    exit($status_msg);
?> 
