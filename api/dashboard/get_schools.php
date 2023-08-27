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
    $order_by = $data->order_by;           
    $res_q = '';

    $sql_q = "SELECT * FROM quiz WHERE q_id=$q_id";
    $query = $conn->prepare($sql_q);
    $query->execute();
    $res_q = $query->fetch(PDO::FETCH_OBJ);
    if($query->rowCount() == 0){
        http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'สำเร็จ', 'data' => $datas, 'quiz'=>$res_q));
            exit();
    }


   
    $sql = "SELECT * FROM schools ORDER BY sch_no ASC";
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

            $sql_qa = "SELECT *
                    FROM quiz_anser 
                    WHERE sch_id=$rs->sch_id 
                        AND q_id=$q_id
                        AND q_no <= $res_q->q_num
                    ORDER BY q_no ASC";
            $query_qa = $conn->prepare($sql_qa);
            $query_qa->execute();
            $res_qa = [];
            if($query_qa->rowCount() > 0){
                $res = $query_qa->fetchAll(PDO::FETCH_OBJ);  
                foreach($res as $r){
                    array_push($res_qa,$r->q_no);
                }                      
            }

            array_push($datas,array(
                'sch_id' => $rs->sch_id,
                'sch_no'   => $rs->sch_no,
                'sch_name' => $rs->sch_name,
                'award'      => $rs->award,
                'pic'      => $img_link,
                'quiz_anser'      => $res_qa,
                'quiz_anser_count'  => count($res_qa)
                
                ));            
            }
            if($order_by == 'คะแนน'){
                usort($datas, "sortByMax");
            }    


            http_response_code(200);
            echo json_encode(array('status' => true, 'message' => 'สำเร็จ', 'data' => $datas, 'quiz'=>$res_q));
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

// Custom comparison function to sort by age
function sortByMax($quiz_anser_count1, $quiz_anser_count2) {
    // return $quiz_anser_count1["quiz_anser_count"] - $quiz_anser_count2["quiz_anser_count"];
    return $quiz_anser_count2["quiz_anser_count"] - $quiz_anser_count1["quiz_anser_count"];
}
function sortBySchool($quiz_anser_count1, $quiz_anser_count2) {

    return $quiz_anser_count1["sch_name"] - $quiz_anser_count2["sch_name"];
}
