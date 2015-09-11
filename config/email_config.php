<?php  session_save_path('../sessions'); if(!isset($_SESSION)){session_start(); }
$_SESSION['session_mail_server'] = "mail.automatemyit.co.uk";
$_SESSION['session_mail_port'] = "25";
$_SESSION['session_mail_username'] = "info@automatemyit.co.uk";
$_SESSION['session_mail_password'] = "Admin123";
$_SESSION['session_application_owner'] = "Tim Simms";
$_SESSION['session_owner_email'] = "tim-simms@hotmail.com";

$_SESSION['session_info_name'] = "Info";
$_SESSION['session_info_email'] = "info@automatemyit.co.uk";
$_SESSION['session_company_website'] = "http://www.automatemyit.co.uk/Bookings/frm_customer_services.php";
$_SESSION['session_web_address_admin'] = "http://www.automatemyit.co.uk/Bookings/frm_login.php";
?>
