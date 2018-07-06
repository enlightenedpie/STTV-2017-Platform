import cart from './modules/cart'
import checkout from './modules/checkout/checkout'
import config from './modules/utilities/config'
import form from './modules/form'
import modal from './modules/modal'
import mu from './modules/mu'
import * as func from './modules/functions'
import './modules/events'

let sttv = {
	stripe : config[config.env].stripe,
	resources : {
		api : 'https://api.supertutortv.com/v2/',
		app : 'https://courses.supertutortv.com',
		content : 'https://supertutortv.com/wp-content/themes/sttvsite/'
	},
	cart : cart,
	checkout : checkout,
	form : form,
	modal : modal,
	mu : mu
}
sttv.prototype = {
	analytics : func.analytics,
	parseParams : func.parseParams,
	request : func.request,
	menuToggle : func.menuToggle,
	dismiss : func.dismiss,
	scroll : func.scroll
}

module.exports = sttv