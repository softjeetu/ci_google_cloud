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
		$this->load->view('welcome_message', $data);
	}
	
	
	
	public function gcv(){
		$this->form_validation->set_message('is_natural_no_zero', 'Natural non zero only.');
        $this->form_validation->set_rules('txt', 'File name', 'required|trim|xss_clean|strip_tags');
		
        if ($this->form_validation->run() == true)
        {
			if($_FILES['imgInp']['size'] > 0){
				$this->load->library("cloud_vision");		
				$image_data = $this->cloud_vision->_get_annotate($_FILES['imgInp']['tmp_name'], $_FILES['imgInp']['name']);
				print_r($image_data);
			
			
				if($_FILES['imgInp']['size'] > 0 && sizeof($image_data)){

					$this->load->library('upload');

					$config['upload_path'] = 'uploads/gcv';
					$config['allowed_types'] = 'gif|jpg|png|jpeg'; 
					#$config['max_size'] = '1024';
					#$config['max_width'] = '200';
					#$config['max_height'] = '30';
					$config['overwrite'] = FALSE;
					$config['file_name'] = round(microtime(true) * 1000).'_'.$_FILES['imgInp']['name'];				

					$this->upload->initialize($config);

					if(!$this->upload->do_upload('imgInp')){

							$error = $this->upload->display_errors();
							$this->session->set_flashdata('message', $error);
							redirect(base_url(), 'refresh');
					} 

					$data['image_name'] = $this->upload->file_name;
					$data['image_lables'] = json_encode($image_data);
					
					$this->db->insert('gcv', $data);
					$this->session->set_flashdata('success_message', 'Data saved successfully!');
					redirect(base_url(), 'refresh');

				}
			}
			else{die('jay');
				$this->session->set_flashdata('error', "Choose a file to upload!");
				redirect('welcome', 'refresh');
			}
		}
		else{
			$this->session->set_flashdata('error', validation_errors());
			redirect('welcome', 'refresh');
		}
		
	}
}
