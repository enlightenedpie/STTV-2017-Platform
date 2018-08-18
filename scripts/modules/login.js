import Form from '../core/classes/form'

export default class Login extends Form {
  constructor() {
    super({
      username: '',
      password: ''
    })
  }

  submit() {
    this.post('/auth/token',(d) => {
        if (d.code === 'login_fail')
            return this.printError(d.message) && this.overlay()
        else
            window.location.href = _st.resources.app
    })
  }
  
}