( function ( $ ) {
	
	var fsub = {
			setOutcome : function(result) {
				var successElement = document.querySelector('.success');
				var errorElement = document.querySelector('.error');
				successElement.classList.remove('visible');
				errorElement.classList.remove('visible');
		
				  if (result.token) {
					console.log(result.token);
				  } else if (result.error) {
					$('.error').text(result.error.message);
				  }
			},// end setOutcome
			setToken : function(result) {
				if (result.error) {
				  $('#modal1 .modal-content').empty();
				  $('#modal1 .modal-content').append('<div>'+result.error.message+'</div><small>code: '+result.error.code+'</small>');
				  setTimeout(function(){$('#modal1').modal('close')},10000);
				  console.log(result.error);
				  return false;
			  	}
			  
				  data.token = result.token.id;
				  
				  $.post(
					stajax.ajaxURL,
					data,
					function(response) {
						var action = {};
						
						if (response.success) {
							action.ST = function() {
								window.location.reload();
							};
							action.action = 'Success';
							action.color = 'olive';
							action.msg = 'You will be redirected shortly';
							action.icon = 'done';
							
						} else {
							action.ST = function() {
								$('#modal1').modal('close');
							};
							action.action = 'Error';
							action.color = 'red-text text-darken-3';
							action.msg = response.data.message;
							action.icon = 'report_problem';
						}
						var appended = '<div>';
						appended += '<h2 class="'+action.color+'">';
						appended += '<i class="material-icons">'+action.icon+'</i> ';
						appended += action.action+'</div>';
						appended += '<small>'+action.msg+'</small><br/>';
						appended += (!response.success)?'<small>err: '+response.data.error+'</small>':'';
						
						console.log(action);
						$('#modal1 .modal-content').empty();
						$('#modal1 .modal-content').append(appended);
						setTimeout(function(){action.ST()},3000);
				});
			},//end setToken
			checkout : function() {
				
				$('.modal-content').append($('<div id="wrapper_line-item" class="col s12">').load('<?php echo get_stylesheet_directory_uri(); ?>/templates/html/_checkout.html',
				function() {
					card.mount('#sttv_card_element');
				}));
				
			}
			
		};
	
	
} ( jQuery ) );