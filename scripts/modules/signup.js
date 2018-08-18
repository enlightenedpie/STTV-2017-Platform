import Form from '../core/classes/form'
import account from './signup/account'
import cardSetup from './signup/cardSetup'
import copyAddress from './signup/copyAddress'
import disableSubmit from './signup/disableSubmit'
import enableSubmit from './signup/enableSubmit'
import next from './signup/next'
import prev from './signup/prev'
import pricer from './signup/pricer'
import renderItemsTable from './signup/renderItemsTable'
import report from './signup/report'
import setChecker from './signup/setChecker'
import setOutcome from './signup/setOutcome'
import setShipping from './signup/setShipping'
import step from './signup/step'
import submit from './signup/submit'
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
			id : '',
			signature : '',
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
			pricing : {
				items : [],
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
				}
			},
			html : [],
			table : []
		})

		// initialize the signup process
		this.overlay()
		this.get('/signup/init', (data) => {
			this.state.id = Date.now()
			this.state.signature = btoa(navigator.userAgent+'|'+navigator.platform+'|'+navigator.product).replace(/=/g,'')

			data.html.forEach((ele,i,a) => {
				if (ele.length === 0) return this.state.html[i] = ele

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

	account(action) {account.call(this,action)}
	cardSetup() {cardSetup.call(this)}
	copyAddress(el) {copyAddress.call(this,el)}
	disableSubmit() {disableSubmit.call(this)}
	enableSubmit() {enableSubmit.call(this)}
	next(action) {next.call(this,action)}
	prev() {prev.call(this)}
	pricer(price) {pricer.call(this,price)}
	renderItemsTable() {renderItemsTable.call(this)}
	report() {report.call(this)}
	setChecker(el) {setChecker.call(this,el)}
	setOutcome(result,con) {setOutcome.call(this,result,con)}
	setShipping() {setShipping.call(this,el)}
	step(dir,cb) {step.call(this,dir,cb)}
	submit() {submit.call(this)}
	update(action,obj) {update.call(this,action,obj)}
	validate(inputs,cb) {validate.call(this,inputs,cb)}
}