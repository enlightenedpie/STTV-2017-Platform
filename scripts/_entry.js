import cart from './modules/cart'
import checkout from './modules/checkout/checkout'
import config from './modules/utilities/config'
import form from './modules/form'
import modal from './modules/modal'
import mu from './modules/mu'
import * as func from './modules/functions'
import './modules/events'

const STTV = class {
	constructor() {
		this.stripe = config[config.env].stripe
		this.resources = {
			api : 'https://api.supertutortv.com/v2/',
			app : 'https://courses.supertutortv.com',
			content : 'https://supertutortv.com/wp-content/themes/sttvsite/'
		}
		this.cart = cart,
		this.checkout = checkout,
		this.form = form,
		this.modal = modal,
		this.mu = mu

		STTV.init(this)
	}

	analytics(obj) {
		return func.analytics.call(this,obj)
	}

	dismiss(cb) {
		return func.dismiss.call(this,cb)
	}

	menuToggle(cb) {
		return func.menuToggle.call(this,cb)
	}

	parseParams(str,regex) {
		return func.parseParams.call(this,str,regex)
	}

	request(obj) {
		return func.request.call(this,obj)
	}

	scroll(a) {
		return func.scroll.call(this,a)
	}

	static init(t) {
		var keys = Object.keys(t)
		keys.forEach(function(k){
			if (typeof t[k] !== 'function') return
			t[k] = new t[k]
		})
	}
}

module.exports = new STTV