/* Let's define the checkout object with methods and properties */
_st.checkout = (function(element) {
	var cart = _st.cart.get(),
		total = tax = taxRate = taxable = shipping = disc = discp = shipping = 0

	return {
		type : _st.checkout,
		valid : false,
		items : cart,
		totals : {
			total : total,
			tax : {
				amt : tax,
				msg : '',
				rate : taxRate
			},
			taxable : taxable,
			shipping : shipping,
			disc : disc,
			discp : discp,
			shipping : shipping,
			msg : '',
			coupon : ''
		},
		state : {},
		pricer : function(price) {
			return (Math.round(price)/100).toFixed(2)
		},
		update : function(obj) {
			if (obj !== null) {
				$.extend(this.totals,obj)
			}

			if ( this.state === this.totals ) {
				console.log('state unchanged')
				return false
			}
			var items = this.items,
				keys = Object.keys(items),
				tot = this.totals

			element.fadeOut(100,function() {
				$(this).empty()

				for ( var i = 0; i < keys.length; i++ ) {
					if ( i === 0 ) {
						tot.total = tot.taxable = tot.tax.amt = 0
					}
				
					var item = items[keys[i]],
						price = item.price*item.qty

					if ( item.taxable !== false ) {
						tot.taxable += item.taxableAmt
					}

					tot.total += price
	
					$(this).append('<div class="row"><div class="col s2">'+item.qty+'</div><div class="col s8">'+item.name+'</div><div class="col s2 right-align">'+_st.checkout.pricer(price)+'</div></div>')
				}

				if ( 0 < tot.disc ) {
					var discprice = tot.disc;
				} else if ( 0 < tot.discp ) {
					var discprice = (tot.total*(tot.discp/100));
				}

				if (0 < tot.disc || 0 < tot.discp) {
					tot.total -= discprice
					$(this).append('<div class="row"><div class="col s2"></div><div class="col s8">Discount ('+tot.coupon+')</div><div class="col s2 right-align">-'+_st.checkout.pricer(discprice)+'</div></div>')
				}
				
				if ( tot.tax.rate > 0 ) {
					tot.tax.amt = (tot.taxable*tot.tax.rate)/100
					tot.total += tot.tax.amt
					$(this).append('<div class="row"><div class="col s2"></div><div class="col s8">'+tot.tax.msg+'</div><div class="col s2 right-align">+'+_st.checkout.pricer(tot.tax.amt)+'</div></div>')
				}

				if ( tot.shipping > 0 ) {
					tot.total += tot.shipping
					$(this).append('<div class="row"><div class="col s2"></div><div class="col s8">Priority Shipping</div><div class="col s2 right-align">+'+_st.checkout.pricer(tot.shipping)+'</div></div>')
				}
				
				$('#ttltxt>span').text(_st.checkout.pricer(tot.total))
			}).fadeIn(100);

			this.state = $.extend( true, {}, this.totals );
		},
		setOutcome : function( result, con ) {
			if ( typeof result.error !== 'undefined' ) {
				$( '.error', con ).text( result.error.message );
			} else {
				$( '.error', con ).text( '' );
			}

			var inputs = $( 'input, select', con )
			_st.checkout.validate( inputs, con, result.complete )
		},
		submit : function( data ) {
			_st.modal.loader(function(){
				var mo = $('#modal_loading_overlay')

				mo.find('*').not('img').remove()
				
				mo.append('<h2 style="margin-top:4em">Authorizing card...</h2>')
					.append('<span>(Patience, young padawan... This will take a moment.)</span>');
			})
			var det = {
				name: data.cardname,
				address_line1: data.billing_address1,
				address_line2: data.billing_address2,
				address_city: data.billing_city,
				address_state: data.billing_state,
				address_zip: data.billing_pcode,
				address_country: data.billing_country
			}

			stripe.createToken(card, det).then(function(result){
				if (result.error) {
					console.log(result.error)
					return _st.modal.loader(function(el){
						$('.error',el).text(result.error.message)
					})
				} else {
					$('#modal_loading_overlay h2').text('Processing order...')
					data.token = result.token
					data.cart = _st.cart.cartObj

					_st.request({
						route : stajax.rest.url+'/checkout',
						method : 'POST',
						cdata : data,
						headers : {
							'X-WP-Nonce' : stajax.rest.nonce,
						},
						success : function(d) {
							console.log(d)
							
							if ( 'error' === d.code ) {
								var ecode = d.error.decline_code || d.error.code
								_st.modal.loader(function(el){
									$('p.error',el).html('<span class="col s12">We\'re sorry. '+d.error.message+'</span><span class="col s12">err code: '+ecode+'</span>')
								})
							} else if ( 'success' === d.code ) {
								var success = $('<div/>',{
									id: 'success',
									'class': 'col s12'
								}).append('<h2><i class="material-icons">done</i></h2><br/><span>'+d.message+'</span>')

								$('#modal_loading_overlay')
									.empty()
									.append(success)

								var lines = d.order.invoice.data[0].lines.data,
									tax = shipping = '0',
									coupon = d.order.invoice.data[0].discount || ''

								for (var i = 0, len = lines.length; i < len; i++) {
									var line = lines[i]

									if ( line.description === 'Sales tax' ) {
										tax = (line.amount/100).toFixed(2)
									} else if ( line.description === 'Priority Shipping' ) {
										shipping = (line.amount/100).toFixed(2)
									}
								}

								_st.analytics({
									type : 'ec:setAction',
									action : 'purchase',
									data : {
										'id' : d.cart.ID,
										'revenue' : (d.order.invoice.data[0].amount_paid/100).toFixed(2),
										'tax' : tax,
										'shipping' : shipping,
										'coupon' : coupon,
										'affiliation' : 'SupertutorTV Online Store'
									},
									pageview : true,
									page : '/checkout'
								})

								_st.cart.unset()

								setTimeout(function(){
									window.location.href = d.order.redirect
								},3000)
							}
						},
						error : function(x) {
							var d = x[0].responseJSON
							console.log(x,d)
						}
					})
				}
			})
		},
		validate : function( inputs, context, extra ) {
			if ( typeof extra === 'undefined' ) {
				extra = true
			}
			
			var cEr = true

			inputs.each( function( k, v ) {
				var t = $(this),
					msgTag = ''

				if ( t.is(':required') && ( !t.val() || t.hasClass('invalid') ) ) {
					var msgTag = ( !t.val() ) ? ' is required' : ' is invalid'
					cEr = false
					$( '.error', context ).html( $(v).siblings('label').text()+msgTag );
				} else if ( t.hasClass('invalid') ) {
					cEr = false
					$( '.error', context ).html( $(v).siblings('label').text()+' is invalid' );
				}

				if ( !cEr ) { return cEr }
			});

			$( '.signup-submit', context ).prop( 'disabled', !( cEr && extra ) );

			return ( cEr && extra )
		}
	}
})($('.items-row'))

/* Now that the checkout object has been declared, let's run an init */

var stripe = Stripe(stajax.stripe.public_key);
var elements = stripe.elements();
var card = elements.create('card',{
    hidePostalCode: true
});
card.mount('#sttv_card_element');

card.on( 'change', function( event ) {
    _st.checkout.setOutcome( event, '#checkout-wrapper' );
});

$('#same_as_billing').on('change',function() {
    if ($(this).is(":checked")) {
        
        $('input[name=sttv_shipping_address1]').val($('input[name=sttv_billing_address1]').val());
        $('input[name=sttv_shipping_address2]').val($('input[name=sttv_billing_address2]').val());
        $('input[name=sttv_shipping_city]').val($('input[name=sttv_billing_city]').val());
        $('input[name=sttv_shipping_state]').val($('input[name=sttv_billing_state]').val());
        $('input[name=sttv_shipping_pcode]').val($('input[name=sttv_billing_pcode]').val());
        $('select[name=sttv_shipping_country]').val($('select[name=sttv_billing_country]').val());
        
        $('select').material_select()

    } else {
        $("#shipping_fields :input").each(function(){
            $(this).val('');
        });
        $("select[name=sttv_shipping_country]").prop("selectedIndex", -1);
    }

    Materialize.updateTextFields()
    $( 'input, select', '#shipping_fields' ).blur()
});

$('[name=shipping_options]').on({
    change : function(e) {
        e.preventDefault()
        _st.checkout.update({
            shipping : parseInt($(this).filter(':checked').val())
        })
    }
})

$('[name=sttv_billing_pcode]').on({
    focusin : function() {
        $(this).data('val',$(this).val())
    },
    change : function(e) {
        e.preventDefault()
        var val = $(this).val()
        if ( $(this).data('val') === val ) {
            return false
        } else if ( !val ) {
            return _st.checkout.update({
                tax : {
                    rate : 0
                }
            })
        }

        _st.request({
            route : stajax.rest.url+'/checkout?zip='+val,
            headers : {
				'X-WP-Nonce' : stajax.rest.nonce,
			},
			success : function(d) {
                _st.checkout.update({
                    tax : {
                        rate : d.tax,
                        msg : d.message
                    }
                })
			},
			error : function(x) {
				console.log(x)
			}
        })
    }
})

$('[name=sttv_email],input[name=sttv_coupon]').on({
    focusout : function(e) {
        if ( !$(this).val() ) {
            tThis.removeClass('valid invalid')
        }
    },
    change : function(e) {
        e.preventDefault();
        var tThis = $(this),
            val = tThis.val(),
            qstring = ''

        switch (tThis.attr('name')) {
            case 'sttv_coupon':
                if (!val) {
                    _st.checkout.update({
                        disc : 0,
                        discp : 0
                    })
                    return false
                }
                qstring = 'coupon='
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
        
        _st.request({
            route : stajax.rest.url+'/checkout?'+qstring+val,
            headers : {
                'X-WP-Nonce' : stajax.rest.nonce,
            },
            success : function(d) {
                tThis.removeClass('valid invalid')
                console.log(d)

                var msg = {},
                    cls = ''

                switch (d.code) {
                    case 'coupon_valid':
                    case 'email_available':
                        cls = 'valid'
                        msg = { 'data-success' : d.message }
                        break
                    case 'coupon_invalid':
                        cls = 'invalid'
                        msg = { 'data-error' : d.error.message }
                        break
                    case 'coupon_expired':
                    case 'email_taken':
                        cls = 'invalid'
                        msg = { 'data-error' : d.message }
                        break
                }

                tThis
                    .addClass( cls )
                    .siblings( 'label' )
                    .attr( msg )

                _st.checkout.update({
                    discp : d.percent_off,
                    disc : d.amount_off,
                    coupon : d.id
                })
            },
            error : function(x) {
                console.log(x)
            }
        })
    }
});

$('.signup-submit').on('click',function(e) {
    e.preventDefault();
    var inputs = $( 'input, select', '#checkout-wrapper' ),
        valid = _st.checkout.validate( inputs, '#checkout-wrapper' )
    if ( valid ) {
        _st.checkout.submit( _st.parseParams( inputs.serialize(), /sttv_/gi ) )
    }
})

/* Final setup of fields and prices */

Materialize.updateTextFields()
$('select').material_select();
_st.checkout.update()