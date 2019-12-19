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
		$this->load->view('welcome_message');
	}
	
	public function gcu(){
		$this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
        $this->form_validation->set_rules('txt', 'File name', 'required|trim|xss_clean|strip_tags');
        if ($this->form_validation->run() == true)
        {
			$this->load->library("cloud_vision");
		
			echo "<pre>";
			print_r($this->input->post());
			print_r($_FILES);
			echo "</pre>";
			$image_data = $this->cloud_vision->_get_annotate($_FILES['imgInp']['tmp_name'], $_FILES['imgInp']['name']);
			die;
			/*if($_FILES['imgInp']['size'] > 0){

				$this->load->library('upload');

				$config['upload_path'] = 'uploads/gcv';
				$config['allowed_types'] = 'gif|jpg|png|jpeg'; 
				#$config['max_size'] = '1024';
				#$config['max_width'] = '200';
				#$config['max_height'] = '30';
				$config['overwrite'] = FALSE; 

				$this->upload->initialize($config);

				if(!$this->upload->do_upload('imgInp')){

						$error = $this->upload->display_errors();
						$this->session->set_flashdata('message', $error);
						redirect("welcome", 'refresh');
				} 

				$data['gc_image_name'] = $this->upload->file_name;


			}
		*/}
		
	}
}
