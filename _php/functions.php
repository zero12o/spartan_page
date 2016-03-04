<?php
/**
 * Returns a vaild ip address
 *
 * @return ip address 
 */
function getip() {
   if (validip($_SERVER["HTTP_CLIENT_IP"])) {
       return $_SERVER["HTTP_CLIENT_IP"];
   }
   foreach (explode(",",$_SERVER["HTTP_X_FORWARDED_FOR"]) as $ip) {
       if (validip(trim($ip))) {
           return $ip;
       }
   }
   if (validip($_SERVER["HTTP_PC_REMOTE_ADDR"])) {
        return $_SERVER["HTTP_PC_REMOTE_ADDR"];
   } elseif (validip($_SERVER["HTTP_X_FORWARDED"])) {
       return $_SERVER["HTTP_X_FORWARDED"];
   } elseif (validip($_SERVER["HTTP_FORWARDED_FOR"])) {
       return $_SERVER["HTTP_FORWARDED_FOR"];
   } elseif (validip($_SERVER["HTTP_FORWARDED"])) {
       return $_SERVER["HTTP_FORWARDED"];
   } else {
       return $_SERVER["REMOTE_ADDR"];
   }
}
function validip($ip) {
   if (!empty($ip) && ip2long($ip)!=-1) {
       $reserved_ips = array (
       array('0.0.0.0','2.255.255.255'),
       array('10.0.0.0','10.255.255.255'),
       array('127.0.0.0','127.255.255.255'),
       array('169.254.0.0','169.254.255.255'),
       array('172.16.0.0','172.31.255.255'),
       array('192.0.2.0','192.0.2.255'),
       array('192.168.0.0','192.168.255.255'),
       array('255.255.255.0','255.255.255.255')
       );

       foreach ($reserved_ips as $r) {
           $min = ip2long($r[0]);
           $max = ip2long($r[1]);
           if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) return false;
       }
       return true;
   } else {
       return false;
   }
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
function domain_exists($email, $record){
    list($user, $domain) = explode('@', $email);
    return checkdnsrr($domain, $record);
}

?>