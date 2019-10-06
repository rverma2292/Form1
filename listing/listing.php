<?php
	$server = "localhost";
	$user = "root";
	$pass = "";
	$db_name = "form_database";
	
	$database = mysqli_connect($server, $user, $pass, $db_name);
	if(!$database){
		echo "Not Connected with database.";
		echo mysqli_connect_errno();
		echo "<br/>";
		die;
	}
	
	if(isset($_POST['submit'])){
		// echo "<pre>"; print_r($_POST); echo "</pre>";
		$name = $_POST['name'];
		$fname = $_POST['fname'];
		$email = $_POST['email'];
		$password = $_POST['pass'];
		$cpass = $_POST['cpass'];
		$gender = isset($_POST['gender'])?$_POST['gender']:'';
		$error = true;
		
		if($name==""){
			$error = false;
			$nameError = "Please Enter your Name.";
		}
		else if(is_numeric($name)){
			$error = false;
			$nameError = "Name must be Characters.";
		}
		else if(strlen($name)<2){
			$error = false;
			$nameError = "Name length must be grater than 2 character.";
		}
		
		if($fname==""){
			$error = false;
			$fnameError = "Please Enter your Father's Name.";
		}
		else if(is_numeric($fname)){
			$error = false;
			$fnameError = "Father Name must be Characters.";
		}
		else if(strlen($fname)<2){
			$error = false;
			$fnameError = "Length of input must be grater than 2 character.";
		}
		
		if($email==""){
			$error = false;
			$emailError = "Please Enter your email or ID";
		}
		
		if($password==""){
			$error = false;
			$passError = "Please Enter your Password";
		}
		else if(strlen($password)<5 || strlen($password)>=10){
			$error = false;
			$passError = "Password length must be between 5 to 10 characters.";
		}
		
		if($cpass==""){
			$error = false;
			$cpassError = "Please Enter your Password";
		}	
		else if($cpass!=$password){
			$error = false;
			$cpassError = "Confirm Password must be same as Password";
		}
			
		if($gender==""){
			$error = false;
			$genError = "Please select your gender.";
		}
		if(isset($_FILES['upload'])){
			// echo "<pre>"; print_r($_FILES); echo "</pre>";
			$filearr = [];
			$count = 0;
			foreach($_FILES['upload']['name'] as $value){
				$filearr[] = array(
					'name' => $_FILES['upload']['name'][$count],
					'type' => $_FILES['upload']['type'][$count],
					'tmpname' => $_FILES['upload']['tmp_name'][$count],
					'error' => $_FILES['upload']['error'][$count],
					'size' => $_FILES['upload']['size'][$count],
				);
				$count++;
			}
			// echo "<pre>"; print_r($filearr); echo "</pre>";
			// echo $filearr[0]['type'];
		}
		if($filearr[0]['name']==""){
			$error = false;
			$fileError1 = "Please upload your image.";
		}
		// else if($filearr[0]['type']!="image/jpeg" || $filearr[0]['type']!="image/jpg"){
			// $error = false;
			// $fileError1 = "Image format must be in jpg or jpeg";
		// }
		if($filearr[1]['name']==""){
			$error = false;
			$fileError2 = "Please upload your Sign Doc.";
		}
		// else if($filearr[1]['type']!="image/jpeg" || $filearr[1]['type']!="image/jpg"){
			// $error = false;
			// $fileError2 = "Image format must be in jpg or jpeg";
		// }
		if($filearr[2]['name']==""){
			$error = false;
			$fileError3 = "Please upload your ID Proof.";
		}
		// else if($filearr[2]['type']!="application/pdf"){
			// $error = false;
			// $fileError3 = "Image format must be in pdf format.";
		// }
		
		if($error==true){
			$insertqry = "INSERT INTO `form_data_table`(name, father_name, email_id, password, gender) VALUES('".$name."', '".$fname."', '".$email."', '".$password."', '".$gender."')";
			$result = mysqli_query($database, $insertqry);
			$last_insert_id = mysqli_insert_id($database);
			$errorarray = 0;
			if($result){
				$success = " Data Submitted Successfully.";
				foreach($filearr as $mainfilearr){
					if($mainfilearr['name']==''){
						continue;
					}
					else{
						$filename = $mainfilearr['name'];	
						$filetype = $mainfilearr['type'];	
						$filetmpname = $mainfilearr['tmpname'];	
						$fileerror = $mainfilearr['error'];	
						$filesize = $mainfilearr['size'];	
						$fileinfo = pathinfo($filename);
						$ext = isset($fileinfo['extension'])?$fileinfo['extension']:'';
						$newfilename = uniqid().".".$ext;
						$upload_directory = "../images/".$newfilename;
						$move_file = move_uploaded_file($filetmpname, $upload_directory);
						if($move_file){
							$inserfile = "INSERT INTO `form_documents`(customer_id, docs) VALUES('".$last_insert_id."', '".$newfilename."')";
							$runfileqry = mysqli_query($database, $inserfile);
						}
						else{
							$errorarray++;
						}
					}					
				}
			}
			else{
				$error_msg = "Something went wrong.";
			} 
			if($errorarray>0){
				$fileError = "Some files are not uploaded ".mysqli_errno($database);
			}
		}
	}
	
	function getCustomerImage($customerId, $database){
		$qry = "SELECT * FROM form_documents WHERE customer_id='".$customerId."'";
		$result = mysqli_query($database, $qry);
		return $result;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Listing Page</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<script src="js/jquery.min.js" type="text/javascript"></script>
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
</head>
<body>
	
	<div class="card-group border">
	  <div class="card">
		<form action="" method="POST" enctype="multipart/form-data" class="border border-info m-4 p-5 rounded">
			<?php if(isset($success)): ?>
			<div class="alert alert-success">
			  <strong>Success !</strong><?php echo $success; ?>
			</div>
			<?php endif; ?>
			<?php if(isset($error_msg)): ?>
			<div class="alert alert-danger">
			  <strong>Error !</strong><?php echo $error_msg; ?>
			</div>
			<?php endif; ?>
			<?php if(isset($fileError)): ?>
			<div class="alert alert-warning">
			  <strong>Error !</strong><?php echo $fileError; ?>
			</div>
			<?php endif; ?>
			<div class="form-group">
				<label for="id_name">Name :</label>
				<input type="text" name="name" class="form-control" id="id_name" placeholder="Name">
				<span class="text-danger"><?php echo (isset($nameError)?$nameError:''); ?></span>
			</div>
			<div class="form-group">
				<label for="id_fname">Father's Name :</label>
				<input type="text" name="fname" class="form-control" id="id_fname" placeholder="Father's Name">
				<span class="text-danger"><?php echo (isset($fnameError)?$fnameError:''); ?></span>
			</div>
			<div class="form-group">
				<label for="email">Email/ID :</label>
				<input type="email" name="email" class="form-control" id="email" placeholder="Email or ID">
				<span class="text-danger"><?php echo (isset($emailError)?$emailError:''); ?></span>
			</div> 
			<div class="form-group">
				<label for="pass">Password :</label>
				<input type="password" name="pass" class="form-control" id="pass" placeholder="Password">
				<span class="text-danger"><?php echo (isset($passError)?$passError:''); ?></span>
			</div>
			<div class="form-group">
				<label for="cpass">Confirm Password :</label>
				<input type="password" name="cpass" class="form-control" id="cpass" placeholder="Confirm Password">
				<span class="text-danger"><?php echo (isset($cpassError)?$cpassError:''); ?></span>
			</div>
			<div class="form-group">
				<label>Gender :</label><br>
				<label class="radio-inline">
					<input type="radio" name="gender" id="male" value="Male" > &nbsp;<span for="male">Male</span>
				</label>&nbsp;&nbsp;&nbsp;&nbsp;
				<label class="radio-inline">
					<input type="radio" name="gender" id="female" value="Female"> &nbsp; <span for="female">Female</span>
				</label>&nbsp;&nbsp;&nbsp;&nbsp;
				<label class="radio-inline">
					<input type="radio" name="gender" id="trans" value="Transgender"> &nbsp; <span for="trans">Transgender</span>
				</label><br />
				<span class="text-danger"><?php echo (isset($genError)?$genError:''); ?></span>
			</div>
			<div class="form-group" >
				<label for="file1">Uploade Image :</label>&nbsp;&nbsp;
				<input type="file" name="upload[]" id="file1"><br/>
				<span class="text-danger"><?php echo (isset($fileError1)?$fileError1:''); ?></span>
			</div>
			<div class="form-group" >
				<label for="file2">Uploade Sign :</label>&nbsp;&nbsp;
				<input type="file" name="upload[]" id="file2"><br/>
				<span class="text-danger"><?php echo (isset($fileError2)?$fileError2:''); ?></span>
			</div>
			<div class="form-group" >
				<label for="file3">Proof of identity :</label>&nbsp;&nbsp;
				<input type="file" name="upload[]" id="file3"><br/>
				<span class="text-danger"><?php echo (isset($fileError3)?$fileError3:''); ?></span>
			</div>
			<button type="submit" name="submit" class="btn btn-success">Submit</button>
		</form>
	</div>
	 
	<div class="card">
		<div class="container">
			<h2>Bordered Table</h2>
			<p>The .table-bordered class adds borders to a table:</p>            
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Sr no.</th>
						<th>Name</th>
						<th>Father Name</th>
						<th>Email/ID</th>
						<th>Password</th>
						<th>Gender</th>
						<th>image</th>
						
					</tr>
				</thead>
				<?php 
					// select qry
					$selectqry = "SELECT * FROM  form_data_table limit 10";
					$selectres = mysqli_query($database,$selectqry);
					// $fetch_data = mysqli_fetch_array($selectres);
					 // echo "<pre>"; print_r($fetch_data); echo "</pre>";
					$number = 1;
					while($fetch_data = mysqli_fetch_assoc($selectres)): ?>
						<tbody>
							<tr>
								<td><?php echo $number; ?></td>
								<td><?php echo $fetch_data['name']; ?></td>
								<td><?php echo $fetch_data['father_name']; ?> </td>
								<td><?php echo $fetch_data['email_id']; ?></td>
								<td><?php echo $fetch_data['password']; ?></td>
								<td><?php echo $fetch_data['gender']; ?> </td>
								<td>
									<input type='button' name='viewbtn' class="btn btn-success show_doc" value="View Image" id="customer_img_<?php echo $fetch_data['id']; ?>" >
									<?php $customerResult = getCustomerImage($fetch_data['id'], $database); ?>
									<?php while($docData = mysqli_fetch_assoc($customerResult)): ?>
										<input type="hidden" class="customer_img_<?php echo $fetch_data['id']; ?>" value="<?php echo $docData['docs']?>">
									<?php endwhile; ?>
								</td>
								
							</tr>			  
						</tbody>
						<?php $number++; ?>
					<?php endwhile; ?>
			</table>
		</div>
	</div>
</div>

<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<!--button type="button" class="close" data-dismiss="modal">&times;</button-->
				<h4 class="modal-title">Modal Header</h4>
			</div>
			<div class="modal-body">
				<div class="row customer_img_section"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('document').ready(function(){
		$(".show_doc").click(function(){
			var custId = $(this).attr('id');
			$('.customer_img_section').html('');
			$('.'+custId).each(function(){
				var imgUrl = $(this).val();
				var html = "<div class='col-sm-3'><img class='img-fluid' width='80px' height='80px' src='http://localhost/form/images/"+imgUrl+"'></div>";
				$('.customer_img_section').append(html);
			});
			$('#myModal').modal('show');
		});
	});
</script>		
</body>
</html>