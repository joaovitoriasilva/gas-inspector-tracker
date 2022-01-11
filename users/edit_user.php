<?php
    if(!isset($_SESSION)){
		session_start();
	}
		
    require_once $_SERVER['DOCUMENT_ROOT']."/inc/Funcoes-sql.php"; 
    
    $page="edit_user";

    if(!isLogged()){
        header("Location: ../login.php");
    }

    if($_SESSION["type"] == 1){
        header("Location: ../index.php");
    }

    /* Retrive variables from get */
    if(isset($_GET["userID"])){
        if(isset($_GET["deletePhotoAction"]) && $_GET["deletePhotoAction"] == 1){
            $userPhoto = getUserPhotoAuxFromID($_GET["userID"]);
            if(unlink($userPhoto)){
                $deletePhotoAction = unsetUserPhoto($_GET["userID"]);
                if($deletePhotoAction == 0){
                    header("Location: ../users/users.php?userID=".$_GET["userID"]."&deletePhotoAction=0");
                }
            }else{
                $deletePhotoAction = -3;
            }
        }
        if(!isset($_POST["userEdit"])){
            $user = getUserFromID($_GET["userID"]);
            if($user == -1){
                header("Location: ../users/users.php?editUserError=-1");
            }else{
                if($user != -2 && $user != -3){
                    foreach ($user as $userValue){
                        $_POST["userTypeEdit"] = $userValue["tipo"];
                        $_POST["userNameEdit"] = $userValue["name"];
                        $_POST["userUsernameEdit"] = $userValue["username"];
                        $_POST["userImgEdit"] = $userValue["photo_path"];
                        $userImg = $userValue["photo_path"];
                        $userImgAux = $userValue["photo_path_aux"];
                    }
                }else{
                    if($user == -2){
                        header("Location: ../users/users.php?editUserError=-2");
                    }else{
                        if($user == -3){
                            header("Location: ../users/users.php?editUserError=-3");
                        }
                    }
                }
            }
        }

        if(is_null($userImg)){
            $photoNumber = rand(1,4);
            /*$genderRandom = rand(1,2);
            if(($_SESSION["gender"] == 1) || ($_SESSION["gender"] == 3 && $genderRandom == 1)) {
                $userImg = "../img/avatar/Male_Avatar_$photoNumber.png";
            }else{
                if(($_SESSION["gender"] == 2) || ($_SESSION["gender"] == 3 && $genderRandom == 2)) {*/
                    $userImg = "../img/avatar/Female_Avatar_$photoNumber.png";
            /* }
            }*/
        }
    }else{
        header("Location: ../users/users.php");
    }

    /* Edit action */
    if (isset($_POST["userEdit"])) {
        if(empty($_POST["userUsernameEdit"])){
            $_POST["userUsernameEdit"] = NULL;
        }
        if(empty($_POST["userNameEdit"])){
            $_POST["userNameEdit"] = NULL;
        }
        if(empty($_POST["userTypeEdit"])){
            $_POST["userTypeEdit"] = NULL;
        }
        
        if(empty($_POST["userUsernameEdit"]) && empty($_POST["userTipoEdit"])){
            $editAction = -3;
            $userImg = getUserPhotoFromID($_GET["clientID"]);
        }else{
            if(isset($_FILES["userImgEdit"]) && $_FILES["userImgEdit"]["error"] == 0){
                $target_dir = "../users/users_img/";
                $info = pathinfo($_FILES["userImgEdit"]["name"]);
                $ext = $info['extension']; // get the extension of the file
                $newname = $_GET["userID"].".".$ext;
                $target_file = $target_dir.$newname;
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["userImgEdit"]["tmp_name"]);
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
                    if (move_uploaded_file($_FILES["userImgEdit"]["tmp_name"], $target_file)) {
                        $photoPath = "..\users\users_img\\".$newname;
                        $photoPath_aux = $target_file;
                        $editAction = editUser($_POST["userNameEdit"], $_POST["userUsernameEdit"], $_GET["userID"], $_POST["userTypeEdit"],$photoPath,$photoPath_aux);
                    } else {
                        $editAction = -6;
                        $uploadOk = 0;
                    }
                }
            }else{
                $userPhoto = getUserPhotoFromID($_GET["userID"]);
                $userPhotoAux = getUserPhotoAuxFromID($_GET["userID"]);
                $editAction = editUser($_POST["userNameEdit"], $_POST["userUsernameEdit"], $_GET["userID"], $_POST["userTypeEdit"],$userPhoto,$userPhotoAux);
            }
            if($editAction == 0){
                header("Location: ../users/users.php?userID=".$_GET["userID"]."&userEditAction=0");
            }
        }
    }else{
        /* Delete action */
        if(isset($_GET["deleteUser"]) && $_GET["deleteUser"] == 1){
            if($_GET["userID"] != $_SESSION["id"]){
                #if($clientLoanCount == 0 && $clientRepairCount == 0 && $clientConstructionCount == 0){
                    $photo_path = getUserPhotoAuxFromID($_GET["userID"]);
                    $deleteAction = deleteUser($_GET["userID"]);
                    if($deleteAction == 0){
                        if(!is_null($photo_path)){
                            if(unlink($photo_path)){
                                header("Location: ../users/users.php?userDeleted=0&photoDeleted=0");
                            }else{
                                header("Location: ../users/users.php?userDeleted=0&photoDeleted=1");
                            }
                        }else{
                            header("Location: ../users/users.php?userDeleted=0&photoDeleted=2");
                        }
                    }
                #}else{
                #    $deleteAction = -3;
                #}
            }else{
                $deleteAction = -3;
            }
        }
    }
?>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Top.php" ?>

<!-- Page content -->
<div class="w3-content w3-padding-16" style="max-width:2000px;margin-top:46px;margin-left:16px;margin-right:16px">
    <!-- Edit client -->
    <div class="w3-content" style="max-width:600px">
        <h2>Editar utilizador</h2>
        <!-- Error banners -->
        <?php if($editAction == -1 || $editAction == -2 || $editAction == -3 || $editAction == -4 || $editAction == -5 || $editAction == -6 || $deletePhotoAction == -1 || $deletePhotoAction == -2 || $deletePhotoAction == -3 || $deleteAction == -1 || $deleteAction == -2 || $deleteAction == -3 || $deleteAction == -4){ ?>
            <div class="w3-panel w3-red w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3><i class="fas fa-hand-paper"></i> Erro</h3>
                <?php if($editAction == -1 || $editAction == -2){ ?>
                    <p>Não foi possível editar utilizador (-1/-2).</p>
                <?php }else{ ?>
                    <?php if($editAction == -3){ ?>
                        <p>Username e tipo obrigatórios (-3).</p>
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
                                    <?php if($deletePhotoAction == -1 || $deletePhotoAction == -2 || $deletePhotoAction == -3){ ?>
                                        <p>Não foi possível eliminar foto do utilizador (-1/-2/-3).</p>
                                    <?php }else{ ?>
                                        <?php if($deleteAction == -1 || $deleteAction == -2){ ?>
                                            <p>Não foi possível eliminar utilizador (-1/-2).</p>
                                        <?php }else{ ?>
                                            <?php if($deleteAction == -3){ ?>
                                                <p>Não é possível eliminar utilizador atualmente em uso (-3).</p>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>

        <div class="w3-center">
            <img src="<?php echo $userImg; ?>" class="w3-margin w3-circle" alt="Client photo" style="width:125px">
            <?php if(!is_null($_POST["userImgEdit"])){ ?>
                <button onclick="window.location.href = '../users/edit_user.php?userID=<?php echo ($_GET["userID"]); ?>&deletePhotoAction=1';" type="button" class="w3-button w3-block w3-red w3-section w3-padding">Eliminar foto</button>
            <?php } ?>
        </div>
        <form action="../users/edit_user.php?userID=<?php echo($_GET["userID"]); ?>" method="post" enctype="multipart/form-data">
            <div class="w3-section">
                <label for="userImgEdit"><b>Foto cliente</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="file" accept="image/*" name="userImgEdit" id="userImgEdit" value="<?php echo($_POST["userImgEdit"]); ?>">  
                <label for="userUsernameEdit"><b>* Username</b></label>
                <input class="w3-input w3-border w3-margin-bottom"  type="text" name="userUsernameEdit" placeholder="Username (Max 250 caractéres)" maxlength="250" value="<?php echo($_POST["userUsernameEdit"]); ?>" required>
                <label for="userNameEdit"><b>Nome utilizador</b></label>
                <input class="w3-input w3-border w3-margin-bottom"  type="text" name="userNameEdit" placeholder="Nome (Max 250 caractéres)" maxlength="250" value="<?php echo($_POST["userNameEdit"]); ?>">
                <label for="userTypeEdit"><b>* Tipo utilizador</b></label>
                <select class="w3-input w3-border w3-margin-bottom"  name="userTypeEdit">
                    <option value="1" <?php if($_POST["userTypeEdit"] == 1){ ?> selected="selected" <?php } ?>>Não administrador</option>
                    <option value="2" <?php if($_POST["userTypeEdit"] == 2){ ?> selected="selected" <?php } ?>>Administrador</option>
                </select required>
                <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit" name="userEdit">Editar utilizador</button>
            </div>
        </form>
        <button onclick="window.location.href = '../users/edit_user.php?userID=<?php echo ($_GET["userID"]); ?>&deleteUser=1';" type="button" class="w3-button w3-block w3-red w3-section w3-padding">Eliminar utilizador</button>
    </div>
    <button onclick="window.history.back();" type="button" class="w3-button w3-block w3-blue w3-section w3-padding w3-hide-meddium w3-hide-large">Voltar</button>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Bottom.php" ?>  
