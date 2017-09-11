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
					$('.sblock',this.ld).append('<p class="smessage">'+d.message+'</p>');
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