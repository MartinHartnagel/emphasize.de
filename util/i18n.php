<?php
include_once(dirname(__FILE__).'/../includes/config.php');
if (isset($_GET["standalone"]) && $_GET["standalone"]!="true") {
	$export="js";
}

		if (r("do") == "writeI18N") {
			User::connectDb();
			writeI18N(r("translate"), r("key"), r("value"));
			echo("written");
			exit();
		}
header("Content-Type: text/html;charset=UTF-8");
$from=r("from");
if (strlen($from) == 0) {
	$from="en";
}
$translate=r("translate");
if (!array_key_exists($translate, $lc)) {
	echo("translation to ".$translate." not supported.");
	exit();
}
User::connectDb();
$set=getI18Ns($from, $translate);
foreach($set as $k=>$v) {
	echo("<div class=\"translate\" id=\"$k\" style=\"display:none;\">".$lc[$from]." text:<br/><textarea readonly=\"true\" style=\"width:100%;background-color:white;color:blue;\">".$v[0]."</textarea><br/><br/>translated to ".$lc[$translate].":<br/><textarea style=\"width:100%;background-color:white;\">".$v[1]."</textarea></div>\n");
}
$process=getI18NProcess($translate);
?>
<script type="text/javascript">
  var translate="<?php echo($translate) ?>";
  var i=0;
  var c=<?php echo($process[0]) ?>;
  var n=<?php echo($process[1]) ?>;
  var unchanged="";
  var changes=new Array();
  $('div.translate:eq('+i+')').show();
  $('#current').attr("title", $('div.translate:eq('+i+')').attr('id'));
  unchanged=$('div.translate:eq('+i+') textarea:eq(1)').val();
  function detectChange() {
    var changed=$('div.translate.translate:eq('+i+') textarea:eq(1)').val();
    if (changed != unchanged) {
      var key=$('div.translate:eq('+i+')').attr('id');
      //alert(changed.replace(/:/, '\\:'));
      $.ajax({
        url:domain+"i18n.php", type: "POST", async:true, dataType: "html",
        data: ({ "do": "writeI18N", "translate": translate, "key": key, "value": changed.replace(/:/, '\\:') }),
        success: function(msg){
          if (changes.indexOf(key) == -1) {
            changes.push(key);
            $('#progress').html((Math.ceil((c+changes.length)*100/n))+"% ("+(c+changes.length)+"/"+n+")");
          }
        }, error: function(req, status, error) {
          alert("Saving changes failed,\nplease try again later!");
        }});
    }
  }
  function back() {
    detectChange();
    $('div.translate:eq('+i+')').hide();
    i=(i+n-1)%n;
    $('#current').html((i+1));
    $('#current').attr("title", $('div.translate:eq('+i+')').attr('id'));
    $('div.translate:eq('+i+')').show();
    $('div.translate:eq('+i+') textarea:eq(1)').focus();
    unchanged=$('div.translate:eq('+i+') textarea:eq(1)').val();
  }
  function forward() {
    detectChange();
    $('div.translate:eq('+i+')').hide();
    i=(i+n+1)%n;
    $('#current').html((i+1));
    $('#current').attr("title", $('div.translate:eq('+i+')').attr('id'));
    $('div.translate:eq('+i+')').show();
    $('div.translate:eq('+i+') textarea:eq(1)').focus();
    unchanged=$('div.translate:eq('+i+') textarea:eq(1)').val();
  }
</script>
<table border="0" width="100%">
	<tr>
		<td><span style="white-space: nowrap;"> <a href="javascript:back();"><img
					id="back" src="graphics/b.png" title="back" height="32" width="32"
					border="0" align="middle"> </a> <b>#<span id="current" title=""
					style="width: 50px; text-align: center;">1</span>
			</b> <a href="javascript:forward();"><img id="forward"
					src="graphics/f.png" title="forward" height="32" width="32"
					border="0" align="middle"> </a>
		</span>
		</td>
		<td width="99%">translation progress: <span id="progress"> <?php
		echo(floor($process[0]*100/$process[1]).'% ('.$process[0].'/'.$process[1].')');
		?>
		</span>
		</td>
		<td>
			<button name="finish" type="button" value="Close"
				onclick="detectChange();alert('Thank you very much! After reviewing your input the <?php echo($lc[$translate]);?> version\nwill be made available on http://emphasize.de as soon as possible.');aboveClose();">Close</button>
		</td>
	</tr>
</table>
