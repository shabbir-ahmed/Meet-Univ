<div class="container">
		<div class="body_bar"></div>
		<div class="body_header"></div>
		<div class="body">
			<div class="row margin_t1">
				<div class="float_l span13 margin_l">
				<div class="float_l span4 margin_zero">
						<div id="event_calendar">
						
						</div>
						<div id="event_search_filter">
						<h3> <div style="color:#7D7E7D;margin-top:9px;margin-left:19px;" > Filter By City </div> </h3>
						<?php 
						if(!empty($city_name_having_event))
						{
							foreach($city_name_having_event as $citynames)
							{
								echo "<div id='cityname' style='margin-left:17px;'><h3><a style='cursor:pointer;' id=".$citynames['city_id']." onclick='filter_city(this)'><div class='city_name_filter'> ".$citynames['cityname']."</div> </a></h3></div>";
							}
						}
						?>
						</div>
					</div>
					<div class="float_r" style="width:585px;">
					<h2 class="course_txt">Events in <?php echo $selected_month; ?> Month </h2>
					<div class="margin_t1">
					
					<?php
					if(!empty($events))
								{
								$array_dates = array();
								foreach($events as $event_detail){ 
								$var_date = '';
								//echo $event_detail['event_date_time'];
								$extract_date = explode(" ",$event_detail['event_date_time']);
								//echo $extract_date[];
								$month = $extract_date[1];
								$number_month = date('m', strtotime($month));
								//echo $extract_date[0];
								//echo $number_months; //= $number_month-1 ;
								//echo $extract_date[2];
								$var = "'".$number_month.'/'.$extract_date[0].'/'.$extract_date[2]."'";
								array_push($array_dates,$var);
								} }
								?>
							<div id="loading_img" style="z-index:-1;margin-left: 252px;margin-top: 139px;position:absolute;"> <img src="<?php echo "$base$img_path"; ?>/AjaxLoading.gif"/> </div>
					
						<div id="event1" class="">
						<?php 
						if(!empty($events))
						{
						foreach($events as $event_detail){ ?>
						<div class="event_border">
							<div class="float_l">
								<?php if($event_detail['univ_logo_path']==''){?>
								<img src="<?php echo "$base$img_path"; ?>/default_logo.png" style="width:80px;height:80px;margin-right:20px">
								<?php } else {?>
								<img src="<?php echo $base; ?>/uploads/univ_gallery/<?php echo $event_detail['univ_logo_path']; ?>" style="width:80px;height:80px;margin-right:20px" >
								<?php } ?>	
							</div>
							<div class="dsolution">
								<div>
									<div class="float_l">
<h3><a href="<?php echo $base;?>univ-<?php echo $event_detail['univ_id']; ?>-event-<?php echo $event_detail['event_id']; ?>"><?php echo $event_detail['event_title']; ?>
				</a></h3>
										<span>
										<?php echo $event_detail['cityname'].",".$event_detail['statename'].",".$event_detail['country_name']?>,
										
										<strong><?php echo $event_detail['event_date_time']; ?></strong>
										</span><br/>
									</div>
									<div class="float_r">
										<!--<h4>22 Register</h4>-->
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="course_cont"><?php echo substr($event_detail['event_detail'],0,250).'..'; ?>
								
								</div>
							</div>
							<div class="float_r margin_t1">
							<form action="find_college/<?php echo $event_detail['univ_id'].'/'.$event_detail['event_id']; ?>" method="post">
							<button class="btn btn-success" href="#">Register</button>
							</form>
							</div>
							<div class="clearfix"></div>
						</div>
					<?php } } ?>	
					</div>
					</div> </div>
				</div>
				<div class="float_r span3">
					<img src="images/banner_img.png">
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
<?php 
if(!empty($events))
{
$array_dates=implode(',',$array_dates);
}
else{
$array_dates = date("m/d/Y");
}
//echo $event_detail['event_date_time'];
$show_date = '';
								//echo $event_detail['event_date_time'];
								if(!empty($events))
								{
								$show_current_date = explode(" ",$event_detail['event_date_time']);
								//echo $extract_date[];
								$month = $extract_date[1];
								//echo $show_current_date[2];
								$number_month = date('m', strtotime($month));
								$number_month = $number_month -1;
								}
								else{
								$show_current_date[2] = date('Y');
								$number_month = date('m');
								}
			// foreach($array_dates as $dates){
			// echo $dates;
			// }?>
<?php $event_month = $this->input->get('event_month'); ?>
<script type="text/javascript">
var event_month = '<?php echo $event_month; ?>';
var x = new Array(<?php echo $array_dates; ?>);
//'12/04/2012';
			$(document).ready(function () {
				//$("#example").thingerlyCalendar();
				$("#event_calendar").thingerlyCalendar({
					'month' : <?php echo $number_month; ?>,
					'year' : <?php echo $show_current_date[2]; ?>,
          'transition' : 'slide',
					'viewTransition' : 'fade',
          'events' : [
		  <?php echo $array_dates; ?>
          ],
					'eventClick' : function(e) {
					FB.XFBML.parse();
					$('#event1').animate({
					opacity: 0.1,
					});
					//$('#event1').fadeTo('slow', 0.3);
					$('#loading_img').css('z-index', 1);
					var d=new Date(e);
					//alert(e.toSource());
					var x = d.getDate().toString();
					var z = d.getFullYear().toString();
					var month=new Array();
						month[0]="Jan";
						month[1]="Feb";
						month[2]="Mar";
						month[3]="Apr";
						month[4]="May";
						month[5]="Jun";
						month[6]="Jul";
						month[7]="Aug";
						month[8]="Sep";
						month[9]="Oct";
						month[10]="Nov";
						month[11]="Dec";
						var n = month[d.getMonth()];
						var y = n.toString();
					var complete_date = x + y+ z;
					var searchUrl = "<?php echo $base; ?>/univ/event_search_page_by_calendar";
					//fetch result according calendar date
					//alert('hello');
					
					$.ajax({
						type: "POST",
						url: searchUrl,
						data:'date='+x+"&month="+y+"&year="+z+"&type=all",
						success: function(response)
						{
							//$('#content_search').html(response);
							//alert(response);
							$('#event1').html(response);
							$('#event1').animate({
								opacity: 1,
							});
							$('#loading_img').css('z-index',"-1");
						}
					});
						/* alert(complete_date);
						alert(d.getDate());
						alert(d.getMonth());
						alert(d.getFullYear()); */
					}
          });

			});
		</script>

<script>
function filter_city(atribute)
{
$('#event1').animate({
					opacity: 0.1,
					});
					//$('#event1').fadeTo('slow', 0.3);
					$('#loading_img').css('z-index', 1);
var event_city_id = atribute.id;
var searchUrl = "<?php echo $base; ?>/univ/event_filter_by_city";
	$.ajax({
						type: "POST",
						url: searchUrl,
						data:'event_city='+event_city_id+"&event_month="+event_month,
						success: function(response)
						{
							//$('#content_search').html(response);
							$('#event1').html(response);
							$('#event1').animate({
								opacity: 1,
							});
							$('#loading_img').css('z-index',"-1");
						}
					});
}
</script>		