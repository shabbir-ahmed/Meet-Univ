<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Search extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->load->helper('url');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->helper('string');
		$this->load->library('email');
		$this->ci =& get_instance();
		$this->load->library('fbConn/facebook');
	}
	
	function index()
	{
	}
	
	function college_search()
	{
			$data = $this->path->all_path();
			$data['err_div']=0;
			$this->load->view('auth/header',$data);
			$data['gallery_home'] = $this->users->fetch_home_gallery();
			$data['country'] = $this->users->fetch_country();
			$data['area_interest'] = $this->users->fetch_area_interest();
			$data['fetch_educ_level'] =$this->users->fetch_educ_level();
		if($this->input->get('btn_search')=='col_search')
		{
				//$this->form_validation->set_rules('type_search','Education Level','trim|required');
				//$this->form_validation->set_rules('search_country','Country','trim|required');
				
			//if($this->form_validation->run())
			//{
				$config['geocodeCaching'] = TRUE;
				//$this->googlemaps->initialize($config);
				$type_educ_level = $this->input->get('type_search');
				$search_country = $this->input->get('search_country');
				$search_course = $this->input->get('search_program');
				$data['get_university'] = $this->searchmodel->get_collages_by_search1($type_educ_level,$search_country,$search_course);
				//print_r($data['get_university']);
				
				
				/* code for google map api v.3 */
				// Load the library
					$this->load->library('GMap');
					$this->gmap->GoogleMapAPI();
					$cnt_pos_univ = count($data['get_university']['position']);
					//print_r($data['get_university']['position'][0]);
					//echo 'Position-'.$cnt_pos_univ;
					// Initialize our map. Here you can also pass in additional parameters for customising the map (see below)
					//$this->googlemaps->initialize($config);
					// Create the map. This will return the Javascript to be included in our pages <head></head> section and the HTML code to be
					// placed where we want the map to appear.
					
					// valid types are hybrid, satellite, terrain, map
						$this->gmap->setMapType('map');

						// you can also use addMarkerByCoords($long,$lat)
						// both marker methods also support $html, $tooltip, $icon_file and $icon_shadow_filename
						$marker = array();
						//$marker_icon = 'http://localhost/www/Meet-Univ/images/location-marker.png';
						
							for($pos = 0; $pos < $cnt_pos_univ; $pos++)
							{
								$marker = explode("||",$data['get_university']['position'][$pos]);
								//print_r($data['get_university']['position']);
								//print_r($marker);
								$this->gmap->addMarkerByAddress($marker[0],$marker[1],$marker[2]);
								//$this->gmap->addOverlay();
								//$this->googlemaps->add_marker($marker);
							}
							
						
						//$this->$gmap->setMarkerIconKey($marker_icon,$marker_icon,0,0,10,10); 
						$data['headerjs'] = $this->gmap->getHeaderJS();
						$data['headermap'] = $this->gmap->getMapJS();
						$data['onload'] = $this->gmap->printOnLoad();
						$data['map'] = $this->gmap->printMap();
						$data['sidebar'] = $this->gmap->printSidebar();

					
					
					
					//$marker = array();
					// for($pos = 0; $pos < $cnt_pos_univ; $pos++)
					// {
						// $marker['position'] = $data['get_university']['position'][$pos];
						// $this->googlemaps->add_marker($marker);
					// }
					// $marker['position'] = '51.5115,-0.0921';
					// $this->googlemaps->add_marker($marker);
					//$data['map'] = $this->googlemaps->create_map();
					// Load our view, passing the map data that has just been created
					//$this->load->view('my_view', $data);
				
				if($data['get_university'] != 0 )
				{
				$this->load->view('auth/listed_collage',$data);
				}
				else{
				$data['err_msg']='<h2> Sorry....</br><span class="text-align">No result Found.... </span> </h2>';
				$data['err_div']=1;
				$this->load->view('auth/NotFoundPage',$data);
				}
			//}
			
		}
		else{
		$this->load->view('auth/listed_collage',$data);
		}
		$this->load->view('auth/footer',$data);
	}
	function events_search()
	{
		$data = $this->path->all_path();
		$this->load->view('auth/header',$data);
		$data['city_name_having_event'] = $this->leadmodel->city_name_having_event();
		$data['err_div']=0;
		$data['selected_month']=$this->input->get('event_month');
		 if($this->input->get('event_month')=='' && $this->input->get('event_city')=='' && $this->input->get('type_search')=='0')
		 {
			redirect(base_url().'events');
		 }
		 /* else if($this->input->get('event_month')!='' && $this->input->get('event_city')!='' && $this->input->get('type_search')=='0')
		 {
			$type='all';
			$month = '1';
			$city = '1';
			$data['events'] = $this->searchmodel->events($type,$month,$city);
		 }
		 else if($this->input->get('event_month')!='' && $this->input->get('event_city')=='' && $this->input->get('type_search')=='0')
		 {
			
		 } */
		 else if($this->input->get('event_month')=='' && $this->input->get('event_city')=='' && $this->input->get('type_search')=='spot_admission')
		 {
			redirect(base_url().'spot_admission_events');
		 }
		 /* else if($this->input->get('event_month')!='' && $this->input->get('event_city')!='' && $this->input->get('type_search')=='spot_admission')
		 {
			
		 } */
		 else if($this->input->get('event_month')=='' && $this->input->get('event_city')=='' && $this->input->get('type_search')=='fairs')
		 {
			redirect(base_url().'fairs_events');
		 }
		/*  else if($this->input->get('event_month')!='' && $this->input->get('event_city')!='' && $this->input->get('type_search')=='fairs')
		 {
			
		 } */
		 else if($this->input->get('event_month')=='' && $this->input->get('event_city')=='' && $this->input->get('type_search')=='others')
		 {
			redirect(base_url().'Counselling_events');
		 }
		 /* else if($this->input->get('event_month')!='' && $this->input->get('event_city')!='' && $this->input->get('type_search')=='others')
		 {
			
		 } */
		 else if($this->input->get('event_month')=='' && $this->input->get('event_city')=='' && $this->input->get('type_search')=='alumuni')
		 {
			redirect(base_url().'Counselling_events');
		 }
		 else{
		 $data['events'] = $this->searchmodel->serach_events();
		 if($data['events'] != 0)
		 {
			$this->load->view('auth/search_event_results',$data);
		 }
		 else {
		 $data['err_msg']='<h2> Sorry....</br><span class="text-align">No result Found.... </span> </h2>';
		$data['err_div']=1;
		$this->load->view('auth/NotFoundPage',$data);
		 }
		 }
		 /* else if($this->input->get('event_month')!='' && $this->input->get('event_city')!='' && $this->input->get('type_search')=='alumuni')
		 {
			
		 } */
		/*else if($this->input->get('event_month')!='' || $this->input->get('event_city')!='')
		{
		$data['events']=$this->searchmodel->serach_events();
		if($data['events']!=0)
		{
		$this->load->view('auth/search_event_results',$data);
		}
		else
		{
		$data['err_msg']='<h2> Sorry....</br><span class="text-align">No result Found.... </span> </h2>';
		$data['err_div']=1;
		$this->load->view('auth/NotFoundPage',$data);
		}
		}
		else
		{
		$data['err_msg']='<h2> Sorry....</br><span class="text-align">No result Found.... </span> </h2>';
		$data['err_div']=1;
		$this->load->view('auth/NotFoundPage',$data);
		} */
		
		$this->load->view('auth/footer',$data);
	}
	
	function fetch_parent_progrmas_on_home_ajax()
	{
		$data['result']=$this->searchmodel->fetch_program_list_ajax();
		$this->load->view('ajaxviews/program_list',$data);
		
	}		function cronjob()		{	 $this->load->library('googleanalytics');	 $this->load->library('email');	 $ga = new GoogleAnalytics(); 	 $ga->setProfile('ga:60386809');	 $yesterday_date=date('Y-m-d',strtotime('1 day ago'));	 $y_date=str_replace('-','',$yesterday_date);	 $ga->setDateRange($yesterday_date,$yesterday_date);	 $data['report'] = $ga->getReport(array('dimensions'=>urlencode('ga:date'),'metrics'=>urlencode('ga:pageviews,ga:uniquePageviews,ga:visitors,ga:newVisits')));		 $noofvisitor=$data['report'][$y_date]['ga:visitors'];		 $noofuniquevisitor=$data['report'][$y_date]['ga:newVisits'];		 $pageviews=$data['report'][$y_date]['ga:pageviews'];		 $uniquepageviews=$data['report'][$y_date]['ga:uniquePageviews'];	 	 $to=array('nitin@globalcampusmedia.com','himanshu@globalcampusmedia.com','debal@webinfomart.com','keshavmunjal@webinfomart.com');	 $subject = "Google Analytics Daily Report";	 $message = "Dear sir,<br /> Yesterday report of site visitors is described below<br /><br />";	 $message.="Total No of Visitors : ".$noofvisitor.'<br /><br />';	 $message.="Total No of Unique Visitors : ".$noofuniquevisitor.'<br /><br />';	 $message.="Total No of Page Views : ".$pageviews.'<br /><br />';	 $message.="Total No of Unique Page Views : ".$uniquepageviews.'<br />';	 $body = $message ;	 $config['protocol'] = $this->config->item('mail_protocol');	 $config['smtp_host'] = $this->config->item('smtp_server'); 	 $config['smtp_user'] = $this->config->item('smtp_user_name');	 $config['smtp_pass'] = $this->config->item('smtp_pass');	 $this->email->initialize($config);    	 $this->email->from('info@meetuniversities.com', 'MeetUniversities.com');	 $this->email->to($to);	      $this->email->subject($subject);       $this->email->message($body);     $this->email->send();	}
}
?>