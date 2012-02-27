<?php 
include_once(dirname(__FILE__).'/../includes/config.php');
if (!isset($_SESSION)) {
  session_start();
}
connectDb();
if (isset($_SESSION['token'])) {
	$token=$_SESSION['token'];
	pickup($token);
}
if (!empty($_POST)) {
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
	if (isset($_POST["do"]) && $_POST["do"] == "createTemplate") {
		$export="json";
		$name = $_POST["name"];
		$tbody = $_POST["tbody"];
		$key=$id.'_'.base_convert(time(), 10, 36);
		// make name unique
		$sql=@mysql_query("SELECT name FROM " . $db_prefix . "TEMPLATES WHERE id_user=".p($id));
		$names=array();
		while ($row = mysql_fetch_array($sql)) {
			$names[]=$row["name"];
		}
		mysql_free_result($sql);
		if (in_array($name, $names)) {
			$c=1;
			do {
				$c++;
				$name=preg_replace("/ \([0-9]+\)$/", "", $name);
				$append=" (".$c.")";
				if (strlen($name)+strlen($append)>30) {
					$name=substr($name, 0, 30-strlen($append));
				}
				$name.=$append;
			} while(in_array($name, $names));
		}
		// name should now be unique and not longer than 30 chars

		$insert = @mysql_query("INSERT INTO " . $db_prefix . "TEMPLATES SET id_user=".p($id).", name='".p($name)."', `KEY`='".p($key)."', tbody='".p($tbody)."'");
		if (!$insert) {
			fail("Eintrag fehlgeschlagen");
		}
	} else if (isset($_POST["do"]) && $_POST["do"] == "loadTemplate") {
		$export="json";
		header("Content-Type: text/html;charset=UTF-8");
		$key = $_POST["key"];
		if (array_key_exists($key, $tbody_value)) {
			echo($tbody_value[$key]);
		} else {
			$sql = @mysql_query("SELECT tbody FROM " . $db_prefix . "TEMPLATES WHERE id_user='".p($id)."' AND `KEY`='" . p($key) . "'");
			if ($row = mysql_fetch_array($sql)) {
				echo($row["tbody"]);
			}
		}
		bottom();
	} else if (isset($_POST["do"]) && $_POST["do"] == "removeTemplate") {
		$export="json";
		$key = $_POST["key"];
		$delete = @mysql_query("DELETE FROM " . $db_prefix . "TEMPLATES WHERE id_user=".p($id)." AND `KEY`='" . p($key) . "'");
		if (!$delete) {
			fail("delete failed");
		}
	}
}
if (!isset($templates_included)) {
	$export="js";
	header("Content-Type: text/html;charset=UTF-8");
}

$ts='<select id="templateSelect" name="templates"
	onchange="checkRemoveTemplate('.(sizeof($tbody_names)+1).')">
	<option value="reset">
		<i18n key="tmp0"> <en>Reset</en> <de>Zurücksetzen</de> <fr>Reset</fr>
		<es>Restablecer</es></i18n>
	</option>'."\n";
foreach($tbody_names as $key=>$tbody_name) {
	$ts.="<option value=\"$key\">$tbody_name</option>\n";
}
$sql = @mysql_query("SELECT `KEY`, name FROM " . $db_prefix . "TEMPLATES WHERE id_user='".p($id)."' ORDER BY name ASC, `KEY` ASC");
while($row = mysql_fetch_array($sql)) {
	$ts.="<option value=\"".h($row["KEY"])."\">".h($row["name"])."</option>\n";
}
mysql_free_result($sql);
$ts.='</select>'."\n";
?>
<script type="text/javascript">
function fileImport() {
	$.ajaxFileUpload({
			url:domain+'util/templates.php',
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

<form action="" method="post" name="presets"
	onsubmit="return loadTempl();">
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td><i18n key="tab31"> <en>Templates for field layout</en>
				<de>Vorlagen zur Feldaufteilung</de> <fr>Modèles pour division
				du terrain</fr> <es>Plantillas para devision campo</es></i18n>:</ts>
		 </tr><tr>
			<td id="templateSelectSpan">
			<?php echo($ts); ?>
			</td>
			<td>
			<div class="hc">
					<input id="loadTemplate" name="load" value="<i18n   key='tab32'>
					<en>Load</en>
					<de>Laden</de>
					<fr>Charger</fr>
					<es>Cargar</es>
					</i18n>
					" type="submit" class="button help" />
					<div id="help_loadTemplate" class="docu"
						style="width: 320px; height: 44px;">
						<i18n key="tab33"> <en>Load selected template for field layout
						and continue editing.</en> <de>Ausgewählte Vorlage zur
						Feldaufteilung laden und weiter bearbeiten.</de> <fr>Chargez
						le modèle sélectionné pour la mise sur le terrain et continuer
						à éditer.</fr> <es>Cargue la plantilla seleccionada para el
						diseño de campo y continuar con la edición.</es></i18n>
					</div>
				</div></td>
			<td><div class="hc">
					<input id="removeTemplate" name="remove" disabled="true"
						value="<i18n   key='tab34'>
					<en>Delete</en>
					<de>Entfernen</de>
					<fr>Supprimer</fr>
					<es>Quitar</es>
					</i18n>
					" type="button" onclick="removeTempl()" class="button help" />
					<div id="help_removeTemplate" class="docu"
						style="width: 320px; height: 44px;">
						<i18n key="tab35"> <en>Delete personal template. Is only
						selectable with personal templates.</en> <de>Persönliche
						Vorlage wieder löschen. Ist nur bei persönlichen Vorlagen
						auswählbar.</de> <fr>Supprimer modèle personnel. Ne peut être
						sélectionné avec des modèles personnels.</fr> <es>Eliminar
						plantilla de personal. Sólo se puede seleccionar con las
						plantillas de personal.</es></i18n>
					</div>
				</div></td>
			</tr><tr>
			<td><input id="descTemplate" name="desc" value="<i18n 
				
				key='tab36'> <en>new template</en> <de>neue Vorlage</de> <fr>nouveau
				modèle</fr> <es>nueva plantilla</es> </i18n>" type="text"
				onblur="checkTemplateName()" onkeyup="checkTemplateName()" /></td>
			<td><div class="hc">
					<input id="createTemplate" name="create"
						value="<i18n 
						 key='tab37'>
					<en>Create</en>
					<de>Anlegen</de>
					<fr>Créer</fr>
					<es>Crear</es>
					</i18n>
					" type="button" onclick="createTempl()" class="button help" />
					<div id="help_createTemplate" class="docu"
						style="width: 320px; height: 44px;">
						<i18n key="tab38"> <en>Create personal template with the given
						name.</en> <de>Persönliche Vorlage mit der gegebenen
						Bezeichnung anlegen.</de> <fr>Créer une modèle personnel avec
						le nom donné.</fr> <es>Plantilla de personal con el nombre
						dado a crear.</es></i18n>
					</div>
				</div></td><td>
				<div class="hc">
					<input id="exportTemplate" name="export"
						value="<i18n 
						 key='tab39'>
					<en>Export</en>
					<de>Exportieren</de>
					<fr>Export</fr>
					<es>Exportación</es>
					</i18n>
					" type="button" onclick="exportTempl()" class="button help" />
					<div id="help_exportTemplate" class="docu"
						style="width: 320px; height: 44px;">
						<i18n key="tab40"> <en>Export personal template to a file.</en>
						<de>Persönliche Vorlage als Datei exportieren.</de> <fr>Export
						une modèle personnel dans une fichier.</fr> <es>Exportación
						plantilla de personal.</es></i18n>
					</div>
				</div></td></tr>
						</tr>
	</table>
	</form>
	<br/>
<form name="form" action="" method="POST" enctype="multipart/form-data"
		onsubmit="return fileImport();">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
		<i18n key="tab42"> <en>Import personal template from a file</en> <de>Persönliche
		Vorlage als Datei importieren</de> <fr>Import une modèle personnel de
		une fichier</fr> <es>Importar plantilla de personal</es></i18n>
		:<br /> <input type="hidden" name="max_file_size"
			value="<?php echo($max_import_filesize);?>"> <input id="fileToImport"
			type="file" size="20" name="fileToImport" />
		<div class="hc">
			<input type="submit" id="buttonImport" class="button help"
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
					</td>
		</tr>
	</table>
</form>
<?php if (!isset($templates_included)) { 
	bottom();
} ?>
