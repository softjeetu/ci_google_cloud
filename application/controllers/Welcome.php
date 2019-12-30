<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{		
		$data['message'] = $this->session->flashdata('error');
		$data['success_message'] = $this->session->flashdata('success_message');
		$_s_param = $this->input->get('s_query');
		$data['s_query'] = $_s_param;
		$this->load->view('welcome_message', $data);
	}
	
	
	
	public function gcv(){
		$this->form_validation->set_message('is_natural_no_zero', 'Natural non zero only.');
        $this->form_validation->set_rules('txt', 'File name', 'required|trim|xss_clean|strip_tags');
		
        if ($this->form_validation->run() == true)
        {
        	/*echo "<pre>";
        	print_r(sizeof(array_filter($_FILES['imgInp']['name'])));
        	echo "</pre>";die;*/
			
			# upload limit 20
			if(sizeof(array_filter($_FILES['imgInp']['name'])) > 20){
				$this->session->set_flashdata('error', "You can only upload a maximum of 20 files.");
				redirect('welcome', 'refresh');
			}
			
			# if no file chosen
        	if(sizeof(array_filter($_FILES['imgInp']['name'])) > 0){
        		foreach($_FILES['imgInp']['tmp_name'] as $key => $file_tmp_name){

        			$_FILES['file']['name'] = $_FILES['imgInp']['name'][$key];
        			$_FILES['file']['type'] = $_FILES['imgInp']['type'][$key];
        			$_FILES['file']['tmp_name'] = $_FILES['imgInp']['tmp_name'][$key];
        			$_FILES['file']['error'] = $_FILES['imgInp']['error'][$key];
        			$_FILES['file']['size'] = $_FILES['imgInp']['size'][$key];

					if($_FILES['file']['size'] > 0){
						#cloud vision api call
						$this->load->library("cloud_vision");		
						$image_data = $this->cloud_vision->_get_annotate($_FILES['file']['tmp_name'], $_FILES['file']['name']);
						/*echo "<pre>";
						print_r($image_data);die;*/
					
					
						if($_FILES['file']['size'] > 0 && sizeof($image_data)){

							$this->load->library('upload');

							$config['upload_path'] = 'uploads/gcv';
							$config['allowed_types'] = 'gif|jpg|png|jpeg'; 
							#$config['max_size'] = '1024';
							#$config['max_width'] = '200';
							#$config['max_height'] = '30';
							$config['overwrite'] = FALSE;
							$config['file_name'] = $key.round(microtime(true) * 1000).'_'.$_FILES['file']['name'];				

							$this->upload->initialize($config);

							if(!$this->upload->do_upload('file')){

									$error = $this->upload->display_errors();
									$this->session->set_flashdata('message', $error.' for file name '.$_FILES['file']['name']);
									#redirect('welcome', 'refresh');
							} 

							$data['image_name'] = $this->upload->file_name;
							$data['image_lables'] = json_encode($image_data);
							
							$this->db->insert('gcv', $data);
							
							$this->session->set_flashdata('success_message', 'Data saved successfully!');							

						}
					}
					else{
						$this->session->set_flashdata('error', "Incorrect File!!");						
					}
				}
			}
			else{
				$this->session->set_flashdata('error', "Choose a file to upload!");				
			}
		}
		else{
			$this->session->set_flashdata('error', validation_errors());			
		}
		redirect('welcome', 'refresh');
	}


	public function search(){
		$this->form_validation->set_message('is_natural_no_zero', 'Natural non zero only.');
        
		$data['search_result'] = array();
		$_s_param = $this->input->get('s_query');
		$data['s_query'] = $_s_param;
        if (!empty($_s_param))
        {
        	
		    $this->db->where('JSON_SEARCH(image_lables, "all", "%'.ucwords($_s_param).'%") IS NOT NULL');		    
		    $res = $this->db->get('gcv');
		    #echo $this->db->last_query();
		    
        	if($res->num_rows()){
        		$data['search_result'] = $res->result_array();
        	}
		}
		else{	
			$this->session->set_flashdata('error', "Please input search string!!");								
		}

		
		$data['message'] = $this->session->flashdata('error');	
		$data['success_message'] = $this->session->flashdata('success_message');
		$this->load->view('search_result', $data);
	}
}
