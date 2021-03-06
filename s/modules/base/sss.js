( function ( $ ) { //begin wrapper
	"use strict";

// Opener functions

$('#main-menu, #login').click(function(e) {
	e.preventDefault();
	if ( $(this).is( "#login" )) {
		$('body').addClass('login-sidebar-open');
	} else {
		$('body').addClass('nav-sidebar-open');
	}
});



// Closer function
var closerFunc = function() {
	$('body').removeClass('nav-sidebar-open login-sidebar-open');
};
$('#acctmodal, .close, .sttvmodal').on('click touchstart',function(e) {
	e.preventDefault();
	closerFunc();
});
var thenav = $('body.nav-sidebar-open #main-nav');
thenav.on('click touchstart',function(e) {
	if (e.offsetX > thenav.offsetWidth) {
		alert('Clicked!');
		e.preventDefault();
		closerFunc();
	}
});

$('li.menu-item-has-children>a').click(function(e) {
	e.preventDefault();
	//console.log('click happened');
	$(this).siblings('ul.sub-menu').toggleClass('active').promise().done(function(){
		$('ul.sub-menu').not(this).removeClass('active');
	});
});

$('form#sttv_login_form').on('submit',function(e) {
	e.preventDefault();
	var loader = '<img src="'+sttvAjax.contentURL+'/i/sttv-spinner.gif" alt="Loading..." />';
	var ld = $('#loading_overlay');
		ld.html(loader).promise().done(function(){
			$(this).fadeIn(250);
		});
		
		
	var formData = {
		action: 'sttvlogin',
		nonce:  this.sttv_nonce_ver.value,
		sttv_login: this.sttv_user.value,
		sttv_pass: this.sttv_pass.value,
		g_recaptcha_response: grecaptcha.getResponse()
	};
	
	$.post(sttvAjax.ajaxURL, formData, function(data){
			
			if(data.success === false){
				ld.fadeOut(250);
				$('p.error').html(data.data.message).css('display','block');
			} else {
				ld.empty().html('<p class="sblock"><strong><span class="dashicons dashicons-yes"></span></strong></p>').fadeIn(250);
				$('.sblock').hide().fadeIn(250,function() {
					setTimeout(location.reload(),3000);
				});
			}
			
		});
});

} ( jQuery ) ); //end wrapper