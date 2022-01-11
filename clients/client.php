<?php
    if(!isset($_SESSION)){
		session_start();
	}
		
    require_once $_SERVER['DOCUMENT_ROOT']."/inc/Funcoes-sql.php"; 
    
    $page="client";

    if(!isLogged()){
        header("Location: ../login.php");
    }

    function getClientInfo(){
        global $clientID, $clientName, $clientNIF, $clientPhone, $clientEmail, $clientAddress, $clientNotes, $clientImg, $clientLoanCount, $clientLoans, $clientRepairCount, $clientConstructionCount;

        $client = getClientFromId($clientID);

        if($client == -1){
            $client = NULL;
        }else{
            if($client != -2 && $client != -3){
                foreach ($client as $clientValue){
                    $clientName = $clientValue["name"];
                    $clientNIF = $clientValue["nif"];
                    $clientPhone = $clientValue["phone"];
                    $clientEmail = $clientValue["email"];
                    $clientAddress = $clientValue["address"];
                    $clientNotes = $clientValue["notes"];
                    $clientImg = $clientValue["photo_path"];
                }
            }
        }
    }

    if(isset($_GET["clientID"])){
        $clientID = $_GET["clientID"];
    }else{
        header("Location: ../clients/clients.php");
    }

    getClientInfo();

    if(isset($_GET["pageNumber"])){
        $pageNumber = $_GET["pageNumber"];
    }else{
        $pageNumber = 1;
    }
    $clientInspections = getInspectionsForClientCreatedByUser($_SESSION["id"], $_GET["clientID"], $pageNumber);
    $numInspectionsClient = getNumInspectionsForClientCreatedByUser($_SESSION["id"], $_GET["clientID"]);
    $total_pages = ceil($numInspectionsClient / 25);

    /* Delete action */
    if(isset($_GET["deleteClient"]) && $_GET["deleteClient"] == 1){
        if($clientLoanCount == 0 && $clientRepairCount == 0 && $clientConstructionCount == 0){
            $photo_path = getClientPhotoAuxPathFromID($_GET["clientID"]);
            $deleteAction = deleteClient($_GET["clientID"]);
            if($deleteAction == 0){
                if(!is_null($photo_path)){
                    if(unlink($photo_path)){
                        header("Location: ../clients/clients.php?clientDeleted=0&photoDeleted=0");
                    }else{
                        header("Location: ../clients/clients.php?clientDeleted=0&photoDeleted=1");
                    }
                }else{
                    header("Location: ../clients/clients.php?clientDeleted=0&photoDeleted=2");
                }
            }
        }else{
            $deleteAction = -3;
        }
    }

    if(is_null($clientImg)){
        $photoNumber = rand(1,4);
        /*$genderRandom = rand(1,2);
        if(($_SESSION["gender"] == 1) || ($_SESSION["gender"] == 3 && $genderRandom == 1)) {
            $userImg = "../img/avatar/Male_Avatar_$photoNumber.png";
        }else{
            if(($_SESSION["gender"] == 2) || ($_SESSION["gender"] == 3 && $genderRandom == 2)) {*/
                $clientImg = "../img/avatar/Female_Avatar_$photoNumber.png";
           /* }
        }*/
    }
?>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Top.php" ?>

<!-- Page content -->
<div class="w3-container w3-content" style="max-width:1400px;margin-top:80px">
    <div class="w3-content" style="max-width:600px">
        <!-- Error banners -->
        <?php if($deleteAction == -1 || $deleteAction == -2){ ?>
            <div class="w3-panel w3-red w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3><i class="fas fa-hand-paper"></i> Erro</h3>
                <p>Não foi possível eliminar cliente (-1/-2).</p>
            </div>
        <?php } ?>
        <?php if($deleteAction == -3){ ?>
            <div class="w3-panel w3-red w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3><i class="fas fa-hand-paper"></i> Erro</h3>
                <p>Existem empréstimos, reparações e/ou construções associadas a este cliente. Não é possível eliminar (-3).</p>
            </div>
        <?php } ?>
        <!-- Success banners -->
        <?php if(isset($_GET["editAction"])){
            if($_GET["editAction"] == 0){ ?>
                <div class="w3-panel w3-green w3-display-container">
                    <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                    <h3>Info</h3>
                    <p>Dados de cliente alterados.</p>
                </div>
            <?php } 
        }?>
        <?php if(isset($_GET["deletePhotoAction"])){
            if($_GET["deletePhotoAction"] == 0){ ?>
                <div class="w3-panel w3-green w3-display-container">
                    <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                    <h3>Info</h3>
                    <p>Foto de cliente eliminada.</p>
                </div>
            <?php } 
        }?>

        
        <div class="w3-content" style="max-width:600px">
            <div class="w3-center">
                <img src="<?php echo $clientImg; ?>" class="w3-margin w3-circle" alt="Client photo" style="width:150px">
                <h1><?php echo $clientName; ?></h1>
            </div>
            <div>
                <p><strong>NIF: </strong><?php if(!is_null($clientNIF)){ echo ($clientNIF); }else{ echo ("Não especificado"); } ?></p>
                <p><strong>Telefone: </strong><?php if(!is_null($clientPhone)){ echo ($clientPhone); }else{ echo ("Não especificado"); } ?></p>
                <p><strong>E-mail: </strong><?php if(!is_null($clientEmail)){ echo ($clientEmail); }else{ echo ("Não especificado"); } ?></p>
                <p><strong>Morada: </strong><?php if(!is_null($clientAddress)){ echo ($clientAddress); }else{ echo ("Não especificado"); } ?></p>
                <p><strong>Notas: </strong><?php if(!is_null($clientNotes)){ echo ($clientNotes); }else{ echo ("Não especificado"); } ?></p>
                <button onclick="window.location.href = '../clients/edit_client.php?clientID=<?php echo ($clientID); ?>';" type="button" class="w3-button w3-block w3-blue w3-section w3-padding">Editar cliente</button>
                <button onclick="window.location.href = '../clients/client.php?clientID=<?php echo ($clientID); ?>&deleteClient=1';" type="button" class="w3-button w3-block w3-red w3-section w3-padding">Eliminar cliente</button>
            </div>
        </div>

        <!-- Client inspections section -->
        <div class="w3-content" style="max-width:600px">
            <h4>Inspeções cliente:</h4>
            
            <!-- Error banners -->
            <?php if($clientInspections == -1 || $numInspectionsClient == -1 || $clientInspections == -2){ ?>
                <div class="w3-panel w3-red w3-display-container">
                    <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                    <h3><i class="fas fa-hand-paper"></i> Erro!</h3>
                    <p>Não foi possível listar inspeções do cliente.</p>
                </div>
            <?php }else{ ?>
                <!-- Info banners -->
                <?php if($clientInspections == -3){ ?>
                    <div class="w3-panel w3-yellow w3-display-container">
                        <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                        <h3><i class="fas fa-exclamation-triangle"></i> Info!</h3>
                        <p>Não existem inspeções inseridas.</p>
                    </div>
                <?php }else{ ?>
                    <p>Existe um total de <?php echo ($numInspectionsClient); ?> inspeções (25 por página):</p>
                    <ul class="w3-ul w3-border-top w3-border-bottom w3-hoverable">
                        <?php foreach ($clientInspections as $inspection) { ?>
                            
                            <li class="w3-bar w3-button" onclick="window.location.href = '../inspections/inspection.php?inspectionID=<?php echo ($inspection["id"]); ?>';">
                                <img src=<?php if(is_null($clientImg)){ echo ("../img/avatar/Male_Avatar_4.png"); }else{ echo ($clientImg); }?> class="w3-bar-item w3-circle" style="width:85px">
                                <div class="w3-bar-item">
                                    <?php if(!is_null($clientName)){ ?>
                                        <span class="w3-large w3-left"><?php echo ($clientName); ?></span><br>
                                    <?php }else{ ?>
                                        <span class="w3-large w3-left">Nome não introduzido</span><br>
                                    <?php } ?>
                                    <span class="w3-left">Data última inspeção: <?php echo ($inspection["data_inspecao"]); ?></span><br>
                                    <span class="w3-left">Data limite próxima inspeção: <?php echo ($inspection["data_prox_inspecao"]); ?></span>
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

        <button onclick="window.history.back();" type="button" class="w3-button w3-block w3-blue w3-section w3-padding w3-hide-meddium w3-hide-large">Voltar</button>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Bottom.php" ?>