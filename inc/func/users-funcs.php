<?php 
    /* ************************************************************************** */
    /* Users                                                                      */
    /* ************************************************************************** */
    /* Get all users */
    function getUsers(){
        global $mydb;
        $number=numUsers();
        $result = array();
        if($number != -1){
            $query="SELECT id, name, username, type, photo_path, photo_path_aux type FROM users";
            $stmt=$mydb->prepare($query);
            if ($stmt->execute()) {
                $stmt->bind_result($id,$nome,$username,$tipo,$photo_path,$photo_path_aux);
                while ($stmt->fetch()){
                    $result[] = array( 
                        "id" => $id,
                        "name" => $nome,
                        "username" => $username,
                        "type" => $tipo,
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

    /* Get number of users */
    function numUsers(){
        global $mydb;
        $query = "SELECT count(*) FROM users";
        $stmt = $mydb->prepare($query);
        $stmt->execute();       
        $stmt->bind_result($number);
        if (!$stmt->fetch()){
            $number = -1;
        }
        $stmt->close();
        return $number;
    }

    /* Get last ID of users */
    function lastIdUsers(){
        global $mydb;
        $query = "SELECT max(id) FROM users";
        $stmt = $mydb->prepare($query);
        $stmt->execute();       
        $stmt->bind_result($number);
        if (!$stmt->fetch()){
            $number = -1;
        }
        $stmt->close();
        return $number;
    }

    /* Get user from username */
    function getUserFromUsername($usernameUser){
        global $mydb;
        $number=numUsers();
        $result = array();
        if($number != -1){
            $query="SELECT id, name, username, type, photo_path, photo_path_aux FROM users WHERE username=?";
            $stmt=$mydb->prepare($query);
            $stmt->bind_param('s',$usernameUser);
            if ($stmt->execute()) {
                $stmt->bind_result($id,$nome,$username,$tipo,$photo_path,$photo_path_aux);
                while ($stmt->fetch()){
                    $result[] = array( 
                        "id" => $id,
                        "name" => $nome,
                        "username" => $username,
                        "type" => $tipo,
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
            return $number;
        }
        return $result;
    }

    /* Get user from id */
    function getUserFromID($id){
        global $mydb;
        $number=numUsers();
        $result = array();
        if($number != -1){
            $query="SELECT id, name, username, type, photo_path, photo_path_aux FROM users WHERE id=?";
            $stmt=$mydb->prepare($query);
            $stmt->bind_param('i',$id);
            if ($stmt->execute()) {
                $stmt->bind_result($id,$nome,$username,$tipo,$photo_path,$photo_path_aux);
                while ($stmt->fetch()){
                    $result[] = array( 
                        "id" => $id,
                        "name" => $nome,
                        "username" => $username,
                        "type" => $tipo,
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
            return $number;
        }
        return $result;
    }

    /* Get user ID from username */
    function getUserIDFromUsername($usernameUser){
        global $mydb;
        $number=numUsers();
        if($number != -1){
            $query="SELECT id FROM users WHERE username=?";
            $stmt=$mydb->prepare($query);
            $stmt->bind_param('s',$usernameUser);
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

    /* Get user photo from ID */
    function getUserPhotoFromID($id){
        global $mydb;
        $number=numUsers();
        if($number != -1){
            $query="SELECT photo_path FROM users WHERE id=?";
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

    /* Get user photo path aux from ID */
    function getUserPhotoAuxFromID($id){
        global $mydb;
        $number=numUsers();
        if($number != -1){
            $query="SELECT photo_path_aux FROM users WHERE id=?";
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

    /* Creates a new user */
    function newUser($nome, $username, $password, $tipo, $photo_path, $photo_path_aux){
        global $mydb;
        if(getUserFromUsername($username) != -3){
            return -3;
        }
        $number=lastIdUsers();
        if($number != -1){
            $number=$number+1;
            $query = "INSERT INTO users(id,name,username,password,type,photo_path,photo_path_aux) VALUES (?,?,?,?,?,?,?)";
            $stmt = $mydb->prepare($query);
            $hashPassword = hash("sha256",$password);
            $stmt->bind_param("isssiss",$number, $nome, $username, $hashPassword, $tipo, $photo_path, $photo_path_aux);
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

    /* Edit user */
    function editUser($nome, $username, $id, $tipo, $photo_path, $photo_path_aux){
        global $mydb;
        $number=numUsers();
        if($number != -1){
            $query = "UPDATE users SET name = ?, username = ?, type = ?, photo_path = ?, photo_path_aux = ? WHERE id = ?";
            $stmt = $mydb->prepare($query);
            $stmt->bind_param("ssissi",$nome, $username, $tipo, $photo_path, $photo_path_aux, $id);
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

    /* Unset user photo */
    function unsetUserPhoto($id){
        global $mydb;
        $number=numUsers();
        if($number != -1){
            $query = "UPDATE users SET photo_path = NULL and photo_path_aux = NULL WHERE id = ?";
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

    /* Deletes a user based on its ID */
    function deleteUser($id){
        global $mydb;
        $number=numUsers();
        if($number != -1){
            $query = "DELETE FROM users WHERE id = ?";
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
?>