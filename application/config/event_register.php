<?php
	if($eve_reg_suc == 'suc')
	{
	?>
	<script>
	$(document).ready(function(){
	$('#show_success').css('display','block');
	$('#show_success').hide();
	$('#show_success').show("show");
	$("#show_success").delay(3000).fadeOut(200);
	});
	</script>
	<?php
	}
	$eve_reg_suc = '';
	?>
	<div class="container">
		<div class="body_bar"></div>
		<div class="body_header"></div>
		<div class="body">
		
		
		<div class="modal" id="show_success" style="display:none;" >
					  <div class="modal-header">
						<a class="close" data-dismiss="modal"></a>
						<h3>Message For You</h3>
					  </div>
					  <div class="modal-body">
						<p><center><h4>Event Has been registred successfully.....</h4></center></p>
					  </div>
					  <div class="modal-footer">
						<!--<a href="#" class="btn">Close</a>-->
						<!--<a href="#" class="btn btn-primary">Save changes</a>-->
					  </div>
				</div>
		
		
			<div class="row margin_t1">
				<div class="float_l span13 margin_l">
					<div class="float_l span10 margin_zero round_box">
						<h2>Event Registration</h2>
					<div class="margin_t1">
						<form class="form-horizontal form_step_box" action="" method="post">
							<div class="control-group">
								<label class="control-label" for="focusedInput">Full Name</label>
								<div class="controls">
								<input type="text" class="input-xlarge focused" name="event_fullname" value="<?php if(!empty($get_info_logged_user)){echo $get_info_logged_user['fullname']? $get_info_logged_user['fullname']:'Full Name'; }?>"/>
<span style="color:red;"> <?php echo form_error('event_fullname'); ?> <?php echo isset($errors['event_fullname'])? $errors['event_fullname'] : ''; ?> </span>
									
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="focusedInput">Email</label>
								<div class="controls">
								<input type="text" name="event_email" class="input-xlarge focused" value="<?php if(!empty($get_info_logged_user)){echo $get_info_logged_user['email']? $get_info_logged_user['email']:'Email'; }?>"/>
<span style="color:red;"> <?php echo form_error('event_email'); ?> <?php echo isset($errors['event_email'])? $errors['event_email'] : ''; ?> </span>
									
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="focusedInput">Phone</label>
								<div class="controls">
								<input type="text" class="input-xlarge focused" name="event_phone" value="<?php if(!empty($get_info_logged_user)){echo $get_info_logged_user['mob_no']? $get_info_logged_user['mob_no']:'Phone';} ?>"/>
<span style="color:red;"> <?php echo form_error('event_phone');