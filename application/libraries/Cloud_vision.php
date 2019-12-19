<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Google Cloud vision Class
 *
 * @package       CodeIgniter
 * @subpackage    Libraries
 * @category      Cloud vision
 * @author        Jitendra Kumar <softjeetu@gmail.com>
 */
require_once('google/cloud_vision/autoload.php' );
use Google\Cloud\Vision\VisionClient;
use Google\Cloud\Core\Exception\ServiceException as VisionException;


class Cloud_vision
{
    /**
     * vision client objectg
     *
     * @var string
     **/
    protected $vision;
	
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
        $this->load->config('config', TRUE);        
        //$this->load->library('session');
        $this->load->helper('cookie'); 
		
		#CREATING VISION OBJECT
		$this->vision = new VisionClient(['keyFile' => json_decode(file_get_contents(base_url('assets/gcv/credentials.json')), true)]);
                
    }
    

    /**
     * __call
     *
     * Acts as a simple way to call model methods without loads of stupid alias'
     *
     **/
    public function __call($method, $arguments)
    {
           
        /*if (!method_exists( $this->booking_model, $method) )
        {
            throw new Exception('Undefined method Booking::' . $method . '() called');
        }       
        return call_user_func_array( array($this->booking_model, $method), $arguments);*/
    }

    /**
     * __get
     *
     * Enables the use of CI super-global without having to define an extra variable.
     *
     * I can't remember where I first saw this, so thank you if you are the original author. -Militis
     *
     * @access  public
     * @param   $var
     * @return  mixed
     */
    public function __get($var)
    {
        return get_instance()->$var;
    }


    
	function _get_annotate($image = false){
			
		if(!$image){
			return false;
		}
		try {
			// Annotate an image, detecting faces.
			$image = $this->vision->image(
				fopen($image, 'r'),
				[
					'faces','landmarks','logos','labels','text', 'safeSearch', 
					'imageProperties', 'web'
				]
			);

			$annotation = $this->vision->annotate($image);
			echo "<pre>";
			echo '-----------------------------FACES--------------------------------------------';
			print_r($annotation->faces());
			echo '-----------------------------LANDSMARKS--------------------------------------------';
			print_r($annotation->landmarks());
			echo '-----------------------------LOGOS--------------------------------------------';
			print_r($annotation->logos());
			echo '-----------------------------LABLES--------------------------------------------';
			print_r($annotation->labels());
			echo '-----------------------------TEXT--------------------------------------------';
			print_r($annotation->text());
			echo '-----------------------------SAFESEARCH--------------------------------------------';
			print_r($annotation->safeSearch());
			echo '-----------------------------IMAGE PORPS--------------------------------------------';
			print_r($annotation->imageProperties());
			echo '-----------------------------WEB--------------------------------------------';
			print_r($annotation->web());
			echo "</pre>";
			// Determine if the detected faces have headwear.
			foreach ($annotation->faces() as $key => $face) {
				if ($face->hasHeadwear()) {
					echo "Face $key has headwear.\n";
				}
			}
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