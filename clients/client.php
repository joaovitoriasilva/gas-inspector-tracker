<?php
    if(!isset($_SESSION)){
		session_start();
	}
		
    require_once $_SERVER['DOCUMENT_ROOT']."/inc/Funcoes-sql.php"; 
    
    $page="client";

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

    /* Delete action */
    if(isset($_GET["deleteClient"]) && $_GET["deleteClient"] == 1){
        if($clientLoanCount == 0 && $clientRepairCount == 0 && $clientConstructionCount == 0){
            $photo_path = getClientPhotoFromID($_GET["clientID"]);
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
<div class="w3-content w3-padding-16" style="max-width:2000px;margin-top:46px;margin-left:16px;margin-right:16px">
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

    <div class="w3-row">
        <div class="w3-half w3-container" style="height:auto">
            <div class="w3-center">
                <img src="<?php echo $clientImg; ?>" class="w3-margin w3-circle" alt="Client photo" style="width:150px">
                <h1><?php echo $clientName; ?></h1>
            </div>
            <div class="w3-center">
                <p><strong>NIF: </strong><?php if(!is_null($clientNIF)){ echo ($clientNIF); }else{ echo ("Não especificado"); } ?></p>
                <p><strong>Telefone: </strong><?php if(!is_null($clientPhone)){ echo ($clientPhone); }else{ echo ("Não especificado"); } ?></p>
                <p><strong>E-mail: </strong><?php if(!is_null($clientEmail)){ echo ($clientEmail); }else{ echo ("Não especificado"); } ?></p>
                <p><strong>Morada: </strong><?php if(!is_null($clientAddress)){ echo ($clientAddress); }else{ echo ("Não especificado"); } ?></p>
                <p><strong>Notas: </strong><?php if(!is_null($clientNotes)){ echo ($clientNotes); }else{ echo ("Não especificado"); } ?></p>
                <button onclick="window.location.href = '../clients/edit_client.php?clientID=<?php echo ($clientID); ?>';" type="button" class="w3-button w3-block w3-blue w3-section w3-padding">Editar cliente</button>
                <button onclick="window.location.href = '../clients/client.php?clientID=<?php echo ($clientID); ?>&deleteClient=1';" type="button" class="w3-button w3-block w3-red w3-section w3-padding">Eliminar cliente</button>
            </div>
        </div>
    </div>
    <button onclick="window.history.back();" type="button" class="w3-button w3-block w3-blue w3-section w3-padding w3-hide-meddium w3-hide-large">Voltar</button>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Bottom.php" ?>