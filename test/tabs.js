testPage('/', function() {
	module("Tabs");

	test('Login', function() {
		  ok($('#loginName').is(':visible'), 'Login-TextField visible');
		  $('#loginName').val(login);
		  ok($('#loginPassword').is(':visible'), 'Password-TextField visible');
		  $('#loginPassword').val(password);
		  ok($('#loginSubmit').is(':visible'), 'Login-Submit visible');
		  
		  assertResponse('LoggedIn', function() {
			  ok($('#tabs').is(':visible'), 'Tabs visible');
			  
			  ok($('.ui-icon-pencil').is(':visible'), 'Edit Tab visible');
			  $('.ui-icon-pencil').click();
			  equal($('#tabs li').size(), 5, "4 initial + 1 create new Tab");
			  $('.ui-icon-trash:visible').click();
			  equal($('#tabs li').size(), 4, "leaving 3 initial + 1 create new Tab");
		  });		  
		  $('#loginSubmit').click();
	});
});
