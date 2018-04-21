
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
					.append('<span>(This could take some time if you have a slow connection)</span>');
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
								}).append('<h2><i class="material-icons">done</i></h2><br/><small>'+d.message+'</small>')

								$('#modal_loading_overlay')
									.empty()
									.append(success)

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