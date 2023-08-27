<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json; charset=utf-8");

require_once '../../vendor/autoload.php';

$data = json_decode(file_get_contents("php://input"));


// $files = glob('../../output/preview/*'); 

// // Deleting all the files in the list
// foreach($files as $file) {   
//     if(is_file($file))     
//     unlink($file); 
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $school = $data->school;
    $student = $data->student;

    $name = $student->std_name;
    $school_name = $school->sch_name;
    $award = $school->award;

    $tm = './tm_award.pdf';

    /**สร้างเอกสาร docx */
    $tm = './tm_award.pdf';

    if($student->std_st == 'อาจารย์' && $school->award != ''){
        $tm = './tm_award_aj.pdf';
    }
    if($student->std_st == 'อาจารย์' && $school->award == ''){
        $tm = './tm_aj.pdf';
    }

    if($student->std_st == 'ผู้เข้าแข่งขัน' && $school->award != ''){
        $tm = './tm_award.pdf';
    }

    if($student->std_st == 'ผู้เข้าแข่งขัน' && $school->award == ''){
        $tm = './tm_non.pdf';
    } 

        
    
    // $text_font = 'thsarabun';
    $text_font = 'charmonman';
    $text_size = 24;
    $name_text_align = 'center';
    $name_x = 0;
    $text_y = 69;
        
    $name_file = 'preview.pdf';
    $output = $name_file;
    
    $mpdf = new \Mpdf\Mpdf();
    
    $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];
    
    $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];
    
    // var_dump($fontData);
    
    $mpdf = new \Mpdf\Mpdf([
    'fontDir' => array_merge($fontDirs, [
        '../../fonts',
    ]),
    'fontdata' => $fontData + [
        'thsarabun' => [
            'R' => 'Sarabun-Regular.ttf',
            //'I' => 'THSarabunNew Italic.ttf',
            //'B' => 'THSarabunNew Bold.ttf',
            'useOTL' => 0xFF,
			// 'useKashida' => 75
        ],
        'prompt' => [
            'R' => 'Prompt.ttf',
            'B' => 'Prompt-Bold.ttf',
            'useOTL' => 0xFF,
			// 'useKashida' => 75
            
        ],
        'jschulee' => [
            'R' => 'Jschulee.ttf',
            //'I' => 'THSarabunNew Italic.ttf',
            //'B' => 'THSarabunNew Bold.ttf',
            // 'useOTL' => 0xFF,
			// 'useKashida' => 75
        ],
        'charmonman' => [
            'R' => 'Charmonman-Regular.ttf',
            //'I' => 'THSarabunNew Italic.ttf',
            //'B' => 'THSarabunNew Bold.ttf',
            // 'useOTL' => 0xFF,
			// 'useKashida' => 75
        ],
        'charm' => [
            'R' => 'Charm-Regular.ttf',
            'B' => 'Charm-Bold.ttf',
            'useOTL' => 0xFF,
			// 'useKashida' => 75
        ]
    ],
    'mode' => 'utf-8', 
    'format' => 'A4',
    'orientation' => 'L',
    'default_font' => 'charm',
    // 'default_font_size' => 8,
    // 'format' => [235, 108],    
    // 'default_font' => 'kanit',
    // 'default_font' => 'NotoSerifThai',
    // 'default_font' => $text_font,
    'default_font_size' => $text_size
]);
    $mpdf->useDictionaryLBR = false;
    // $mpdf->useKashida = true;
    // $mpdf->useOTL = true;
    
    $mpdf->SetTitle('pkkjc-cert');
    $mpdf->SetAuthor('pkkjc');
    $mpdf->SetSubject('pkkjc-cert');
    $mpdf->SetCreator('pkkjc.coj');
    $mpdf->SetKeywords('pkkjc');
    
    $mpdf->AddPage();    
    
    
    // $pagecount = $mpdf->setSourceFile('tm.pdf');
    // $pagecount = $mpdf->setSourceFile($tm);
    $pagecount = $mpdf->setSourceFile($tm);
    $tplId = $mpdf->importPage($pagecount);
    
    $actualsize = $mpdf->useTemplate($tplId);
    $mpdf->WriteHTML('ปี ศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์ เนตรนภาเกิดเปี่ยม');
       
    $data_text = '<div style="text-align:center; background-color:powderblue;">'.'ภาษาไทย'.'</div>';
    $mpdf->WriteFixedPosHTML($data_text, 10, 75, 280, 20, 'auto');

    


      
    $mpdf->Output($output);
    
    // $link_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
    $link_url = './service/mpdf/api/index/'.$name_file;
    
    http_response_code(200);
    echo json_encode(array(
        'status' => true, 
        'massege' => 'สำเร็จ', 
        'url' => $link_url,
        'data' => $data
    ));
    exit;

}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // $school = $data->school;
    // $student = $data->student;

    $tm = './tm_award.pdf';
       
    
    // $text_font = 'thsarabun';
    $text_font = 'charmonman';
    $text_size = 24;
    $name_text_align = 'center';
    $name_x = 0;
    $text_y = 69;
        
    $name_file = 'preview.pdf';
    $output = $name_file;
    
    $mpdf = new \Mpdf\Mpdf();
    
    $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];
    
    $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];
    
    // var_dump($fontData);
    
    $mpdf = new \Mpdf\Mpdf([
    'fontDir' => array_merge($fontDirs, [
        '../../fonts',
    ]),
    'fontdata' => $fontData + [
        'thsarabun' => [
            'R' => 'Sarabun-Regular.ttf',
            //'I' => 'THSarabunNew Italic.ttf',
            //'B' => 'THSarabunNew Bold.ttf',
            'useOTL' => 0xFF,
			// 'useKashida' => 75
        ],
        'prompt' => [
            'R' => 'Prompt.ttf',
            'B' => 'Prompt-Bold.ttf',
            'useOTL' => 0xFF,
			// 'useKashida' => 75
            
        ],
        'jschulee' => [
            'R' => 'Jschulee.ttf',
            //'I' => 'THSarabunNew Italic.ttf',
            //'B' => 'THSarabunNew Bold.ttf',
            // 'useOTL' => 0xFF,
			// 'useKashida' => 75
        ],
        'charmonman' => [
            'R' => 'Charmonman-Regular.ttf',
            //'I' => 'THSarabunNew Italic.ttf',
            //'B' => 'THSarabunNew Bold.ttf',
            // 'useOTL' => 0xFF,
			// 'useKashida' => 75
        ],
        'srisakdi' => [
            'R' => 'Srisakdi-Regular.ttf',
            //'I' => 'THSarabunNew Italic.ttf',
            //'B' => 'THSarabunNew Bold.ttf',
            'useOTL' => 0xFF,
			// 'useKashida' => 75
        ],
        'yodthida' => [
            'R' => 'Yodthida-Regular.ttf',
            //'I' => 'THSarabunNew Italic.ttf',
            //'B' => 'THSarabunNew Bold.ttf',
            // 'useOTL' => 0xFF,
			// 'useKashida' => 75
        ],
        'charm' => [
            'R' => 'Charm-Regular.ttf',
            'B' => 'Charm-Bold.ttf',
            'useOTL' => 0xFF,
			// 'useKashida' => 75
        ]
    ],
    'mode' => 'utf-8', 
    'format' => 'A4',
    'orientation' => 'L',
    'default_font' => 'jschulee',
    // 'default_font_size' => 8,
    // 'format' => [235, 108],    
    // 'default_font' => 'kanit',
    // 'default_font' => 'NotoSerifThai',
    // 'default_font' => $text_font,
    'default_font_size' => $text_size
]);
    $mpdf->useDictionaryLBR = false;
    // $mpdf->useKashida = true;
    // $mpdf->useOTL = true;
    
    $mpdf->SetTitle('pkkjc-cert');
    $mpdf->SetAuthor('pkkjc');
    $mpdf->SetSubject('pkkjc-cert');
    $mpdf->SetCreator('pkkjc.coj');
    $mpdf->SetKeywords('pkkjc');
    
    $mpdf->AddPage();    
    
    
    // $pagecount = $mpdf->setSourceFile('tm.pdf');
    // $pagecount = $mpdf->setSourceFile($tm);
    $pagecount = $mpdf->setSourceFile($tm);
    $tplId = $mpdf->importPage($pagecount);
    
    $actualsize = $mpdf->useTemplate($tplId);
       
    $data_text = '<div style="text-align:center; background-color:powderblue;">'.$name .'</div>';
    $mpdf->WriteFixedPosHTML($data_text, 10, 82, 280, 20, 'auto');

    $data_text = '<div style="text-align:center; background-color:powderblue;">'.$school_name.'</div>';
    $mpdf->WriteFixedPosHTML($data_text, 10, 95, 280, 20, 'auto');

    $data_text = '<div style="text-align:center; background-color:powderblue;">'.$award.'</div>';
    $mpdf->WriteFixedPosHTML($data_text, 10, 100, 280, 20, 'auto');

    


      
    $mpdf->Output($output);
    
    // $link_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
    $link_url = './service/mpdf/api/index/'.$name_file;
    
    http_response_code(200);
    echo json_encode(array(
        'status' => true, 
        'massege' => 'สำเร็จ', 
        'url' => $link_url,
        'data' => $data
    ));
    exit;

}

function DateThai_full($strDate)
{
    if($strDate == ''){
        return "-";
    }
    $strYear = date("Y",strtotime($strDate))+543;
    $strMonth= date("n",strtotime($strDate));
    $strDay= date("j",strtotime($strDate));
    $strHour= date("H",strtotime($strDate));
    $strMinute= date("i",strtotime($strDate));
    $strSeconds= date("s",strtotime($strDate));
    $strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม",
                        "สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
    $strMonthThai=$strMonthCut[$strMonth];
    return "$strDay $strMonthThai $strYear";
}
function DateThai_ft($strDate)
{
    if($strDate == ''){
        return "-";
    }
    $strYear = date("Y",strtotime($strDate))+543;
    $strMonth= date("n",strtotime($strDate));
    $strDay= date("j",strtotime($strDate));
    $strHour= date("H",strtotime($strDate));
    $strMinute= date("i",strtotime($strDate));
    $strSeconds= date("s",strtotime($strDate));
    $strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม",
                        "สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
    $strMonthThai=$strMonthCut[$strMonth];
    return "เวลา $strHour:$strMinute ";
}

function DateThai_M($strDate)
{
    if($strDate == ''){
        return "-";
    }
    $strMonth= date("n",strtotime($strDate));
    $strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม",
                        "สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
    $strMonthThai=$strMonthCut[$strMonth];
    return "$strMonthThai";
}
?>
