<?php
class Template {

 private $userId;
 private $templateId;
 private $name;
 private $value;

 public function __construct($userId) {
  $this->userId=$userId;
 }

 function getId() {
  return $this->templateId;
 }

 function getName() {
  return $this->name;
 }

 function setName($name) {
  $this->name=$name;
 }

 function getValue() {
  return $this->value;
 }

 function setValue($value) {
  $this->value=$value;
 }

 function save() {
  if ($this->templateId==null) {
  $insert = @mysql_query("INSERT INTO " . DB_PREFIX . "TEMPLATE SET id_user='".p($this->userId)."', name='".p($this->name)."', content='".p($this->value)."'");
  if (!$insert) {
   fail("unexpected: template ".$this->name." insert failed");
  }
  $this->templateId = mysql_insert_id();
  } else {
   $update = @mysql_query("REPLACE INTO " . DB_PREFIX . "TEMPLATE SET id_user='".p($this->userId)."', id='".p($this->templateId)."', name='".p($this->name)."', content='".p($this->value)."'");
   if (!$update) {
    fail("unexpected: template ".$this->name." update failed");
   }
  }
 }

 function load($templateId=null) {
  if ($this->value != null) {
   return;
  }
  if ($templateId==null) {
   $templateId=$this->templateId;
  }
  $sql = @mysql_query("SELECT content FROM " . DB_PREFIX . "TEMPLATE WHERE id_user='".p($this->userId)."' AND `ID`='" . p($templateId) . "'");
  if($row = mysql_fetch_array($sql)) {
   $this->value=$row["content"];
  } else {
   fail("unexpected: template ".$templateId." of ".$this->userId." not found");
  }
  mysql_free_result($sql);
  return $this;
 }

 /**
  * Returns an associative array of template-names to templates (which have no value loaded).
  * @param unknown_type $userId of the user.
  * @return multitype:Template an associative array of template-names to templates (which have no value loaded).
  */
 public static function getTemplates($userId) {
  $templates=array();
  $sql = @mysql_query("SELECT id, name FROM " . DB_PREFIX . "TEMPLATE WHERE id_user='".p($userId)."'");
  while($row = mysql_fetch_array($sql)) {
   $template=new Template($userId);
   $template->templateId=$row["id"];
   $template->name=$row["name"];
   $templates[$template->name]=$template;
  }
  mysql_free_result($sql);
  return $templates;
 }
}
?>