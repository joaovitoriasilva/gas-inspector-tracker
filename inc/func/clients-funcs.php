<?php 
    /* ************************************************************************** */
    /* Clients                                                                    */
    /* ************************************************************************** */
    /* Get number of clients */
    function numClients(){
        global $mydb;
        $query = "SELECT count(*) FROM clients";
        $stmt = $mydb->prepare($query);
        $stmt->execute();       
        $stmt->bind_result($number);
        if (!$stmt->fetch()){
            $number = -1;
        }
        $stmt->close();
        return $number;
    }

    /* Get all clients */
    function getClients(){
        global $mydb;
        $number = numClients();
        $result = array();
        if($number != -1){
            $query="SELECT * FROM clients";
            $stmt=$mydb->prepare($query);
            if ($stmt->execute()) {
                $stmt->bind_result($id,$user_id,$nome,$nif,$morada,$telefone,$email,$notas,$photo_path,$photo_path_aux);
                while ($stmt->fetch()){
                    $result[] = array( 
                        "id" => $id,
                        "user_id" => $user_id,
                        "name" => $nome,
                        "nif" => $nif,
                        "address" => $morada,
                        "phone" => $telefone,
                        "email" => $email,
                        "notes" => $notas,
                        "photo_path" => $photo_path,
                        "photo_path_aux" => $photo_path_aux);
                }
                if($stmt->num_rows == 0){
                    $result = -3;
                }
            }else{
                $result = -2;
            }
            $stmt->close();
        }else{
            $result=-1;
        }
        return $result;
    }

    /* Clients pagination */
    function getClientsPagination($pageNumber){
        global $mydb;

        /* Get number of clients */
        $number = numClients();
        $result = array();

        if($number != -1){
            /* php pagination */
            $no_of_records_per_page = 25;
            $offset = ($pageNumber-1) * $no_of_records_per_page;

            /* Get the number of total number of pages */
            $total_pages = ceil($number / $no_of_records_per_page);

            $query = "SELECT * FROM clients LIMIT $offset, $no_of_records_per_page";
            $stmt=$mydb->prepare($query);
            if ($stmt->execute()) {
                $stmt->bind_result($id,$user_id,$nome,$nif,$morada,$telefone,$email,$notas,$photo_path,$photo_path_aux);
                while ($stmt->fetch()){
                    $result[] = array( 
                        "id" => $id,
                        "user_id" => $user_id,
                        "name" => $nome,
                        "nif" => $nif,
                        "address" => $morada,
                        "phone" => $telefone,
                        "email" => $email,
                        "notes" => $notas,
                        "photo_path" => $photo_path,
                        "photo_path_aux" => $photo_path_aux);
                }
                if($stmt->num_rows == 0){
                    $result = -3;
                }
            }else{
                $result = -2;
            }
            $stmt->close();
        }else{
            $result=-1;
        }
        return $result;
    }

    /* Get client from ID */
    function getClientFromId($id){
        global $mydb;
        $number=numClients();
        $result = array();
        if($number != -1){
            $query="SELECT * FROM clients WHERE id=?";
            $stmt=$mydb->prepare($query);
            $stmt->bind_param('i',$id);
            if ($stmt->execute()) {
                $stmt->bind_result($id,$user_id,$nome,$nif,$morada,$telefone,$email,$notas,$photo_path,$photo_path_aux);
                while ($stmt->fetch()){
                    $result[] = array( 
                        "id" => $id,
                        "user_id" => $user_id,
                        "name" => $nome,
                        "nif" => $nif,
                        "address" => $morada,
                        "phone" => $telefone,
                        "email" => $email,
                        "notes" => $notas,
                        "photo_path" => $photo_path,
                        "photo_path_aux" => $photo_path_aux);
                }
                if($stmt->num_rows == 0){
                    $result = -3;
                }
            }else{
                $result = -2;
            }
            $stmt->close();
        }else{
            $result=-1;
        }
        return $result;
    }

    /* Get client ID from NIF */
    function getClientIDFromNif($nifClient){
        global $mydb;
        $number=numClients();
        if($number != -1){
            $query="SELECT id FROM clients WHERE nif=?";
            $stmt=$mydb->prepare($query);
            $stmt->bind_param('i',$nifClient);
            if ($stmt->execute()) {
                $stmt->bind_result($id);
                while ($stmt->fetch()){
                    $result = $id;
                }
                if($stmt->num_rows == 0){
                    $result = -3;
                }
            }else{
                $result = -2;
            }
            $stmt->close();
        }else{
            $result=-1;
        }
        return $result;
    }

    /* Get client ID from name */
    function getClientIDFromName($nameClient){
        global $mydb;
        $number=numClients();
        if($number != -1){
            $aux= '%' . $nameClient . '%';
            $query="SELECT id FROM clients WHERE nome LIKE ?";
            $stmt=$mydb->prepare($query);
            $stmt->bind_param('s',$aux);
            if ($stmt->execute()) {
                $stmt->bind_result($id);
                while ($stmt->fetch()){
                    $result = $id;
                }
                if($stmt->num_rows == 0){
                    $result = -3;
                }
            }else{
                $result = -2;
            }
            $stmt->close();
        }else{
            $result=-1;
        }
        return $result;
    }

    /* Get client ID from contact */
    function getClientIDFromContact($contactClient){
        global $mydb;
        $number=numClients();
        if($number != -1){
            $query="SELECT id FROM clients WHERE telefone=?";
            $stmt=$mydb->prepare($query);
            $stmt->bind_param('i',$contactClient);
            if ($stmt->execute()) {
                $stmt->bind_result($id);
                while ($stmt->fetch()){
                    $result = $id;
                }
                if($stmt->num_rows == 0){
                    $result = -3;
                }
            }else{
                $result = -2;
            }
            $stmt->close();
        }else{
            $result=-1;
        }
        return $result;
    }

    /* Get client photo from ID */
    function getClientPhotoFromID($id){
        global $mydb;
        $number=numClients();
        if($number != -1){
            $query="SELECT photo_path FROM clients WHERE id=?";
            $stmt=$mydb->prepare($query);
            $stmt->bind_param('i',$id);
            if ($stmt->execute()) {
                $stmt->bind_result($photo_path);
                while ($stmt->fetch()){
                    $result = $photo_path;
                }
                if($stmt->num_rows == 0){
                    $result = -3;
                }
            }else{
                $result = -2;
            }
            $stmt->close();
        }else{
            $result=-1;
        }
        return $result;
    }

    /* Get client photo aux path from ID */
    function getClientPhotoAuxPathFromID($id){
        global $mydb;
        $number=numClients();
        if($number != -1){
            $query="SELECT photo_path_aux FROM clients WHERE id=?";
            $stmt=$mydb->prepare($query);
            $stmt->bind_param('i',$id);
            if ($stmt->execute()) {
                $stmt->bind_result($photo_path_aux);
                while ($stmt->fetch()){
                    $result = $photo_path_aux;
                }
                if($stmt->num_rows == 0){
                    $result = -3;
                }
            }else{
                $result = -2;
            }
            $stmt->close();
        }else{
            $result=-1;
        }
        return $result;
    }

    /* Unset client photo */
    function unsetClientPhoto($id){
        global $mydb;
        $number=numClients();
        if($number != -1){
            $query = "UPDATE clients SET photo_path = NULL, photo_path_aux = NULL WHERE id = ?";
            $stmt = $mydb->prepare($query);
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $result = 0;
            }else{
                $result = -2;
            }
            $stmt->close();
        }else{
            return $number;
        }
        return $result;
    }

    /* Edit client */
    function editClient($user_id, $nome, $nif, $morada, $telefone, $email, $id, $notas, $photo_path, $photo_path_aux){
        global $mydb;
        $number=numClients();
        if($number != -1){
            $query = "UPDATE clients SET user_id = ?, nome = ?, nif= ?, morada = ?, telefone = ?, email = ?, notas = ?, photo_path = ?, photo_path_aux = ? WHERE id = ?";
            $stmt = $mydb->prepare($query);
            $stmt->bind_param("isisissssi",$user_id, $nome, $nif, $morada, $telefone, $email, $notas, $photo_path, $photo_path_aux, $id);
            if ($stmt->execute()) {
                $result = 0;
            }else{
                $result = -2;
            }
            $stmt->close();
        }else{
            return $number;
        }
        return $result;
    }

    /* Deletes a client based on its ID */
    function deleteClient($id){
        global $mydb;
        $number=numClients();
        if($number != -1){
            $query = "DELETE FROM clients WHERE id = ?";
            $stmt = $mydb->prepare($query);
            $stmt->bind_param("i",$id);
            if ($stmt->execute()) {
                $result = 0;
            }else{
                $result = -2;
            }
            $stmt->close();
        }else{
            return $number;
        }
        return $result;
    }

    /* Get last ID of clients */
    function lastIdClients(){
        global $mydb;
        $query = "SELECT max(id) FROM clients";
        $stmt = $mydb->prepare($query);
        $stmt->execute();       
        $stmt->bind_result($number);
        if (!$stmt->fetch()){
            $number = -1;
        }
        $stmt->close();
        return $number;
    }

    /* Creates a new client */
    function newClient($nome, $user_id, $nif, $morada, $telefone, $email, $notas, $photo_path, $photo_path_aux){
        global $mydb;
        if(getClientIDFromNif($nif) != -3){
            return -3;
        }
        $number=lastIdClients();
        if($number != -1){
            $number=$number+1;
            $query = "INSERT INTO clients(id,user_id,nome,nif,morada,telefone,email,notas,photo_path,photo_path_aux) VALUES (?,?,?,?,?,?,?,?,?,?)";
            $stmt = $mydb->prepare($query);
            $stmt->bind_param("iisisissss",$number, $user_id, $nome, $nif, $morada, $telefone, $email, $notas,$photo_path, $photo_path_aux);
            if ($stmt->execute()) {
                $result = 0;
            }else{
                $result = -2;
            }
            $stmt->close();
        }else{
            return $number;
        }
        return $result;
    }
?>