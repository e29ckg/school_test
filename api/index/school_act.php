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
    $school = $data->school;
    // $id = time();
    
    try{
        if($school->act == 'insert'){
            if($school->sch_name == ''){
                http_response_code(200);
                echo json_encode(array('status' => false, 'message' => 'no name'));
                exit();
            }
            $award = 'รอผล';
            
            $sql = "INSERT INTO schools(sch_no, sch_name, award) 
                    VALUE(:sch_no, :sch_name, :award);";        
            $query = $conn->prepare($sql);
            $query->bindParam(':sch_no',$school->sch_no, PDO::PARAM_INT);
            $query->bindParam(':sch_name',$school->sch_name, PDO::PARAM_STR);   
            $query->bindParam(':award',$award, PDO::PARAM_STR);   
            $query->execute();            

            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'ok'));
            exit();                
        } 

        if($school->act == 'update'){
            $sch_id      = $school->sch_id;
            $sch_no    = $school->sch_no;
            $sch_name    = $school->sch_name;
            $award    = $school->award;

            $sql = "UPDATE schools 
                    SET sch_no =:sch_no, 
                        sch_name = :sch_name,                   
                        award = :award                   
                    WHERE sch_id = :sch_id";   

            $query = $conn->prepare($sql);
            $query->bindParam(':sch_no',$sch_no, PDO::PARAM_STR);
            $query->bindParam(':sch_name',$sch_name, PDO::PARAM_STR);
            $query->bindParam(':award',$award, PDO::PARAM_STR);
            $query->bindParam(':sch_id',$sch_id, PDO::PARAM_STR);
            $query->execute();    

            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'ok', 'responseJSON' => $school));
            exit();                
        }  
              
         
        if($school->act == 'del'){
            $sch_id  = $school->sch_id;
            
            $sql = "DELETE FROM schools WHERE sch_id = $sch_id";
            $conn->exec($sql);
            $sql = "DELETE FROM students WHERE sch_id = $sch_id";
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



