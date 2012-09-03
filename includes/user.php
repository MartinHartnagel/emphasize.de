<?php
class User {
 // singleton instance
 private static $instance;

 // db-connection established
 private static $dbcnx = false;

 private $id=null;
 private $token=null;
 private $lang=null;
 private $baseHref=null;
 private $avatar=null;
 private $name=null;
 private $confirmed=false;
 private $dateFormat=null;
 private $template;
 private $activeTemplate;

 // private constructor function
 // to prevent external instantiation
 private function __construct() {
 }

 // getInstance method
 public static function getInstance() {
  global $token;

  if (!self :: $instance) {
   self :: $instance = new self();
  }

  if (self :: $instance->getId() == null) {
   if (isset($token)) {
    self :: connectDb();
    self :: $instance->pickup($token);
   } elseif (r("token") != null) {
    self :: connectDb();
    self :: $instance->pickup(r("token"));
   }
  }

  return self :: $instance;
 }

 /**
  * Returns not <code>null</code> if logged in.
  */
 public function getId() {
  return $this->id;
 }

 public function getToken() {
  return $this->token;
 }

 public function getLang() {
  return $this->lang;
 }

 public function getName() {
  return $this->name;
 }

 public function getConfirmCode() {
  return $this->confirmed;
 }

 public function getBaseHref() {
  return $this->baseHref;
 }


 public function getEmail() {
  return $this->email;
 }

 function setActiveTemplate($activeTemplate) {
  $this->activeTemplate=$activeTemplate;
  $this->template=new Template($this->id);
  $this->template->load($this->activeTemplate);
 }

 function getActiveTemplate() {
  return $this->activeTemplate;
 }

 function getActiveTemplateValue() {
  return $this->template->getValue();
 }

 function getUserAvatar($specific=null) {
  global $domain;
  global $max_avatar_width;
  global $max_avatar_height;

  if ($specific != null) {
   $a=$specific;
  } else {
   $a=$this->avatar;
  }

  return '<img id="shadow" src="' . $domain . '/util/shadow.php?../avatars/' . $a . '.png" style="pointer-events:none;position:absolute;z-index:107;top:0px;left:' . ($max_avatar_width * -2) . 'px;" />' .
    '<div id="avatar" style="pointer-events:none;position:absolute; z-index:108; top:100px;left:' . ($max_avatar_width * -2 - 3) . 'px;width:1px;height:1px;">' .
    '<img id="user" class="help" src="' . $domain . '/avatars/' . $a . '.png" title="' . $this->name . '" style="pointer-events:none;" /><div id="help_user" class="docu" style="width:220px;height:44px;">' . "<i18n key='con25'><en><a href=\"javascript:tubeTutorial('akZ90qEgKEQ')\">Character</a> placed on the current activity by clicking on the corresponding field.</en><de><a href=\"javascript:tubeTutorial('R1XmQ9pioJU')\">Spielfigur</a> die jeweils auf die aktuelle Aktivität durch Klick auf das entsprechende Feld gesetzt wird.</de><fr>Le <a href=\"javascript:tubeTutorial('akZ90qEgKEQ')\">caractère</a> en cours à chaque activité en cliquant sur le coffret.</fr><es>El <a href=\"javascript:tubeTutorial('akZ90qEgKEQ')\">carácter</a> actual de cada actividad haciendo clic en la caja.</es></i18n>" . '</div></div>';
 }

 function setUserAvatar($avatar) {
  if ((strpos($avatar, "/") !== false) || (strpos($avatar, ":") !== false) || (strpos($avatar, ".") !== false) || (strpos($avatar, "?") !== false)) {
   fail("invalid avatar name " . $avatar);
  }
  $update = @ mysql_query("UPDATE " . DB_PREFIX . "USER SET avatar='" . p($avatar) . "' WHERE id=" . p($this->id));
  if (!$update) {
   fail("update failed");
  }
 }

 function deleteUserAvatar($theavatar) {
  $dir = opendir(dirname(__FILE__).'/../avatars');
  while (($file = readdir($dir)) !== false) {
   if ($file !== '.' && $file !== '..' && (substr($file, 0, strlen($this->id . "_")) == $this->id . "_") && ($file == $theavatar . ".png")) {
    unlink(dirname(__FILE__).'/../avatars/' . $file);
    break;
   }
  }
  closedir($dir);
 }

 /**
  * Performs a user login with name and password-hash.
  * @param unknown_type $confirmed if <code>true</code> the confirmed flag is checked, elsewise with an unconfirmed user, the help is activated.
  * @param unknown_type $name of the user.
  * @param unknown_type $pw_hash hash of the password. See pw_hash() in functions.php.
  */
 function login($confirmed, $name, $pw_hash) {
  global $command;

  self :: connectDb();

  // try to pickup and prolong the token
  $sql = @ mysql_query("SELECT t1.id_user AS id, t2.name AS name, t2.email AS email, t2.id_template AS id_template, t2.confirmed AS confirmed, t2.lang as lang, t2.avatar as avatar, t2.FORMAT_DATE as FORMAT_DATE, t2.BASE_HREF AS BASE_HREF, t1.token AS token FROM " . DB_PREFIX . "USAGE t1, " . DB_PREFIX . "USER t2, " . DB_PREFIX . "TEMPLATE t3 WHERE t3.id_user=t2.id AND t3.id=t2.id_template AND t2.name='" . p($name) . "' AND t2.pw_hash='" . p($pw_hash) . "' AND t1.id_user=t2.id");
  if ($sql === FALSE) {
   fail("pickup query failed");
  }
  if ($row = mysql_fetch_array($sql)) {
   $this->token = $row["token"];
  } else {
   mysql_free_result($sql);
   $sql = @ mysql_query("SELECT t2.id AS id, t2.name AS name, t2.email AS email, t2.id_template AS id_template, t2.confirmed AS confirmed, t2.lang AS lang, t2.avatar AS avatar, t2.FORMAT_DATE AS FORMAT_DATE, t2.BASE_HREF AS BASE_HREF FROM " . DB_PREFIX . "USER t2, " . DB_PREFIX . "TEMPLATE t3 WHERE t3.id_user=t2.id AND t3.id=t2.id_template AND t2.name='" . p($name) . "' AND t2.pw_hash='" . p($pw_hash) . "'");
   if ($sql === FALSE) {
    fail("query failed");
   }
   $row = mysql_fetch_array($sql);
   if (is_numeric($row["id"])) {
    $this->token = gen_token($row["name"]);
   }
  }

  $this->fillUser($row);
  mysql_free_result($sql);
  if ($this->getId() != null) {
   if ($confirmed && $this->confirmed != 't') {
    fail("<i18n key='con23'><en>Login not allowed. Please use link in the registration email to confirm user</en><de>Login noch nicht erlaubt. Bitte Link in der Registrierungs-Email zur Bestätigung des Benutzers verwenden</de><fr>Connectez-vous pas autorisés. S'il vous plaît lien dans l'utilisation du courrier électronique d'enregistrement pour confirmer l'utilisateur</fr><es>Entrada no está permitida. Por favor, enlace en el uso de correo electrónico de registro para confirmar del usuario</es></i18n>");
   }
   $insert = @ mysql_query("REPLACE INTO " . DB_PREFIX . "USAGE SET id_user=" . p($this->id) . ", token='" . p($this->token) . "'");
   if (!$insert) {
    fail("unexpected: usage insert failed");
   }
   if (!$confirmed && $this->confirmed != 't') {
    $command = "toggleShowHelp();";
   }

   $this->setState($this->id, "");
   return $this->token;
  } else {
   fail("<i18n key='log0'><en>This combination of login and password and is unknown.</en><de>Diese Kombination aus Login und Passwort ist und unbekannt.</de><fr>Cette combinaison de login et mot de passe et est inconnue.</fr><es>Esta combinación de login y password y no se conoce.</es></i18n>");
  }
 }

 function idLogin($userId) {
  global $command;

  if (!is_numeric($userId)) {
   fail("id $userId is not numeric");
  }

  // try to pickup and prolong the token
  $sql = @ mysql_query("SELECT t1.id_user AS id, t2.name AS name, t2.email AS email, t2.id_template AS id_template, t2.confirmed AS confirmed, t2.lang as lang, t2.avatar as avatar, t2.FORMAT_DATE as FORMAT_DATE, t2.BASE_HREF AS BASE_HREF, t1.token AS token FROM " . DB_PREFIX . "USAGE t1, " . DB_PREFIX . "USER t2, " . DB_PREFIX . "TEMPLATE t3 WHERE t3.id_user=t2.id AND t3.id=t2.id_template AND t2.id=" . $userId . " AND t2.confirmed='t' AND t1.id_user=t2.id");
  if ($sql === FALSE) {
   fail("pickup query failed");
  }
  if ($row = mysql_fetch_array($sql)) {
   $this->token = $row["token"];
  } else {
   mysql_free_result($sql);
   $sql = @ mysql_query("SELECT t2.id AS id, t2.name AS name, t2.email AS email, t2.id_template AS id_template, t2.confirmed AS confirmed, t2.lang AS lang, t2.avatar AS avatar, t2.FORMAT_DATE AS FORMAT_DATE, t2.BASE_HREF AS BASE_HREF FROM " . DB_PREFIX . "USER t2, " . DB_PREFIX . "TEMPLATE t3 WHERE t3.id_user=t2.id AND t3.id=t2.id_template AND t2.confirmed='t' AND t2.id=" . $userId);
   if ($sql === FALSE) {
    fail("query failed");
   }
   $row = mysql_fetch_array($sql);
   if (is_numeric($row["id"])) {
    $this->token = gen_token($row["name"]);
   }
  }

  $this->fillUser($row);
  mysql_free_result($sql);
  if ($this->getId() != null) {
   $insert = @ mysql_query("REPLACE INTO " . DB_PREFIX . "USAGE SET id_user=" . p($this->id) . ", token='" . p(getToken()) . "'");
   if (!$insert) {
    fail("unexpected: usage insert failed");
   }
   return getToken();
  } else {
   fail("unknown id " . $userId);
  }
 }

 function logout() {
  $delete = @ mysql_query("DELETE FROM " . DB_PREFIX . "USAGE WHERE token='" . p(User::getInstance()->getToken()) . "'");
  if (!$delete) {
   fail("delete failed");
  }
 }

 private function fillUser($row) {
  if (!is_numeric($row["id"])) {
   return;
  }
  $this->id=$row["id"];
  $this->name = $row["name"];
  $this->email = $row["email"];
  $this->activeTemplate=$row["id_template"];
  $this->template=new Template($this->id);
  $this->template->load($this->activeTemplate);
  $this->lang = $row["lang"];
  $this->avatar = $row["avatar"];
  $this->dateFormat = $row["FORMAT_DATE"];
  $this->baseHref = $row["BASE_HREF"];
  $this->confirmed = $row["confirmed"];
 }

 function pickup($token) {
  $sql = @ mysql_query("SELECT t1.id_user AS id, t2.name AS name, t2.email AS email, t2.id_template AS id_template, t2.confirmed AS confirmed, t2.lang as lang, t2.avatar as avatar, t2.FORMAT_DATE as FORMAT_DATE, t2.BASE_HREF AS BASE_HREF FROM " . DB_PREFIX . "USAGE t1, " . DB_PREFIX . "USER t2, " . DB_PREFIX . "TEMPLATE t3 WHERE t3.id_user=t2.id AND t3.id=t2.id_template AND t1.token='" . p($token) . "' AND t1.id_user=t2.id");
  if ($row = mysql_fetch_array($sql)) {
   $this->fillUser($row);
   $this->token=$token;
  	mysql_free_result($sql);
  	return $this->id;
  }
  fail("Automatic Logout");
 }

 public static function connectDb() {
  global $db_host;
  global $db_username;
  global $db_password;
  global $db_name;

  if (self ::$dbcnx) { // already connected
   return;
  }

  //CONNECT TO DB
  self ::$dbcnx = @ mysql_connect($db_host, $db_username, $db_password);
  if (!self ::$dbcnx) {
   fail("Datenbank nicht erreichbar, bitte später erneut versuchen");
  }
  mysql_query("SET NAMES 'utf8'");
  //Select the database
  if (!@ mysql_select_db($db_name)) {
   fail("Datenbank nicht korrekt konfiguriert");
  }
 }

 function aid($aid) {
  self::connectDb();

  $sql = @ mysql_query("SELECT id, FORMAT_DATE FROM " . DB_PREFIX . "USER WHERE aid='" . p($aid) . "'");
  if ($row = mysql_fetch_array($sql)) {
   if (is_numeric($row["id"])) {
    $this->id=$row["id"];
    $this->dateFormat = $row["FORMAT_DATE"];
    mysql_free_result($sql);
    return $this->id;
   }
  }
  return null;
 }

 function updateTbody($tbody) {
  $this->template->setValue($tbody);
  $this->template->save();
 }

 function setBaseHref($newBaseHref) {
  $update = @ mysql_query("UPDATE " . DB_PREFIX . "USER SET BASE_HREF='" . p($newBaseHref) . "' WHERE id=" . p($this->id));
  if (!$update) {
   fail("update failed");
  }
  $this->baseHref=$newBaseHref;
 }

 function setUserLang($lang) {
  global $lc;

  if (array_key_exists($lang, $lc)) {
   if ($this->getId() != null) {
    $update = @ mysql_query("UPDATE " . DB_PREFIX . "USER SET lang='" . p($lang) . "' WHERE id=" . p($this->id));
    if (!$update) {
     fail("update failed");
    }
   }
   $this->lang=$lang;
  }
 }

 function setPasswd($password, $verify) {
  if ($password == $verify) {
   $pw_hash = pw_hash($password);
   $update = @ mysql_query("UPDATE " . DB_PREFIX . "USER SET pw_hash='" . p($pw_hash) . "' WHERE id=" . $this->id);
   if (!$update) {
    //ERROR
    fail("passwd failed");
   }
  }
 }

 /**
  * States used in user-cleanup. A user can be marked as 'dwa' (deletion-warning-after). This is the state a user must be in to allow automated deletion.
  */
 public static function getState($id_user) {
  $sql = @ mysql_query("SELECT state FROM " . DB_PREFIX . "USER WHERE id=" . $id_user);
  if ($sql === FALSE) {
   fail("state query failed");
  }
  if ($row = mysql_fetch_array($sql)) {
   $state = $row["state"];
  }
  mysql_free_result($sql);
  return $state;
 }

 public static function setState($id_user, $state) {
  $insert = @ mysql_query("UPDATE " . DB_PREFIX . "USER SET `state`='" . p($state) . "' WHERE id=" . $id_user);
  if (!$insert) {
   fail("update state " . $id_user . " with \"$state\" failed");
  }
 }

 public static function deleteUser($id) {
  $token=null;
  $sql = @ mysql_query("SELECT token FROM " . DB_PREFIX . "USAGE WHERE id_user=".$id);
  if ($row = mysql_fetch_array($sql)) {
   $token = $row["token"];
  }
  mysql_free_result($sql);
  if ($token != null) {
   if (file_exists(dirname(__FILE__).'/../i/'.$token.'/index.php')) {
     unlink(dirname(__FILE__).'/../i/'.$token.'/index.php');
   }
   if (is_dir(dirname(__FILE__).'/../i/'.$token)) {
     rmdir(dirname(__FILE__).'/../i/'.$token);
   }
  }
  $delete = @mysql_query("DELETE FROM " . DB_PREFIX . "USAGE WHERE id_user=".$id);
	if (!$delete) {
		echo("delete failed: "."DELETE FROM " . DB_PREFIX . "USAGE WHERE id_user=".$id."\n");
	}
  $delete = @mysql_query("DELETE FROM " . DB_PREFIX . "ENTRY WHERE id_user=".$id);
	if (!$delete) {
		echo("delete failed: "."DELETE FROM " . DB_PREFIX . "ENTRY WHERE id_user=".$id."\n");
	}
   $delete = @mysql_query("DELETE FROM " . DB_PREFIX . "EVENT WHERE id_user=".$id);
	if (!$delete) {
		echo("delete failed: "."DELETE FROM " . DB_PREFIX . "EVENT WHERE id_user=".$id."\n");
	}
	$delete = @mysql_query("DELETE FROM " . DB_PREFIX . "INFO WHERE id_user=".$id);
	if (!$delete) {
		echo("delete failed: "."DELETE FROM " . DB_PREFIX . "INFO WHERE id_user=".$id."\n");
	}
  $delete = @mysql_query("DELETE FROM " . DB_PREFIX . "TEMPLATE WHERE id_user=".$id);
	if (!$delete) {
		echo("delete failed: "."DELETE FROM " . DB_PREFIX . "TEMPLATE WHERE id_user=".$id."\n");
	}
  $delete = @mysql_query("DELETE FROM " . DB_PREFIX . "CRON WHERE id_user=".$id);
	if (!$delete) {
		echo("delete failed: "."DELETE FROM " . DB_PREFIX . "CRON WHERE id_user=".$id."\n");
	}
	$delete = @mysql_query("DELETE FROM " . DB_PREFIX . "USER WHERE id=".$id);
	if (!$delete) {
		echo("delete failed: "."DELETE FROM " . DB_PREFIX . "USER WHERE id=".$id."\n");
	}
 }
}
?>
