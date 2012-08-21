<?php
include_once(dirname(__FILE__)."/../includes/config.php");
?>
<title><i18n key="fdb0"> <en>Thank your for your feedback</en> <de>Danke
	für das Feedback</de> <fr>Merci pour la rétroaction</fr> <es>Gracias
	por la información</es></i18n></title>
</head>
<body>
	<p>
		<?php

		if (!empty($_POST)) {
			$message="feedback ".gmdate('Y-m-d h:i:s',time()).":\n\n";
			foreach($_POST as $key=>$value) {
				$message=$message.$key.": ".$value."\n\n";
			}

			if (!(strpos(strtolower($message), "<a href=") === false)) {
				die("spam link detected, rejecting feedback");
			}

			if (!(strpos(strtolower($message), "http://") === false)) {
				die("spam link detected, rejecting feedback");
			}

			if (!(strpos(strtolower($message), "[url=http") === false)) {
				die("spam link detected, rejecting feedback");
			}

			$message=$message."userAgent: ".$_SERVER['HTTP_USER_AGENT']."\n\n";
			$message=$message."referer: ".$_SERVER["HTTP_REFERER"]."\n\n";

			$from="admin@emphasize.de";
			if (strlen(r("from")) > 2) {
				$from=r("from");
			}

			$title = "[feedback] " . substr(r("message"), 0, 40);
			enqueueMail("admin@emphasize.de",
					$title,
					$message,
					"From: " . $from. "\r\nContent-Type: text/plain; charset=utf-8\r\nContent-Transfer-Encoding: 8bit\r\n");
			echo("<i18n key='fdb1'><en>Thank your for your feedback. Text transmitted</en><de>Danke für das Feedback. Übermittelt wurde</de><fr>Merci pour la rétroaction. A été envoyé</fr><es>Gracias por la información. Fue enviado</es></i18n>:<br />");
			echo('<pre style="border: 1px solid rgb(0, 0, 0); padding: 0.5em; color: black; background-color: rgb(255, 255, 187);">');
			echo($message);
			echo("</pre>");
		} else {
			echo("Sending feedback failed.<br />No feedback text specified");
		}
		echo("<br /><i18n key='fdb2'><en>Please close this confirmation window/tab to continue.</en><de>Bitte dieses Bestätigungs-Fenster/-Tab schließen um fortzufahren.</de><fr>S'il vous plaît fermer cette fenêtre de confirmation / onglet pour continuer.</fr><es>Por favor, cierre esta ventana de confirmación ficha para continuar.</es></i18n><br /></p>");
		bottom();
		?>