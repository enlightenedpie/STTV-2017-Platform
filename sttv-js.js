// =require "base.js"
// Closer function
var closerFunc = function(callback) {
	jQuery('body').removeClass('nav-sidebar-open login-sidebar-open');
	if (typeof callback == 'undefined') {callback = function(){return true;}}
	callback();
};

( function ( $ ) { //begin wrapper
	"use strict";

var _st = {
	request : function(obj) {
		var ajaxp = {
			url: obj.route || '',
			method: obj.method || 'GET',
			headers: obj.headers || {},
			processData : false,
			dataType : obj.dataType || 'json',
			success: function(data){
				typeof obj.success !== 'undefined' && obj.success(data);
			},
			error: function(x,s,r){
				typeof obj.error !== 'undefined' && obj.error([x,s,r]);
			}
		}
		if (ajaxp.method !== 'GET') {
			ajaxp['data'] = JSON.stringify(obj.cdata || {})
		}
		$.ajax(ajaxp)
	}
}

// Opener functions

$('#main-menu').click(function(e) {
	e.preventDefault();
	if ( $(this).is( "#login" )) {
		$('body').addClass('login-sidebar-open');
	} else {
		$('body').addClass('nav-sidebar-open');
	}
});

$('#acctmodal, .close').on('click touchstart',function(e) {
	e.preventDefault();
	closerFunc();
});
$('.read-more').on('click touchstart',function(e) {
	e.preventDefault();
	$(this).parent().css({'display':'none'});
	$('#content-wrapper').css({'max-height':'none'});
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
	$(this).siblings('ul.sub-menu').toggleClass('active').promise().done(function(){
		$('ul.sub-menu').not(this).removeClass('active');
	});
});

$('form#sttv_login_form').on('submit',function(e) {
	e.preventDefault();
	if (0 === $('#sttv_user').val().length){
		$('.message').html('Username is required')
		return;
	}

	var loader = '<img src="'+stajax.contentURL+'/i/sttv-spinner.gif" alt="Loading..." />',
		ld = $('.loading_overlay');
	ld.append(loader).promise().done(function(){
		$(this).fadeIn(250);
	});

	_st.request({
		route : stajax.rest.url+'/auth?action=login',
		method : 'POST',
		headers : {
			'X-WP-Nonce' : stajax.rest.nonce,
			'X-STTV-Auth' : btoa(this.sttv_user.value+':'+this.sttv_pass.value)
		},
		success : function(data) {
			if ( data.code == 'login_success' ) {
				ld.empty().html('<p class="sblock"><strong><i class="material-icons">done</i></strong></p>').fadeIn(250)
				$('.sblock').hide().fadeIn(250)
				setTimeout(function(){
					window.location.href = data.redirect
				},250);
			}
		},
		error : function(x) {
			var data = x[0].responseJSON,
				msg = ( typeof data.errors.too_many_retries !== 'undefined') ? data.errors.too_many_retries[0] : data.message;

			$('.message').html(msg)
			ld.fadeOut(250)
			console.log(data)
		}
	})
});

$('form#sttv_contact').on('submit',function(e) {
	e.preventDefault();
	var loading = $('.loading_overlay',$(this).parent()).html('<img src="'+stajax.contentURL+'/i/sttv-spinner.gif" alt="Loading..." />')

	loading.fadeIn(250)

	_st.request({
		route : stajax.rest.url+'/contact',
		method : 'POST',
		headers : {
			'X-WP-Nonce' : stajax.rest.nonce
		},
		cdata : {
			g_recaptcha_response : grecaptcha.getResponse(),
			sttv_contact_name: this.sttv_contact_name.value,
			sttv_contact_email: this.sttv_contact_email.value,
			sttv_contact_subject: this.sttv_contact_subject.value,
			sttv_contact_message: this.sttv_contact_message.value
		},
		success : function(data) {
			console.log(data)
			if ( data.sent ) {
				loading.empty().html('<p class="sblock"><strong><i class="material-icons">done</i></strong></p>').fadeIn(250)
				var s = $('.sblock');
				var p = $('<p/>',{"class":"smessage"});
				p.appendTo(s).append(data.message);
				$('.sblock').hide().fadeIn(250)
			} else {
				$('.message').html(data.message)
				loading.fadeOut(250)
			}
		},
		error : function(x) {
			$('.message').html('Something went wrong. Please refresh the page and try again.')
			loading.fadeOut(250)
			console.log(x)
		}
	})
	
  });

	$('#subscribe_page_mc').on('submit',function(e){
		e.preventDefault();
		var form = $(this)

		var loading = $('.loading_overlay',$(this).parent()).html('<img src="'+stajax.contentURL+'/i/sttv-spinner.gif" alt="Loading..." />')

		loading.fadeIn(250)

		var fields = {
			fname : $('#sttv_mc_fname',form).val(),
			lname : $('#sttv_mc_lname',form).val(),
			email : $('#sttv_mc_email',form).val(),
			g_recaptcha_response : grecaptcha.getResponse()
		}
		
		_st.request({
			route : stajax.rest.url+'/subscribe',
			method : 'POST',
			cdata : fields,
			headers : {'X-WP-Nonce' : stajax.rest.nonce},
			success : function(d){
				$('input, button',form).prop('disabled',true)
				grecaptcha.reset()
				loading.empty().html('<p class="sblock"><strong><i class="material-icons">done</i></strong></p>').fadeIn(250)
				var s = $('.sblock');
				var p = $('<p/>',{"class":"smessage"});
				p.appendTo(s).append(d.message);
				$('.sblock').hide().fadeIn(250)
				console.log(d)
			},
			error : function(x){
				$('.message',form).html('Something went wrong. Please refresh the page and try again.')
				loading.fadeOut(250)
				console.log(x)
			}
		})
	});

} ( jQuery ) ); //end wrapper

// =require "handlers.js"
( function ( $ ) { //begin wrapper
	"use strict";
	
	$(document).ready(function() {
    	$('select').material_select();
	  });
  
  } ( jQuery ) ); //end wrapper