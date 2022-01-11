<?php
	#if(!isLogged()) {
		#if($page != "index" && $page != "login"){
			#header("Location: ../login.php");
		#}
	#}
?>

<!DOCTYPE html>
<html>
    <head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gás Tracker</title>
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <script src="https://kit.fontawesome.com/8c44ee63d9.js"></script>
		<link rel="shortcut icon" href="../img/logo/logo.png">
		<link rel="apple-touch-icon" href="../img/logo/logo.png">
	</head>
	<body>
        <script>
            // Used to toggle the menu on small screens when clicking on the menu button
            function showNav() {
                var x = document.getElementById("navSmallScreens");
                if (x.className.indexOf("w3-show") == -1) {
                    x.className += " w3-show";
                } else { 
                    x.className = x.className.replace(" w3-show", "");
                }
            }
        </script>

        <!-- Navbar -->
        <div class="w3-top">
            <div class="w3-bar w3-dark-grey w3-card">
                <?php if(isLogged()) {?>
                    <a class="w3-bar-item w3-button w3-padding-large w3-hide-medium w3-hide-large w3-right" href="javascript:void(0)" onclick="showNav()" title="Toggle Navigation Menu"><i class="fas fa-bars"></i> Menu</a>
                <?php } ?>
                <a href="../index.php" class="w3-bar-item w3-button w3-padding-large"><i class="fas fa-home"></i> Gás<span>Tracker</a>
                <?php if(!isLogged()) {?>
                    <a href="../login.php" class="w3-bar-item w3-button w3-padding-large w3-right"><i class="fas fa-sign-in-alt"></i> Entrar</a>
                <?php }else{ ?>
                    <a href="../clients/clients.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small"><i class="fas fa-users"></i> Clientes</a>
                    <a href="../inspections.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small"><i class="fas fa-hard-hat"></i> Inspeções</a>
                    <!--<a href="../nutrition.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small"><i class="fas fa-euro-sign"></i> Vendas</a>
                    <a href="../report.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small"><i class="fas fa-user-clock"></i> Report horas</a>-->
                    <?php if($_SESSION["type"] == 2){ ?>
                        <a href="../users/users.php" class="w3-bar-item w3-button w3-padding-large w3-hide-small"><i class="fas fa-users-cog"></i> Utilizadores</a>
                    <?php } ?>
                    <a href="../logout.php" class="w3-bar-item w3-button w3-padding-large w3-right w3-hide-small"><i class="fas fa-sign-out-alt"></i> Sair</a>
                <?php } ?>
            </div>
        </div>

        <!-- Navbar on small screens (remove the onclick attribute if you want the navbar to always show on top of the content when clicking on the links) -->
        <div id="navSmallScreens" class="w3-bar-block w3-dark-grey w3-hide w3-hide-large w3-hide-medium w3-top" style="margin-top:46px">
            <?php if(isLogged()) {?>
                <a href="../clients/clients.php" class="w3-bar-item w3-button w3-padding-large" onclick="showNav()"><i class="fas fa-users"></i> Clientes</a>
                <a href="../evaluations.php" class="w3-bar-item w3-button w3-padding-large" onclick="showNav()"><i class="fas fa-hard-hat"></i> Inspeções</a>
                <!--<a href="../nutrition.php" class="w3-bar-item w3-button w3-padding-large"><i class="fas fa-euro-sign"></i> Vendas</a>
                <a href="../report.php" class="w3-bar-item w3-button w3-padding-large"><i class="fas fa-user-clock"></i> Report horas</a>-->
                <?php if($_SESSION["type"] == 2){ ?>
                    <a href="../users/users.php" class="w3-bar-item w3-button w3-padding-large" onclick="showNav()"><i class="fas fa-users-cog"></i> Utilizadores</a>
                <?php } ?>
                <a href="../logout.php" class="w3-bar-item w3-button w3-padding-large" onclick="showNav()"><i class="fas fa-sign-out-alt"></i> Sair</a>
            <?php } ?>
        </div>
