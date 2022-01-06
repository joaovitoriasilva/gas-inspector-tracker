<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/inc/Funcoes-sql.php"; 
	if(!isset($_SESSION)){
		session_start();
	}
    clearUserRelatedInfoSession();
    header("location: ../index.php");
?>