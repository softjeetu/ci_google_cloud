<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.1/assets/img/favicons/favicon.ico">

    <title>Google Cloud Vision</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.1/examples/offcanvas/">

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url('assets/css/bootstrap.min.css');?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url('assets/css/offcanvas.css');?>" rel="stylesheet">
  </head>

  <body class="bg-light">

    <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
      <a class="navbar-brand mr-auto mr-lg-0" href="<?php echo base_url(); ?>">Google Cloud Vision</a>
      <button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="<?php echo base_url(); ?>">Home <span class="sr-only">(current)</span></a>
          </li>
          
        </ul>
		<?php 
		$attrib = array('class' => 'form-inline my-2 my-lg-0', 'method'=>'get');
		echo form_open('search-result', $attrib);?>        
          <input class="form-control mr-sm-2" type="text" placeholder="Search Images" aria-label="Search" name="s_query" value="<?php echo (isset($s_query) && !empty($s_query)) ? $s_query : ''; ?>">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        <?php echo form_close() ?>
      </div>
    </nav>    
    </div>

    <main role="main" class="container">
      <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-purple rounded shadow-sm">        
        <div class="lh-100">
          <h6 class="mb-0 text-white lh-100">Search Result(s)</h6>
          <small><?php echo date('m/d/Y'); ?></small>
        </div>
      </div>

      <div class="my-3 p-3 bg-white rounded shadow-sm">
        <div class="container">
				<div class="col-md-12">
					<?php if(isset($message) && $message != '') { echo "<div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>"; } ?>
					<?php if(isset($success_message) && $success_message != '') { echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $success_message . "</div>"; } ?>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<table class="table">
						  <thead>
						    <tr>
						      <th scope="col">#</th>
						      <th scope="col">Image</th>					      
						    </tr>
						  </thead>
						  <tbody>
						  	<?php if(sizeof($search_result) > 0){
						  		foreach($search_result as $key => $result){?>
							    <tr>
							      <th scope="row"><?php echo $key+1; ?></th>
							      <td><img src="<?php echo base_url('uploads/gcv/'.$result['image_name']); ?>" class="img-responsive img-thumbnail"></td>						     
							    </tr>
						    <?php }}
						    else{?>	
							    <tr>							      
							      <td>No records found!!</td>						     
							    </tr>
						    <?php }?>					   						   
						  </tbody>
						</table>
						
					</div>
				</div>
				
				
			
		</div>
      </div>

      <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">Suggestions</h6>
        <div class="media text-muted pt-3">
          <p>Derive insights from your images in the cloud or at the edge with AutoML Vision or use pre-trained Vision API models to detect emotion, understand text, and more.</p>

		
        </div>
        <div class="media text-muted pt-3">
          <p>Store the file name as key and returning JSON into Google Cloud Datastore, Choose a representation in Google Cloud data store that allows searching images with particular objects, like cars, phones etc.</p>

        </div>        
      </div>
    </main>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url('assets/js/jquery-3.3.1.slim.min.js');?>" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="<?php echo base_url("assets/js/jquery-slim.min.js");?>"><\/script>')</script>
    <script src="<?php echo base_url('assets/js/popper.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/holder.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/offcanvas.js')?>"></script>
	<script>
		$(document).ready( function() {					
			function readURL(input) {
				if (input.files && input.files[0]) {
					var reader = new FileReader();
					
					reader.onload = function (e) {
						$('#img-upload').attr('src', e.target.result);
						$("#txt").val(input.files[0].name);
					}
					
					reader.readAsDataURL(input.files[0]);
				}
			}

			$("#imgInp").change(function(){
				readURL(this);
			}); 	
		});
		</script>
  </body>
</html>