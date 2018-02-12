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