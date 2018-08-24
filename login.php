<?php
	session_start();
    require_once("config.php");
	
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<link rel="icon" href="../images/fb.png" type="image/png" sizes="16x16">
        <title>FaceBook Gallery</title>
		<link rel="stylesheet" type="text/css" href="css/stylell.css" />
		
    </head>
    <body>
        <div class="container">
		
			<!-- Codrops top bar -->
            <div class="codrops-top">
                 <strong>&laquo; Delveloped : </strong>Nikhil Patel
                
                <span class="right">
                    <strong>Back to the Codrops Article &raquo;</strong>
                </span>
            </div> 
            <!--/ Codrops top bar-->
			
			<header>
				<h1><i class="icon-large icon-camera-retro icon-4x"></i></h1>
				<br/>
				<h1>Wellcome To<strong> FaceBook Gallery</strong></h1>
				

				<div class="support-note">
					<span class="note-ie">Sorry, only modern browsers.</span>
				</div>
				
			</header>
			
			<section class="main">
				<form class="form-1" method="POST">
				<?php
				    echo '<a href="' . $loginUrl . '"><img src="images/login-with-fb.png"></a>';
				?>
				</form>
			</section>
        </div>
    </body>
</html>