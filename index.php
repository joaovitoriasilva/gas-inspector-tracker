<?php
    if(!isset($_SESSION)){
        session_start();
    }
        
    require_once $_SERVER['DOCUMENT_ROOT']."/inc/Funcoes-sql.php"; 

	$page="index";

    if(!isLogged()){
        header("Location: ../login.php");
    }


?>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Top.php" ?>

<!-- Page Container -->
<div class="w3-container w3-content" style="max-width:1400px;margin-top:80px">
    <div class="w3-content" style="max-width:600px">
        <?php if($_GET["editclientaccess"] == -1){ ?>
            <div class="w3-panel w3-red w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-large w3-display-topright">&times;</span>
                <h3><i class="fas fa-hand-paper"></i> Erro</h3>
                <?php if($_GET["editclientaccess"] == -1){ ?>
                    <p>Não tem privilégios para editar cliente (-1).</p>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
<!-- End Page Container -->
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT']."/inc/Template-Bottom.php" ?>
