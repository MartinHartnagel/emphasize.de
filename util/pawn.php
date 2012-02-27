<?php
include_once(dirname(__FILE__).'/../includes/config.php');
$token=$_POST["token"];
connectDb();
pickup($token);
$export='json';
if (isset($_POST["do"]) && $_POST["do"] == "setAvatar") {
	$newavatar=$_POST['avatar'];
	setUserAvatar($newavatar);
	pickup($token);
} else if (isset($_POST["do"]) && $_POST["do"] == "deleteAvatar") {
	$theavatar=$_POST['avatar'];
	deleteUserAvatar($theavatar);
	exit();
}
echo(getUserAvatar($avatar));
bottom();
?>
