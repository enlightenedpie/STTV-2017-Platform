const Login = class {
  constructor(el) {
    Object.assign(this,{
      activePane: 1,
      redirected: null,
      creds: {
        username: '',
        password: ''
      }
    })
    _st.request({
      route: '/auth/form',
      success: (d) => {
        el.append(d.form)
        
        this.render(function(){
          _st.modal.loader()
        })
      }
    })
  }

  render(cb) {
    var pane = $('#pane-'+this.activePane),
      cPane = $('.st-login-pane.active')

    cPane.removeClass('active')
		setTimeout(function(){
      cPane.hide()
      pane.addClass('active').show()
    },250)
    typeof cb === 'function' && cb()
  }

  next() {
    this.activePane++
    this.render()
  }

  prev() {
    this.activePane--
    this.render()
  }

  reset() {
    console.log('reset')
  }
  setState(){

  }

  send() {
    var t = this
    t.validate((creds)=>{
      _st.request({
        route: '/auth/token',
        method: 'POST',
        cdata: creds,
        success : (d) => {
          console.log(d)
          var errFlag = document.querySelector('.error')
          if (d.code === 'login_fail') 
            errFlag.innerHTML = d.message
          else
            window.location.href = t.redirected || _st.resources.app
        },
        error : (err) => {
          console.log(err)
        }
      })
    })
  }
  
  validate(cb) {
    this.creds.username = $('[name="st-username"').val()
    this.creds.password = $('[name="st-password"').val()
    typeof cb === 'function' && cb(this.creds)
  }
}

export default Login
