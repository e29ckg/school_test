<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=utf-8");


include "../connect.php";
// include "../function.php";

// The request is using the POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$data = json_decode(file_get_contents("php://input"));
$datas = array();

try{
    $q_id = $data->q_id;            
    $q_no = $data->q_no;            

            $sql = "SELECT *
                    FROM schools 
                    ORDER BY sch_no ASC";
            $query = $conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_OBJ);

            if($query->rowCount() > 0){                        //count($result)  for odbc
                
                foreach($result as $rs){     
                    $img_link = './img/school/nopic.png';
                    if ($rs->pic && file_exists('../../img/school/' . $rs->pic)) {
                        $img_link = './img/school/' . $rs->pic;
                    }
                    $ck = 0 ;
                    $qa_id = '';
                    $sql = "SELECT *
                            FROM quiz_anser 
                            WHERE sch_id=$rs->sch_id 
                                AND q_id=$q_id
                                AND q_no = $q_no";
                    $query_qa = $conn->prepare($sql);
                    $query_qa->execute();
                    if($query_qa->rowCount() > 0){
                        $res = $query_qa->fetch(PDO::FETCH_OBJ);
                        $ck = 1 ;
                        $qa_id = $res->qa_id;
                    }

                    array_push($datas,array(
                        'sch_id' => $rs->sch_id,
                        'sch_no'   => $rs->sch_no,
                        'sch_name' => $rs->sch_name,
                        'award'      => $rs->award,
                        'pic'      => $img_link,
                        'ck'      => $ck,
                        'qa_id'      => $qa_id,
                        ));
                    
                }


               
        
                http_response_code(200);
                echo json_encode(array('status' => true, 'message' => 'สำเร็จ', 'data' => $datas));
                exit();
            }
            http_response_code(200);
            echo json_encode(array('status' => false, 'message' => 'ไม่พบข้อมูล ','data' => []));
            exit();
          
    
    }catch(PDOException $e){
        http_response_code(200);
        echo json_encode(array('status' => false, 'message' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
        exit();
    }
}