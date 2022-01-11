<?php
    if(!isset($_SESSION)){
		session_start();
	}
		
    require_once $_SERVER['DOCUMENT_ROOT']."/inc/Funcoes-sql.php"; 
    
    $page="add_user";

    if(!isLogged()){
        header("Location: ../login.php");
    }

    if($_SESSION["type"] == 1){
        header("Location: ../index.php");
    }

    if(isset($_POST["addUser"])){
        if(empty($_POST["UserNameAdd"])){
            $_POST["UserNameAdd"] = NULL;
        }

        if(isset($_FILES["userImgAdd"]) && $_FILES["userImgAdd"]["error"] == 0){
            $target_dir = "../users/users_img/";
            $info = pathinfo($_FILES["userImgAdd"]["name"]);
            $ext = $info['extension']; // get the extension of the file
            $number=lastIdUsers()+1;
            $newname = $number.".".$ext;
            $target_file = $target_dir.$newname;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["userImgAdd"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                $addUserAction = -4;
                $uploadOk = 0;
            }
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                $addUserAction = -5;
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["userImgAdd"]["tmp_name"], $target_file)) {
                    $photoPath = "..\users\users_img\\".$newname;
                    $photoPath_aux = $target_file;
                } else {
                    $addUserAction = -6;
                    $uploadOk = 0;
                }
            }
        }else{
            $photoPath = NULL;
            $photoPath_aux = NULL;
            $uploadOk = 1;
        }
        if($uploadOk == 1){
            $addUserAction = newUser($_POST["userNameAdd"], $_POST["userUsernameAdd"], $_POST["passUserAdd"], $_POST["userTypeAdd"], $photoPath, $photoPath_aux);
        }
        if ($addUserAction == 0){
            header("Location: ../users/users.php?addUserAction=0");
        }
    }
?>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Top.php" ?>

<!-- Page content -->
<div class="w3-content w3-padding-16" style="max-width:2000px;margin-top:46px;margin-left:16px;margin-right:16px">
    <!-- Add user -->
    <div class="w3-content" style="max-width:600px">
        <h2>Adicionar utilizador</h2>
        <!-- Error banners -->
        <?php if($addUserAction == -1 || $addUserAction == -2 || $addUserAction == -3 || $addUserAction == -4 || $addUserAction == -5 || $addUserAction == -6){ ?>
            <div class="w3-panel w3-red w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3><i class="fas fa-hand-paper"></i> Erro</h3>
                <?php if($addUserAction == -1){ ?>
                    <p>Não foi possível obter novo número de cliente (-1).</p>
                <?php }else{ ?>
                    <?php if($addUserAction == -2){ ?>
                        <p>Não foi possível criar novo cliente (-2).</p>
                    <?php }else{ ?>
                        <?php if($addUserAction == -3){ ?>
                            <p>Cliente com username já inserido. Verificar username (-3).</p>
                        <?php }else{ ?>
                            <?php if($addUserAction == -4){ ?>
                                <p>Ficheiro para foto inválido (-4).</p>
                            <?php }else{ ?>
                                <?php if($addUserAction == -5){ ?>
                                    <p>Apenas fotos do tipo .jpg, .jpeg e .png podem ser carregadas (-5).</p>
                                <?php }else{ ?>
                                    <?php if($addUserAction == -6){ ?>
                                        <p>Não foi possível carregar foto (-6).</p>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>

        <form action="../users/add_user.php" method="post" enctype="multipart/form-data">
            <div class="container">
                <label for="userImgAdd"><b>Foto utilizador</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="file" accept="image/*" name="userImgAdd" id="userImgAdd" value="<?php echo($_POST["userImgAdd"]); ?>">  
                <label for="userUsernameAdd"><b>* Username</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" name="userUsernameAdd" placeholder="Username (Max 45 caractéres)" maxlength="45" value="<?php echo($_POST["userUsernameAdd"]); ?>" required>
                <label for="userNameAdd"><b>Nome</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" name="userNameAdd" placeholder="Nome (Max 45 caractéres)" maxlength="45" value="<?php echo($_POST["userNameAdd"]); ?>">
                <label for="passUserAdd"><b>* Password</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="password" name="passUserAdd" placeholder="Password" value="<?php echo($_POST["passUserAdd"]); ?>" required>
                <label for="userTypeAdd"><b>* Tipo utilizador</b></label>
                <select class="w3-input w3-border w3-margin-bottom" name="userTypeAdd">
                    <option value="1" <?php if($_POST["userTypeAdd"] == 1){ ?> selected="selected" <?php } ?>>Não administrador</option>
                    <option value="2" <?php if($_POST["userTypeAdd"] == 2){ ?> selected="selected" <?php } ?>>Administrador</option>
                </select required>
                <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit" name="addUser">Adicionar utilizador</button>
            </div>
        </form>
        <div class="w3-content">
            <p>* Campo(s) obrigatório(s)</p>
        </div>
    </div>
    <button onclick="window.history.back();" type="button" class="w3-button w3-block w3-blue w3-section w3-padding w3-hide-meddium w3-hide-large">Voltar</button>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Bottom.php" ?>    
