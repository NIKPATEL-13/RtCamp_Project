<?php

	session_start();

	if(isset($_REQUEST['logout']))
	{
		session_destroy();
		header('Location: https://fgallery.000webhostapp.com/nikgallery/login.php?logout=true');	
	}
	require_once("jsonparser.php");
	require_once("config.php");
	require_once("imagecontroller.php");
	
	spl_autoload_register();

try {
	if(isset($_SESSION['facebook_access_token'])) {
		$GLOBALS['accessToken']= $_SESSION['facebook_access_token'];
	} else {
		 $GLOBALS['accessToken'] = $fb->getRedirectLoginHelper()->getAccessToken();
	}
}
catch(Facebook\Exceptions\FacebookResponseException $e){//Facebook\Exceptions\FacebookResponseException $e) {
 	// When Graph returns an error
 	echo 'Graph returned an error: ' . $e->getMessage();
  	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
 	// When validation fails or other local issues
	echo "Facebook SDK returned an error:".$e->getMessage();
	echo '<hr/><a href="'.$logoutUrl.'">Back To Main Page</a>';
  	exit;
 }

if (isset($accessToken)) {
	if (isset($_SESSION['facebook_access_token'])) {
	//	$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
		
	} else {
		// getting short-lived access token
		$_SESSION['facebook_access_token'] = (string) $accessToken;

	  	// OAuth 2.0 client handler
		$oAuth2Client = $fb->getOAuth2Client();

		// Exchanges a short-lived access token for a long-lived one
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);

		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

		// setting default access token to be used in script
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	}

	// redirect the user back to the same page if it has "code" GET variable
	 ob_start();
	if (!isset($_GET['code'])) {
		header('Location: https://fgallery.000webhostapp.com/nikgallery/login.php?fbcode=isnotset');
	}

	// getting basic info about user
	try {
		  $profile_request = $fb->get('me?fields=id,name,email,birthday,cover,picture,hometown,location,albums{count,name,photos{images}}',$accessToken);
		
		  $GLOBALS['profile'] = $profile_request->getGraphNode()->asArray();
		  $GLOBALS['url_data']=$profile_request->getGraphNode();
		  //udfRecursiveTraverse($profile);
		  //echo "<hr/>";
		  //exit;
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		session_destroy();
		// redirecting user back to app login page
		header("Location: https://fgallery.000webhostapp.com/nikgallery/login.php?lol2=ababba");
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		echo '<a href="https://fgallery.000webhostapp.com/nikgallery/login.php?logout=true">Back</a>';
		exit;
	}
	
  	// Now you can redirect to another page and use the access token from $_SESSION['facebook_access_token']
} else {
	header("Location:https://fgallery.000webhostapp.com/nikgallery/login.php?final=hahaha");
}
?>

<!DOCTYPE html>
<html>
<title>FaceBook Gallery</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="./images/fb.png" type="image/png" sizes="16x16">
<link rel="stylesheet" href="./css/w3.css">
<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-blue-grey.css">
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
<link rel="stylesheet" href="css/style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/specialtitle.css">
<style>
html,body,h1,h2,h3,h4,h5 {font-family: "Open Sans", sans-serif}
.w3-top{
    z-index:2;
}
.mySlides {
	width:1200px;
	height:600px;
	display:none;
	 
	}
.slider{
    text-decoration: none;
}
	</style>

<body class="w3-theme-l5" style="background-color: #b5b6b7 !important;">
<form method="GET" action="imagecontroller.php">
<!-- Navbar -->
<div class="w3-top">
 <div class="w3-bar w3-theme-d2 w3-left-align w3-large">
  <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-theme-d2" href="javascript:void(0);" onclick="openNav()"><i class="fa fa-bars"></i></a>
  <a href="#" class="w3-bar-item w3-button w3-padding-large w3-theme-d4"><i class="fa fa-home w3-margin-right"></i><i class="fa fa-facebook"></i><span><b>aceBook</b></span></a>
  <a href="#" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" title="News"><i class="fa fa-globe"></i></a>
  <a href="#" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" title="Account Settings"><i class="fa fa-user"></i></a>
  <a href="#" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" title="Messages"><i class="fa fa-envelope"></i></a>
  <div class="w3-dropdown-hover w3-hide-small">
    <a href="#" class="w3-button w3-padding-large" title="Notifications"><i class="fa fa-bell"></i></a>
  </div>
  
  <div class="w3-dropdown-hover w3-right">
    <button class="w3-button"><img src="./images/avatar2.png" class="w3-circle" style="height:23px;width:23px" alt="Avatar"></button>
    <div class="w3-dropdown-content w3-bar-block w3-border" style="right:0">
	  <?php echo '<b><a href="' . $logoutUrl . '" class="w3-bar-item w3-button">Logout</a></b>'; ?>
    </div>
  </div>
 </div>
</div>

<!-- Navbar on small screens -->
<div id="navDemo" class="w3-bar-block w3-theme-d2 w3-hide w3-hide-large w3-hide-medium w3-large">
  <a href="#" class="w3-bar-item w3-button w3-padding-large">Link 1</a>
  <a href="#" class="w3-bar-item w3-button w3-padding-large">Link 2</a>
  <a href="#" class="w3-bar-item w3-button w3-padding-large">Link 3</a>
  <a href="#" class="w3-bar-item w3-button w3-padding-large">My Profile</a>
</div>

<div class="w3-container w3-content" style="max-width:1400px;margin-top:55px">    
  <!-- The Grid -->
  
  <div class="w3-row">
	<div class="w3-content" style="max-width:1200px">
	  <img class="mySlides" src="<?php echo $profile['albums'][2]['photos'][2]['images'][0]['source'] ?>" >
	  <img class="mySlides" src="<?php echo $profile['albums'][2]['photos'][3]['images'][0]['source'] ?>" >
	  <img class="mySlides" src="<?php echo $profile['albums'][2]['photos'][4]['images'][0]['source'] ?>" >
	  <img class="mySlides" src="<?php echo $profile['albums'][2]['photos'][5]['images'][0]['source'] ?>" >
	  <img class="mySlides" src="<?php echo $profile['albums'][2]['photos'][6]['images'][0]['source'] ?>" >
	  <img class="mySlides" src="<?php echo $profile['albums'][2]['photos'][7]['images'][0]['source'] ?>" >
	  <img class="mySlides" src="<?php echo $profile['albums'][2]['photos'][8]['images'][0]['source'] ?>" >
	</div>

	<div class="w3-center">
	  <div class="w3-section">
		<a class="w3-button w3-light-grey slider" onclick="plusDivs(-1)">< Prev</a>
		<a class="w3-button w3-light-grey slider" onclick="plusDivs(1)">Next ></a>
	  </div>
	  <a class="w3-button demo" onclick="currentDiv(1)">1</a> 
	  <a class="w3-button demo" onclick="currentDiv(2)">2</a> 
	  <a class="w3-button demo" onclick="currentDiv(3)">3</a> 
	  <a class="w3-button demo" onclick="currentDiv(4)">4</a> 
	  <a class="w3-button demo" onclick="currentDiv(5)">5</a> 
	  <a class="w3-button demo" onclick="currentDiv(6)">6</a> 
	  <a class="w3-button demo" onclick="currentDiv(7)">7</a> 
	</div>
  </div>
</div>

<!-- Page Container -->
<div class="w3-container w3-content" style="max-width:1400px;margin-top:30px;margin-left:130px;">    
  <!-- The Grid -->
  <div class="w3-row">
    <!-- Left Column -->
    <div class="w3-col m3">
      <!-- Profile -->
      <div class="w3-card w3-round w3-white">
        <div class="w3-container">
         <h4 class="w3-center"><?php echo $profile['name']; ?></h4>
         <p class="w3-center"><img src="<?php echo $profile['picture']['url'];?>" class="w3-circle" style="height:106px;width:106px" alt="Avatar"></p>
         <hr>
         <p><i class="fa fa-pencil fa-fw w3-margin-right w3-text-theme"></i> Designer, UI</p>
         <p><i class="fa fa-home fa-fw w3-margin-right w3-text-theme"></i>Gujarat, <?php echo $profile['hometown']['name']; ?></p>
         <p><i class="fa fa-birthday-cake fa-fw w3-margin-right w3-text-theme"></i><?php $Date = $profile['birthday']->format('j-F-Y'); echo $Date; ?></p>
		 <p><i class="fa fa-envelope fa-fw w3-margin-right w3-text-theme"></i><?php echo $profile['email'] ?></p>
		</div>
      </div>
      <br>
     
    </div>
    
    <!-- Middle Column -->
    <div class="w3-col m8">
    
      <div class="w3-row-padding">
        <div class="w3-col m12">
          <div class="w3-card w3-round w3-white">
            <div class="w3-container w3-padding">
              <center><button type="button" id="btnopen" class="w3-button w3-theme"><i class="fa fa-camera"> </i> Click To Download And Upload Specific Pictures</button> </center>
            </div>
          </div>
        </div>
      </div>
      
      <div class="w3-container w3-card w3-white w3-round w3-margin"><br>
        <i class="icon-large icon-camera-retro icon-4x"></i><h4 class="w3-card w3-round" id="specialtitle">FaceBook Albums</h4>
        <hr class="w3-clear">
		<div id="main" class="col-sm-12">
			<div class="row">
			  <div class="panel-group">
				<?php 
					udfRecursiveTraversefor_individual_album_pic();
				?>
			  </div>
			</div>
			<div id="gallery" class="row">
			<?php 
				udfRecursiveTraverseforalbum();
			?>
			</div>
			<div>
			<hr/>
				<center><button type="submit" id="submit" name="submit" class="w3-button w3-theme-d1 w3-margin-bottom"><i class="fa fa-thumbs-up"></i> Download And Upload Albums</button></center> 
			</div>
			<div id="for_spcific_pic">
			<hr/>
				<center><button type="submit" id="btn_individual_download" name="btn_individual_download" class="w3-button w3-theme-d1 w3-margin-bottom"><i class="fa fa-thumbs-up"></i>Download Selected Pictures</button></center> 
			</div>
		</div>
      </div>
    </div>
    
  </div>
  
<!-- End Page Container -->
</div>
<br>

<!-- Footer -->
<footer class="w3-container w3-theme-d3 w3-padding-16">
  <center><h5><u>Designed By</u> : Nikhil Patel - 16MCA039 </h5></center>
</footer>
<footer class="w3-container w3-theme-d5">
  <center><p><a href="#" target="_blank">FaceBook Gallery</a></p></center>
</footer>
 
<script>
// Accordion
function myFunction(id) {
    var x = document.getElementById(id);
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
        x.previousElementSibling.className += " w3-theme-d1";
		
    } else { 
        x.className = x.className.replace("w3-show", "");
        x.previousElementSibling.className = 
        x.previousElementSibling.className.replace(" w3-theme-d1", "");
    }
}

// Used to toggle the menu on smaller screens when clicking on the menu button
function openNav() {
    var x = document.getElementById("navDemo");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else { 
        x.className = x.className.replace(" w3-show", "");
    }
}
$(function(){
    $("#btnopen").click(function(){
		$("#submit").slideToggle("fast");
		$("#for_spcific_pic").slideToggle("fast");
        $("#gallery").slideToggle("slow");
		$(".panel-info").slideToggle("slow");
    });
	$(document.body).click(function(evt){
			var currentID = evt.target.id || "No ID!";
				currentID="#"+currentID;
			if(currentID.indexOf("panelhead") >= 0)
			{$(currentID).next().slideToggle("slow");}
	})
});
</script>
<script>
var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {
  showDivs(slideIndex += n);
}

function currentDiv(n) {
  showDivs(slideIndex = n);
}

function showDivs(n) {
  var i;
  var x = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("demo");
  if (n > x.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = x.length}
  for (i = 0; i < x.length; i++) {
     x[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
     dots[i].className = dots[i].className.replace(" w3-red", "");
  }
  x[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " w3-red";
}
</script>
</form>
</body>
</html>