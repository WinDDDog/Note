$('#add-new-problem').click(function(){
	$.get('/adminfuckme_controll/view_add', function(data) {
		$('#admin-main-content').html(data);
	});
});
$('#manage-problem').click(function () {
	$.get('/adminfuckme_controll/view_edit', function (data) {
		$('#admin-main-content').html(data);
	});
});
$('#system-setting').click(function () {
	$.get('/adminfuckme_controll/view_set', function (data) {
		$('#admin-main-content').html(data);
	});
});
$('#user-problem').click(function () {
	$.get('/adminfuckme_controll/view_user', function (data) {
		$('#admin-main-content').html(data);
	});
});
$('#class-problem').click(function () {
	$.get('/adminfuckme_controll/view_class', function (data) {
		$('#admin-main-content').html(data);
	});
});