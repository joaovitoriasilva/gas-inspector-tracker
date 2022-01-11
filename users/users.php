<?php
    if(!isset($_SESSION)){
		session_start();
	}
		
    require_once $_SERVER['DOCUMENT_ROOT']."/inc/Funcoes-sql.php"; 
    
    $page="users";

    if(!isLogged()){
        header("Location: ../login.php");
    }

    if($_SESSION["type"] == 1){
        header("Location: ../index.php");
    }

    if(isset($_POST["userSearch"])){
        $userID = getUserIDFromUsername($_POST["userUsername"]);
        if($userID > 0){
            header("Location: ../users/edit_user.php?userID=".$userID);
        }
    }

    $users = getUsers();
    $numUsers = numUsers();
?>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Top.php" ?>

<!-- Page content -->
<div class="w3-content w3-padding-16" style="max-width:2000px;margin-top:46px;margin-left:16px;margin-right:16px">
    <div class="w3-content" style="max-width:600px">
        <!-- Error banners -->
        <?php if($userID == -1 || $userID == -2){ ?>
            <div class="w3-panel w3-red w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3><i class="fas fa-hand-paper"></i> Erro</h3>
                <p>Não foi possível procurar utilizador (-1/-2).</p>
            </div>
        <?php } ?>
        <?php if($_GET["editUserError"] == -1 || $_GET["editUserError"] == -2 || $_GET["editUserError"] == -3){ ?>
            <div class="w3-panel w3-red w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3><i class="fas fa-hand-paper"></i> Erro</h3>
                <p>Não foi possível listar utilizador após edição (-1/-2/-3).</p>
            </div>
        <?php } ?>
       <!-- Info banners -->
       <?php if($userID == -3){ ?>
            <div class="w3-panel w3-yellow w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3><i class="fas fa-exclamation-triangle"></i> Info!</h3>
                <p>Utilizador não existe.</p>
            </div>
        <?php } ?>
        <?php if(isset($_GET["userDeleted"])){
            if($_GET["userDeleted"] == 0 && $_GET["photoDeleted"] == 1){ ?>
                <div class="w3-panel w3-yellow w3-display-container">
                    <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                    <h3>Info</h3>
                    <p>Utilizador eliminado. Não foi possível eliminar respetiva foto no filesystem.</p>
                </div>
            <?php } 
        }?>
        <!-- Success banners -->
        <?php if(isset($_GET["addUserAction"]) && $_GET["addUserAction"] == 0){ ?>
            <div class="w3-panel w3-green w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3>Info</h3>
                <p>Utilizador adicionado.</p>
            </div>
        <?php } ?>
        <?php if(isset($_GET["userEditAction"]) && $_GET["userEditAction"] == 0){ ?>
            <div class="w3-panel w3-green w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3>Info</h3>
                <p>Dados de utilizador alterados.</p>
            </div>
        <?php } ?>
        <?php if(isset($_GET["userDeleted"])){
            if(($_GET["userDeleted"] == 0 && $_GET["photoDeleted"] == 0) || ($_GET["userDeleted"] == 0 && $_GET["photoDeleted"] == 2)){ ?>
                <div class="w3-panel w3-green w3-display-container">
                    <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                    <h3>Info</h3>
                    <p>Utilizador eliminado.</p>
                </div>
            <?php } 
        }?>
        <?php if(isset($_GET["deletePhotoAction"])){
            if($_GET["deletePhotoAction"] == 0){ ?>
                <div class="w3-panel w3-green w3-display-container">
                    <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                    <h3>Info</h3>
                    <p>Foto de utilizador eliminada.</p>
                </div>
            <?php } 
        }?>
    </div>

    <div class="w3-content" style="max-width:600px" id="users">
        <h2>Utilizadores</h2>
    </div>
    <!-- Add user section -->
    <div class="w3-content" style="max-width:600px">
        <p>Adicionar utilizador:</p>
        <button onclick="window.location.href = '../users/add_user.php';" type="button" class="w3-button w3-block w3-blue w3-section w3-padding">Novo utilizador</button>
    </div>

    <!-- Search users section -->
    <div class="w3-content" style="max-width:600px">
        <p>Procurar utilizador por username:</p>
        <form action="../users/users.php" method="post">
            <label for="userUsername"><b>Username</b></label>
            <input class="w3-input w3-border w3-margin-bottom" type="text" name="userUsername" placeholder="Username" required>
            <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit" name="userSearch">Procurar</button>
        </form>
    </div>

    <!-- List users Section -->
    <div class="w3-content" style="max-width:600px">
         <!-- Error banners -->
         <?php if($users == -1 || $numUsers == -1 || $users == -2){ ?>
            <div class="w3-container">
                <div class="w3-panel w3-red w3-display-container">
                    <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                    <h3><i class="fas fa-hand-paper"></i> Erro</h3>
                    <p>Não foi possível listar utilizadores (-1/-2).</p>
                </div>
            </div>
        <?php }else{ ?>
            <!-- Info banners -->
            <?php if($users == -3){ ?>
                <div class="w3-container">
                    <div class="w3-panel w3-yellow w3-display-container">
                        <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                        <h3><i class="fas fa-exclamation-triangle"></i> Info</h3>
                        <p>Não existem utilizadores inseridos (-3).</p>
                    </div>
                </div>
            <?php }else{ ?>
                <p>Existe um total de <?php echo ($numUsers); ?> utilizadores:</p>
                <ul class="w3-ul w3-border-top w3-border-bottom w3-hoverable">
                    <?php foreach ($users as $user) { ?>
                        <li class="w3-bar w3-button" onclick="window.location.href = '../users/edit_user.php?userID=<?php echo ($user["id"]); ?>';">
                            <img src=<?php if(is_null($user["photo_path"])){ echo ("../img/avatar/Male_Avatar_4.png"); }else{ echo ($user["photo_path"]); }?> class="w3-bar-item w3-circle" style="width:85px">
                            <div class="w3-bar-item">
                                <span class="w3-large w3-left"><?php echo ($user["username"]); ?></span><br>
                                <span class="w3-left">Tipo: <?php if($user["tipo"] == 1){ echo ("Não administrador"); }else{ echo ("Administrador"); } ?></span>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
        <?php } ?>
    </div>
<!-- END MAIN -->
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Bottom.php" ?>
