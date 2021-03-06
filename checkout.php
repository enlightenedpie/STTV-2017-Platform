<?php 

/* Template Name: Checkout Page */

$countrydd = wp_remote_get('https://gist.githubusercontent.com/enlightenedpie/888ba7972fa617579c374e951bd7eab9/raw/b987e55ddc4cde75f50298559e3a173a132657af/gistfile1.txt');
?>
<?php get_header('stripped'); ?>
<div id="content" class="row" style="height:100vh">
    <div id="checkout-right" class="col s12 m5 push-m7">
        <div class="card-content row">
            <div class="col s12">
                <table id="totals_table" class="highlight responsive-table">
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><div class="divider"></div></td>
                        </tr>
                        <tr>
                            <td id="coupon_cell">
                                <div class="input-field col s6">
                                    <input id="sttv_coupon" data-error="Invalid coupon" name="sttv_coupon" type="text"/>
                                    <label for="sttv_coupon">Coupon Code</label>
                                </div>
                            </td>
                            <td style="text-align:right">Total:</td>
                            <td style="text-align:right" id="signup_total_price"><span style="font-weight:bold"></span></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col s12">
                <fieldset id="course_options">
                    <legend>Shipping Options</legend>
                    <div class="input-field col s12" style="margin-top:0px">
                        <!--input class="filled-in" type="checkbox" name="sttv_no_trial" id="sttv_no_trial" checked/><label style="margin-top:1em" for="sttv_no_trial">Skip the trial period and start right away</label><br/-->
                        <input class="filled-in" type="checkbox" name="sttv_digital_book" id="sttv_digital_book"/><label style="margin-top:1em;margin-bottom:1em" for="sttv_digital_book">I want Priority Shipping (3-4 days)((U.S. Only))</label><br/>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <div id="checkout-left" class="col s12 m7 pull-m5">
        <header id="checkoutheader">
            <div id="checkout-logo-box" class="valign-wrapper">
                <img src="https://supertutortv.com/wp-content/uploads/2017/01/sttv_site_logo.png" alt="logo" />
                <h3>Checkout</h3>
            </div>
        </header>
        <div id="wrapper_line-item" class="col s12" style="text-align:left">
            <div id="customer_info" class="row">
                <div id="account_info" class="col s12">
                    <div class="row">
                        <h4>Your Information</h4>
                        <div class="input-field col s6">
                            <input type="text" class="validate" name="sttv_firstname" required/>
                            <label for="sttv_firstname">First Name</label>
                        </div>
                        <div class="input-field col s6">
                            <input type="text" class="validate" name="sttv_lastname" required/>
                            <label for="sttv_lastname">Last Name</label>
                        </div>
                        <div class="input-field col s12">
                            <input id="sttv_email" class="validate" name="sttv_email" type="email" required/>
                            <label data-error="Invalid email address" for="sttv_email">Email Address</label>
                        </div>
                        <div class="input-field col s12">
                            <input id="sttv_password" name="sttv_password" type="password" required/>
                            <label for="sttv_password">Choose Password</label>
                        </div>
                    </div>
                </div>
                <div id="shipping_info" class="col s12">
                    <div class="row">
                        <div class="col s12">
                            <h4 style="display:inline">Shipping Address</h4>
                        </div>
                        <div class="input-field col s12 m6">
                            <input id="sttv_shipping_address1" name="sttv_shipping_address1" type="text" class="validate" required/>
                            <label for="sttv_shipping_address1" data-error="Invalid format" >Address Line 1</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input id="sttv_shipping_address2" name="sttv_shipping_address2" type="text" />
                            <label for="sttv_shipping_address2">Address Line 2</label>
                        </div>
                        <div class="input-field col s6 m3">
                            <input id="sttv_shipping_city" class="validate" name="sttv_shipping_city" type="text" required/>
                            <label for="sttv_shipping_city">City</label>
                        </div>
                        <div class="input-field col s6 m3">
                            <input id="sttv_shipping_state" class="validate" name="sttv_shipping_state" type="text" required/>
                            <label for="sttv_shipping_state">State</label>
                        </div>
                        <div class="input-field col s6 m3">
                            <input id="sttv_shipping_pcode" class="validate" name="sttv_shipping_pcode" type="tel" required/>
                            <label for="sttv_shipping_pcode">Postal Code</label>
                        </div>
                        <div class="input-field col s6 m3">
                            <select class="country-dd browser-default validate" name="sttv_shipping_country" required>
                                <option value disabled selected>Country...</option>
								<?php print $countrydd['body']; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="billing_info" class="col s12">
                    <div id="billing_fields" class="row">
                        <div class="col s12 m6">
                            <h4 style="display:inline">Billing Address</h4>
                        </div>
                        <div class="col s12 m6">
                            <input class="filled-in" type="checkbox" id="same_shipping" /><label style="margin-top:1em" for="same_shipping">Same as shipping</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input id="sttv_billing_address1" name="sttv_billing_address1" type="text" class="validate" required/>
                            <label class="active" for="sttv_billing_address1" data-error="Invalid format" >Address Line 1</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input id="sttv_billing_address2" name="sttv_billing_address2" type="text" />
                            <label class="active" for="sttv_billing_address2">Address Line 2</label>
                        </div>
                        <div class="input-field col s6 m3">
                            <input id="sttv_billing_city" name="sttv_billing_city" type="text" required/>
                            <label class="active" for="sttv_billing_city">City</label>
                        </div>
                        <div class="input-field col s6 m3">
                            <input id="sttv_billing_state" name="sttv_billing_state" type="text" required/>
                            <label class="active" for="sttv_billing_state">State</label>
                        </div>
                        <div class="input-field col s6 m3">
                            <input id="sttv_billing_pcode" name="sttv_billing_pcode" type="tel" required/>
                            <label class="active" for="sttv_billing_pcode">Postal Code</label>
                        </div>
                        <div class="input-field col s6 m3">
                            <select class="country-dd browser-default" name="sttv_billing_country" required>
                                <option value disabled selected>Country...</option>
								<?php print $countrydd['body']; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-content row">
                <h4>Payment</h4>
                <div class="input-field col s12">
                    <input name="sttv_cardname" type="text" required/>
                    <label class="active" for="sttv_cardname">Name On Card</label>
                </div>
                <div class="col s12">
                    <div id="sttv_card_element"></div>
                    <p class="success"></p>
                    <p class="error token"></p>
                </div>
                <div class="col s12">
                    <div class="input-field col s12" style="margin-top:0px">
                        <input class="filled-in" type="checkbox" name="sttv_mailinglist" id="sttv_mailinglist" checked/><label style="margin-top:1em" for="sttv_mailinglist">Sign me up for future discounts, coupons, and giveaways from SupertutorTV</label><br/>
                        <input class="filled-in" type="checkbox" name="t_and_c" id="t_and_c" /><label style="margin-top:1em;margin-bottom:1em" for="t_and_c">I have read the Terms and Conditions (required)</label>
                    </div>
                </div>
                <div class="col s12">
                    <button type="submit" class="signup-submit button-wide z-depth-1 waves-effect waves-light" disabled><span></span></button>
                </div>
            </div>
        </div>
    </div>

 <script>
 	var stripe = Stripe(stajax.stripe.public_key);
	var elements = stripe.elements();
	var card = elements.create('card',{
		hidePostalCode: true
	});
	card.mount('#sttv_card_element');
	
	card.on('change', function(event) {
		fsub.setOutcome(event);
	});
	
	$('[name=sttv_email],[name=sttv_billing_pcode],input[name=sttv_coupon]').blur(function(e) {
		e.preventDefault();
		var tThis = $(this);
		
		if (!tThis.val()) {
			if (tThis.is('input[name=sttv_coupon]')) {
				plan.disc = plan.discp = 0;
				tThis.removeClass('valid invalid');
				price_updater();
			} else if (tThis.is('input[name=sttv_billing_pcode]')) {
				plan.tax = 0;
				price_updater();
			}
			return;
		}
		//console.log(tThis.attr('name'));
		
		var data = {
			action : tThis.attr('name'),
			value : tThis.val()
		}
		
		setTimeout( function() {
			$.post(
			stajax.ajaxURL,
			data,
			function(response) {
		
				var rd = response.data;
				var rs = response.success;
				
				//console.log(response);
				
				switch (rd.field) {
					case 'testing' :
						console.log(rd);
					case 'email' :
						if (rs) {
							if (rd.current) {return;}
							tThis.siblings('label').attr({
								'data-success':rd.msg
								
							});
							tThis.removeClass('invalid');
							tThis.addClass('valid');
						} else {
							tThis.siblings('label').attr({
								
								'data-error':rd.msg
								
							});
							tThis.removeClass('valid');
							tThis.addClass('invalid');
						}
						break;
						
					case 'zip' :
						plan.tax = rd.tax;
						price_updater();
						console.log(rd);
						break;
						
					case 'coupon' :
					
						
						if (!rs) {
							tThis.siblings('label').attr({
								'data-error':'Invalid coupon'
								
							});
							
							tThis.removeClass('valid');
							tThis.addClass('invalid');
							plan.disc = plan.discp = 0;
							price_updater();
							
						} else {
							if (rd.obj.percent_off) {
								plan.disc = 0;
								plan.discp = rd.obj.percent_off;
								var msg = String(rd.obj.percent_off)+'% off';
							} else if (rd.obj.amount_off) {
								plan.discp = 0;
								plan.disc = rd.obj.amount_off;
								var msg = '$'+String(rd.obj.amount_off/100)+' off';
							}
							tThis.siblings('label').attr({
								
								'data-success': msg
								
							});
							tThis.removeClass('invalid');
							tThis.addClass('valid');
							window.plan.coupon = rd.obj.id;
							price_updater();
						}
						//console.log(plan);
						break;
					
				} 
			});
		},1);
		
	});
	 $('#t_and_c, #sttv_digital_book').change(function() {
		 var disb = $(this).is(":checked");
		 var valID = (disb && fsub.valid);
			 $('.signup-submit').prop('disabled',!valID);
		 
		 if ($(this).is('#sttv_digital_book')) {
			 price_updater();
		 }
		 
	 });
	 $('select[name=sttv_shipping_country]').one('change',function() {
		 fsub.shipWasChecked = $('#sttv_digital_book').is(":checked");
	 });
	 $('select[name=sttv_shipping_country]').change(function(){
		 var shipUS = $(this).val();
			 
		 if (!shipUS || shipUS !== 'US') {
			 $('#sttv_digital_book').prop('checked',false)
			 $('#sttv_digital_book').prop('disabled',true)
		 } else {
			 $('#sttv_digital_book').prop('checked',fsub.shipWasChecked)
			 $('#sttv_digital_book').prop('disabled',false)
		 }
		 price_updater();
	 });
	$('#same_shipping').change(function() {
		if ($(this).is(":checked")) {
			
			$('input[name=sttv_billing_address1]').val($('input[name=sttv_shipping_address1]').val());
			$('input[name=sttv_billing_address2]').val($('input[name=sttv_shipping_address2]').val());
			$('input[name=sttv_billing_city]').val($('input[name=sttv_shipping_city]').val());
			$('input[name=sttv_billing_state]').val($('input[name=sttv_shipping_state]').val());
			$('input[name=sttv_billing_pcode]').val($('input[name=sttv_shipping_pcode]').val());
			$('select[name=sttv_billing_country]').val($('select[name=sttv_shipping_country]').val());
			
			Materialize.updateTextFields();

		} else {
			$("#billing_fields :input").each(function(){
				$(this).val('');
			});
			$("select[name=sttv_billing_country]").prop("selectedIndex", -1);
		}
		$('input[name=sttv_billing_pcode]').blur();
	});
	
	$('.signup-submit').click(function(e) {
		e.preventDefault();
		var cEr = true;
		
		var sInputs = $('input','#wrapper_line-item');
		sInputs.each(function(k,v) {
			var msgTag = '';
			if ( $(this).is(':required') && (!$(this).val() || $(this).hasClass('invalid')) ) {
				var msgTag = (!$(this).val()) ? ' is required' : ' is invalid'
				cEr = false
			} else if ( $(this).hasClass('invalid') ) {
				var msgTag = ' is invalid'
				cEr = false
			}
			$('.error','#wrapper_line-item').html('&otimes; '+$(v).siblings('label').text()+msgTag);
			if (!cEr) {return cEr}
		});
		if (!cEr) {return cEr}
		
		// show 'processing' modal
		cModal.animate({
			scrollTop: 0
		}, 1);
		
		cModal.css('overflow','hidden');
		
		$('#checkout_modal_overlay').fadeIn(1,function() {
			$(this).prepend('<h2 style="margin-top:3em">PROCESSING...</h2>');
			$('span',this).text('This could take a minute if you have a slow connection.');
		});
		//end show modal
		
		var det = {
			name: $('input[name=sttv_cardname]').val(),
			address_line1: $('input[name=sttv_billing_address1]').val(),
			address_line2: $('input[name=sttv_billing_address2]').val(),
			address_city: $('input[name=sttv_billing_city]').val(),
			address_state: $('input[name=sttv_billing_state]').val(),
			address_zip: $('input[name=sttv_billing_pcode]').val(),
			address_country: $('select[name=sttv_billing_country]').val()
		};
			  
		  var data = {
			  inputs: sInputs.serialize(),
			  plan: window.plan.ID,
			  url: window.location.href,
			  action: 'sttvajax_signup'
		  };
		  //console.log(det,data);
		  
		  stripe.createToken(card, det).then(function(result){
			  data.token = result.token;
			  data.totals_table = $('#totals_table').wrap('<div/>').parent().html();
			
			$.post(stajax.ajaxURL,data,function(response) {
				console.log(response);
				var action = {};
				var cmOver = $('#checkout_modal_overlay');
						
						if (response.success) {
							action.ST = function() {
								gtag_report_conversion()
								setTimeout(function(){
									window.location.replace('/my-account')
								},5000);
							};
							action.action = 'Success';
							action.color = 'olive';
							action.msg = 'You will be redirected shortly';
							action.class = 'class="s-success"';
							
						} else {
							action.ST = function() {
								setTimeout(function() {
									cmOver.fadeOut(500,function(){
										$(this).empty();
										$(this).prepend('<img src="'+stajax.contentURL+'/i/sttv-spinner.gif"/><span></span>');
									});
									
									cModal.css('overflow','auto');
								},8000);
							};
							action.action = 'Error';
							action.color = 'red-text text-darken-3';
							action.msg = response.data.message;
							action.class = '';
						}
						var appended = '<div id="modal_results"><div id="sttv-emotee" '+action.class+'></div><br/><h2 class="'+action.color+'">'+action.action+'</h2><small>'+action.msg+'</small><br/>';
						if (!response.success){appended += '<small>err: '+response.data.error+'</small>';}
						appended += '</div>';
						
						//console.log(response);
						cmOver.empty();
						cmOver.append(appended);
						action.ST();
			})
			.fail(function(xhr,status,err){
				console.log(xhr,status,err);
			});
			  
			  
		  });
		  
	});
	price_updater();
 </script>

<?php get_footer('stripped'); ?>