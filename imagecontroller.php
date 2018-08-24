<?php     
//me?fields=id,name,albums{id,name,count,photos{picture}}
$md_array = array ();
$retval = array ();
$url="https://graph.facebook.com/v3.1/me?fields=id%2Cname%2Cemail%2Cbirthday%2Cpicture%2Chometown%2Clocation%2Calbums%7Bcount%2Cname%2Cphotos%7Bpicture%7D%7D&access_token=".$_SESSION['facebook_access_token'];
function udfRecursiveTraverseforalbum()
{
		ob_start();   
		global $md_array;
		global $retval;
        global $url;
 
        $header = array("Content-type: application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $st = curl_exec($ch);
        
        $retval = json_decode($st,true);
		
		 
		$l=count($retval['albums']['data']);

		for($i=0;$i<$l;$i++)
		{
			$albums_name=$retval['albums']['data'][$i]['name'];
			$pic=$retval['albums']['data'][$i]['photos']['data'];
			
			$total_pic=$retval['albums']['data'][$i]['count'];	
			
			$md_array[$albums_name][] =$pic;
		}
		
		$ll=count($md_array);
		
		if(!isset($_GET['submit']))
		{
			foreach($md_array as $key=>$val)
			{
			   $a=$val[0];
			   $l=count($a);
			   echo "<div class=\"col-xs-3 gallery-item\">";
			   echo "<div class='album' style=\"margin-bottom: 25px; font-family:  fantasy;\">
						<center><span>$key</span></center>
					 </div>";
			   
			   echo "<div class=\"album\">";
			
			   for($i=0;$i<$l;$i++)
			   {
				   $xx=$md_array[$key][0][$i]['picture'];
				  
				   echo "<img src=\"$xx\" style=\"height:150px; width:150px;\">";
		 
				  
			   }
			   echo "</div>";
			   echo "<div class='album' style=\"margin-top: 25px; font-family:  fantasy;\">
						<center> <input type='checkbox' name='profileAlbums[]' value=\"$key\">Download</center>
					 </div>";
			   echo "</div>";
			}	
		}
}

		$xx=array();
		if(isset($_GET['submit']))
		{
			
			udfRecursiveTraverseforalbum();
			
			$total_album=count($retval['albums']['data']);
			if(isset($_GET['profileAlbums']))
			{
				 $select_data = $_GET['profileAlbums'];
				 
				 for($x=0;$x<count($select_data);$x++)
				 {
					  $album_name=$select_data[$x];
					  $all_pic=$md_array[$album_name][0];
					  
					  for($i=0;$i<count($all_pic);$i++)
					  {
						array_push($xx,$all_pic[$i]['picture']);
					  }
					
			   
					   $file_zip_name="$album_name".".zip";
					   
					   
					   
					   
				 }
					   download_zip($xx,$album_name);
			}
			else{
				echo "No Album Has Selected..!";
			}
	   	}
		if(isset($_GET['btn_individual_download']))
		{
			if(isset($_GET['profileCombineAlbums']))
			{
				$select_data = $_GET['profileCombineAlbums'];
				download_zip($select_data,"CustomAlbum");
			}
			else
			{
				echo "No picture has been selected..!!!";
			}
	   	}
		function download_zip($x,$file_name)
		{
			
			$files = $x;
			
			$tmpFile = tempnam('/tmp', '');
			
			$zip = new ZipArchive;
			$zip->open($tmpFile, ZipArchive::CREATE);
			foreach ($files as $file) 
			{
				// download file
				$fileContent = file_get_contents($file);
				$f_name=explode ("?",$file);
				$only_name=$f_name[0];
				
				$zip->addFromString(basename($only_name), $fileContent);	
			}
			$zip->close();
			
			$file_zip_name="$file_name".".zip";
			$zip_name = str_replace(' ', '_', $file_zip_name);
			
			header('Content-type: application/zip');
			header("Content-disposition: attachment; filename=$zip_name");
			header('Content-length: ' . filesize($tmpFile));
			readfile($tmpFile);
			
			 //unlink($tmpFile);
					 
			 header("location:driveuploader.php?filename=$file_name"); 
		}
		
		function udfRecursiveTraversefor_individual_album_pic()
		{
			global $md_array;
			global $retval;
			global $url;
	 
			$header = array("Content-type: application/json");
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			$st = curl_exec($ch);
			
			$retval = json_decode($st,true);
			
			
			$l=count($retval['albums']['data']);

			for($i=0;$i<$l;$i++)
			{
				$albums_name=$retval['albums']['data'][$i]['name'];
				$pic=$retval['albums']['data'][$i]['photos']['data'];
				$total_pic=$retval['albums']['data'][$i]['count'];
				
				$md_array[$albums_name][] =$pic;
			}
			
			$ll=count($md_array);
			$Divcounter=0;
			
			foreach($md_array as $key=>$val)
			{
			   $a=$val[0];
			   $l=count($a);
			   
			   startalbum($key,$Divcounter);
			   
			   $Divcounter++;
			   for($i=0;$i<$l;$i++)
			   {
				  $xx=$md_array[$key][0][$i]['picture'];
				  
				  set_individual_album_pic($xx);
			   }
			   
			   endalbum();
			}	
		}
		function set_individual_album_pic($pic_link)
		{
			
				
				echo "<div class='col-xs-3 gallery-item' style='margin-top: 5px;'>";
					echo "<div class='album'>";
						echo "<img src=\"$pic_link\" style='height:150px; width:150px;' alt='' />";
					echo "</div>";
					echo "<div class='album' style='margin-top: 10px;padding-right: 25px; font-family:  fantasy;'>";
						echo "<center><input type='checkbox' name='profileCombineAlbums[]' value=\"$pic_link\">Download</center>";
					echo "</div>";
				echo "</div>";
				
		    
		}
		function startalbum($albumName,$Divid)
		{
			$panelid="panelhead".$Divid;
			$subpanelid="subpanel".$Divid;
			echo "<div class='panel panel-info'>";
			echo "<div id='$panelid' class='panel-heading w3-theme-d2'>$albumName</div>";
			echo "<div id='$subpanelid' class='panel-body'>";
		}
		function endalbum()
		{
			
			echo "</div>";
		    echo "</div>";
		}
 ?>
