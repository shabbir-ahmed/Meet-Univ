<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		// $data['base'] = $this->config->item('base_url');
		// $data['css'] = $this->config->item('css_path');
		// $data['css'] = $this->config->item('img_path');
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		//$this->ci->load->config('tank_auth', TRUE);
		//$this->ci->load->library('session');
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
		$data = $this->path->all_path();
		/*  Upload code */
		//$this->load->model('Gallery_model');
		
		$data['gallery_home'] = $this->users->fetch_home_gallery();
		$data['country'] = $this->users->fetch_country();
		$data['area_interest'] = $this->users->fetch_program();
		/*  Upload code end */
		$this->load->view('auth/header',$data);
		$this->load->view('auth/home',$data);
		
		//$this->load->view('auth/home',$data);
		/*if (!$this->tank_auth->is_logged_in()) {
			redirect('/login/');
		} else {
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			$logged_user = $data['user_id'];
			$this->load->model('users');
			$data['query'] = $this->users->fetch_all_data($logged_user);
			$data['profile_pic'] = $this->users->fetch_profile_pic($logged_user);
		//	print_r($data['profile_pic']);
			$data['educ_level'] = $this->users->fetch_educ_level();
			$data['country'] = $this->users->fetch_country();
			//print_r($data['country']);
			$data['area_interest'] = $this->users->fetch_area_interest();
			$this->load->view('auth/profile',$data);
			//$this->load->view('welcome', $data);
		}
		if ($this->input->post('upload')) {
			$this->users->do_upload();
		}*/
		
		//$data['images'] = $this->users->get_images();
		$this->load->view('auth/footer',$data);
	}
	function home($pwd_change='')
	{
		$data = $this->path->all_path();
		$data['pwd_change']=$pwd_change;
		
		//$this->load->view('welcome');
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/login/');
		} else {
			$this->load->view('auth/header',$data);
			$data['user_id']	= $this->tank_auth->get_user_id();
			$data['username']	= $this->tank_auth->get_username();
			$logged_user = $data['user_id'];
			$this->load->model('users');
			$data['fetch_profile'] = $this->users->fetch_profile($logged_user);
			$data['query'] = $this->users->fetch_all_data($logged_user);
			$data['profile_pic'] = $this->users->fetch_profile_data($logged_user); 
		//	print_r($data['profile_pic']);
			$data['educ_level'] = $this->users->fetch_educ_level();
			$data['country'] = $this->users->fetch_country();
			//print_r($data['country']);
			$data['area_interest'] = $this->users->fetch_area_interest();
			$this->load->view('auth/profile',$data);
			
		}
		if ($this->input->post('upload')) {
			$this->users->do_upload();
		}
		$this->load->view('auth/footer', $data);
	}

	/**
	 * Login user on the site
	 *
	 * @return void
	 */
	function login()
	{
		$data = $this->path->all_path();
	$this->load->view('auth/header',$data);
		if ($this->tank_auth->is_logged_in()) {									// logged in
			redirect('home');

		} else {
			$data['login_by_username'] = ($this->config->item('login_by_username', 'tank_auth') AND
					$this->config->item('use_username', 'tank_auth'));
			$data['login_by_email'] = $this->config->item('login_by_email', 'tank_auth');

			$this->form_validation->set_rules('login', 'Login', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('remember', 'Remember me', 'integer');
			$this->form_validation->set_rules('user_type', 'user_type', 'string');

			// Get login for counting attempts to login
			if ($this->config->item('login_count_attempts', 'tank_auth') AND
					($login = $this->input->post('login'))) {
				$login = $this->security->xss_clean($login);
			} else {
				$login = '';
			}

			//$data['use_recaptcha'] = $this->config->item('use_recaptcha', 'tank_auth');
			/*if ($this->tank_auth->is_max_login_attempts_exceeded($login)) {
				if ($data['use_recaptcha'])
					$this->form_validation->set_rules('recaptcha_response_field', 'Confirmation Code', 'trim|xss_clean|required|callback__check_recaptcha');
				else
					$this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|xss_clean|required|callback__check_captcha');
			}*/
			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->login(
						$this->form_validation->set_value('login'),
						$this->form_validation->set_value('password'),
						$this->form_validation->set_value('remember'),
						$data['login_by_username'],
						$data['login_by_email'],
						$this->form_validation->set_value('user_type')
						)) {								// success
					redirect('home');

				} else {
					$errors = $this->tank_auth->get_error_message();
					if (isset($errors['banned'])) {								// banned user
						$this->_show_message($this->lang->line('auth_message_banned').' '.$errors['banned']);

					} elseif (isset($errors['not_activated'])) {				// not activated user
						redirect('/auth/send_again/');

					} else {													// fail
						foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
					}
				}
			}
			/*$data['show_captcha'] = FALSE;
			if ($this->tank_auth->is_max_login_attempts_exceeded($login)) {
				$data['show_captcha'] = TRUE;
				if ($data['use_recaptcha']) {
					$data['recaptcha_html'] = $this->_create_recaptcha();
				} else {
					$data['captcha_html'] = $this->_create_captcha();
				}
			}*/
			$this->load->view('auth/login', $data);
		}
		$this->load->view('auth/footer',$data);
	}

	/**
	 * Logout user
	 *
	 * @return void
	 */
	function logout()
	{
		$this->tank_auth->logout();
		redirect('login');
		//$this->_show_message($this->lang->line('auth_message_logged_out'));
	}

	/**
	 * Register user on the site
	 *
	 * @return void
	 */
	function register()
	{
	$data = $this->path->all_path();
	$this->load->view('auth/header',$data);
		
		if ($this->tank_auth->is_logged_in()) {									// logged in
			redirect('home');

		} elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/send_again/');

		} elseif (!$this->config->item('allow_registration', 'tank_auth')) {	// registration is off
			$this->_show_message($this->lang->line('auth_message_registration_disabled'));

		} else {
			$use_username = $this->config->item('use_username', 'tank_auth');
			if ($use_username) {
				$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|min_length['.$this->config->item('username_min_length', 'tank_auth').']|max_length['.$this->config->item('username_max_length', 'tank_auth').']|alpha_dash');
			}
			$this->form_validation->set_rules('fullname', 'Fullname', 'trim|required|xss_clean');
			$this->form_validation->set_rules('createdby', 'Createdby', 'trim');
			$this->form_validation->set_rules('agree_term', 'I Agree', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]');
			$this->form_validation->set_rules('level_user', 'level_user', 'trim|string');
			$this->form_validation->set_rules('user_type', 'user_type', 'string');
			
			//$captcha_registration	= $this->config->item('captcha_registration', 'tank_auth');
			//$use_recaptcha			= $this->config->item('use_recaptcha', 'tank_auth');
			/*if ($captcha_registration) {
				if ($use_recaptcha) {
					$this->form_validation->set_rules('recaptcha_response_field', 'Confirmation Code', 'trim|xss_clean|required|callback__check_recaptcha');
				} else {
					$this->form_validation->set_rules('captcha', 'Confirmation Code', 'trim|xss_clean|required|callback__check_captcha');
				}
			}*/
			$data['errors'] = array();

			$email_activation = $this->config->item('email_activation', 'tank_auth');

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->create_user(
						$use_username ? $this->form_validation->set_value('username') : '',
						$this->form_validation->set_value('fullname'),
						$this->form_validation->set_value('createdby'),
						$this->form_validation->set_value('level_user'),
						$this->form_validation->set_value('email'),
						$this->form_validation->set_value('password'),
						$this->form_validation->set_value('user_type'),
						$email_activation
						))) {									// success
						//$CI->session->userdata('name');
						//Send Email for new registration
						//print_r($this->session->userdata);
						$uid = $this->session->userdata('user_id');
						$data['logged_user_email'] = $this->users->get_email_by_userid($uid);
						$uid = $data['logged_user_email'];
						$email_body = $this->load->view('auth/new_signup_content_email.php','',TRUE);
						$this->email->set_newline("\r\n");

            $this->email->from('Meet-University.com', 'Meet University');
            $this->email->to($uid);
            $this->email->subject('New Registration');
            $message = "$email_body" ;
            //$message .="<br/>Thank you very much";
            $this->email->message($message);
			print_r($message);
			$this->email->send();
			redirect('home');
						
						
					$data['site_name'] = $this->config->item('website_name', 'tank_auth');

					if ($email_activation) {									// send "activate" email
						$data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;

						$this->_send_email('activate', $data['email'], $data);

						unset($data['password']); // Clear password (just for any case)

						$this->_show_message($this->lang->line('auth_message_registration_completed_1'));

					} else {
						if ($this->config->item('email_account_details', 'tank_auth')) {	// send "welcome" email

							$this->_send_email('welcome', $data['email'], $data);
						}
						unset($data['password']); // Clear password (just for any case)

						$this->_show_message($this->lang->line('auth_message_registration_completed_2').' '.anchor('/auth/login/', 'Login'));
					}
					
				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			/*if ($captcha_registration) {
				if ($use_recaptcha) {
					$data['recaptcha_html'] = $this->_create_recaptcha();
				} else {
					$data['captcha_html'] = $this->_create_captcha();
				}
			}*/
			$data['use_username'] = $use_username;
			//$data['captcha_registration'] = $captcha_registration;
			//$data['use_recaptcha'] = $use_recaptcha;
			//$data['base'] = $base;
			
			$this->load->view('auth/register', $data);
		}
		$this->load->view('auth/footer',$data);
	}

	/**
	 * Send activation email again, to the same or new email address
	 *
	 * @return void
	 */
	function send_again()
	{
		if (!$this->tank_auth->is_logged_in(FALSE)) {							// not logged in or activated
			redirect('/login/');

		} else {
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->change_email(
						$this->form_validation->set_value('email')))) {			// success

					$data['site_name']	= $this->config->item('website_name', 'tank_auth');
					$data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;

					$this->_send_email('activate', $data['email'], $data);

					$this->_show_message(sprintf($this->lang->line('auth_message_activation_email_sent'), $data['email']));

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->load->view('auth/send_again_form', $data);
		}
	}

	/**
	 * Activate user account.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function activate()
	{
		$user_id		= $this->uri->segment(3);
		$new_email_key	= $this->uri->segment(4);

		// Activate user
		if ($this->tank_auth->activate_user($user_id, $new_email_key)) {		// success
			$this->tank_auth->logout();
			$this->_show_message($this->lang->line('auth_message_activation_completed').' '.anchor('/auth/login/', 'Login'));

		} else {																// fail
			$this->_show_message($this->lang->line('auth_message_activation_failed'));
		}
	}

	/**
	 * Generate reset code (to change password) and send it to user
	 *
	 * @return void
	 */
	/*function forgot_password()
	{
		if ($this->tank_auth->is_logged_in()) {									// logged in
			redirect('');

		} elseif ($this->tank_auth->is_logged_in(FALSE)) {						// logged in, not activated
			redirect('/send_again/');

		} else {
			$this->form_validation->set_rules('login', 'Email or login', 'trim|required|xss_clean');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->forgot_password(
						$this->form_validation->set_value('login')))) {

					$data['site_name'] = $this->config->item('website_name', 'tank_auth');

					// Send email with password activation link
					$this->_send_email('forgot_password', $data['email'], $data);

					$this->_show_message($this->lang->line('auth_message_new_password_sent'));

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->load->view('auth/forgot_password_form', $data);
		}
	}
*/
	/**
	 * Replace user password (forgotten) with a new one (set by user).
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function reset_password() 
	{
		$user_id		= $this->uri->segment(3);
		$new_pass_key	= $this->uri->segment(4);

		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
		$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

		$data['errors'] = array();

		if ($this->form_validation->run()) {								// validation ok
			if (!is_null($data = $this->tank_auth->reset_password(
					$user_id, $new_pass_key,
					$this->form_validation->set_value('new_password')))) {	// success

				$data['site_name'] = $this->config->item('website_name', 'tank_auth');

				// Send email with new password
				$this->_send_email('reset_password', $data['email'], $data);

				$this->_show_message($this->lang->line('auth_message_new_password_activated').' '.anchor('/auth/login/', 'Login'));

			} else {														// fail
				$this->_show_message($this->lang->line('auth_message_new_password_failed'));
			}
		} else {
			// Try to activate user by password key (if not activated yet)
			if ($this->config->item('email_activation', 'tank_auth')) {
				$this->tank_auth->activate_user($user_id, $new_pass_key, FALSE);
			}

			if (!$this->tank_auth->can_reset_password($user_id, $new_pass_key)) {
				$this->_show_message($this->lang->line('auth_message_new_password_failed'));
			}
		}
		$this->load->view('auth/reset_password_form', $data);
	}

	

	/**
	 * Change user email
	 *
	 * @return void
	 */
	function change_email()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('/login/');

		} else {
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if (!is_null($data = $this->tank_auth->set_new_email(
						$this->form_validation->set_value('email'),
						$this->form_validation->set_value('password')))) {			// success

					$data['site_name'] = $this->config->item('website_name', 'tank_auth');

					// Send email with new email address and its activation link
					$this->_send_email('change_email', $data['new_email'], $data);

					$this->_show_message(sprintf($this->lang->line('auth_message_new_email_sent'), $data['new_email']));

				} else {
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->load->view('auth/change_email_form', $data);
		}
	}

	/**
	 * Replace user email with a new one.
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function reset_email()
	{
		$user_id		= $this->uri->segment(3);
		$new_email_key	= $this->uri->segment(4);

		// Reset email
		if ($this->tank_auth->activate_new_email($user_id, $new_email_key)) {	// success
			$this->tank_auth->logout();
			$this->_show_message($this->lang->line('auth_message_new_email_activated').' '.anchor('/auth/login/', 'Login'));

		} else {																// fail
			$this->_show_message($this->lang->line('auth_message_new_email_failed'));
		}
	}

	/**
	 * Delete user from the site (only when user is logged in)
	 *
	 * @return void
	 */
	function unregister()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('/login/');

		} else {
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->delete_user(
						$this->form_validation->set_value('password'))) {		// success
					$this->_show_message($this->lang->line('auth_message_unregistered'));

				} else {														// fail
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->load->view('auth/unregister_form', $data);
		}
	}

	/**
	 * Show info message
	 *
	 * @param	string
	 * @return	void
	 */
	function _show_message($message)
	{
		$this->session->set_flashdata('message', $message);
		redirect('/auth/');
	}

	/**
	 * Send email message of given type (activate, forgot_password, etc.)
	 *
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	void
	 */
	function _send_email($type, $email, &$data)
	{
		$this->load->library('email');
		$this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
		$this->email->to($email);
		$this->email->subject(sprintf($this->lang->line('auth_subject_'.$type), $this->config->item('website_name', 'tank_auth')));
		$this->email->message($this->load->view('email/'.$type.'-html', $data, TRUE));
		$this->email->set_alt_message($this->load->view('email/'.$type.'-txt', $data, TRUE));
		$this->email->send();
	}

	/**
	 * Create CAPTCHA image to verify user as a human
	 *
	 * @return	string
	 */
	function _create_captcha()
	{
		$this->load->helper('captcha');

		$cap = create_captcha(array(
			'img_path'		=> './'.$this->config->item('captcha_path', 'tank_auth'),
			'img_url'		=> base_url().$this->config->item('captcha_path', 'tank_auth'),
			'font_path'		=> './'.$this->config->item('captcha_fonts_path', 'tank_auth'),
			'font_size'		=> $this->config->item('captcha_font_size', 'tank_auth'),
			'img_width'		=> $this->config->item('captcha_width', 'tank_auth'),
			'img_height'	=> $this->config->item('captcha_height', 'tank_auth'),
			'show_grid'		=> $this->config->item('captcha_grid', 'tank_auth'),
			'expiration'	=> $this->config->item('captcha_expire', 'tank_auth'),
		));

		// Save captcha params in session
		$this->session->set_flashdata(array(
				'captcha_word' => $cap['word'],
				'captcha_time' => $cap['time'],
		));

		return $cap['image'];
	}

	/**
	 * Callback function. Check if CAPTCHA test is passed.
	 *
	 * @param	string
	 * @return	bool
	 */
	function _check_captcha($code)
	{
		$time = $this->session->flashdata('captcha_time');
		$word = $this->session->flashdata('captcha_word');

		list($usec, $sec) = explode(" ", microtime());
		$now = ((float)$usec + (float)$sec);

		if ($now - $time > $this->config->item('captcha_expire', 'tank_auth')) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_captcha_expired'));
			return FALSE;

		} elseif (($this->config->item('captcha_case_sensitive', 'tank_auth') AND
				$code != $word) OR
				strtolower($code) != strtolower($word)) {
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Create reCAPTCHA JS and non-JS HTML to verify user as a human
	 *
	 * @return	string
	 */
	function _create_recaptcha()
	{
		$this->load->helper('recaptcha');

		// Add custom theme so we can get only image
		$options = "<script>var RecaptchaOptions = {theme: 'custom', custom_theme_widget: 'recaptcha_widget'};</script>\n";

		// Get reCAPTCHA JS and non-JS HTML
		$html = recaptcha_get_html($this->config->item('recaptcha_public_key', 'tank_auth'));

		return $options.$html;
	}

	/**
	 * Callback function. Check if reCAPTCHA test is passed.
	 *
	 * @return	bool
	 */
	function _check_recaptcha()
	{
		$this->load->helper('recaptcha');

		$resp = recaptcha_check_answer($this->config->item('recaptcha_private_key', 'tank_auth'),
				$_SERVER['REMOTE_ADDR'],
				$_POST['recaptcha_challenge_field'],
				$_POST['recaptcha_response_field']);

		if (!$resp->is_valid) {
			$this->form_validation->set_message('_check_recaptcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}
	
	
	
	function change_password()
	{
		if (!$this->tank_auth->is_logged_in()) {								// not logged in or not activated
			redirect('/login/');

		} else {
			$this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
			$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');

			$data['errors'] = array();

			if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->change_password(
						$this->form_validation->set_value('old_password'),
						$this->form_validation->set_value('new_password'))) {	// success
					$this->_show_message($this->lang->line('auth_message_password_changed'));

				} else {														// fail
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
			}
			$this->load->view('auth/change_password_form', $data);
		}
	}
	function update_password()
	{
		$data = $this->path->all_path();
		$data['pwd_change']=0;
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/login/');
		} else {
		
		$this->load->view('auth/header',$data);
		$this->form_validation->set_rules('current_password', 'Current Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
		$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');
		$data['errors'] = array();
		if ($this->form_validation->run()) {								// validation ok
				if ($this->tank_auth->change_password(
						$this->form_validation->set_value('current_password'),
						$this->form_validation->set_value('new_password'))) {	// success
						redirect('home/pwd_change');
					//$this->_show_message($this->lang->line('auth_message_password_changed'));
					
				} else {														// fail
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
				}
				
			}
			$this->load->view('auth/update_pass',$data);
		
		}
		$this->load->view('auth/footer',$data);	
		
	}
	function update_profile()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/login/');
		} else {
		
		$data = $this->path->all_path();
		$this->load->view('auth/header',$data);
		$logged_user = $data['user_id'] = $this->tank_auth->get_user_id();
		$data['fetch_profile'] = $this->users->fetch_profile($logged_user);
		$data['country'] = $this->users->fetch_country();
		$data['educ_level'] = $this->users->fetch_educ_level();
		$data['area_interest'] = $this->users->fetch_area_interest();
		
		if($this->input->post('update'))
		{
		$this->form_validation->set_rules('alt_email','Alternate Email','trim|xss_clean|valid_email');
		$this->form_validation->set_rules('mob_no','Mobile Phone','trim|integer|xss_clean');
		$this->form_validation->set_rules('sex','Sex','trim|required');
		//$this->form_validation->set_rules('year','Year','trim|required');
		//$this->form_validation->set_rules('month','Month','trim|required');
		//$this->form_validation->set_rules('date','Date','trim|required');
		if($this->form_validation->run())
		{
		$data['user_profile_update'] = $this->users->user_profile_update($logged_user);
		$this->users->do_upload_profile_pic();
		$data['pus'] = 1;
		redirect('home/pus');
		}
		else
		{
			$errors = $this->tank_auth->get_error_message();
		foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
		}
		}
		
		
		$this->load->view('auth/profile_single',$data);
		$this->load->view('auth/footer',$data);
		}
	}
	

	/*function user_profile_update()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/login/');
		} else {
		
		$this->load->model('users');
		$logged_user = $data['user_id'] = $this->tank_auth->get_user_id();
		$data['user_profile_update'] = $this->users->user_profile_update($logged_user);
		//if ($this->input->post('upload')) {
			$this->users->do_upload_profile_pic();
		//}
		redirect('home');
		}
	}*/

	
	function find_college()
	{
		$data = $this->path->all_path();
		$this->load->view('auth/header',$data);
		$data['country'] = $this->users->fetch_country();
		$data['area_interest'] = $this->users->fetch_area_interest();
		$data['educ_level'] = $this->users->fetch_educ_level();
		if($this->input->post('process_step_one'))
		{
		$this->form_validation->set_rules('step_email','Email','trim|xss_clean|required|valid_email');
		$this->form_validation->set_rules('iagree','I agree','trim|xss_clean|required');
		if($this->form_validation->run())
		{
			$data_stepone = array(
			'country_home' => $this->input->post('home_country'),
			'email' => $this->input->post('step_email')
			);
			$this->session->set_userdata($data_stepone);
			$this->load->view('auth/step_two',$data);
		}
		else{
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
					
					$this->load->view('auth/step_one',$data);
		}
		}
		 else if($this->input->post('submit_step_data'))
		 {
			
			$this->form_validation->set_rules('interest_study_country','Interested Country','trim|xss_clean|required');
		$this->form_validation->set_rules('first_name','First Name','trim|xss_clean|required');
		$this->form_validation->set_rules('last_name','Last Name','trim|xss_clean|required');
		$this->form_validation->set_rules('dob_day','DOB Day','trim|xss_clean|integer|required');
		$this->form_validation->set_rules('dob_year','DOB Year','trim|xss_clean|integer|required');
		$this->form_validation->set_rules('home_address','Home Address','trim|xss_clean|required');
		$this->form_validation->set_rules('state','State','required');
		$this->form_validation->set_rules('city','City','required');
		$this->form_validation->set_rules('phone','Phone','trim|xss_clean|integer|required');
		$this->form_validation->set_rules('area_interest','Interest Area','required');
		$this->form_validation->set_rules('current_educ_level','Current Education','required');
		$this->form_validation->set_rules('next_educ_level','Next Education Level','required');
		$this->form_validation->set_rules('academic_exam_score1','Academic Exam Score','trim|xss_clean|required|integer');
		//$this->form_validation->set_rules('eng_prof_exam_score1','English Proficiency','trim|xss_clean|required');
			
			if($this->form_validation->run())
			{
			 $data_steptwo = array(
			 'intake1'             => $this->input->post('begin_year1'),
			 'intake2'             => $this->input->post('begin_year2'),
			 'title'               => $this->input->post('title'),
			 'firstname'           => $this->input->post('first_name'),
			 'lastname'            => $this->input->post('last_name'),
			 'studying_country_id' => $this->input->post('interest_study_country'),
			 'dob'                 => $this->input->post('dob_year').'-'.$this->input->post('dob_month').'-'.$this->input->post('dob_day'),
			 'address'            =>  $this->input->post('home_address'),
			 'state'              => $this->input->post('state'),
			 'city'               => $this->input->post('city'),
			 'phone_type1'        => $this->input->post('phone_type'),
			 'phone_no1'          => $this->input->post('phone'),
			 'phone_type2'        => $this->input->post('home_country'),
			 'phone_no2'          => $this->input->post('home_country'),
			 'prog_parent_id'     => $this->input->post('area_interest'),
			 'current_educ_level' => $this->input->post('current_educ_level'),
			 'next_educ_level'    => $this->input->post('next_educ_level'),
			 
			 'acedmic_exam_score_type1' =>  $this->input->post('academic_exam_type1'),
			 'acedmic_exam_score1'      => 	$this->input->post('academic_exam_score1'),
			 'acedmic_exam_score_type2' =>  $this->input->post('academic_exam_type2'),
			 'acedmic_exam_score2'      =>  $this->input->post('academic_exam_score2'),
			 'eng_profiency_exam1'      =>  $this->input->post('eng_prof_exam_type1'),
			 'eng_profiency_exam_score1'=>  $this->input->post('eng_prof_exam_score1'),
			 'eng_profiency_exam2'      =>  $this->input->post('eng_prof_exam_type2'),
			 'eng_profiency_exam_score2'=>  $this->input->post('eng_prof_exam_score2'),
			 
			 );
			 $this->session->set_userdata($data_steptwo);
			 $this->load->view('auth/step_two');
			 }
			 
			else
				{
					$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
					
					$this->load->view('auth/step_two',$data);
				}
			 
		 }
		else{
			$this->load->view('auth/step_one');
		}
		$this->load->view('auth/footer',$data);
	}
	
	function state_list_ajax($cid='0')
	{
		
		$data['region']=$this->adminmodel->fetch_states($cid);
		$this->load->view('ajaxviews/state_ajax',$data);
	}
	
	function city_list_ajax($sid='0')
	{
		
		$data['region']=$this->adminmodel->fetch_city($sid);
		$this->load->view('ajaxviews/city_ajax',$data);
	}
	/* Reset Email By User- Code by Subhanarayan */
	function forgot_password()
	{
		$data = $this->path->all_path();
		$this->load->view('auth/header',$data);
		
		if($this->input->post('reset_pass'))
		{
			$data['msg'] = 0;
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email'); 
			if($this->form_validation->run())
			{
				$data['email_check'] = $this->users->check_email_exists_lost_pass();
				//print_r($data['email_check']);
				//print_r($data);
				if($data['email_check'] == 0)
				{
					$data['errors']['email'] = 'Email does not Exist';
					$data['msg'] = 1;
					$this->load->view('auth/login',$data);
				}
				else{
				$data['email_send'] = 0;
				$data['errors']['email'] = 'Email Exist';
				$key = md5(rand().microtime());
				$user_id = $data['email_check']['id'];
				$email = $data['email_check']['email'];
				$set_key = array(
				'new_password_key'=>$key,
				'new_password_requested'=>date('Y-m-d H:i:s'),
				'psw_recover_flag'=>'1',
				);
				$this->users->set_key_forgot_password($set_key,$user_id);
				$this->email->set_newline("\r\n");

            $this->email->from('Workforcetrack.in', 'Meet University');
            $this->email->to($email);
            $this->email->subject('Lost Password');
            $key = $key;
            $message = "Please click this url to change your password ". base_url()."change_user_password/".$key.'/'.$user_id ;
            $message .="<br/>Thank you very much";
            $this->email->message($message);
			//print_r($message);
			if($this->email->send())
                {
                    $data['email_send'] = 1;
                }

                else
                {
                    show_error($this->email->print_debugger());
                }
					$data['msg'] = 1;
					$this->load->view('auth/login',$data);
				}
			}
			else{
				$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
					$data['msg'] = 1;
					$this->load->view('auth/login',$data);
			}
		}
		else{
		$this->load->view('auth/login',$data);
		}
		$this->load->view('auth/footer',$data);
	}
	/* Check if recovery password link is valid */
	
	public function change_user_password($key='',$id='')
	{
		$this->ci->load->library('session');
		if ($this->tank_auth->is_logged_in()) {
			redirect('/home');
		} else {
		$data = $this->path->all_path();
		$this->load->view('auth/header',$data);
		
		//echo $key; echo $id;
		$set_values = array(
		'id' => trim($id),
		'new_password_key' => trim($key)
		);
		$data['user_detail'] = $this->users->fetch_profile($id);
		if($this->input->post('update_for_lost_psw'))
		{
			$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');
			$this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');
			
			if($this->form_validation->run())
			{
				$hasher = new PasswordHash(
				$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
				$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
				$hashed_password = $hasher->HashPassword($this->input->post('new_password'));
				
				$set_update_values = array(
				'password' => $hashed_password,
				'psw_recover_flag' => 0
				);
				
				$data['link_valid'] = $this->users->update_and_deactivate_psw_request($set_values,$set_update_values);
				if($data['link_valid'] == 1)
				{
				$this->ci->session->set_userdata(array(
						 'user_id'	=> trim($id),
						 'username'	=> '',
						 'status'	=> STATUS_ACTIVATED,
						 'psw_change'	=> 'true'
						 ));
				redirect('/home');
				//echo 'YOUR PASSWORD HAS BEEN UPDATED';
				}
				else{
				$this->ci->session->set_userdata(array(
						 'psw_change'	=> 'true'
						 ));
				$this->load->view('auth/forgot_pass_invalid_link');
				}
			}
			else{
				$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
					$this->load->view('auth/change_lost_password',$data);
			}
			
		}
		else{
		$this->load->view('auth/change_lost_password',$data);
		}
		
		//echo $key;
		$this->load->view('auth/footer',$data);
	}
	}
	
	function university($univ_id='')
	{
		$data = $this->path->all_path();
		$this->load->view('auth/header',$data);
		$data['university_details'] = $this->users->get_university_by_id($univ_id);
		$country_id = $data['university_details']['country_id'];
		$city_id = $data['university_details']['city_id'];
		$longitude = $data['university_details']['longitude'];
		$latitude = $data['university_details']['latitude'];
		$university_name = $data['university_details']['univ_name'];
		$university_address = $data['university_details']['address_line1'];
		$logged_user_id = $this->session->userdata('user_id');
		 $redirect_current_url = $this->config->site_url().$this->uri->uri_string();
		 $data['area_interest'] = $this->users->fetch_area_interest();
		 $data['univ_gallery'] = $this->users->get_univ_gallery($univ_id);
		 $data['article_news_gallery'] = $this->users->get_detail_articles_of_univ($univ_id);
		 $data['followers_detail_of_univ'] = $this->users->get_followers_detail_of_univ($univ_id);
		 $data['events_of_univ'] = $this->users->fetch_latest_events_by_univ_id($univ_id);
		 //print_r($data['events_of_univ']);
		 //print_r($data['followers_detail_of_univ']);
		$add_follower = array(
			'follow_to_univ_id' => $univ_id,
			'followed_by' => $logged_user_id
			);
			
		$data['is_already_follow'] = $this->users->check_is_already_followed($add_follower);
		
		/* code for university map */
		$this->load->library('GMapuniv');
		$this->gmapuniv->GoogleMapAPI();
		$this->gmapuniv->setMapType('map');
		$this->gmapuniv->addMarkerByCoords($longitude,$latitude,$university_name,$university_address);
		
						$data['headerjs'] = $this->gmapuniv->getHeaderJS();
						$data['headermap'] = $this->gmapuniv->getMapJS();
						$data['onload'] = $this->gmapuniv->printOnLoad();
						$data['map'] = $this->gmapuniv->printMap();
						$data['sidebar'] = $this->gmapuniv->printSidebar();
		
		if($this->input->post('join_now'))
		{
			if (!$this->tank_auth->is_logged_in()) {
			redirect('/home');
		} else {
			$data['user_follow_university'] = $this->users->add_followers($add_follower);
			redirect($redirect_current_url);
			}
		}

		else if($this->input->post('unjoin_now'))
		{
			$data['unjoin_now_success'] = $this->users->unjoin_now($add_follower);
			redirect($redirect_current_url);
		}
		
		else if($this->input->post('apply_now'))
		{
			$apply_now_data = array(
				'apply_name' => $this->input->post('apply_name'),
				'apply_course_interest' => $this->input->post('apply_course_interest'),
				'apply_email' => $this->input->post('apply_email'),
				'apply_mob' => $this->input->post('apply_mobile')
			);
			$this->session->set_userdata($apply_now_data);
			//print_r($this->session->userdata);
		}
		
		if($data['university_details'] != 0)
		{
			$data['country_name_university'] = $this->users->fetch_country_name_by_id($country_id);
			$data['city_name_university'] = $this->users->fetch_city_name_by_id($city_id);
			$data['count_followers'] = $this->users->get_followers_of_univ($univ_id);
			$data['count_articles'] = $this->users->get_articles_of_univ($univ_id);
			$this->load->view('auth/university',$data);
		}
		
		else{
		//$data['errors'] = 'Sorry, No University Details Found !!!';
		$this->load->view('auth/university',$data);
		}
		//$this->load->view('auth/university',$data);
		$this->load->view('auth/footer',$data);
	}
	
	function all_colleges()
	{
		$data = $this->path->all_path();
		$this->load->view('auth/header',$data);
		$data['get_university'] = $this->users->show_all_college();
		//print_r($data['get_university']);
		$this->load->view('auth/show_all_college',$data);
		$this->load->view('auth/footer',$data);
	}
	
	function user($id='')
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/home');
		} else {
		$data = $this->path->all_path();
		$this->load->view('auth/header',$data);
		$redirect_current_url = $this->config->site_url().$this->uri->uri_string();
		$data['detail_visited_user'] = $this->users->fetch_profile($id);
		$cur_educ_lvl = $data['detail_visited_user']['curr_educ_level'];
		$area_interest = $data['detail_visited_user']['prog_parent_id'];
		$data['educ_level'] = $this->users->fetch_educ_level_by_id($cur_educ_lvl);
		$data['area_interest'] = $this->users->fetch_area_interest_by_id($area_interest);
		$data['area_interest'] = $this->users->fetch_area_interest_by_id($area_interest);
		$data['follower_detail'] = $this->users->get_followers_detail_of_person($id);
		$logged_user_id = $this->session->userdata('user_id');
		$data['send_message_to_user_error'] = 0;
		$data['follow_own'] = 0;
		$logged_user_id == $id ? $data['follow_own'] = 1 : $data['follow_own'] = 0;
		$add_follower = array(
			'followed_to_person_id' => $id,
			'followed_by' => $logged_user_id
			);
			
		$data['is_already_follow'] = $this->users->check_is_already_followed_to_person($add_follower);
		//print_r($data['follower_detail']);
		
		if($this->input->post('follow_now'))
		{
			$data['user_follow_university'] = $this->users->add_followers_to_person($add_follower);
			redirect($redirect_current_url);
		}

		else if($this->input->post('unfollow_now'))
		{
			$data['unjoin_now_success'] = $this->users->unfollow_now_to_user($add_follower);
			redirect($redirect_current_url);
		}
		
		$data['send_message_to'] = 0 ;
		if($this->input->post('btn_msg_send'))
		{
			$this->form_validation->set_rules('subject_message','Subject box','trim|xss_clean|required');
			$this->form_validation->set_rules('message_body','Message box','trim|xss_clean|required');
			if($this->form_validation->run())
			{
				$sender_id = $this->session->userdata('user_id');
				$recipent_id = $id;
				$msg = array(
				'sender_id'=>$sender_id,
				'recipent_id'=>$recipent_id,
				'subject'=> $this->input->post('subject_message'),
				'body'=> $this->input->post('message_body'),
				);
				//print_r($msg);
				$data['send_message_to'] = $this->users->send_message_to_user($msg);
			}
			else{
			$errors = $this->tank_auth->get_error_message();
					foreach ($errors as $k => $v)	$data['errors'][$k] = $this->lang->line($v);
			$data['send_message_to_user_error'] = 1;
			}
		}
		$this->load->view('auth/visit-user-profile',$data);
		$this->load->view('auth/footer',$data);
		}
	}
	
	
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */