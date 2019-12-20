<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Google Cloud vision Class
 *
 * @package       CodeIgniter
 * @subpackage    Libraries
 * @category      Cloud vision
 * @author        Jitendra Kumar <softjeetu@gmail.com>
 */
require_once(APPPATH .'third_party/google/cloud_vision/autoload.php' );
require_once(APPPATH .'third_party/google/cloud_datastore/autoload.php' );
use Google\Cloud\Vision\VisionClient;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Datastore\DatastoreClient;
use Google\Cloud\Core\Exception\ServiceException as VisionException;


class Cloud_vision
{
    /**
     * vision, datastore client objectg
     *
     * @var string
     **/
    protected $vision, $datastore, $imageAnnotator;
	
	/**
     * account status ('not_activated', etc ...)
     *
     * @var string
     **/
    protected $status;

    
    /**
     * __construct
     *
     * @return void
     * @author Jitendra Kumar
     **/
    public function __construct()
    {            
        //$this->load->config('config', TRUE);        
        //$this->load->library('session');
        //$this->load->helper('cookie'); 
		
		#CREATING VISION OBJECT
		$this->vision = new VisionClient(['keyFile' => json_decode(file_get_contents(base_url('assets/gcv/credentials.json')), true)]);
		$this->datastore = new DatastoreClient(['keyFile' => json_decode(file_get_contents(base_url('assets/gcv/credentials.json')), true)]);
		$this->imageAnnotator = new ImageAnnotatorClient(['credentials' => FCPATH.'assets/gcv/credentials.json']);
                
    }    
	
	
	
	function _store_data($data = array(), $img_name){
		// Create an entity
		if(sizeof($data) == 0){
			return false;
		}
		try{
			$_data = $this->datastore->entity('SearchImage');
			$_data['img_name'] = $img_name;
			$_data['img_data'] = $data;
			$_ins_search_image = $this->datastore->insert($_data);
			echo "<pre>";
			print_r($_ins_search_image);die;

			// Update the entity
			//$bob['email'] = 'bobV2@example.com';
			//$datastore->update($bob);

			// If you know the ID of the entity, you can look it up
			//$key = $this->datastore->key('SearchImage', '12345328897844');
			//$entity = $this->datastore->lookup($key);
		}
		catch (Exception $e) {
			echo $e->getMessage();
		}
	}

    
	function _get_annotate($image = false, $img_name){
			
		if(!$image){
			return false;
		}
		try {
			// Annotate an image, detecting labels, text, imageProperties.
			$image = $this->vision->image(
				fopen($image, 'r'),
				[
					'labels','text','imageProperties'
				]
			);
			

			$annotation = $this->vision->annotate($image);			
			
			$_label_data = [];
			// Determine the values whose score is greater than 0.9.
			if(sizeof($annotation->labels()) > 0){
				foreach ($annotation->labels() as $key => $label) {
					if ($label->score() >= 0.9) {
						$_label_data[] = $label->description();					
					}
				}
			}
			
			return $_label_data;
		}
		catch (VisionException $e) {
			echo $e->getMessage();
		}
		catch (InvalidArgumentException $e) {
			echo $e->getMessage();
		}
	}

    
      

    
}

/* End of file cloud_vision.php */ 
/* Location: ./libraries/cloud_vision.php */