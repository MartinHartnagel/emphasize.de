<?php 
include_once(dirname(__FILE__).'/../includes/config.php');
$export="js";
session_start();
if (isset($_POST['token'])) {
	$token=$_POST['token'];
} else if (isset($_SESSION['token'])) {
	$token=$_SESSION['token'];
}
if (!isset($token)) {
	echo("not logged in");
	exit();
}

connectDb();
pickup($token);

$json=array('error'=>'', 'msg'=>'', 'avatar'=>'');
$fileElementName = 'fileToUpload';
if (!empty($_FILES[$fileElementName]['error']))
{
	switch($_FILES[$fileElementName]['error'])
	{
		case '1':
			$json['error'] = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			break;
		case '2':
			$json['error'] = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
			break;
		case '3':
			$json['error'] = 'The uploaded file was only partially uploaded';
			break;
		case '4':
			$json['error'] = 'No file was uploaded.';
			break;

		case '6':
			$json['error'] = 'Missing a temporary folder';
			break;
		case '7':
			$json['error'] = 'Failed to write file to disk';
			break;
		case '8':
			$json['error'] = 'File upload stopped by extension';
			break;
		case '999':
		default:
			$json['error'] = 'No error code available';
	}
	header("Content-Type: text/plain;charset=UTF-8");
	echo(json_encode($json));
	exit();
} else if (!(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')) {
	$json['avatar']=base_convert(time(), 10, 36);
	$json['msg']= "File Name: " . $avatar;
	if (@filesize($_FILES[$fileElementName]['tmp_name']) > $max_avatar_filesize) {
		$json['error'] = 'PNG file size '.@filesize($_FILES[$fileElementName]['tmp_name']).' exceeds allowed maximum '.$max_avatar_filesize;
	}
	if ($json['error'] == "") {
		$image = @imagecreatefrompng($_FILES[$fileElementName]['tmp_name']);
		if (!$image) {
			$json['error'] = 'Not a valid PNG file..';
		}
		$w= imagesx($image);
		if ($w > $max_avatar_width) {
			$json['error'] = 'PNG width '.$w.' exceeds allowed maximum '.$max_avatar_width;
		}
		$h= imagesy($image);
		if ($h > $max_avatar_height) {
			$json['error'] = 'PNG height '.$h.' exceeds allowed maximum '.$max_avatar_height;
		}
	}

	if ($json['error'] == "") {
		move_uploaded_file($_FILES[$fileElementName]['tmp_name'], "../avatars/".$id."_".$avatar.".png");
		@unlink($_FILES[$fileElementName]);
	}
	header("Content-Type: text/plain;charset=UTF-8");
	echo(json_encode($json));
	exit();
}
header("Content-Type: text/html;charset=UTF-8");
?>
<script
	type="text/javascript" src="js/ajaxfileupload.js"></script>
<script type="text/javascript">
	function fileUpload()
	{
		$("#loading")
		.ajaxStart(function(){
		  $('#buttonUpload').attr('disabled', true);
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
			$('#buttonUpload').attr('disabled', false);
		});

		$.ajaxFileUpload({
				url:domain+'util/settings.php',
				secureuri:false,
				fileElementId:'fileToUpload',
				dataType: 'json',
				success: function (data, status) {
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);
						}else
						{
							$('#avatars').load(domain+"util/avatars.php");
						}
					}
				},
				error: function (data, status, e) {
					alert(e);
				}
			});
		
		return false;

	}
	</script>
</head>

<body>
	<form name="baseHrefForm" action="" method="POST"
		onsubmit="return setBaseHref(this.basehref.value);">
		<i18n key="upl6"> <en>Set a base URL for reports-links</en> <de>Eine
		Basis-URL für Links in den Berichten setzen</de> <fr>Mis une URL de
		base pour les liens dans les rapports</fr> <es>Establecidos una
		dirección URL base para los vínculos en los informes</es></i18n>
		:<br />
		<div class="hc">
			<input type="text" name="basehref" value="<?php echo($base_href);?>"
				style="width: 70%;" maxsize="256" /> <input type="submit"
				id="buttonBaseHref" class="help" style="width: 30%;"
				value="<i18n 
				 key='upl4'>
			<en>Define</en>
			<de>Einstellen</de>
			<fr>Choisir</fr>
			<es>Elegir</es>
			</i18n>
			" />
			<div id="help_buttonUpload" class="docu"
				style="width: 240px; height: 128px;">
				<i18n key="upl5"> <en>If specified, creates a link with this base
				URL appended by the entry-text. Use to easily connect to a third
				party webservice.</en> <de>Wenn angegeben, wird eine Verknüpfung mit
				dieser Basis-URL gefolgt von dem Eintrags-Text im Bericht erzeugt.
				Dient zur Anbindung eines weiteren Web-Dienstes.</de> <fr>S'il est
				spécifié, crée un lien avec cette URL de base annexée par l'entrée
				de texte. Utilisez-la pour se connecter facilement à un webservice
				tiers.</fr> <es>Si se especifica, crea un enlace con esta URL base
				del título, por la introducción de texto. Se utiliza para conectar
				fácilmente a un servicio web de terceros.</es></i18n>
			</div>
		</div>
	</form>
	<form name="form" action="" method="POST" enctype="multipart/form-data"
		onsubmit="return fileUpload();">
		<input type="hidden" name="token" value="<?php echo($token);?>" />
		<i18n key="upl0"> <en>Upload a PNG-picture</en> <de>Ein PNG-Bild
		hochladen</de> <fr>Télécharger une image PNG</fr> <es>Subir una imagen
		PNG</es></i18n>
		:<br /> <input type="hidden" name="max_file_size"
			value="<?php echo($max_avatar_filesize);?>"> <input id="fileToUpload"
			type="file" size="25" name="fileToUpload" />
		<div class="hc">
			<input type="submit" id="buttonUpload" class="help"
				value="<i18n 
				 key='upl1'>
			<en>Upload</en>
			<de>Hochladen</de>
			<fr>Télécharger</fr>
			<es>Subir</es>
			</i18n>
			" />
			<div id="help_buttonUpload" class="docu"
				style="width: 240px; height: 128px;">
				<?php echo(str_replace(array("_max_avatar_filesize_", "_max_avatar_width_", "_max_avatar_height_"), array(ceil($max_avatar_filesize/1024), $max_avatar_width, $max_avatar_height), i18n("<i18n key='upl2'> <en>Upload a PNG image with \"Select ...\" and
				\"Upload\" to make it selectable as a pawn. The PNG image must be
				smaller than _max_avatar_filesize_ KB and _max_avatar_width_
				pixels wide and _max_avatar_height_ pixels high. The
				shadow of the character should not be included in the PNG image,
				this is created automatically.</en> <de>Ein PNG-Bild mit
				\"Durchsuchen ...\" auswählen und \"Hochladen\" als Spielfigur
				auswählbar machen. Das PNG-Bild muß kleiner als _max_avatar_filesize_
				KB sein und maximal _max_avatar_width_ Pixel breit
				und _max_avatar_height_ Pixel hoch sein. Der Schatten
				der Spielfigur sollte im PNG-Bild nicht enthalten sein, dieser wird
				automatisch erzeugt.</de> <fr>Une image PNG avec \"Choisir ...\" et
				sélectionnez \"Envoyer\" comme un pion pour faire de sélection.
				L'image PNG doit être inférieure à _max_avatar_filesize_
				Ko et _max_avatar_width_ pixels de large et _max_avatar_height_
				pixels de haut. L'ombre du personnage ne doit pas être inclus dans
				l'image PNG, ceci est créé automatiquement.</fr> <es>Una imagen PNG
				con \"Seleccionar ...\" y seleccione \"Subir\", como un peón para hacer
				seleccionable. La imagen PNG debe ser menor de _max_avatar_filesize_
				KB y _max_avatar_width_ píxeles de ancho y _max_avatar_height_
				píxeles. La sombra del personaje no debe ser incluido en la imagen
				PNG, este se crea automáticamente.</es></i18n>"))); ?>
			</div>
		</div>
		<img id="loading" src="graphics/loading.gif" width="18" height="18"
			style="display: none;">
	</form>
	<br/>
	<i18n key="upl3"> <en>Choose a character</en> <de>Spielfigur auswählen</de>
	<fr>Choisissez un personnage</fr> <es>Elige un personaje</es></i18n>
	:
	<br />
	<div id="avatars" class="scroller">
		<?php include_once(dirname(__FILE__).'/avatars.php');?>
	</div>
	<?php bottom(); ?>
