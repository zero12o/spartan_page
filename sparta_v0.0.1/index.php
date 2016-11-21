<?php
/* Initialization files and functions */
/* =========================================================== */
/* Load required files */
require 'assets/inc/db/config.php';
require 'assets/inc/vendor/vendor/autoload.php';
require 'assets/inc/functions/functions.php';
/* Global Variables */
/* =========================================================== */
 /* Deploy Google Recaptcha */
$deployRecaptcha = false;
 /* Create dateTime for expiration date */
$expire_date_string = "+3 days";
$expire_date = output_datetime(null,$expire_date_string);
// echo "Menu Issue ?";
// echo $expire_date;
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
$ip_check = $db_conn->rawQueryOne('select * from ip_recorder where ip=?',array($ip_address));
/* =========================================================== */
    /* Make sure IP is in DB and its not flagged if not then create the cookie and send data to the database. */

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
                setcookie("mztok", $cookie_token_encode, $expire_date, '/', null, null, true); 
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
                setcookie("mztok", $cookie_token_encode, $expire_date, '/', null, null, true); 
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
            setcookie("mztok", $cookie_token_encode, $expire_date, '/', null, null, true); 
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
// Database table call
$showPortfolioDBItems = $db_conn->JsonBuilder()->rawQuery("SELECT id, portfolioItemTitle, portfolioItemCategory, portfolioItemTechnology, portfolioThumbImage,portfolioItemDescription,portfolioItemCredits,portfolioItemDemoLink,portfolioThumbImage FROM portfolio_items");
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
        <!-- Main styles -->
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
		<div id="main-menu" class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header"> 
            <a class="navbar-brand logo" href="#title_section" data-menu="main-menu-link">&#60;MobiusZero &#92;&#62;</a>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".mainmenu">
            <span class="sr-only">Toggle navigation</span>
            <i class="fa fa-bars fa-lg fa-inverse"></i>
            </button>
        </div>
        <div class="collapse navbar-collapse mainmenu">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#title_section" data-menu="main-menu-link">Home</a></li>
                <li><a href="#about" data-menu="main-menu-link">About</a></li>
                <li><a href="#portfolio" data-menu="main-menu-link">Portfolio</a></li>
                <li><a href="#contact" data-menu="main-menu-link">Contact</a></li>
            </ul>
        </div>
    </div>
</div>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="logo-placement">
						<div class="logo-holder">
							<h1 class="text-center logo"><span>&#60;MobiusZero &#92;&#62;</h1>
							<a href="#portfolio" data-menu="main-menu-link" class="btn btn-projects">View My Projects</a>
						</div>
					</div>
				</div>		 
			</div>
		</div>
	</section>
	<section id="about" class="about content module">
		<div class="container">
			<div class="row">
				<div class="col-md-10 center-block">
					<article>
						<div class="content-item">
				            <h2>A few words about me.</h2> 
				            <h4>I am a web developer with a main focus in design minimalism. Its my obsession.</h4>
							<p>I am a web designer that is based in Florida. My main focus is design minimalism. Its my obsession.</p>

							<p>I create beautiful, usable, professional websites using best practice accessibility and the latest W3C web standards guidelines, resulting in semantic and SEO friendly webpages. All my websites are lovingly hand coded in HTML 5 and CCS3.</p>
							<p>In my toolbox I use Photoshop, Illustrator, and Fireworks for layout and asset building. Dreamweaver for email layout and coding. Node and Grunt for page building and modularization for different setups with the very kind help of sublime text 3. Finally, I use frameworks like bootstrap to help me in my quest to make outstanding looking websites.</p>
							<p>I am currently have introductory practical knowledge of php and javascript so I can create wordpress themes or more interesting looking pages. Though my two year plan that I have will have me circling around C++ or more focus direction with javascript.</p>
							<p>I have practical knowledge  with Infusionsoft to create small to large scale marketing funnels. I worked with the following ESP API's Get Response, Infusionsoft, Mail Chimp and Ongage.  So, if you need help with getting your marketing funnel in order or need a custom API solution. I can be more than happy to help out.</p>
						</div>
					</article>
				</div>
			</div>
		</div>
	</section>
	<section id="portfolio" class="portfolio content module">
		<div class="container">
			<div class="row"> 
				<div class="col-md-10 center-block">
					<article>
						<h2>My Portfolio</h2>
						<p>Here are some of my projects that I had worked on. Take a look and let me know what you think. I'll try to send updates on twitter if I have anything new that I have created that I want to add to my portfolio. You can also find me in codepen. I'll be doing some unique experiments on there.</p>
					</article>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div id="portifolioImgHolder" class="masonry-elememts">
							<div class="masonry-resize col-xs-12 col-sm-6 col-md-4"></div>
							<?php 
								$showItems = json_decode($showPortfolioDBItems);
								$arrLength = count($showItems);
								for ($i = 0; $i < $arrLength; $i++) { 
									echo "<div class=\"masonry-item col-xs-12 col-sm-6 col-md-4\"><div class=\"portfolio-bloc\"><figure> <img src=\"assets/img/portfolio/".$showItems[$i]->portfolioThumbImage."\" alt=\"".$showItems[$i]->portfolioItemTitle."\" class=\"img-responsive msny\" /><h4 class=\"portfolioTitle\">".$showItems[$i]->portfolioItemTitle."</h4><figcaption><span class=\"portfolioLearnMore\"><i class=\"fa fa-info-circle\" aria-hidden=\"true\"></i> Learn More</span><a class=\"modal-call\" id=\"".$showItems[$i]->id."\" href=\"#portfolioItemDisplay\" data-toggle=\"modal\" data-target=\"#portfolioItemDisplay\"></a></figcaption></figure></div></div>";
								} 
							?>
						</div>
					</div> 
				</div>
			</div>
		</div>
	</section>   
<section id="contact" class="contact content module">
		<div class="container"> 
			<div class="row"> 
				<div class="col-md-10 center-block">
					<h2>Contact Me</h2>
					<p>Message me with any questions or inquires that you made have about what I can do for you or to simply chat but please allow me at least 24-48 hours to respond back to you. <a class="hidden" href="http://www.mobiuszero.com/hp/hungry.php"><!-- FRoZXJlIGlzIG5vIHBlYWNlIGFtb25nIG1lbiwgb25seSBiZWVyIGNhbiBkbyB0aGF0ISA= --></a></p>
				</div>
				<div class="col-md-12 contact_form">
					<div class="col-md-7">
						<form role="form" method="POST" action="assets/inc/process.php" accept-charset="UTF-8" id="contactmeform">
							<div class="first_name_field form-group">
								<label for="first_name">First Name:</label>
								<div class="input-group">
									<span class="input-group-addon" id="personIcon"><i class="fa fa-user" aria-hidden="true"></i></span>
									<input id="first_name" name="first_name" type="text" class="form-control" placeholder="Enter Your First Name" value="" required aria-describedby="personIcon" />
								</div>
								<div class="help-block with-errors"></div>
							</div>
							<div class="email_field form-group">
								<label for="email">Email:</label>
								<div class="input-group">
									<span class="input-group-addon" id="emailIcon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
									<input id="email" name="email" type="email" class="form-control" placeholder="Enter Your Email Address - user@example.com" value="" required aria-describedby="emailIcon" />
								</div>
								<div class="help-block with-errors"></div>
							</div>
							<div class="message_subject_field form-group">
								<label for="message-subject" class="sr-only">Subject:</label>
								<input id="message-subject" name="message_subject" type="text" class="form-control" placeholder="" value="" />
							</div>
							<div class="message_field form-group">
							<label for="message">Message:</label>
							<div class="input-group">
								<span class="input-group-addon" id="messageIcon"><i class="fa fa-pencil" aria-hidden="true"></i></span>
								<textarea name="message" id="message" class="form-control" placeholder="Write Your Message Or Inquiries Here. Up To 450 Words Max." rows="10" value="" required aria-describedby="messageIcon"></textarea> 
							</div>
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
						<div id="robotStatusMsg" class="robotStatusMsg hidden">
							<div class="robotThoughtCloud">
								<h4 class="text-center">Thanks I will message you as soon as I can.</h4>
							</div>
							<img src="assets/img/statusBot.svg" alt="robotStatusMsg" class="img-responsive center-block" />
						</div>
					</div>
					<div class="col-md-5">
						<h4 class="text-center">Come find me in the social areas of the internet!</h4>
						<ul class="text-center social_media">
	                        <li><a class="social-media-links github" href="https://github.com/zero12o" target="_blank"><i class="fa fa-github"></i></a></li>
	                        <li><a class="social-media-links linkedin" href="https://www.linkedin.com/in/carlos-vargas-16b41b107" target="_blank"><i class="fa fa-linkedin-square"></i></a></li>
	                        <li><a class="social-media-links googleplus" href="https://plus.google.com/u/1/109886193538627695155/about" target="_blank"><i class="fa fa-google-plus-square"></i></a></li>
	                        <li><a class="social-media-links twitter" href="https://twitter.com/mobiusZero12o" target="_blank"><i class="fa fa-twitter-square"></i></a></li>
	                        <li><a class="social-media-links codepen" href="http://codepen.io/mobius_zero/" target="_blank"><i class="fa fa-codepen"></i></a></li>
						</ul>
					</div>					
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
        <!-- ======================  -->
        <!-- Modal -->
<div class="modal fade" id="portfolioItemDisplay" data-id="" tabindex="-1" role="dialog" aria-labelledby="portfolioItem">
  <div class="modal-dialog full-page-dialog" role="document">
    <div class="modal-content">
      <div id="modalOverlay" class="modalOverlay">
        <h3 class="modalStatusMsg"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></h3>
      </div>
        <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times fa-2x" aria-hidden="true"></i></button>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="portfolioItemInfoWrapper col-md-4">
              <div class="col-md-11 center-block">
                <h4 class="portfolioTitle"></h4>
                <div class="portfolioThumbnail thumbnail">
                  <img src="assets/img/placeHolder320x400.png" width="100%" alt="" />
                </div>
                <div class="portfolioMetaInfo"></div>
              </div>
            </div>
            <div class="portfolioItemContentWrapper col-md-8">
              <div class="col-md-10 center-block">
                <div class="portfolioDescriptionContent"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
        <!-- ======================  -->
        <!-- bootstrap plugins -->
        <script src="assets/js/scripts.min.js?v=3.3.7"></script>
         
    </body>
</html>