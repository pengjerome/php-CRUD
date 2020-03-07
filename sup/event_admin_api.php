<?php
require_once './pdo_config.php';
require_once './RestApi.php';
require_once './FetchData.php';
require_once './matchUrl.php';

$companyDb = 'vendorData';
$storePath = '../images/';

function getResponse($pdo)
{
  global $companyDb;
  $eventsInfo = [];
  $eventsData = [];
  if (!isset($_GET['cid']) || $_GET['cid'] === '') {
    $query_getAll = "SELECT
      `e`.`id`, `e`.`title`, `e`.`content`, `e`.`date`, `e`.`location`, `e`.`banner`, `c`.`vendorAccount`, `c`.`id` AS `cid`
    FROM
      `events` AS `e`
    INNER JOIN
	    `$companyDb` AS `c`
    ON
	    `e`.`cid` = `c`.`id`";
    $getAll_stmt = $pdo->query($query_getAll);
    $eventsInfo = $getAll_stmt->fetchAll(PDO::FETCH_CLASS, 'FetchData');
  } else {
    $cid = (int) $_GET['cid'];
    $query_getByCid = "SELECT
      `e`.`id`, `e`.`title`, `e`.`content`, `e`.`date`, `e`.`location`, `e`.`banner`, `c`.`vendorAccount`, `c`.`id` AS `cid`
    FROM
      `events` AS `e`
    INNER JOIN
	    `$companyDb` AS `c`
    ON
	    `e`.`cid` = `c`.`id`
    WHERE
	    `e`.`cid` = ?";
    $getByCid_stmt = $pdo->prepare($query_getByCid);
    $getByCid_stmt->execute([$cid]);
    $eventsInfo = $getByCid_stmt->fetchAll(PDO::FETCH_CLASS, 'FetchData');
  }
  if ($eventsInfo) {
    foreach ($eventsInfo as $eInfo) {
      $eventsData[] = $eInfo->getData();
    }
  }

  $query_getCompany = "SELECT `id`, `vendorAccount` FROM `$companyDb`";
  $getCompany_stmt = $pdo->query($query_getCompany);
  $companys = $getCompany_stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach ($companys as $key => $value) {
    # code...
  }
  $res_data = [
    'eventsData' => $eventsData,
    'companys' => $companys
  ];
  $res = [
    'success' => true,
    'msg' => '',
    'data' => $res_data
  ];
  $res_json = json_encode($res);
  echo $res_json;
}

function deleteResponse($pdo)
{
  global $storePath;
  try {
    $deleteId = json_decode(file_get_contents('php://input'));
    $query_getImg = "SELECT `content`, `banner` FROM `events` WHERE `id` = ?";
    $getImg_stmt = $pdo->prepare($query_getImg);
    $getImg_stmt->execute($deleteId);
    $arr = [];
    foreach ($getImg_stmt->fetch(PDO::FETCH_NUM) as $value) {
      $arr[] = $value;
    }
    $imgUrls = matchImgUrl($arr[0]);
    if ($arr[1]) {
      $imgUrls[] = $arr[1];
    }
    foreach ($imgUrls as $imgUrl) {
      @unlink($storePath.basename($imgUrl));
    }

    $query_delete = "DELETE FROM `events` WHERE `id` = ?";
    $delete_stmt = $pdo->prepare($query_delete);
    $delete_stmt->execute($deleteId);

    $msg = [
      'success' => true,
      'msg' => '刪除成功'
    ];
    echo json_encode($msg);
  } catch (Exception $e) {
    $msg = [
      'success' => false,
      'msg' => "",
      'error' => $e->getMessage()
    ];
    echo json_encode($msg);
  }
}
$restfulApi = new Restful();
$restfulApi->setGet('getResponse')->setDelete('deleteResponse')->receiveReq($_SERVER['REQUEST_METHOD'])($pdo);
