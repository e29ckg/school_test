<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Content-Type, Accept");
header("Content-Type: application/json; charset=utf-8");

include '../../vendor/autoload.php';
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;

$data = json_decode(file_get_contents("php://input"));

// The request is using the POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $school = $data->school;
    $student = $data->student;
    
    $datas = array();

    try{
        $name = $student->std_name;
        $school_name = $school->sch_name;
        $award = $school->award;

        if($award == 'รอผล'){
            http_response_code(200);
            echo json_encode(array(
                'status' => false, 
                'message' => 'รอผการแข่งขัน',  
                
            ));
            exit;
        }
        
            /**สร้างเอกสาร docx */
            $name_doc = './tm_non.docx';

            if($student->std_st == 'อาจารย์' && $school->award != ''){
                $name_doc = './tm_award_aj.docx';
            }
            if($student->std_st == 'อาจารย์' && $school->award == ''){
                $name_doc = './tm_non_aj.docx';
            }

            if($student->std_st == 'ผู้เข้าแข่งขัน' && $school->award != ''){
                $name_doc = './tm_award.docx';
            }

            if($student->std_st == 'ผู้เข้าแข่งขัน' && $school->award == ''){
                $name_doc = './tm_non.docx';
                $award = '';
            }
            if($student->std_st == ''){
                $name_doc = './tm_non.docx';
                $award = '';
            }


            $templateProcessor = new TemplateProcessor($name_doc);//เลือกไฟล์ template ที่เราสร้างไว้
            $templateProcessor->setValue('name', $name);//อัดตัวแปร รายตัว
            $templateProcessor->setValue('school', $school_name);//อัดตัวแปร รายตัว
            $templateProcessor->setValue('award', $award);//อัดตัวแปร รายตัว

            $templateProcessor->saveAs('./cert.docx');//สั่งให้บันทึกข้อมูลลงไฟล์ใหม่
           
            http_response_code(200);
            echo json_encode(array(
                'status' => true, 
                'message' => 'OK', 
                'url' => './service/word/cert.docx', 
                
            ));
            exit;
       
    
    }catch(PDOException $e){
        http_response_code(400);
        echo json_encode(array('status' => false, 'message' => 'เกิดข้อผิดพลาด..' . $e->getMessage()));
        exit;
    }
}


