<?php
    if(!isset($_SESSION)){
        session_start();
    }
        
    require_once $_SERVER['DOCUMENT_ROOT']."/inc/Funcoes-sql.php"; 

	$page="index";

    if(!isLogged()){
        header("Location: ../login.php");
    }

    if(isset($_GET["pageNumberWeek"])){
        $pageNumberWeek = $_GET["pageNumberWeek"];
    }else{
        $pageNumberWeek = 1;
    }
    $nextWeekInspections = getInspectionNextWeekForUser($_SESSION["id"],$pageNumberWeek);
    $numInspectionsUserForNextWeek = getNumInspectionsUserForNextWeek($_SESSION["id"]);
    $total_pages_week = ceil($numInspectionsUserForNextWeek / 25);

    if(isset($_GET["pageNumberMonth"])){
        $pageNumberMonth = $_GET["pageNumberMonth"];
    }else{
        $pageNumberMonth = 1;
    }
    $nextMonthInspections = getInspectionNextMonthForUser($_SESSION["id"],$pageNumberMonth);
    $numInspectionsUserForNextMonth = getNumInspectionsUserForNextMonth($_SESSION["id"]);
    $total_pages_month = ceil($numInspectionsUserForNextMonth / 25);

?>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Top.php" ?>

<!-- Page Container -->
<div class="w3-container w3-content" style="max-width:1400px;margin-top:80px">
    <div class="w3-content" style="max-width:600px">
        <!-- Error banners -->
        <?php if($_GET["editclientaccess"] == -1){ ?>
            <div class="w3-panel w3-red w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3><i class="fas fa-hand-paper"></i> Erro</h3>
                <?php if($_GET["editclientaccess"] == -1){ ?>
                    <p>Não tem privilégios para editar cliente (-1).</p>
                <?php } ?>
            </div>
        <?php } ?>
        <!-- Index client section -->
        <h2>Olá <?php echo $_SESSION["name"]; ?></h2>

        <!-- Next week inspections section -->
        <div class="w3-content" style="max-width:600px">
            <h4>Inspeções próxima semana (7 dias):</h4>
            
            <!-- Error banners -->
            <?php if($nextWeekInspections == -1 || $numInspectionsUserForNextWeek == -1 || $nextWeekInspections == -2){ ?>
                <div class="w3-panel w3-red w3-display-container">
                    <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                    <h3><i class="fas fa-hand-paper"></i> Erro!</h3>
                    <p>Não foi possível listar inspeções da próxima semana.</p>
                </div>
            <?php }else{ ?>
                <!-- Info banners -->
                <?php if($nextWeekInspections == -3){ ?>
                    <div class="w3-panel w3-yellow w3-display-container">
                        <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                        <h3><i class="fas fa-exclamation-triangle"></i> Info!</h3>
                        <p>Não existem inspeções inseridas.</p>
                    </div>
                <?php }else{ ?>
                    <p>Existe um total de <?php echo ($numInspectionsUserForNextWeek); ?> inspeções (25 por página):</p>
                    <ul class="w3-ul w3-border-top w3-border-bottom w3-hoverable">
                        <?php foreach ($nextWeekInspections as $inspection) { ?>
                            <?php $client = getClientFromId($inspection["client_id"]);
                                foreach ($client as $clientValue){
                                    $clientName = $clientValue["name"];
                                    $clientNIF = $clientValue["nif"];
                                    $clientPhone = $clientValue["phone"];
                                    $clientEmail = $clientValue["email"];
                                    $clientAddress = $clientValue["address"];
                                    $clientNotes = $clientValue["notes"];
                                    $clientImg = $clientValue["photo_path"];
                                }
                            ?>
                            <li class="w3-bar w3-button" onclick="window.location.href = '../clients/client.php?clientID=<?php echo ($inspection["client_id"]); ?>';">
                                <img src=<?php if(is_null($clientImg)){ echo ("../img/avatar/Male_Avatar_4.png"); }else{ echo ($clientImg); }?> class="w3-bar-item w3-circle" style="width:85px">
                                <div class="w3-bar-item">
                                    <?php if(!is_null($clientName)){ ?>
                                        <span class="w3-large w3-left"><?php echo ($clientName); ?></span><br>
                                    <?php }else{ ?>
                                        <span class="w3-large w3-left">Nome não introduzido</span><br>
                                    <?php } ?>
                                    <span class="w3-left">Última inspeção: <?php echo ($inspection["data_inspecao"]); ?></span><br>
                                    <span class="w3-left">Próxima inspeção: <?php echo ($inspection["data_prox_inspecao"]); ?></span>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                    <div class="w3-container w3-content w3-center w3-padding-16">
                        <div class="w3-bar">
                            <a href="?pageNumberWeek=1" class="w3-button">«</a>
                            <?php for ($i = 1; $i <= $total_pages_week; $i++) { ?>
                                <a href="?pageNumberWeek=<?php echo ($i);?>" class="w3-button <?php if($i == $pageNumberWeek){ echo("w3-dark-grey");} ?>"><?php echo ($i);?></a>
                            <?php } ?>
                            <a href="?pageNumberWeek=<?php echo ($total_pages_week);?>" class="w3-button">»</a>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>

        <!-- Next month inspections section -->
        <div class="w3-content" style="max-width:600px">
            <h4>Inspeções próximo mês (31 dias) excluindo próxima semana:</h4>
            
            <!-- Error banners -->
            <?php if($nextMonthInspections == -1 || $numInspectionsUserForNextMonth == -1 || $nextMonthInspections == -2){ ?>
                <div class="w3-panel w3-red w3-display-container">
                    <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                    <h3><i class="fas fa-hand-paper"></i> Erro!</h3>
                    <p>Não foi possível listar inspeções do próximo mês.</p>
                </div>
            <?php }else{ ?>
                <!-- Info banners -->
                <?php if($nextMonthInspections == -3){ ?>
                    <div class="w3-panel w3-yellow w3-display-container">
                        <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                        <h3><i class="fas fa-exclamation-triangle"></i> Info!</h3>
                        <p>Não existem inspeções inseridas.</p>
                    </div>
                <?php }else{ ?>
                    <p>Existe um total de <?php echo ($numInspectionsUserForNextMonth); ?> inspeções (25 por página):</p>
                    <ul class="w3-ul w3-border-top w3-border-bottom w3-hoverable">
                        <?php foreach ($nextMonthInspections as $inspection) { ?>
                            <?php $client = getClientFromId($inspection["client_id"]);
                                foreach ($client as $clientValue){
                                    $clientName = $clientValue["name"];
                                    $clientNIF = $clientValue["nif"];
                                    $clientPhone = $clientValue["phone"];
                                    $clientEmail = $clientValue["email"];
                                    $clientAddress = $clientValue["address"];
                                    $clientNotes = $clientValue["notes"];
                                    $clientImg = $clientValue["photo_path"];
                                }
                            ?>
                            <li class="w3-bar w3-button" onclick="window.location.href = '../clients/client.php?clientID=<?php echo ($inspection["client_id"]); ?>';">
                                <img src=<?php if(is_null($clientImg)){ echo ("../img/avatar/Male_Avatar_4.png"); }else{ echo ($clientImg); }?> class="w3-bar-item w3-circle" style="width:85px">
                                <div class="w3-bar-item">
                                    <?php if(!is_null($clientName)){ ?>
                                        <span class="w3-large w3-left"><?php echo ($clientName); ?></span><br>
                                    <?php }else{ ?>
                                        <span class="w3-large w3-left">Nome não introduzido</span><br>
                                    <?php } ?>
                                    <span class="w3-left">Data última inspeção: <?php echo ($inspection["data_inspecao"]); ?></span><br>
                                    <span class="w3-left">Data próxima inspeção: <?php echo ($inspection["data_prox_inspecao"]); ?></span>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                    <div class="w3-container w3-content w3-center w3-padding-16">
                        <div class="w3-bar">
                            <a href="?pageNumberMonth=1" class="w3-button">«</a>
                            <?php for ($i = 1; $i <= $total_pages_month; $i++) { ?>
                                <a href="?pageNumberMonth=<?php echo ($i);?>" class="w3-button <?php if($i == $pageNumberMonth){ echo("w3-dark-grey");} ?>"><?php echo ($i);?></a>
                            <?php } ?>
                            <a href="?pageNumberMonth=<?php echo ($total_pages_month);?>" class="w3-button">»</a>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
<!-- End Page Container -->
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Bottom.php" ?>
