<?php
include_once (dirname(__FILE__) .
'/includes/config.php');
header("Content-Type: text/html;charset=UTF-8");
?>
<title>Time-Reporting Emphasize Roadmap</title>
<script type="text/javascript">
  function inc(id, orig) {
    var pre=$("#v"+id).text()*1;
    var next=pre+1;
    if (Math.abs(next-orig) >1) {
      return false;
    }
    $("#v"+id).html(next);
    $.ajax({
      url : domain+"roadmap.php",
      type : "GET",
      async : true,
      dataType : "html",
      data : ({
        "vote" : id,
        "type" : "inc",
        "o": orig
      })
    });
    return false;
  }

  function dec(id, orig) {
    var pre=$("#v"+id).text()*1;
    var next=pre-1;
    if (Math.abs(next-orig) >1) {
      return false;
    }
    $("#v"+id).html(next);
    $.ajax({
      url : domain+"roadmap.php",
      type : "GET",
      async : true,
      dataType : "html",
      data : ({
        "vote" : id,
        "type" : "dec",
        "o": orig
      })
    });
    return false;
  }
</script>
</head>
<body>
<h1>Time-Reporting Emphasize Roadmap</h1>
<p>This is the roadmap for the <a href="<?php echo(DOMAIN); ?>" title="track and report your working time with the free tool emphasize">time-reporting tool emphasize</a>.<br/>
Dies ist die weitere Planung der <a href="<?php echo(DOMAIN); ?>" title="Erfasse Deine Arbeitszeit mit dem kostenlosen Webdienst Emphasize">Arbeits-Zeit-Erfassung Emphasize</a>.</p>
<ul>
  <li><a href="roadmap.php#t">Tasks</a></li>
  <li><a href="roadmap.php#d">Discussed Features</a></li>
  <li><a href="roadmap.php#s">Solveds</a></li>
</ul>
<p><i>This content will not be translated, but left in language as reported.</i></p>
<a name="t">&nbsp;</a>
<h2>Tasks</h2>
<?php
  if (file_exists(dirname(__FILE__)."/cache/votes.php")) {
  	$v=unserialize(file_get_contents(dirname(__FILE__)."/cache/votes.php"));
  } else {
  	$v=array();
  }

  $c=0;
  $changes=false;
  function printTable($dateText, $a) {
  	global $v;
  	global $c;
  	global $changes;

  	echo('<table class="bodyTable">
  <tbody><tr class="a">
    <th style="width: 50px;">Version</th>
    <th><nobr>'.$dateText.'</nobr></th>
    <th>Description</th>
    <th>Votes</th>
  </tr>'."\n");
  	$even=false;
  	foreach($a as $e) {
  		if (!isset($v[$e[1].":".$e[2]])) {
  			$v[$e[1].":".$e[2]]=0;
  		}
  		if (isset($_GET["vote"])) {
  			if ($c==$_GET["vote"]
  					&& ($v[$e[1].":".$e[2]] == $_GET["o"])) {
  				if ($_GET["type"] == "inc") {
  					$v[$e[1].":".$e[2]]++;
  					$changes=true;
  				} elseif ($_GET["type"] == "dec") {
  					$v[$e[1].":".$e[2]]--;
  					$changes=true;
  				}
  			}
  		}
  	  $vote=$v[$e[1].":".$e[2]];
  		echo("<tr class=\"".($even?"even":"odd")."\"><td valign=\"top\">".$e[0]."</td><td valign=\"top\">".$e[1]."</td><td valign=\"top\">".$e[2]."</td><td valign=\"top\" align=\"center\"><a href=\"#\" onclick=\"return inc(".$c.", ".$vote.");\" title=\"increase importance\">+</a>&nbsp;<span id=\"v".$c."\">".$vote."</span>&nbsp;<a href=\"#\" onclick=\"return dec(".$c.", ".$vote.");\" title=\"decrease importance\">-</a></td></tr>\n");

  		$even=!$even;
  		$c++;
  	}
  	echo('</tbody></table>'."\n");
  }
?>

<?php
  $a=array(
  array("1.x", "2012-06-05", "make a settings-option for automatically set to Pause on browser unload"),
  array("1.x", "2012-05-07", "Plausibilitäts-Check einbauen, also wenn ein Eintrag gemacht werden soll, der die Server-Zeit um +25h überschreitet, dass dann eine Warnung kommt."),
  array("1.x", "2012-04-25", "In dem Textfeld zum Springen zu einem Datum würde mit einem Monats-Kalender das springen zu einem zurückliegendem Tag einfacher sein."),
    array("1.x", "2012-04-25", "Pfeile um Event-Start-Weise in der Zeit vor/zurück zu springen."),
    array("1.x", "2012-04-20", "Ermöglichen bestimmte Daten (z.B. die Erfassung ganzer Tage) zu löschen."),
  array("1.x", "2012-04-11", "zu Emphasize eine Art HTTP Api geben würde, könnte man ein script schreiben, das svn oder git Kommentare direkt als Kommentar ins emphasize schreibt.

$ git commit -a -m \"Fixed this strange Bug\"

Das würde dann eventuell diese URL aufrufen:  http(s)://arbeits-zeit-erfassung.emphasize.de/addComment?message=Fixed%20this%20strange%20Bug&user=andreas&paswort=xxx

Auf diese Art würde man etliche klicks sparen und auf eine relativ einfache Art seinen Arbeitsverlauf mit dokumentieren. Der Konzentration würde es ebenfalls zugute kommen und auch die Qualität der Code Kommentare würde meiner Meinung nach steigen."),
    array("1.x", "2012-02-13", "Zeiten auf 15 Minuten runden: Eventuell macht es Sinn, etwas einzurichten, dass in Mails und Berichten gleich dahinter eine spalte mit den gerundeten Werten steht. Wenn man die Exakten Werte mit angibt, hat man meiner meinung nach nicht so viele problemem mit rundungsfehlern. Die Rundungszeiteinheit sollte konfigurierbar sein."),
    array("1.x", "2012-02-13", "Einrichten eines Account Bereichs: Dort sollte es möglich sein das passwort zu ändern, die Funktion des Avatars kann auch da mit rein."),
    array("1.x", "2012-02-06", "aktuell deployte emphasize versionsnummer, in Footer eingebunden wäre. Eventuell könnte das auch ein Link sein zum changelog und der roadmap sein?"),
    array("1.x", "2011-12-29", "select-boxen mit style"),
    array("2.0.0", "2011-12-29", "Misplaced-Title zeigt html an"),
    array("1.x", "2011-11-28", "Bericht der einen Zeitplan erstellt im Format <strong>8:00 - 9:27 Meeting</strong>"),
    array("1.x", "2011-11-03", "12 Stunden reichen für die Anzeige des Zeitstrahls. Was ich gut findne würde, wäre wenn die farbigen Balken größer wären und der Tooltip nicht über den Browser sondern unmittelbar per javascript angezeigt werden würde."),
    array("1.x", "2011-11-03", "Entkoppelung von Namen und ID: nützlich wenn nur vertippt und dann korrigiert"),
    array("1.x", "2011-10-05", "Rückgängig Funktion: Hab mich eben verklickt und aus versehen zwei Felder gemerged. Sollte irgendwie rückgängig zu machen sein"),
    array("1.x", "2011-10-05", "Farbfelder nicht überlagert mit den Kleb und Schneidfeldern: Hab versucht eine Farbe auszuwählen, diese wurde aber von der Klebtube überdeckt."),
    array("1.x", "2011-10-05", "Größere Farbfelder: Ich denke es macht Sinn, wenn das Farbselektier-Widget eine feste Größe bekommt. Bei kleinen Feldern passt nicht mehr alles rein und auch die 2 x 2 Pixel Felder für existierende Farben sind auf einem großen Monitor zu klein"),
    array("1.x", "2011-10-05", "Möglichkeit Änderungen nicht zu Speichern: Klickt man auf das x oben rechts beim editieren, wird trotzdem alles gespeichert. Es sollte die Möglichkeit geben, alle änderungen zu verwerfen."),
    array("1.x", "2011-10-05", "Tabs: Ich weiß, hab ich schon ganz oft gewünscht. Weiß nicht, ob das wirklich viele brauchen, aber sobald man inhaltlich mehr als einen Kunden hat, macht das total Sinn. Zur zeit überschreibe ich alte Felder, weil ich keinen Platz mehr hab. "),
    array("2.0.0", "2011-10-02", "Feldnamen sind zu stark begrenzt 50 - 100 Zeichen mehr würden für mich besser funktionieren. "),
    array("1.x", "2011-09-28", "von/bis nicht erlauben/Report Zeigen ausgrauen/automatisch korrigieren wenn von>bis"),
    array("1.x", "2011-09-22", "Error Handling: Wenn dort steht: error, später neuer Versuch, lade ich im Moment immer neu. Toll wäre es daneben einen Button zu haben: \"jetzt versuchen\". Nutzer werden ja immer etwas nervös, bei solchen Fehelrmeldungen."),
    array("1.x", "2011-09-18", 'Bessere Fehlermeldung super. Zur Zeit steht nur "Login failed". Ich glaub der Standard ist sowas wie: "Diese Kombination aus Login und Passwort ist und unbekannt."'),
    array("1.x", "2011-09-14", "malfunction: language-switch und blog funktioniert nicht mit hashchange"),
    array("1.30.0", "2011-09-13", "behoben?:typo: rtr1 hat typo in translation: haben,nbevor "),
    array("1.x", "2011-09-08", "Beim zeitlichen Nachkorrigieren springt die Figur kurz zum Zielfeld und dann wieder zurück. Einbuchen mit Zeitabfrage ggf. um Sekunden nach vorne/hinten korrigieren."),
    array("1.30.0", "2011-09-01", "behoben?:Tryout hat kein aid und daher auch keine base_href"),
    array("1.30.0", "2011-09-01", "im Tryout wenn man ein Template anlegt, sieht man plötzlich alle (auch nicht eigenen) Templates"),
    array("1.x", "2011-08-29", "In Safari: rotes-Fragezeichen aus nicht-above führt bei Mouseover im-above zur Überdeckung des above-Feldes."),
    array("1.x", "2011-08-29", "Helps liegen im IE bei Mouseover unter Textfeldern und werden abgeschnitten."),
    array("1.x", "2011-08-27", "Import-Text ist auf Deutsch-> Internationalisieren!"),
    array("1.30.0", "2011-08-26", "wenn noplace-feld angezeigt, wird in title <div ... angegeben"),
    array("1.x", "2011-08-18", "cronjob o.ä. welches Events \"verschmilzt\" bei gleichem event und start==end, DB-Bereinigung"),
    array("1.x", "2011-08-18", "Quickedit in den Ecken der Felder ermöglichen (z.B. wenn in Config aktiviert).Linksoben: Feldnamen ändern;Rechtsoben: Farbpalette;Linksunten: Hyperlink;Rechtsunten: Berichtbar;Schere und Glue wie gehabt"),
    array("1.x", "2011-08-16", "Passwort-Ändern im Config-Dialog anbieten"),
    array("1.30.0", "2011-07-01", "bis-Zeitwähler erscheint nicht in Chrome"),
    array("1.x", "2011-06-29", "Kommentare mit in der Zeitübersicht anzuzeigen. Muss nicht im Excel stehen, sondern einfach nur angezeigt werden. Gerne auch in einem versteckten Div das beim Hover von einem I Icon eingeblendet wird."),
    array("1.x", "2011-06-27", "Doku zu Basis-URL Einstellen zeigt sich nicht"),
    array("1.x", "2011-06-06", "Schnelleingabe für einmalige Events, z.B. Textfeld mit Farbe unter Info"),
    array("1.x", "2011-06-03", "Smart-Completions bei Feld-Texteingabe"),
    array("1.x", "2011-05-17", "inputs: array of events with event.start, event.end;array of intermediate events (noch nicht gespeichert); array \"verliert\" elemente, die nicht in den letzten 5 angeguckten Bereichen liegen; constants: height of panel; variables: scale-factor (zoom),width of panel,currentTime (wenn in future, ist es in der Mitte des Panels, sonst;rendering:div ohne clipping, overflow:hidden und backgroundPosition-x versatz,background-Bild in scale-factor-Auflösungen, pro scale-factor auch eine Anweisung, wie Zeiten darzustellen sind"),
    array("1.x", "2011-05-02", "Hyperlinks ermöglichen in Feldern/Reports"),
    array("1.x", "2011-05-02", "Tooltip über Feld zeigt Bezeichner"),
    array("1.x", "2011-04-15", "Automatisches E-Mail aus Bericht heraus \"diesen Bericht täglich/wöchentlich/monatlich schicken\" zum StundenSchreiben http://www.phpeveryday.com/articles/PHP-Email-Using-Embedded-Images-in-HTML-Email-P113.html"),
    array("1.x", "2011-04-15", "IE8 zeigt Hilfe- und Schließen Symbol rechts außerhalb der aboveBox an."),
    array("1.x", "2011-04-13", "User-Config für die Skalierung vorsehen im Editiermenü der Bericht-Felder wo man die max. darzustellende Stundenanzahl der Timeline Konfigurieren kann. z.B: 9 Stunden einstellen für einen Arbeitstag."),
    array("1.x", "2011-04-05", "Offline trackt tatsächlich den Anfang einer Zeiteinheit, übernimmt dann aber keine Änderungen"),
    array("1.x", "2011-02-21", "Pause-Icon anstatt Feld (=Initalstellung für Spielfigur, besetzt/frei)"),
    array("1.x", "2010-12-09", "timeline-fills durch js erzeugen lassen, ohne lücken mit position:absolute plaziert"),
    array("1.x", "2010-12-07", "title, alt etc. mit Großbuchstaben beginnen"),

    array("1.x", "2010-11-04", "Logout ohne Speichern-> Warnung, bzw. Nachfrage; Split&Merge abbrechbar machen?"),
  );
  printTable("Found-Date", $a);
?>

<a name="d">&nbsp;</a>
<h2>Discussed Features</h2>
<?php
  $a=array(
    array("2.x", "2010-11-10", "Kugeln/Blasen anstatt Tabellenfelder, kein split/merge, nur noch add."),
    array("2.x", "2010-11-10", "Getting-Things-Done Technik für Felder/Feldgruppen"),
    array("2.x", "2011-11-07", "Rechnungen für einen bestimmten Zeitraum und eine Gruppe von Maßnahmen als Rechnung direkt aus Emphasize heraus erstellen könnte. Ggf.: Rechnungsplugin, kostet Monatlich oder pro Rechnung."),
    array("2.x", "2010-12-14", "Feld-Verschachtelung mit Hierarchie ermöglichen"),
    array("2.x", "2010-12-04", "Schnelleingabe mit Smarter Logik für Zeiten davor un d danach")
  );
  printTable("Thought-Of-Date", $a);
?>

<a name="s">&nbsp;</a>
<h2>Solveds</h2>
<?php
  $a=array(
    array("2.0.0", "2012-09-15", "Added (shareable) Atom Feed about activities and infos to logged-in time-reporting page."),
  array("1.29.2", "2012-05-10", "Infos in den Stündlichen/Tägliche/Monatlichen Berichten"),
  array("1.29.1", "2011-12-22", "Vorlagen Speichern schlägt fehl"),
      array("1.29.0", "2011-12-13", "multiple-domain Fähigkeit"),
      array("1.29.0", "2011-09-29", 'error: Hallo, ich wollte die Server-Software auf meinem Rechner ausprobieren. Habe Emphasize in einen XAMPP wie in der install.txt beschrieben installiert. Leider kommen dann nur undefinierbare Fehlermeldungen wenn man die Seite im Browser aufruft :-(. FIX: XAMPP tested'),
    array("1.29.0", "2011-09-13", "typo: reg3 enthält Benutzername, mit _name_ ersetzen"),
    array("1.29.0", "2011-08-16", "php/js/html-code Formatieren: notepad++ TextFX Edit Reindent C++ Code. FIX: Eclipse-Php formatting & indenting"),
    array("1.29.0", "2011-12-01", "Performanceverbesserung bei i18n"),
      array("1.28.2", "2011-11-29", "Refactoring um Fehlertoleranz zu reduzieren (und dadurch eigentliche Fehler zu finden)"),
      array("1.28.1", "2011-04-15", "emphasize meaning verlinken: http://en.wiktionary.org/wiki/emphasize. prioritize, draw attention to, accentuate, point up, enounce http://www.thefreedictionary.com/emphasize auch de-emphasize interessant"),
      array("1.28.0", "2010-12-05", "confirm.php liegt nun unter util/"),
      array("1.28.0", "2011-09-01", "Automatische Feldaufteilung-Behebung mit \"fix(...)\"-Feldern."),
      array("1.28.0", "2011-05-27", "Bericht nur nach Feld. Wenn ich also am Ende eines Projektes wissen möchte, wie viel ich eigentlich dafür gearbeitet habe und nicht mehr weiß, wann ich angefangen habe. Als Ansicht wäre es toll pro Monat oder Tag die Gesamtzeit zu haben  und am Ende noch mal eine Zusammenfassung für alles und eventuell auch jeden Monat."),
      array("1.28.0", "2011-03-23", "Website ist leider gar nicht weiter Google-optimal.Beispiel: die Begriffe \"Zeiterfassung\" oder \"Arbeitszeiterfassung\" tauchen kein einziges Mal im Text auf, dabei geht es doch genau darum?! Nur im title-Tag, was auf jeden Fall schon mal sehr gut ist. Google-Bot kann kein JavaScript ausführen. Links, die so erzeugt werden, können also nicht gelesen/indiziert werden (also z.B. die anderen Sprachversionen). Ins Suchfeld site: http://emphasize.de eingeben"),
      array("1.27.2", "2011-08-17", "Fieldedit Close-Icon."),
      array("1.27.1", "2011-08-15", "Disabled selection in Timeline."),
      array("1.27.1", "2011-07-11", "Warnmeldung anzeigen, wenn JavaScript deaktiviert ist."),
      array("1.27.1", "2011-04-12", "Timeline nicht unterscheidbar, man sieht den Übergang nicht: Begrenzung einfügen"),
      array("1.27.0", "2011-07-25", "Timeline-Wochen-Limit aufgehoben, unbegrenzt weit zurück mit Direkteingabe."),
      array("1.26.0", "2011-06-23", "Tageweise Zurückspringen."),
      array("1.26.0", "2011-06-22", "Basis-URL für Report-Links."),
      array("1.26.0", "2011-06-20", "Externe Links werden Dereferenced."),
      array("1.26.0", "2011-06-18", "Bis-Datumauswahl für Reports wird automatisch aktualisiert."),
      array("1.25.2", "2011-04-14", "IE8 kann Farbwähler nicht anzeigen."),
      array("1.25.1", "2011-05-06", "name in vorlagenliste nicht unique."),
      array("1.25", "2011-04-15", "reload nur bei switchLang machen, nicht bei avatar."),
      array("1.25", "2011-04-15", "Try-Out Bereich ohne Registrierung"),
      array("1.25", "2011-04-30", "redundante i18n-Texte reduziert"),
      array("1.24", "2011-03-30", "social network internationalisation"),
      array("1.24", "2011-03-29", "spielfigur-auswahl ohne extra iframe"),
      array("1.24", "2011-03-22", "field-design vereinfachen"),
      array("1.24", "2011-03-22", "fix von no-color-bug"),
      array("1.24", "2011-02-21", "Export/Import von Feldaufteilungen"),
      array("1.24", "2010-12-05", "alert-fehler in js über ajax als automatischer feedback verschicken (und nicht mehr anzeigen, sondern nur status zeigen)"),
      array("1.24", "2010-11-04", "check Localtime & Sommerzeit-ok?"),
      array("1.24", "2011-01-25", "Emphasize.de JavaScripts Open Source machen."),
      array("1.23", "2011-03-09", "Infos in Berichte einbauen"),
      array("1.23", "2011-02-24", "kleine Info-Spalte (ca. 120 Zeichen) für Bericht im Detail: Eingabefeld oben/mitte, in Timeline \"i\" anzeigen mit Tooltip."),
      array("1.23", "2011-03-03", "Timeline mouseover hover popup mit Zeit im Format Stunden:Minuten für besseren Überblick & Zielen nach einer bestimmten Zeit"),
      array("1.23", "2011-03-08", "edit-bug: &amp; wird als encodiertes HTML dargestellt."),
      array("1.23", "2011-03-08", "Zeitfeld-name welches gerade ausgewählt im Fenster-Title"),
      array("1.23", "2011-02-22", "Berichtbar & Farben für historische Daten im nachhinein änderbar machen (à la UPDATE)"),
      array("1.23", "2011-02-21", "Farbwähler zusätzlich mit Feldern der bereits verwendeten Farben (zum umgruppieren)"),
      array("1.22", "2011-01-25", "Emphasize.de zip zum Download anbieten, Installations-Anleitung, DB-Passwörter ersetzen!"),
      array("1.21", "2011-02-22", "CSV-Export Zeitformat umgestellt auf =ZEIT(HH;MM;SS)"),
      array("1.21", "2011-02-04", "Report-Table-Cells background images"),
      array("1.21", "2010-12-14", "avatar & schatten durchklickbar machen"),
      array("1.21", "2011-01-23", "Return in Feld-Name sollte hideEdits bewirken."),
      array("1.21", "2011-01-08", "Täglicher Bericht enthält \"6d etc.\", kann nicht sein"),
      array("1.21", "2011-01-24", "Stündlicher Bericht"),
      array("1.20", "2010-11-04", "Logout bei Fehlern, Re-Use of token bei erneutem Login"),
      array("1.19", "2010-11-17", "YouTube-Video erstellen zu \"So einfach geht Emphasize Zeiterfassung\""),
      array("1.18", "2010-11-05", "Das Kalender (scw) Layout & css schick machen ;)"),
      array("1.17", "2010-12-02", "Progress-Loading Anzeige wenn ajax-Request losgeschickt bei Status"),
      array("1.16", "2010-11-05", "Feldaufteilungs-Vorlagen (anstatt Workspace Tabs). -
  Das wäre so für meinen Arbeitsstil super hilfreich. Wenn man also an mehreren Projekten gleichzeitig arbeitet oder in die Pause geschickt wird, wäre es super, wenn man einfach einen neuen Tab aufmachen kann und für das nächste Projekt mit einer \"sauberen\" Zeitfeldmenge neu startet, ohne die alten Konfigurationen zu verlieren, wenn man wieder in das Projekt kommt.
  Bleibt die Frage, wie das wechseln von Tabs sich auf das Zeitlogging auswirkt. Vielleicht will man nur kurz mal reinschauen, ohne gleich den Modus zu wechseln..."),
      array("1.15", "2010-11-17", "Eintragen lassen in Directories zum Thema \"Zeiterfassung\""),
      array("1.14", "2010-11-17", "Einsteiger-Hilfe mit Funktionsbeschreibung und Zeigern"),
      array("1.13", "2010-11-04", "window onResize überwachen für Aktualisierung von moveAvatar, showEdits - Wenn das Fenster Skaliert wird, bleibt die Figur fest stehen. Die Abstände sollten wohl auch prozentual wie bei den Flächen definiert werden."),
      array("1.12", "2010-11-04", "csv/xml export addcslashes"),
      array("1.11", "2010-11-17", "Sprachwähler, Sprache in Session speichern"),
      array("1.9", "2010-11-15", "Color-Chooser Auflösungs-Abhängig machen, der Farbwähler ist gut, leider etwas klein. Wäre auch toll, wenn man die Farbe einfach schon beim Mouseover in einem Beispielfenster oder ebem beim Klick erst mal die Farbe im Wähler ändert und dann noch mal bestätigt. Ist momentan ein bischen unhandlich."),
      array("1.8", "2010-11-04", "Avatar-Selector & Builder public/private"),
      array("1.7", "2010-11-17", "Demo-Text erweitern"),
      array("1.6", "2010-11-12", "Test auf jetzt-autozurücksetzen/timeline editierzeit, ob min 1 Minute, max. 6 Minuten ok."),
      array("1.6", "2010-11-12", "Nicht-Jetzt-Zeit moveAvatar mit spontanem ExistiertNichtMehr-Feld"),
      array("1.6", "2010-11-17", "rgb-color-chooser mit 0.0-1.0 coords & skalierbar zu machen"),
      array("1.6", "2010-11-17", "Zeitstrahl direkte Zeiteingabe in Textfeld."),
      array("1.5", "2010-11-10", "Test mit single-, double-quotes, ampersand, greater-, lower-than in den Feldern"),
      array("1.5", "2010-11-12", "Zeit-Zeiger durchclickbar machen."),
      array("1.5", "2010-11-04", "Das Feedback Feld sollte zu gehen, wenn man auf senden klickt.
    Das Bestätigungsfenster sollte kein neuer Tab sondern ein AJAX Layer sein (meiner Meinung nach... Das mit dem Tab verwirrt ein bischen) "),
      array("1.5", "2010-11-04", "internationalisierte AGBs"),
      array("1.5", "2010-11-04", "nach grace-logout & login: \"haben Sie schon die Bestätigungs-Email erhalten und auf den Link darin geklickt?\""),
      array("1.5", "2010-11-14", "IE Login stay-Feld hat keinen Initialwert."),
      array("1.5", "2010-11-14", "Timeline-Tooltyp mit Zeichen &lt;&gt;\"\'& klappt nicht"),
      array("1.5", "2010-11-15", "Speichern-Queue wenn mal offline weitergearbeitet."),
      array("1.5", "2010-11-15", "Registration & Confirmation Email an Admin schicken"),
      array("1.4", "2010-11-04", "Fehlerbehandlung in status-div mit .ajax( Methoden anstatt .post("),
      array("1.4", "2010-11-04", "Split&Merge Icons und Config-Icon verbessern, z-index unter popups"),
      array("1.4", "2010-11-05", "Mehr Farben - Die Möglichkeit auf einen color-chooser zugreifen zu können wäre toll. momentan sind es noch zu wenig farben. Image-Bar mit php-pick-farben benutzen?"),
      array("1.4", "2010-11-12", "Nicht-Jetzt-Zeit roter Rahmen mit \"X\"."),
      array("1.3", "2010-11-05", "Das Texteingabefeld ist für das feedback ist zu breit. Rechts wird etwas Text abgeschnitten."),
      array("1.3", "2010-11-04", "Oben die Art des Feedbacks solte bewusst gewählt werden."),
      array("1.3", "2010-11-05", "Im bearbeiten Modus springt die Feldgröße. Ich find das ziemlich verwirrend. Vielleicht kann man das gleiche mit einem Mouseover erreichen? Ich denke feste Feldgrößen für das benutzen und das bearbeiten wären besser."),
      array("1.3", "2010-11-04", "AdSence in demo & reports"),
      array("1.2", "2010-11-04", "Zeit-Editor"),
      array("1.2", "2010-11-04", "Wenn man die Figur auf einem Zeitfeld hat und dann ein Fenster teilt, wird sie standardmäßig auf Pause zurückgesetzt, wenn man speichern klickt. Wäre beser, wenn sie danach auf der gleichen Fläche steht, wie vor dem Bearbeiten"),
  		array("1.1", "2010-11-02", "Mehr Berichte über die erfassten Zeiten"),
  		array("1.0", "2010-11-01", "Start des kostenlosen Webdienstes zur Arbeitszeiterfassung")
  );
  printTable("Reported-Date", $a);

  if ($changes) {
  	file_put_contents(dirname(__FILE__)."/cache/votes.php", serialize($v));
  }
?>
<br/>
<?php bottom();?>



