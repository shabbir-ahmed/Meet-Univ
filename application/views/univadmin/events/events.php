<div class="modal hide" id="alert">
	<div class="modal-header">		
		<div align="center" style="color:red;"><h3>You have reached maximum limit for show event</h3></div>
	</div>
</div>
<div class="modal hide" id="delete">
	<div class="modal-header">		
		<div align="center"><h3>Event deleted successfully</h3></div>
	</div>
</div>
<div class="modal hide" id="approved">
	<div class="modal-header">		
		<div align="center"><h3>Event approved successfully</h3></div>
	</div>
</div>
<div class="modal hide" id="disapproved">
	<div class="modal-header">		
		<div align="center"><h3>Event disapproved successfully</h3></div>
	</div>
</div>
<div class="modal hide" id="featured">
	<div class="modal-header">		
		<div align="center"><h3>Event featured successfully</h3></div>
	</div>
</div>
<div class="modal hide" id="unfeatured">
	<div class="modal-header">		
		<div align="center"><h3>Event unfeatured successfully</h3></div>
	</div>
</div>
<div class="modal hide" id="sel_atl_one">
	<div class="modal-header">		
		<div align="center"><h3>please select atleast one event</h3></div>
	</div>
</div>
<div class="modal hide" id="sel_act">
	<div class="modal-header">		
		<div align="center"><h3>please select the action</h3></div>
	</div>
</div>
<div class="modal hide" id="denied">
	<div class="modal-header">		
		<div align="center" style="color:red;"><h3>Unable to perform action please contact admin</h3></div>
	</div>
</div>
<div id="content" class="content_msg" style="display:none;z-index:99;position:absolute;left:190px;">
	<div class="span8 margin_t">
		<div class="message success"><p class="info_message"></p>
		</div>
	</div>
</div>
<meta charset="utf-8">
<meta name="description" content="">
<meta name="viewport" content="width=device-width">
<?php
$edit=0;
$delete=0;
$view=0;
$insert=0;
$event_edit_op=array('3','6','7','10');
$event_delete_op=array('5','7','8','10');
$event_insert_op=array('4','6','8','10');

foreach ($admin_priv as $admin_priv_res){ 
	if($admin_priv_res['privilege_type_id']=='2' && $admin_priv_res['privilege_level']!=0)
	{
		$view=1;
		if(in_array($admin_priv_res['privilege_level'],$event_edit_op))
		{
			$edit=1;
		}
		if(in_array($admin_priv_res['privilege_level'],$event_delete_op))
		{
			$delete=1;
		}
		if(in_array($admin_priv_res['privilege_level'],$event_insert_op))
		{
			$insert=1;
		}
	}
}
?>
<!-- BEGIN Content -->
  <div class="content">
    <div class="container-fluid">
      <div class="responsible_navi"></div>
      <div class="row-fluid">
        <div class="span12">
          <div class="page-header clearfix tabs">
            <h2>Events</h2>
            <ul class="nav nav-pills">
			<li class='active'>
                <a href="#cal" data-toggle="pill">View Calendar</a>
            </li>
              <li>
                <a href="#all" data-toggle="pill">All</a>
              </li>
              <li>
                <a href="#new" data-toggle="pill">New</a>
              </li>
			  <li id="active_menu">
                <a href="#create" data-toggle="pill">Create Events</a>
              </li>
            </ul>
          </div>
          <div class="content-box">
            <div class="tab-content">
			<!--start cal tab -->
				<div class="tab-pane active" id="cal">
					<div class="content-box">
						<div class="calendar"></div>
					</div>
				</div>
			<!--end cal tab -->
			<!--start all tab -->
              <div class="tab-pane" id="all">
                <table class="responsive table table-striped dataTable" id="media">				
                  <thead>
                    <tr class="for_remove_class_all">
						<th width="5%" class="event_title_class_all" ><input type="checkbox" name="sel_rows" class='sel_rows' data-targettable="media"></th>
						<th width="20%">Events Title</td>
						<th width="25%">University Name</th>
						<th width="15%">Event's Country</th>
						<th width="15%">Event Date</th>
						<th width="20%" class="event_title_class_all" >Choose Option</th>
                    </tr>
                  </thead>
                  <tbody>					
					<?php
						foreach($events_info as $row)
						{
					?>	
						<tr id="check_university_<?php echo $row->event_id;?>" class="check_university_<?php echo $row->event_id;?>">
							<td><input type="checkbox" value="<?php echo $row->event_id;?>" name="check_event_<?php echo $row->event_id; ?>" class='selectable_checkbox setchkval'  id="check_event_<?php echo $row->event_id; ?>"></td>							
							<td><?php if($row->event_title){ echo ucwords(substr($row->event_title,0,50)); } else { echo "<span style='color:#000;'>Not Available</span>"; } ?></td>
							<td><?php echo ucwords($row->univ_name); ?></td>
							<td><?php if($row->cityname!=''){ echo ucwords($row->cityname); } else { echo '<span style="color:#000;">Not Available</span>'; }?></td>
							<td class="center"><?php echo ucwords($row->event_date_time); ?></a></td>
							<td class="options">
							<div class="btn-group">
								<?php if($view==1) { ?>
								<a href="<?php echo $base;?>newadmin/admin_events/view_event/<?php echo $row->event_id;?>" class="btn btn-icon tip" data-original-title="View">
									<i class="icon-ok"></i>
								</a>
								<?php } if($edit==1){ ?>
								<a href="<?php echo $base;?>newadmin/admin_events/view_event/<?php echo $row->event_id;?>" style="display:none" class="btn btn-icon tip" data-original-title="Edit">
									<i class="icon-pencil"></i>
								</a>
								<?php } if($delete==1)   {?>
								<div class="modal hide" id="myModal_<?php echo $row->event_id; ?>">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">x</button>
										<h3>Do you want to delete?</h3>
									</div>
									<div class="modal-footer">
										<a href="javascript:void(0);" onclick="delete_confirm('<?php echo $row->event_id; ?>')" class="btn" data-dismiss="modal">Yes</a>
										<a href="javascript:void(0);" class="btn" data-dismiss="modal">Close</a>
									</div>
								</div>						
								<a href="#myModal_<?php echo $row->event_id; ?>" class="btn btn-icon tip"  data-toggle="modal" data-original-title="Delete">
									<i class="icon-trash"></i>
								</a>
								<?php }	if(($edit==1 || $delete==1 || $insert==1) ){  ?>																	
								<div class="modal hide" id="myAppDisModal_<?php echo $row->event_id; ?>">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">x</button>										
											<h3>Do you want to change status ?</h3>	
									</div>
									<div class="modal-footer">										
										<a id="ahc_<?php echo $row->event_id; ?>_<?php echo $row->ban_event; ?>" href="javascript:void(0)" onclick="approve_home_confirm(this);" class="btn" data-dismiss="modal">Yes</a>
										<a href="javascript:void(0)" class="btn" data-dismiss="modal">Close</a>
									</div>
								</div>							
								<a id="a_<?php echo $row->event_id; ?>" href="#myAppDisModal_<?php echo $row->event_id; ?>" class="btn btn-icon tip" <?php if($row->ban_event == 1){ ?> data-original-title="Approved" <?php } else { ?> data-original-title="Disapproved" <?php } ?> data-toggle="modal" >
									<i id="icon_<?php echo $row->event_id; ?>" class="<?php if($row->ban_event == 1){ echo 'icon-blue'; }?> icon-fire"></i>
								</a>
								<?php 
								$event_title=$this->subdomain->process_url_title(substr($row->event_title,0,50));
								$event_link=$this->subdomain->genereate_the_subdomain_link($row->subdomain_name,'event',$event_title,$row->event_id);									
								?>
								<a href="<?php echo $event_link ; ?>" class="btn btn-icon tip" data-original-title="Preview">
									<i class="icon-film"></i>
								</a>
								<?php if($admin_user_level ==5){  ?>
								<div class="modal hide" id="myFeaUnfModal_<?php echo $row->event_id; ?>">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">x</button>
										<h3>Do you want to change status ?</h3>											
									</div>
									<div class="modal-footer">
										<a id="mhf_<?php echo $row->event_id; ?>_<?php  echo $row->featured_home_event; ?>" href="javascript:void(0)" onclick="featured_home_confirm(this);" class="btn" data-dismiss="modal">Yes</a>
										<a href="javascript:void(0)" class="btn" data-dismiss="modal">Close</a>
									</div>
								</div>											
								<a id="mhf_a_<?php echo $row->event_id; ?>" href="#myFeaUnfModal_<?php echo $row->event_id; ?>"  class="btn btn-icon tip" <?php if($row->featured_home_event){ ?> data-original-title="Featured" <?php } else { ?> data-original-title="Unfeatured" <?php } ?> data-toggle="modal">
									<i id="mhf_icon_<?php echo $row->event_id; ?>" class="<?php if($row->featured_home_event){ echo 'icon-blue'; }?> icon-star"></i>
								</a>
								<?php } ?>
								<?php } ?>
							</div>
						</td>				
                     </tr> 
					<?php 
						} 
					?>					 
                  </tbody>
                </table>
				<?php if($delete==1) { ?> 	
				<div class="tableactions" style="margin-top:70px;">
					<select name="univ_action" id="univ_action">
						<option value="">Actions</option>
						<option value="delete">Delete</option>
					</select>				
					<input type="button" onclick="action_formsubmit(0,0)" class="submit tiny" value="Apply to selected" />
				</div>	
				<?php } ?> 	
			  </div>
			  <!--end cal tab -->
			  <!--start new tab -->
              <div class="tab-pane" id="new">
				<table class="responsive table table-striped dataTable" id="media1">				
                  <thead>
                    <tr class="for_remove_class_new">
                     <th width="5%" class="event_title_class_new"> <input type="checkbox" name="sel_rows" class='sel_rows' data-targettable="media1"></th>
                     <th width="20%">Events Title</th>
                      <th width="25%">University Name</th>
                      <th width="15%">Event's Country</th>
					  <th width="15%">Event Date</th>
					   <th width="20%" class="event_title_class_new">Choose Option</th>
                    </tr>
                  </thead>
                  <tbody>
					<?php
					foreach($recent_event as $row){
					?>
					<tr id="check_university_<?php echo $row->event_id;?>" class="check_university_<?php echo $row->event_id;?>">
					  <td><input type="checkbox" value="<?php echo $row->event_id;?>" name="check_event_<?php echo $row->event_id; ?>" class='selectable_checkbox setchkval'  id="check_event_<?php echo $row->event_id; ?>"></td>							
                      <td><?php echo ucwords(substr($row->event_title,0,50)); ?></td>
                       <td><?php echo ucwords($row->univ_name); ?></td>
                       <td><?php if($row->cityname!=''){ echo ucwords($row->cityname); } else { echo '<span style="color:#000;">Not Available</span>'; }?></td>
                       <td class="center"><?php echo ucwords($row->event_date_time); ?></td>
					   <td class="options center">
							<div class="btn-group">
								<?php if($view==1) { ?>
								<a href="<?php echo $base;?>newadmin/admin_events/view_event/<?php echo $row->event_id;?>" class="btn btn-icon tip" data-original-title="View">
									<i class="icon-ok"></i>
								</a>
								<?php } if($edit==1){ ?>
								<a href="<?php echo $base;?>newadmin/admin_events/view_event/<?php echo $row->event_id;?>" style="display:none" class="btn btn-icon tip" data-original-title="Edit">
									<i class="icon-pencil"></i>
								</a>
								<?php } if($delete==1)   {?>
								<div class="modal hide" id="myRecentModal_<?php echo $row->event_id; ?>">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">x</button>
										<h3>Do you want to delete?</h3>
									</div>
									<div class="modal-footer">
										<a href="javascript:void(0);" onclick="delete_confirm('<?php echo $row->event_id; ?>')" class="btn" data-dismiss="modal">Yes</a>
										<a href="javascript:void(0);" class="btn" data-dismiss="modal">Close</a>
									</div>
								</div>						
								<a href="#myRecentModal_<?php echo $row->event_id; ?>" class="btn btn-icon tip"  data-toggle="modal" data-original-title="Delete">
									<i class="icon-trash"></i>
								</a>
								<?php }	if(($edit==1 || $delete==1 || $insert==1)  ){  ?>
								<div class="modal hide" id="myAppDisModal1_<?php echo $row->event_id; ?>">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">x</button>										
											<h3>Do you want to change status ?</h3>	
									</div>
									<div class="modal-footer">										
										<a id="ahc1_<?php echo $row->event_id; ?>_<?php echo $row->ban_event; ?>" href="javascript:void(0)" onclick="approve_home_confirm(this);" class="btn" data-dismiss="modal">Yes</a>
										<a href="javascript:void(0)" class="btn" data-dismiss="modal">Close</a>
									</div>
								</div>							
								<a id="a1_<?php echo $row->event_id; ?>" href="#myAppDisModal1_<?php echo $row->event_id; ?>" class="btn btn-icon tip" <?php if($row->ban_event == 1){ ?> data-original-title="Approved" <?php } else { ?> data-original-title="Disapproved" <?php } ?> data-toggle="modal" >
									<i id="icon1_<?php echo $row->event_id; ?>" class="<?php if($row->ban_event == 1){ echo 'icon-blue'; }?> icon-fire"></i>
								</a>
								<?php 
								$event_title=$this->subdomain->process_url_title(substr($row->event_title,0,50));
								$event_link=$this->subdomain->genereate_the_subdomain_link($row->subdomain_name,'event',$event_title,$row->event_id);									
								?>
								<a href="<?php echo $event_link ; ?>" class="btn btn-icon tip" data-original-title="Preview">
									<i class="icon-film"></i>
								</a>
								<div class="modal hide" id="myFeaUnfModal1_<?php echo $row->event_id; ?>">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">x</button>
										<h3>Do you want to change status ?</h3>											
									</div>
									<div class="modal-footer">
										<a id="mhf1_<?php echo $row->event_id; ?>_<?php  echo $row->featured_home_event; ?>" href="javascript:void(0)" onclick="featured_home_confirm(this);" class="btn" data-dismiss="modal">Yes</a>
										<a href="javascript:void(0)" class="btn" data-dismiss="modal">Close</a>
									</div>
								</div>											
								<a id="mhf_a1_<?php echo $row->event_id; ?>" href="#myFeaUnfModal1_<?php echo $row->event_id; ?>"  class="btn btn-icon tip" <?php if($row->featured_home_event){ ?> data-original-title="Featured" <?php } else { ?> data-original-title="Unfeatured" <?php } ?> data-toggle="modal">
									<i id="mhf_icon1_<?php echo $row->event_id; ?>" class="<?php if($row->featured_home_event){ echo 'icon-blue'; }?> icon-star"></i>
								</a>								
								<?php } ?>
							</div>
						</td>
                     </tr>
					<?php
					}
					?>                  
                  </tbody>
                </table>
				<?php if($delete==1) { ?> 	
				<div class="tableactions" style="margin-top:70px;">
					<select name="univ_action" id="del_action">
						<option value="">Actions</option>
						<option value="delete">Delete</option>
					</select>				
					<input type="button" onclick="action_formsubmit(0,0)" class="submit tiny" value="Apply to selected" />
				</div>	
				<?php } ?> 
              </div>
				<!--end new tab -->				
				<!--start create tab -->
				<div class="tab-pane" id="create">
				<div class="row-fluid">
					<div class="span12">
						<form name="myAddForm" id="myAddForm" onsubmit="return addEvent(this);" action="<?php echo $base; ?>newadmin/admin_events/create_event_ajax" method="post" class="form-horizontal">
							<fieldset>
								<div class="row-fluid">
									<div class="span6">
										<div class="control-group">
										<label class="control-label" for="input01">Event Title</label>
										<div class="controls">
											<input type="text" class="input-xlarge" name="title" id="title">
										</div>
										</div>	
										<!--
										<div class="control-group">
										<label class="control-label" for="input01">Choose University</label>
										<div class="controls">
											<select name="university" id="university" class="inline" >
												<option value="">Please Select</option>
												<?php foreach($univ_info as $univ_detail) { ?>
												<option value="<?php echo $univ_detail->univ_id; ?>"><?php echo $univ_detail->univ_name; ?></option>
												<?php } ?>
											</select>
											<input type="hidden" name="university_name" id="university_name">
																						
										</div>
										</div>
										-->
										<input type="hidden" name="university" id="university" value="<?php if(isset($univ_info['univ_id'])){ echo $univ_info['univ_id']; } ?>">
										<input type="hidden" name="university_name" id="university_name" value="<?php if(isset($univ_info['univ_id'])){ echo $univ_info['univ_name']; } ?>">
										<div class="control-group">
										<label class="control-label" for="input06">Checked IF Event IS Online</label>
										<div class="controls">
											<label class="checkbox"><input type="checkbox" value="0" name="location_event" id="location_event"> Check this checkbox!</label>
											<input type="hidden" name="fixedloc" id="fixedloc">
										</div>
										</div>
										<div id="divShowHide">
											<div class="control-group">
												<label class="control-label" for="input01">Country</label>
												<div class="controls">
													<select  id="country" name="country" class="inline" onchange="fetchstate(0)" >
													<option value="">Please Select</option>
													<?php foreach($countries as $key => $countries_detail) { ?>
													<option value="<?php echo $countries_detail['country_id']; ?>"><?php echo $countries_detail['country_name']; ?></option>
													<?php } ?>
													</select>													
													<a href="#add-country" class="btn btn-icon tip"  data-toggle="modal" data-original-title="Add New Country">
														<i class="icon-plus"></i>
													</a>
													<input type="hidden" name="countryname" id="countryname">
												</div>
											</div>									
											<div class="control-group">
												<label class="control-label" for="input01">State</label>
												<div class="controls">
													<select id="state" name="state" class="inline" onchange="fetchcity(0,0)" disabled="disabled">
														<option value="">Please Select</option>
													</select>
													<a href="#add-state" class="btn btn-icon tip" data-toggle="modal" data-original-title="Add New State">
														<i class="icon-plus" id="setCountryValue"></i>
													</a>
													<span class="inline margin_l" style="display:none"><button class="btn btn-icon tip" data-original-title="Add New State"><i class="icon-plus"></i></button></span>
													<input type="hidden" name="statename" id="statename">
												</div>
											</div>	
											<div class="control-group">
												<label class="control-label" for="input01">City</label>
												<div class="controls">
													<select id="city" name="city" class="inline" disabled="disabled">
														<option value="">Please Select</option>	
													</select>
													<a href="#add-city" class="btn btn-icon tip"  data-toggle="modal" data-original-title="Add New City">
														<i class="icon-plus" id="setCountryStateValue"></i>
													</a>
													<span class="inline margin_l" style="display:none"><button class="btn btn-icon tip" data-original-title="Add New City"><i class="icon-plus"></i></button></span>
													<input type="hidden" name="cityname" id="cityname">
												</div>
											</div>
										</div>
										<div class="control-group">
										<label class="control-label" for="input06">Hide Event On Site</label>
										<div class="controls">
											<label class="checkbox"><input type="checkbox" id="hide_event" value="1"  name="hide_event"> Check this checkbox!</label>
										</div>
										</div>
										<div class="control-group">
										<label class="control-label" for="input01">Event Place</label>
										<div class="controls">
											<input type="text" class="input-xlarge" id="event_place" name="event_place">
										</div>
										</div>
										<div class="control-group">
										<label class="control-label" for="input06">Event Type</label>
										<div class="controls">
											<select id="event_type" name="event_type">
											<option value="">Select Category</option>
											<option value="spot_admission">Spot Admission</option>
											<option value="fairs">Fairs</option>
											<option value="alumuni">Counselling-Alumuni</option>
											<option value="others">Counselling-Others</option>
											</select>
										</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="date">Event Start Date</label>
											<div class="controls">
											<div class="input-prepend">
											<span class="add-on"><i class="icon-calendar"></i></span><input type="text" size="16" name="event_start_date"  id="event_start_date" class='span4 datepick'>
											</div>
											</div>
											<input type="hidden" name="event_time" id="event_time">
										</div>
										<div class="control-group">
											<label class="control-label" for="date">Event End Date</label>
											<div class="controls">
											<div class="input-prepend">
											<span class="add-on"><i class="icon-calendar"></i></span><input type="text" size="16" name="event_end_date"  id="event_end_date" class='span4 datepick'>
											</div>
											<span class="validAfterDatepicker" style="display:none;color:red;">Please enter a date after the previous value</span>
											</div>
										</div>
										<div class="control-group">
										<label class="control-label" for="input06">Checked IF Event Timing Is</label>
										<div class="controls">
											<label class="checkbox"><input type="checkbox" name="event_timing_fixed_not_fixed" id="event_timing_fixed_not_fixed"> (Appintment based,Not Fixed etc.)</label>
											<input type="hidden" name="etiming" id="etiming" value="1">
										</div>
										</div>
										<div id="divShowHideTime">
											<div class="control-group">
												<label class="control-label" for="time">Event time</label>
												<div class="controls">
													<div class="input-prepend">
														<span>From</span>
													<span class="add-on"><i class="icon-time"></i></span><input type="text" size="16" id="event_time_start" name="event_time_start" class='span4 timepicker'>
													<span class="margin_r">Till</span><span class="add-on inline margin_l"><i class="icon-time"></i></span><input type="text" size="16" id="event_time_end"  name="event_time_end" class='span4 timepicker'>
													</div>
													<span class="validAfterTimepickerEqual" style="display:none;color:red;">End time is equal to start time!</span>
													<span class="validAfterTimepickerBefore" style="display:none;color:red;">End time is before start time!</span>
												</div>
											</div>
										</div>
										<div class="control-group" style="display:none" id="divShowHideMentionTime">
											<label class="control-label" for="time">Mention Event Timing</label>
											<div class="controls">
												<input type="text" name="event_mention_time" id="event_mention_time" class="input-xlarge">
											</div>
										</div>
										<div class="control-group" style="display:block" id="divShowHideShareOnFacebook">
										<label class="control-label" for="input06">Share on Facebook</label>
										 <div class="controls">
											<input type="checkbox"  class="text time" id="share_facebook"  name="share_facebook" />
										</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="tags">Council</label>
											<div class="controls">
											<input type="text" class='tagsinput span12' id="tags" name="tags">
											</div>
										</div>
									</div>
									<div class="span6">										
										<div class="control-group">
										<label class="control-label" for="input06">Share on Facebook for</label>
										 <div class="controls">
											<label class='radio'><input type="radio" name="share_facebook_for" id="share_facebook_for" value="3"> 3 days</label>
											<label class='radio'><input type="radio" name="share_facebook_for" id="share_facebook_for" value="7"> 7 days</label>
											<label class='radio'><input type="radio" name="share_facebook_for" id="share_facebook_for" value="15"> 15 days</label>
										</div>
										</div>
										<div class="control-group">
										<label class="control-label" for="input06">Detail</label>
										<div class="controls">
											<textarea name="detail"  id="detail" class='cleditor span12'></textarea>
										</div>
										</div>
										<div class="form-actions">
										 <button type="submit" class='btn btn-primary'>Add Event</button>
										 <a href="<?php echo $base; ?>newadmin/admin_events" class='btn btn-danger'>Cancel</a>									
										</div>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
              </div>
				<!--end create tab -->
            </div>
          </div>
        </div>
      </div>
    </div><!-- close .container-fluid -->
</div><!-- close .content -->
  <!-- END Content -->

<div class="modal hide" id="add-country">
	<div class="modal-lightsout" id="add-country">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2>Add Your Place</h2>
			<form action="" method="post" id="form_country" id="add_country_form" >
				<p>
					<label>Country:</label><br>
					<input type="text" size="30" class="text" name="country_model" id="country_model" value=""> 
					<label class="form_error"  id="country_error"></label>
				</p>
				<p>
					<label>State:</label><br>
					<input type="text" size="30" class="text" name="state_model" id="state_model" value=""> 
					<label class="form_error"  id="state_error"></label>
				</p>
				<p>
					<label>City:</label><br>
					<input type="text" size="30" class="text" name="city_model" id="city_model" value=""> 
					<label class="form_error"  id="city_error"></label>
				</p>	
		</div>
		<div class="modal-footer">
			<input type="button" class="btn" name="addcountry" id="addcountry" value="Submit">			
			<a href="#" class="btn" data-dismiss="modal">Close</a>
		</div>
			</form>
	</div>
</div>

<div class="modal hide" id="add-state">
	<div class="modal-lightsout" id="add-state">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2>Add Your Place</h2>
				<form action="" method="post" id="form_state" id="add_state_form">
					<p>
						<label>Country:</label><br>
						<select class="text country_select margin_zero" name="country_model1" id="country_model1" >
							<option value="">Select Country</option>
							<?php foreach($countries as $country) { ?>
							<option value="<?php echo $country['country_id']; ?>" ><?php echo $country['country_name']; ?></option>
							<?php } ?>
						</select>
						<label class="form_error"  id="country_error1"></label>						
					</p>
						
					<p>
						<label>State:</label><br>
						<input type="text" size="30" class="text" name="state_model1" id="state_model1" value=""> 
						<label class="form_error"  id="state_error1"></label>
					
					</p>
					<p>
						<label>City:</label><br>
						<input type="text" size="30" class="text" name="city_model1" id="city_model1" value=""> 
						<label class="form_error"  id="city_error1"></label>
					
					</p>				
				
		</div>
		<div class="modal-footer">
			<input type="button" class="btn" name="addstate" id="addstate" value="Submit">			
			<a href="#" class="btn" data-dismiss="modal">Close</a>
		</div>
			</form>
	</div>
</div>

<div class="modal hide" id="add-city">
	<div class="modal-lightsout" id="add-city">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">x</button>
			<h2>Add Your Place</h2>
						<form action="" method="post" id="add_city_form" >
							<p>
								<label>Country:</label><br>
							<select class="text country_select margin_zero" name="country_model2"  id="country_model2" onchange="fetchstates('-1')">
											<option value="">Select Country</option>
											<?php foreach($countries as $country) { ?>
											<option value="<?php echo $country['country_id']; ?>" ><?php echo $country['country_name']; ?></option>
											<?php } ?>
							</select>
							<label class="form_error"  id="country_error2"></label>
							<div style="color: red;"> <?php echo form_error('country_model2'); ?><?php echo isset($errors['country_model2'])?$errors['country_model2']:''; ?> </div>
							
							</p>
							<p>
								<label>State:</label><br>
								<select class="text country_select margin_zero" name="state_model2"  id="state_model2" disabled="disabled">
								<option value="">Please Select</option>
								</select>
								<label class="form_error"  id="state_error2"></label>
							</p>
							<p>
								<label>City:</label><br>
								<input type="text" size="30" class="text" name="city_model2" id="city_model2"> 
									<label class="form_error"  id="city_error2"></label>
							</p>
							<input type="hidden" name="level_user" value="3">						
						
		</div>
		<div class="modal-footer">
			<input type="button" class="btn" name="addcity" id="addcity" value="Submit">			
			<a href="#" class="btn" data-dismiss="modal">Close</a>
		</div>
			</form>
	</div>
</div>
<script>
function addEvent()
{
	var valid=true;
	if($("#title").val()=='')
	{
		$("#title").addClass('needsfilled');
		valid=false;		
	}
	else
	{
		$("#title").removeClass('needsfilled');
		
	}
	// if($("#university").val()=='')
	// {
		// $("#university").addClass('needsfilled');
		// valid=false;		
	// }
	// else
	// {
		// $("#university").removeClass('needsfilled');
		// valid=true;		
	// }
	if(!$("#location_event").is(":checked"))
	{
		if($('#country option:selected').val()=='')
		{
			$("#country").addClass('needsfilled');
			valid=false;
		}
		else
		{
			$("#country").removeClass('needsfilled');
				
		}
		if($('#state option:selected').val()=='')
		{
			$("#state").addClass('needsfilled');
			valid=false;
		}
		else
		{
			$("#state").removeClass('needsfilled');
					
		}
		if($('#city option:selected').val()=='')
		{
			$("#city").addClass('needsfilled');
			valid=false;
		}
		else
		{
			$("#city").removeClass('needsfilled');
					
		}
	}
	if($("#event_place").val()=='')
	{
		$("#event_place").addClass('needsfilled');
		valid=false;		
	}
	else
	{
		$("#event_place").removeClass('needsfilled');
			
	}
	if($('#event_type option:selected').val()=='')
	{
		$("#event_type").addClass('needsfilled');
		valid=false;
	}
	else
	{
		$("#event_type").removeClass('needsfilled');
			
	}
	if($("#event_start_date").val()=='')
	{
		$("#event_start_date").addClass('needsfilled');
		valid=false;		
	}
	else
	{
		$("#event_start_date").removeClass('needsfilled');			
	}
	if($("#event_end_date").val()=='')
	{
		$("#event_end_date").addClass('needsfilled');
		valid=false;		
	}
	else
	{
		$("#event_end_date").removeClass('needsfilled');
	}
	var start = $("#event_time_start").val();	
	var end = $("#event_time_end").val();	
	var dtStart = new Date($("#event_start_date").val() +" "+ start);	
	var dtEnd = new Date($("#event_end_date").val() +" "+ end);
	difference_in_milliseconds = dtEnd - dtStart;	
	if (difference_in_milliseconds <= 0)
	{
		if (difference_in_milliseconds == 0)
		{
			$(".validAfterTimepickerEqual").show();
			$(".validAfterTimepickerBefore").hide();
		}
		else
		{
			$(".validAfterTimepickerEqual").hide();
			$(".validAfterTimepickerBefore").show();
		}
		valid = false;
	}
	else
	{
		$(".validAfterTimepickerEqual").hide();
		$(".validAfterTimepickerBefore").hide();
	}
	var country_name=$("#country option:selected").text();			
	var state_name=$("#state option:selected").text();
	var city_name=$("#city option:selected").text();
	var event_time = $("#event_start_date").val();
	$("#countryname").val(country_name);
	$("#statename").val(state_name);
	$("#cityname").val(city_name);	
	$("#event_time").val(event_time);	
	if(valid==true)
	{
		document.forms["myAddForm"].submit();
	}
	return valid;
}  
$(document).ready(function(){	
	$("#event_start_date").change(function() {
		if((new Date($("#event_end_date").val()).getTime() < new Date($("#event_start_date").val()).getTime()))
		{
			$(".validAfterDatepicker").show();
		}
		else
		{
			$(".validAfterDatepicker").hide();
		}
		$(".datepicker").hide();
		
	});	
	$("#event_end_date").change(function() {
		
		if((new Date($("#event_end_date").val()).getTime() < new Date($("#event_start_date").val()).getTime()))
		{
			$(".validAfterDatepicker").show();
		}
		else
		{
			$(".validAfterDatepicker").hide();
		}
		$(".datepicker").hide();		
	});
	
	$('.event_title_class_all').removeClass('sorting_asc sorting_desc');	
	$('.event_title_class_new').removeClass('sorting_asc sorting_desc');	
	$(".for_remove_class_all").click(function() {
	$('.event_title_class_all').removeClass('sorting sorting_asc sorting_desc');	
	});	
	$(".for_remove_class_new").click(function() {
	$('.event_title_class_new').removeClass('sorting sorting_asc sorting_desc');	
	});	
	$('.collapsed-nav').css('display','none');
	var url = window.location.pathname; 
	var activePage = url.substring(url.lastIndexOf('/')+1);
	$('.mainNav li a').each(function(){  
		var currentPage = this.href.substring(this.href.lastIndexOf('/')+1);
		if (activePage == currentPage) {
			$('.mainNav li').removeClass('active');
			$('li').find('span').removeClass('label-white');
			$('li').find('i').removeClass('icon-white');
			$(this).parent().addClass('active'); 
			$(this).parent().find('span').addClass('label-white');
			$(this).parent().find('i').addClass('icon-white');
				$(this).parent().parent().css('display','block');
				if($(this).parent().parent().css('display','block'))
				{
					$(this).parent().parent().prev().parent().addClass('active');
					$(this).parent().parent().prev().find('span img').attr('src', 'img/toggle_minus.png');
					$(this).parent().parent().prev().find('span').addClass('label-white');
					$(this).parent().parent().prev().find('i').addClass('icon-white');
				}
			} 
		});
	});
	
function delete_confirm(id)
{	
	$.ajax({	
		type: "POST",
		url: "<?php echo $base; ?>newadmin/admin_events/delete_single_event/"+id,
		async:false,
		data: '',
		cache: false,
		success: function(msg)
		{	   
			if(msg == 1)
			{
				$('.check_university_'+id).hide();
				//$('.check_university1_'+id).hide();
				$('#delete').show();
				setTimeout(function(){$('#delete').fadeOut('slow');},2000);		
			}
			else
			{
				$('#denied').show();
				setTimeout(function(){$('#denied').fadeOut('slow');},2000);	
			}
		}
	
	});
}
function approve_home_confirm(id_sta)
{
	var arr = id_sta.id.split('_');	
	var c = arr[1];		//id
	var b = arr[2];		//status
	var data={'id':c,'status':b};	
	$.ajax({
		type: "POST",
		url: '<?php echo $base; ?>newadmin/admin_events/show_hide_event/'+b+'/'+c,
		async:false,
		data: data,
		cache: false,
		success: function(msg)
		{
			if(msg == '1')
			{				
				$('#icon_'+c).addClass('icon-blue');
				$('#icon1_'+c).addClass('icon-blue');
				$('#a_'+c).attr('data-original-title','Approved');
				$('#a1_'+c).attr('data-original-title','Approved');
				$('#ahc_'+c+'_'+b).attr('id','ahc_'+c+'_'+msg);
				$('#ahc1_'+c+'_'+b).attr('id','ahc1_'+c+'_'+msg);								
				$('#approved').show();				
				setTimeout(function(){$('#approved').fadeOut('slow');},2000);				
			}
			else if(msg == '0')
			{				
				$('#icon_'+c).removeClass('icon-blue');
				$('#icon1_'+c).removeClass('icon-blue');
				$('#a_'+c).attr('data-original-title','Disapproved');			
				$('#a1_'+c).attr('data-original-title','Disapproved');			
				$('#ahc_'+c+'_'+b).attr('id','ahc_'+c+'_'+msg);				
				$('#ahc1_'+c+'_'+b).attr('id','ahc1_'+c+'_'+msg);				
				$('#disapproved').show();
				setTimeout(function(){$('#disapproved').fadeOut('slow');},2000);				
			}
			else
			{
				$('#denied').show();
				setTimeout(function(){$('#denied').fadeOut('slow');},2000);				
			}
		}
	});	
}
function featured_home_confirm(id_sta)
{
	var arr = id_sta.id.split('_');	
	var c = arr[1];		//id
	var b = arr[2];		//status
	if(b==1)
	{
		$.ajax({
			type: "POST",
			url: '<?php echo $base; ?>newadmin/admin_events/featured_unfeatured_event/'+b+'/'+c,
			async:false,
			data: '',
			cache: false,
			success: function(msg)
			{
				if(msg==1)
				{
					$('#mhf_icon_'+c).addClass('icon-blue');
					$('#mhf_icon1_'+c).addClass('icon-blue');
					$('#mhf_a_'+c).attr('data-original-title','Featured');
					$('#mhf_a1_'+c).attr('data-original-title','Featured');
					$('#mhf_'+c+'_'+b).attr('id','mhf_'+c+'_'+msg);
					$('#mhf1_'+c+'_'+b).attr('id','mhf1_'+c+'_'+msg);			
					$('#featured').show();				
					setTimeout(function(){$('#featured').fadeOut('slow');},2000);
				}
				else if(msg==0)
				{
					$('#mhf_icon_'+c).removeClass('icon-blue');
					$('#mhf_icon1_'+c).removeClass('icon-blue');
					$('#mhf_a_'+c).attr('data-original-title','Featured');
					$('#a1_'+c).attr('data-original-title','Featured');
					$('#mhf_'+c+'_'+b).attr('id','mhf_'+c+'_'+msg);
					$('#mhf1_'+c+'_'+b).attr('id','mhf1_'+c+'_'+msg);				
					$('#unfeatured').show();				
					setTimeout(function(){$('#unfeatured').fadeOut('slow');},2000);
				}
				else
				{
					$('#denied').show();
					setTimeout(function(){$('#denied').fadeOut('slow');},2000);	
				}
			}
			
		});
	}
	else
	{
		$.ajax({
			type: "POST",
			url: "<?php echo $base; ?>newadmin/admin_events/count_featured_events/featured_home_event",
			async:false,
			data: '',
			cache: false,
			success: function(msg)
			{
				if(msg==1)
				{
					$.ajax({
						type: "POST",
						url: '<?php echo $base; ?>newadmin/admin_events/featured_unfeatured_event/'+b+'/'+c,
						async:false,
						data: '',
						cache: false,
						success: function(msg)
						{
							if(msg==1)
							{
								$('#mhf_icon_'+c).addClass('icon-blue');
								$('#mhf_icon1_'+c).addClass('icon-blue');
								$('#mhf_a_'+c).attr('data-original-title','Featured');
								$('#mhf_a1_'+c).attr('data-original-title','Featured');
								$('#mhf_'+c+'_'+b).attr('id','mhf_'+c+'_'+msg);
								$('#mhf1_'+c+'_'+b).attr('id','mhf1_'+c+'_'+msg);							
								$('#featured').show();				
								setTimeout(function(){$('#featured').fadeOut('slow');},2000);
							}
							else if(msg==0)
							{
								$('#mhf_icon_'+c).removeClass('icon-blue');
								$('#mhf_icon1_'+c).removeClass('icon-blue');
								$('#mhf_a_'+c).attr('data-original-title','Featured');
								$('#a1_'+c).attr('data-original-title','Featured');
								$('#mhf_'+c+'_'+b).attr('id','mhf_'+c+'_'+msg);
								$('#mhf1_'+c+'_'+b).attr('id','mhf1_'+c+'_'+msg);							
								$('#unfeatured').show();				
								setTimeout(function(){$('#unfeatured').fadeOut('slow');},2000);
							}
							else
							{
								$('#denied').show();
								setTimeout(function(){$('#denied').fadeOut('slow');},2000);	
							}
						}
						
					});
				}				
				else
				{
					$('#alert').show();
					setTimeout(function(){$('#alert').fadeOut('slow');},2000);					
				}
			}
		});
	}
}
var arr=new Array;
function action_formsubmit(id,flag)
{
	var action;
	if($('#univ_action').val()!='')
	{
		action=$('#univ_action').val();
	}
	if($('#del_action').val()!='')
	{
		action=$('#del_action').val();
	}
	
	if(action=='delete')
	{
		var atLeastOneIsChecked = $('.setchkval:checked').length > 0;
		if(atLeastOneIsChecked)
		{
			var r=confirm("Are you sure you want to delete selected events");
			set_chkbox_val();
			var data={'event_id':arr};
			if(r)
			{
				$.ajax({
					type:"post",
					url:'<?php echo $base ?>newadmin/admin_events/delete_events',
					async:false,
					data: data,
					cache: false,
					success: function(msg)
					{						
						$('.toremove').hide();
						$("#deleted").show();
						$("#deleted").delay(5000).hide("slow");						
					}
					
				});
			}
		}
		else
		{
			$('#sel_atl_one').show();				
			setTimeout(function(){$('#sel_atl_one').fadeOut('slow');},2000);
			return false;
		}
	}
	else
	{
		$('#sel_act').show();				
		setTimeout(function(){$('#sel_act').fadeOut('slow');},2000);		
		return false;
	}
}

function set_chkbox_val()
{
	$('.setchkval').each(function()
	{
		if($(this).attr('checked'))
		{
			$('#check_university_'+$(this).val()).addClass('toremove');
			arr.push($(this).val());
		}		
	});
}
$(document).ready(function()
{	
	$("#location_event").click(function() {		
		if($("#location_event").is(":checked"))
		{
			$("#fixedloc").val(0);
		}
		else
		{
			$("#fixedloc").val(1);
		}		
		$("#divShowHide").toggle('slow');
	});
		
	$("#event_timing_fixed_not_fixed").click(function() { 
		if($("#event_timing_fixed_not_fixed").is(":checked"))
		{
			$("#etiming").val(0);
		}
		else
		{
			$("#etiming").val(1);
		}	
		$("#divShowHideTime").toggle('slow');
		$("#divShowHideMentionTime").toggle('slow');
		$("#divShowHideShareOnFacebook").toggle('slow');
	});	
	
});
function fetchcountry(cid,cname)
{
	$("#country option:selected").text(cname);	
	$("#country option:selected").val(cid);
	$("#state").removeAttr("disabled");
	$("#city").removeAttr("disabled");
}
function fetchstate(sid)
{
	var stid=sid;
	var cid=$("#country option:selected").val();
	$.ajax({
	   type: "POST",
	   url: "<?php echo $base; ?>admin/state_list_ajax/",
	   data: 'country_id='+cid+'&sel_state_id='+stid,
	   cache: false,
	   success: function(msg)
	   {
		$('#state').attr('disabled', false);
		$('#state').html(msg);			
	   }
	   });
}
function fetchstates(sid,sname)
{
var stid=sid;
var cid;
if(sid=='-1')
{
stid='0';
cid=$("#country_model2 option:selected").val();
$.ajax({
   type: "POST",
   url: "<?php echo $base; ?>admin/state_list_ajax/",
   data: 'country_id='+cid+'&sel_state_id='+stid,
   cache: false,
   success: function(msg)
   {
    if(sid=='-1')
	{
	$('#state_model2').attr('disabled', false);
	$('#state_model2').html(msg);
	}
   }
   });
}  
else
{
	$("#state option:selected").text(sname);	
	$("#state option:selected").val(sid);	
	$("#state").removeAttr("disabled");
	$("#city").removeAttr("disabled");
}
}
function fetchcity(state_id,cityid)
{
	if(state_id=='0')
	{
		state_id=$("#state option:selected").val();
	}
	$.ajax({
		type: "POST",
		url: "<?php echo $base; ?>admin/city_list_ajax/",
		data: 'state_id='+state_id+'&sel_city_id='+cityid,
		cache: false,
		success: function(msg)
		{
			$('#city').attr('disabled', false);
			$('#city').html(msg);
		}
	});  
} 
function fetchcities(cityid,cityname)
{
	$("#city option:selected").text(cityname);	
	$("#city option:selected").val(cityid);	
	$("#state").removeAttr("disabled");
	$("#city").removeAttr("disabled");
}

$('#addcountry').click(function(){
	var country=$("#country_model").val();
	var state=$("#state_model").val();
	var city=$("#city_model").val();
	var flag=0;
	if(country=='' || country==null)
	{
	 $('#country_error').html("Please enter the country name"); 
	 $('#country_model').addClass('needsfilled');
	 flag=0;
	}
	else
	{
		$('#country_error').html("") 
		$('#country_model').removeClass('needsfilled');
		flag=flag+1;
	}
	if(state=='' || state==null)
	{
		$('#state_error').html("Please enter the state name"); 
		$('#state_model').addClass('needsfilled');
		flag=0;
	}
	else
	{
		$('#state_error').html(""); 
		$('#state_model').removeClass('needsfilled');
		flag=flag+1;
	}
	if(city=='' || city==null)
	{
		$('#city_error').html("Please enter the city"); 
		$('#city_model').addClass('needsfilled');
		flag=0;
	}
	else
	{
		$('#city_error').html(""); 
		$('#city_model').removeClass('needsfilled');
		flag=flag+1;
	}
	if(flag==3)
	{
	 var  countrystatus=0;
		$.ajax({
	   type: "POST",
	   url: "<?php echo $base; ?>admin/check_unique_field/country_name/country",
	   async:false,
	   data: 'field='+country,
	   cache: false,
	   success: function(msg)
	   {
	   if(msg=='1')
		{
		$('#country_error').html('Country Already Exist');
		$('#country_model').addClass('needsfilled');
		}
		else if(msg=='0')
		{
		$('#country_model').html('');
		$('#country_error').addClass('');
		countrystatus=1;
		}
	   }
	   });
	 if(countrystatus)
	 {
	$.ajax({
	   type: "POST",
	   url: "<?php echo $base; ?>admin/add_country_ajax",
	   async:false,
	   data: 'country_model='+country+'&state_model='+state+'&city_model='+city,
	   cache: false,
	   success: function(msg)
	   {
	    var place=msg.split('##');
		fetchcountry(place[0],country);
		fetchstates(place[1],state);
		fetchcities(place[2],city);				
		$('.modal-lightsout').fadeOut("slow");
		$('.modal-backdrop').fadeOut("slow");	
		$('.in').fadeOut("slow");	
		//$('#add_country_form').reset();
		$('.info_message').html('Your Place Added Successfully');
		$('.content_msg').show(500);
	   }
	   });
	 } 
	   
	}
	
});

$('#addstate').click(function(){
	var country=$("#country_model1 option:selected").val();
	var countrytext=$("#country_model1 option:selected").text();
	var state=$("#state_model1").val();
	var city=$("#city_model1").val();
	var flag=0;
	if(country=='' || country==null || country=='0')
	{
	 $('#country_error1').html("Please select the country"); 
	 $('#country_model1').addClass('needsfilled');
	 flag=0;
	}
	else
	{
	$('#country_error1').html("");
	 $('#country_model1').removeClass('needsfilled');
	  flag=flag+1;
	}
	if(state=='' || state==null)
	{
	$('#state_error1').html("Please enter the state name"); 
	$('#state_model1').addClass('needsfilled');
	flag=1;
	
	}
	else
	{
	$('#state_error1').html(""); 
	$('#state_model1').removeClass('needsfilled');
	  flag=flag+1;
	}
	if(city=='' || city==null)
	{
	$('#city_error1').html("Please enter the city"); 
	$('#city_model1').addClass('needsfilled');
	flag=0;
	}
	else
	{
	$('#city_error1').html(""); 
	$('#city_model1').removeClass('needsfilled');
	 flag=flag+1;
	}
	if(flag==3)
	{
	 var  statestatus=0;
		$.ajax({
	   type: "POST",
	   url: "<?php echo $base; ?>admin/state_check",
	   async:false,
	   data: 'state_model1='+state+'&country_model1='+country,
	   cache: false,
	   success: function(msg)
	   {
	    if(msg=='1')
		{
		$('#state_error1').html('State Already Exist in Selected Country');
		$('#state_model1').addClass('needsfilled');
		}
		else if(msg=='0')
		{
		$('#state_error1').html('');
		$('#state_model1').addClass('');
		statestatus=1;
		}
	   }
	   });
	 if(statestatus)
	 {
	 $.ajax({
	   type: "POST",
	   url: "<?php echo $base; ?>admin/add_state_ajax",
	   async:false,
	   data: 'country_model1='+country+'&state_model1='+state+'&city_model1='+city,
	   cache: false,
	   success: function(msg)
	   {
	    var place=msg.split('##');
		fetchcountry(place[0],countrytext);
		fetchstates(place[1],state);
		fetchcities(place[2],city);
		$('.modal-lightsout').fadeOut("fast");
		$('.modal-backdrop').fadeOut("slow");
		$('.in').fadeOut("slow");		
		//$('#add_state_form').reset();
		$('.info_message').html('Your Place Added Successfully');
		$('.content_msg').css('display','block');
	   }
	   });
	 } 
	   
	}
	
});

$('#addcity').click(function(){
	var country=$("#country_model2 option:selected").val();
	var countrytext=$("#country_model2 option:selected").text();
	var state=$("#state_model2 option:selected").val();
	var statetext=$("#state_model2 option:selected").text();
	var city=$("#city_model2").val();
	var flag=0;
	if(country=='' || country==null || country=='0')
	{
	 $('#country_error2').html("Please select the country"); 
	 $('#country_model2').addClass('needsfilled');
	 flag=0;
	}
	else
	{
	$('#country_error2').html("");
	 $('#country_model2').removeClass('needsfilled');
	  flag=flag+1;
	}
	if(state=='' || state==null || state=='0')
	{
	$('#state_error2').html("Please select the state "); 
	$('#state_model2').addClass('needsfilled');
	flag=0;
	}
	else
	{
	$('#state_error2').html(""); 
	$('#state_model2').removeClass('needsfilled');
	 flag=flag+1;
	}
	if(city=='' || city==null)
	{
	$('#city_error2').html("Please enter the city"); 
	$('#city_model2').addClass('needsfilled');
	flag=0;
	}
	else
	{
	$('#city_error2').html(""); 
	$('#city_model2').removeClass('needsfilled');
	flag=flag+1;
	}
	if(flag==3)
	{
	 var  citystatus=0;
		$.ajax({
	   type: "POST",
	   url: "<?php echo $base; ?>admin/city_check",
	   async:false,
	   data: 'state_model2='+state+'&country_model2='+country+'&city_model2='+city,
	   cache: false,
	   success: function(msg)
	   {
	    if(msg=='1')
		{
		$('#city_error2').html('CIty Already Exist in Selected State');
		$('#city_model2').addClass('needsfilled');
		}
		else if(msg=='0')
		{
		$('#city_error2').html('');
		$('#city_model2').addClass('');
		citystatus=1;
		}
	   }
	   });
	 if(citystatus)
	 {
	 $.ajax({
	   type: "POST",
	   url: "<?php echo $base; ?>admin/add_city_ajax",
	   async:false,
	   data: 'country_model2='+country+'&state_model2='+state+'&city_model2='+city,
	   cache: false,
	   success: function(msg)
	   {
	    var place=msg.split('##');
		fetchcountry(place[0],countrytext);
		fetchstates(place[1],statetext);
		fetchcities(place[2],city);
		$('.modal-lightsout').fadeOut("fast");
		$('.modal-backdrop').fadeOut("slow");	
		$('.in').fadeOut("slow");		
		//$('#add_city_form').reset();
		$('.info_message').html('Your Place Added Successfully');
		$('.content_msg').css('display','block');
	   }
	   });
	 } 	   
	}	
});
$('#setCountryValue').click(function(){
	var selCountryText = $("#country option:selected").text();
	var selCountryVal = $("#country option:selected").val();
	$("#country_model1 option:selected").val(selCountryVal);
	$("#country_model1 option:selected").text(selCountryText);
	if(selCountryText=="Please Select")
	{
		$("#country_model1").removeAttr("disabled");
	}
	else
	{
		$('#country_model1').attr('disabled', true);
	}
});
$('#setCountryStateValue').click(function(){
	var selCountryText = $("#country option:selected").text();
	var selCountryVal = $("#country option:selected").val();	
	$("#country_model2 option:selected").text(selCountryText);
	$("#country_model2 option:selected").val(selCountryVal);
	if(selCountryText=="Please Select")
	{
		$("#country_model2").removeAttr("disabled");
	}
	else
	{
		$('#country_model2').attr('disabled', true);
	}


	var selStateText = $("#state option:selected").text();
	var selStateVal = $("#state option:selected").val();
	$("#state_model2 option:selected").text(selStateText);
	$("#state_model2 option:selected").val(selStateVal);
	$('#state_model2').attr('disabled', true);
	if(selStateText=="Please Select" || selStateText=="Select state")
	{
		$("#state_model2").removeAttr("disabled");
	}
	else
	{
		$('#state_model2').attr('disabled', true);
	}
	
});
</script>

<!-- for calander -->
<script>
	if($('.calendar').length > 0){
		$('.calendar').fullCalendar({
			header: {
				left: 'prev',
				center: 'title',
				right: 'next,month,agendaWeek,agendaDay'
			},
			editable: false,
			events: [
			<?php
			if(!empty($events_for_calendar))
			{
				foreach($events_for_calendar as $event_detail){
					echo "{";
						if(!empty($event_detail['event_title']))
							echo "title: '".$event_detail['event_title']."',";	
						if(!empty($event_detail['event_date_time']))
							echo "start: '".$event_detail['event_date_time']."',";	
						if(!empty($event_detail['event_date_time']))
							echo "end: '".$event_detail['event_date_time_end']."',";		
					echo "},";
				}
			}
			?>			
			]			
		});
	}
</script>
