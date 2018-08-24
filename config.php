<?php

	spl_autoload_register();
    require_once 'Facebook/autoload.php';

	$fb = new Facebook\Facebook ([
	  'app_id' => '151318708871083', // '298686670890693'
	  'app_secret' => 'b2026e20f40e1d04d5a92b95940b01cb', // '2d6264c84ec8c4a9f0ec724a12dc830a'
	  'default_graph_version' => 'v2.12', // 'v3.1',
	  ]);

	$helper = $fb->getRedirectLoginHelper();

	$permissions = ['email']; // optional
	
	$loginUrl = $helper->getLoginUrl('https://fgallery.000webhostapp.com/nikgallery/home.php', $permissions);
	
	$logoutUrl = 'https://fgallery.000webhostapp.com/nikgallery/login.php?logout=true';	
	
	$accessToken;
	
?>

