<?php 
    /* ************************************************************************** */
    /* Inspections                                                                */
    /* ************************************************************************** */
    /* Get last ID of inspections */
    function lastIdInspections(){
        global $mydb;
        $query = "SELECT max(id) FROM inspections";
        $stmt = $mydb->prepare($query);
        $stmt->execute();       
        $stmt->bind_result($number);
        if (!$stmt->fetch()){
            $number = -1;
        }
        $stmt->close();
        return $number;
    }

    /* Get inspection based on ID */
    function getInspectionFromID($inspectionID){
        global $mydb;
        $number=numInspections();
        $result = array();
        if($number != -1){
            $query="SELECT * FROM inspections WHERE id=?";
            $stmt=$mydb->prepare($query);
            $stmt->bind_param('i',$inspectionID);
            if ($stmt->execute()) {
                $stmt->bind_result($id,$user_id,$client_id,$data_inspecao,$data_limite_prox_inspecao,$descricao,$notas);
                while ($stmt->fetch()){
                    $result[] = array( 
                        "id" => $id,
                        "user_id" => $user_id,
                        "client_id" => $client_id,
                        "data_inspecao" => $data_inspecao,
                        "data_limite_prox_inspecao" => $data_limite_prox_inspecao,
                        "descricao" => $descricao,
                        "notas" => $notas);
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

    /* Get number of inspections */
    function numInspections(){
        global $mydb;
        $query = "SELECT count(*) FROM inspections";
        $stmt = $mydb->prepare($query);
        $stmt->execute();       
        $stmt->bind_result($number);
        if (!$stmt->fetch()){
            $number = -1;
        }
        $stmt->close();
        return $number;
    }

    /* Inspections pagination */
    function getInspectionsPagination($pageNumber){
        global $mydb;

        /* Get number of clients */
        $number = numInspections();
        $result = array();

        if($number != -1){
            /* php pagination */
            $no_of_records_per_page = 25;
            $offset = ($pageNumber-1) * $no_of_records_per_page;

            /* Get the number of total number of pages */
            $total_pages = ceil($number / $no_of_records_per_page);

            $query = "SELECT * FROM inspections order by data_limite_prox_inspecao DESC LIMIT $offset, $no_of_records_per_page";
            $stmt=$mydb->prepare($query);
            if ($stmt->execute()) {
                $stmt->bind_result($id,$user_id,$client_id,$data_inspecao,$data_limite_prox_inspecao,$descricao,$notas);
                while ($stmt->fetch()){
                    $result[] = array( 
                        "id" => $id,
                        "user_id" => $user_id,
                        "client_id" => $client_id,
                        "data_inspecao" => $data_inspecao,
                        "data_limite_prox_inspecao" => $data_limite_prox_inspecao,
                        "descricao" => $descricao,
                        "notas" => $notas);
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

    /* Get number of inspections created by specific user*/
    function numInspectionsUser($userID){
        global $mydb;
        $query = "SELECT count(*) FROM inspections WHERE user_id=?";
        $stmt = $mydb->prepare($query);
        $stmt->bind_param('i',$userID);
        $stmt->execute();       
        $stmt->bind_result($number);
        if (!$stmt->fetch()){
            $number = -1;
        }
        $stmt->close();
        return $number;
    }

    /* Get number of inspections for specific client*/
    function numInspectionsClient($clientID){
        global $mydb;
        $query = "SELECT count(*) FROM inspections WHERE client_id=?";
        $stmt = $mydb->prepare($query);
        $stmt->bind_param('i',$clientID);
        $stmt->execute();       
        $stmt->bind_result($number);
        if (!$stmt->fetch()){
            $number = -1;
        }
        $stmt->close();
        return $number;
    }

    /* Get user next week inspections */
    function getNextWeekInspections($pageNumber){
        global $mydb;
        $number=numInspections();
        $result = array();
        if($number != -1){
            /* php pagination */
            $no_of_records_per_page = 25;
            $offset = ($pageNumber-1) * $no_of_records_per_page;

            /* Get the number of total number of pages */
            $total_pages = ceil($number / $no_of_records_per_page);

            $query="SELECT * FROM inspections WHERE data_limite_prox_inspecao between curdate() and curdate() + INTERVAL '7' DAY order by data_limite_prox_inspecao DESC LIMIT $offset, $no_of_records_per_page";
            $stmt=$mydb->prepare($query);
            if ($stmt->execute()) {
                $stmt->bind_result($id,$user_id,$client_id,$data_inspecao,$data_limite_prox_inspecao,$descricao,$notas);
                while ($stmt->fetch()){
                    $result[] = array( 
                        "id" => $id,
                        "user_id" => $user_id,
                        "client_id" => $client_id,
                        "data_inspecao" => $data_inspecao,
                        "data_limite_prox_inspecao" => $data_limite_prox_inspecao,
                        "descricao" => $descricao,
                        "notas" => $notas);
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

    /* Get number of inspections for next week*/
    function getNumNextWeekInspections(){
        global $mydb;
        $query = "SELECT count(*) FROM inspections WHERE data_limite_prox_inspecao between curdate() and curdate() + INTERVAL '7' DAY order by data_limite_prox_inspecao DESC";
        $stmt = $mydb->prepare($query);
        $stmt->execute();       
        $stmt->bind_result($number);
        if (!$stmt->fetch()){
            $number = -1;
        }
        $stmt->close();
        return $number;
    }

    /* Get next month inspections */
    function getNextMonthInspections($pageNumber){
        global $mydb;
        $number=numInspections();
        $result = array();
        if($number != -1){
            /* php pagination */
            $no_of_records_per_page = 25;
            $offset = ($pageNumber-1) * $no_of_records_per_page;

            /* Get the number of total number of pages */
            $total_pages = ceil($number / $no_of_records_per_page);

            $query="SELECT * FROM inspections WHERE data_limite_prox_inspecao between curdate() + INTERVAL '7' DAY and curdate() + INTERVAL '1' MONTH order by data_limite_prox_inspecao DESC LIMIT $offset, $no_of_records_per_page";
            $stmt=$mydb->prepare($query);
            if ($stmt->execute()) {
                $stmt->bind_result($id,$user_id,$client_id,$data_inspecao,$data_limite_prox_inspecao,$descricao,$notas);
                while ($stmt->fetch()){
                    $result[] = array( 
                        "id" => $id,
                        "user_id" => $user_id,
                        "client_id" => $client_id,
                        "data_inspecao" => $data_inspecao,
                        "data_limite_prox_inspecao" => $data_limite_prox_inspecao,
                        "descricao" => $descricao,
                        "notas" => $notas);
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

    /* Get number of inspections for next month*/
    function getNumNextMonthInspections(){
        global $mydb;
        $query = "SELECT count(*) FROM inspections WHERE data_limite_prox_inspecao between curdate() + INTERVAL '7' DAY and curdate() + INTERVAL '1' MONTH order by data_limite_prox_inspecao DESC";
        $stmt = $mydb->prepare($query);
        $stmt->execute();       
        $stmt->bind_result($number);
        if (!$stmt->fetch()){
            $number = -1;
        }
        $stmt->close();
        return $number;
    }

    /* Get user next week inspections */
    function getInspectionNextWeekForUser($userID,$pageNumber){
        global $mydb;
        $number=getNumInspectionsUserForNextWeek($userID);
        $result = array();
        if($number != -1){
            /* php pagination */
            $no_of_records_per_page = 25;
            $offset = ($pageNumber-1) * $no_of_records_per_page;

            /* Get the number of total number of pages */
            $total_pages = ceil($number / $no_of_records_per_page);

            $query="SELECT * FROM inspections WHERE user_id=? and data_limite_prox_inspecao between curdate() and curdate() + INTERVAL '7' DAY order by data_limite_prox_inspecao DESC LIMIT $offset, $no_of_records_per_page";
            $stmt=$mydb->prepare($query);
            $stmt->bind_param('i',$userID);
            if ($stmt->execute()) {
                $stmt->bind_result($id,$user_id,$client_id,$data_inspecao,$data_limite_prox_inspecao,$descricao,$notas);
                while ($stmt->fetch()){
                    $result[] = array( 
                        "id" => $id,
                        "user_id" => $user_id,
                        "client_id" => $client_id,
                        "data_inspecao" => $data_inspecao,
                        "data_limite_prox_inspecao" => $data_limite_prox_inspecao,
                        "descricao" => $descricao,
                        "notas" => $notas);
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

    /* Get number of inspections for specific user for next week*/
    function getNumInspectionsUserForNextWeek($userID){
        global $mydb;
        $query = "SELECT count(*) FROM inspections WHERE user_id=? and data_limite_prox_inspecao between curdate() and curdate() + INTERVAL '7' DAY order by data_limite_prox_inspecao DESC";
        $stmt = $mydb->prepare($query);
        $stmt->bind_param('i',$userID);
        $stmt->execute();       
        $stmt->bind_result($number);
        if (!$stmt->fetch()){
            $number = -1;
        }
        $stmt->close();
        return $number;
    }

    /* Get user next month inspections */
    function getInspectionNextMonthForUser($userID,$pageNumber){
        global $mydb;
        $number=getNumInspectionsUserForNextMonth($userID);
        $result = array();
        if($number != -1){
            /* php pagination */
            $no_of_records_per_page = 25;
            $offset = ($pageNumber-1) * $no_of_records_per_page;

            /* Get the number of total number of pages */
            $total_pages = ceil($number / $no_of_records_per_page);

            $query="SELECT * FROM inspections WHERE user_id=? and data_limite_prox_inspecao between curdate() + INTERVAL '7' DAY and curdate() + INTERVAL '1' MONTH order by data_limite_prox_inspecao DESC LIMIT $offset, $no_of_records_per_page";
            $stmt=$mydb->prepare($query);
            $stmt->bind_param('i',$userID);
            if ($stmt->execute()) {
                $stmt->bind_result($id,$user_id,$client_id,$data_inspecao,$data_limite_prox_inspecao,$descricao,$notas);
                while ($stmt->fetch()){
                    $result[] = array( 
                        "id" => $id,
                        "user_id" => $user_id,
                        "client_id" => $client_id,
                        "data_inspecao" => $data_inspecao,
                        "data_limite_prox_inspecao" => $data_limite_prox_inspecao,
                        "descricao" => $descricao,
                        "notas" => $notas);
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

    /* Get number of inspections for specific user for next month*/
    function getNumInspectionsUserForNextMonth($userID){
        global $mydb;
        $query = "SELECT count(*) FROM inspections WHERE user_id=? and data_limite_prox_inspecao between curdate() + INTERVAL '7' DAY and curdate() + INTERVAL '1' MONTH order by data_limite_prox_inspecao DESC";
        $stmt = $mydb->prepare($query);
        $stmt->bind_param('i',$userID);
        $stmt->execute();       
        $stmt->bind_result($number);
        if (!$stmt->fetch()){
            $number = -1;
        }
        $stmt->close();
        return $number;
    }

    /* Get user next month inspections */
    function getInspectionsForClientCreatedByUser($userID,$clientID,$pageNumber){
        global $mydb;
        $number=getNumInspectionsForClientCreatedByUser($userID,$clientID);
        $result = array();
        if($number != -1){
            /* php pagination */
            $no_of_records_per_page = 25;
            $offset = ($pageNumber-1) * $no_of_records_per_page;

            /* Get the number of total number of pages */
            $total_pages = ceil($number / $no_of_records_per_page);

            $query="SELECT * FROM inspections WHERE user_id=? and client_id=? order by data_limite_prox_inspecao DESC LIMIT $offset, $no_of_records_per_page";
            $stmt=$mydb->prepare($query);
            $stmt->bind_param('ii',$userID,$clientID);
            if ($stmt->execute()) {
                $stmt->bind_result($id,$user_id,$client_id,$data_inspecao,$data_limite_prox_inspecao,$descricao,$notas);
                while ($stmt->fetch()){
                    $result[] = array( 
                        "id" => $id,
                        "user_id" => $user_id,
                        "client_id" => $client_id,
                        "data_inspecao" => $data_inspecao,
                        "data_limite_prox_inspecao" => $data_limite_prox_inspecao,
                        "descricao" => $descricao,
                        "notas" => $notas);
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

    /* Get number of inspections for specific user for next month*/
    function getNumInspectionsForClientCreatedByUser($userID,$client_id){
        global $mydb;
        $query = "SELECT count(*) FROM inspections WHERE user_id=? and client_id=?";
        $stmt = $mydb->prepare($query);
        $stmt->bind_param('ii',$userID,$client_id);
        $stmt->execute();       
        $stmt->bind_result($number);
        if (!$stmt->fetch()){
            $number = -1;
        }
        $stmt->close();
        return $number;
    }
    
    /* Get client inspections */
    function getClientInspections($client_id, $pageNumber){
        global $mydb;
        $number=numInspectionsClient($client_id);
        $result = array();
        if($number != -1){
            /* php pagination */
            $no_of_records_per_page = 25;
            $offset = ($pageNumber-1) * $no_of_records_per_page;

            /* Get the number of total number of pages */
            $total_pages = ceil($number / $no_of_records_per_page);

            $query="SELECT * FROM inspections WHERE client_id=? order by data_limite_prox_inspecao DESC LIMIT $offset, $no_of_records_per_page";
            $stmt=$mydb->prepare($query);
            $stmt->bind_param('i',$client_id);
            if ($stmt->execute()) {
                $stmt->bind_result($id,$user_id,$client_id,$data_inspecao,$data_limite_prox_inspecao,$descricao,$notas);
                while ($stmt->fetch()){
                    $result[] = array( 
                        "id" => $id,
                        "user_id" => $user_id,
                        "client_id" => $client_id,
                        "data_inspecao" => $data_inspecao,
                        "data_limite_prox_inspecao" => $data_limite_prox_inspecao,
                        "descricao" => $descricao,
                        "notas" => $notas);
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

    /* Creates a new inspection */
    function newInspection($user_id, $client_id, $data_inspecao, $descricao, $notas){
        global $mydb;
        $number=lastIdInspections();
        $data_limite_prox_inspecao = date('Y-m-d', strtotime(date('Y-m-d', strtotime($data_inspecao)). " + 3 year"));
        if($number != -1){
            $number=$number+1;
            $query = "INSERT INTO inspections(id,user_id,client_id,data_inspecao,data_limite_prox_inspecao,descricao,notas) VALUES (?,?,?,?,?,?,?)";
            $stmt = $mydb->prepare($query);
            $stmt->bind_param("iiissss",$number, $user_id, $client_id, $data_inspecao, $data_limite_prox_inspecao, $descricao, $notas);
            //echo "<script>console.log('Debug Objects: " . $number . " " . $user_id . " " . $client_id . " " . $data_inspecao . " " . $data_limite_prox_inspecao . " " . $descricao . " " . $notas . "' );</script>";
            if ($stmt->execute()) {
                $result = 0;
            }else{
                $result = -2;
            }
            /*echo "<script>console.log('Debug Objects: " . $stmt->error_list . "' );</script>";
            foreach($stmt->error_list as $clientValue){
                foreach($clientValue as $clientValue2){
                    echo "<script>console.log('Debug Objects: " . $clientValue2["error"] . "' );</script>";
                }
            }*/
            print_r($stmt->error_list);
            $stmt->close();
        }else{
            return $number;
        }
        return $result;
    }

    /* Deletes a inspection based on its ID */
    function deleteInspection($id){
        global $mydb;
        $number=numInspections();
        if($number != -1){
            $query = "DELETE FROM inspections WHERE id = ?";
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

     /* Edit inspection */
     function editInspection($id, $data_inspecao, $descricao, $notas){
        global $mydb;
        $number=numInspections();
        $data_limite_prox_inspecao = date('Y-m-d', strtotime(date('Y-m-d', strtotime($data_inspecao)). " + 3 year"));
        if($number != -1){
            $query = "UPDATE inspections SET data_inspecao= ?, data_limite_prox_inspecao = ?, descricao = ?, notas = ? WHERE id = ?";
            $stmt = $mydb->prepare($query);
            $stmt->bind_param("ssssi",$data_inspecao, $data_limite_prox_inspecao, $descricao, $notas, $id);
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