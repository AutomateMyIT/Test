<?php

$_SESSION['live']=false;
$_SESSION['active']=true;

$live=$_SESSION['live'];

if($live==false)
{	
	//Dev Database
	$db_name="80legs_tracker";
	$db_host="127.0.0.1";
	$db_user="root";
	$db_password="password";
}
else
{
	//Live Database
	$db_name="80legs_tracker";
	$db_host="127.0.0.1";
	$db_user="root";
	$db_password="password";
}

//DB Connection
//No Edit
$dsn = "mysql:host=$db_host";
$login=$db_user;
$password=$db_password;

try 
{
    $conn = new PDO($dsn, $login, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} 
catch(PDOException $e) 
{
	echo "DB Access Fail";
	exit;
}

$sql="CREATE DATABASE IF NOT EXISTS $db_name";
try 
{
	$ps = $conn->prepare($sql);
	$ps->execute();

} 
catch(PDOException $e) 
{
	echo "DB Install Fail";
	exit;
}

$dsn = "mysql:host=$db_host;dbname=$db_name";
$login=$db_user;
$password=$db_password;
$conn = new PDO($dsn, $login, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


?>
