<?php
//$this->load->library('facebook'); 
/****************************************** 
 *  Load Model and session library and    *
 *  Object                                *
 ******************************************/
  
  $this->load->model('users');
  $this->ci =& get_instance();
  $this->ci->load->config('tank_auth', TRUE);
  $this->ci->load->library('session');
 
$facebook = new Facebook(array(
  'appId'  => '358428497523493',
  'secret' => '497eb1b9decd06c794d89704f293afdd',
));
$base = base_url();
//$base['fb_redirect'] = "'".base_url()."'/auth/facebook";
$user = $facebook->getUser();
if ($user) {
$logoutUrl2 = $this->tank_auth->logout();
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}
if ($user) {
/****************************************** 
 * Logout Link for Facebook and MU site   *
 ******************************************/
	/******* Logout Link of Facebook and Site *******/
  $logoutUrl = $facebook->getLogoutUrl();
  
  
  /********************************************* 
 * Session start for validate in welcome Model *
 *  facebbok login                             *
 ***********************************************/
  $_SESSION['fb'] = 'Facebook';
  
  /****************************************** 
 * Set session and Loged in user after      *
 *  facebbok login                          *
 ******************************************/
  
  $fbdata = array(
  'fullname'	=> $user_profile['name'],
  'createdby' => 'self',
  'level'     => '1',
  'email'		=> $user_profile['email'],
  'activated'  => '1',
  'createdby_user_id'  => '0',
  //'last_ip'	=> $this->ci->input->ip_address(),
  );
  $fb_email = $user_profile['email'];
  $fb_name = $user_profile['name'];

/**************************************** 
 *  Check if current facebook uesr      *
 *  email is available or not           *
 ****************************************/
  $fb_return_num_rows = $this->users->check_facebook_email($fb_email);
  if($fb_return_num_rows == 0)
  {
  /**************************************** 
 *  Insert data from facebook             *
 *  if user is new for this site from FB  *
 ****************************************/
  $data['fb_user_id'] = $this->users->facebook_insert($fbdata);
  $data_fb_id = trim($data['fb_user_id']);
  $this->ci->session->set_userdata(array(
						 'user_id'	=> $data_fb_id,
						 'username'	=> $fb_name,
						 'status'	=> STATUS_ACTIVATED,
						 ));
						 
  }
  else if($fb_return_num_rows > 0)
  {
  /**************************************** 
 *  Fetch facebook user id from db        *
 *  for store in session                  *
 ****************************************/
 //echo $fb_email;
	$get_fb_user_id['query'] = $this->users->fetch_fb_user_id($fb_email);
	//print_r($get_fb_user_id);
	$fb_user_id = trim($get_fb_user_id['query']['id']);
	//echo $fb_user_id;
	$this->ci->session->set_userdata(array(
						 'user_id'	=> $fb_user_id,
						 'username'	=> $fb_name,
						 'status'	=> STATUS_ACTIVATED,
						 ));
						 //redirect('');
  } //redirect('');
} else {
/**************************************** 
 *  Login URL of Facebook with perms    *
 *                                      *
 ****************************************/
  $loginUrl = $facebook->getLoginUrl(array(
                'scope'         => 'email,offline_access,publish_stream,user_birthday,user_location,user_work_history,user_about_me,user_hometown',
                //'redirect_uri'      => $base['url'],
            ));
	$_SESSION['fb'] = '';
	   }
	  //print_r($this->session->userdata);
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="author" content="">
<title>Project</title>
<link rel="stylesheet" href="<?php echo "$base$css_path"?>/bootstrap.css">
<link rel="stylesheet" href="<?php echo "$base$css_path"?>/style.css">
<link rel="stylesheet" href="<?php echo "$base$css_path"?>/style_sh.css">
<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>
  FB.init({appId: '358428497523493', status: true, cookie: true, xfbml: true});
  FB.Event.subscribe('auth.sessionChange', function(response) {
    if (response.session) {
      // A user has logged in, and a new cookie has been saved
        window.location.reload(true);
    } else {
      // The user has logged out, and the cookie has been cleared
    }
  });
</script>
<script src="<?php echo "$base$js";?>/jquery.js"></script>
<script type="text/javascript" src="<?php echo "$base$js";?>/bootstrap-collapse.js"></script>
<script type="text/javascript" src="<?php echo "$base$js";?>/bootstrap-dropdown.js"></script>
<script src="<?php echo "$base$js";?>/bootstrap-alerts.js"></script>
<script type="text/javascript" src="<?php echo "$base$js";?>/bootstrap-modal.js"></script>
<script type="text/javascript" src="<?php echo "$base$js";?>/pic_upload_js.js"></script>
<script type="text/javascript" src="<?php echo "$base$js";?>/bootstrap-button.js"></script>
<script type="text/javascript">
$(document).ready(function(){
 $('#pulse').click(function(){
$('#myModal').modal('toggle');});
 });
</script>
</head>
<body>
<div id="fb-root"></div>
	<header>
		<div class="header_bar">	
			<div class="container">
				<div class="row">
					<div class="span4 offset9">
						<div class="bar">
						<?php
						if($this->ci->session->userdata('status')){ ?>
						<?php if($user) { ?>
						<a href="<?=$logoutUrl ?>" onclick="<?=$logoutUrl2 ?>"><img src="<?php echo "$base$img_path" ?>/facebook_logout_button.png"/> </a>
						<?php } else { ?>	
						<a href="<?php echo $base ?>logout"><div class="login">Hi <?php echo ucwords($this->ci->session->userdata('fullname')); ?></div> <div class="login">Logout</div></a>
						<?php } } else { ?>
							<a href="<?php echo $base ?>login"><div class="login">Login</div></a>
							<a href="<?php echo $base ?>register"><div class="signup">Signup</div></a>
							<span id="fb_button">
							<fb:login-button   perms="email,user_checkins" id="fb_butonek" onlogin="window.location.reload(true);"></fb:login-button>
							<img src="<?php echo "$base$img_path" ?>/inconnect.png" />
							</span>
							<?php } ?>
						
							
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="logo_holder">
			<div class="container">
				<div class="margin">
					<div class="row"><!--LOGO-->
						<div class="span4">
						<a href="<?php echo $base; ?>">	<img src="<?php echo "$base$img_path" ?>/logo.png" /></a>
						</div>
						<div class="span6 float_r">
							<img src="<?php echo "$base$img_path" ?>/banner.png" />
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="row">
						<div class="main_menu">
							<div class="float_r span9">
								<ul class="menu">
									<li><a href="#" class="active">Home</a></li>
									<li><a href="#">Colleges</a></li>
									<li><a href="#">Study Abroad</a></li>
									<li><a href="#">Questions & Answers</a></li>
									<li><a href="#">Events</a></li>
									<li class="padding_beta" style="border:none;"><a href="#">News</a></li>
								</ul>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>
	<style>
	#fb_butonek a
{
background: url("http://workforcetrack.in/images/fbconnect.png") no-repeat;

width:20px; /*my image width*/

height:19px; /*my image height*/
}

#fb_butonek a span
{
border:0px none;
background: none;
display:none;
}
	</style>
	<!--<div>
		<div class="body_bar"></div>
		<div class="body_header"></div>
		<div class="body"> -->