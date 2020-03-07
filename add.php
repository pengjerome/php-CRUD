<?php
// require_once('./checkAdmin.php'); //引入登入判斷
require_once('./pdo.php'); //引用資料庫連線

// echo "<pre>";
// print_r($_POST);
// echo "</pre>";
// exit();

$sql = "INSERT INTO `commodity` (`title`,`tag`,`classIfy`,`price`,`unit`,`sTime`,`idVendor`,`feaTure`,`img`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$img = '';
//回傳狀態
// $objResponse = [];

if( $_FILES["img"]["error"] === 0 ) {
    //為上傳檔案命名
    $strDatetime = "images_".date("YmdHis");
        
    //找出副檔名
    $extension = pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION);

    //建立完整名稱
    $img = $strDatetime.".".$extension;

    //若上傳失敗，則回報錯誤訊息
    if( !move_uploaded_file($_FILES["img"]["tmp_name"], "./images/{$img}") ) {
        // $objResponse['success'] = false;
        // $objResponse['code'] = 500;
        // $objResponse['info'] = "上傳圖片失敗";
        // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
        echo "上傳圖片失敗";
        exit();
    }
}

//SQL 敘述


//繫結用陣列
$arrParam = [
    $_POST['title'],
    $_POST['tag'],
    $_POST['classIfy'],
    $_POST['price'],
    $_POST['unit'],
    $_POST['sTime'],
    $_POST['idVendor'],
    $_POST['feaTure'],
    $img
];

$stmt = $pdo->prepare($sql);
// $stmt->execute();
$stmt->execute($arrParam);

if($stmt->rowCount() > 0) {
    header("Refresh: 3; url=./commodity.php");
    // $objResponse['success'] = true;
    // $objResponse['code'] = 200;
    // $objResponse['info'] = "新增成功";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    echo "新增成功";
    exit();
} else {
    header("Refresh: 3; url=./commodity.php");
    // $objResponse['success'] = false;
    // $objResponse['code'] = 500;
    // $objResponse['info'] = "沒有新增資料";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    echo "沒有新增資料";
    exit();
}