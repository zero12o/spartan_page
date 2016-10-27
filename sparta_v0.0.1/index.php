<?php
date_default_timezone_set("America/New_York");
/* Initialization files and functions */
/* =========================================================== */
/* Load required files */
require 'assets/inc/db/config.php';
require 'assets/inc/functions/functions.php';
/* Global Variables */
/* =========================================================== */
 /* Deploy Google Recaptcha */
$deployRecaptcha = false;
 /* Create dateTime for expiration date */
$expire_date_string = "+2 days";
$expire_date = output_datetime(null,$expire_date_string);
$today_date = output_datetime(null,"now");
/* IP ADDRESS and token variables */
$ip_address = getIPAddress();
$ip_token_key = randomstring(rand(15,20));
/* Set COOKIE array values */
$cookie_IPTokenDate = array(
    'ip' => $ip_address, 
    'ip_token_key' => $ip_token_key, 
    'expire_date' => $expire_date, 
);
$cookie_token_encode = json_encode($cookie_IPTokenDate,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
$mztok = json_decode($_COOKIE['mztok'],true);
$ipFormToken = array(
    'formtoken' => $mztok['ip_token_key']
);
/*
 * $mztok['ip'];
 * $mztok['ip_token_key'];
 * $mztok['expire_date']; 
*/

/* =========================================================== */
    /* Make sure IP is in DB and its not flagged if not then create the cookie and send data to the database. */
    $ip_check = $db_conn->rawQueryOne('select * from ip_recorder where ip=?',array($ip_address));

    if ($ip_check['flagged'] != 1 && $ip_check['cookie_set'] < 7) {
        if ($ip_check) {
            $hit = Array(
                'hits' => $db_conn->inc(1)
            );
            $db_conn->where('ip',$ip_address);
            /* Return error if failed */
            if(!$db_conn->update('ip_recorder',$hit)){
                outputarray(null,'update failed: ' . $db_conn->getLastError());
            } 
            /* Make sure the cookie and ip values matches database values */
            if ($ip_check['ip_token_key'] == $mztok['ip_token_key'] && $ip_check['expire_date'] > $today_date){
                outputarray(null,'READY');
            } elseif ($ip_check['ip_token_key'] != $mztok['ip_token_key'] && $ip_check['expire_date'] > $today_date) {
                $expire_date = output_datetime("unix",$ip_check['expire_date']);
                setcookie("mztok", $cookie_token_encode, $expire_date); 
                /* Send ip token info to database */
                $updateToken = array( 
                    'ip_token_key' => $ip_token_key,
                    'cookie_set'=> $db_conn->inc(1),
                );
                $db_conn->where('ip',$ip_address);
                /* Return error if failed */
                if(!$db_conn->update('ip_recorder',$updateToken)){
                    outputarray(null,'update failed: ' . $db_conn->getLastError());
                }
                // outputarray(null,'KEY MISMATCH - DATE VALID');
            } else {
                $expire_date = output_datetime("unix",$expire_date_string);
                $expire_db_date = output_datetime(null,$expire_date_string);
                setcookie("mztok", $cookie_token_encode, $expire_date); 
                /* Send ip token info to database */
                $updateToken = array( 
                    'ip_token_key' => $ip_token_key,
                    'cookie_set'=> $db_conn->inc(1),
                    'expire_date' => $expire_db_date
                );
                $db_conn->where('ip',$ip_address);
                /* Return error if failed */
                if(!$db_conn->update('ip_recorder',$updateToken)){
                    outputarray(null,'update failed: ' . $db_conn->getLastError());
                }
                // outputarray(null,'REDEPLOYED - KEY MISMATCHED AND INVALID DATE');
            }

        } else {
            $expire_date = output_datetime("unix",$expire_date_string);
            $expire_db_date = output_datetime(null,$expire_date_string);
            /* Create cookie for the token */
            setcookie("mztok", $cookie_token_encode, $expire_date); 
            /* Send ip token info to database */
            $ipToken = array(
                'ip' => $ip_address, 
                'ip_token_key' => $ip_token_key,
                'hits' => 1,
                'cookie_set'=>1,
                'created_date'=> $today_date,
                'expire_date' => $expire_db_date
            );
            $send_ip = $db_conn->insert('ip_recorder',$ipToken);
            /* Return error if failed */
            if (!$send_ip){
                outputarray(null,'update failed: ' . $db_conn->getLastError());
            }
            // outputarray(null,'NEW');
        }
    } else {
        $deployRecaptcha = true;

    }
    

    
/* =========================================================== */
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]--> 
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>mobiuszero</title>
        <meta name="author" content="mobiuszero: Carlos Vargas">
        <meta name="description" content="This is the portfolio site for mobius zero. A web developer and designer that creates visually pleasing sites.">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">

        <style type="text/css">
            /* latin */ @font-face {font-family: 'Droid Sans'; font-style: normal; font-weight: 400; src: local('Droid Sans'), local('DroidSans'), url(https://fonts.gstatic.com/s/droidsans/v6/s-BiyweUPV0v-yRb-cjciPk_vArhqVIZ0nv9q090hN8.woff2) format('woff2'); unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000; } /* cyrillic-ext */ @font-face {font-family: 'Open Sans'; font-style: normal; font-weight: 700; src: local('Open Sans Bold'), local('OpenSans-Bold'), url(https://fonts.gstatic.com/s/opensans/v13/k3k702ZOKiLJc3WVjuplzK-j2U0lmluP9RWlSytm3ho.woff2) format('woff2'); unicode-range: U+0460-052F, U+20B4, U+2DE0-2DFF, U+A640-A69F; } /* cyrillic */ @font-face {font-family: 'Open Sans'; font-style: normal; font-weight: 700; src: local('Open Sans Bold'), local('OpenSans-Bold'), url(https://fonts.gstatic.com/s/opensans/v13/k3k702ZOKiLJc3WVjuplzJX5f-9o1vgP2EXwfjgl7AY.woff2) format('woff2'); unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116; } /* greek-ext */ @font-face {font-family: 'Open Sans'; font-style: normal; font-weight: 700; src: local('Open Sans Bold'), local('OpenSans-Bold'), url(https://fonts.gstatic.com/s/opensans/v13/k3k702ZOKiLJc3WVjuplzBWV49_lSm1NYrwo-zkhivY.woff2) format('woff2'); unicode-range: U+1F00-1FFF; } /* greek */ @font-face {font-family: 'Open Sans'; font-style: normal; font-weight: 700; src: local('Open Sans Bold'), local('OpenSans-Bold'), url(https://fonts.gstatic.com/s/opensans/v13/k3k702ZOKiLJc3WVjuplzKaRobkAwv3vxw3jMhVENGA.woff2) format('woff2'); unicode-range: U+0370-03FF; } /* vietnamese */ @font-face {font-family: 'Open Sans'; font-style: normal; font-weight: 700; src: local('Open Sans Bold'), local('OpenSans-Bold'), url(https://fonts.gstatic.com/s/opensans/v13/k3k702ZOKiLJc3WVjuplzP8zf_FOSsgRmwsS7Aa9k2w.woff2) format('woff2'); unicode-range: U+0102-0103, U+1EA0-1EF9, U+20AB; } /* latin-ext */ @font-face {font-family: 'Open Sans'; font-style: normal; font-weight: 700; src: local('Open Sans Bold'), local('OpenSans-Bold'), url(https://fonts.gstatic.com/s/opensans/v13/k3k702ZOKiLJc3WVjuplzD0LW-43aMEzIO6XUTLjad8.woff2) format('woff2'); unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF; } /* latin */ @font-face {font-family: 'Open Sans'; font-style: normal; font-weight: 700; src: local('Open Sans Bold'), local('OpenSans-Bold'), url(https://fonts.gstatic.com/s/opensans/v13/k3k702ZOKiLJc3WVjuplzOgdm0LZdjqr5-oayXSOefg.woff2) format('woff2'); unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000; } @font-face {font-family: 'Fredoka One'; font-style: normal; font-weight: 400; src: local('Fredoka One'), local('FredokaOne-Regular'), url(https://fonts.gstatic.com/s/fredokaone/v4/SL0aFUFfkFMMdariYQ3_YY4P5ICox8Kq3LLUNMylGO4.woff2) format('woff2'); unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000; } </style> <!-- Main styles -->
        <link rel="stylesheet" type="text/css" href="assets/css/style.css?v=0.6.1">

<!-- =================== Google Analytics ================== -->
            <script> (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o), m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m) })(window,document,'script','https://www.google-analytics.com/analytics.js','ga'); ga('create', 'UA-55537329-3', 'auto'); ga('send', 'pageview'); </script>
        <!-- =================== End Google Analytics ============== -->
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        
	<section id="title_section" class="title module">
	        <div id="main_menu" class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand logo" href="#title_section" data-menu="main_menu_link">&#60;MobiusZero &#92;&#62;</a>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar_mainmenu">
            <span class="sr-only">Toggle navigation</span>
            <i class="fa fa-bars fa-lg fa-inverse"></i>
            </button>
        </div>
        <div class="collapse navbar-collapse navbar_mainmenu">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#title_section" data-menu="main_menu_link">Home</a></li>
                <li><a href="#about" data-menu="main_menu_link">About</a></li>
                <li><a href="#portfolio" data-menu="main_menu_link">Portfolio</a></li>
                <li><a href="#contact" data-menu="main_menu_link">Contact</a></li>
            </ul>
        </div>
    </div>
</div>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h1 class="text-center logo">&#60;<span>MobiusZero &#92;&#62;</h1>
					<a href="#portfolio" data-menu="main_menu_link" class="btn btn-projects">View My Projects</a>
				</div>		 
			</div>
		</div>
	</section>
	<section id="about" class="about content module">
		<div class="container">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
		            <h2>A few words about me.</h2> 
		            <h4>I am a web designer that is based in Florida. My main focus is design minimalism. Its my obsession.</h4>
		            <p>I create beautiful, usable, professional websites using best practice accessibility and the latest W3C web standards guidelines, resulting in semantic and SEO friendly webpages. All my websites are lovingly hand coded in HTML 5 and CCS3.</p>
		            <p>In my toolbox I use Photoshop, Illustrator, and Fireworks for layout and asset building. Dreamweaver for email layout and coding. Node and Grunt for page building and modularization for different setups with the very kind help of sublime text 3. Finally, I use frameworks like bootstrap to help me in my quest to make outstanding looking websites.</p>
		            <p>I am currently teaching myself more php and javascript so I can create wordpress themes or more interesting looking pages. Though my two year plan that I have will have me circling around C++. I may dibble in Ruby on Rails. Stay tuned by <a data-menu="contact" href="#contact">following me.</a></p>
		            <p>I have practical knowledge of Infusionsoft to create small to large scale marketing funnels. So, if you need help with getting your marketing funnel in order I can be more than happy to help out.</p> 
				</div>
			</div>
		</div>
	</section>
	<section id="portfolio" class="portfolio content module">
		<div class="container">
			<div class="row"> 
				<div class="col-md-10 col-md-offset-1">
					<h2>My Portfolio</h2>
					<p>Here are some of my projects that I had worked on. Take a look and let me know what you think. I'll try to send updates on twitter if I have anything new that I have created that I want to add to my portfolio. You can also find me in codepen. I'll be doing some unique experiments on there.</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-4" >
							<div class="waypoint hidden">
								<figure>
									<img src="assets/img/world-chaos.png" alt="Opt-in Page: World Chaos" width="380" height="275" />
								</figure>
							</div>
						</div>
						<div class="col-md-4" >
							<div class="waypoint hidden">
								<figure>
									<img src="assets/img/theweeklyoptionstrader.png" alt="Opt-in Page: World Chaos" width="380" height="275" />
								</figure>
							</div>
						</div>
						<div class="col-md-4" >
							<div class="waypoint hidden">
								<figure>
									<img src="assets/img/24hourprofit.png" alt="Opt-in Page: World Chaos" width="380" height="275" />
								</figure>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-4" >
							<div class="waypoint hidden">
								<figure>
									<img src="assets/img/theweeklyoptionstrader_unsub.png" alt="Opt-Out Page: The Weekly Options Trader" width="380" height="275" />
								</figure>
							</div>
						</div>
						<div class="col-md-4" >
							<div class="waypoint hidden">
								<figure>
									<img src="assets/img/investing-lab_salespage.png" alt="Opt-in Page: World Chaos" width="380" height="275" />
								</figure>
							</div>
						</div>
						<div class="col-md-4" >
							<div class="waypoint hidden">
								<figure>
									<img src="assets/img/investingsignal_member_subscribe_minsite.png" alt="Investing Signal member subscribe minsite" width="380" height="275" />
								</figure>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>   
<section id="contact" class="contact content module">
		<div class="container">
			<div class="row"> 
				<div class="col-md-10 col-md-offset-1">
					<h2>Contact Me</h2>
					<p>Message me with any questions or inquires that you made have about what I can do for you or to simply chat but please allow me at least 24-48 hours to respond back to you. <a class="hidden" href="http://www.mobiuszero.com/hp/hungry.php"><!-- FRoZXJlIGlzIG5vIHBlYWNlIGFtb25nIG1lbiwgb25seSBiZWVyIGNhbiBkbyB0aGF0ISA= --></a></p> 

					<div class="center-block clearfix col-md-9">
						<form role="form" method="POST" action="assets/inc/process.php" accept-charset="UTF-8" id="contactmeform">
							<div class="first_name_field form-group">
								<label for="first_name">First Name:</label>
								<input id="first_name" name="first_name" type="text" class="form-control" placeholder="Enter Your First Name" value="" required />
								<div class="help-block with-errors"></div>
							</div>
							<div class="email_field form-group">
								<label for="email">Email:</label>
								<input id="email" name="email" type="email" class="form-control" placeholder="Enter Your Email Address - user@example.com" value="" required  />
								<div class="help-block with-errors"></div>
							</div>
							<div class="message_field form-group">
								<label for="message_subject" class="sr-only">Subject:</label>
								<input id="message_subject" name="message_subject" type="text" class="form-control" placeholder="" value="" />
							</div>
							<div class="message_field form-group">
								<label for="message">Message:</label>
								<textarea name="message" id="message" class="form-control" placeholder="Write Your Message Or Inquiries Here. Up To 450 Words Max." rows="10" value="" required></textarea>
								<div class="help-block with-errors"></div>
							</div>
							<?php 
								foreach ($ipFormToken as $token_name => $token_value) {
									echo "<input class=\"hidden\" type=\"text\" name=\"$token_name\" value=\"$token_value\" />\n";
								}
								/* Deploy Google Recaptcha */
								if ($deployRecaptcha) {
									echo "<div class=\"g-recaptcha\" data-sitekey=\"6LdT7ikTAAAAAJQR7u4LS6aVoWJmd2G9CVloaZYf\"></div>";
									echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
								} 
							?>
							<button type="submit" id="submitbutton" class="btn btn-lg btn-primary btn-block">Contact Me Now</button> 
						</form>	 
						<div id="statuscontrol" class="text-center alert alert-danger text-capitalize hidden"></div>
						<div id="thesuccessmessage" class="thesuccessmessage hidden">
							<h2 class="text-center">
								<i class="fa fa-4x fa-check-circle" aria-hidden="true"></i>
							</h2>
							<h4 class="text-center">Your Message Has Been Sent<span class="lead">!</span></h4>
						</div>
					</div>
					<h4 class="text-center">Come find me in the social areas of the internet!</h4>
					<ul class="text-center social_media">
                        <li><a class="social_media_links github" href="https://github.com/zero12o" target="_blank"><i class="fa fa-github"></i></a></li>
                        <li><a class="social_media_links linkedin" href="https://www.linkedin.com/in/carlos-vargas-16b41b107" target="_blank"><i class="fa fa-linkedin-square"></i></a></li>
                        <li><a class="social_media_links googleplus" href="https://plus.google.com/u/1/109886193538627695155/about" target="_blank"><i class="fa fa-google-plus-square"></i></a></li>
                        <li><a class="social_media_links twitter" href="https://twitter.com/mobiusZero12o" target="_blank"><i class="fa fa-twitter-square"></i></a></li>
                        <li><a class="social_media_links codepen" href="http://codepen.io/mobius_zero/" target="_blank"><i class="fa fa-codepen"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
	</section>  
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="text-center">
                            <li>MobiusZero &copy; <span id="year"></span> <script> var date = new Date(); var get_year = date.getFullYear(); document.getElementById("year").innerHTML = get_year; </script></li>
                        </ul>
                    </div> 
                </div>
            </div> 
        </footer>
        <!-- bootstrap plugins -->
        <script src="assets/js/scripts.min.js?v=3.3.5"></script>
        
    </body>
</html>