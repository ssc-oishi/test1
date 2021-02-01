<?php
//セッション有無確認用PHP
if (!isset($_SESSION)) {
	session_start();
}
if (!isset($_SESSION["uID"])) {
	header('Location:SessionOut.php');
}
// print_r($_SESSION);
