<?php 
include_once(dirname(__FILE__).'/../includes/config.php');
$export="js";

$json=array('error'=>'', 'msg'=>'', 'tbody'=>'');
$fileElementName = 'fileToImport';
if (!empty($_FILES[$fileElementName]['error']))
{
	switch($_FILES[$fileElementName]['error'])
	{
		case '1':
			$json['error'] = 'The imported file exceeds the upload_max_filesize directive in php.ini';
			break;
		case '2':
			$json['error'] = 'The imported file exceeds the MAX_FILE_SIZE directive that .minwas specified in the HTML form';
			break;
		case '3':
			$json['error'] = 'The imported file was only partially imported';
			break;
		case '4':
			$json['error'] = 'No file was imported.';
			break;

		case '6':
			$json['error'] = 'Missing a temporary folder';
			break;
		case '7':
			$json['error'] = 'Failed to write file to disk';
			break;
		case '8':
			$json['error'] = 'File import stopped by extension';
			break;
		case '999':
		default:
			$json['error'] = 'No error code available';
	}
	header("Content-Type: text/plain;charset=UTF-8");
	echo(json_encode($json));
	exit();
} else if (!(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')) {
	$json['msg'] = utf8_encode(htmlentities(str_replace(".emphasize", "", $_FILES[$fileElementName]['name'])));
	if (@filesize($_FILES[$fileElementName]['tmp_name']) > $max_import_filesize) {
		$json['error'] = 'file size '.@filesize($_FILES[$fileElementName]['tmp_name']).' exceeds allowed maximum '.$max_import_filesize;
	}
	if ($json['error'] == "") {
		$contents=file_get_contents($_FILES[$fileElementName]['tmp_name']);
		if (preg_match("/<emphasize>.*<\/emphasize>/msU", $contents, $f)) {
			$verify=md5("mph".$f[0]);
			$json['tbody']=str_replace("</emphasize>", "", str_replace("<emphasize>", "", $f[0]));
		} else {
			$json['error'] = "emphasize-section missing";
		}

		if (preg_match("/untouched-lock:.*/", $contents, $l)) {
			$lock=str_replace("untouched-lock:", "", $l[0]);
		} else {
			$json['error'] = "untouched-lock missing";
		}

		if ($verify != $lock) {
			$json['error'] = "rejecting import as file was manually edited";
		}

		@unlink($_FILES[$fileElementName]);
	}
	header("Content-Type: text/plain;charset=UTF-8");
	echo(json_encode($json));
	exit();
}
header("Content-Type: text/html;charset=UTF-8");
?>
<script
	type="text/javascript"
	src="<?php echo($domain.'/js/ajaxfileupload.js'); ?>"></script>
<script type="text/javascript">
	function fileImport() {
		$("#loading")
		.ajaxStart(function() {
		  $('#buttonImport').attr('disabled', true);
			$(this).show();
		})
		.ajaxComplete(function() {
			$(this).hide();
			$('#buttonImport').attr('disabled', false);
		});

		$.ajaxFileUpload({
				url:domain+'util/import.php',
				secureuri:false,
				fileElementId:'fileToImport',
				dataType: 'json',
				success: function (data, status) {
					if(typeof(data.error) != 'undefined') {
						if(data.error != '') {
							alert(data.error);
						} else {
						  $("#descTemplate").val(data.msg);
						  t.setTableHtml(data.tbody);
						  if (aboveClose != undefined) {
                aboveClose();
              }
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
	<form name="form" action="" method="POST" enctype="multipart/form-data"
		onsubmit="return fileImport();">
		<i18n key="tab42"> <en>Import personal template from a file</en> <de>Persönliche
		Vorlage als Datei importieren</de> <fr>Import une modèle personnel de
		une fichier</fr> <es>Importar plantilla de personal</es></i18n>
		:<br /> <input type="hidden" name="max_file_size"
			value="<?php echo($max_import_filesize);?>"> <input id="fileToImport"
			type="file" size="25" name="fileToImport" />
		<div class="hc">
			<input type="submit" id="buttonImport" class="help"
				value="<i18n 
				 key='tab41'>
			<en>Import</en>
			<de>Importieren</de>
			<fr>Import</fr>
			<es>Importar</es>
			</i18n>
			" />
			<div id="help_buttonImport" class="docu"
				style="width: 240px; height: 78px;">
				<i18n key="upl2"> <en>Import a personal template previously exported
				into a file with "Select ..." and "Import" to load its contained set
				of field devisions. The file must be smaller than <?php echo(ceil($max_import_filesize/1024));?>
				KB.</en> <de>Eine zuvor mit "Export" in eine Datei gespeicherte
				persönliche Vorlage mit "Durchsuchen ..." auswählen und "Hochladen"
				als Feldaufteilung laden. Die Datei muß kleiner als <?php echo(ceil($max_import_filesize/1024));?>
				KB sein.</de> <fr>Importer un modèle personnel préalablement
				exportés dans un fichier avec "Choisir ..." et "Importer"pour
				charger son contenu devisions ensemble de champ. Le fichier doit
				être inférieure à <?php echo(ceil($max_import_filesize/1024));?> Ko.</fr>
				<es>Importar una plantilla de personal previamente exportados a un
				archivo con "Seleccionar ..." y "Importar"para cargar su contenido
				conjunto de devisions campo. El archivo debe ser menor de <?php echo(ceil($max_import_filesize/1024));?>
				KB.</es></i18n>
			</div>
		</div>
		<img id="loading"
			src="<?php echo($domain.'/graphics/loading.gif'); ?>" width="18"
			height="18" style="display: none;">
	</form>
	<?php bottom(); ?>