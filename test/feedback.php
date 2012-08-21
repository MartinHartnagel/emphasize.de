<title>Feedback Sent</title>
</head>
<body>
	<p>
		<?php

		if (!empty($_POST)) {
			if (!isset($_POST["results"])) {
			  die("spam link detected, rejecting feedback");
			}
  		$message=str_replace(array("_q", "_a", "_g", "_l", "_u"), array("\"", "'", ">", "<", "_"), stripslashes($_POST["results"]));

			$from="emphasize-test@emphasize.de";

			$c = preg_match_all('/<h2 id="qunit-userAgent">(.*)<\/h2>/imsU', $message, $matches);
			if ($c > 0) {
			  $browser=trim($matches[1][0]);
			}
			$c = preg_match_all('/Tests completed in (.*) milliseconds./imsU', $message, $matches);
			if ($c > 0) {
			  $ms=trim($matches[1][0]);
			}
			$c = preg_match_all('/<span class="passed">(.*)<\/span> tests of <span class="total">(.*)<\/span> passed, <span class="failed">(.*)<\/span> failed./imsU', $message, $matches);
			if ($c > 0) {
			  $passed=trim($matches[1][0]);
			  $total=trim($matches[2][0]);
			  $failed=trim($matches[3][0]);
			}

			$title = "[emphasize-test] $failed:$passed/$total $ms ms $browser";
			enqueueMail("martin@emphasize.de",
					$title,
					$message,
					"From: " . $from. "\r\nContent-Type: text/html; charset=utf-8\r\nContent-Transfer-Encoding: 8bit\r\n");
		} else {
			echo("Sending feedback failed.<br />No feedback text specified");
		}
		?>
	</p>
</body>
</html>
