/* Let's define the checkout object with methods and properties */
const Checkout = class {
	constructor(id){
		Object.assign(this,{
			valid : false,
			card : false,
			stripe : null,
			elements : null,
			tempId : id,
			index : 0,
			state : {
				id : '',
      			signature : '',
				items : [],
				trial: 0,
				total : 0,
				shipping : 0,
				taxable : 0,
				tax : {
					id: '',
					val: ''
				},
				coupon : {
					id: '',
					val: ''
				},
				email: {
					id: '',
					val: ''
				},
				customer : {
					token: ''
				}
			},
			html : [],
			table : []
		})
	}

	copyAddress(el) {
		var billing = this.state.customer.billing,
			elems = document.querySelectorAll('input.shipping, select.shipping')
		elems.forEach(function(v){
			if (el.checked) {
				var classes = v.className.split(/\s+/)
				classes.some(function(a){
					return v.value = billing[a] || ''
				})
			} else {
				if (v.selectedIndex) {
					v.selectedIndex = 0
				} else {
					v.value = ''
				}
			}
			v.focus()
			v.blur()
		})
	}

	init(cb) {
		var t = this
		_st.request({
			route: '/checkout?pricing='+t.tempId, //C3500
			success : (data) => {
				data.data.html.forEach(function(v){
					t.html.push($(v))
				})
				t.state.items.push(data.data.pricing)
				t.state.trial = data.data.pricing.trial_period
				t.update()
				typeof cb === 'function' && cb(t)
				t.setIDSignature()
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

	pricer(price) {
		return (Math.round(price)/100).toFixed(2)
	}

	render(el) {
		var t = this
		if (t.index === 0) {
			var wrapper = $('<div id="st-checkout-wrapper" class="col s12"></div>')
			if (typeof el !== 'undefined') wrapper.appendTo(el)
			t.html.forEach(function(v){
				wrapper.append(v)
			})
			t.index++
		} else {
			var wrapper = $('#st-checkout-wrapper')
		}

		var currentPane = $('#pane-'+t.index)

		this.renderItemsTable()
		
		$('.st-checkout-pane').fadeOut(100,function(){
			$(this).removeClass('active').hide()
		})
		setTimeout(function(){
			currentPane.show()
			setTimeout(function(){
				currentPane.addClass('active')
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

	setChecker(el) {
		function clear(el,reset) {
			$('p.error').text('')
			el.classList.remove('valid', 'invalid')
			if (typeof reset !== 'undefined' && reset) t.state[p] = {'id':'','val':''}
			t.update().renderItemsTable()
			return true
		}
		var t = this,
			params = ['coupon','email','tax'],
			p = false
			params.some(function(v){
				if (el.classList.contains(v)) p = v
				return el.classList.contains(v)
			})
		
		if (el.value === '') return clear(el,true)

		if (el.value === t.state[p].val) return false

		_st.request({
			route : '/checkout?'+p+'='+el.value+'&sig='+t.state.signature,
			success : function(d) {
				clear(el)
				switch (d.code) {
					case 'coupon_valid':
					case 'email_available':
					case 'checkout_tax':
						el.classList.add('valid')
						break;
					case 'coupon_invalid':
					case 'coupon_expired':
					case 'email_taken':
						el.classList.add('invalid')
						$('p.error').text(d.message)
						break;
					default:
						return 'What\'re you even doing here?'
				}
				t.state[p] = {
					id: d.id,
					val: d.value
				}
				t.update().renderItemsTable()
			},
			error : function(x){
				console.log(x)
			}
		})
		return this
	}

	setIDSignature() {
		delete this['tempId']
		this.state.id = Date.now()
		this.state.signature = btoa(navigator.userAgent+'|'+navigator.platform+'|'+navigator.product).replace(/=/g,'')
		return this
	}

	setIndex(itm,ind,val) {
		var nInd = ind[0]
		if (typeof ind == 'string')
			return this.setIndex(itm,ind.split('-'), val)
		else if (ind.length==1 && typeof val !== 'undefined') {
			return itm[nInd] =  val
		} else if (ind.length==0)
			return itm;
		else
			if (typeof itm[nInd] === 'undefined') itm[nInd] = {}
			return this.setIndex(itm[nInd],ind.slice(1), val)
	}

	setOutcome( result, con = document ) {
		var t = this
		if ( typeof result.error !== 'undefined' ) {
			return $( '.error', con ).text( result.error.message );
		} else {
			$( '.error', con ).text( '' );
		}

		if ( !result.empty && result.complete ) {
			var inputs = $( 'input, select', con )
			_st.checkout.validate( inputs, function(inp) {
				t.update(inp.serializeArray())
				t.valid = true
				t.enableSubmit()
			})
		}
	}

	setState(arr) {
		var t = this
		if (typeof arr !== 'undefined'){
			for (let a = 0; a < arr.length; a++) {
				let v = arr[a].name.split('|')
				for (let i = 0; i < v.length; i++) {
					t.setIndex(t.state,v[i].replace('st-','').split('-'),arr[a].value)
				}
			}
		}
		return this
	}

	setShipping(el) {
		this.state.shipping = (el.checked) ? 705 : 0
		this.update()
		return this
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
			t.table.push('<div class="row"><div class="col s8">'+item.name+'</div><div class="col s4 right-align">'+t.pricer(item.price)+'</div></div>')
		}

		var disc = state.coupon.val.match(/\\$([0-9]+)/) || ['0','0'],
			discp = state.coupon.val.match(/([0-9]+)%/) || ['0','0'],
			discprice = state.total*(parseInt(discp[1])/100) || parseInt(disc[1])

		if (0 < discprice) {
			state.total -= discprice
			t.table.push('<div class="row"><div class="col s8">Discount ('+state.coupon.id+')</div><div class="col s4 right-align">-'+t.pricer(discprice)+'</div></div>')
		}

		if ( parseFloat(state.tax.val) > 0 ) {
			let taxxx = (state.taxable*parseFloat(state.tax.val))/100
			state.total += taxxx
			t.table.push('<div class="row"><div class="col s8">'+state.tax.id+'</div><div class="col s4 right-align">+'+t.pricer(taxxx)+'</div></div>')
		}

		if ( state.shipping > 0 ) {
			state.total += state.shipping
			t.table.push('<div class="row"><div class="col s8">Priority Shipping</div><div class="col s4 right-align">+'+t.pricer(state.shipping)+'</div></div>')
		}
		return this
	}

	setup() {
		this.stripe = Stripe(_st.stripe.publicKey)
		this.elements = this.stripe.elements()
		this.card = this.elements.create('card',{
			hidePostalCode: true
		})
		
		this.card.mount('#st-checkout-card-element')

		var t = this
		this.card.on( 'change', function( event ) {
			t.setOutcome( event, '#st-checkout-wrapper' )
		})
	}

	submit() {
		_st.modal.loader()
		var cus = this.state.customer,
			t = this

		t.stripe.createToken(t.card, cus.billing).then(function(result){
			if (result.error) {
				console.log(result.error)
				return _st.modal.loader(function(el){
					$('.error',el).text(result.error.message)
				})
			} else {
				cus.token = result.token.id
				cus.shipping.name = cus.firstname+' '+cus.lastname
				delete cus.shipping.copy

				_st.request({
					route : '/checkout',
					method : 'POST',
					cdata : t.state,
					success : function(d) {
						window.location.href = _st.resources.app
						return console.log(d)

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

	enableSubmit() {
		if (this.valid) {
			document.querySelector('.st-checkout-submit').removeAttribute( 'disabled' )
		}
	}

	disableSubmit() {
		document.querySelector('.st-checkout-submit').setAttribute( 'disabled', '' )
	}

	validate( inputs, cb ) {
		this.disableSubmit()
		var context = '#st-modal-inner',
			ctrl = false,
			inp = inputs.toArray(),
			msg = ' is invalid'
		inp.some( function( v, i ) {
			var t = $(v)
			if ( t.is(':required') && ( !t.val() || t.hasClass('invalid') ) ) {
				if (!t.val()) msg = t.attr('placeholder')+' is required'
				t.addClass('invalid')
				return ctrl = true
			} else if ( t.hasClass('invalid') ) {
				msg = t.attr('placeholder')+msg
				return ctrl = true
			}
			return false
		})
		
		if (ctrl) {
			$( 'p.error', context ).text( msg )
			return !ctrl
		}

		$( 'p.error', context ).text('')
		typeof cb === 'function' && cb(inputs)
	}
}

export default Checkout
