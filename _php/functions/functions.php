<?php
  /**
   * Sends via POST in a curl
   * @param  string $url post url to make the request
   * @param  array $data variable to send the request 
   * @param  bool $test variable to print output request
   * @return null or request output        
   */
  function post($url,$data=array(),$test=false) { 
    $ch = curl_init();  
    // set options
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST,count($data));
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($data));
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,0);
    // execute
    $output = curl_exec($ch);
    // test
    if ($test === true) {
      print_r($output);
      exit();
    }
    // close
    curl_close($ch);
    unset($ch,$output);
  }
  /**
   * Sends via GET in a curl
   * @param  string $url post url to make the request
   * @param  array $data variable to send the request
   * @param  bool $test variable  to print output of request
   * @return null or request output        
   */
  
  function get( $url,$data=array(),$test=false ) {
    $ch = curl_init();  
    // set options
    curl_setopt($ch,CURLOPT_URL,$url.'?'.http_build_query($data));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,0);
    // execute
    $output = curl_exec($ch);
    // test
    if ($test === true) {
      print_r($output);
      exit();
    }
    // close
    curl_close($ch);
    unset($ch,$output);
  }
  
/**
* Calls Google Recaptcha 
* @param array $gRecaptchaResponse
* @param string $remoteIp
* @return true or false with error messages of failure
* 
*/
function googleRecaptcha($gRecaptchaResponse, $remoteIp){
  // Load required files
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
function output_datetime($type = null,$format = null,$daterelativeformat){
  /* Create dateTime object $dateformat*/
  $set_date = new DateTime(); 
  /* Create dateTime object for expiration date */
  $set_date->setTimestamp(strtotime($daterelativeformat));
  if ($type != "unix"){
    if ($format == "dayMonDateSuffix") {
        $return_date = $set_date->format('l, F dS');
    } elseif ($format == "monDayYearTime"){
        $return_date = $set_date->format('F d, Y H:i:s');
    } elseif ($format == "timeMerTZ"){
        $return_date = $set_date->format('g A')." EASTERN TIME";
    } elseif ($format == "std") {
        $return_date = $set_date->format('Y-d-m H:i:s');
    }
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
* Creates random strings 
* @param int $length variable needed for the lenght of the string. 
* @return random string between A-z,0-9

*/
function randomstring ($length = 15) {
  $factory = new RandomLib\Factory;
  $generator = $factory->getLowStrengthGenerator();
  $password = $generator->generateString($length,"0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");
  return $password;
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

?>
