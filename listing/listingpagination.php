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

	if(isset($_GET['id'])){
		$id= $_GET['id'];
		$selectquery = "SELECT * FROM form_data_table WHERE id = '$id'";
		$selectres = mysqli_query($database, $selectquery);
		$rowdata = mysqli_fetch_assoc($selectres);
		// echo "<pre>"; print_r($rowdata); echo "</pre>";		
		$name = $rowdata['name'];
		$fname = $rowdata['father_name'];
		$email = $rowdata['email_id'];
		$password = $rowdata['password'];		
		$gender = $rowdata['gender'];
	}
	
	if(isset($_POST['submit'])){
		// echo "<pre>"; print_r($_POST); echo "</pre>";
		$name = $_POST['name'];
		$fname = $_POST['fname'];
		$email = $_POST['email'];
		$password = $_POST['pass'];
		$cpass = $_POST['cpass'];
		$gender = isset($_POST['gender'])?$_POST['gender']:'';
		$hidden_id = $_POST['hidden_id'];
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
		// if(isset($_FILES['upload'])){
		// 	// echo "<pre>"; print_r($_FILES); echo "</pre>";
		// 	$filearr = [];
		// 	$count = 0;
		// 	foreach($_FILES['upload']['name'] as $value){
		// 		$filearr[] = array(
		// 			'name' => $_FILES['upload']['name'][$count],
		// 			'type' => $_FILES['upload']['type'][$count],
		// 			'tmpname' => $_FILES['upload']['tmp_name'][$count],
		// 			'error' => $_FILES['upload']['error'][$count],
		// 			'size' => $_FILES['upload']['size'][$count],
		// 		);
		// 		$count++;
		// 	}
			// echo "<pre>"; print_r($filearr); echo "</pre>";
			// echo $filearr[0]['type'];
		// }
		if(isset($_FILES['upload_image']['name'])){
			$img_name = $_FILES['upload_image']['name'];
			$img_type = $_FILES['upload_image']['type'];
			$img_tmpname = $_FILES['upload_image']['tmp_name'];
			$img_error = $_FILES['upload_image']['error'];
			$img_size = $_FILES['upload_image']['size'];
			$img_pathinfo = pathinfo($img_name);
			$img_ext = isset($img_pathinfo['extension'])?$img_pathinfo['extension']:'png';
			$img_new_filename = uniqid().".".$img_ext;			
			if($img_name==""){
				$error = false;
				$fileError1 = "Please upload your image.";
			}
			else {
				$img_dir = "../images/".$img_new_filename;
				$check_img_file = move_uploaded_file($img_tmpname, $img_dir);
			}
		}
		// echo "<pre>"; print_r($_FILES); echo "</pre>";
		if(isset($_FILES['upload_sign']['name'])){
			$sign_name = $_FILES['upload_sign']['name'];
			$sign_type = $_FILES['upload_sign']['type'];
			$sign_tmpname = $_FILES['upload_sign']['tmp_name'];
			$sign_error = $_FILES['upload_sign']['error'];
			$sign_size = $_FILES['upload_sign']['size'];
			$sign_pathinfo = pathinfo($img_name);
			$sign_ext = isset($img_pathinfo['extension'])?$img_pathinfo['extension']:'png';
			$sign_new_filename = uniqid().".".$img_ext;			
			if($sign_name==""){
				$error = false;
				$fileError2 = "Please upload your Sign Doc.";
			}
			else {
				$sign_dir = "../images/".$sign_new_filename;
				$check_sign_file = move_uploaded_file($sign_tmpname, $sign_dir);
			}
		}

		if(isset($_FILES['upload_identity']['name'])){
			$id_name = $_FILES['upload_identity']['name'];
			$id_type = $_FILES['upload_identity']['type'];
			$id_tmpname = $_FILES['upload_identity']['tmp_name'];
			$id_error = $_FILES['upload_identity']['error'];
			$id_size = $_FILES['upload_identity']['size'];
			$id_pathinfo = pathinfo($img_name);
			$id_ext = isset($img_pathinfo['extension'])?$img_pathinfo['extension']:'png';
			$id_new_filename = uniqid().".".$img_ext;			
			if($id_name==""){
				$error = false;
				$fileError3 = "Please upload your ID Proof.";
			}
			else {
				$id_dir = "../images/".$sign_new_filename;
				$check_id_file = move_uploaded_file($id_tmpname, $id_dir);
			}
		}
		// if($filearr[0]['name']==""){
		// 	$error = false;
		// 	$fileError1 = "Please upload your image.";
		// }
		// else if($filearr[0]['type']!="image/jpeg" || $filearr[0]['type']!="image/jpg"){
			// $error = false;
			// $fileError1 = "Image format must be in jpg or jpeg";
		// }
		// if($filearr[1]['name']==""){
		// 	$error = false;
		// 	$fileError2 = "Please upload your Sign Doc.";
		// }
		// else if($filearr[1]['type']!="image/jpeg" || $filearr[1]['type']!="image/jpg"){
			// $error = false;
			// $fileError2 = "Image format must be in jpg or jpeg";
		// }
		// if($filearr[2]['name']==""){
		// 	$error = false;
		// 	$fileError3 = "Please upload your ID Proof.";
		// }
		// else if($filearr[2]['type']!="application/pdf"){
			// $error = false;
			// $fileError3 = "Image format must be in pdf format.";
		// }
		
		if($error==true){
			if($hidden_id==""){
				$insertqry = "INSERT INTO `form_data_table`(name, father_name, email_id, password, gender) VALUES('".$name."', '".$fname."', '".$email."', '".$password."', '".$gender."')";
				$result = mysqli_query($database, $insertqry);
				$last_insert_id = mysqli_insert_id($database);
				$errorarray = 0;
				$img_type = "image";
				$sign_type = "sign";
				$id_type = "identity";
				if($result){
					$message = " Data Submitted Successfully.";
					header('location:listingpagination.php?msg=Data Submitted Successfully');
				
					if($check_img_file){
					$insert_img_file = "INSERT INTO `form_documents`(customer_id, docs, file_type) VALUES('".$last_insert_id."', '".$img_new_filename."', '".$img_type."')";
					$run_img_qry = mysqli_query($database, $insert_img_file);
					if(!$run_img_qry){
						$fileError1 = "Something went wrong";
						}
					}
					if($check_sign_file){
					$insert_sign_file = "INSERT INTO `form_documents`(customer_id, docs, file_type) VALUES('".$last_insert_id."', '".$sign_new_filename."', '".$sign_type."')";
					$run_sign_qry = mysqli_query($database, $insert_sign_file);
						if(!$run_sign_qry){
						$fileError2 = "Something went wrong";
						}
					}
					if($check_id_file){
					$insert_id_file = "INSERT INTO `form_documents`(customer_id, docs, file_type) VALUES('".$last_insert_id."', '".$id_new_filename."', '".$id_type."')";
					$run_id_qry = mysqli_query($database, $insert_id_file);
						if(!$run_id_qry){
						$fileError3 = "Something went wrong";
						}
					}
				
					// foreach($filearr as $mainfilearr){
					// 	if($mainfilearr['name']==''){
					// 		continue;
					// 	}
					// 	else{
					// 		$filename = $mainfilearr['name'];	
					// 		$filetype = $mainfilearr['type'];	
					// 		$filetmpname = $mainfilearr['tmpname'];	
					// 		$fileerror = $mainfilearr['error'];	
					// 		$filesize = $mainfilearr['size'];	
					// 		$fileinfo = pathinfo($filename);
					// 		$ext = isset($fileinfo['extension'])?$fileinfo['extension']:'';
					// 		$newfilename = uniqid().".".$ext;
					// 		$upload_directory = "../images/".$newfilename;
					// 		$move_file = move_uploaded_file($filetmpname, $upload_directory);
					// 		if($move_file){
					// 			$inserfile = "INSERT INTO `form_documents`(customer_id, docs) VALUES('".$last_insert_id."', '".$newfilename."')";
					// 			$runfileqry = mysqli_query($database, $inserfile);
					// 		}
					// 		else{
					// 			$errorarray++;
					// 		}
					// 	}					
					// }
				}
				else{
					$error_msg = "Something went wrong.";
				} 
				if($errorarray>0){
					$fileError = "Some files are not uploaded ".mysqli_errno($database);
				}
			}
			else{
				$updateqry = "UPDATE `form_data_table` SET name='$name', father_name='$fname', email_id='$email', password='$password',
				 gender='$gender' WHERE id='$hidden_id'";
				echo $hidden_id;
				$updateResult = mysqli_query($database, $updateqry);
					if($updateResult){
						$update_msg = "Data updated.";
						header('location:listingpagination.php?update_msg=updated successfully');
					}  			
			}			
		}		
	}
	
	// function getCustomerImage($customerId, $database){
	// 	$qry = "SELECT * FROM form_documents WHERE customer_id='".$customerId."'";
	// 	$result = mysqli_query($database, $qry);
	// 	return $result;
	// }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Listing Page</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <style>
        .pagination>li {
    position:relative;
    list-style-type:none;
    float: left;
    padding: 6px 12px;
    margin-left: -1px;
    line-height: 1.42857143;
    color: #337ab7;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #ddd;
    }
    .pagination>li>a{
    text-decoration:none;
    }
    </style>
</head>
<body>
	
	<div class="card-group border">
	  <div class="card">
		<form action="" method="POST" enctype="multipart/form-data" class="border border-info m-4 p-5 rounded">
			<?php if(isset($_GET['update_msg'])): ?>
			<div class="alert alert-success">
			  <strong>Success! </strong><?php echo $_GET['update_msg']; ?>
			</div>
			<?php endif; ?>
			<?php if(isset($_GET['msg'])): ?>
			<div class="alert alert-success">
			  <strong>Success !</strong><?php echo $_GET['msg']; ?>
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
				<input type="text" name="name" class="form-control" id="id_name" placeholder="Name" value="<?php if(isset($name)){echo $name;}?>">
				<span class="text-danger"><?php echo (isset($nameError)?$nameError:''); ?></span>
			</div>
			<div class="form-group">
				<label for="id_fname">Father's Name :</label>
				<input type="text" name="fname" class="form-control" id="id_fname" placeholder="Father's Name" value="<?php if(isset($fname)){echo $fname;}?>">
				<span class="text-danger"><?php echo (isset($fnameError)?$fnameError:''); ?></span>
			</div>
			<div class="form-group">
				<label for="email">Email/ID :</label>
				<input type="email" name="email" class="form-control" id="email" placeholder="Email or ID" value="<?php if(isset($email)){echo $email;}?>">
				<span class="text-danger"><?php echo (isset($emailError)?$emailError:''); ?></span>
			</div> 
			<div class="form-group">
				<label for="pass">Password :</label>
				<input type="password" name="pass" class="form-control" id="pass" placeholder="Password" value="<?php if(isset($password)){echo $password;}?>">
				<span class="text-danger"><?php echo (isset($passError)?$passError:''); ?></span>
			</div>
			<div class="form-group">
				<label for="cpass">Confirm Password :</label>
				<input type="password" name="cpass" class="form-control" id="cpass" placeholder="Confirm Password" value="<?php if(isset($password)){echo $password;}?>">
				<span class="text-danger"><?php echo (isset($cpassError)?$cpassError:''); ?></span>
			</div>
			<div class="form-group">
				<label>Gender :</label><br>
				<label class="radio-inline">
					<input type="radio" name="gender" id="male" value="Male" <?php echo (isset($gender) && $gender=='Male')?'checked':''; ?>> &nbsp;<span for="male" value="Male" >Male</span>
				</label>&nbsp;&nbsp;&nbsp;&nbsp;
				<label class="radio-inline">
					<input type="radio" name="gender" id="female" value="Female" <?php echo (isset($gender) && $gender=='Female')?'checked':'';?>> &nbsp; <span for="female">Female</span>
				</label>&nbsp;&nbsp;&nbsp;&nbsp;
				<label class="radio-inline">
					<input type="radio" name="gender" id="trans" value="Transgender" <?php echo (isset($gender) && $gender=='Transgender')?'checked':'';?>> &nbsp; <span for="trans">Transgender</span>
				</label><br />
				<span class="text-danger"><?php echo (isset($genError)?$genError:''); ?></span>
			</div>
			<div class="form-group" >
				<label for="file1">Uploade Image :</label>&nbsp;&nbsp;
				<input type="file" name="upload_image" id="file1"><br/>
				<span class="text-danger"><?php echo (isset($fileError1)?$fileError1:''); ?></span>
			</div>
			<div class="form-group" >
				<label for="file2">Uploade Sign :</label>&nbsp;&nbsp;
				<input type="file" name="upload_sign" id="file2"><br/>
				<span class="text-danger"><?php echo (isset($fileError2)?$fileError2:''); ?></span>
			</div>
			<div class="form-group" >
				<label for="file3">Proof of identity :</label>&nbsp;&nbsp;
				<input type="file" name="upload_identity" id="file3"><br/>
				<span class="text-danger"><?php echo (isset($fileError3)?$fileError3:''); ?></span>
			</div>
			<div>
				<input type="hidden" name="hidden_id" value="<?php echo isset($id)?$id:''; ?>">
			</div>
			<button type="submit" name="submit" class="btn btn-success">Submit</button>
		</form>
	</div>
	 
	<div class="card">
		<div class="container">
			<h2>Bordered Table</h2>
            <p>The .table-bordered class adds borders to a table:</p>            
            <?php
            if (isset($_GET['page_no']) && $_GET['page_no']!="") {
                $page_no = $_GET['page_no'];
            } 
            else {
                $page_no = 1;
            }
                
            $total_records_per_page = 5;
            $offset = ($page_no-1) * $total_records_per_page;
            $previous_page = $page_no - 1;
            $next_page = $page_no + 1;
            $adjacents = "2";
        
            $result_count = mysqli_query(
                $database,
                "SELECT COUNT(*) As total_records FROM `form_data_table"
            );
            $total_records = mysqli_fetch_array($result_count);
            // echo "<pre>"; print_r($total_records); echo "</pre>";
            $total_records = $total_records['total_records'];
            $total_no_of_pages = ceil($total_records / $total_records_per_page);
            //echo "<pre>"; print_r($total_no_of_pages); echo "</pre>";
            $second_last = $total_no_of_pages - 1;
        
            $selectquery = "SELECT * FROM form_data_table order by id desc limit $offset, $total_records_per_page";
            $runselectqry = mysqli_query($database, $selectquery);
            ?>
			<strong>Page <?php echo $page_no." of ".$total_no_of_pages; ?></strong>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Sr no.</th>
						<th>Name</th>
						<th>Father Name</th>
						<th>Email/ID</th>
						<th>Password</th>
						<th>Gender</th>						
						<th>Action</th>						
						<th>Delete</th>						
					</tr>
				</thead>
				<?php 
					// select qry
					//$selectqry = "SELECT * FROM  form_data_table limit 10";
					//$selectres = mysqli_query($database,$selectqry);
					// $fetch_data = mysqli_fetch_array($selectres);
					//echo "<pre>"; print_r($fetch_data); echo "</pre>";
					$number = $offset+1;
                    while($fetch_data = mysqli_fetch_assoc($runselectqry)):?>
                    <tr>          
                        <td><?php echo $number; ?></td>
                        <td><?php echo $fetch_data['name']; ?></td>
                        <td><?php echo $fetch_data['father_name']; ?></td>
                        <td><?php echo $fetch_data['email_id']; ?></td>        
                        <td><?php echo $fetch_data['password']; ?></td>        
                        <td><?php echo $fetch_data['gender']; ?></td>						                        
                        <td><a href='listingpagination.php?id=<?php echo $fetch_data['id'] ?>'>Edit</a></td>
						<td><a onclick='return confirm("Are you sure to delete")' href='listingpagination.php?delete_id=<?php $fetch_data['id']?>'>Delete</a></td>                        
                    </tr>
                    <?php $number++; ?>                    
                <?php endwhile;?>                
			</table>
			<?php echo "<pre>"; $fetch_data['id']; echo "</pre>"; ?>
            <div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
            
            </div>
                <ul class="pagination">
                <?php
                    if($page_no <= 1){
                        echo "";
                    }else{
                        echo "<li><a href='listingpagination.php?page_no=".$previous_page."'>Previous Page</a></li>";
                    }
                    
                    for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
                        if ($counter == $page_no) {
                            echo "<li class='active'><a>$counter</a></li>"; 
                        }
                        else{
                            echo "<li><a href='listingpagination.php?page_no=".$counter."'>$counter</a></li>";
                        }            
                    }
                    if($page_no<$total_no_of_pages){
                        echo "<li><a href='listingpagination.php?page_no=".$next_page."'>Next Page</a></li>";
                    }
                    else{echo "";}                    
                    ?>   
                </ul>
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