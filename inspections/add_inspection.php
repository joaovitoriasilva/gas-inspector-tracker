<?php
    if(!isset($_SESSION)){
		session_start();
	}
		
    require_once $_SERVER['DOCUMENT_ROOT']."/inc/Funcoes-sql.php"; 
    
    $page="add_inspection";

    if(!isLogged()){
        header("Location: ../login.php");
    }

    if(isset($_GET["clientID"])){
        $client = getClientFromId($_GET["clientID"]);
        if($client == -1){
            header("Location: ../index.php?add_inspection=-2");
        }else{
            if($client != -2 && $client != -3){
                foreach ($client as $clientValue){
                    $_POST["inspectionClientNameAdd"] = $clientValue["name"];
                }
            }else{
                header("Location: ../index.php?add_inspection=-3");
            }
        }
    }else{
        header("Location: ../index.php?add_inspection=-1");
    }

    if(isset($_POST["addInspection"])){
        if(!empty($_POST["inspectionDateAdd"])){
            if(empty($_POST["inspectionDescriptionAdd"])){
                $_POST["inspectionDescriptionAdd"] = NULL;
            }
            if(empty($_POST["inspectionNotesAdd"])){
                $_POST["inspectionNotesAdd"] = NULL;
            }
            $addInspectionAction = newInspection($_SESSION["id"],$_GET["clientID"],date('Y-m-d', strtotime($_POST["inspectionDateAdd"])),$_POST["inspectionDescriptionAdd"],$_POST["inspectionNotesAdd"]);
            if ($addInspectionAction == 0){
                header("Location: ../clients/client.php?clientID=".$_GET["clientID"]."&addInspectionAction=0");
            }
        }else{
            $addInspectionAction = -10;
        }
    }
?>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Top.php" ?>

<!-- Page content -->
<div class="w3-content w3-padding-16" style="max-width:2000px;margin-top:46px;margin-left:16px;margin-right:16px">
    <!-- Add user -->
    <div class="w3-content" style="max-width:600px">
        <h2>Adicionar inspeção</h2>
        <!-- Error banners -->
        <?php if($addInspectionAction == -10 || $addInspectionAction == -1 || $addInspectionAction == -2 ){ ?>
            <div class="w3-panel w3-red w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3><i class="fas fa-hand-paper"></i> Erro</h3>
                <?php if($addInspectionAction == -10){ ?>
                    <p>Data não especificada (-10).</p>
                <?php }else{ ?>
                    <?php if($addInspectionAction == -2){ ?>
                        <p>Não foi possível criar nova inspeção (-2).</p>
                    <?php }else{ ?>
                        <?php if($addInspectionAction == -1){ ?>
                            <p>Não foi obter identificador para nova inspeção (-1).</p>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>

        <form action="../inspections/add_inspection.php?clientID=<?php echo ($_GET["clientID"]); ?>" method="post" enctype="multipart/form-data">
            <div class="container">  
                <label for="inspectionClientNameAdd"><b>Nome cliente</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" name="inspectionClientNameAdd" placeholder="Nome cliente" maxlength="45" value="<?php echo($_POST["inspectionClientNameAdd"]); ?>" readonly>
                <label for="inspectionDateAdd"><b>* Data inspeção</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="date" name="inspectionDateAdd" value="<?php echo($_POST["inspectionDateAdd"]); ?>" required>
                <label for="inspectionDescriptionAdd"><b>Descrição</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" name="inspectionDescriptionAdd" placeholder="Descrição (max 250 caracteres)" maxlength="250" value="<?php echo($_POST["inspectionDescriptionAdd"]); ?>">
                <label for="inspectionNotesAdd"><b>Notas</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" name="inspectionNotesAdd" placeholder="Notas (max 250 caracteres)" maxlength="250" value="<?php echo($_POST["inspectionNotesAdd"]); ?>">
                <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit" name="addInspection">Adicionar inspeção</button>
            </div>
        </form>
        <div class="w3-content">
            <p>* Campo(s) obrigatório(s)</p>
        </div>
    </div>
    <button onclick="window.history.back();" type="button" class="w3-button w3-block w3-blue w3-section w3-padding w3-hide-meddium w3-hide-large">Voltar</button>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Bottom.php" ?>    

