const MU = class {
  constructor(el){
    Object.assign(this,{
      state : {
        mukey : '',
        email : '',
        firstname : '',
        lastname : '',
        password : ''
      },
      valid : false
    })

    _st.request({
      route: '/multiuser/form',
      success: (d) => {
        el.append(d.form.replace(/(\{\{[\w]+\}\})/g,muWelcome))
        document.getElementById('pane-1').classList.add('active')
        this.render(function(){
          _st.modal.loader()
        })
      }
    })
  }

  render(cb) {
    typeof cb === 'function' && cb()
  }

  setState(el) {
    this.state[el.name.replace('st-','')] = el.value
  }

  submit() {
    for (var val in this.state) {
      if (this.state[val].length == 0) return val
    }
    _st.modal.loader()

    _st.request({
      route : '/multiuser/signup',
      method : 'POST',
      cdata : this.state,
      success : function(d) {
        if (d.code === 'multiuser_signup_success') return window.location.replace(d.redirect)

        _st.modal.loader(function(el){
          $('#st-modal-errors .error').text(d.message)
        })
      },
      error : function(x) {
        console.log(x)
      }
    })
  }
}

export default MU
