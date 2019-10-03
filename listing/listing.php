<?php 
	if(isset($_POST['submit'])){
		// echo "<pre>"; print_r($_POST); echo "</pre>";
		$name = $_POST['name'];
		$fname = $_POST['fname'];
		$email = $_POST['email'];
		$password = $_POST['pass'];
		$cpass = $_POST['cpass'];
		$gender = isset($_POST['gender'])?$_POST['gender']:'';
		
		if($name==""){
			$nameError = "Please Enter your Name.";
		}
		else if(is_numeric($name)){
			$nameError = "Name must be Characters.";
		}
		else if(strlen($name)<2){
			$nameError = "Name length must be grater than 2 character.";
		}
		
		if($fname==""){
			$fnameError = "Please Enter your Father's Name.";
		}
		else if(is_numeric($fname)){
			$fnameError = "Father Name must be Characters.";
		}
		else if(strlen($fname)<2){
			$fnameError = "Length of input must be grater than 2 character.";
		}
		
		if($email==""){
			$emailError = "Please Enter your email or ID";
		}
		
		if($password==""){
			$passError = "Please Enter your Password";
		}
		else if(strlen($password)<5 || strlen($password)>=10){
			$passError = "Password length must be between 5 to 10 characters.";
		}
		
		if($cpass==""){
			$cpassError = "Please Enter your Password";
		}	
		else if($cpass!=$password){
			$cpassError = "Confirm Password must be same as Password";
		}
			
		if($gender==""){
			$genError = "Please select your gender.";
		}
		
		if(isset($_FILES['upload'])){
			echo "<pre>"; print_r($_FILES); echo "</pre>";
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
		}
		
		
		
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
	<div class="container">
		<div class="row">
			<div class="col-md-6 border bg-light border-primary mt-4 shadow p-3 mb-5 rounded">
				<form action="" method="POST" enctype="multipart/form-data">
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
						<input type="file" name="upload[]" id="file1">
					</div>
					<div class="form-group" >
						<label for="file2">Uploade Sign :</label>&nbsp;&nbsp;
						<input type="file" name="upload[]" id="file2">
					</div>
					<div class="form-group" >
						<label for="file3">Proof of identity :</label>&nbsp;&nbsp;
						<input type="file" name="upload[]" id="file3">
					</div>
					<button type="submit" name="submit" class="btn btn-success">Submit</button>
				</form>
			</div>
		</div>
	</div>
</body>
</html>