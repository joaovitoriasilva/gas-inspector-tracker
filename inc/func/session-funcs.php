<?php 
    /* ************************************************************************** */
    /* Session info                                                                  */
    /* ************************************************************************** */

    /* Check if a user is logged */
    function isLogged(){
        if (isset($_SESSION["id"])){
            if ($_SESSION["id"]>=0){
                return TRUE;
            }
        }
        return FALSE;
    }

    /* Do a login */
    function loginUser($username, $password){
        global $mydb;
        $query = "SELECT id FROM users WHERE (username=?) and (password=?)";
        $stmt = $mydb->prepare($query);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();       
        $stmt->bind_result($userID);
        if (!$stmt->fetch()){
            $userID = -1;
        }
        $stmt->close();    
        return $userID;
    }

    /* Unset user info */
    function clearUserRelatedInfoSession(){
        unset($_SESSION["id"]);
        unset($_SESSION["username"]);
        unset($_SESSION["name"]);
        unset($_SESSION["type"]);
        unset($_SESSION["gender"]);
        unset($_SESSION["photo_path"]);
    }

    /* Set user info */
    function setUserRelatedInfoSession($userID){
        clearUserRelatedInfoSession();
        if ($userID >= 0)
        {
        global $mydb;
        $query = "SELECT id, name, username, type, gender, photo_path FROM users WHERE (id = ?)";
        $stmt = $mydb->prepare($query);
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->bind_result($id, $name, $username, $type, $gender, $photo_path);
        if ($stmt->fetch())
            {
                $_SESSION["id"] = $id;
                $_SESSION["username"] = $username;
                $_SESSION["name"] = $name;
                $_SESSION["type"] = $type;
                $_SESSION["gender"] = $gender;
                $_SESSION["photo_path"] = $photo_path;
            }
        $stmt->close();		
        }
    }
?>