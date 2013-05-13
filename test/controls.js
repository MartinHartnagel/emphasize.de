testPage('/', function() {
	module("Page Controls");

	test('Help toggles, Feedback popups, no 18ns', function() {
		  ok($('#showHelp').is(':visible'), 'Help-Button visible');
		  ok(!$('#toggleHelp_language').is(':visible'), 'Language-Selection Help invisible');
		  $('#showHelp').click();
		  ok($('#toggleHelp_language').is(':visible'), 'Language-Selection Help visible');
		  $('#showHelp').click();
		  ok(!$('#toggleHelp_language').is(':visible'), 'Language-Selection Help invisible again');
		  
		  ok($('#feedback').is(':visible'), 'Feedback-Button visible');
		  $('#feedback').click();
		  ok($("body").html().indexOf("undefined i18n")==-1, "undefined i18n detected");		  
	});
});
