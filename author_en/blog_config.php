<?php
$author = "en"; //author
$name = "Martin"; // name
$surname = "Hartnagel"; // surname
$feedback_to = "martin-anywhere@emphasize.de"; //mail address(es) to send feedback to
DB_PREFIX="emphasize_";

include_once(dirname(__FILE__).'/base_config.php');

$domain="http://emphasize.de";

function entry($id) {
  global $author;
  global $domain;
  
  $sql = mysql_query("SELECT log_heading, log_text, log_day, log_date, log_time FROM " . DB_PREFIX . "blog WHERE log_author='$author' AND log_id='$id'"); 
  if ($row = mysql_fetch_array($sql)) {
      $header = stripslashes($row["log_heading"]);  
      echo("<h2>" . $header . "</h2></div></div></div>\n");
      echo("<p>[<a href=\"" . longUrl($header, $id) . "\" target=\"".$header."\">link</a>]<br/><br/></p>\n");
      $text=stripslashes($row["log_text"]);
      echo("<p>" . $text . "</p><br clear=\"all\" />\n");
      $bl=getBacklinksListed($id);
      if ($bl != "") {
        echo("<br/><p><b>" . $header . " [<a href=\"" .longUrl($row["log_heading"], $id). "\">backlinks</a>]:</b><ul>" . $bl . "</ul></p>\n");
      }
      echo("</div></div></div>\n");
  } else {
    echo("missing entry ".$id);
  }
}

function headers($id) {
  global $author;
  global $domain;
  global $blogsPreviewHalf;
  global $filter;
  
  $maxHalf=$blogsPreviewHalf;

  if (isset($filter) && ($filter) != "") {
    $filter_ql="AND log_heading LIKE '%".$filter."%'";
  }

  $txt="";
  $script="";
  $latestId=-1;
  // after
  if (isset($id) && ($id > 0)) {
    $count=0;
    $entry_ql="AND log_id >= ".$id;

    $sql = mysql_query("SELECT log_id, log_heading, log_date, log_day, log_time FROM " . DB_PREFIX. "blog WHERE log_author='$author' AND log_heading NOT LIKE '%_special_%' $entry_ql $filter_ql AND TIMESTAMP(log_date, log_time) <= CURRENT_TIMESTAMP ORDER BY log_id ASC LIMIT 0,$maxHalf"); 

    while ($row = mysql_fetch_array($sql)) {
      if ($latestId == -1) {
        $latestId = $row["log_id"];
      }
      $txt = printRow($row) . $txt;
      $script = "rel += relDelta;\nmove(" . $row["log_id"] . ", rel);\n" . $script;
      $count++;
    }

    $maxHalf=$maxHalf*2 - $count;
    // before
    $entry_ql="AND log_id < ".$id;

    $sql = mysql_query("SELECT log_id, log_heading, log_date, log_day, log_time FROM " . DB_PREFIX. "blog WHERE log_author='$author' AND log_heading NOT LIKE '%_special_%' $entry_ql $filter_ql AND TIMESTAMP(log_date, log_time) <= CURRENT_TIMESTAMP ORDER BY log_id DESC LIMIT 0,$maxHalf"); 

    while ($row = mysql_fetch_array($sql)) {
      if ($latestId == -1) {
        $latestId = $row["log_id"];
      }
      $txt = $txt . printRow($row);
      $script = $script . "rel += relDelta;\nmove(" . $row["log_id"] . ", rel);\n";
    }
  } else {

    $maxHalf=$maxHalf*2;

    $sql = mysql_query("SELECT log_id, log_heading, log_date, log_day, log_time FROM " . DB_PREFIX. "blog WHERE log_author='$author' AND log_heading NOT LIKE '%_special_%' $filter_ql AND TIMESTAMP(log_date, log_time) <= CURRENT_TIMESTAMP ORDER BY log_id DESC LIMIT 0,$maxHalf"); 
    while ($row = mysql_fetch_array($sql)) {
      if ($latestId == -1) {
        $latestId = $row["log_id"];
      }
      $txt = $txt . printRow($row);
      $script = $script . "rel += relDelta;\nmove(" . $row["log_id"] . ", rel);\n";
    }
  }

  echo($txt . "\n<script type=\"text/javascript\">\n//<!--\nvar rel=0;\nvar relDelta=0.12;" . $script . "\nflushMove();\n//--></script>\n");
  return $latestId;
}

function longUrl($header, $id) {
  global $domain;
  
  return $domain."/".longLink(stripslashes(str_replace(array("&amp;","'","#252;","#246;", "&uuml;"), array("and","","u","oe","ue"), $header)), $id);
}

function printRow($row) {
    global $domain;
    global $fullname;

    $header = stripslashes($row["log_heading"]);    
    $id = $row["log_id"];
    return '<a id="head' . $id . '" class="header" href="'.longUrl($row["log_heading"], $id).'" onclick="loadEntry(' . $id . '); loadBlogs(' . $id . '); return false;" title="'. $row["log_day"] . " " . $row["log_date"] . " " . $row["log_time"] .'">'. $header .'</a>'."\n";
}

?>
