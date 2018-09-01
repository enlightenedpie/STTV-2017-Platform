import Form from '../core/classes/form'
import cardSetup from './signup/cardSetup'
import copyAddress from './signup/copyAddress'
import next from './signup/next'
import pay from './signup/pay'
import plan from './signup/plan'
import prev from './signup/prev'
import pricer from './signup/pricer'
import render from './signup/render'
import renderPayment from './signup/renderPayment'
import report from './signup/report'
import setChecker from './signup/setChecker'
import setOutcome from './signup/setOutcome'
import setShipping from './signup/setShipping'
import step from './signup/step'
import submitBtn from './signup/submitBtn'
import update from './signup/update'
import validate from './signup/validate'

export default class Signup extends Form {
	constructor(){
		// set state
		super({
			el : document.getElementById('stSignupForm'),
			valid : false,
			card : {
				valid : false,
				obj : null
			},
			stripe : null,
			step : 0,
			submitted : {
				account : false,
				plan : false,
				shipping : false,
				billing : false,
				payment : false
			},
			id : '',
			signature : '',
			customer : {
				account : {
					email: '',
					firstname: '',
					lastname: '',
					password: ''
				},
				plan : {},
				shipping : {},
				billing : {},
				token: ''
			},
			pricing : {
				total : 0,
				shipping : 0,
				taxable : 0,
				tax : {
					id: '',
					value: 0
				},
				coupon : {
					id: '',
					value: ''
				}
			},
			html : [''],
			table : []
		})

		// initialize the signup process
		this.overlay()
		this.get('/signup/init', (data) => {
			this.state.id = Date.now()
			this.state.signature = btoa(navigator.userAgent+'|'+navigator.platform+'|'+navigator.product).replace(/=/g,'')
			this.render(data.html)
			this.step(() => {
				this.overlay()
			})
		})
	}

	// defined methods (from modules)
	cardSetup() {return cardSetup.call(this)}
	copyAddress(el) {return copyAddress.call(this,el)}
	next(action,cb) {return next.call(this,action,cb)}
	pay() {return pay.call(this)}
	plan(action) {return plan.call(this,action)}
	prev() {return prev.call(this)}
	pricer(price) {return pricer.call(this,price)}
	render(html) {return render.call(this,html)}
	renderPayment() {return renderPayment.call(this)}
	report() {return report.call(this)}
	setChecker(el) {return setChecker.call(this,el)}
	setOutcome(result,con) {return setOutcome.call(this,result,con)}
	setShipping(el) {return setShipping.call(this,el)}
	step(dir,cb) {return step.call(this,dir,cb)}
	submitBtn() {return submitBtn.call(this)}
	update(obj,action,cb) {return update.call(this,obj,action,cb)}
	validate(cb) {return validate.call(this,cb)}
}