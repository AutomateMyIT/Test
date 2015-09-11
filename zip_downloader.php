<?php session_save_path('sessions'); if(!isset($_SESSION)){session_start();}
include "config/string_variables.php";


	$file_url=$_POST['file_url'];
	$site_url_name=$_POST['site_url_name'];
	$download_time=date("Y-m-d H:i:s");
	
	header('Content-Type: application/octet-stream');
	header("Content-Transfer-Encoding: Binary"); 
	header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
	readfile($file_url);
		
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
		
return;
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
	//alert("D");
	//	document.getElementById("submit").click();
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