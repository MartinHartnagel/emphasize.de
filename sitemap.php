<?php
header('Content-Type: text/xml;charset=UTF-8');
echo('<?xml version=\'1.0\' encoding=\'UTF-8\'?>'."\n"); ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?php
include_once(dirname(__FILE__)."/includes/config.php");
$export="xml";
$no_ob_start=true;
if (strpos($domain, "://".$_SERVER['SERVER_NAME']) === false) {
  if (isset($domains)) {
    foreach($domains as $l=>$d) {
      if (strpos($d, "://".$_SERVER['SERVER_NAME']) !== false) {
        $domain=$d;
        break;
      }
    }
  }
}
?>
<url>
<loc><?php echo($domain);?>/</loc>
<lastmod>2011-09-06</lastmod>
<changefreq>daily</changefreq>
<priority>0.2</priority>
</url>
<url>
<loc><?php echo($domain);?>/agb.php</loc>
<lastmod>2011-09-06</lastmod>
<changefreq>monthly</changefreq>
<priority>0.1</priority>
</url>
<url>
<loc><?php echo($domain);?>/?lang=de</loc>
<lastmod>2011-09-06</lastmod>
<changefreq>daily</changefreq>
<priority>0.4</priority>
</url>
<url>
<loc><?php echo($domain);?>/?lang=en</loc>
<lastmod>2011-09-06</lastmod>
<changefreq>daily</changefreq>
<priority>0.4</priority>
</url>
<?php
User::connectDb();
$sql = @mysql_query("SELECT log_id, log_heading, log_date, log_author FROM emphasize_blog WHERE TIMESTAMP(log_date, log_time) <= CURRENT_TIMESTAMP ORDER BY log_id ASC");
while ($row = mysql_fetch_array($sql)){
	$title=$row["log_heading"];
	$lang=$row["log_author"];
	$file=longLink($title, $row["log_id"]);
	echo(" <url>\n   <loc>" . $domain . "/" . $file . "</loc>\n   <lastmod>".$row["log_date"]."</lastmod>\n    <changefreq>daily</changefreq>\n   <priority>0.9</priority>\n </url>\n");
}
mysql_free_result($sql);
?> </urlset>
