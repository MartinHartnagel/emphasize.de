<?php 
include_once(dirname(__FILE__).'/../includes/config.php');
$export='js';
?>
<form id="dereferForm" method="POST" action="<?php echo($domain.'/');?>"
	target="_blank">
	<input type="hidden" id="dereferDo" name="do" value="derefer" /> <input
		type="hidden" id="dereferUrl" name="url" value="" /> <input
		type="submit" id="dereferSubmit" name="submit" value="Submit" />
</form>
</body>
</html>
