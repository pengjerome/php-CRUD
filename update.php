<?php
require_once './check.php';
require_once('./pdo.php'); //引用資料庫連線

// echo "<pre>";
// print_r($_POST);
// echo "</pre>";



//回傳狀態
 $objResponse = [];

//用在繫結 SQL 用的陣列
$arrParam = [];


//SQL 語法
$sql = "UPDATE 
        `commodity` 
        SET ";
// $sql = "UPDATE `commodity` 
// SET `title` = ?,
// `tag` = ?,
// `classIfy` = ?,
// `price` = ?,
//  `unit` = ?,
//   `sTime` = ?,
//    `idVendor` = ?,
//    `feaTure` = ? ";

// $sql = "UPDATE `commodity` SET  ";

// itemName SQL 語句和資料繫結
// $arrParam = [
//     $_POST['title'],
//     $_POST['tag'],
//     $_POST['classIfy'],
//     $_POST['price'],
//     $_POST['unit'],
//     $_POST['sTime'],
//     $_POST['idVendor'],
//     $_POST['feaTure']
 
// ];

$sql.= "`title` = ? ,";
$arrParam[] = $_POST['title'];


if( $_FILES["img"]["error"] === 0 ) {
    //為上傳檔案命名
    $strDatetime = date("YmdHis");
        
    //找出副檔名
    $extension = pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION);

    //建立完整名稱
    $img = $strDatetime.".".$extension;

    //若上傳成功 (有夾帶檔案上傳)，則將上傳檔案從暫存資料夾，移動到指定的資料夾或路徑
    if( move_uploaded_file($_FILES["img"]["tmp_name"], "./images/{$img}") ) {
        //先查詢出特定 id (itemId) 資料欄位中的大頭貼檔案名稱
        $sqlGetImg = "SELECT `img` FROM `commodity` 
        WHERE `id` = ? ";
        $stmtGetImg = $pdo->prepare($sqlGetImg);

        //加入繫結陣列
        $arrGetImgParam = [
            (int)$_POST['id']
        ];

        //執行 SQL 語法
        $stmtGetImg->execute($arrGetImgParam);

        //若有找到 itemImg 的資料
        if($stmtGetImg->rowCount() > 0) {
            //取得指定 id 的商品資料 (1筆)
            $arrImg = $stmtGetImg->fetchAll(PDO::FETCH_ASSOC);

            //若是 itemImg 裡面不為空值，代表過去有上傳過
            if($arrImg[0]['img'] !== NULL){
                //刪除實體檔案
                @unlink("./images/".$arrImg[0]['img']);
            } 

            //itemImg SQL 語句字串
            // $sql.= ",";

            $sql.= "`img`= ? ,";

            //僅對 itemImg 進行資料繫結
            $arrParam[] = $img;
            
        }
    }
}

//itemPrice SQL 語句和資料繫結


$sql.= "`tag` = ? ,";
$arrParam[] = $_POST['tag'];

$sql.= "`classIfy` = ? ,";
$arrParam[] = $_POST['classIfy'];

$sql.= "`price` = ? ,";
$arrParam[] = $_POST['price'];

$sql.= "`unit` = ? ,";
$arrParam[] = $_POST['unit'];

$sql.= "`sTime` = ? ,";
$arrParam[] = $_POST['sTime'];

$sql.= "`idVendor` = ? ,";
$arrParam[] = $_POST['idVendor'];

$sql.= "`feaTure` = ? ";
$arrParam[] = $_POST['feaTure'];



$sql.= "WHERE `id` = ? ";
$arrParam[] = (int)$_POST['id'];
// echo "<pre>";
// print_r($sql);
// echo "</pre>";

$stmt = $pdo->prepare($sql);
// $stmt=$pdo->errorCode() ;
// echo "<pre>";
// print_r($stmt);
// echo "</pre>";


$stmt->execute($arrParam);
    
// echo "<pre>";
// print_r($stmt);
// echo "</pre>";

if( $stmt->rowCount()> 0 ){

    header("Refresh: 3; url=./commodity.php?id={$_POST['id']}");
    // $objResponse['success'] = true;
    // $objResponse['code'] = 204;
    // $objResponse['info'] = "更新成功";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    echo "更新成功";
    exit();
} else {
    header("Refresh: 3; url=./commodity.php?id={$_POST['id']}");
    // $objResponse['success'] = false;
    // $objResponse['code'] = 400;
    // $objResponse['info'] = "沒有任何更新";
    // echo json_encode($objResponse, JSON_UNESCAPED_UNICODE);
    echo "更新失敗";
    exit();
}