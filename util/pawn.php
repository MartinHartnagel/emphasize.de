<?php
include_once(dirname(__FILE__).'/../includes/config.php');
$export='json';
if (r("do") == "setAvatar") {
	$newavatar=r('avatar');
	User::getInstance()->setUserAvatar($newavatar);
} else if (r("do") == "deleteAvatar") {
	$theavatar=r('avatar');
	User::getInstance()->deleteUserAvatar($theavatar);
	exit();
}
echo(User::getInstance()->getUserAvatar($avatar));
bottom();
?>
