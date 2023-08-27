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
$datas = array();

// The request is using the POST method

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        
    $school = $data->school;
    $student = $data->student;
    
    try{
        if($student->act == 'get_all'){
            $sql = "SELECT *
            FROM students 
            WHERE sch_id = :sch_id
            ORDER BY std_no ASC";
        $query = $conn->prepare($sql);
        $query->bindParam(':sch_id',$school->sch_id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        if($query->rowCount() > 0){ 
        foreach($result as $rs){                
                array_push($datas,array(
                    'std_id' => $rs->std_id,
                    'sch_id' => $rs->sch_id,
                    'std_no'   => $rs->std_no,
                    'std_name'   => $rs->std_name,
                    'std_st'     => $rs->std_st
                    ));                
            }

            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'สำเร็จ', 'data' => $datas));
            exit();
        }
        http_response_code(200);
        echo json_encode(array('status' => false, 'message' => 'ไม่พบข้อมูล ','data' => []));
        exit();

        }


        if($student->act == 'insert'){

            if($student->std_name == ''){
                http_response_code(200);
                echo json_encode(array('status' => false, 'message' => 'no name'));
                exit();
            }
            $created_at = date("Y-m-d h:i:s");
            
            $sql = "INSERT INTO students(sch_id, std_name, std_st, std_no) 
                    VALUE(:sch_id, :std_name, :std_st, :std_no);";        
            $query = $conn->prepare($sql);
            $query->bindParam(':sch_id',$school->sch_id, PDO::PARAM_INT);
            $query->bindParam(':std_name',$student->std_name, PDO::PARAM_STR);
            $query->bindParam(':std_st',$student->std_st, PDO::PARAM_STR);   
            $query->bindParam(':std_no',$student->std_no, PDO::PARAM_INT);   
            $query->execute();            

            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'ok'));
            exit();                
        } 

        if($student->act == 'update'){
            $std_id     = $student->std_id;
            $sch_id     = $student->sch_id;
            $std_name    = $student->std_name;
            $std_st    = $student->std_st;
            $std_no    = $student->std_no;

            $sql = "UPDATE students 
                    SET sch_id =:sch_id, 
                        std_name = :std_name,                   
                        std_st = :std_st,                   
                        std_no = :std_no                   
                    WHERE std_id = :std_id";   

            $query = $conn->prepare($sql);
            $query->bindParam(':sch_id',$sch_id, PDO::PARAM_INT);
            $query->bindParam(':std_name',$std_name, PDO::PARAM_STR);
            $query->bindParam(':std_st',$std_st, PDO::PARAM_STR);
            $query->bindParam(':std_no',$std_no, PDO::PARAM_INT);
            $query->bindParam(':std_id',$std_id, PDO::PARAM_STR);
            $query->execute();    

            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'ok', 'responseJSON' => $student));
            exit();                
        }  
              
         
        if($student->act == 'del'){
            $std_id  = $student->std_id;
            
            $sql = "DELETE FROM students WHERE std_id = $std_id";
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



