import Form from '../core/classes/form'

export default class MU extends Form {
  constructor() {
    super({
      mukey : '',
      email : '',
      firstname : '',
      lastname : '',
      password : ''
    })
  }

  submit() {
    this.send('/multiuser/signup',(d) => {
      if (d.code !== 'multiuser_signup_success')
        return this.printError(d.message) && this.overlay()
      else
        return window.location.replace(d.redirect)
    })
  }
}