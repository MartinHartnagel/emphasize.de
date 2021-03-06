<?php
// freigeschaltet
$al=array("en","de","fr","es");

// alle Sprachen
$lc=array("aa"=>"Afar", "ab"=>"Abkhazian", "af"=>"Afrikaans", "am"=>"Amharic", "ar"=>"Arabic", "as"=>"Assamese", "ay"=>"Aymara", "az"=>"Azerbaijani", "ba"=>"Bashkir", "be"=>"Byelorussian", "bg"=>"Bulgarian", "bh"=>"Bihari", "bi"=>"Bislama", "bn"=>"Bengali", "bo"=>"Tibetan", "br"=>"Breton", "ca"=>"Catalan", "co"=>"Corsican", "cs"=>"Czech", "cy"=>"Welch", "da"=>"Danish", "de"=>"Deutsch - German", "dz"=>"Bhutani", "el"=>"Greek", "en"=>"English", "eo"=>"Esperanto", "es"=>"Español - Spanish", "et"=>"Estonian", "eu"=>"Basque", "fa"=>"Persian", "fi"=>"Finnish", "fj"=>"Fiji", "fo"=>"Faeroese", "fr"=>"français - French", "fy"=>"Frisian", "ga"=>"Irish", "gd"=>"Scots Gaelic", "gl"=>"Galician", "gn"=>"Guarani", "gu"=>"Gujarati", "ha"=>"Hausa", "hi"=>"Hindi", "he"=>"Hebrew", "hr"=>"Croatian", "hu"=>"Hungarian", "hy"=>"Armenian", "ia"=>"Interlingua", "id"=>"Indonesian", "ie"=>"Interlingue", "ik"=>"Inupiak", "in"=>"former Indonesian", "is"=>"Icelandic", "it"=>"Italian", "iu"=>"Inuktitut (Eskimo)", "iw"=>"former Hebrew", "ja"=>"Japanese", "ji"=>"former Yiddish", "jw"=>"Javanese", "ka"=>"Georgian", "kk"=>"Kazakh", "kl"=>"Greenlandic", "km"=>"Cambodian", "kn"=>"Kannada", "ko"=>"Korean", "ks"=>"Kashmiri", "ku"=>"Kurdish", "ky"=>"Kirghiz", "la"=>"Latin", "ln"=>"Lingala", "lo"=>"Laothian", "lt"=>"Lithuanian", "lv"=>"Latvian, Lettish", "mg"=>"Malagasy", "mi"=>"Maori", "mk"=>"Macedonian", "ml"=>"Malayalam", "mn"=>"Mongolian", "mo"=>"Moldavian", "mr"=>"Marathi", "ms"=>"Malay", "mt"=>"Maltese", "my"=>"Burmese", "na"=>"Nauru", "ne"=>"Nepali", "nl"=>"Dutch", "no"=>"Norwegian", "oc"=>"Occitan", "om"=>"(Afan) Oromo", "or"=>"Oriya", "pa"=>"Punjabi", "pl"=>"Polish", "ps"=>"Pashto, Pushto", "pt"=>"Portuguese", "qu"=>"Quechua", "rm"=>"Rhaeto-Romance", "rn"=>"Kirundi", "ro"=>"Romanian", "ru"=>"Russian", "rw"=>"Kinyarwanda", "sa"=>"Sanskrit", "sd"=>"Sindhi", "sg"=>"Sangro", "sh"=>"Serbo-Croatian", "si"=>"Singhalese", "sk"=>"Slovak", "sl"=>"Slovenian", "sm"=>"Samoan", "sn"=>"Shona", "so"=>"Somali", "sq"=>"Albanian", "sr"=>"Serbian", "ss"=>"Siswati", "st"=>"Sesotho", "su"=>"Sudanese", "sv"=>"Swedish", "sw"=>"Swahili", "ta"=>"Tamil", "te"=>"Tegulu", "tg"=>"Tajik", "th"=>"Thai", "ti"=>"Tigrinya", "tk"=>"Turkmen", "tl"=>"Tagalog", "tn"=>"Setswana", "to"=>"Tonga", "tr"=>"Turkish", "ts"=>"Tsonga", "tt"=>"Tatar", "tw"=>"Twi", "ug"=>"Uigur", "uk"=>"Ukrainian", "ur"=>"Urdu", "uz"=>"Uzbek", "vi"=>"Vietnamese", "vo"=>"Volapuk", "wo"=>"Wolof", "xh"=>"Xhosa", "yi"=>"Yiddish", "yo"=>"Yoruba", "za"=>"Zhuang", "zh"=>"Chinese", "zu"=>"Zuluaa");

function i18n($text, $useLang = "") {
 global $i18n;

 if ($useLang != "") {
  $lang = $useLang;
 } else {
  $lang = detectLang();
 }

 $text = str_replace(
   array('<lang/>', '<LANG/>', '<domain/>', '<app_name/>', '<version/>', '{lang/}', '{LANG/}', '{domain/}', '{app_name/}', '{version/}'),
   array($lang, strtoupper($lang), DOMAIN, APP_NAME, VERSION, $lang, strtoupper($lang), DOMAIN, APP_NAME, VERSION),
   $text);
 $text = preg_replace('/[<{]i18n\s+?key=/imsU', "<i18n ref=", $text);
 $text = preg_replace('/[>}]\s*?[<{]\/i18n[>}]\s*?[<{]/imsU', "/><", $text);
 $text = preg_replace('/[>}]\s*?[<{]i18n/imsU', '><i18n', $text);
 $text = preg_replace('/[>}]\s*?[<{]\/i18n[>}]\s*?/imsU', "/> ", $text);
 $text = preg_replace('/\s*?[<{]i18n/imsU', ' <i18n', $text);
 $text = preg_replace('/[<{]i18n\s+?/imsU', '<i18n ', $text);
 while (($f = strpos($text, "<i18n ref=")) !== false) {
  $sep = substr($text, $f +10, 1);
  $e = strpos($text, $sep, $f +11);
  $key = substr($text, $f +11, $e - $f -11);
  if (!isset ($i18n[$key . "." . $lang]) || $i18n[$key . "." . $lang] == "") {
   if (!isset ($i18n[$key . ".en"]) || $i18n[$key . ".en"] == "") {
    $result = "[undefined i18n ref='" . $key . ".en']";
   } else {
    $result = trim($i18n[$key . ".en"]);
   }
  } else {
   $result = trim($i18n[$key . "." . $lang]);
  }
  $result = str_replace(
   array('<lang/>', '<LANG/>', '<domain/>', '<app_name/>', '<version/>', '{lang/}', '{LANG/}', '{domain/}', '{app_name/}', '{version/}'),
   array($lang, strtoupper($lang), DOMAIN, APP_NAME, VERSION, $lang, strtoupper($lang), DOMAIN, APP_NAME, VERSION),
   $result);
  $te = strpos($text, "/>", $f);
  $ta = strpos($text, "/}", $f);
  if ($ta != false && $ta < $te) {
   $te=$ta;
  }
  $text = substr_replace($text, $result, $f, $te - $f +2);
 }

 return $text;
}
?>
