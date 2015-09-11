<?php session_save_path('sessions'); if(!isset($_SESSION)){session_start();}
include "config/string_variables.php";

$str_url="";
$html="";
$download_time=date("Y-m-d H:i:s");
$eightylegs_key = "vk9wi2xcg1hsozjyfv84klfur4c8u2o5";
$crawl_names = retrieveCrawlNames($eightylegs_key);
$crawl_name_array = explode(",",$crawl_names);
$crawl_name="";
$crawl_status="";
//l6pe7j005p38b5wst3r3zcr2vtvh8ud2
//bl vk9wi2xcg1hsozjyfv84klfur4c8u2o5
//Retrieve crawl names
foreach($crawl_name_array as $crawl_split)
{
	$split_arr_2 = explode(":",$crawl_split);

	$identifier = stripChars($split_arr_2[0]);
	$field_value = stripChars($split_arr_2[1]);

	switch ($identifier)
	{
		case "name":
			$crawl_name = $field_value;
		break;
		
		case "status":
			$crawl_status = $field_value;
		break;	
	}
		
	if($crawl_name!=""&&$crawl_status!="")
	{
			$html.="<form method = 'POST' action='index.php'>";
			$html.="<label style='width:400px;float:left; clear:left; padding:3px;'>".$crawl_name." - ".$crawl_status ."</label>";
			$html.="<input style='width:200px; float:left;  background:#eee; border:none; border-radius:4px; padding:3px; ' type='submit' value='Check Files'/>";
			$html.="<input type='hidden' value='".$crawl_name."' name='site_url_name' />";
			$html.="</form>";	
			
			//Check DB for this crawl name
			$sql = "SELECT * FROM tbl_file_download WHERE site_status = :site_status AND site_url = :site_url LIMIT 0,1";
			try 
			{$ps = $conn->prepare($sql);	
			$params=array(
			'site_status'=>"ADDED",
			'site_url'=>$crawl_name
			);
			$ps->execute($params);
			} catch(PDOException $e) {$html.="->db_fail";}
				
			$count=0;
			foreach($ps as $row)
			{$count++;}
			
			//Add crawl to DB if not already there
			if($count==0)
			{
				$file_name = "";
				$site_url = $crawl_name;
				$site_status="ADDED";
				
				$sql = "INSERT INTO tbl_file_download
				(file_name,site_url,download_time,site_status) VALUES (:file_name,:site_url,:download_time,:site_status)";
				try 
				{$ps = $conn->prepare($sql);	
				$params=array(
				'file_name'=>$file_name,
				'site_url'=>$site_url,
				'download_time'=>$download_time,
				'site_status'=>$site_status
				);
				$ps->execute($params);
				} catch(PDOException $e) {$html.="->db_fail";}			
			}
	
			//Reset crawl name and status for next iteration
			$crawl_name="";
			$crawl_status="";
	}
}




function stripChars($str)
{
	$str = str_replace("\"","",$str);
	$str = str_replace("[","",$str);
	$str = str_replace("]","",$str);
	return $str;
}

function retrieveCrawlNames($eightylegs_key)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,  "https://".$eightylegs_key.":@api.80legs.com/v2/crawls");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);
	curl_close($ch);

	return $response;
}

$site_url_name="";
if(isset($_POST['site_url_name']))
{$site_url_name = $_POST['site_url_name'];}

if($site_url_name!="")
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,  "https://".$eightylegs_key.":@api.80legs.com/v2/crawls/".$site_url_name);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($ch);
	curl_close($ch);

	$split_arr = explode(",",$response);
	foreach($split_arr as $response_str)
	{
		$split_arr_2 = explode(":",$response_str);
		$identifier = $split_arr_2[0];
		$field_value = $split_arr_2[1];
		
		if($identifier=='"id"')
		{
			$crawl_id = $field_value;
		}
		
		if($identifier=='"status"')
		{
			$site_status = $field_value;
			$sql = "INSERT INTO tbl_file_download
			(site_url,download_time,site_status) VALUES (:site_url,:download_time,:site_status)";
			try 
			{$ps = $conn->prepare($sql);	
			$params=array(
			'site_url'=>$site_url_name,
			'download_time'=>$download_time,
			'site_status'=>$site_status
			);
			$ps->execute($params);
			} catch(PDOException $e) {$html.="->db_fail";}
		}

		
	}

//CHECK DOWNLOADS

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,  "https://".$eightylegs_key.":@api.80legs.com/v2/results/".$site_url_name);

// Set so curl_exec returns the result instead of outputting it.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Get the response and close the channel.

$downloads_response = curl_exec($ch);

curl_close($ch);



$split_arr = explode(",",$downloads_response);
$file_array_str="";
foreach($split_arr as $response_str)
{
	$file_count=0;
	$field_value = str_replace("\"","",$response_str);
	$field_value = str_replace("[","",$field_value);
	$field_value = str_replace("]","",$field_value);
	$field_value = str_replace(" ","",$field_value);

	$file_url = $field_value;

	$sql = "SELECT * FROM tbl_file_download WHERE file_name = '".$file_url."'";
	try 
	{$ps = $conn->prepare($sql);	
	$params=array();
	$ps->execute($params);
	} catch(PDOException $e) {$html.="->db_fail";}
	
	foreach($ps as $row)
	{
		$file_count++;
	}
	$file_array_str.=$field_value.",";
	
	$time_code = Date("dmyHis");
	if($file_count==0)
	{				
		file_put_contents("downloads/".$site_url_name."-".$time_code.".json", file_get_contents($file_url));
		//include "email/email_new_file.php";
		$html.="<a href='$file_url'>$file_url</a>";
		$html.="<br/><form action='index.php' method ='POST' target='_blank'><input type='submit' id='reset'/>
		<input type='hidden' name='file_url' value='$file_url'/>
		<input type='hidden' name='site_url_name' value='$site_url_name'/>
		</form>";
	
		$sql = "INSERT INTO tbl_file_download
		(
		file_name,
		site_url,
		download_time,
		site_status
		) VALUES (
		:file_name,
		:site_url,
		:download_time,
		:site_status
		)";
		try 
		{$ps = $conn->prepare($sql);	
		$params=array(
			'file_name'=>$file_url,
			'site_url'=>$site_url_name,
			'download_time'=>$download_time,
			'site_status'=>"DOWNLOAD"
		);
		$ps->execute($params);
		} catch(PDOException $e) {$html.="->db_fail";}
		break;
	}

}
}
echo $html;
//$file_array_str=substr($file_array_str, 0, -1);



/*

$files = explode(",",$file_array_str);
$zipname = 'file.zip';
$zip = new ZipArchive;
$zip->open($zipname, ZipArchive::CREATE);
foreach ($files as $file) 
{
  $zip->addFile($file);
  echo "DONE";
}
$zip->close();
*/
/*
header('Content-Type: application/zip');
header('Content-disposition: attachment; filename='.$zipname);
header('Content-Length: ' . filesize($zipname));
readfile($zipname);
*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="Application/format/format.css" type="text/css" />

<script src="Application/file_1.js"></script>
<script src="Application/file_2.js"></script>
<script type="text/javascript" src="Application/comms/server_to_client.js"></script>
<script type="text/javascript" src="Application/comms/get_message.js"></script>
<script type="text/javascript" src="Application/menu_navigation.js"></script>	
	
<script src="Application/pdf/src/shared/util.js"></script>
<script src="Application/pdf/src/display/api.js"></script>
<script src="Application/pdf/src/display/canvas.js"></script>


<script src="Application/version.js"></script>

 <script >

function loadStart()
{
		document.getElementById("reset").click();
}
<!--https://l6pe7j005p38b5wst3r3zcr2vtvh8ud2:@api.80legs.com/v2/urllists/name_of_url_list -H "Content-Type: application/octet-stream" --data-binary "[\"http://www.example.com/\", \"http://www.sample.com/\", \"http://www.test.com/\"]" -i -->
<!--https://l6pe7j005p38b5wst3r3zcr2vtvh8ud2:@api.80legs.com/v2/crawls/ff-->

</script>

</head>

<body class='body' onload='loadStart()'>
	<form method = "POST" action="index.php">
		<input type='text' name='str_url'/>
		<input type='submit' value='Add URL'/>
	</form>

		<form method = "POST" action="index.php">
		<input type='submit' id='submit' value='Add URL'/>
	</form>
	
	
</body>

</html>