testPage('/?lang=de', function() {
	module("Demo");

	test('Blog Ajax Reloads', function() {
		  ok($('#bContent a').is(':visible'), 'Links are visible');
		  
		  // click on realen Zeitaufwand 
		  $('#bContent a').slice(1, 2).click();
		  ok($("#bContent").text().trim().indexOf("unbewusst oder selbst")>-1, "unbewusst oder selbst detected");
		  ok($("#bContent").text().trim().indexOf("wann ich es tue")>-1, "ich wann ich es tue detected");
		  ok($("body").html().indexOf("undefined i18n")==-1, "undefined i18n detected");

		  var testFrame=parent.document.getElementById('testFrame').contentWindow;
		  var hash=testFrame.location.hash;
		  
		  // click on deutlich machen
		  $('#bContent a').slice(2, 3).click();
		  ok($("#bContent").text().trim().indexOf("pointieren")>-1, "unbewusst oder selbst detected");
		  ok($("#bContent").text().trim().indexOf("wann ich es tue")==-1, "ich wann ich es tue detected");
		  ok($("body").html().indexOf("undefined i18n")==-1, "undefined i18n detected");
 
		  // test hashchange navigation
		  testFrame.location.hash=hash;
		  $(testFrame).hashchange();
		  ok($("#bContent").text().trim().indexOf("pointieren")==-1, "unbewusst oder selbst detected");
		  ok($("#bContent").text().trim().indexOf("unbewusst oder selbst")>-1, "unbewusst oder selbst detected");
		  ok($("#bContent").text().trim().indexOf("wann ich es tue")>-1, "ich wann ich es tue detected");
		  ok($("body").html().indexOf("undefined i18n")==-1, "undefined i18n detected");
	
	});
});
