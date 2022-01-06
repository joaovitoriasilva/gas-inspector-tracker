<?php	
    if(!isset($_SESSION)){
        session_start();
    }
        
    require_once $_SERVER['DOCUMENT_ROOT']."/inc/Funcoes-sql.php"; 

    $page="login";
    
    if(isLogged()){
        header("Location: ../index.php");
    }
    
    $error = 0;
	
	if(isset($_POST["loginUsername"]) && isset($_POST["loginPassword"]))
	{
        clearUserRelatedInfoSession();
        $hashPassword = hash("sha256",$_POST["loginPassword"]);
		$userID = loginUser($_POST["loginUsername"], $hashPassword);
		if($userID >=0){
            setUserRelatedInfoSession($userID);
            header("Location: ../index.php");
            die();
		}else{
            $error = 1;
        }
	}
?>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Top.php" ?>

<!-- Page content -->
<div class="w3-content" style="max-width:2000px;margin-top:46px;margin-left:16px;margin-right:16px">
    <!-- Login Section -->
    <div class="w3-container w3-content w3-padding-16" style="max-width:600px">
        <!-- Avatar -->
        <div class="w3-center">
            <img src="../img/avatar/Female_Avatar_4.png" alt="Avatar" style="width:30%" class="w3-circle w3-margin-top">
        </div>
        <!-- Error banners -->
        <?php if($error == 1){ ?>
            <div class="w3-container">
                <div class="w3-panel w3-red w3-display-container">
                    <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>  
                    <h3>Error!</h3>
                    <p>Invalid user.</p>
                </div>
            </div>
        <?php } ?>
        <form action="../login.php" method="post">
            <div class="w3-section">
                <label><b>Username</b></label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="Introduza utilizador" name="loginUsername" required>
                <label><b>Password</b></label>
                <input class="w3-input w3-border" type="password" placeholder="Introduza password" name="loginPassword" required>
                <button class="w3-button w3-block w3-green w3-section w3-padding" type="submit">Login</button>
            </div>
        </form>
        <!--<div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
            <button onclick="" type="button" class="w3-button w3-red">Não me lembro da password</button>
        </div>-->
    </div>
<!-- End Page Content -->
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Bottom.php" ?>