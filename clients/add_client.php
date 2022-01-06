<?php
    if(!isset($_SESSION)){
		session_start();
	}
		
    require_once $_SERVER['DOCUMENT_ROOT']."/inc/Funcoes-sql.php"; 
    
    $page="add_client";

    if(isset($_POST["addClient"])){
        if(!empty($_POST["clientNifAdd"]) || !empty($_POST["clientPhoneAdd"]) || !empty($_POST["clientNameAdd"])){
            if(empty($_POST["clientNameAdd"])){
                $_POST["clientNameAdd"] = NULL;
            }
            if(empty($_POST["clientAddressAdd"])){
                $_POST["clientAddressAdd"] = NULL;
            }
            if(empty($_POST["clientPhoneAdd"])){
                $_POST["clientPhoneAdd"] = NULL;
            }
            if(empty($_POST["clientEmailAdd"])){
                $_POST["clientEmailAdd"] = NULL;
            }
            if(empty($_POST["clientNifAdd"])){
                $_POST["clientNifAdd"] = NULL;
            }
            if(empty($_POST["clientNotesAdd"])){
                $_POST["clientNotesAdd"] = NULL;
            }

            if(isset($_FILES["clientImgAdd"]) && $_FILES["clientImgAdd"]["error"] == 0){
                $target_dir = "../clients/clients_img/";
                $info = pathinfo($_FILES["clientImgAdd"]["name"]);
                $ext = $info['extension']; // get the extension of the file
                $number=lastIdClients()+1;
                $newname = $number.".".$ext;
                $target_file = $target_dir.$newname;
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["clientImgAdd"]["tmp_name"]);
                if($check !== false) {
                    $uploadOk = 1;
                } else {
                    $addClientAction = -4;
                    $uploadOk = 0;
                }
                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    $addClientAction = -5;
                    $uploadOk = 0;
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 1) {
                    if (move_uploaded_file($_FILES["clientImgAdd"]["tmp_name"], $target_file)) {
                        $photoPath = "..\clients\clients_img\\".$newname;
                        $photoPath_aux = $target_file;
                    } else {
                        $addClientAction = -6;
                        $uploadOk = 0;
                    }
                }
            }else{
                $photoPath = NULL;
                $photoPath_aux = NULL;
                $uploadOk = 1;
            }
            if($uploadOk == 1){
                $addClientAction = newClient($_POST["clientNameAdd"], $_SESSION["id"], $_POST["clientNifAdd"], $_POST["clientAddressAdd"], $_POST["clientPhoneAdd"], $_POST["clientEmailAdd"],$_POST["clientNotesAdd"], $photoPath, $photoPath_aux);
            }
            if ($addClientAction == 0){
                header("Location: ../clients/clients.php?addClientAction=0");
            }
        }else{
            $addClientAction = -7;
        }
    }
?>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Top.php" ?>

<!-- Page content -->
<div class="w3-content w3-padding-16" style="max-width:2000px;margin-top:46px;margin-left:16px;margin-right:16px">
    <!-- Add client -->
    <div class="w3-content" style="max-width:600px">
        <h2>Adicionar cliente</h2>
        <!-- Error banners -->
        <?php if($addClientAction == -1 || $addClientAction == -2 || $addClientAction == -3 || $addClientAction == -4 || $addClientAction == -5 || $addClientAction == -6 || $addClientAction == -7){ ?>
            <div class="w3-panel w3-red w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3><i class="fas fa-hand-paper"></i> Erro</h3>
                <?php if($addClientAction == -1){ ?>
                    <p>Não foi possível obter novo número de cliente (-1).</p>
                <?php }else{ ?>
                    <?php if($addClientAction == -2){ ?>
                        <p>Não foi possível criar novo cliente (-2).</p>
                    <?php }else{ ?>
                        <?php if($addClientAction == -3){ ?>
                            <p>Cliente com NIF já inserido. Verificar NIF (-3).</p>
                        <?php }else{ ?>
                            <?php if($addClientAction == -4){ ?>
                                <p>Ficheiro para foto inválido (-4).</p>
                            <?php }else{ ?>
                                <?php if($addClientAction == -5){ ?>
                                    <p>Apenas fotos do tipo .jpg, .jpeg e .png podem ser carregadas (-5).</p>
                                <?php }else{ ?>
                                    <?php if($addClientAction == -6){ ?>
                                        <p>Não foi possível carregar foto (-6).</p>
                                    <?php }else{ ?>
                                        <?php if($addClientAction == -7){ ?>
                                            <p>Um dos três é obrigatório (NIF, contacto ou nome) (-7).</p>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>

        <form action="../clients/add_client.php" method="post" enctype="multipart/form-data">
            <div class="w3-section">
                <label for="clientImgAdd"><b>Foto cliente</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="file" accept="image/*" name="clientImgAdd" id="clientImgAdd" value="<?php echo($_POST["clientImgAdd"]); ?>">
                <label for="clientNameAdd"><b>Nome cliente</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" name="clientNameAdd" placeholder="Nome (Max 45 caractéres)" maxlength="45" value="<?php echo($_POST["clientNameAdd"]); ?>">
                <label for="clientNifAdd"><b>NIF</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="number" name="clientNifAdd" placeholder="NIF" value="<?php echo($_POST["clientNifAdd"]); ?>">
                <label for="clientPhoneAdd"><b>Telefone</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="number" name="clientPhoneAdd" placeholder="Telefone" value="<?php echo($_POST["clientPhoneAdd"]); ?>">
                <label for="clientEmailAdd"><b>Email</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="email" name="clientEmailAdd" placeholder="Email (Max 100 caractéres)" maxlength="100" value="<?php echo($_POST["clientEmailAdd"]); ?>">
                <label for="clientAddressAdd"><b>Morada</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" name="clientAddressAdd" placeholder="Morada (Max 250 caractéres)" maxlength="250" value="<?php echo($_POST["clientAddressAdd"]); ?>">
                <label for="clientNotesAdd"><b>Notas</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" name="clientNotesAdd" placeholder="Notas (Max 250 caractéres)" maxlength="250" value="<?php echo($_POST["clientNotesAdd"]); ?>">
                <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit" name="addClient">Adicionar cliente</button>
            </div>
        </form>
        <div class="w3-content">
            <p>* Campo(s) obrigatório(s)</p>
        </div>
    </div>
    <button onclick="window.history.back();" type="button" class="w3-button w3-block w3-blue w3-section w3-padding w3-hide-meddium w3-hide-large">Voltar</button>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Bottom.php" ?>