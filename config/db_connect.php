<?php session_save_path("../sessions"); if(!isset($_SESSION)){session_start(); }

$dsn = "mysql:host=$db_host;dbname=$db_name";
$login=$db_user;
$password=$db_password;
$conn = new PDO($dsn, $login, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
