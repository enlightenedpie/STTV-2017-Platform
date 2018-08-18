import config from './utilities/config'
import signup from './modules/signup'
import sub from './modules/subscribe'
import login from './modules/login'
import modal from './modules/modal'
import MU from './modules/mu'
import * as func from './core/functions/index'
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
		this.signup = signup
		this.subscribe = sub
		this.login = login
		this.modal = modal
		this.mu = MU
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