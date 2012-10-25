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
<div id="addques" class="alert alert-success" style="display:none">
  <a class="close" data-dismiss="alert" href="#">�</a>
  <strong>Question Added Successfully</strong>
</div>
<div id="access" class="alert alert-success" style="display:none">
  <a class="close" data-dismiss="alert" href="#">�</a>
  <strong>Unable to perform action please contact admin</strong>
</div>
<div id="deleted" class="alert alert-success" style="display:none">
  <a class="close" data-dismiss="alert" href="#">�</a>
  <strong>Question Deleted Successfully</strong>
</div>
<div id="approved" class="alert alert-success" style="display:none">
  <a class="close" data-dismiss="alert" href="#">�</a>
  <strong>Question approved Successfully</strong>
</div>
<div id="disapproved" class="alert alert-success" style="display:none">
  <a class="close" data-dismiss="alert" href="#">�</a>
  <strong>Question disapproved Successfully</strong>
</div>
<div id="featured" class="alert alert-success" style="display:none">
  <a class="close" data-dismiss="alert" href="#">�</a>
  <strong>Question is now home featured</strong>
</div>
<div id="unfeatured" class="alert alert-success" style="display:none">
  <a class="close" data-dismiss="alert" href="#">�</a>
  <strong>Question removed from home featured</strong>
</div>
<div class="content">
  <div class="container-fluid">      
		<div class="row-fluid">
        <div class="span12">
          <div class="page-header clearfix tabs">
            <h2>Question</h2>
            <ul class="nav nav-pills">
              <li class='active'>
                <a href="#all" data-toggle="pill">All</a>
              </li>
              <li>
                <a href="#new" data-toggle="pill">New</a>
              </li>
			  <li id="active_menu">
                <a href="#create" data-toggle="pill">Add Question</a>
              </li>
            </ul>
          </div>
          <div class="content-box">
            <div class="tab-content">
              <div class="tab-pane active" id="all">
                <table class="table table-striped dataTable">
                  <thead>
                    <tr>
                      <th><input type="checkbox" class="check_all"></th>
                      <th>Questions Title</th>
                      <th>University Name</th>
                      <th>Status</th>
					  <th>Featured</th>
					  <th>Answers Count</th>
					   <th style="width:16%!important;">Choose Option</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
				if(!empty($ques_info))
				{
				foreach($ques_info as $row){
				?>
					<tr id="check_university_<?php echo $row->que_id;?>" >
                      <td><input type="checkbox" class="selectable_checkbox setchkval" value="<?php echo $row->que_id;?>"></td>
                      <td><?php echo ucwords(substr($row->q_title,0,50)); ?></td>
                       <td><?php echo ucwords($row->univ_name); ?></td>					   
                       <td class="center"><?php if($row->q_approve){ echo "Disapprove"; } else { echo "Approve";} ?></td>
					   <td><?php if($row->q_featured_home_que){ echo "Make Unfeatured"; } else {  echo"Make Featured";} ?></td>
					   <td id="count_<?php echo $row->que_id; ?>"><?php $count=$this->ques_model->ans_count($row->que_id); echo $count; ?></td>	
					   <td class="options">
							<div class="btn-group">
							<?php if($view==1) { ?>
								<a href="<?php echo "$base"; ?>newadmin/admin_ques/edit_ques/<?php echo $row->que_id; ?>" class="btn btn-icon tip" data-original-title="View">
									<i class="icon-ok"></i>
								</a>
								<?php } if($edit==1) { ?>
								<a href="<?php echo "$base"; ?>newadmin/admin_ques/edit_ques/<?php echo $row->que_id; ?>" class="btn btn-icon tip" data-original-title="Edit">
									<i class="icon-pencil"></i>
								</a>
								<?php } if($delete==1){ ?>
								<div class="modal hide" id="myModal_<?php echo $row->que_id; ?>">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">x</button>
										<h3>Do you want to delete?</h3>
									</div>
									<div class="modal-footer">
										<a href="" onclick="delete_confirm('<?php echo $row->que_id; ?>');" class="btn" data-dismiss="modal">Yes</a>
										<a href="#" class="btn" data-dismiss="modal">Close</a>
									</div>
								</div>
								<a href="#myModal_<?php echo $row->que_id; ?>" class="btn btn-icon tip" data-toggle="modal" data-original-title="Delete">
									<i class="icon-trash"></i>
								</a>
								<?php }								
								$ques_title=$this->subdomain->process_url_title(substr($row->q_title,0,50));
								$ques_link=$this->subdomain->genereate_the_subdomain_link($row->subdomain_name,'ques',$ques_title,$row->que_id);
								?>
								<a href="<?php echo $ques_link; ?>" class="btn btn-icon tip" data-original-title="Preview">
									<i class="icon-film"></i>
								</a>
								<?php if(($edit==1 || $delete==1 || $insert==1) && $admin_user_level!='3') 
								{ ?>
								<a href="#"  onclick="approve_home_confirm('<?php echo "$base";?>newadmin/admin_ques','<?php  echo $row->q_approve; ?>','<?php echo $row->que_id; ?>');" class="btn btn-icon tip" <?php if($row->q_approve){ ?> data-original-title="Disapprove" <?php } else { ?> data-original-title="Approve" <?php } ?>>
									<i class="<?php if($row->q_approve){ echo 'icon-blue'; }?> icon-fire app_<?php echo $row->que_id; ?>"></i>
								</a>
								<a href="#" onclick="featured_home_confirm('<?php echo "$base";?>newadmin/admin_ques','<?php  echo $row->q_featured_home_que; ?>','<?php echo $row->que_id; ?>');" class="btn btn-icon tip" <?php if($row->q_featured_home_que){ ?> data-original-title="Unfeatured" <?php } else { ?> data-original-title="Featured" <?php } ?>>
									<i class="<?php if($row->q_featured_home_que){ echo 'icon-blue'; }?> icon-star feat_<?php echo $row->que_id; ?>"></i>
								</a>
								<?php } ?>
							</div>
						</td>
                     </tr>
					 <?php } } ?>
                     </tbody>
                </table>
				<?php if($delete==1) { ?> 	
			<div class="tableactions" style="margin-top:70px;">
				<select name="univ_action" id="univ_action">
					<option value="">Actions</option>
					<option value="delete">Delete</option>
				</select>
				
				<input type="button" onclick="action_formsubmit(0,0)" class="submit tiny" value="Apply to selected" />
			</div>		<!-- .tableactions ends -->
		<?php  } ?>
			  </div>
              <div class="tab-pane" id="new">
				 <table class="table table-striped dataTable">
                  <thead>
                    <tr>
                      <th><input type="checkbox" class="check_all"></th>
                      <th>Questions Title</th>
                      <th>University Name</th>
                      <th>Status</th>
					  <th>Featured</th>
					  <th>Answers Count</th>
					   <th style="width:16%!important;">Choose Option</th>
                    </tr>
                  </thead>
                  <tbody>
				  <?php
				if(!empty($latest_ques))
				{
				foreach($latest_ques as $latest){
				?>
					<tr id="check_university_<?php echo $latest->que_id;?>" >
                      <td><input type="checkbox" class="selectable_checkbox setchkval" value="<?php echo $latest->que_id;?>"></td>
                      <td><?php echo ucwords(substr($latest->q_title,0,50)); ?></td>
                       <td><?php echo ucwords($latest->univ_name); ?></td>					   
                       <td class="center"><?php if($latest->q_approve){ echo "Disapprove"; } else { echo "Approve";} ?></td>
					   <td><?php if($latest->q_featured_home_que){ echo "Make Unfeatured"; } else {  echo "Make Featured";} ?></td>
					   <td id="count_<?php echo $latest->que_id; ?>"><?php $count=$this->ques_model->ans_count($latest->que_id); echo $count; ?></td>	
					    <td class="options">
							<div class="btn-group">
							<?php if($view==1) { ?>
								<a href="<?php echo "$base"; ?>newadmin/admin_ques/edit_ques/<?php echo $latest->que_id; ?>" class="btn btn-icon tip" data-original-title="View">
									<i class="icon-ok"></i>
								</a>
								<?php } if($edit==1) { ?>
								<a href="<?php echo "$base"; ?>newadmin/admin_ques/edit_ques/<?php echo $latest->que_id; ?>" class="btn btn-icon tip" data-original-title="Edit">
									<i class="icon-pencil"></i>
								</a>
								<?php } if($delete==1){ ?>
								<div class="modal hide" id="myModal_<?php echo $latest->que_id; ?>">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">x</button>
										<h3>Do you want to delete?</h3>
									</div>
									<div class="modal-footer">
										<a href="" onclick="delete_confirm('<?php echo $latest->que_id; ?>');" class="btn" data-dismiss="modal">Yes</a>
										<a href="#" class="btn" data-dismiss="modal">Close</a>
									</div>
								</div>
								<a href="#myModal_<?php echo $latest->que_id; ?>" class="btn btn-icon tip" data-toggle="modal" data-original-title="Delete">
									<i class="icon-trash"></i>
								</a>
								<?php }								
								$ques_title=$this->subdomain->process_url_title(substr($latest->q_title,0,50));
								$ques_link=$this->subdomain->genereate_the_subdomain_link($latest->subdomain_name,'ques',$ques_title,$latest->que_id);
								?>
								<a href="<?php echo $ques_link; ?>" class="btn btn-icon tip" data-original-title="Preview">
									<i class="icon-film"></i>
								</a>
								<?php if(($edit==1 || $delete==1 || $insert==1) && $admin_user_level!='3') 
								{ ?>
								<a href="#" onclick="approve_home_confirm('<?php echo "$base";?>newadmin/admin_ques','<?php  echo $latest->q_approve; ?>','<?php echo $latest->que_id; ?>');" class="btn btn-icon tip" <?php if($latest->q_approve){ ?> data-original-title="Disapprove" <?php } else { ?> data-original-title="Approve" <?php } ?>>
									<i class="<?php if($latest->q_approve){ echo 'icon-blue'; }?> icon-fire app_<?php echo $latest->que_id; ?>"></i>
								</a>
								<a href="#" onclick="featured_home_confirm('<?php echo "$base";?>newadmin/admin_ques','<?php  echo $latest->q_featured_home_que; ?>','<?php echo $latest->que_id; ?>');" class="btn btn-icon tip" <?php if($latest->q_featured_home_que){ ?> data-original-title="Unfeatured" <?php } else { ?> data-original-title="Featured" <?php } ?>>
									<i class="<?php if($latest->q_featured_home_que){ echo 'icon-blue'; }?> icon-star feat_<?php echo $latest->que_id; ?>"></i>
								</a>
								<?php } ?>
							</div>
						</td>
                     </tr>
					 <?php } } ?>
                     </tbody>
                </table>
				<?php if($delete==1) { ?> 	
			<div class="tableactions" style="margin-top:70px;">
				<select name="univ_action" id="del_action">
					<option value="">Actions</option>
					<option value="delete">Delete</option>
				</select>
				
				<input type="button" onclick="action_formsubmit(0,0)" class="submit tiny" value="Apply to selected" />
			</div>		<!-- .tableactions ends -->
		<?php  } ?>	
              </div>
			  <div class="tab-pane" id="create">			  
				<div class="row-fluid">
					<div class="span9">
						<form class="form-horizontal" >
							<fieldset>							
								<div class="control-group">
								<input type="hidden" name="ques_type_ud" value="univ_ques"/>
								<label class="control-label" >Title</label>
								<div class="controls">
								<input type="text" id="title"  class="input-xlarge ">
								</div>
								</div>
								<?php if($admin_user_level['admin_user_level']!='3')
								{ ?>
								<div class="control-group">
								<label class="control-label" for="select01">Select Categories:</label>
								<div class="controls">
								<select id="category" name="category" onchange="fetch_collage(this);">
								<option value="general">Choose Type</option>			
								<option value="univ">College</option>			
								</select>		
								</div>
								</div>
								<div class="control-group">
								<label class="control-label" for="select0">Choose University:</label>
								<div class="controls1">
								<select id="colleges" name="colleges" class="colege_set">
								<option value="0"> select </option>	
								</select>
								</div>
								</div>			
								<?php }
								else
								{ ?>
									<input type="hidden" id="category" value="univ" />	
									<input type="hidden" id="colleges" value="<?php echo $univ_info['univ_id']; ?>" />
								<?php
								}
								?>
								<div class="control-group">
								<label class="control-label" for="input07">Detail</label>
								<div class="controls">
									<textarea name="detail" id="detail" class='span12' rows='8'></textarea>
								</div>
								</div>
								<div class="form-actions">
								<input type="button"  onclick="addQues()" class='btn btn-primary' value="Add Question" />
								<a href="#" class='btn btn-danger'>Cancel</a>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
              </div>
            </div>
          </div>
        </div>
      </div>
	</div><!-- close .container-fluid -->
  </div><!-- close .content -->

   <script>
$(document).ready(function(){
	//alert('fnslfc');
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
 </script>
 <script type="text/javascript">
function fetch_collage(values)
{
var type = values.value;
if(type == 'univ')
{
$.ajax({
   type: "POST",
   url: "<?php echo $base; ?>quest_ans_controler/collage_list_ajax",
   data: '',
   cache: false,
   success: function(msg)
   {
	$('#colleges').html(msg);
   }
   });
 }
 else if(type == 'sa')
 {
	$.ajax({
   type: "POST",
   url: "<?php echo $base; ?>quest_ans_controler/country_list_ajax",
   data: '',
   cache: false,
   success: function(msg)
   {
	$('#colleges').html(msg);
   }
   });
 }
}

function addQues()
{
	if($("#title").val()=='')
	{
		$("#title").addClass('needsfilled');		
	}
	if($("#detail").val()=='')
	{
		$("#detail").addClass('needsfilled');		
	}
		
	if($("#title").val()!='' && $("#detail").val()!='' )
	{
	
	var title=$('#title').val();
	var colleges=$('#colleges').val();
	var category=$('#category').val();
	var type=$('#ques_type_ud').val();
	var detail=$('#detail').val();
	var data={type:type,title:title,colleges:colleges,detail:detail,category:category};
	$.ajax({
	type:"POST",
	url:"<?php echo $base; ?>newadmin/admin_ques/add_ques",
	data:data,
	cache:false,
	success:function(msg)
	{
		if(msg=='1')
		{
			$('#addques').show();
			setTimeout(function(){$('#addques').hide('slow');},3000);
		}
	}
	});
	}
}
function approve_home_confirm(a,b,c)
{
	if(b==0)
	{
		status='approve';
	}
	if(b==1)
	{
		status='disapprove';
	}
	var r=confirm("Are you sure you want to " +status+ " to this question?");
	if (r==true)
	{
	  var url=a+'/approve_disapprove_ques/'+b+'/'+c+'/';
	  $.ajax({
	   type: "POST",
	   url: url,
	   async:false,
	   data: '',
	   cache: false,
	   success: function(msg)
		{
			if(msg=='1')
			{
				$('#approved').show();
				setTimeout(function(){$('#approved').hide('slow');},3000);
				$(".app_"+c).addClass('icon-blue');
			}
			else
			{
				$('#disapproved').show();
				setTimeout(function(){$('#disapproved').hide('slow');},3000);
				$(".app_"+c).removeClass('icon-blue');
			}
		}
		
	   });
	}
}
function featured_home_confirm(a,b,c)
{
	var nof='1';
	if(b=='0')
	{
		nof=chknooffeatured('q_featured_home_que');
	}
	if(nof=='1')
	{
		var status;
		if(b==0)
		{
		status='make home featured';
		}
		if(b==1)
		{
		status='make home unfeatured';
		}
		var r=confirm("Are you sure you want to " +status+ " to this question?");
		if (r==true)
		{
		  url=a+'/featured_unfeatured_ques/'+b+'/'+c+'/';
		  $.ajax({
	   type: "POST",
	   url: url,
	   async:false,
	   data: '',
	   cache: false,
	   success: function(msg)
		{
			if(msg=='1')
			{
				$('#featured').show();
				setTimeout(function(){$('#featured').hide('slow');},3000);
				$(".feat_"+c).addClass('icon-blue');
			}
			else
			{
				$('#unfeatured').show();
				setTimeout(function(){$('#unfeatured').hide('slow');},3000);
				$(".feat_"+c).removeClass('icon-blue');
			}
		}
		
	   });
		}
	}
	else
	{
		alert("You have reached maximum limit for show ques");
	}
}
function chknooffeatured(field)
{
	var f;
	$.ajax({
	   type: "POST",
	   url: "<?php echo $base; ?>newadmin/admin_ques/count_featured_ques/"+field,
	   async:false,
	   data: '',
	   cache: false,
	   success: function(msg)
	   {
			f=msg;
		}
	   });
	 return f;
}
function delete_confirm(id)
{
	//alert(newsid);
	$.ajax({	
	 type: "POST",
	   url: "<?php echo $base; ?>newadmin/admin_ques/delete_single_ques/"+id,
	   async:false,
	   data: '',
	   cache: false,
	   success: function(msg)
	   {//alert(msg);
		if(msg=='1')
		{
			$('#check_university_'+id).hide();
			$('#deleted').show();
			setTimeout(function(){$('#deleted').hide('slow');},3000);		
		}
		else
		{
			
			$('#access').show();
			setTimeout(function(){$('#access').hide('slow');},3000);	
		}
		}
	
	});
}
var arr=new Array;
function action_formsubmit(id,flag)
{
	var action;
	
	if($("#univ_action option:selected").val()!='')
	{
		action=$("#univ_action option:selected").val();
	}
	if($("#del_action option:selected").val()!='')
	{
		action=$("#del_action option:selected").val();
	}
		
	if(action=='delete')
	{
		var atLeastOneIsChecked = $('.setchkval:checked').length > 0;
		if(atLeastOneIsChecked)
		{
			var r=confirm("Are you sure you want to delete selected questions");
			set_chkbox_val();			
			var data={'que_id':arr};

			if(r)
			{
				$.ajax({
					type:"post",
					url:'<?php echo $base ?>newadmin/admin_ques/delete_ques',
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
			alert("please select at least one question");
			return false;
		}
	}
	else
	{
		alert("please select the action");
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

	</script>
