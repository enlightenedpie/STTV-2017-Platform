<?php 

$user = $sgt['user']; 
$shipping = '<span>No shipping options available for mutli-user license orders.</span>';
$disabled = '';

if ( $sgt['type'] === 'checkout' ) {
$shipping = <<<HTML

HTML;
} else {
    $disabled = 'disabled';
}

?>
<section class="row">
    <header id="checkoutheader" class="col s12">
        <div id="checkout-logo-box" class="valign-wrapper">
            <img src="<?php header_image(); ?>" alt="<?php echo bloginfo('name');?>" />
            <h3>Checkout</h3>
        </div>
    </header>
	<div id="checkout-left" class="col s12 m4 checkout-test">
        <div id="wrapper_line-item" class="col s12" style="text-align:left">
            <div id="customer_info" class="row">
                <div id="account_info" class="col s12">
                    <div class="row">
                        <h4>Your Information</h4>
                        <div class="input-field left-col col s6">
                            <input type="text" class="validate" name="sttv_firstname" value="<?php print $user->first_name; ?>" required/>
                            <label for="sttv_firstname">First Name</label>
                        </div>
                        <div class="input-field left-col col s6">
                            <input type="text" class="validate" name="sttv_lastname" value="<?php print $user->last_name; ?>" required/>
                            <label for="sttv_lastname">Last Name</label>
                        </div>
                        <div class="input-field col s12">
                            <input id="sttv_email" class="validate" name="sttv_email" type="email" value="<?php print $user->user_email; ?>" required/>
                            <label data-error="Invalid email address" for="sttv_email">Email Address</label>
                        </div>
                        <div class="input-field col s12">
                            <input id="sttv_password" name="sttv_password" type="password" value="<?php print $user->user_pass; ?>" required/>
                            <label for="sttv_password">Choose Password</label>
                        </div>
                        <div class="input-field col s12">
                            <input id="sttv_phone" class="validate" name="sttv_phone" type="tel" value="" required/>
                            <label data-error="Invalid phone number" for="sttv_phone">Phone Number</label>
                        </div>
                    </div>
                </div>
                <div id="billing_info" class="col s12">
                    <div id="billing_fields" class="row">
                        <h4>Billing Address</h4>
                        <div class="input-field col s12">
                            <input id="sttv_billing_address1" name="sttv_billing_address1" type="text" class="validate" required/>
                            <label class="active" for="sttv_billing_address1" data-error="Invalid format" >Address Line 1</label>
                        </div>
                        <div class="input-field col s12">
                            <input id="sttv_billing_address2" name="sttv_billing_address2" type="text" />
                            <label class="active" for="sttv_billing_address2">Address Line 2</label>
                        </div>
                        <div class="input-field col s12">
                            <input id="sttv_billing_city" name="sttv_billing_city" type="text" required/>
                            <label class="active" for="sttv_billing_city">City</label>
                        </div>
                        <div class="input-field left-col col s6">
                            <input id="sttv_billing_state" name="sttv_billing_state" type="text" required/>
                            <label class="active" for="sttv_billing_state">State</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="sttv_billing_pcode" name="sttv_billing_pcode" type="tel" required/>
                            <label class="active" for="sttv_billing_pcode">Postal Code</label>
                        </div>
                        <div class="input-field col s12">
                            <select class="country-dd" name="sttv_billing_country" required>
                                <option value disabled selected>Country...</option>
								<?php print $sgt['countrydd']['body']; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
    <div id="checkout-middle" class="col s12 m4 cart-test">
        <div class="col s12">
            <div class="row">
                <h4>Shipping Options</h4>
                <div id="shipping_options" class="col s12">
                    <div class="shp-msg">
                        <?php print $shipping; ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="shipping_info" class="col s12">
            <div id="shipping_fields" class="row">
                <h4>Shipping Address</h4>
                <div class="col s12">
                    <input class="filled-in" type="checkbox" id="same_as_billing" /><label style="margin-top:1em" for="same_as_billing">Same as billing address</label>
                </div>
                <div class="input-field col s12">
                    <input id="sttv_shipping_address1" name="sttv_shipping_address1" type="text" class="validate" required/>
                    <label for="sttv_shipping_address1" data-error="Invalid format" >Address Line 1</label>
                </div>
                <div class="input-field col s12">
                    <input id="sttv_shipping_address2" name="sttv_shipping_address2" type="text" />
                    <label for="sttv_shipping_address2">Address Line 2</label>
                </div>
                <div class="input-field col s12">
                    <input id="sttv_shipping_city" class="validate" name="sttv_shipping_city" type="text" required/>
                    <label for="sttv_shipping_city">City</label>
                </div>
                <div class="input-field left-col col s6">
                    <input id="sttv_shipping_state" class="validate" name="sttv_shipping_state" type="text" required/>
                    <label for="sttv_shipping_state">State</label>
                </div>
                <div class="input-field col s6">
                    <input id="sttv_shipping_pcode" class="validate" name="sttv_shipping_pcode" type="tel" required/>
                    <label for="sttv_shipping_pcode">Postal Code</label>
                </div>
                <div class="input-field col s12">
                    <select class="country-dd validate" name="sttv_shipping_country" required>
                        <option value disabled selected>Country...</option>
                        <?php print $sgt['countrydd']['body']; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div id="checkout-right" class="col s12 m4 cart-test">
        <div class="row">
            <h4>Your Order</h4>
            <div id="cart-column" class="col s12">
                <div class="row headings-row">
                    <div class="col s2">Qty</div>
                    <div class="col s8">Item</div>
                    <div class="col s2 right-align">Price</div>
                </div>
                <div class="items-row"></div>
                <div class="row totals-row">
                    <div class="col s8">
                        <div class="input-field coupon col s12">
                            <input id="sttv_coupon" data-error="Invalid coupon" name="sttv_coupon" type="text" <?php print $disabled; ?>/>
                            <label for="sttv_coupon">Coupon Code</label>
                        </div>
                    </div>
                    <div id="total" class="col s4 right-align"><span id="ttltxt">Total: $<span>0</span></span></div>
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
                    <input class="filled-in" type="checkbox" name="t_and_c" id="t_and_c" /><label style="margin-top:1em;margin-bottom:1em" for="t_and_c">I have read the Terms and Conditions</label>
                </div>
            </div>
            <div class="col s12">
                <button type="submit" class="signup-submit button-wide z-depth-1 waves-effect waves-light" disabled><span>Place Order</span></button>
            </div>
        </div>
    </div>
</section><?php /*
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
 </script>*/ ?>
 <script>
 (function(){
    var mainJS = document.getElementById('sttv-js-main'),
        checkoutJS = document.getElementById('sttv-checkout-js')

    if ( null === checkoutJS ) {
        checkoutJS = document.createElement('script')
        checkoutJS.type = 'text/javascript'
        checkoutJS.src = '<?php print get_stylesheet_directory_uri().'/s/checkout.js' ?>'
        checkoutJS.id = 'sttv-checkout-js'
        mainJS.parentNode.insertBefore(checkoutJS, mainJS.nextSibling)
    }

    Materialize.updateTextFields()
 })()

var stripe = Stripe(stajax.stripe.public_key);
var elements = stripe.elements();
var card = elements.create('card',{
    hidePostalCode: true
});
card.mount('#sttv_card_element');

$('#same_as_billing').change(function() {
    if ($(this).is(":checked")) {
        
        $('input[name=sttv_shipping_address1]').val($('input[name=sttv_billing_address1]').val());
        $('input[name=sttv_shipping_address2]').val($('input[name=sttv_billing_address2]').val());
        $('input[name=sttv_shipping_city]').val($('input[name=sttv_billing_city]').val());
        $('input[name=sttv_shipping_state]').val($('input[name=sttv_billing_state]').val());
        $('input[name=sttv_shipping_pcode]').val($('input[name=sttv_billing_pcode]').val());
        $('select[name=sttv_shipping_country]').val($('select[name=sttv_billing_country]').val());
        
        $('select').material_select()
        _st.checkout.fields()

    } else {
        $("#shipping_fields :input").each(function(){
            $(this).val('');
        });
        $("select[name=sttv_shipping_country]").prop("selectedIndex", -1);
    }
    $('input[name=sttv_billing_pcode]').blur();

    _st.checkout.fields()
});

$('[name=sttv_email],[name=sttv_billing_pcode],input[name=sttv_coupon]').on('blur',function(e) {
    e.preventDefault();
    var tThis = $(this),
        val = tThis.val(),
        qstring = ''

    switch (tThis.attr('name')) {
        case 'sttv_coupon':
            if (!val) {
                tThis.removeClass('valid invalid')
                _st.checkout.update({
                    disc : 0,
                    discp : 0
                })
                return false
            }
            qstring = 'coupon='
            break
        case 'sttv_billing_pcode':
            if (!val) {
                _st.checkout.update({
                    tax : 0
                })
                return false
            }
            qstring = 'zip='
            break
        case 'sttv_email':
            if (!val) {
                return false
            }
            qstring = 'email='
            break
        default:
            return false
    }
    
    setTimeout( function() {
        _st.request({
            route : stajax.rest.url+'/checkout?'+qstring+val,
            headers : {
				'X-WP-Nonce' : stajax.rest.nonce,
			},
			success : function(d) {
				switch (d.code) {
                    case 'checkout_tax':
                        _st.checkout.update({
                            taxRate : d.tax,
                            msg : d.message
                        })
                        break
                }
			},
			error : function(x) {
				console.log(x)
			}
        })

        /* $.post(
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
        }); */
    },1);
    
});
 </script>