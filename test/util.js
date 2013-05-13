var tests = new Array();
var currentCallback = null;
var feedbackSent = false;

function testPage(page, callback) {
	tests.push({
		"page" : page,
		"callback" : callback
	});
}

function assertResponse(name, callback) {
	if (currentCallback != null) {
		ok(false, "uncalled callback function: " + currentCallback);
	}
	currentCallback = {
		"name" : name,
		"callback" : callback
	};
}

function nextTest() {
	if (tests.length > 0) {
		var test = tests.shift();
		if (currentCallback != null) {
			ok(false, "uncalled callback function: " + currentCallback);
		}
		currentCallback = {
			"name" : null,
			"callback" : test.callback
		};

		$$('#testFrame').attr('src',
				location.protocol + '//' + location.hostname + test.page);
	} else if (currentCallback == null) {
		sendResults();
	}
}

function runTests() {

	$$('#testFrame').load(function() {
		// grab jQuery from inside the document
		jQuery = window.frames[0].jQuery;
		if (jQuery != null) {
			$ = jQuery;

			// turn off async so tests will wait for ajax results
			$.ajaxSetup({
				async : false
			});

			// turn off animations so they do no beark tests
			$.fx.off = true;

			if (currentCallback != null) {
				var callbackElement = currentCallback;
				currentCallback = null;
				if (callbackElement.name == null) {
					callbackElement.callback();
				} else {
					test(callbackElement.name, callbackElement.callback);
				}
			}
			nextTest();
		}
	});
	nextTest();
}

function sendResults() {
	if (feedbackSent) {
		return false;
	}
	feedbackSent = true;
	module("COMPLETED");
	res = $$('body div:nth-child(2)').html();
	$$.ajax({
		type : "POST",
		url : location.protocol + '//' + location.hostname
				+ "/test/feedback.php",
		data : ({
			"results" : saveMask(res)
		}),
		success : function(msg) {
			if (location.hostname != "next.emphasize.de") {
				alert("test results transmitted - thanks for helping");
			}
			test('Feedback', function() {
			});
		},
		error : function(req, status, error) {
			alert("failed transmitting the test results - " + error + " "
					+ status);
		}
	});
	return false;
}

function saveMask(s) {
	return s.replace(/_/g, "_u").replace(/</g, "_l").replace(/>/g, "_g")
			.replace(/'/g, "_a").replace(/"/g, "_q").replace(/&apos;/g, "_a")
			.replace(/&quot;/g, "_q");
}