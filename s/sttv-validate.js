( function ( $ ) { //begin wrapper
	"use strict";

	$('form#sttv_contact').on('submit',function(e) {
		e.preventDefault();
		
		var formData = {
			action: 'sttvcontact',
			sttv_contact_name: this.sttv_contact_name.value,
			sttv_contact_email: this.sttv_contact_email.value,
			sttv_contact_subject: this.sttv_contact_subject.value,
			sttv_contact_message: this.sttv_contact_message.value
		};
		
		$.post(sttvAjax.ajaxURL, formData, function(data){
			
			if(data.success === false){
				$('.message').html(data.data);
				console.log('fail');
			} else {
				$('.message').html(data.data);
				$('form#sttv_contact').trigger("reset");
				console.log(data);
			}
			
		});
		
	});
	
	
} (jQuery) );