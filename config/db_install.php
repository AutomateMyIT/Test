<?php session_save_path('../sessions'); if(!isset($_SESSION)){session_start(); }
Include "../config/string_variables.php";

$sql = "CREATE TABLE tbl_file_download
(
file_download_id int NOT NULL AUTO_INCREMENT,
file_name varchar(250),
site_url varchar(64),
download_time DATETIME,
site_status varchar(25),
PRIMARY KEY(file_download_id)
)";
try 
{$ps = $conn->prepare($sql);	$ps->execute();} catch(PDOException $e) {}


$sql = "ALTER TABLE tbl_file_download ADD expiry_time DATETIME";
try 
{$ps = $conn->prepare($sql);	$ps->execute();} catch(PDOException $e) {}



?>