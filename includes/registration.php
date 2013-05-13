<?php
include_once(dirname(__FILE__)."/../util/securimage.php");
$img = new Securimage();
$valid = $img->check(r('code'));
if (session_id() != '' ) {
 session_destroy();
 unset($_SESSION);
}

if ($valid == true
) {
 if (r("name") == null) {
  fail("name not specified");
 }
 $name = r("name");
 if (strlen($name) < 2) {
  fail("Sorry, user name is not long enough (>1 character)");
 }

 if (r("password") == null) {
  fail("password not specified");
 }
 $pw_hash = pw_hash(r("password"));
 if (r("password") != r("verify")) {
  fail("Sorry, password does not verify");
 }

 $email = r("email");
 if (strlen($email) < 6) {
  fail("Sorry, email is not long enough (>5 character)");
 }

 if (r("termsAndConditions")!="readAccepted") {
  fail("Sorry, terms and conditions not accepted");
 }

 $plang = trim(r("lang"));
 if (!array_key_exists($plang,$lc)) {
  fail("Sorry, unrecognized language ".$plang);
 }

 User :: connectDb();
 $confirmcode=confirm_code();

 $aid=generateUniqueId(5, "USER", "aid");

 $insert = @mysql_query("INSERT INTO " . DB_PREFIX . "USER SET aid='".p($aid)."', name='".p($name)."', pw_hash='".p($pw_hash)."', email='".p($email)."', id_template=0, lang='".p($plang)."', confirmed='".p($confirmcode)."'");
 if (!$insert) {
  fail("unexpected: user insert failed: "."INSERT INTO " . DB_PREFIX . "USER SET aid='".p($aid)."', name='".p($name)."', pw_hash='".p($pw_hash)."', email='".p($email)."', id_template=0, lang='".p($plang)."', confirmed='".p($confirmcode)."'");
 }
 $nid=mysql_insert_id();

 $lang=detectLang();
 $id_template=0;
 // create initial template entries
 foreach($tbody_value as $k=>$v) {
  $key=i18n($tbody_names[$k]);
  $value=i18n($v);
  $template=new Template($nid);
  $template->setName($key);
  $template->setValue($value);
  $template->save();
  if ($id_template == 0) {
   $id_template = $template->getId();
  }
 }
 $update = @mysql_query("UPDATE " . DB_PREFIX . "USER SET id_template=".$id_template." where id=".$nid);
 if (!$update) {
  fail("unexpected: user update failed");
 }

 User::getInstance()->login(false, $name, $pw_hash, 60); // register-login for 60 mins
 include_once(dirname(__FILE__)."/dashboard.php");

 $confirm_url=DOMAIN."/util/confirm.php?".$confirmcode;

 $title=str_replace("_name_", $name, "[Emphasize] ".i18n("<i18n key='reg0'><en>Confirm _name_ Registration</en><de>Bestätigung der _name_ Registration</de><fr>Confirmation d'inscription _name_</fr><es>La confirmación de inscripción _name_</es></i18n>"));
 $body=str_replace(array("_name_", "_domain_", "_confirm_url_"), array($name, DOMAIN, $confirm_url), i18n("<i18n key='reg1'><en>Hello _name_,\r\nthis email has automatically been sent to you to confirm your registration for a user _name_ on _domain_.\r\nPlease click on the following link to confirm your registration (or copy and paste it to your browser):\r\n\r\n_confirm_url_ \r\nYou will be able to login to the _name_ account with your given password thereafter.\r\nIf for any reason you did not apply for registration then just ignore this email and don't click on the link above.\r\nKind regards,</en><de>Guten Tag _name_,\r\ndiese Email wurde automatisch an Sie verschickt, um die Registration des Benutzers _name_ auf _domain_ zu bestätigen.\r\nBitte Klicken sie auf den folgenden Hyperlink (oder Kopieren Sie die Adresse in Ihren Browser):\r\n_confirm_url_ \r\nEin Login mit dem Benutzerkonto _name_ und dem von Ihnen gewählten Passwort ist dann möglich.\r\nBitte klicken Sie nicht auf den Hyperlink, wenn Sie sich nicht registriert haben.\r\nMit freundlichen Grüssen,</de><fr>Bonjour _name_,\r\nce courriel a été envoyé automatiquement au nom de l'enregistrement de l'utilisateur de confirmer le nom _name_ en _domain_.\r\nS'il vous plaît cliquer sur le lien suivant (ou de copier l'adresse dans votre navigateur):\r\n\r\n_confirm_url_ \r\nAlors il est possible de utiliser le compte du _name_ avec votre mot de passe.\r\nS'il vous plaît ne pas cliquer sur le lien hypertexte, si vous n'êtes pas inscrit.\r\nCordialement,</fr><es>Hola _name_,\r\neste correo electrónico fue enviado automáticamente a usted el nombre _name_ del registro del usuario para confirmar en _domain_.\r\n
   Por favor, haga clic en el siguiente enlace (o copiar la dirección en su navegador):\r\n\r\n_confirm_url_ \r\nUn inicio de sesión con el nombre _name_ de cuenta de usuario y contraseña es elegido posible.\r\n
   Por favor, no haga clic en el hipervínculo, si no se ha registrado.\r\n
   Le saluda atentamente,</es></i18n>\r\n  Martin Hartnagel."));
 instantMail($email, $title, $body, "From: $registration_from\r\nMime-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: quoted-printable\r\n\r\n");
 echo(str_replace("_name_", $name, i18n("<i18n key='reg3'><en>pre confirmed login with _name_ valid for one hour</en><de>_name_ Login mit noch nicht bestätigter Registrierung gültig für eine Stunde</de><fr>Connexion avec _name_ pas encore confirmées d'enregistrement valide pour une heure</fr><es>Ingresar con _name_ el registro aún no confirmados válidos durante una hora</es></i18n>")));
 if (!(isset($testing) && $testing)) {
  // inform admin
  enqueueMail($feedback_to, "[Emphasize] Registration: $name", "$name on DOMAIN with $email", "From: $feedback_to\r\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: 8bit\r\n\r\n");
 }
} else {
 fail("Sorry, the code \"".r('code')."\" you entered was invalid");
}
?>
