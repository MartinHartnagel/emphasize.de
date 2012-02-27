<?php 
include_once("blog_config.php"); 
$title="Blog Author";
include($blog_base . "head.php");
?>
<frameset cols="*,300">
  <frame src="lyric.php" name="lyric">
  <frame src="media.php" name="media">
  <noframes>
    <body>
      <p>frames must be enabled to author blogs</p>
    </body>
  </noframes>
</frameset>
</html>
