<?php
require_once './check.php';
require_once('./pdo.php'); //引用資料庫連線


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  require_once './tpl/head.php'
  ?>
    <style>
    .border {
        border: 1px solid;
    }
    img.img {
        width: 100px;
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
       <h4>新增商品</h4>
  </div>
<form name="myForm" enctype="multipart/form-data" method="POST" action="update.php">

    <table class="border table table-striped table-dark custom-table">
        <thead>
            <tr>
            <th class="border">項目</th>
            <th class="border">內容</th>

            <!-- <th class="border">名稱</th>
            <th class="border">茶種</th>
            <th class="border">分類</th>
            <th class="border">價錢</th>
            <th class="border">單位</th>
            <th class="border">保存期限</th>
            <th class="border">廠商ID</th>
            <th class="border">商品內容</th>
            <th class="border">圖片</th> -->
            
            </tr>
        </thead>
        <tbody>
        <?php
        //SQL 敘述
        $sql = "SELECT `id`,`title`,`tag`,`classIfy`,`price`,`unit`,`sTime`,`idVendor`,`feaTure`,`img`     
        FROM `commodity`
        WHERE `id` = ? ";

        $arrParam = [
            (int)$_GET['id']
        ];

        //查詢
        $stmt = $pdo->prepare($sql);
        $stmt->execute($arrParam);
        // $stmt->execute($arrParam);

        //資料數量大於 0，則列出相關資料
        if($stmt->rowCount() > 0) {
            $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
       
            <tr>
                <td>名稱</td>
                <td class="border">
                    <input type="text" name="title" value="<?php echo $arr[0]['title']; ?>" maxlength="20" />
                </td>
            </tr>
            <tr>
                <td>茶種</td>
                <td class="border">
                    <select name="tag">
                    <option  value="<?php echo $arr[0]['tag']; ?>" selected><?php echo $arr[0]['tag']; ?></option>
                    <option value="綠茶">綠茶</option>
                    <option value="烏龍茶">烏龍茶</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>分類</td>
                <td class="border">
                    <input type="text" name="classIfy" value="<?php echo $arr[0]['classIfy']; ?>" maxlength="20" />
                </td>
            </tr>   
            <tr>
                <td>價錢</td>
                <td class="border">
                    <input type="number" name="price" value="<?php echo $arr[0]['price']; ?>" maxlength="20" />
                </td>
            </tr>
            <tr>
                <td>單位</td>
                <td class="border">
                    <input type="number" name="unit" value="<?php echo $arr[0]['unit']; ?>" maxlength="20" />
                </td>
            </tr>
            <tr>
                <td>保存期限</td>
                <td class="border">
                    <input type="text" name="sTime" value="<?php echo $arr[0]['sTime']; ?>" maxlength="20" />
                </td>
            </tr>
            <tr>
                <td>廠商ID</td>
                <td class="border">
                    <input type="text" name="idVendor" value="<?php echo $arr[0]['idVendor']; ?>" maxlength="20" />
                </td>
            </tr>
            <tr>
                <td>商品內容</td>    
                <td class="border">
                <textarea name="feaTure" id="" cols="40" rows="10"s><?php echo $arr[0]['feaTure']; ?></textarea>
                </td>
            
            </tr>
            <tr>
                <td>
                      圖片
                </td>
                <td class="border td-white"> 
                <img class="img" src="./images/<?php echo $arr[0]['img']; ?>" />
                <input id="update-img" type="file" name="img" value="" class=img/>
                   
                </td>
            </tr>
        <?php
        } else {
        ?>
            <tr>
                <td colspan="12">沒有資料</td>
            </tr>
        <?php
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td class="border" colspan="12"><input type="submit" name="smb" value="更新"class="btn btn-danger"></td>
            </tr>
        </tfoo>
    </table>
    <input type="hidden" name="id" value="<?php echo (int)$_GET['id']; ?>">
</form>
</section>
</main>
</body>
</html>