//執行登出動作
<?php
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
	unset($_SESSION["loginMember"]);
	header("Location: main.php");
}
?>