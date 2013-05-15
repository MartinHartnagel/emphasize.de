<?php

$news=array(
  'de' => array(
    1 => array('Arbeitszeiterfassung - unbewusst oder selbst gefälscht', '<p>Früher habe ich meine Arbeitszeiterfassung <i>anders</i> gemacht. Der Projektleiter kam zu mir und sagte, der Kunde will nun doch wissen was wir letzten Monat gemacht haben. Dazu hat er ein paar Arbeitszeitkonten angelegt und verfügbare Zeit eingestellt, auf die wir buchen können. Nagut, hab ich also mal für 2 Stunden aufgehört zu entwickeln und mir ein halbwegs schlüssiges Ausfüllen der Arbeitszeitkonten ausgedacht. Schnell hat sich dabei aber herausgestellt, dass die verfügbaren Kategorien der Arbeitszeitkonten überhaupt nicht zu dem passen, zumindest zu dem an was ich mich erinnern konnte und letzten Monate gemacht hatte. Aber egal, der Kunde will ja nur einen Bericht über das was er da von mir fordert und das zählt ja schließlich. Ich weiß nicht ob das, was ich damals als Tätigkeiten erfasst habe, annähernd der Wahrheit entspricht. Wie sollte ich auch, es gab nur wenige Quellen, in denen ich erahnen konnte, was ich den Monat über gemacht hatte:
<ul>
<li>Start und Stop des Computers im Ereignisprotokoll in der Systemverwaltung (nur zu dumm, wenn ich den Computer über Nacht angelassen hatte)</li>
<li>in Outlook die gesendeten Emails (da muß ich wohl bewusst dabei gewesen sein)</li>
<li>im Versionskontrollsystem meine "commits" (davor muss ich wohl die entsprechenden Änderungen gemacht haben)</li>
</ul>
<p>Alles andere, Zettel mit Start/Stop-Zeiten, intelligente Excel-Sheets die alles automatisch machen sollen oder das Zeiterfassungssystem XYZ welches mir nur lange Listen von Input-Feldern anbietet, habe ich nicht wirklich über einen längeren Zeitraum konsequent benutzen können: zu starr waren die vorgegebenen Kategorien, zu schmerzhaft das notieren einer Zeit, zu viele Klicks um halbwegs präzise zu bleiben.<br/>
So eine Herangehensweise bezeichne ich heute als <b>unbewusst</b> <strike>gefälschte</strike> erfolgte Arbeitszeiterfassung.</p>
<p>Heute bin ich ehrlicher. Heute weiß ich wann ich fälsche! Bin immer eingeloggt in <a href="Arbeitszeiterfassung-Benutze_Emphasize.php" title="Web-Dienst zur Arbeitszeiterfassung" class="blog"><app_name/> als Webdienst zur Zeiterfassung</a> und rücke meine Figur weiter, wenn ich eine neue Tätigkeit anfange oder eine liegengelassene wieder aufnehme. Dafür brauche ich: nur einen Klick! Das tut nicht weh, sieht auch noch schick aus, wenn die Figur durch die Luft schwebt. Wenn ich mal vergessen hab die Figur umzusetzen, stelle ich einfach die Zeit zurück und setzt sie dann um. Ich bin kein penibler Stoppuhr-Liebhaber. Wie spät es gerade ist, ist mir fast den ganzen Arbeitstag egal, nur wenn ich dann gehe will ich wissen, wie viele Überstunden/Unterstunden ich gerade akzeptiere. Das liefert mir der "Tägliche Bericht". Mein System von Feldern, in denen ich buche ändert sich andauernd, genau so wie ich es gerade brauche. Und dann, wenn ich die Arbeitszeiterfassung in einem System des Kunden ausfüllen soll, dann bin ich mit einem Klick bei der Aufgabe (gerade arbeite ich mit JIRA) und kopiere die Zeit rüber. Oder ich <strike>fälsche</strike> passe die Zeit nochmal selbst an, aber zumindest weiß ich wann ich es tue!</p>
<p>Aus der Sicht der Projektleitung kann ich jetzt besser <a href="Arbeitszeiterfassung-Warum_Emphasize.php" title="Die Bedeutung von <app_name/>" class="blog">deutlich machen</a>, was ich wann wirklich gemacht habe, ohne mich besonders anstrengen zu müssen. Aus Sicht des Kunden sind die Zahlen die er bekommt wirklich etwas Wert. Ich denke ich nähere mich dem Punkt, wo sich Berichtspflicht und freiwillige Selbstkontrolle ergänzen.</p>'),
    2 => array('Arbeitszeiterfassung - Keywords', 'deutsch:
<pre>
Zeitmanagement, timemanagement, projekte, arbeitszeiterfassung, arbeitszeiten, erfassung, stoppuhr, arbeitszeit, zeiterfassung, reporting, projektmanagement, projektplanung, projektverwaltung, projektzeiterfassung, projekt, projektcontrolling, projektabrechnung, timer, Projektüberblick, flexibel, Kommentieren, Tätigkeiten, Briefing, Ruhezeit

arbeitszeit
zeiterfassung
reporting
reporting
zeiterfassung
arbeitszeit
projektmanagement
projektplanung
projektverwaltung
projektzeiterfassung
projekt
projektcontrolling
projektabrechnung
timer
arbeitszeiterfassung
arbeitszeiten
mitarbeiter
urlaub
projekte
urlaubsplaner
kalender
termin
stoppuhr
timemanagement
zeitmanagement
erfassung
Zeit
Excel
</pre>
englisch: http://en.wikipedia.org/wiki/Comparison_of_time_tracking_software
<pre>time tracking software, time reporting,reporting,quick,first,time,magazine,travel,clock,time management,payroll, benefits,training,recruitment,talent management,management,employee relations,outsourcing,compensation,employment,personnel administration,organizational management,performance management,home,human resources,services,cost management,resource management,issue management,schedule management,job,vacancies,skills,leadership,personnel
</pre>'),
    3 => array('Arbeitszeiterfassung - Konkurrenz', '<pre>
http://www.getharvest.com/
http://en.wikipedia.org/wiki/Comparison_of_time_tracking_software
http://www.projektron.de/ Projektron BCS 6.18
</pre>'),
    4 => array('Arbeitszeiterfassung - Testcase', '<ol>
<li><a href="http://admin.emphasize.de/next">Demo Seite öffnen</a>.</li>
<li>Funkioniert das Demo?</li>
<li>Klick auf "Hilfe einblenden".</li>
<li>Funktionieren die Hilfe-Mouseovers?</li>
<li>Klick auf "Feedback geben".</li>
<li>Funktioniert dort die Hilfe-Mouseovers und ist an?</li>
<li>In Feedback "test" eingeben und "Rechtschreibfehler" auswählen.</li>
<li>Klick auf "Leeren".</li>
<li>In Feedback "test2" eingeben und "Fehlfunktion" auswählen.</li>
<li>Klick auf "Senden".</li>
<li>Wird das Feedback-Fenster geschlossen?</li>
<li>Wird "Feedback übermittelt" als Status angezeigt?</li>
<li>Klick auf "Hilfe ausblenden".</li>
</ol>'),
    6 => array('Arbeitszeiterfassung - Benutze <app_name/>', 'schneide Deinen <a href="Arbeitszeiterfassung-unbewusst_oder_selbst_gefälscht.php" title="Wie nah an der Wirklichkeit ist das, was ich als Arbeitszeit angebe" class="blog">realen Zeitaufwand</a> über <a href="Arbeitszeiterfassung-Warum_Emphasize.php" title="Wofür steht der Name?" class="blog"><app_name/></a> mit ...
<h2>neuen Benutzer anlegen</h2>
<ul>
<li>die <a href="#" onclick="tubeTutorial(\'jVxjkfQj6UE\')">Registrierung</a> ist einfach und geht schnell</li>
<li>man kann sofort seine <a href="#" onclick="tubeTutorial(\'hk05wFpoRyM\')">Felder individuell aufteilen und benennen</a></li>
<li><a href="#" onclick="tubeTutorial(\'R1XmQ9pioJU\')">Spielfigur</a> kann selbst gestaltet werden</li>
</ul>
<a href="#" onclick="$(\'#register\').slice(0, 1).click()" title="neuen Benutzer anlegen">Jetzt benutzen</a>!
<br/>
<div>
<h2>Verwende es ab sofort, denn ...</h2>
<ul>
<li>der Zwang sich am Monatsende die Zahlen aus den Fingern zu saugen entfällt</li>
<li>eine individuell gestaltbare Selbstkontrolle im Gegensatz zu einer starren Kategorien-Vorgabe</li>
<li>Berichte sind jederzeit abrufbar und als Excel-csv oder xml exportierbar</li>
<li>Mit einem Login für "immer" als <a href="#" onclick="tubeTutorial(\'JCAXvyypUrA\')">Active-Deskop Widget einrichtbar</a></li>
<li>zwischen berichtbaren und nicht-berichtbaren Aktivitäten unterscheiden</li>
<li>läuft auf Firefox, Chrome, Opera, Safari, IE und Smartphones (IPhone und Nexus)</li>
<li>kann Offline weiter Zeiten erfassen und aktualisiert sich wenn wieder mit Internet verbunden</li>
</ul>
Kostenlose
 <a href="#" onclick="$(\'#register\').slice(0, 1).click()" title="neuen Benutzer anlegen">Registrierung und Verwendung</a> hier als Web-Dienst!
</div>
<br/>
Über <a href="util/about.php" title="Aktuelle Versionshinweise" class="blog"><app_name/></a> lesen auf ...<br/>
<a href="https://twitter.com/emphasizede" target="_blank" title="Über <app_name/> auf Twitter lesen"><img src="http://emphasize.de/graphics/twitter.png" alt="Über <app_name/> auf Twitter lesen" height="44" width="44"></a>
<a title="<app_name/>, Download bei heise" href="http://www.heise.de/software/download/emphasize/77625"><img alt="<app_name/>, Download bei heise" title="<app_name/>, Download bei heise" align="top" src="http://www.heise.de/software/icons/download_logo2.png" /></a>

<a href="http://www.arbeitszeit-erfassung.net" target="_blank" title="Über <app_name/> auf arbeitszeit-erfassung.net lesen" title="Über <app_name/> auf arbeitszeit-erfassung.net lesen">Zeiterfassung</a>

<h2><app_name/> Verbessern</h2>
<ul>
<li>Mit einem Klick auf das <a href="#" title="Feedback geben" onclick="$(\'#feedLink\').slice(0, 1).click()"><img src="graphics/feedback.png" align="bottom"></a> Icon unten links kann man schnell Feedback über einen Fehler oder eine Verbesserung abgeben.</li>
<!--translationWelcome/-->
<li>Die wichtigsten Funktionen von <app_name/> können <a href="test/" title="automatische Tests starten" target="_blank" class="newWindow" >in einer Test-Suite getestet</a> werden, um die Fehlerfreiheit für diesen Browser abzusichern. Die Ergebnisse werden anschließend automatisch an den Entwickler übermittelt.</li>
<li>Der komplette Source-Code, inklusive Bestandteile dieser Webseite, ist als Open-Source einsehbar unter <a href="http://github.com/MartinHartnagel/emphasize.de" title="Open-Source-Code <app_name/>.de auf github" target="_blank" class="newWindow">github</a>. Hier können bestehende Sicherheitslücken entdeckt werden, die dann umgehend nach einem <a href="#" title="Feedback geben" onclick="$(\'#feedLink\').slice(0, 1).click()">Feedback</a> geschlossen werden können.</li>

<li>Die <a href="roadmap.php" title="Planung zur weiteren Entwicklung der Zeiterfassung <app_name/>" target="_blank" class="newWindow">Roadmap</a> (weitere Planung) ist nun öffentlich einsehbar mit der Möglichkeit für einzelne Funktionen zu stimmen. Dopplungen von Fehlerberichten und Verbesserungswünschen können so vermieden werden.</li></ul>'),
    9 => array('Arbeitszeiterfassung - Warum <app_name/>', 'Im <i>deutschen</i> hat das <i>englische</i> Verb "to emphasize" die Bedeutung "betonen", "unterstreichen", "hervorheben", "herausstellen", "akzentuieren", "pointieren", "hervorkehren", "deutlich machen", "Nachdruck legen auf", "Wert legen auf" oder auch "auf etw. abheben" (siehe auch <a href="http://en.wiktionary.org/wiki/emphasize" title="<app_name/> Eintrag bei Wiktionary" class="newWindow" target="_blank">weitere Schreibweisen</a> und <a href="http://www.thefreedictionary.com/emphasize" title="Redewendungen mit <app_name/>" class="newWindow" target="_blank">übliche Redewendungen</a>).<br/>
Was soll mit der <a href="Arbeitszeiterfassung-Benutze_Emphasize.php" title="Web-Dienst zur Arbeitszeiterfassung" class="blog">Arbeitszeiterfassung <app_name/></a> nun betont, hervorgehoben und deutlich gemacht werden?
Es geht vor allem bei diesem Web-Dienst darum die <b>Einfachheit</b> dieses Aufgabe hervorzuheben. Dadurch dass sich dieses Tool eben nur auf diese eine Aufgabe konzentriert, wird ein Ansatz gewählt, der zumindest die Erfassung so einfach wie möglich macht. Im Gegensatz dazu stehen <a href="http://en.wikipedia.org/wiki/Comparison_of_time_tracking_software" title="weitere Anwendungen zur Zeiterfassung auf Wikipedia" class="newWindow" target="_blank">die vielen anderen Anwendungen zur Arbeitszeiterfassung</a>, die zwar <a href="Arbeitszeiterfassung-Abgrenzung.php" title="Was kann <app_name/> und was nicht" class="blog">unglaublich viele Features bieten</a>, jedoch eine nur wenig anwenderfreundliche Erfassung bieten.
Dieser Philosophie folgend, soll es zukünftig bei der weiteren Entwicklung von <app_name/> nur um die Verbesserung dieser Kernkompetenz gehen.'),
    10 => array('Arbeitszeiterfassung - Linkbait', 'Linkbait Ideen:
- Witzige Feldaufteilung, in der kein Logout möglich ist public rumschicken
- Top-News-Themen, Gadaffi-Feldaufteilung'),
    11 => array('Arbeitszeiterfassung - Agile', 'Rally, Scrumworks, XPlanner, Mingle, VersionOne, TargetProcess, xProcess, Extreme Planner,ProjectCards,CardMeeting,XP Story Studio,
Planning p oker'),
    12 => array('Arbeitszeiterfassung - Abgrenzung', '<h2>Das kann <app_name/></h2>
<ul>
<li>dient zur einfachen, schnellen Zeiterfassung</li>
<li>funktioniert wie mehrere Stoppuhren</li>
<li>liefert dem Entwickler eine Projektüberblick in den Berichten</li>
<li>erlaubt eine super flexible Aufgabenliste mit der Feldaufteilung</li>
<li>ermöglicht Feed-ähnliches Kommentieren</li>
<li>zeigt ein Tätigkeits-Briefing</li>
<li>unterstützt eine mobile Zeiterfassung</li>
<li>bietet eine Auswertung der eigenen Arbeitszeit und Ruhezeit</li>
<li>bietet einen einfachen Kundenzugang über das Tätigkeits-Briefing</li>
</ul>

<h2>Es ist geplant <app_name/> zu erweitern</h2>
<ul>
<li>mit automatischen Benachrichtigungen per E-Mail</li>
<li>um Aufgabendiagramme</li>
</ul>

<h2>Dafür ist <app_name/> nicht gedacht</h2>
<ul>
<li>zur Projektdefinition</li>
<li>zur Abschätzung von Risiken</li>
<li>zur Projektstrukturplanung</li>
<li>zur Erstellung von Gantt-Diagrammen</li>
<li>zur Aufwandsplanung</li>
<li>zur Kostenplanung</li>
<li>zum Controlling (Kostenüberblick und Kostenprognosen, Verwaltung der Personalkosten, Erfassung von Sachkosten, Meilenstein-Trendanalyse)</li>
<li>zur Unterstützung von Scrum (agiles Projektmanagement)</li>
<li>es gibt kein Product Backlog</li>
<li>dient nicht zur Planung von Scrum-Projekten</li>
<li>unterstützt nicht die Durchführung von Sprints</li>
<li>Erstellung von Angeboten und Rechnungen</li>
<li>arbeitet nicht mit Währungen und Umrechnungskurse</li>
<li>dient nicht zur Auswertung von Projektkosten und Projektumsätzen</li>
<li>bietet kein Ressourcenmanagement</li>
<li>arbeitet nicht mit einem Arbeitszeitmodell</li>
<li>erzeugt keine Auslastungsprognose</li>
<li>hat kein Urlaubsmanagement</li>
<li>dient nicht zur Einsatzplanung</li>
<li>Qualifikationen der Mitarbeiter</li>
<li>verwaltet keine weiteren Ressourcen</li>
<li>verwaltet keine Kundendaten und bietet kein Kontaktmanagement</li>
<li>bietet keine Kundendatenverwaltung</li>
<li>verwaltet keine Termine</li>
<li>hat keine Funktion für Wiedervorlagen</li>
<li>bietet keinen Adressdatenaustausch</li>
<li>dient nicht zur Dokumentierten Kommunikation</li>
<li>Importiert keine E-Mails und dient nicht zur Versendung von E-Mails</li>
<li>hat keine Anbindung an eine Telefonanlage</li>
<li>dient nicht zur Angebotserstellung</li>
<li>unterstützt nicht bei Akquisen</li>
<li>verschickt keine Serienmails</li>
<li>hat kein Vertragsmanagement</li>
<li>unterstützt keine Faktura</li>
<li>dient nicht zur Spesenerfassung</li>
<li>hat kein Ticketsystem</li>
<li>dient nicht zur Qualitätssicherung</li>
<li>benutzt keine Workflows</li>
<li>hat keine Checklisten</li>
<li>dient nicht zur Erfahrungssicherung</li>
</ul>
'),
  ),
  'en' => array(
    7 => array('Time-Reporting - Use <app_name/>', 'tracking your <a href="Time-Reporting-unwittingly_or_deliberately_faking_.php" title="Honestly, how close to reality are my times reported" class="blog">real time</a> with <a href="Time-Reporting-Why_Emphasize.php" title="Why is this time-tracking tool called <app_name/>?" class="blog"><app_name/></a> spent for activities ...
<h2>register new user</h2>
<ul>
<li><a href="#" onclick="tubeTutorial(\'A1wF8aVZOfg\')">Registration</a> is fast and simple</li>
<li>You can instantly start to <a href="#" onclick="tubeTutorial(\'gP9O8lLCleU\')">design and divide your fields individually</a></li>
<li><a href="#" onclick="tubeTutorial(\'akZ90qEgKEQ\')">Character</a> may be individually designed</li>
</ul>
<h2>Use it now!</h2>
<ul>
<li>month-end figures continously produced</li>
<li>no rigid preset categories but customizable self-regulation</li>
<li>Reports are available at any time and as an Excel csv or xml exportable</li>
<li>Setup as an <a href="#" onclick="tubeTutorial(\'FIRhzFfefTY\')">Active Deskop Widget</a> with a login for "always" </li>
<li>Distinguish between reportable and non-reportable activities</li>
<li>runs on Firefox, Chrome, Opera, Safari, IE and Smartphones (IPhone and Nexus)</li>
<li>can capture times offline and updates itself when re-connected to Internet</li>
</ul>
Free
 <a href="#" onclick="$(\'#register\').slice(0, 1).click()" title="register new user">registration and usage</a>!
<br/><br/>
Read about <app_name/> on ...<br/>
<a href="https://twitter.com/emphasizede" target="_blank" title="Read about <app_name/> on Twitter"><img src="http://emphasize.de/graphics/twitter.png" alt="Read about <app_name/> on Twitter" height="44" width="44"></a>
 <a title="<app_name/>, Download bei heise" href="http://www.heise.de/software/download/emphasize/77625"><img alt="<app_name/>, Download bei heise" title="<app_name/>, Download bei heise" align="top" src="http://www.heise.de/software/icons/download_logo2.png" /></a>
 <a href="http://www.arbeitszeit-erfassung.net" target="_blank" title="Read about <app_name/> on arbeitszeit-erfassung.net" title="Read about <app_name/> on arbeitszeit-erfassung.net">time-reporting</a><br/>
 <a href="http://www.filemapper.com">Free software downloads -- Filemapper.com </a>

<h2>Improve <app_name/></h2>
<ul>
<li>With a click on the <a href="#" title="give feedback" onclick="$(\'#feedLink\').slice(0, 1).click()"><img src="graphics/feedback.png" align="bottom"></a> Icon positioned bottom right you may quickly give feedback about an error or suggest an improvement.</li>
<!--translationWelcome/-->
<li>The features of <app_name/> can <a href="test/" title="run automatic tests" target="_blank" class="newWindow" >be tested in a test suite</a> to verify determinacy for this browser. The results will be automatically transmitted to the developer thereafter.</li>
<li>All source-code, including parts used for this website, is browsable as open-source on <a href="http://github.com/MartinHartnagel/emphasize.de" title="open source code <app_name/>.de on github" target="_blank" class="newWindow">github</a>. Existing vulnerabilities may be discovered that way and can be fixed after reporting them via a <a href="#" title="give feedback" onclick="$(\'#feedLink\').slice(0, 1).click()">feedback</a>.</li>

<li>The <a href="roadmap.php" title="development planned  for the time-reporting tool <app_name/>" target="_blank" class="newWindow">roadmap</a> is public now with the ability to vote for certain functionalities. Duplicates of bug reports and improvements can be avoided that way, too.</li>
</ul>'),
    13 => array('Time-Reporting - unwittingly or deliberately faking ', '<p>Some time ago I did my time-reporting <i>somewhat different</i>. The project-leader approached me and demanded what I had been up to last month for our client. In order to do so he set up some time-accounts for us to book on to. Well, then I stopped developping for two hours to invent some reasonable looking figures for these time-accounts. It didn\'t take long to find out that there was a discrepancy between the existing time-accounts and the ones I should have booked on, at least for the passed month and as far as I could remember. But don\'t bother I thought, our client only seems to want the time-reporting in the categories available and that\'s what really counts. I obviously have no idea if what I had recorded as activities were any close to accurate. How should I, there were only a few sources to make assumptions of what I had been up to that month:
<ul>
<li>start and shutdown times in the event-protocoll of the computer (just my bad if I had left it running through the night)</li>
<li>in Outlook the sent mails (I must have been aware then)</li>
<li>the "commits" in the versioning-system (preceeding I must have worked for these changes)</li>
</ul>
Anything else, short notices with starting- and stopping-times, intelligent Excel-Sheets which are supposed to do things automatically or any other time-reporting system XYZ which just offers long rows of input-fields, have not really sustained my task over the long run consiquently enough: too fixed in categories, too painful for entering times, too many clicks to achive at least a decent accuracy.<br/>I tend to call these kind of approaches <b>unwittingly</b> <strike>faking</strike> time-reporting.</p>
<p>
Nowadays I am more honest. I know when I fake! I\'m constantly logged in to the <a href="Time-Reporting-Use_Emphasize.php" title="time-reporting web-service" class="blog"><app_name/> web-service for time-reporting</a> and move my pawn as soon as I start a new activity or resume a paused one. For this I need: one single click! That does not hurt, looks nifty as the figure hovers in mid-air. If I for once forgot to set my figure, I just turn back time and correct it. I\'m not a fussy stopwatch-lover. I tend to forget the current time when I\'m working, just when I\'m leaving my desk I want to know if I did any surplus or not. This is optained through the "daily report". My system of fields where I do my booking on is changing very frequently, just to fit any current need. And then, when I\'m supposed to fill in the other time-reporting tool of a client, I switch into that with a click on the activity (just now I am working with JIRA) and copy\'n\'paste the duration. Or I <strike>fake</strike> adjust the duration deliberately, but at least I know what I\'m up to!</p>
<p>From a project-leading point of view I am now more capable to justify what I have really done without to much bothering about. From the client point of view the numbers which sum up are actually worthy now. I think this is just the point where the duty of having to report and a voluntary control done by oneself approach and supplement each other.</p>'),
    14 => array('Time-Reporting - Why <app_name/>', 'The <i>english</i> verb "to <app_name/>" has the meaning "stressing sth.", "prominence", "distinguish", "highlighting", "accentuating" and also "to get to the heart of sth." (see <a href="http://en.wiktionary.org/wiki/emphasize" title="<app_name/> entry on Wiktionary" class="newWindow" target="_blank">further spellings</a> and <a href="http://www.thefreedictionary.com/emphasize" title="phrases with <app_name/>" class="newWindow" target="_blank">common phrases</a>).<br/>
What is it that <a href="Time-Reporting-Use_Emphasize.php" title="web service for time tracking" class="blog">time-reporting <app_name/></a> accentuates, highlights and gets to the heart of?
The main aim of this web service is to focus on the <b>simplicity</b> of one functionality: that of tracking time itself. By concentrating just on this task, the tool follows an approach which makes gathering extremely easy. In comparison to this approach there are <a href="http://en.wikipedia.org/wiki/Comparison_of_time_tracking_software" title="further tools for tracking time on Wikipedia" class="newWindow" target="_blank">many other tools for time-tracking</a>, which may <a href="Time-Reporting-Limitations.php" title="What does <app_name/> support and what is it not designed to do" class="blog">provide an amazing set of features</a>, but mostly provide a less usable acquisition.
Following this idea, further development of <app_name/> will concentrate on improving this core capability.'),
    15 => array('Time-Reporting - Limitations', '<h2><app_name/> is designed</h2>
<ul>
<li>for simple and fast time tracking</li>
<li>to work like several stop-watches</li>
<li>to provide an overview of a project through the reports for a developper</li>
<li>to allows a very flexible list of tasks with the field-divisions</li>
<li>to support feed-like comments</li>
<li>to show an activity-briefing</li>
<li>to support a mobile time-reporting</li>
<li>to offer an analysis of ones working and pause times</li>
<li>to be used as customer-access for transparent reporting</li>
</ul>

<h2>There are plans to extend <app_name/> with</h2>
<ul>
<li>an automated email-notification</li>
<li>task diagrams</li>
</ul>

<h2><app_name/> does not support</h2>
<ul>
<li>defining of projects</li>
<li>estimation of risks</li>
<li>structured planning of projects</li>
<li>creation of gantt-diagrams</li>
<li>planning of expenses</li>
<li>planning of costs</li>
<li>controlling (overview of costs and estimation of costs, administration of payroll costs, acquisition of material costs, milestone trend analysis)</li>
<li>agile project management with scrum</li>
<li>a prodcut backlog</li>
<li>performing sprints</li>
<li>creation of offers and invoices</li>
<li>working with currencies and exchange rates</li>
<li>an analysis of project costs and turnovers</li>
<li>a resource management</li>
<li>working with work time models</li>
<li>estimation of occupancy rates</li>
<li>management of holidays</li>
<li>applications planning</li>
<li>extended vocational training</li>
<li>managing of resources</li>
<li>management of customer files and customer relations</li>
<li>scheduling</li>
<li>follow-ups</li>
<li>address data exchange</li>
<li>documenting correspondence</li>
<li>import of emails and sending emails</li>
<li>connecting to a telephone switchboard</li>
<li>proposal preparation</li>
<li>acquisition</li>
<li>contract management</li>
<li>issuing an invoice</li>
<li>gathering expenses</li>
<li>a ticket system</li>
<li>quality control</li>
<li>use of workflows</li>
<li>any check lists</li>
<li>record experiences</li>
</ul>
'),
  ));

if (isset($blang)) {
 switch($blang) {
  case 'de': $latestId = 6; break;
  case 'en': $latestId = 7; break;
 }
}

function title($lang, $bid) {
 global $news;

 $header = $news[$lang][$bid][0];
 return $header;
}

function entry($lang, $bid) {
 global $news;

 $header = $news[$lang][$bid][0];
 echo ("<h1><a href=\"" . longUrl($header, $bid) . "\" title=\"link\">" . $header . "</a></h1>\n");
 $text = $news[$lang][$bid][1];
 echo ($text . "<br clear=\"all\" />\n");
}


?>