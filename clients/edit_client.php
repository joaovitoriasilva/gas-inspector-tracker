<?php
    if(!isset($_SESSION)){
		session_start();
	}
		
    require_once $_SERVER['DOCUMENT_ROOT']."/inc/Funcoes-sql.php"; 
    
    $page="edit_client";

    /* Retrive variables from get */
    if(isset($_GET["clientID"])){
        if(isset($_GET["deletePhotoAction"]) && $_GET["deletePhotoAction"] == 1){
            $clientPhoto = getClientPhotoFromID($_GET["clientID"]);
            if(unlink($clientPhoto)){
                #$deletePhotoAction = unsetClientPhoto($_GET["clientID"]);
                #if($deletePhotoAction == 0){
                if(unsetClientPhoto($_GET["clientID"]) == 0){
                    header("Location: ../clients/client.php?clientID=".$_GET["clientID"]."&deletePhotoAction=0");
                }
            }else{
                header("Location: ../clients/edit_client.php?clientID=".$_GET["clientID"]."&deletePhotoAction=-3");
            }
        }
        $_POST["clientIDEdit"] = $_GET["clientID"];
    }else{
        header("Location: ../clients/clients.php");
    }

    /* Edit action */
    if (isset($_POST["clientEdit"])) {
        if(empty($_POST["clientNameEdit"])){
            $_POST["clientNameEdit"] = NULL;
        }
        if(empty($_POST["clientAddressEdit"])){
            $_POST["clientAddressEdit"] = NULL;
        }
        if(empty($_POST["clientPhoneEdit"])){
            $_POST["clientPhoneEdit"] = NULL;
        }
        if(empty($_POST["clientEmailEdit"])){
            $_POST["clientEmailEdit"] = NULL;
        }
        if(empty($_POST["clientNifEdit"])){
            $_POST["clientNifEdit"] = NULL;
        }
        if(empty($_POST["clientNotesEdit"])){
            $_POST["clientNotesEdit"] = NULL;
        }

        if(empty($_POST["clientNifEdit"]) && empty($_POST["clientPhoneEdit"]) && empty($_POST["clientNameEdit"])){
            $editAction = -3;
            $clientImg = getClientPhotoFromID($_GET["clientID"]);
        }else{
            if(isset($_FILES["clientImgEdit"]) && $_FILES["clientImgEdit"]["error"] == 0){
                #$target_dir = $_SERVER['DOCUMENT_ROOT']."\clients\clients_img\\";
                $target_dir = "../clients/clients_img/";
                $info = pathinfo($_FILES["clientImgEdit"]["name"]);
                $ext = $info['extension']; // get the extension of the file
                $newname = $_POST["clientIDEdit"].".".$ext;
                $target_file = $target_dir.$newname;
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["clientImgEdit"]["tmp_name"]);
                if($check !== false) {
                    $uploadOk = 1;
                } else {
                    $editAction = -4;
                    $uploadOk = 0;
                }
                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    $editAction = -5;
                    $uploadOk = 0;
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 1) {
                    if (move_uploaded_file($_FILES["clientImgEdit"]["tmp_name"], $target_file)) {
                        $photoPath = "..\clients\clients_img\\".$newname;
                        $editAction = editClient($_POST["clientNameEdit"],$_POST["clientNifEdit"],$_POST["clientAddressEdit"],$_POST["clientPhoneEdit"],$_POST["clientEmailEdit"],$_POST["clientIDEdit"],$_POST["clientNotesEdit"],$photoPath);
                    } else {
                        $editAction = -6;
                        $uploadOk = 0;
                    }
                }
            }else{  
                $clientPhoto = getClientPhotoFromID($_GET["clientID"]);
                $editAction = editClient($_POST["clientNameEdit"],$_POST["clientNifEdit"],$_POST["clientAddressEdit"],$_POST["clientPhoneEdit"],$_POST["clientEmailEdit"],$_POST["clientIDEdit"],$_POST["clientNotesEdit"],$clientPhoto);
            }
            if($editAction == 0){
                header("Location: ../clients/client.php?clientID=".$_POST["clientIDEdit"]."&editAction=0");
            }
        }
    }else{
        $client = getClientFromId($_GET["clientID"]);

        if($client == -1){
            header("Location: ../clients/clients.php?editError=-1");
        }else{
            if($client != -2 && $client != -3){
                foreach ($client as $clientValue){
                    $_POST["clientNameEdit"] = $clientValue["name"];
                    $_POST["clientUserIDEdit"] = $clientValue["user_id"];
                    $_POST["clientNifEdit"] = $clientValue["nif"];
                    $_POST["clientPhoneEdit"] = $clientValue["phone"];
                    $_POST["clientEmailEdit"] = $clientValue["email"];
                    $_POST["clientAddressEdit"] = $clientValue["address"];
                    $_POST["clientNotesEdit"] = $clientValue["notes"];
                    $_POST["clientImgEdit"] = $clientValue["photo_path"];
                    $_POST["clientIDEdit"] = $clientValue["id"];
                    $clientImg = $clientValue["photo_path"];
                }
                if($_POST["clientUserIDEdit"] != $_SESSION["id"]){
                    header("Location: ../index.php?editclientaccess=-1");
                }
            }else{
                if($client == -2){
                    header("Location: ../clients/clients.php?editError=-2");
                }else{
                    if($client == -3){
                        header("Location: ../clients/clients.php?editError=-3");
                    }
                }
            }
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
    <!-- Edit client -->
    <div class="w3-content" style="max-width:600px">
        <h2>Editar cliente</h2>
        <!-- Error banners -->
        <?php if($editAction == -1 || $editAction == -2 || $editAction == -3 || $editAction == -4 || $editAction == -5 || $editAction == -6 || $_GET["deletePhotoAction"] == -1 || $_GET["deletePhotoAction"] == -2 || $_GET["deletePhotoAction"] == -3){ ?>
            <div class="w3-panel w3-red w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3><i class="fas fa-hand-paper"></i> Erro</h3>
                <?php if($editAction == -1 || $editAction == -2){ ?>
                    <p>Não foi possível editar cliente (-1/-2).</p>
                <?php }else{ ?>
                    <?php if($editAction == -3){ ?>
                        <p>Um dos três é obrigatório (NIF, contacto ou nome) (-3).</p>
                    <?php }else{ ?>
                        <?php if($editAction == -4){ ?>
                            <p>Ficheiro para foto inválido (-4).</p>
                        <?php }else{ ?>
                            <?php if($editAction == -5){ ?>
                                <p>Apenas fotos do tipo .jpg, .jpeg e .png podem ser carregadas (-5).</p>
                            <?php }else{ ?>
                                <?php if($editAction == -6){ ?>
                                    <p>Não foi possível carregar foto  (-6).</p>
                                <?php }else{ ?>
                                    <?php if($_GET["deletePhotoAction"] == -1 || $_GET["deletePhotoAction"] == -2 || $_GET["deletePhotoAction"] == -3){ ?>
                                        <p>Não foi possível eliminar foto do cliente (-1/-2/-3).</p>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>

        <!-- Avatar ?clientID=<?php echo($_POST["clientIDEdit"]); ?>-->
        <div class="w3-center">
            <img src="<?php echo $clientImg; ?>" class="w3-margin w3-circle" alt="Client photo" style="width:125px">
            <?php if(!is_null($_POST["clientImgEdit"])){ ?>
                <button onclick="window.location.href = '../clients/edit_client.php?clientID=<?php echo ($_POST["clientIDEdit"]); ?>&deletePhotoAction=1';" type="button" class="w3-button w3-block w3-red w3-section w3-padding">Eliminar foto</button>
            <?php } ?>
        </div>
        <form action="../clients/edit_client.php?clientID=<?php echo($_POST["clientIDEdit"]); ?>" method="post" enctype="multipart/form-data">
            <div class="w3-section">
                <label for="clientImgEdit"><b>Foto cliente</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="file" accept="image/*" name="clientImgEdit" id="clientImgEdit" value="<?php echo($_POST["clientImgEdit"]); ?>">
                <label for="clientIDEdit"><b>* Número cliente (não alterável)</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="number" name="clientIDEdit" placeholder="Número cliente" value="<?php echo($_POST["clientIDEdit"]); ?>" readonly>  
                <label for="clientUserIDEdit"><b>* ID utilizador (não alterável)</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="number" name="clientUserIDEdit" placeholder="ID utilizador" value="<?php echo($_POST["clientUserIDEdit"]); ?>" readonly>   
                <label for="clientNameEdit"><b>Nome cliente</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" name="clientNameEdit" placeholder="Nome (Max 45 caractéres)" maxlength="45" value="<?php echo($_POST["clientNameEdit"]); ?>">
                <label for="clientNifEdit"><b>NIF</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="number" name="clientNifEdit" placeholder="NIF" value="<?php echo($_POST["clientNifEdit"]); ?>">
                <label for="clientPhoneEdit"><b>Telefone</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="number" name="clientPhoneEdit" placeholder="Telefone" value="<?php echo($_POST["clientPhoneEdit"]); ?>">
                <label for="clientEmailEdit"><b>Email</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="email" name="clientEmailEdit" placeholder="Email (Max 100 caractéres)" maxlength="100" value="<?php echo($_POST["clientEmailEdit"]); ?>">
                <label for="clientAddressEdit"><b>Morada</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" name="clientAddressEdit" placeholder="Morada (Max 250 caractéres)" maxlength="250" value="<?php echo($_POST["clientAddressEdit"]); ?>">
                <label for="clientNotesEdit"><b>Notas</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" name="clientNotesEdit" placeholder="Notas (Max 250 caractéres)" maxlength="250" value="<?php echo($_POST["clientNotesEdit"]); ?>">
                <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit" name="clientEdit">Editar cliente</button>
            </div>
        </form>
    </div>
    <button onclick="window.history.back();" type="button" class="w3-button w3-block w3-blue w3-section w3-padding w3-hide-meddium w3-hide-large">Voltar</button>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Bottom.php" ?>