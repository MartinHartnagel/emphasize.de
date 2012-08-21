<?php 
include_once(dirname(__FILE__).'/../includes/config.php');
$export="js";
header("Content-Type: text/html;charset=UTF-8");

$files = array();
$dir = opendir(dirname(__FILE__)."/../avatars");
while(($file = readdir($dir)) !== false) {
	if($file !== '.' && $file !== '..' && !is_dir($file) && (substr($file, strlen($file)-4)==".png") && ($file=="default.png" || substr($file, 0, 1)=="_") || (substr($file, 0, strlen($id."_"))==$id."_")) {
		$files[] = $file;
	}
}
closedir($dir);
rsort($files);

foreach($files as $png) {
	$a=substr($png, 0, strlen($png)-4);
	echo("<span style=\"position:relative;\">");
	if ($avatar==$a) {
		echo('<img src="util/thumb.php?../avatars/' . $png . '" width="' . $thumb_size . '" height="' . $thumb_size . '" style="border-width:2px;border-style:solid;border-color:red;" title="<i18n key="ava0"><en>Current character</en><de>Aktuelle Spielfigur</de><fr>Caractère actuel</fr><es>Carácter actual</es></i18n>"/>');
	} else {
		echo('<a href="javascript:parent.setAvatar(\'' . $a . '\')" title="<i18n key="ava1"><en>Set as character</en><de>Als Spielfigur auswählen</de><fr>Définir en tant que personnage</fr><es>Establecer como personaje</es></i18n>"><img src="util/thumb.php?../avatars/' . $png . '" width="' . $thumb_size . '" height="' . $thumb_size . '" style="border-width:2px;border-style:none;border-color:white;"/></a>');
		if (substr($png, 0, strlen($id."_"))==$id."_") {
			echo('<a href="javascript:parent.deleteAvatar(\'' . $a . '\')" title="<i18n key="ava2"><en>Delete character</en><de>Spielfigur löschen</de><fr>Personnage que ce soit</fr><es>Eliminar el carácter</es></i18n>"><img src="graphics/trash.png" border="0" width="33" height="42" style="position:absolute;z-index:600;left:0px;top:-42px;"/></a>');
		}
	}
	echo("</span>\n");
}
?>

<?php bottom(); ?>
