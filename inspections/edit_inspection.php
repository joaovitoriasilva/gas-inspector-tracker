<?php
    if(!isset($_SESSION)){
		session_start();
	}
		
    require_once $_SERVER['DOCUMENT_ROOT']."/inc/Funcoes-sql.php"; 
    
    $page="edit_inspection";

    if(!isLogged()){
        header("Location: ../login.php");
    }
    
    if(isset($_GET["clientID"])){
        $clientID = $_GET["clientID"];
    }else{
        $clientID = NULL;
    }

    /* Retrive variables from get */
    if(isset($_GET["inspectionID"])){
        if(!isset($_POST["inspectionEdit"])){
            $inspection = getInspectionFromID($_GET["inspectionID"]);
            if($inspection == -1){
                header("Location: ../index.php?edit_inspection=-2");
            }else{
                if($inspection != -2 && $inspection != -3){
                    foreach ($inspection as $inspectionValue){
                        $clientID = $inspectionValue["client_id"];
                        $_POST["inspectionData_inspecaoEdit"] = $inspectionValue["data_inspecao"];
                        $_POST["inspectionDescriptionEdit"] = $inspectionValue["descricao"];
                        $_POST["inspectionNotesEdit"] = $inspectionValue["notas"];

                        // get client name
                        $client = getClientFromId($inspectionValue["client_id"]);
                        foreach ($client as $clientValue){
                            $_POST["inspectionClientNameEdit"] = $clientValue["name"];
                        }

                        // get user name
                        $user = getUserFromID($inspectionValue["user_id"]);
                        foreach ($user as $userValue){
                            $_POST["inspectionUserNameEdit"] = $userValue["name"];
                        }
                    }
                }else{
                    header("Location: ../index.php?edit_inspection=-3");
                }
            }
        }
    }else{
        header("Location: ../index.php?edit_inspection=-1");
    }

    /* Edit action */
    if(isset($_POST["inspectionEdit"])){
        if(!empty($_POST["inspectionData_inspecaoEdit"])){
            if(empty($_POST["inspectionDescriptionEdit"])){
                $_POST["inspectionDescriptionEdit"] = NULL;
            }
            if(empty($_POST["inspectionNotesEdit"])){
                $_POST["inspectionNotesEdit"] = NULL;
            }
            $editAction = editInspection($_GET["inspectionID"], date('Y-m-d', strtotime($_POST["inspectionData_inspecaoEdit"])),$_POST["inspectionDescriptionEdit"],$_POST["inspectionNotesEdit"]);
            if ($editAction == 0){
                header("Location: ../clients/client.php?clientID=$clientID&editInspectionAction=0");
            }
        }else{
            $editAction = -10;
        }
    }else{
        /* Delete action */
        if(isset($_GET["deleteInspection"]) && $_GET["deleteInspection"] == 1){
            $deleteAction = deleteInspection($_GET["inspectionID"]);
            if($deleteAction == 0){
                header("Location: ../clients/client.php?clientID=$clientID&inspectionDeleted=0");
            }
        }
    }
?>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Top.php" ?>

<!-- Page content -->
<div class="w3-content w3-padding-16" style="max-width:2000px;margin-top:46px;margin-left:16px;margin-right:16px">
    <!-- Edit client -->
    <div class="w3-content" style="max-width:600px">
        <h2>Editar inspeção</h2>
        <!-- Error banners -->
        <?php if($editAction == -1 || $editAction == -2 || $editAction == -10 || $deleteAction == -1 || $deleteAction == -2){ ?>
            <div class="w3-panel w3-red w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3><i class="fas fa-hand-paper"></i> Erro</h3>
                <?php if($editAction == -1 || $editAction == -2){ ?>
                    <p>Não foi possível editar inspeção (-1/-2).</p>
                <?php }else{ ?>
                    <?php if($editAction == -10){ ?>
                        <p>Data não especificada (-10).</p>
                    <?php }else{ ?>
                        <?php if($deleteAction == -1 || $deleteAction == -2){ ?>
                            <p>Não foi possível eliminar utilizador (-1/-2).</p>
                        <?php } ?>               
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>

        <form action="../inspections/edit_inspection.php?inspectionID=<?php echo($_GET["inspectionID"]); ?>" method="post" enctype="multipart/form-data">
            <div class="w3-section">
                <label for="inspectionClientNameEdit"><b>Nome cliente</b></label>
                <input class="w3-input w3-border w3-margin-bottom"  type="text" name="inspectionClientNameEdit" placeholder="Nome cliente (Max 250 caractéres)" maxlength="250" value="<?php echo($_POST["inspectionClientNameEdit"]); ?>" readonly>
                <label for="inspectionUserNameEdit"><b>Utilizador que criou inspeção</b></label>
                <input class="w3-input w3-border w3-margin-bottom"  type="text" name="inspectionUserNameEdit" placeholder="Nome utilizador(Max 250 caractéres)" maxlength="250" value="<?php echo($_POST["inspectionUserNameEdit"]); ?>" readonly>
                <label for="inspectionData_inspecaoEdit"><b>* Data inspeção</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="date" name="inspectionData_inspecaoEdit" value="<?php echo($_POST["inspectionData_inspecaoEdit"]); ?>" required>
                <label for="inspectionDescriptionEdit"><b>Descrição</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" name="inspectionDescriptionEdit" placeholder="Descrição (max 250 caracteres)" maxlength="250" value="<?php echo($_POST["inspectionDescriptionEdit"]); ?>">
                <label for="inspectionNotesEdit"><b>Notas</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" name="inspectionNotesEdit" placeholder="Notas (max 250 caracteres)" maxlength="250" value="<?php echo($_POST["inspectionNotesEdit"]); ?>">
                <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit" name="inspectionEdit">Editar inspeção</button>
            </div>
        </form>
        <div class="w3-content">
            <p>* Campo(s) obrigatório(s)</p>
        </div>
        <button onclick="window.location.href = '../inspections/edit_inspection.php?inspectionID=<?php echo ($_GET["inspectionID"]); ?>&deleteInspection=1';" type="button" class="w3-button w3-block w3-red w3-section w3-padding">Eliminar inspeção</button>
    </div>
    <button onclick="window.history.back();" type="button" class="w3-button w3-block w3-blue w3-section w3-padding w3-hide-meddium w3-hide-large">Voltar</button>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Bottom.php" ?>  