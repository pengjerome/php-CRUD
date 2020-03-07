<?php
require_once './check.php';
require_once('./pdo.php');


$sqlTotal = "SELECT count(1) FROM `commodity`"; //SQL 敘述
$total = $pdo->query($sqlTotal)->fetch(PDO::FETCH_NUM)[0]; //取得總筆數
$numPerPage = 10; //每頁幾筆
$totalPages = ceil($total/$numPerPage); // 總頁數
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; //目前第幾頁
$page = $page < 1 ? 1 : $page; //若 page 小於 1，則回傳 1



//商品種類 SQL 敘述
$sqlTotalCatogories = "SELECT count(1) FROM `commodity`";

//取得商品種類總筆數
$totalCatogories = $pdo->query($sqlTotalCatogories)->fetch(PDO::FETCH_NUM)[0];
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  require_once './tpl/head.php'
  ?>
  <style>
    
    table{
        border-radius: 7px;  
    }
    .border {
        border: 1px solid;
    }
    img.img {
        width: 100px;
    }
    .text-nowrap{
        white-space: nowrap;
    }
    td{
        vertical-align: middle !important;
    }
    .tfoot-border{
        /* background-color: #4a904c; */
        font-size : 16px;
    }
    .tfoot-border a{
        padding : 20px;
        
    }
  </style>
</head>

<body>

  <?php
  require_once './tpl/header.php';
  ?>
  <?php
  require_once './tpl/sideBar.php';
  ?>
 

<!-- main content -->
<main id="main-content">
<section class="wrapper">


    <?php require_once('./templates/title.php'); ?>

    <hr />
    <div class="table-title">
        <h4>商品列表</h4>
    </div>

    <form name="myForm" entype= "multipart/form-data" method="POST" action="delete.php">
        <table class="border table table-striped table-dark">
            <thead>
                <tr>
                    <th class="border text-nowrap">勾選</th>
                    <th class="border text-nowrap">流水號ID</th>
                    <th class="border text-nowrap">名稱</th>
                    <th class="border text-nowrap">茶種</th>
                    <th class="border text-nowrap">分類</th>
                    <th class="border text-nowrap">價錢</th>
                    <th class="border text-nowrap">單位</th>
                    <th class="border text-nowrap">保存期限</th>
                    <th class="border text-nowrap">廠商ID</th>
                    <th class="border text-nowrap">商品內容</th>
                    <th class="border text-nowrap">圖片</th>
                    <th class="border text-nowrap">功能</th>
                </tr>
            </thead>
            <tbody>
                <?php  
                $sql ="SELECT `id`,`title`,`tag`,`classIfy`,`price`,`unit`,`sTime`,`idVendor`,`feaTure`,`img`
                    FROM`commodity`
                    ORDER BY `id` ASC 
                    LIMIT ?, ? ";

$arrParam = [($page - 1) * $numPerPage, $numPerPage];



                $stmt = $pdo->prepare($sql);
                // $stmt->execute();
                $stmt->execute($arrParam);
                if($stmt->rowCount() > 0) {
                    $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    for($i = 0; $i < count($arr); $i++) {

                ?>
                <tr>
                        <td class="border">
                            <input type="checkbox" name="chk[]" value="<?php echo $arr[$i]['id']; ?>" /> 
                        </td>
                        <td class="border"><?php echo $arr[$i]['id']; ?></td>
                        <td class="border"><?php echo $arr[$i]['title']; ?></td>
                        <td class="border text-nowrap"><?php echo $arr[$i]['tag']; ?></td>
                        <td class="border"><?php echo $arr[$i]['classIfy']; ?></td>
                        <td class="border"><?php echo $arr[$i]['price']; ?></td>
                        <td class="border">
                            <?php echo $arr[$i]['unit']; ?>
                            <!-- / -->
                            <?php // echo $arr[$i]['unit']; ?>
                        </td>
                        <td class="border"><?php echo $arr[$i]['sTime']; ?></td>
                        <td class="border"><?php echo $arr[$i]['idVendor']; ?></td>
                        <td class="border"><?php echo $arr[$i]['feaTure']; ?></td>
                        <td class="border">
                            <img class="img" src="./images/<?php echo $arr[$i]['img']; ?>">
                        </td>
                        <td class="border text-nowrap">
                            <a  class="text-warning" href="./edit.php?id=<?php echo $arr[$i]['id']; ?>">編輯</a>
                        </td>
                    </tr>
                    <img src="" alt="">
                    <?php
                    }
                  } else {
              ?>
            <tr>
                <td class="border" colspan="12">沒有資料</td>
            </tr>
        <?php
        }
        ?>
            </tbody>
            <tfoot>
            <tr>
                <td class="border  tfoot-border" colspan="12">
                <?php for($i = 1; $i <= $totalPages; $i++){ ?>
                    <a href="?page=<?=$i?>"><?= $i ?></a>
                <?php } ?>
                </td>
            </tr>
            
            <?php if($total > 0) { ?>
            
            
                 <tr>
                    <td class="border" colspan="12"><input type="submit" name="smb" value="刪除" class="btn btn-danger"></td>
                </tr>
                <?php } ?> 
            </tfoot>
        </table>
    </form>
</section>
</main> 
<?php
  require_once './tpl/footer.php';
  ?>
       
</body>

</html>  