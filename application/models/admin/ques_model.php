<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ques_model extends CI_Model
{
	var $gallery_path;
	var $univ_gallery_path;
	function __construct()
	{
	
		parent::__construct();
		$this->gallery_path = realpath(APPPATH . '../uploads/home_gallery');
		$this->univ_gallery_path = realpath(APPPATH . '../uploads/ques_ques_images');
		$this->load->library('pagination');
		$this->load->database();
	}
	
	function create_ques()
	{$flag=1;
		$data['user_id'] = $this->tank_auth->get_admin_user_id();		
		$data_insert = array(
			   'q_title' => $this->input->post('title'),
			   'q_detail' => $this->input->post('detail'),
			   'q_askedby' => $data['user_id'],			    
			   'q_univ_id' => $this->input->post('colleges'),
			   'q_category' => $this->input->post('category'),
			   'q_featured_home_que'=>'0',
			   'q_approve'=>'0'
			);
			//print_r($data_insert);exit;
			$this->db->insert('questions', $data_insert);
			return $flag;
	}

	function ques_detail()
	{
		$data['admin_user_level']=$this->tank_auth->get_admin_user_level();
		$data['user_id']= $this->tank_auth->get_admin_user_id();
		$univ=$this->univ_vs_user_model->get_assigned_univ_info($data['user_id']);
		$this->db->select('*');
		$this->db->from('questions');
		$this->db->join('university', 'questions.q_univ_id = university.univ_id','left');		
		if($data['admin_user_level']=='3')
		{
		$this->db->where('university.user_id',$data['user_id']);
		}
		if($data['admin_user_level']=='4')
		{
			$this->db->where_in('q_univ_id',$univ);
		}		
		$query=$this->db->get();		
		return $query->result();
	}
	function latest_ques()
	{
		$data['admin_user_level']=$this->tank_auth->get_admin_user_level();
		$data['user_id']= $this->tank_auth->get_admin_user_id();
		$univ=$this->univ_vs_user_model->get_assigned_univ_info($data['user_id']);
		$this->db->select('*');
		$this->db->from('questions');
		$this->db->join('university', 'questions.q_univ_id = university.univ_id','left');		
		if($data['admin_user_level']=='3')
		{
		$this->db->where('university.user_id',$data['user_id']);
		}
		if($data['admin_user_level']=='4')
		{
			$this->db->where_in('q_univ_id',$univ);
		}
		$this->db->order_by('q_asked_time','desc');
		$this->db->limit(10);
		$query=$this->db->get();		
		return $query->result();
	}
	function fetch_univ_id($user_id)
	{
		$this->db->select('univ_id');
		$this->db->from('university');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	
	function fetch_ques_ids($univ_id)
	{
		$this->db->select('que_id');
		$this->db->from('questions');
		$this->db->where('q_univ_id',$univ_id);
		$query = $this->db->get();
		$res=$query->result_array();
		$i=0;
		foreach($res as $res1)
		{
		$r[]=$res[$i]['que_id'];
		$i++;
		}
		return $r;
	}
	
	function fetch_ques_detail($ques_id)
	{
		$this->db->select('*');
		$this->db->from('questions');
		$this->db->join('university', 'questions.q_univ_id = university.univ_id','left');		
		$this->db->where('que_id',$ques_id);
		$query=$this->db->get();
		return $query->result_array();
		
	}
	
	function fetch_ques_ans($ques_id)
	{
		$this->db->select('*');
		$this->db->from('comment_table');	
		$this->db->where('comment_on_id',$ques_id);
		$query=$this->db->get();
		return $query->result_array();
		
	}
	function ans_count($id)
	{
		$query=$this->db->query("select comment_id from comment_table where comment_on_id='".$id."'");		
		return $query->num_rows();
	}
	//new
	function update_ques($ques_id)
	{
				
		$data = array(
		   'q_title' => $this->input->post('title'),
			'q_detail' => $this->input->post('detail'),
			'q_univ_id' => $this->input->post('univ')
		);
		
		$this->db->update('questions', $data, array('que_id' => $ques_id));
		return 1;
		
	}	
	
	function delete_single_ques($ques_id)
	{
		$this->db->delete('questions', array('que_id' => $ques_id));
		$this->db->delete('comment_table', array('comment_on_id' => $ques_id));
		return 1;
	}
	function dropans()	
	{
		$id=$this->input->post('id');
		$this->db->delete('comment_table', array('comment_id' => $id));	
		return 1;
	}
function edit_ans()	
	{
		$id=$this->input->post('id');
		$ans=$this->input->post('ans');
		$record=array(		
		'commented_text'=>$ans
		);
		$this->db->where('comment_id',$id);
		$this->db->update('comment_table', $record);	
		return 1;
	}	
	function home_featured_unfeatured_ques($f_status,$ques_id)
	{
		if($f_status=='1')
		{
		$f_status='0';
		}
		else if($f_status=='0')
		{
		$f_status='1';
		}
		$data=array('q_featured_home_que'=>$f_status);
		$this->db->update('questions', $data, array('que_id' => $ques_id));
		return $f_status;
       
	}
	//new
	function approve_home_confirm($approve_status,$ques_id)
	{
		if($approve_status=='1')
		{
		$approve_status='0';
		}
		else if($approve_status=='0')
		{
		$approve_status='1'; 
		}
		$data=array('q_approve'=>$approve_status);
		$this->db->update('questions', $data, array('que_id' => $ques_id));
		return $approve_status;
       
	}	
	//new
	function count_feature_ques($field)
	{
		$this->db->select('*');
		$this->db->from('questions');
		$this->db->where($field,'1');
		$query = $this->db->get();
		if($query->num_rows()<4)
		return 1;
		else
		return 0;
	}
	
	//new
	function delete_ques()
	{
		$idsstring=$this->input->post('que_id');
		foreach($idsstring as $ids)
		{
			$this->db->delete('questions', array('que_id' => $ids));
		}
		return 1;
	}
	
	function addans()
	{
		$user_id = $this->tank_auth->get_admin_user_id();	
		$id=$this->input->post('id');
		$data = array(
		'commented_by' => $user_id,
		'comment_on_id'=>$id,
		'commented_on'=>'qna',
		'commented_text' => $this->input->post('answer')
		);		
		$this->db->insert('comment_table',$data);							//added by satbir on 11/20/2012
		$data1 = array(
			'q_answered' =>'1'		
		);
		$this->db->where('que_id',$id);
		$this->db->update('questions', $data1);
		return 1;
		
	}	
	
}