/* Let's define the checkout object with methods and properties */
const Checkout = class {
	constructor(){
		Object.assign(this,{
			valid : false,
			card : false,
			reset : null,
			index : 0,
			state : {
				items : [],
				total : 0,
				tax : 0,
				taxable : 0,
				shipping : 0,
				disc : 0,
				discp : 0,
				coupon : '',
				trial: 0,
				customer : {
					token: ''
				}
			},
			html : [],
			table : []
		})
	}

	init(cb) {
		var t = this
		_st.request({
			route: '/checkout?pricing=C3500',
			success : (data) => {
				console.log(data)
				t.html = data.data.html
				t.state.items.push(data.data.pricing)
				t.state.trial = data.data.pricing.trial_period
				t.update()
				typeof cb === 'function' && cb(t)
			},
			error : (err) => {
			  console.log(err)
			}
		  })
	}

	next() {
		if (this.index < 4) {
			var pane = $('#pane-'+this.index),
				inputs = $('input,select',pane),
				t = this
			return this.validate(inputs, function(inp) {
				t.update(inp.serializeArray())
				t.html[t.index] = pane
				t.index++
				t.render()
			})
		}
	}

	prev() {
		if (this.index > 1) {
			var pane = $('#pane-'+this.index),
				inputs = $('input,select',pane)
				this.update(inputs.serializeArray())
				this.html[this.index] = pane
				this.index--
				this.render()
		}
	}

	render(el) {
		var t = this
		if (t.index === 0) {
			/* t.html.forEach(function(l,i){
				if (typeof l !== 'string' || l === '') return
				
				t.html[i] = l.replace(/({{)(.*)(}})/g,(match,p1,p2,p3) => {
					return t.totals[p2]
				})

			}) */
			t.index++
		}
		
		if (typeof el !== 'undefined') {
			var wrapper = $('<div id="st-checkout-wrapper" class="col s12"></div>')
			wrapper.html(t.html[t.index])
			wrapper.appendTo(el)
		} else {
			var wrapper = $('#st-checkout-wrapper')
			wrapper.empty().append(t.html[t.index])
		}

		this.renderItemsTable()
		
		$('.st-checkout-pane').removeClass('active')
		setTimeout(function(){
			setTimeout(function(){
				$('#pane-'+t.index).addClass('active')
			},100)
		},100)
	}

	renderItemsTable() {
		var t = this
		$('.items-row').fadeOut(100,function() {
			var that = $(this)
			that.empty()

			t.table.forEach(function(row){
				that.append($(row))
			})

			$('#ttltxt>span').text(t.pricer(t.state.total))
		}).fadeIn(100)
	}

	setIndex(itm,ind,val) {
		if (typeof ind == 'string')
			return this.setIndex(itm,ind.split('-'), val);
		else if (ind.length==1 && val!==undefined)
			return itm[ind[0]] = val;
		else if (ind.length==0)
			return itm;
		else
			if (typeof itm[ind[0]] == 'undefined') itm[ind[0]] = {}
			return this.setIndex(itm[ind[0]],ind.slice(1), val);
	}

	setState(obj) {
		var t = this
		if (typeof obj !== 'undefined'){
			obj.forEach(function(val){
				val.name = val.name.replace('st-','')
				t.setIndex(t.state,val.name.split('-'),val.value)
			})
		}
		return this
	}

	setShipping() {
		this.state.shipping = 705
		this.update()
		return this
	}

	pricer(price) {
		return (Math.round(price)/100).toFixed(2)
	}

	update(obj) {
		if (typeof obj !== 'undefined') this.setState(obj)
		var state = this.state,
			t = this
		state.total = state.taxable = 0
		t.table = []

		for ( var i = 0; i < state.items.length; i++ ) {
			var item = state.items[i]
			state.total += item.price
			state.taxable += item.taxable_amt
			t.table.push('<div class="row"><div class="col s2">'+item.qty+'</div><div class="col s8">'+item.name+'</div><div class="col s2 right-align">'+t.pricer(item.price)+'</div></div>')
		}

			if ( 0 < state.disc ) {
				var discprice = state.disc;
			} else if ( 0 < state.discp ) {
				var discprice = (state.total*(state.discp/100));
			}

			if (0 < state.disc || 0 < state.discp) {
				state.total -= discprice
				t.table.push('<div class="row"><div class="col s2"></div><div class="col s8">Discount ('+state.coupon+')</div><div class="col s2 right-align">-'+t.pricer(discprice)+'</div></div>')
			}

			if ( state.tax > 0 ) {
				let taxxx = (state.taxable*state.tax)/100
				state.total += taxxx
				t.table.push('<div class="row"><div class="col s2"></div><div class="col s8">'+state.taxmsg+'</div><div class="col s2 right-align">+'+t.pricer(taxxx)+'</div></div>')
			}

			if ( state.shipping > 0 ) {
				state.total += state.shipping
				t.table.push('<div class="row"><div class="col s2"></div><div class="col s8">Priority Shipping</div><div class="col s2 right-align">+'+t.pricer(state.shipping)+'</div></div>')
			}
	}

	setOutcome( result, con ) {
		if ( typeof result.error !== 'undefined' ) {
			$( '.error', con ).text( result.error.message );
		} else {
			$( '.error', con ).text( '' );
		}

		var inputs = $( 'input, select', con )
		_st.checkout.validate( inputs, con, result.complete )
	}

	setup() {
		var stripe = Stripe(_st.stripe.publicKey)
		var elements = stripe.elements()
		var card = elements.create('card',{
			hidePostalCode: true
		});
		card.mount('#st-checkout-card-element');

		card.on( 'change', function( event ) {
			this.setOutcome( event, '#checkout-wrapper' )
		});
		this.card = true
		/* 
		this.update() */
	}

	submit( data ) {
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
					route : '/checkout',
					method : 'POST',
					cdata : data,
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
	}

	validate( inputs, cb ) {
		this.valid = false
		var context = '#st-modal-inner',
			that = this,
			inp = inputs.toArray(),
			msg = ' is invalid'
		inp.some( function( v, i ) {
			var t = $(v)
			if ( t.is(':required') && ( !t.val() || t.hasClass('invalid') ) ) {
				if (!t.val()) msg = t.attr('placeholder')+' is required'
				t.addClass('invalid')
				return true
			} else if ( t.hasClass('invalid') ) {
				msg = t.attr('placeholder')+msg
				return true
			}
			if ( i === inp.length-1) that.valid = true
			return false
		})
		
		if (!this.valid) {
			$( 'p.error', context ).text( msg )
			return this.valid
		}

		$( 'p.error', context ).text('')
		$( '.st-checkout-submit', context ).prop( 'disabled', !( this.valid ) )
		typeof cb === 'function' && cb(inputs)
	}
}

export default Checkout
