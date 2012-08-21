<?php header("Content-Type: text/html;charset=UTF-8"); 
include_once("blog_config.php");  
$formACTION = "lyric.php";
$title="Blogging";
include($blog_base . "head.php");
include($blog_base."blog.php"); 
?>
<body onload="initDefaults()">
<?php include("../../../files/blog/add.php"); ?>
<?php include("../../../files/blog/edit.php"); ?>
<script>
function initDefaults() {
 if (document.authorEntry.heading.value=="") {
  document.authorEntry.heading.value="Arbeitszeiterfassung - ";
  document.authorEntry.text.value="...";
  document.authorEntry.heading.focus();
 }
}
</script>
</body>
</html>
