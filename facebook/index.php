<?php 
	/* Author: Aizaz Ud Din (Aizaz.dinho)*/
	/* Made By: Meralesson.com*/
	include 'core/main.php';
	$check  = new Main;
	$get    = new Main;
	$send   = new Main;
   @$user_id = $_SESSION['user_id'];
   	//fetching user data by user_id
	$data  = $get->user_data($user_id);
	// fetching posts from database
	$post  = $get->posts();
	//check user submit  data
	if(isset($_POST['submit'])){
		$status  = $_POST['status'];
		//checking image if isset
		if (isset($_FILES['post_image'])===true) {
			//if image is not empty 
			 if (empty($_FILES['post_image']['name']) ===true) {
				if(!empty($status)===true){
					$send->add_post($user_id,$status);
				}
			 	 }else {
			 	 //checking image format                                                                                                       
				 $allowed = array('jpg','jpeg','gif','png'); 
				 $file_name = $_FILES['post_image']['name']; 
				 $file_extn = strtolower(end(explode('.', $file_name)));
				 $file_temp = $_FILES['post_image']['tmp_name'];
				 
				 if (in_array($file_extn, $allowed)===true) {
				 	$file_parh = 'images/posts/' . substr(md5(time()), 0, 10).'.'.$file_extn;
				   move_uploaded_file($file_temp, $file_parh);
				   $send->add_post($user_id,$status,$file_parh);

				 }else{
				  echo 'incorrect File only Allowed with less then 1mb ';
				  echo implode(', ', $allowed);
				 }
			 }
			
		}

	}
?>
<html>
	<head>
		<title>Posting System like Facebook</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0" />
		<link rel="stylesheet" href="css/style.css"/>
		<script type="text/javascript" src="js/jquery.js"></script>

	</head>
<body>
	<!-----------Head-------->
<div class="head">
	<div class="head-in">
		<diV class="head-logo">
			<h1 class="h-1">MeriBook</h1>
		</div>
		<div id="login">
		<?php //check user is logged in or not
			if($check->logged_in() === false){
				include 'login.php';
			}else{
				echo '<div id="logout">
					  <li><a href="logout.php">Logout</a>
					 </div>';
			} 
		?>
		</div>
	</div>
</div>

<?php if($check->logged_in() === true){?>

	<div class="wrapper">		
		<!--content -->
		<div class="content">
			<!--left-content-->
			<div class="center">
				<div class="posts">
					<div class="create-posts">
 					<form action="" method="post" enctype="multipart/form-data">
						<div class="c-header">
							<div class="c-h-inner">
								<ul>	
									<li style="border-right:none;"><img src="img/icon3.png"></img><a href="#">Update Status</a></li>
									<li><input type="file"  onchange="readURL(this);" style="display:none;" name="post_image" id="uploadFile"></li>
									<li><img src="img/icon1.png"></img><a href="#" id="uploadTrigger" name="post_image">Add Photos/Video</a></li>
									<li style="border: none;"><img src="img/icon2.png"></img><a href="#">Create Photo Album</a></li>
								</ul>
							</div>
						</div>
						<div class="c-body">
							<div class="body-left">
								<div class="img-box">
									<img src="<?php echo $data['profile_image'];?>"></img>
									
								</div>
							</div>
							<div class="body-right">
								<textarea class="text-type" name="status" placeholder="What's on your mind?"></textarea>
							</div>
							<div id="body-bottom">
							<img src="#"  id="preview"/>
							</div>
						</div>
						<div class="c-footer">
							<div class="right-box">
								<ul>
									<li><button class="btn1"><img class="iconw-margin" src="img/iconw.png"></img>Public<img class="iconp-margin" src="img/iconp.png"></img></button></li>
									<li><input type="submit" name="submit" value="Post" class="btn2"/></li>
								</ul>
							</div>
								
							</div>
						</div>
						</div>
						<script type="text/javascript">
						 //Image Preview Function
								$("#uploadTrigger").click(function(){
								   $("#uploadFile").click();
								});
						        function readURL(input) {
						            if (input.files && input.files[0]) {
						                var reader = new FileReader();

						                reader.onload = function (e) {
						                	$('#body-bottom').show();
						                    $('#preview').attr('src', e.target.result);
						                }

						                reader.readAsDataURL(input.files[0]);
						            }
						        }

						</script>
						<?php foreach($post as $row){
							//fetching all posts
							$time_ago = $row['status_time'];
						echo '
						<div class="post-show">
									<div class="post-show-inner">
										<div class="post-header">
											<div class="post-left-box">
												<div class="id-img-box"><img src="'.$row['profile_image'].'"></img></div>
												<div class="id-name">
													<ul>
														<li><a href="#">'.$row['username'].'</a></li>
														<li><small>'.$get->timeAgo($time_ago).'</small></li>
													</ul>
												</div>
											</div>
											<div class="post-right-box"></div>
										</div>
									
											<div class="post-body">
											<div class="post-header-text">
												'.$row['status'].'
											</div>'.( ($row['status_image'] != 'NULL') ? '<div class="post-img">
												<img src="'.$row['status_image'].'"></img></div>' : '').'
											<div class="post-footer">
												<div class="post-footer-inner">
													<ul>
														<li><a href="#">Like</a></li>
														<li><a href="#">Comment</a></li>
														<li><a href="#">Share</a></li>
													</ul>	
												</div>
											</div>
										</div>
									</div>
								</div><br> ';	
					}	
				?>
					</div>
					</form>	
													
			</div>

 

</div>
<?php }?>
</body>
</html>