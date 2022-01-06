<?php
  	$maquina= "192.168.2.80";
	$utilizador="gas-inspector-tracker";
	$senha="123Testes@";
	$bd="gas-inspector-tracker";
	$mydb = new mysqli($maquina, $utilizador, $senha, $bd);
	if(mysqli_connect_errno()){
		echo mysqli_connect_error();
		exit();
	}   
	$mydb->set_charset("utf8");
	# CREATE USER 'gas-inspector-tracker'@'localhost' IDENTIFIED BY '123Testes@';
	# GRANT ALL PRIVILEGES ON gas-inspector-tracker.* TO 'gas-inspector-tracker'@'localhost';
?>
