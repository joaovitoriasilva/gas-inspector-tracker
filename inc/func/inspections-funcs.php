<?php 
    /* ************************************************************************** */
    /* Inspections                                                                    */
    /* ************************************************************************** */
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

    /* Get number of inspections for specific user*/
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

            $query="SELECT * FROM inspections WHERE user_id=? and data_prox_inspecao between curdate() and curdate() + INTERVAL '7' DAY order by data_prox_inspecao LIMIT $offset, $no_of_records_per_page";
            $stmt=$mydb->prepare($query);
            $stmt->bind_param('i',$userID);
            if ($stmt->execute()) {
                $stmt->bind_result($id,$user_id,$client_id,$data_inspecao,$data_prox_inspecao,$descricao,$notas);
                while ($stmt->fetch()){
                    $result[] = array( 
                        "id" => $id,
                        "user_id" => $user_id,
                        "client_id" => $client_id,
                        "data_inspecao" => $data_inspecao,
                        "data_prox_inspecao" => $data_prox_inspecao,
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
        $query = "SELECT count(*) FROM inspections WHERE user_id=? and data_prox_inspecao between curdate() and curdate() + INTERVAL '7' DAY order by data_prox_inspecao";
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

            $query="SELECT * FROM inspections WHERE user_id=? and data_prox_inspecao between curdate() + INTERVAL '7' DAY and curdate() + INTERVAL '1' MONTH order by data_prox_inspecao LIMIT $offset, $no_of_records_per_page";
            $stmt=$mydb->prepare($query);
            $stmt->bind_param('i',$userID);
            if ($stmt->execute()) {
                $stmt->bind_result($id,$user_id,$client_id,$data_inspecao,$data_prox_inspecao,$descricao,$notas);
                while ($stmt->fetch()){
                    $result[] = array( 
                        "id" => $id,
                        "user_id" => $user_id,
                        "client_id" => $client_id,
                        "data_inspecao" => $data_inspecao,
                        "data_prox_inspecao" => $data_prox_inspecao,
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
        $query = "SELECT count(*) FROM inspections WHERE user_id=? and data_prox_inspecao between curdate() + INTERVAL '7' DAY and curdate() + INTERVAL '1' MONTH order by data_prox_inspecao";
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

            $query="SELECT * FROM inspections WHERE user_id=? and client_id=? order by data_prox_inspecao LIMIT $offset, $no_of_records_per_page";
            $stmt=$mydb->prepare($query);
            $stmt->bind_param('ii',$userID,$clientID);
            if ($stmt->execute()) {
                $stmt->bind_result($id,$user_id,$client_id,$data_inspecao,$data_prox_inspecao,$descricao,$notas);
                while ($stmt->fetch()){
                    $result[] = array( 
                        "id" => $id,
                        "user_id" => $user_id,
                        "client_id" => $client_id,
                        "data_inspecao" => $data_inspecao,
                        "data_prox_inspecao" => $data_prox_inspecao,
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

?>