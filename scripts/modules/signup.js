import Form from '../core/classes/form'
import cardSetup from './signup/cardSetup'
import copyAddress from './signup/copyAddress'
import disableSubmit from './signup/disableSubmit'
import enableSubmit from './signup/enableSubmit'
import next from './signup/next'
import pay from './signup/pay'
import plan from './signup/plan'
import prev from './signup/prev'
import pricer from './signup/pricer'
import render from './signup/render'
import renderItemsTable from './signup/renderItemsTable'
import report from './signup/report'
import setChecker from './signup/setChecker'
import setOutcome from './signup/setOutcome'
import setShipping from './signup/setShipping'
import step from './signup/step'
import update from './signup/update'
import validate from './signup/validate'

export default class Signup extends Form {
	constructor(){
		// set state
		super({
			el : document.getElementById('stSignupForm'),
			valid : false,
			card : false,
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
					val: 0
				},
				coupon : {
					id: '',
					val: ''
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
	cardSetup() {cardSetup.call(this)}
	copyAddress(el) {copyAddress.call(this,el)}
	disableSubmit() {disableSubmit.call(this)}
	enableSubmit() {enableSubmit.call(this)}
	next(action,cb) {next.call(this,action,cb)}
	pay() {pay.call(this)}
	plan(action) {plan.call(this,action)}
	prev() {prev.call(this)}
	pricer(price) {pricer.call(this,price)}
	render(html) {render.call(this,html)}
	renderItemsTable() {renderItemsTable.call(this)}
	report() {report.call(this)}
	setChecker(el) {setChecker.call(this,el)}
	setOutcome(result,con) {setOutcome.call(this,result,con)}
	setShipping() {setShipping.call(this,el)}
	step(dir,cb) {step.call(this,dir,cb)}
	update(obj,action,cb) {update.call(this,obj,action,cb)}
	validate(inputs,cb) {validate.call(this,inputs,cb)}
}