<?php
require_once './pdo_config.php';
require_once './FetchData.php';
require_once './Query.php';
require_once './Img.php';
require_once './RestApi.php';
require_once './matchUrl.php';

$dbName = 'events';
$colNames = ['title', 'content', 'location', 'date', 'banner'];
$storePath = '../images/';
$tempPath = '../temp/';
$displayTemp = './temp/';
$displayStore = './images/';

$query = new Query($dbName);
$restfulApi = new Restful();

function getResponse($pdo)
{
  $query_getAll = "SELECT * FROM `events`";
  $getAll_stmt = $pdo->query($query_getAll);
  $eventsInfo = $getAll_stmt->fetchAll(PDO::FETCH_CLASS, 'FetchData');
  $res_data = [];
  if ($eventsInfo) {
    foreach ($eventsInfo as $eInfo) {
      $res_data[] = $eInfo->getData();
    }
  }
  $res = [
    'success' => true,
    'msg' => '',
    'data' => $res_data
  ];
  $res_json = json_encode($res);
  echo $res_json;
}

function postResponse($pdo)
{
  global $colNames;
  global $dbName;
  global $storePath;
  global $tempPath;
  global $displayStore;
  $query = new Query($dbName);
  $imgStore = new Img($storePath);
  $content = $_POST['content'];
  $tempImgs = [];
  if ($_POST['title'] == '') {
    $msg = [
      'success' => false,
      'msg' => '請輸入標題'
    ];
    echo json_encode($msg);
    exit();
  }
  
  $storePaths = [];
  $imgUrls = matchImgUrl($_POST['content']);
  foreach ($imgUrls as $imgURL) {
    $tempImgs[] = $imgURL;
    $basename = basename($imgURL);
    if (file_exists($tempPath . $basename)) {
      // 替換 content 路徑
      $content = str_replace($imgURL, $displayStore . $basename, $content);
      rename($tempPath . $basename, $storePath . $basename);
      $storePaths[] = $displayStore . $basename;
    }
  }

  $banner = '';
  if ($_FILES['banner']['error'] === 0) {
    $banner = $imgStore->storeUpload($_FILES['banner']['name'], $_FILES['banner']['tmp_name']);
  }

  $post_values = [];
  $post_names = [];
  foreach ($colNames as $name) {
    if ($name === 'content') {
      $post_values[] = $content;
      $post_names[] = $name;
    } else if ($name === 'banner') {
      if (!$banner) {
        continue;
      } else {
        $post_values[] = $banner;
        $post_names[] = $name;
      }
    } else {
      $post_values[] = $_POST[$name];
      $post_names[] = $name;
    }
  }

  if (!$_POST['id']) {
    try {
      $post_names[] = 'cid';
      $post_values[] = 2;
      $query_insert = $query->insertQuery($post_names);
      $insert_stmt = $pdo->prepare($query_insert);
      $insert_stmt->execute($post_values);

      $msg = [
        'success' => true,
        'msg' => '新增成功',
        'tempImgs' => $tempImgs
      ];
      echo json_encode($msg);
    } catch (Exception $e) {
      $msg = [
        'success' => false,
        'msg' => '新增錯誤',
        'error' => $e->getMessage()
      ];
      echo json_encode($msg);
    }
  } else {
    $query_getContent = $query->selectQueryById(['content']);
    $getContent_stmt = $pdo->prepare($query_getContent);
    $getContent_stmt->execute([$_POST['id']]);
    $preContent = $getContent_stmt->fetch(PDO::FETCH_NUM)[0];

    if ($preContent) {
      // 匹配 content 圖片路徑
      $preMatches = matchImgUrl($preContent);
      foreach ($preMatches as $prePath) {
        $is_match = false;
        // 比對更動的圖片名稱
        foreach ($storePaths as $storePath) {
          if ($storePath === $prePath) {
            $is_match = true;
          }
        }
        if (!$is_match) {
          // 刪除圖片
          @unlink($prePath);
        }
      }
    }

    $post_values[] = $_POST['id'];
    $query_update = $query->updateQueryById($post_names);
    $update_stmt = $pdo->prepare($query_update);
    $update_stmt->execute($post_values);
    $msg = [
      'success' => true,
      'msg' => '修改成功'
    ];
    echo json_encode($msg);
  }
}

function deleteResponse($pdo)
{
  global $query;
  try {
    $deleteId = json_decode(file_get_contents('php://input'));
    $query_getImg = $query->selectQueryById(['banner', 'content']);
    $getImg_stmt = $pdo->prepare($query_getImg);
    $getImg_stmt->execute($deleteId);
    $arr = [];
    foreach ($getImg_stmt->fetch(PDO::FETCH_NUM) as $value) {
      $arr[] = $value;
    }
    $imgUrls = matchImgUrl($arr[1]);
    if (!!$arr[0]) {
      $imgNames[] = $arr[0];
    }

    foreach ($imgUrls as $imgUrl) {
      @unlink($imgUrl);
    }

    $query_delete = $query->deleteQueryById();
    $delete_stmt = $pdo->prepare($query_delete);
    $delete_stmt->execute($deleteId);

    $msg = [
      'success' => true,
      'msg' => '刪除成功',
      'imgName' => $imgNames,
      'id' => $deleteId
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

$restfulApi->setGet('getResponse')->setPost('postResponse')->setDelete('deleteResponse')->receiveReq($_SERVER['REQUEST_METHOD'])($pdo);
