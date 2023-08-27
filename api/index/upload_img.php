<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization");

require_once "../connect.php";
// require_once "../function.php";

$upload_path = '../../img/school/';

$sch_id = $_POST['uid'];
$file = $_FILES['sendimage'];

if (empty($sch_id)) {
    $errorMSG = json_encode(array("message" => "No user", "status" => false));
    echo $errorMSG;
    exit;
}

if (empty($file) || !isset($file['tmp_name'])) {
    $errorMSG = json_encode(array("message" => "Please select an image", "status" => false));
    echo $errorMSG;
    exit;
}

 
    $fileName = $file['name'];
    $tempPath = $file['tmp_name'];
    $fileSize = $file['size'];

    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $validExtensions = array('jpeg', 'jpg', 'png', 'gif');

    if (!in_array($fileExt, $validExtensions)) {
        $errorMSG = json_encode(array("message" => "Sorry, only JPG, JPEG, PNG, and GIF files are allowed", "status" => false));
        echo $errorMSG;
        exit;
    }

    if ($fileSize > 5000000) {
        $errorMSG = json_encode(array("message" => "Sorry, your file is too large, please upload a file up to 5 MB", "status" => false));
        echo $errorMSG;
        exit;
    }

    $fileName = 'school_' . $sch_id . '_' . date("His") . '.' . $fileExt;

    try {
        // ตรวจสอบความถูกต้องของ $sch_id และป้องกันการโจมตีแบบ SQL Injection
        $sql = "SELECT pic FROM schools WHERE sch_id = :sch_id";
        $query = $conn->prepare($sql);
        $query->bindParam(':sch_id', $sch_id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);

        if ($result) {
            $img = $result->pic;
            if ($img != '' && file_exists($upload_path . $img)) {
                unlink($upload_path . $img);
            }
        }

        move_uploaded_file($tempPath, $upload_path . $fileName);

        // อัปเดตชื่อไฟล์ภาพในฐานข้อมูล
        $sql = "UPDATE schools SET pic = :pic WHERE sch_id = :sch_id";
        $query = $conn->prepare($sql);
        $query->bindParam(':pic', $fileName, PDO::PARAM_STR);
        $query->bindParam(':sch_id', $sch_id, PDO::PARAM_INT);
        $query->execute();

        $img_link = $upload_path . $fileName;
        echo json_encode(array("message" => "Image uploaded successfully", "status" => true, "img" => $img_link));
    } catch (PDOException $e) {
        $errorMSG = json_encode(array("message" => "Error occurred: " . $e->getMessage(), "status" => false));
        echo $errorMSG;
    }

?>
