<?php
session_start();
include_once '../NikGallery/src/Google_Client.php';
include_once '../NikGallery/src/contrib/Google_Oauth2Service.php';
require_once '../NikGallery/src/contrib/Google_DriveService.php';

$client = new Google_Client();
$client->setClientId('464708382044-7612avfohlotm5ck11ealmbr6q74bdrd.apps.googleusercontent.com');
$client->setClientSecret('wWOj5J_Y_MDs104uCeUnaIgL');
$client->setRedirectUri('https://fgallery.000webhostapp.com/nikgallery/driveuploader.php');
$client->setScopes(array('https://www.googleapis.com/auth/drive.file'));


	
if (isset($_GET['code']) || (isset($_SESSION['access_token']))) 
{
	
	
	$service = new Google_DriveService($client);
    if (isset($_GET['code'])) 
	{
		$client->authenticate($_GET['code']);
		$_SESSION['access_token'] = $client->getAccessToken();		
    } 
	else
	{  
		$client->setAccessToken($_SESSION['access_token']);
	}
    
    //uploading the zip file
    if(isset($_GET['filename']))
	{
		$fileName=$_GET['filename'];
		$file = new Google_DriveFile();
		$file->setTitle($fileName);
		$file->setMimeType('application/zip');
		$file->setDescription('A User Details is uploading in json format');
    
	
		$createdFile = $service->files->insert($file, array(
          'data' =>file_get_contents('A.zip'),// file path
          'mimeType' => 'application/zip',
		  'uploadType'=>'multipart'
        ));
	}
   
	//unlink($fileName);
	echo "Drive Upload Successfully Done..!<br/>";
   echo "<a href='javascript:history.go(-1)'>Go To Back...</a>";
	
} 
else 
{
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit();
}

?>