import Form from '../core/classes/form'

export default class Login extends Form {
  constructor() {
    super({
      username: '',
      password: ''
    })
  }

  submit() {
    this.overlay()
    this.clearError()
    this.post('/auth/token',this.state,(d) => {
        if (d.code === 'login_fail')
            return this.printError(d.message) && this.overlay()
        else
            window.location.href = _st.resources.app
    })
  }
  
}