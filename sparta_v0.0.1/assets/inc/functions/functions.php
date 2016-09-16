<?php
/**
* Calls Google Recaptcha 
* @param array $gRecaptchaResponse
* @param string $remoteIp
* @return true or false with error messages of failure
* 
*/
function googleRecaptcha($gRecaptchaResponse, $remoteIp){
  // Load required files
  require 'vendor/recaptcha/autoload.php';
  $secertKey = "6LdT7ikTAAAAAOSLdyfe3oAxcrjjPBSrm3J9YhiC";
  // Create the recaptcha object
  $recaptcha = new \ReCaptcha\ReCaptcha($secertKey);
  // Process the response
  $resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp);
  // If the response is true then return 
  if ($resp->isSuccess()) {
      return true;
  } else {
    $errors = $resp->getErrorCodes();
    foreach ( $errors as $code) {
      echo $code."\n";
    } 
  }
}

/**
* output datetime
* @return datetime
* $set_date->setTimestamp(strtotime("now")) "+5 minute";
  $today_date = $set_date->format('Y-m-d H:i:s');
*/
function output_datetime($type = null,$daterelativeformat){
  /* Create dateTime object $dateformat*/
  $set_date = new DateTime(); 
  /* Create dateTime object for expiration date */
  $set_date->setTimestamp(strtotime($daterelativeformat));
  if ($type != "unix"){
    $return_date = $set_date->format('Y-m-d H:i:s');
  } elseif ($type === "unix")  {
    $return_date = $set_date->format('U');
  }
  return $return_date; 
}

/**
* output given array value
* @return array output and/or message
*  
*/
function outputarray($arrayinput = array(),$statusMsg = null){
  $output = json_encode($arrayinput);
  echo "<script>";
  if ($arrayinput != null) {
    echo "console.log($output);";
  }
  if ($statusMsg != null) {
    echo "console.log(\"$statusMsg\");";
  }
  echo "</script>";
}

/**
* Gets the user's IP Address 
* @return IPV4 
*  
*/
function getIPAddress() {
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    }
    else {
        $ip = $remote;
    }

    return $ip;
}

/**
* Pseudo random string shuffle 
* @param int $l default value is 12 characters
* @return Pseudo string value
*  
*/
function mt_rand_str ($l = 12, $c = 'abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKMONPQRSTYVWXYZ') {

    for ($s = '', $cl = strlen($c)-1, $i = 0; $i < $l; $s .= $c[mt_rand(0, $cl)], ++$i);
    return $s;
}
/**
 * Returns a true random number from RANDOM.ORG's integer
 * http interface. Requires GET cURL. I have rewritten this to use the strings on random.org 
 *
 * @author Bo Allen - https://boallen.com/php-get-true-random-number.html - and me mobiuszero
 * @param int $length (Optional) The length of the string that you need (default 10)
 * @return mixed Random number (int) on success, error or message (string) on failure
 */
function randomstring($length = 0) {
    // Validate parameters
    if ( (int)$length <= 0 || !is_numeric($length) ) {
        $length = 10;
    }

    // Curl options
    $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => '',
        CURLOPT_USERAGENT => 'PHP',
        CURLOPT_AUTOREFERER => true,
        CURLOPT_CONNECTTIMEOUT => 120,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_MAXREDIRS => 10,
    );
    // Curl init & run
    $ch = curl_init('http://www.random.org/strings/?num=1&len='. $length .'&digits=on&upperalpha=on&loweralpha=on&unique=on&format=plain&rnd=new');
    curl_setopt_array($ch, $options);
    $content = curl_exec($ch);
    curl_close($ch);
    return trim($content);
}
/**
 * Make sure the domain that the email has has an MX record that exists
 * @param  string $email the users email addresss      
 * @param  string $record the type of record to look for
 * @return checkdnsrr - bool    
 */ 
function domain_exists($email, $record = "MX"){
    list($user, $domain) = explode('@', $email);
    if ($domain != null) {
      return checkdnsrr($domain, $record);
    }
}
/**
 * Send me the email message from the form.
 * @param  $sendmsg Array looks for email address, message and firstname 
 * @example  $sendmsg = array(
 *                          'email'=> $email,
 *                          'message'=> $message,
 *                          'firstname'=> $firstname
 *                      ) 
 * 
 * @return true or false if message was sent or not     
 */ 
function deploymessage($message_data = array()) {
      // Variables
      $email = $message_data['email'];
      $message = $message_data['message'];
      $firstname = $message_data['firstname'];
      $date = $message_data['time'];
      $content = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\"> <html xmlns=\"http://www.w3.org/1999/xhtml\"> <head> <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /> <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\"> <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\"> <meta name=\"format-detection\" content=\"telephone=no\" /> <title>Mobius Zero Email Message</title> <style type=\"text/css\"> /* RESET STYLES */ html { background-color:#E1E1E1; margin:0; padding:0; } body, #bodyTable, #bodyCell, #bodyCell{height:100% !important; margin:0; padding:0; width:100% !important;font-family:Helvetica, Arial, \"Lucida Grande\", sans-serif;} table{border-collapse:collapse;} table[id=bodyTable] {width:100%!important;margin:auto;max-width:500px!important;color:#7A7A7A;font-weight:normal;} img, a img{border:0; outline:none; text-decoration:none;height:auto; line-height:100%;} a {text-decoration:none !important;border-bottom: 1px solid;} h1, h2, h3, h4, h5, h6{color:#5F5F5F; font-weight:normal; font-family:Helvetica; font-size:20px; line-height:125%; text-align:Left; letter-spacing:normal;margin-top:0;margin-right:0;margin-bottom:10px;margin-left:0;padding-top:0;padding-bottom:0;padding-left:0;padding-right:0;} /* CLIENT-SPECIFIC STYLES */ .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} /* Force Hotmail/Outlook.com to display emails at full width. */ .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div{line-height:100%;} /* Force Hotmail/Outlook.com to display line heights normally. */ table, td{mso-table-lspace:0pt; mso-table-rspace:0pt;} /* Remove spacing between tables in Outlook 2007 and up. */ #outlook a{padding:0;} /* Force Outlook 2007 and up to provide a \"view in browser\" message. */ img{-ms-interpolation-mode: bicubic;display:block;outline:none; text-decoration:none;} /* Force IE to smoothly render resized images. */ body, table, td, p, a, li, blockquote{-ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; font-weight:normal!important;} /* Prevent Windows- and Webkit-based mobile platforms from changing declared text sizes. */ .ExternalClass td[class=\"ecxflexibleContainerBox\"] h3 {padding-top: 10px !important;} /* Force hotmail to push 2-grid sub headers down */ /* /\/\/\/\/\/\/\/\/ TEMPLATE STYLES /\/\/\/\/\/\/\/\/ */ /* ========== Page Styles ========== */ h1{display:block;font-size:26px;font-style:normal;font-weight:normal;line-height:100%;} h2{display:block;font-size:20px;font-style:normal;font-weight:normal;line-height:120%;} h3{display:block;font-size:17px;font-style:normal;font-weight:normal;line-height:110%;} h4{display:block;font-size:18px;font-style:italic;font-weight:normal;line-height:100%;} .flexibleImage{height:auto;} .linkRemoveBorder{border-bottom:0 !important;} table[class=flexibleContainerCellDivider] {padding-bottom:0 !important;padding-top:0 !important;} body, #bodyTable{background-color:#E1E1E1;} #emailHeader{background-color:#E1E1E1;} #emailBody{background-color:#FFFFFF;} #emailFooter{background-color:#E1E1E1;} .nestedContainer{background-color:#F8F8F8; border:1px solid #CCCCCC;} .emailButton{background-color:#205478; border-collapse:separate;} .buttonContent{color:#FFFFFF; font-family:Helvetica; font-size:18px; font-weight:bold; line-height:100%; padding:15px; text-align:center;} .buttonContent a{color:#FFFFFF; display:block; text-decoration:none!important; border:0!important;} .emailCalendar{background-color:#FFFFFF; border:1px solid #CCCCCC;} .emailCalendarMonth{background-color:#205478; color:#FFFFFF; font-family:Helvetica, Arial, sans-serif; font-size:16px; font-weight:bold; padding-top:10px; padding-bottom:10px; text-align:center;} .emailCalendarDay{color:#205478; font-family:Helvetica, Arial, sans-serif; font-size:60px; font-weight:bold; line-height:100%; padding-top:20px; padding-bottom:20px; text-align:center;} .imageContentText {margin-top: 10px;line-height:0;} .imageContentText a {line-height:0;} #invisibleIntroduction {display:none !important;} /* Removing the introduction text from the view */ /*FRAMEWORK HACKS & OVERRIDES */ span[class=ios-color-hack] a {color:#275100!important;text-decoration:none!important;} /* Remove all link colors in IOS (below are duplicates based on the color preference) */ span[class=ios-color-hack2] a {color:#205478!important;text-decoration:none!important;} span[class=ios-color-hack3] a {color:#8B8B8B!important;text-decoration:none!important;} /* A nice and clean way to target phone numbers you want clickable and avoid a mobile phone from linking other numbers that look like, but are not phone numbers.  Use these two blocks of code to \"unstyle\" any numbers that may be linked.  The second block gives you a class to apply with a span tag to the numbers you would like linked and styled. Inspired by Campaign Monitor's article on using phone numbers in email: http://www.campaignmonitor.com/blog/post/3571/using-phone-numbers-in-html-email/. */ .a[href^=\"tel\"], a[href^=\"sms\"] {text-decoration:none!important;color:#606060!important;pointer-events:none!important;cursor:default!important;} .mobile_link a[href^=\"tel\"], .mobile_link a[href^=\"sms\"] {text-decoration:none!important;color:#606060!important;pointer-events:auto!important;cursor:default!important;} /* MOBILE STYLES */ @media only screen and (max-width: 480px){/*////// CLIENT-SPECIFIC STYLES //////*/ body{width:100% !important; min-width:100% !important;} /* Force iOS Mail to render the email at full width. */ /* FRAMEWORK STYLES */ /* CSS selectors are written in attribute selector format to prevent Yahoo Mail from rendering media query styles on desktop. */ /*td[class=\"textContent\"], td[class=\"flexibleContainerCell\"] { width: 100%; padding-left: 10px !important; padding-right: 10px !important; max-width: 600px !important;  }*/ table[id=\"emailHeader\"], table[id=\"emailBody\"], table[id=\"emailFooter\"], table[class=\"flexibleContainer\"], td[class=\"flexibleContainerCell\"] {width:100% !important;} td[class=\"flexibleContainerBox\"], td[class=\"flexibleContainerBox\"] table {display: block;width: 100%;text-align: left;} /* The following style rule makes any image classed with 'flexibleImage'fluid when the query activates. Make sure you add an inline max-width to those images to prevent them from blowing out. */ td[class=\"imageContent\"] img {height:auto !important; width:100% !important; max-width:100% !important; } img[class=\"flexibleImage\"]{height:auto !important; width:100% !important;max-width:100% !important;} img[class=\"flexibleImageSmall\"]{height:auto !important; width:auto !important;} /* Create top space for every second element in a block */ table[class=\"flexibleContainerBoxNext\"]{padding-top: 10px !important;} /* Make buttons in the email span the full width of their container, allowing for left- or right-handed ease of use. */ table[class=\"emailButton\"]{width:100% !important;} td[class=\"buttonContent\"]{padding:0 !important;} td[class=\"buttonContent\"] a{padding:15px !important;} } /*  CONDITIONS FOR ANDROID DEVICES ONLY *   http://developer.android.com/guide/webapps/targeting.html *   http://pugetworks.com/2011/04/css-media-queries-for-targeting-different-mobile-devices/ ; =====================================================*/ @media only screen and (-webkit-device-pixel-ratio:.75){/* Put CSS for low density (ldpi) Android layouts in here */ } @media only screen and (-webkit-device-pixel-ratio:1){/* Put CSS for medium density (mdpi) Android layouts in here */ } @media only screen and (-webkit-device-pixel-ratio:1.5){/* Put CSS for high density (hdpi) Android layouts in here */ } /* end Android targeting */ /* CONDITIONS FOR IOS DEVICES ONLY =====================================================*/ @media only screen and (min-device-width : 320px) and (max-device-width:568px) {} /* end IOS targeting */ </style> <!-- Outlook Conditional CSS These two style blocks target Outlook 2007 & 2010 specifically, forcing columns into a single vertical stack as on mobile clients. This is primarily done to avoid the 'page break bug' and is optional. More information here: http://templates.mailchimp.com/development/css/outlook-conditional-css --> <!--[if mso 12]> <style type=\"text/css\"> .flexibleContainer{display:block !important; width:100% !important;} </style> <![endif]--> <!--[if mso 14]> <style type=\"text/css\"> .flexibleContainer{display:block !important; width:100% !important;} </style> <![endif]--> </head> <body bgcolor=\"#E1E1E1\" leftmargin=\"0\" marginwidth=\"0\" topmargin=\"0\" marginheight=\"0\" offset=\"0\"> <!-- CENTER THE EMAIL // --> <!-- 1.  The center tag should normally put all the content in the middle of the email page. I added \"table-layout: fixed;\" style to force yahoomail which by default put the content left. 2.  For hotmail and yahoomail, the contents of the email starts from this center, so we try to apply necessary styling e.g. background-color. --> <center style=\"background-color:#E1E1E1;\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" height=\"100%\" width=\"100%\" id=\"bodyTable\" style=\"table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;\"> <tr><td height=\"25\"></td></tr><tr> <td align=\"center\" valign=\"top\" id=\"bodyCell\"> <!-- EMAIL BODY // --> <!-- The table \"emailBody\" is the email's container. Its width can be set to 100% for a color band that spans the width of the page. --> <table bgcolor=\"#FFFFFF\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" id=\"emailBody\"> <!-- MODULE ROW // --> <!-- To move or duplicate any of the design patterns in this email, simply move or copy the entire MODULE ROW section for each content block. --> <tr> <td align=\"center\" valign=\"top\"> <!-- CENTERING TABLE // --> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"> <tr> <td align=\"center\" valign=\"top\"> <!-- FLEXIBLE CONTAINER // --> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" class=\"flexibleContainer\"> <tr> <td align=\"center\" valign=\"top\" width=\"600\" class=\"flexibleContainerCell\"> <table border=\"0\" cellpadding=\"30\" cellspacing=\"0\" width=\"100%\"> <tr> <td align=\"center\" valign=\"top\"> <!-- CONTENT TABLE // --> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"> <tr> <td valign=\"top\" class=\"textContent\"> <div style=\"text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:16px;margin-bottom:0;margin-top:3px;color:#5F5F5F;line-height:135%;\"> <p>A user from mobiuszero.com has filled out the message form. Here's what the user has to say. The form was submitted on <strong>$date</strong></p> <p><strong>The user's name:</strong><br />$firstname </p> <p><strong>The user's email address:</strong><br />$email </p> <p><strong>The user's message to you:</strong><br />$message </p> </div> </td> </tr> </table> <!-- // CONTENT TABLE --> </td> </tr> </table> </td> </tr> </table> <!-- // FLEXIBLE CONTAINER --> </td> </tr> </table> <!-- // CENTERING TABLE --> </td> </tr> <!-- // MODULE ROW --> </table> <!-- // END --> <!-- EMAIL FOOTER // --> <!-- The table \"emailBody\" is the email's container. Its width can be set to 100% for a color band that spans the width of the page. --> <table bgcolor=\"#E1E1E1\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" id=\"emailFooter\"> <!-- FOOTER ROW // --> <!-- To move or duplicate any of the design patterns in this email, simply move or copy the entire MODULE ROW section for each content block. --> <tr> <td align=\"center\" valign=\"top\"> <!-- CENTERING TABLE // --> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"> <tr> <td align=\"center\" valign=\"top\"> <!-- FLEXIBLE CONTAINER // --> <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\" class=\"flexibleContainer\"> <tr> <td align=\"center\" valign=\"top\" width=\"600\" class=\"flexibleContainerCell\"> <table border=\"0\" cellpadding=\"20\" cellspacing=\"0\" width=\"100%\"> <tr> <td valign=\"top\" bgcolor=\"#E1E1E1\"> <div style=\"font-family:Helvetica,Arial,sans-serif;font-size:13px;color:#828282;text-align:center;line-height:36px;\"> <p>Copyright &#169; 2016 by Mobius Zero, All rights reserved.</p> </div> </td> </tr> </table> </td> </tr> </table> <!-- // FLEXIBLE CONTAINER --> </td> </tr> </table> <!-- // CENTERING TABLE --> </td> </tr> </table> <!-- // END --> </td> </tr> </table> </center> </body> </html>";
        
        // Load required files
        require 'db/config.php';
        require 'mailer/PHPMailerAutoload.php';

        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'gator4152.hostgator.com;mail.postmaster.mobiuszero.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'postmaster@postmaster.mobiuszero.com';                 // SMTP username
        $mail->Password = '7TmCPt$G)AbSX%';                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        $mail->setFrom('postmaster@postmaster.mobiuszero.com', 'MobiusZero Site Form');
        $mail->addAddress('mobiuszero@mobiuszero.com', 'mobiuszero');
        $mail->addReplyTo($email, $firstname);
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = "You recieved a message from $firstname. Check it out now!";
        $mail->Body    = $content;
        $mail->AltBody = "A user from mobiuszero.com has filled out the message form. Here's what the user has to say. The form was submitted on $date\n\n The user's name:\n$firstname\n The user's email address:\n$email\n The user's message to you:\n$message\n";
        // if(!$mail->send()) {
        //     echo 'Mailer Error: ' . $mail->ErrorInfo;
        // } else {
        //     $msg_sent = Array(
        //       'msg_sent' => $db_conn->inc(1)
        //     );
        //     // /* Return error if failed */
        //     if(!$db_conn->update('ip_recorder',$msg_sent)){
        //       echo 'update failed: ' . $db_conn->getLastError();
        //     } 
        //     return true;
        // }
        return true;

}
?>
