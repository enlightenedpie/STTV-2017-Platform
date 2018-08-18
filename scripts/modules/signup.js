import Form from '../core/classes/form'

export default class Signup extends Form {
	constructor(){
		super({
			el : null,
			valid : false,
			card : false,
			stripe : null,
			step : 0,
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
			customer : {
				account : {
					submitted: false,
					email: '',
					firstname: '',
					lastname: '',
					password: ''
				},
				plan : {
					submitted: false
				},
				shipping : {
					submitted: false
				},
				billing : {
					submitted: false
				},
				token: ''
			},
			html : [],
			table : []
		})

		this.state.el = document.getElementById('stSignupForm')

		this.overlay()
		this.get('/signup/init', (data) => {
			this.state.id = Date.now()
			this.state.signature = btoa(navigator.userAgent+'|'+navigator.platform+'|'+navigator.product).replace(/=/g,'')

			data.html.forEach((ele,i,a) => {
				if (ele.length === 0) {
					return this.state.html[i] = ele
				}

				var temp = document.createElement('template')
				temp.innerHTML = ele
				var blurs = temp.content.firstChild.querySelectorAll('input, select')
				blurs.forEach((el) => {
					el.addEventListener('blur', () => {
						this.setState([el])
					})
				})
    			return this.state.html[i] = temp.content.firstChild
			})
			this.step(() => {
				this.overlay()
			})
		})
	}

	step(dir = 'forward',cb) {
		cb = (typeof dir === 'function') ? dir : cb
		dir = (typeof dir === 'function') ? '' : dir

		this.state.html[this.state.step] = this.state.el.firstChild
		while (this.state.el.firstChild) this.state.el.removeChild(this.state.el.firstChild)
		if (dir === 'back')
			this.state.step--
		else
			this.state.step++

		this.state.el.appendChild(this.state.html[this.state.step])
		this.report()
		typeof cb === 'function' && cb()
	}

	report() {
		_st.analytics({
			type : 'ec:setAction',
			action : 'checkout',
			data : {
				'step' : this.state.step
			},
			pageview : true,
			page : '/checkout'
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

	next(action) {
		this.clearError()
		if (typeof action === 'undefined') return false

		this.overlay()

		action = action.replace('stBtn_','')
		
		if (action === 'void') return this.step(() => {
			this.overlay()
		})

		if (!this.state.customer[action].submitted)
			this.post('/signup/'+action,this.state.customer[action], (d) => {
				if (d.code === 'signup_error') return this.printError(d.message) && this.overlay()

				this.state.customer[action].submitted = true
				this.step(() => {
					this.overlay()
				})
			})
	}

	prev() {
		this.overlay()
		this.step('back',() => {
			this.overlay()
		})
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

						if ( 'stripe_error' === d.code ) {
							var ecode = d.error.decline_code || d.error.code
							_st.modal.loader(function(el){
								$('p.error',el).html('<span class="col s12">We\'re sorry. '+d.error.message+'</span><span class="col s12">err code: '+ecode+'</span>')
							})
							return false
						}
						
						var resp = d.response
						_st.analytics({
							type : 'ec:setAction',
							action : 'purchase',
							data : {
								'id' : resp.metadata.checkout_id,
								'revenue' : (resp.total/100).toFixed(2),
								'tax' : t.state.tax.val,
								'shipping' : t.state.shipping,
								'coupon' : t.state.coupon.id,
								'affiliation' : 'SupertutorTV Online Store'
							},
							pageview : true,
							page : location.pathname
						})

						setTimeout(function(){
							window.location.href = _st.resources.app
						},1000)
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