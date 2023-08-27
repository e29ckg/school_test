<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header("'Access-Control-Allow-Credentials', 'true'");
// header('Content-Type: application/javascript');
header("Content-Type: application/json; charset=utf-8");

include "../connect.php";
// include "../function.php";

$data = json_decode(file_get_contents("php://input"));


// The request is using the POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $datas = array();    
    $quiz_anser = $data->quiz_anser;
    
    try{
        if($quiz_anser->act == 'insert'){
            
            
            $sql = "INSERT INTO quiz_anser(sch_id, q_id, q_no) 
                    VALUE(:sch_id, :q_id, :q_no);";        
            $query = $conn->prepare($sql);
            $query->bindParam(':sch_id',$quiz_anser->sch_id, PDO::PARAM_INT);
            $query->bindParam(':q_id',$quiz_anser->q_id, PDO::PARAM_INT);
            $query->bindParam(':q_no',$quiz_anser->q_no, PDO::PARAM_INT);   
            $query->execute();            

            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'ok'));
            exit();                
        } 

        if($quiz_anser->act == 'update'){
            $q_id      = $quiz_anser->q_id;
            $q_num    = $quiz_anser->q_num;
            $q_name    = $quiz_anser->q_name;

            $sql = "UPDATE quiz_anser 
                    SET q_num =:q_num, 
                        q_name = :q_name                
                    WHERE q_id = :q_id";   

            $query = $conn->prepare($sql);
            $query->bindParam(':q_num',$q_num, PDO::PARAM_INT);
            $query->bindParam(':q_name',$q_name, PDO::PARAM_STR);
            $query->bindParam(':q_id',$q_id, PDO::PARAM_INT);
            $query->execute();    

            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'ok', 'responseJSON' => $quiz_anser));
            exit();                
        }  
              
         
        if($quiz_anser->act == 'del'){
            $qa_id  = $quiz_anser->qa_id;
            
            $sql = "DELETE FROM quiz_anser WHERE qa_id = $qa_id";
            $conn->exec($sql);
            
            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'DEL ok'));
            exit();                
        }

        http_response_code(200);
        echo json_encode(array('status' => false, 'message' => 'ไม่มีการเปลี่ยนแปลง'));
        exit();  
        
        
    }catch(PDOException $e){
        http_response_code(200);
        echo json_encode(array('status' => false, 'message' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
        exit();
    }
}



