
<?php
$facebook = new Facebook(array(
  'appId'  => '358428497523493',
  'secret' => '497eb1b9decd06c794d89704f293afdd',
));
$user = $facebook->getUser();
$this->load->model('users');
if ($user) {
//$logoutUrl2 = $this->tank_auth->logout();
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me'); 
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}
if($user)
{
$fb_user_country_city = explode(",",$user_profile['location']['name']);

$city_fb_user = trim($fb_user_country_city[0]);

$country_fb_user = trim($fb_user_country_city[1]);

$fetch_country_result = $this->users->fetch_country_id($country_fb_user);

$fetch_city_result = $this->users->fetch_city_id($city_fb_user);

//$country = $fetch_country_result['country']['country_id'];

//$city = $fetch_city_result['city']['city_id'];

$update_fb_profile = array(
'country_id' => $fetch_country_result,
'city_id' => $fetch_city_result,
'full_name' => $user_profile['name'] 
);
$update['facebook'] = $this->users->update_facebook_profile($update_fb_profile);
}
//print_r($this->session->userdata);
?>
<body>
<!-- Load Pop-up for pic upload -->
<?php //echo $fb_user = $facebook->getUser(); ?>

<?php if($profile_pic['curr_educ_level'] == '0' || $profile_pic['prog_parent_id'] == '0' || $profile_pic['country_id'] == '0') { ?>
<script>
$(window).load(function(){
    $('#myModal').modal({
        keyboard: false
    })
});
</script>





<div id="myModal" class="model_back modal hide fade">
	<div class="modal-header no_border model_heading">
		<a class="close" data-dismiss="modal">x</a>
		<h3>Your Profile Information</h3>
	</div>
	<div class="modal-body model_body_height">
		<form method="post" action="home" enctype="multipart/form-data">
			<div>
				<div class="float_l span2 margin_zero">
				
				<?php
							if($profile_pic['user_pic_path'] != '')
							{
							echo "<img src='".base_url()."uploads/".$profile_pic['user_pic_path']."'/>";
							}
							else if($user)
							{
							?>
								<img src="https://graph.facebook.com/<?php echo $user; ?>/picture?type=large">
							<?php
							}
							else{
							echo "<img src='".base_url()."images/profile_icon.png'/>";
							}
							?>
				</div>
				<div class="float_l span3 margin_l12 margin_t50"><h4>Upload Your Picture</h4><div class="span2 margin_zero"><input type="file" name="userfile" /><br />
			</div>
			</div>
				<div class="clearfix"></div>
			</div>
			<div id="show_img_bar" class="img_bar_profile_modal">
			<img src="<?php echo "$base$img_path" ?>/ajax-loader.gif"/>
			</div>
			<div class="margin_t">
				<div class="float_l span2 margin_zero"><h4>Gender</h4></div>
				<div class="float_l span3 margin_l12"><input type="radio" name="sex" value="male" /> Male
					<input type="radio" name="sex" value="female" /> Female</div>
				<div class="clearfix"></div>
			</div>
			<div class="margin_t">
				<div class="float_l span2 margin_zero"><h4>Current Educational level</h4></div>
				<div class="float_l span3">
				<div class="controls">
					<!--<input type="text" class="input-medium" id="input01">-->
					<?php $curent_quali = $fetch_profile['curr_educ_level']; ?>
					<select name="educ_level">
					<option value="">Select</option>
					<?php
									foreach($educ_level as $level)
									{
									if($level['prog_edu_lvl_id'] == $curent_quali) { $selected ='selected';} else { $selected =''; }
									?>
									<option value="<?php echo $level['prog_edu_lvl_id']; ?>" <?php echo $selected; ?> > <?php echo $level['educ_level']; ?>  </option>
									<?php } ?>
					</select>
				</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="margin_t">
				<div class="float_l span2 margin_zero"><h4>Area of Interest</h4></div>
				<div class="float_l span3 margin_l12">
					<div class="controls">
					<?php $intrest_area_profile = $fetch_profile['prog_parent_id']; ?>
					<select name="area_interest">
					<option value="0">Select</option>
					<?php foreach($area_interest as $interest) 
						{
						if($interest['prog_parent_id'] == $intrest_area_profile) { $selected ='selected';} else { $selected =''; }
					?>
					<option value="<?php echo $interest['prog_parent_id']; ?>" <?php echo $selected; ?>><?php echo $interest['program_parent_name']; ?></option>
										
					<?php } ?>
					</select>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="margin_t">
				<div class="float_l span2 margin_zero"><h4>Country</h4></div>
				<div class="float_l span3 margin_l12">
					<div class="controls">
					<?php $user_selected_country = $fetch_profile['country_id']; 
						$selected ='';
						?>
					<select name="countries">
					<option value="">Select</option>
					<?php
										//print_r($country);
										foreach($country as $countries)
										{
										if($countries['country_id'] == $user_selected_country) { $selected ='selected';} else { $selected =''; }
									?>
										<option value="<?php echo $countries['country_id'] ;?>" <?php echo $selected; ?>><?php echo $countries['country_name']; ?></option>
										
										<?php } ?>
					</select>
						</div>
				</div>
					<div class="clearfix"></div>
			</div>
			
			<div class="margin_t">
				<div class="controls">
					<input type="submit" class="btn btn-primary" name="upload" id="upload" value="Continue" >
				</div>
			</div>
		</form>
	</div>
</div>

</div>
</div>

				<!--<div id="mask"></div>-->


<?php } ?>
<!-- Start of Login Dialog -->  

<!-- Load Pop-up for pic upload End Here -->

<div class="modal" id="show_success_update_profile" style="display:none;" >
  <div class="modal-header">
    <a class="close" data-dismiss="modal"></a>
    <h3>Message For You</h3>
  </div>
  <div class="modal-body">
    <p><center><h4>Your Profile has been updated.....</h4></center></p>
  </div>
  <div class="modal-footer">
    <!--<a href="#" class="btn">Close</a>-->
    <!--<a href="#" class="btn btn-primary">Save changes</a>-->
  </div>
</div>



	<div>
		<div class="body_bar"></div>
		<div class="body_header"></div>
		<div class="body_container">
			<div class="row">
				<div class="margin_zero span16">
					<div class="alert alert-message message" data-alert="alert">
								<a class="close" data-dismiss="alert">&times;</a>
								<div>
									<div class="float_l"><h2>Welcome! Let&#8217;s get started by</h2></div>
									<div class="float_r close_cont"> <span> Don't want our help? </span> Close Tips </div>
									<div class="clearfix"></div>
								</div>
								<nav id="help-tools">
									<ul>
										<li class="text_dec">1) Step 1</li>
										<li><a href="#">2) Step 2</a></li>
										<li><a href="#">3) Step 3</a></li>
										<li><a href="#">4) Step 4</a></li>
									</ul>
								</nav>
					</div>
					<?php $this->load->view('user/profile-sidebar.php'); ?>
					<div class="span13 float_r">
						<div class="span10 margin_zero">
							<div class="search_box_profile">
								<span class="">My Country</span>
								<input type="text" class="input_xxx-large search-query">
								<button class="btn btn-success margin_l21" href="#">Search</button>
							</div>
							<div class="events_box margin_t">
								<h2>Events</h2>
								<ul>
									<li>Barnes, H.M. 2012. Durable composites: An overview. Proceedings, American Wood in floodplain lakes of the Mississippi Alluvial Valley. Environmental Biology of Fishes.<src="<?php echo "$base$img_path";  ?>event_arrow.png"></li>
									<li>Dembkowski, D.J., L.E. Miranda. 2012. Hierarchy in factors affecting fish biodiversity Supplemental treatments for timber bridge components. Forest Products Journal.<src="<?php echo "$base$img_path";  ?>event_arrow.png"></li>
									<li>Barnes, H.M. 2012. Durable composites: An overview. Proceedings, American Wood <src="<?php echo "$base$img_path";  ?>event_arrow.png"></li>
									<li>Dembkowski, D.J., L.E. Miranda. 2012. Hierarchy in factors affecting fish biodiversity.<src="<?php echo "$base$img_path";  ?>event_arrow.png"></li>
								</ul>
							</div>
							<div class="margin_t news_box">
								<div class="span5 margin_zero">
									<h2>Study Abroad</h2>
									<div>
										<ul class="study_point">
											<li>
												<div class="float_l count">
													<div class="float_l"><a href="#" class="study_content">USA</a></div>
													<div class="float_r"><img src="<?php echo "$base$img_path"; ?>/us.png"></div>
													<div class="clearfix"></div>
												</div>
												<div class="float_l count">
													<div class="float_l"><a href="#" class="study_content">UK</a></div>
													<div class="float_r"><img src="<?php echo "$base$img_path"; ?>/gb.png"></div>
													<div class="clearfix"></div>
												</div>
												<div class="clearfix"></div>
											</li>
											<li>
												<div class="float_l count">
													<div class="float_l"><a href="#" class="study_content">Canada</a></div>
													<div class="float_r"><img src="<?php echo "$base$img_path";  ?>/ca.png"></div>
													<div class="clearfix"></div>
												</div>
												<div class="float_l count">
													<div class="float_l"><a href="#" class="study_content">Korea</a></div>
													<div class="float_r"><img src="<?php echo "$base$img_path";  ?>/kr.png"></div>
													<div class="clearfix"></div>
												</div>
												<div class="clearfix"></div>
											</li>
											<li>
												<div class="float_l count">
													<div class="float_l"><a href="#" class="study_content">India</a></div>
													<div class="float_r"><img src="<?php echo "$base$img_path";  ?>/india.png"></div>
													<div class="clearfix"></div>
												</div>
												<div class="float_l count">
													<div class="float_l"><a href="#" class="study_content">Online</a></div>
													<div class="float_r"><img src="<?php echo "$base$img_path";  ?>/ol.png"></div>
													<div class="clearfix"></div>
												</div>
											</li>
										</ul>
									</div>
								</div>
								<div class="float_l right_border"></div>
								<div class="span5 margin_zero">
									<h2>News</h2>
										<ul>
											<li>Barnes, H.M. 2012. Durable composites: An overview. Proceedings, American Wood.<src="<?php echo "$base$img_path";  ?>event_arrow.png" class="news_arrow"></li>
											<li>Dembkowski, D.J., L.E. Miranda. 2012. Hierarchy in factors affecting fish biodiversity.<src="<?php echo "$base$img_path";  ?>event_arrow.png" class="news_arrow"></li>
											<li>Barnes, H.M. 2012. Durable composites: An overview. Proceedings, American Wood.<src="<?php echo "$base$img_path";  ?>event_arrow.png" class="news_arrow"></li>
										</ul>
								</div>
								<div class="clearfix"></div>
								</div>
								<div class="margin_t">
									<div class="news_box">
										<h2>Question and Answer</h2>
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a dictum arcu. Vestibulum ultrices lacus in velit posuere sit amet elementum metus fringilla. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut at quam id velit pulvinar rutrum. Cras gravida velit id augue viverra eget tempus mauris mollis.Ut at quam id velit. </p>
									</div>
								</div>
						</div>
						<div class="span3 float_l">
							<img src="<?php echo "$base$img_path";  ?>/banner_img.png">
						</div>
						<div class="clearfix"></div>
						<div class="margin_t">
							<div class="grid_2 margin_zero artical_box">
								<div class="index_sidebar_box">
									<div class="artical_heading">Article</div>
										<div id="home" class="box artical_box_data">
											<div class="float_l margin_r1">
												<img src="<?php echo "$base$img_path";  ?>/layer.png">
											</div>
											<div class="margin_l8">
												Aenean id ipsum nec lorem commodo imperdiet euismod dictum erat. Praesent eu nisl at eros vulputate fringilla vel rdiet od vestibulum felis aesent eu.Aenean id ipsum nec lorem commodo imperdiet euismod dictum erat. Praesent eu nisl at eros vulputate fringilla vel rdiet od vestibulum felis aesent eu nisl at eros vulputate fringilla uismod dictum. 
											</div>							
										</div>
									<div class="clearfix"></div>
								</div>
							</div>
							<div class="grid_2 artical_box">
								<div class="index_sidebar_box">
									<div class="artical_heading">Article</div>
										<div id="home" class="box artical_box_data">
											<div class="float_l margin_r1">
												<img src="<?php echo "$base$img_path";  ?>/layer.png">
											</div>
											<div class="margin_l8">
												Aenean id ipsum nec lorem commodo imperdiet euismod dictum erat. Praesent eu nisl at eros vulputate fringilla vel rdiet od vestibulum felis aesent eu.Aenean id ipsum nec lorem commodo imperdiet euismod dictum erat. Praesent eu nisl at eros vulputate fringilla vel rdiet od vestibulum felis aesent eu nisl at eros vulputate fringilla uismod dictum. 
											</div>							
										</div>
									<div class="clearfix"></div>
								</div>
							</div>
							<div class="grid_2 artical_box">
								<div class="index_sidebar_box">
									<div class="artical_heading">Article</div>
										<div id="home" class="box artical_box_data">
											<div class="float_l margin_r1">
												<img src="<?php echo "$base$img_path";  ?>/layer.png">
											</div>
											<div class="margin_l8">
												Aenean id ipsum nec lorem commodo imperdiet euismod dictum erat. Praesent eu nisl at eros vulputate fringilla vel rdiet od vestibulum felis aesent eu.Aenean id ipsum nec lorem commodo imperdiet euismod dictum erat. Praesent eu nisl at eros vulputate fringilla vel rdiet od vestibulum felis aesent eu nisl at eros vulputate fringilla uismod dictum. 
											</div>							
										</div>
									<div class="clearfix"></div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<div>
    <div id="pwd-change-msg" class="modal hide fade" style="display: none; ">
     <div class="modal-header ">
      <a class="close" data-dismiss="modal">x</a>
      <h3>Your Password hash been changed successfully </h3>
     </div>
    </div>
   </div> 
   
<script>
<?php if($pwd_change=='pwd_change'){ ?>
$('#pwd-change-msg').modal('toggle');
<?php } ?>

<?php if($pwd_change=='pus'){ ?>
$('#show_success_update_profile').css('display','block');
$("#show_success_update_profile").delay(2500).fadeOut(200);
<?php } ?>
</script>
<script>
$('#upload').click(function(){
$('.img_bar_profile_modal').css('display','block').css('z-index','999').css('position','absolute');
});
</script>