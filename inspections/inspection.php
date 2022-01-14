<?php
    if(!isset($_SESSION)){
		session_start();
	}
		
    require_once $_SERVER['DOCUMENT_ROOT']."/inc/Funcoes-sql.php"; 
    
    $page="inspection";

    if(!isLogged()){
        header("Location: ../login.php");
    }

    if(isset($_GET["inspectionID"]) && !empty($_GET["inspectionID"])){
        $inspectionID = $_GET["inspectionID"];
    }else{
        header("Location: ../inspections/inspections.php");
    }

    $inspection = getInspectionFromID($_GET["inspectionID"]);

    if($inspection == -1){
        header("Location: ../inspections/inspections.php?viewInspection=-1");
    }else{
        if($inspection != -2 && $inspection != -3){
            foreach ($inspection as $inspectionValue){
                $user_id = $inspectionValue["user_id"];
                $client_id = $inspectionValue["client_id"];
                $data_inspecao = $inspectionValue["data_inspecao"];
                $data_limite_prox_inspecao = $inspectionValue["data_limite_prox_inspecao"];
                $descricao = $inspectionValue["descricao"];
                $notas = $inspectionValue["notas"];

                $client = getClientFromId($inspectionValue["client_id"]);
                foreach ($client as $clientValue){
                    $clientName = $clientValue["name"];
                    $clientImg = $clientValue["photo_path"];
                    if(is_null($clientImg)){
                        $photoNumber = rand(1,4);
                        $clientImg = "../img/avatar/Female_Avatar_$photoNumber.png";
                    }
                }

                $user = getUserFromID($inspectionValue["user_id"]);
                foreach ($user as $userValue){
                    $userName = $userValue["name"];
                    $userImg = $userValue["photo_path"];
                    if(is_null($userImg)){
                        $photoNumber = rand(1,4);
                        $userImg = "../img/avatar/Female_Avatar_$photoNumber.png";
                    }
                }
            }
        }else{
            header("Location: ../inspections/inspections.php?viewInspection=-2");
        }
    }
?>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Top.php" ?>

<!-- Page content -->
<div class="w3-container w3-content" style="max-width:1400px;margin-top:80px">
    <div class="w3-content" style="max-width:600px">

    <div>
        <div class="w3-row">
            <div class="w3-col s6 w3-center w3-button" onclick="window.location.href = '../clients/client.php?clientID=<?php echo ($client_id); ?>';">
                <img src="<?php echo $clientImg; ?>" class="w3-margin w3-circle" alt="Client photo" style="width:150px">
                <p>Cliente:</p>
                <h2><?php echo $clientName; ?></h2>
            </div>
            <div class="w3-col s6 w3-center w3-button" >
                <img src="<?php echo $userImg; ?>" class="w3-margin w3-circle" alt="User photo" style="width:150px">
                <p>Inspeção criada por:</p>
                <h2><?php echo $userName; ?></h2>
            </div>
        </div>
        <p><strong>Última inspeção: </strong><?php echo ($data_inspecao); ?></p>
        <p><strong>Limite próxima inspeção: </strong><?php echo ($data_limite_prox_inspecao); ?></p>
        <?php if(!is_null($descricao)){ ?>
            <p><strong>Descrição: </strong><?php echo ($descricao); ?></p>
        <?php }else{ ?>
            <p><strong>Descrição: </strong> Não especificado</p>
        <?php } ?>
        <?php if(!is_null($notas)){ ?>
            <p><strong>Notas: </strong><?php echo ($notas); ?></p>
        <?php }else{ ?>
            <p><strong>Notas: </strong> Não especificado</p>
        <?php } ?>

        <button onclick="window.location.href = '../inspections/edit_inspection.php?inspectionID=<?php echo ($_GET["inspectionID"]); ?>';" type="button" class="w3-button w3-block w3-green w3-section w3-padding">Editar inspeção</button>
    </div>

    <button onclick="window.history.back();" type="button" class="w3-button w3-block w3-blue w3-section w3-padding w3-hide-meddium w3-hide-large">Voltar</button>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Bottom.php" ?>
