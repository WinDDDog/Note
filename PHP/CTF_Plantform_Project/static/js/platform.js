$(document).ready(function(){
	$('input').click(function(){
		$('#submit-result .alert').hide();
		$('#submit-flag').attr('disabled',false);
    	});
	$('#person').click(function(){
		$.get('/controll/person', function(data){
			$('#main-body').html(data);
		});
	});
	$('#submit-flag').click(function(){
		var data = {};
		var url = "/controll/sub";
		$('#submit-flag').attr('disabled',true);
		data['flag'] = $.trim($('#flag').val());
		data['problemid'] = $('#problemid').val();
		$.ajax({
			url: url,
			type: 'post',
			data: data,
			dataType: 'json',
			success: function(data){
				if (data.code == 1) {
					$('#suc').show();
					setTimeout('sub_f()',2000);
				}
				else if(data.code == 2){
					$('#done').show();
				}
				else if (data.code == 3) {
					$('#error').show();
				}
				else if (data.code == 4) {
					$('#bad').show();
				}
				else {
					$('#ser-error').show();
				}
			},
			error: function(){
				$('#ser-error').show();
			}
		});
		//$('#submit-flag').attr('disabled',false);
	});
	$('#new').click(function(){
		$('#main-body').html('<img src="/static/img/loading.gif"/>');
		$.get('/controll/week', function(data) {
			$('#main-body').html(data);
		});
	});
});
