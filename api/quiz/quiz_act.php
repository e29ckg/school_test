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
    $quiz = $data->quiz;
    // $id = time();
    
    try{
        if($quiz->act == 'insert'){
            if($quiz->q_name == ''){
                http_response_code(200);
                echo json_encode(array('status' => false, 'message' => 'no name'));
                exit();
            }
            $created_at = date("Y-m-d h:i:s");
            
            $sql = "INSERT INTO quiz(q_num, q_name) 
                    VALUE(:q_num, :q_name);";        
            $query = $conn->prepare($sql);
            $query->bindParam(':q_num',$quiz->q_num, PDO::PARAM_INT);
            $query->bindParam(':q_name',$quiz->q_name, PDO::PARAM_STR);   
            $query->execute();            

            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'ok'));
            exit();                
        } 

        if($quiz->act == 'update'){
            $q_id      = $quiz->q_id;
            $q_num    = $quiz->q_num;
            $q_name    = $quiz->q_name;

            $sql = "UPDATE quiz 
                    SET q_num =:q_num, 
                        q_name = :q_name                
                    WHERE q_id = :q_id";   

            $query = $conn->prepare($sql);
            $query->bindParam(':q_num',$q_num, PDO::PARAM_INT);
            $query->bindParam(':q_name',$q_name, PDO::PARAM_STR);
            $query->bindParam(':q_id',$q_id, PDO::PARAM_INT);
            $query->execute();    

            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'ok', 'responseJSON' => $quiz));
            exit();                
        }  
              
         
        if($quiz->act == 'del'){
            $q_id  = $quiz->q_id;
            
            $sql = "DELETE FROM quiz WHERE q_id = $q_id";
            $conn->exec($sql);
            $sql = "DELETE FROM quiz_anser WHERE q_id = $q_id";
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



