import checkout from './modules/checkout/checkout'
import config from './modules/utilities/config'
import login from './modules/login'
import modal from './modules/modal'
import mu from './modules/mu'
import * as func from './modules/functions'
import './modules/events'

const STTV = class {
	constructor() {
		this.stripe = config[config.env].stripe
		this.root = 'http://localhost:8888/sttvroot'
		this.resources = {
			api : 'https://api.supertutortv.com/v2',
			app : 'https://courses.supertutortv.com',
			content : this.root+'/wp-content/themes/sttvsite'
		}
		this.checkout = checkout
		this.login = login
		this.modal = modal
		this.mu = mu
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
}

module.exports = new STTV