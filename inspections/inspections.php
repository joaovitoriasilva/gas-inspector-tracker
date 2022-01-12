<?php
    if(!isset($_SESSION)){
		session_start();
	}
		
    require_once $_SERVER['DOCUMENT_ROOT']."/inc/Funcoes-sql.php"; 
    
    $page="inspections";

    if(!isLogged()){
        header("Location: ../login.php");
    }

    if(isset($_GET["pageNumber"])){
        $pageNumber = $_GET["pageNumber"];
    }else{
        $pageNumber = 1;
    }

    $inspections = getInspectionsPagination($pageNumber);
    $numInspections = numInspections();
    $total_pages = ceil($numInspections / 25);
?>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Top.php" ?>

<!-- Page content -->
<div class="w3-content w3-padding-16" style="max-width:2000px;margin-top:46px;margin-left:16px;margin-right:16px">
    <!-- Header client section -->
    <div class="w3-content" style="max-width:600px">
        <h2>Inspeções</h2>
    </div>
    
    <!-- List clients Section -->
    <div class="w3-content" style="max-width:600px">
        <!-- Error banners -->
        <?php if($inspections == -1 || $numInspections == -1 || $inspections == -2){ ?>
            <div class="w3-panel w3-red w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3><i class="fas fa-hand-paper"></i> Erro!</h3>
                <p>Não foi possível listar inspeções.</p>
            </div>
        <?php }else{ ?>
            <!-- Info banners -->
            <?php if($inspections == -3){ ?>
                <div class="w3-panel w3-yellow w3-display-container">
                    <h3><i class="fas fa-exclamation-triangle"></i> Info!</h3>
                    <p>Não existem inspeções inseridas.</p>
                </div>
            <?php }else{ ?>
                <p>Existe um total de <?php echo ($numClients); ?> inspeções (25 por página):</p>
                <ul class="w3-ul w3-border-top w3-border-bottom w3-hoverable">
                    <?php foreach ($inspections as $inspection) { ?>
                        <?php $client = getClientFromId($inspection["client_id"]);
                            foreach ($client as $clientValue){
                                $clientName = $clientValue["name"];
                                $clientImg = $clientValue["photo_path"];
                            }
                        ?>
                        <li class="w3-bar w3-button" onclick="window.location.href = '../inspections/edit_inspection.php?inspectionID=<?php echo ($inspection["id"]); ?>';">
                            <img src=<?php if(is_null($clientImg)){ echo ("../img/avatar/Male_Avatar_4.png"); }else{ echo ($clientImg); }?> class="w3-bar-item w3-circle" style="width:85px">
                            <div class="w3-bar-item">
                                <?php if(!is_null($clientName)){ ?>
                                    <span class="w3-large w3-left"><?php echo ($clientName); ?></span><br>
                                <?php }else{ ?>
                                    <span class="w3-large w3-left">Nome não introduzido</span><br>
                                <?php } ?>
                                <span class="w3-left"><strong>Última inspeção: </strong><?php echo ($inspection["data_inspecao"]); ?></span><br>
                                <span class="w3-left"><strong>Limite próxima inspeção: </strong><?php echo ($inspection["data_limite_prox_inspecao"]); ?></span><br>
                                <?php if(!is_null($inspection["descricao"])){ ?>
                                    <span class="w3-left"><strong>Descrição: </strong><?php echo ($inspection["descricao"]); ?></span><br>
                                <?php }else{ ?>
                                    <span class="w3-left"><strong>Descrição: </strong> Não especificado</span><br>
                                <?php } ?>
                                <?php if(!is_null($inspection["notas"])){ ?>
                                    <span class="w3-left"><strong>Notas: </strong><?php echo ($inspection["notas"]); ?></span><br>
                                <?php }else{ ?>
                                    <span class="w3-left"><strong>Notas: </strong> Não especificado</span><br>
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
