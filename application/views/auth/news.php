<div class="container">
		<div class="body_bar"></div>
		<div class="body_header"></div>
		<div class="body">
			<div class="row margin_t1">
				<div class="float_l span13 margin_l">
				<div class="float_l span9 margin_zero">
					<h2>Recent News</h2>
					<div>
						<ul class="event_new">
					<?php
					foreach($news as $news_detail){
					
					
					?>
						<li>
							<div class="float_l span5 margin_zero">
								<?php 
								$news_title =$this->subdomain->process_url_title($news_detail['news_title']);	
								$news_link=$this->subdomain->genereate_the_subdomain_link(
								$news_detail['subdomain_name'],'news',$news_title,$news_detail['news_id']);							
								?>
								<div class="margin_zero grid_3 fix_h3">	
								<h3><a href="<?php echo $news_link; ?>"><?php echo $news_detail['title']; ?></a></h3>
								</div>
							</div>
							<div class="float_r span4 margin_zero">
								<!--<div class="social_set float_r">
										<div id="gp" class="float_l">
											<g:plusone size='medium' id='shareLink' annotation='none' href='<?php echo $news_link; ?>' callback='countGoogleShares' data-count="true"></g:plusone>
										</div>
										<div id="tw" class="float_l tw">
											<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $news_link; ?>" data-via="munjal_sumit" data-lang="en">Tweet</a>
										</div>
										<div id="fb" class="float_r fb"><div class="fb-like" data-href="<?php echo $news_link; ?>" data-send="false" data-layout="button_count" data-width="20" data-show-faces="true" data-font="arial"></div></div>
										<div class="clearfix"></div>
									</div>-->
								</div>
								<div class="clearfix"></div>
							<div class="margin_t1 img_height">
								<div class="img_style float_l img_r aspectcorrect">
									
							<?php
									$image_exist=0;	
									$news_img = $news_detail['news_image_path'];	
									if(file_exists(getcwd().'/uploads/news_article_images/'.$news_img) && $news_img!='')	
									{
									$image_exist=1;
									list($width, $height, $type, $attr) = getimagesize(getcwd().'/uploads/news_article_images/'.$news_img);
									}
									else if(file_exists(getcwd().'/uploads/univ_gallery/'.$news_detail['univ_logo_path']) && $news_detail['univ_logo_path']!='')
									{
									$image_exist=2;
									list($width, $height, $type, $attr) = getimagesize(getcwd().'/uploads/univ_gallery/'.$news_detail['univ_logo_path']);
								    }
									else
									{
									list($width, $height, $type, $attr) = getimagesize(getcwd().'/'.$img_path.'/news_default_image.jpg');
								    }
									if($news_img!='' && $image_exist==1)
									{
									$image=$base.'uploads/news_article_images/'.$news_img;
									}
									else if($news_detail['univ_logo_path']!='' && $image_exist==2)
									{
									$image=$base.'uploads/univ_gallery/'.$news_detail['univ_logo_path'];
									}
									else
									{
									$image=$base.$img_path.'/news_default_image.jpg';
									} 
									$img_arr=$this->searchmodel->set_the_image($width,$height,110,75,TRUE);
							?>
								
									
							
									<img src="<?php echo $image; ?>" style="left:<?php echo $img_arr['targetleft']; ?>px;top:<?php echo $img_arr['targettop']; ?>px;width:<?php echo $img_arr['width']; ?>px;height:<?php echo $img_arr['height']; ?>px;margin-right:20px"/>
								</div>
								<div><img src="<?php echo "$base$img_path"; ?>/clock.png" class="line_img inline"><span class="line_time"><abbr class="timeago time_ago" title="<?php echo $news_detail['publish_time']; ?>"></abbr>
										</span></div>
								<?php echo substr($news_detail['news_detail'],0,250).'..'; ?>
							</div>
						</li>
						<?php } ?>
					</ul>
					
				
					</div>
				</div>
				<div class="float_l span4">
					<div class="back_up">
						<h3><img src="<?php echo base_url(); ?>images/home_cal.gif" style="z-index: 100;position: relative;top:6px;"><span style="position: relative;left: 10px;">Popular News</span></h3>
						<ul class="up_event">
						<?php if(!empty($popular_news)){
								foreach($popular_news as $popular_news_detail)
								{
								$news_title_list =$this->subdomain->process_url_title($popular_news_detail['news_title']);	
								$news_link=$this->subdomain->genereate_the_subdomain_link(
								$popular_news_detail['subdomain_name'],'news',$news_title_list,$popular_news_detail['news_id']);
								?>
									<li><a href="<?php echo $news_link; ?>"><?php echo $popular_news_detail['news_title']; ?></a></li>
								<?php } } ?>
						</ul>
					</div>
				</div>
			</div>
			
				<div class="float_r span3">
					<a href="http://university-of-greenwich.meetuniversities.com/university_events"><img src="<?php echo "$base$img_path" ?>/banner_img.png"></a>
					
				</div>
				<div id="pagination" class="table_pagination right paging-margin">
            <?php echo $this->pagination->create_links();?>
            </div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>