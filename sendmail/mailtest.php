<?php

// require_once('../../vvv/db.inc.php');
require_once('./db.inc.php');
include('../vendor/phpmailer/phpmailer/class.phpmailer.php');
include('../vendor/phpmailer/phpmailer/class.smtp.php');
mb_internal_encoding('UTF-8');

// $sql = "SELECT `vendorAccount`
// from `vendordata`
// WHERE `vendorEmail=?`";

// $arrparam=[$_POST['forgetAcc']];
// $acc="abcd1234";
// $stmt = $pdo->prepare($sql);
// $stmt->execute($arrparam);


function sendAcc($vendorAcc,$vendorEmail,$vendorName){

    $mail = new phpMailer();
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl";
    $mail->Host = "smtp.gmail.com";
    $mail->Post = 465;
    $mail->CharSet = "utf8";

    $mail->Username = "yutudiono@gmail.com";
    $mail->Password = "hop264town372";

    $mail->From = "yutudiono@gmail.com";
    $mail->FromName ="湘茗平台客服";

    $mail->Subject = "忘記帳號信件";
    $mail->Body = "您的帳號為:$vendorAcc";
    $mail->Body .="請回首頁重新登入";

    $mail->isHTML(true);
    $mail->addAddress($vendorEmail,$vendorName);

    if(!$mail->Send()){
        header("Refresh:3;url=./index.php");
        echo "寄送失敗";
    }else{
        header("Refresh:3;url=./index.php");
        echo "帳號寄送成功，請至信箱收信";
    }
}

?>