<?php
$no_ob_start = true;
$testing=true;
$ob_cancel=true;
$lang="de";

function getTestOut($buffer) {
 global $ob_buffer;
 $ob_buffer=i18n($buffer);
 return "";
}

function deleteDirectory($dir) {
 if (!file_exists($dir)) return true;
 if (!is_dir($dir) || is_link($dir)) return unlink($dir);
 foreach (scandir($dir) as $item) {
  if ($item == '.' || $item == '..') continue;
  if (!deleteDirectory($dir . "/" . $item)) {
   chmod($dir . "/" . $item, 0777);
   if (!deleteDirectory($dir . "/" . $item)) return false;
  };
 }
 return rmdir($dir);
}

function unzip($path_file, $destiny)
{
 $zip = zip_open($path_file);
 $_tmp = array();
 $count=0;
 if ($zip)
 {
  while ($zip_entry = zip_read($zip))
  {
   $_tmp[$count]["filename"] = zip_entry_name($zip_entry);
   $_tmp[$count]["stored_filename"] = zip_entry_name($zip_entry);
   $_tmp[$count]["size"] = zip_entry_filesize($zip_entry);
   $_tmp[$count]["compressed_size"] = zip_entry_compressedsize($zip_entry);
   $_tmp[$count]["mtime"] = "";
   $_tmp[$count]["comment"] = "";
   $_tmp[$count]["folder"] = dirname(zip_entry_name($zip_entry));
   $_tmp[$count]["index"] = $count;
   $_tmp[$count]["status"] = "ok";
   $_tmp[$count]["method"] = zip_entry_compressionmethod($zip_entry);

   if (zip_entry_open($zip, $zip_entry, "r"))
   {
    $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

    // Create Recursive Directory
    mkdirr(dirname($destiny . zip_entry_name($zip_entry)), 0700);

    $fp = fopen($destiny . zip_entry_name($zip_entry), "w");
    fwrite($fp, $buf);
    fclose($fp);

    zip_entry_close($zip_entry);
   }
  }

  zip_close($zip);
 }
}

function mkdirr($pn,$mode=null) {

 if(is_dir($pn)||empty($pn)) return true;
 $pn=str_replace(array('/', ''),DIRECTORY_SEPARATOR,$pn);

 if(is_file($pn)) {
  trigger_error('mkdirr() File exists', E_USER_WARNING);return false;
 }

 $next_pathname=substr($pn,0,strrpos($pn,DIRECTORY_SEPARATOR));
 if(mkdirr($next_pathname,$mode)) {
  if(!file_exists($pn)) {
   return mkdir($pn,$mode);
  }
 }
 return false;
}

/**
 * Recursively searches in files matching $pathname_pattern below $dir if $match is contained.
 * Returns pathnames of matching files in an array.
 * @param unknown_type $dir
 * @param unknown_type $pathname_pattern
 * @param unknown_type $match
 * @return pathnames of matching files in an array.
 */
function file_grep($dir, $pathname_pattern, $match) {
 $result=array();
 $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
 foreach ($iterator as $name => $fileinfo) {
  if ($fileinfo->isFile() && preg_match($pathname_pattern, $fileinfo->getPathname())) {
   if (preg_match($match, file_get_contents($fileinfo->getPathname()))) {
    $result[]=$fileinfo->getPathname();
   }
  }
 }
 return $result;
}

?>