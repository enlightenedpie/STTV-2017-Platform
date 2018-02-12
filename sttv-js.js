// =require "base.js"
// Closer function
var closerFunc = function(callback) {
	jQuery('body').removeClass('nav-sidebar-open login-sidebar-open');
	if (typeof callback == 'undefined') {callback = function(){return true;}}
	callback();
};
( function ( $ ) { //begin wrapper
	"use strict";

// Opener functions

$('#main-menu').click(function(e) {
	e.preventDefault();
	if ( $(this).is( "#login" )) {
		$('body').addClass('login-sidebar-open');
	} else {
		$('body').addClass('nav-sidebar-open');
	}
});

$('#acctmodal, .close, .sttvmodal').on('click touchstart',function(e) {
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
			console.log(data);
			if(data.success === false){
				ld.fadeOut(250);
				$('p.error').html(data.data.message).css('display','block');
			} else {
				ld.empty().html('<p class="sblock"><strong><i class="material-icons">done</i></strong></p>').fadeIn(250);
				$('.sblock').hide().fadeIn(250);
					setTimeout(function(){window.location.replace('/my-account');},3000);
			}
			
		});
});

} ( jQuery ) ); //end wrapper

// =require "handlers.js"
( function ( $ ) { //begin wrapper
	"use strict";
	
	
	$(document).ready(function() {
    	$('select').material_select();
	  });
	  
	$('#logout-btn a').click(function(e){
		e.preventDefault()
		$.post(stajax.rest.url+'/auth?action=logout',function(d){
			window.location.href = d
		});
	})
  
  } ( jQuery ) ); //end wrapper
$('.submitter').click(function(e) {
		e.preventDefault();
		
		var submitter = {
				form : $(this).closest('form'),
				token : $('.g-recaptcha-response',this.form).val(),
				whichForm : $('input[name="whichform"]',this.form).val()
			};
		//console.log(submitter.form.attr('id'));
		submitter.formSer = submitter.form.serialize();
		submitter.formData = {
			action : 'sttvsubmitter',
			value : submitter.formSer
		};
		submitter.action = {
			ld : $('.loading_overlay',submitter.form.parent()),
			postForm : function() {
				//var parentThis = this;
				return $.post(
					stajax.ajaxURL, 
					submitter.formData,
					function(data){
						//console.log(data);
				},'json');
			},
			beforeSubmit : function() {
				var loader = '<img src="'+stajax.contentURL+'/i/sttv-spinner.gif" alt="Loading..." />';
				
				this.ld.html(loader).promise().done(function(){
					$(this).fadeIn(500);
				});
			},
			formSuccess : function(data) {
				var d = data.data;
				this.ld.empty().html('<p class="sblock"><i class="material-icons">done</i></p>').fadeIn(500);
				if (d.name) {
					$('.sblock',this.ld).hide().fadeIn(500,function() {
						window.location.replace('/my-account');
							//$('#login .link-text').html(d.name);
					});
				} else if (d.sent || d.subscribed) {
					var s = $('.sblock',this.ld);
					var p = $('<p/>',{"class":"smessage"});
					p.appendTo(s).append(d.message);
					
					$("#"+submitter.form.attr('id')+" :input").prop("disabled",true);
				} else {
					alert("Something went wrong. Please reload the page.");
				}
			},
			formFail : function(data) {
				this.ld.fadeOut(500);
				$('p.message',submitter.form).html(data.data.message).css('display','block');
			}
		};
		var sa = submitter.action;
		
		/** Let's check the recaptcha response, IF it exists **/
		if (submitter.token != null && submitter.token.length === 0) {
			alert('Please prove you\'re not a robot!');
			return;
		}
		
		sa.beforeSubmit(); /** Loading/Processing overlay **/
		
		sa.postForm().done(function(fdata) { //submit the form
			//console.log(fdata);
			return (fdata.success) ? sa.formSuccess(fdata) : sa.formFail(fdata);
		});
	});