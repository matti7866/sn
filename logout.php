<?php
session_start();
include 'connection.php';

if(isset($_SESSION['user_id']))
{
	session_unset();
	session_destroy();
	header('location:login.php');
}



?>