<?php
	$host = "localhost";
	$user = "root";
	$passw ="";
	$db = "neformdatabase";
	// connection with database
	$link = mysqli_connect($host, $user, $passw, $db);
	if (!$link) {
		echo "Database Connection Error";
		echo "<br>";
		echo mysqli_connect_errno();
		die;		
	}
	if(isset($_GET['msg'])){
		$success_msg = $_GET['msg'];
	}
	if(isset($_GET['id'])){
		$id = $_GET['id'];
		$select = "SELECT `my_data_table`.*, `data_table_images`.images, `data_table_images`.sign_images, `data_table_images`.doc_image, `data_table_images`.id as image_id  FROM `my_data_table` join `data_table_images` on `my_data_table`.id = `data_table_images`.customer_id  where `my_data_table`.id='$id'";
		$res = mysqli_query($link, $select);
		$rowdata = mysqli_fetch_assoc($res);
		// echo "<pre>"; print_r($rowdata); echo "</pre>"; 
		$rowname = $rowdata['name']; 
		$rowpass = $rowdata['password']; 
		$rowimage = $rowdata['images']; 
		$rowsign = $rowdata['sign_images']; 
		$rowdoc = $rowdata['doc_image']; 
	}
	if (isset($_POST['submit'])) {
		// print Post array
		// echo "<pre>"; print_r($_POST); echo "</pre>";
		$name = $_POST['name'];
		$pass = $_POST['pwd'];
		echo $hidden_id = $_POST['hidden_id'];
		$error = true;
		if($name==""){
			$error = false;
			$name_error = "Name can't be balnk.";
		}
		if($pass==""){
			$error = false;
			$pass_error = "Please fill your Password.";
		}
		if(!isset($_GET['id'])){
			if($_FILES['img_file']['name']==''){
				$error = false;
				$image_error = "Please Upload Image.";
			}
	
			if($_FILES['sign_file']['name']==''){
				$error = false;
				$sign_error = "Please Upload Sign Image.";
			}
	
			if($_FILES['doc_file']['name']==''){
				$error = false;
				$doc_error = "Please Upload Doc Image.";
			}
		}
		

		if($error==true){
			if($hidden_id==""){
				$insertqry = "INSERT INTO `my_data_table`(name, password) VALUES('".$name."', '".$pass."')"; 
				$result = mysqli_query($link, $insertqry);
				$last_insert_id = mysqli_insert_id($link);
				if (isset($_FILES['img_file'])) {					
					$img_filename = upload_img($_FILES['img_file']);
					$sign_filename = upload_img($_FILES['sign_file']);
					$doc_filename = upload_img($_FILES['doc_file']);
					if($img_filename=="" || $sign_filename=="" || $doc_filename==""){
						// $error = false;
						$error_msg = "Please upload your file.";
					}				
					if($result){
						$fileinsert = "INSERT INTO `data_table_images`(customer_id, images, sign_images, doc_image)
						VALUES('".$last_insert_id."', '".$img_filename."', '".$sign_filename."', '".$doc_filename."')";
						$file_res = mysqli_query($link, $fileinsert);
						if($file_res){
							$success_msg = "Data submitted successfully";
						}
						else{
							$danger_msg = "Data not submitted.";
						}
					} 			
				}
			}					
			else{
				$fileError = true;
				if(isset($_FILES['img_file_update'])){
					if($_FILES['img_file_update']['name']==''){
						$fileError = false;
						$image_error = "Please Upload Image.";
					}
					else{
						$img_filename = upload_img($_FILES['img_file_update']);
					}
				}

				if(isset($_FILES['sign_file_update'])){
					if($_FILES['sign_file_update']['name']==''){
						$fileError = false;
						$sign_error = "Please Upload Sign Image.";
					}
					else{
						$sign_filename = upload_img($_FILES['sign_file_update']);
					}
				}

				if(isset($_FILES['doc_file_update'])){
					if($_FILES['doc_file_update']['name']==''){
						$fileError = false;
						$doc_error = "Please Upload Doc Image.";
					}
					else{
						$doc_filename = upload_img($_FILES['doc_file_update']);
					}
				}

				if($fileError==true){
					if(isset($img_filename)){
						$updateImageFile = "UPDATE `data_table_images` SET images='$img_filename' WHERE customer_id='$hidden_id'";
						$updateres = mysqli_query($link, $updateImageFile);
					}

					if(isset($sign_filename)){
						$updatesignFile = "UPDATE `data_table_images` SET sign_images='$sign_filename' WHERE customer_id='$hidden_id'";
						$updateres = mysqli_query($link, $updatesignFile);
					}

					if(isset($doc_filename)){
						$updateImageFile = "UPDATE `data_table_images` SET doc_image='$doc_filename' WHERE customer_id='$hidden_id'";
						$updateres = mysqli_query($link, $updateImageFile);
					}

					$updateMain = "UPDATE `my_data_table` SET name='$name', password='$pass' WHERE id='$hidden_id'";
					$updateres = mysqli_query($link, $updateMain);
					if($updateres){
						$message = "Data updated successfully";
						header('location:join.php?msg='.$message);
						exit();
					}
					else{
						$update_error = "Somthing went wrong";
					}

					//$updateqry = "UPDATE `my_data_table` inner join `data_table_images` ON `my_data_table`.id = `data_table_images`.customer_id SET name='$name', password='$pass', images='$img_filename', sign_images='$sign_filename', doc_image='$doc_filename' WHERE `my_data_table`.id='$hidden_id'";
					// $updateres = mysqli_query($link, $updateqry);
					
				}				      
			}			
		}
	}
	
	function upload_img($file_data){
		// echo "<pre>"; print_r($file_data); echo "</pre>";
		$file_name = $file_data['name'];
		$file_type = $file_data['type'];
		$file_tmpname = $file_data['tmp_name'];
		$file_error = $file_data['error'];
		$file_size = $file_data['size'];
		$fileinfo = pathinfo($file_name);
		$ext = isset($fileinfo['extension'])?$fileinfo['extension']:'png'; 
		$new_filename = uniqid().".".$ext;
		$file_dir = "../images/".$new_filename;
		$move_file = move_uploaded_file($file_tmpname, $file_dir);
		if($move_file){
			return $new_filename;
		}
		else{
			return "";
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container-fluid">    
    <div class="row">
		<div class="col-md-6">
			<h2>Vertical (basic) form</h2>
			<form action="" method="POST" enctype="multipart/form-data">
        <?php
          if(isset($update_msg)): ?>
            <div class="alert alert-success">
						<strong>Success! </strong><?php echo isset($update_msg)?$update_msg:"" ?>
						</div>
        <?php elseif(isset($update_error)) :?>
            <div class="alert alert-danger">
						<strong>Error! </strong><?php echo isset($update_error)?$update_error:"" ?>
						</div>              
        <?php endif; ?>
				<?php
					if(isset($success_msg)){ ?>
						<div class="alert alert-success">
						<strong>Success! </strong><?php echo isset($success_msg)?$success_msg:"" ?>
						</div><?php
					}
				?>
				<?php 
					if (isset($danger_msg)) {?>
						<div class="alert alert-danger">
						<strong>Danger! </strong><?php echo isset($danger_msg)?$danger_msg:"" ?>
						</div><?php
					}
				?>								
				<div class="form-group">
					<label for="name">Name:</label>
					<input type="text" class="form-control" id="name" placeholder="Enter name" name="name" value="<?php echo isset($rowname)?$rowname:''?>">
					<span style="color:red"><?php echo (isset($name_error)?$name_error:'') ?></span>
				</div>
				<div class="form-group">
					<label for="pwd">Password:</label>
					<input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd" value="<?php echo isset($rowpass)?$rowpass:''?>"">
					<span style="color:red"><?php echo isset($pass_error)?$pass_error:'' ?></span>
				</div>
				<div class="form-group">
          <?php
              if(isset($rowimage)): ?>
                <div id='rmv_img' style="display: <?php echo (isset($image_error)?'none':'block') ?>;">
                  <img src="../images/<?php echo $rowimage; ?>" alt="user_image" width="100px">
                  <button type="button" name="rmvbtn1" onclick="myfunction('rmv_img', 'img_file')">Remove</button>
                </div>
                <div class='rmv_img' style="display:<?php echo (isset($image_error)?'block':'none') ?>;">	
                  <label for="imgfile">Upload Image:</label>
                  <input type="file" class="form-control" id="imgfile" placeholder="" name="<?php echo isset($image_error)?'img_file_update':'img_file'; ?>">
                  <span style="color:red"><?php echo (isset($image_error)?$image_error:'') ?></span>
                </div>
              <?php else: ?>
                  <div class='rmv_img'>
                    <label for="imgfile">Upload Image:</label>
                    <input type="file" class="form-control" id="imgfile" placeholder="" name="img_file">
                    <span style="color:red"><?php echo (isset($image_error)?$image_error:'') ?></span>
                  </div>
              <?php endif; ?>				
				</div>
        <div class="form-group">
          <?php if(isset($rowsign)): ?>
            <div id='rmv_sign' style="display: <?php echo (isset($sign_error)?'none':'block') ?>;">
              <img src="../images/<?php echo $rowsign; ?>" alt="user_image" width="100px">
              <button type="button" name="rmvbtn2" onclick="myfunction('rmv_sign', 'sign_file')" >Remove</button>
            </div>
            <div class='rmv_sign' style="display:<?php echo (isset($sign_error)?'block':'none') ?>;;">
              <label for="signfile">Upload Signature :</label>
              <input type="file" class="form-control" id="signfile" placeholder="" name="<?php echo isset($sign_error)?'sign_file_update':'sign_file'; ?>">
              <span style="color:red"><?php echo (isset($sign_error)?$sign_error:'') ?></span>
            </div>
          <?php else: ?>  
            <div class='rmv_sign'>
              <label for="signfile">Upload Signature :</label>
              <input type="file" class="form-control" id="signfile" placeholder="" name="sign_file">
              <span style="color:red"><?php echo (isset($sign_error)?$sign_error:'') ?></span>
            </div>
          <?php endif;  ?>					
				</div>
				<div class="form-group">
          <?php if(isset($rowdoc)): ?>
            <div id='rmv_doc' style="display: <?php echo (isset($doc_error)?'none':'block') ?>;">
              <img src="../images/<?php echo $rowdoc; ?>" alt="user_image" width="100px">
              <button type="button" name="rmvbtn3" onclick="myfunction('rmv_doc', 'doc_file')">Remove</button>
            </div>
            <div class='rmv_doc' style="display:<?php echo (isset($doc_error)?'block':'none') ?>;">
              <label for="docfile">Upload Document :</label>
              <input type="file" class="form-control" id="docfile" placeholder="" name="<?php echo isset($doc_error)?'doc_file_update':'doc_file'; ?>">
              <span style="color:red"><?php echo (isset($doc_error)?$doc_error:'') ?></span>
            </div>
          <?php else: ?> 
            <div class='rmv_doc'>
              <label for="docfile">Upload Document :</label>
              <input type="file" class="form-control" id="docfile" placeholder="" name="doc_file">
              <span style="color:red"><?php echo (isset($doc_error)?$doc_error:'') ?></span>
            </div>
        <?php endif; ?>					
				</div>
        <div>
          <input type="hidden" name="hidden_id" value="<?php echo isset($id)?$id:''; ?>">
        </div>
				<div class="form-group">
					<label><input type="checkbox" name="remember"> Remember me</label>
				</div>
				<button type="submit" class="btn btn-success" name="submit">Submit</button>				
			</form>
		</div>
		<div class="col-md-6">
			<h2>My Table Data</h2>
			<p>The table-bordered class adds borders to a table:</p>
			            
			<table class="table table-bordered">
				<thead>
				<tr>
					<th>Sr no.</th>
					<th>Name</th>
					<th>Password</th>
					<th>image</th>
					<th>Sign</th>
					<th>Docs</th>
					<th>Action</th>
				</tr>
				</thead>
				
				<?php
				$selectqry = "SELECT `my_data_table`.*, `data_table_images`.images, `data_table_images`.sign_images, `data_table_images`.doc_image, `data_table_images`.id as image_id  FROM `my_data_table` join `data_table_images` on `my_data_table`.id = `data_table_images`.customer_id  order by my_data_table.id desc limit 5 ";
				$selectres = mysqli_query($link, $selectqry);				
				$number = 1;
				while($fetch_data = mysqli_fetch_assoc($selectres)){
					// echo "<pre>"; print_r($fetch_data); echo "</pre>"; die;
					?>
					<tbody>
					<tr>
						<td><?php echo $number++; ?></td>						
						<td><?php echo $fetch_data['name']; ?></td>						
						<td><?php echo $fetch_data['password']; ?></td>						
						<td><img src="../images/<?php echo $fetch_data['images']; ?>" alt="image" width="50px" height="50px"></td>						
						<td><img src="../images/<?php echo $fetch_data['sign_images']; ?>" alt="image" width="50px" height="50px"></td>						
						<td><img src="../images/<?php echo $fetch_data['doc_image']; ?>" alt="image" width="50px" height="50px"></td>												
						<td><a href="join.php?id=<?php echo $fetch_data['id']; ?>">edit</a></td>
					</tr>				
					</tbody>
				<?php } ?>				
			</table>
		</div>
	</div>
</div>
<script>
  function myfunction(id, name){
    document.getElementById(id).style.display = "none";
    document.getElementsByClassName(id)[0].style.display = "block";
    document.getElementsByName(name)[0].setAttribute('name', name+'_update');
  }
</script>
</body>
</html>
