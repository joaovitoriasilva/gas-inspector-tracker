<?php
    if(!isset($_SESSION)){
		session_start();
	}
		
    require_once $_SERVER['DOCUMENT_ROOT']."/inc/Funcoes-sql.php"; 
    
    $page="clients";

    if(!isLogged()){
        header("Location: ../login.php");
    }

    if(isset($_GET["clientID"])){
        $clientID = $_GET["clientID"];
    }else{
        $clientID = 0;
    }

    if(isset($_POST["clientSearch"])){
        if(!empty($_POST["clientNif"])){
            $clientID = getClientIDFromNif($_POST["clientNif"]);
        }else{
            if(!empty($_POST["clientContact"])){
                $clientID = getClientIDFromContact($_POST["clientContact"]);
            }else{
                if(!empty($_POST["clientName"])){
                    $clientID = getClientIDFromName($_POST["clientName"]);
                }
            }
        }
        if($clientID > 0){
            header("Location: ../clients/client.php?clientID=".$clientID);
        }
    }

    if(isset($_GET["pageNumber"])){
        $pageNumber = $_GET["pageNumber"];
    }else{
        $pageNumber = 1;
    }

    $clients = getClientsPagination($pageNumber);
    $numClients = numClients();
    $total_pages = ceil($numClients / 25);
?>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Top.php" ?>

<!-- Page content -->
<div class="w3-content w3-padding-16" style="max-width:2000px;margin-top:46px;margin-left:16px;margin-right:16px">
    <!-- Header client section -->
    <div class="w3-content" style="max-width:600px">
        <h2>Clientes</h2>
        <!-- Error banners -->
        <?php if($clientID == -1 || $clientID == -2){ ?>
            <div class="w3-panel w3-red w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3><i class="fas fa-hand-paper"></i> Erro</h3>
                <p>Não foi possível procurar cliente.</p>
            </div>
        <?php } ?>
        <?php if($_GET["editError"] == -1 || $_GET["editError"] == -2 || $_GET["editError"] == -3){ ?>
            <div class="w3-panel w3-red w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3><i class="fas fa-hand-paper"></i> Erro $editError=-1/-2/-3</h3>
                <p>Não foi possível listar cliente após edição.</p>
            </div>
        <?php } ?>
        <!-- Info banners -->
        <?php if($clientID == -3){ ?>
            <div class="w3-panel w3-yellow w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3><i class="fas fa-exclamation-triangle"></i> Info!</h3>
                <p>Cliente não existe.</p>
            </div>
        <?php } ?>
        <?php if(isset($_GET["clientDeleted"])){
            if($_GET["clientDeleted"] == 0 && $_GET["photoDeleted"] == 1){ ?>
                <div class="w3-panel w3-yellow w3-display-container">
                    <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                    <h3>Info</h3>
                    <p>Cliente eliminado. Não foi possível eliminar respetiva foto no filesystem.</p>
                </div>
            <?php } 
        }?>
        <!-- Success banners -->
        <?php if(isset($_GET["addClientAction"]) && $_GET["addClientAction"] == 0){ ?>
            <div class="w3-panel w3-green w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3>Info</h3>
                <p>Cliente adicionado.</p>
            </div>
        <?php } ?>
        <?php if(isset($_GET["clientDeleted"])){
            if(($_GET["clientDeleted"] == 0 && $_GET["photoDeleted"] == 0) || ($_GET["clientDeleted"] == 0 && $_GET["photoDeleted"] == 2)){ ?>
                <div class="w3-panel w3-green w3-display-container">
                    <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                    <h3>Info</h3>
                    <p>Cliente eliminado.</p>
                </div>
            <?php }
        }?>
    </div>
    
    <!-- Add client section -->
    <div class="w3-content" style="max-width:600px">
        <p>Adicionar cliente:</p>
        <button onclick="window.location.href = '../clients/add_client.php';" type="button" class="w3-button w3-block w3-blue w3-section w3-padding">Novo cliente</button>
    </div>

    <!-- Search clients section -->
    <div class="w3-content" style="max-width:600px">
        <p>Procurar cliente por nome, contacto ou NIF:</p>
        <form action="../clients/clients.php" method="post">
            <label for="clientName"><b>Nome</b></label>
            <input class="w3-input w3-border w3-margin-bottom" type="text" name="clientName" placeholder="Nome">
            <label for="clientContact"><b>Contacto</b></label>
            <input class="w3-input w3-border w3-margin-bottom" type="number" name="clientContact" placeholder="Contacto">
            <label for="clientNif"><b>NIF</b></label>
            <input class="w3-input w3-border w3-margin-bottom" type="number" name="clientNif" placeholder="NIF">
            <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit" name="clientSearch">Procurar</button>
        </form>
    </div>
    
    <!-- List clients Section -->
    <div class="w3-content" style="max-width:600px">
        <!-- Error banners -->
        <?php if($clients == -1 || $numClients == -1 || $clients == -2){ ?>
            <div class="w3-panel w3-red w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3><i class="fas fa-hand-paper"></i> Erro!</h3>
                <p>Não foi possível listar clientes.</p>
            </div>
        <?php }else{ ?>
            <!-- Info banners -->
            <?php if($clients == -3){ ?>
                <div class="w3-panel w3-yellow w3-display-container">
                    <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                    <h3><i class="fas fa-exclamation-triangle"></i> Info!</h3>
                    <p>Não existem clientes inseridos.</p>
                </div>
            <?php }else{ ?>
                <p>Existe um total de <?php echo ($numClients); ?> clientes (25 por página):</p>
                <ul class="w3-ul w3-border-top w3-border-bottom w3-hoverable">
                    <?php foreach ($clients as $client) { ?>
                        <li class="w3-bar w3-button" onclick="window.location.href = '../clients/client.php?clientID=<?php echo ($client["id"]); ?>';">
                            <img src=<?php if(is_null($client["photo_path"])){ echo ("../img/avatar/Male_Avatar_4.png"); }else{ echo ($client["photo_path"]); }?> class="w3-bar-item w3-circle" style="width:85px">
                            <div class="w3-bar-item">
                                <?php if(!is_null($client["name"])){ ?>
                                    <span class="w3-large w3-left"><?php echo ($client["name"]); ?></span><br>
                                <?php }else{ ?>
                                    <span class="w3-large w3-left">Nome não introduzido</span><br>
                                <?php } ?>
                                <?php if(!is_null($client["nif"])){ ?>
                                    <span class="w3-left">NIF: <?php echo ($client["nif"]); ?></span>
                                <?php }else{ ?>
                                    <?php if(!is_null($client["phone"])){ ?>
                                        <span class="w3-left">Contacto: <?php echo ($client["phone"]); ?></span>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
                <div class="w3-container w3-content w3-center w3-padding-16">
                    <div class="w3-bar">
                        <a href="?pageNumber=1" class="w3-button">«</a>
                        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                            <a href="?pageNumber=<?php echo ($i);?>" class="w3-button <?php if($i == $pageNumber){ echo("w3-dark-grey");} ?>"><?php echo ($i);?></a>
                        <?php } ?>
                        <a href="?pageNumber=<?php echo ($total_pages);?>" class="w3-button">»</a>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Bottom.php" ?>