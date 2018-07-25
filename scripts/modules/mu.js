const MU = class {
  constructor(el){
    Object.assign(this,{
      state : {
        mukey : '',
        email : '',
        firstname : '',
        lastname : '',
        password : ''
      }
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

  setState() {

  }
}

export default MU

/* submit : function(el,con) {
  var data = {
    mukey : $('input[name=mukey]',con).val(),
    email : $('input[name=email]',con).val(),
    license : {
      id : $('select[name=sttv_course_id]',con).val(),
      title : $('select[name=sttv_course_id] option:selected',con).text(),
      qty : $('select[name=qty]',con).val()
    }
  },
  type = 'multi-user'

  _st.request({
    route : '/multi-user',
    method : 'POST',
    cdata : data,
    success : function(d) {
      _st.checkout = type
      _st.cart.empty(function(t) {
        t.add(d.data,true)
      })
      el.append(d.html)
      _st.modal.loader()
      console.log(d)
    },
    error : function(x) {
      var d = x[0].responseJSON

      $('.message',con).text(d.message)
      _st.modal.toggle(function() {
        _st.modal.loader()
      })
      console.log(d)
    }
  })
},
register : function(el,con) {

  var data = {
    muid : $('input[name=mukey]',con).val(),
    email : $('input[name=sttv_email]',con).val(),
    password : $('input[name=sttv_password]',con).val(),
    firstName : $('input[name=sttv_firstname]',con).val(),
    lastName : $('input[name=sttv_lastname]',con).val()
  }

  _st.request({
    route : '/checkout',
    method : 'POST',
    cdata : data,
    success : function(d) {
      el.append(d.html)
      _st.modal.loader()
      setTimeout(function(){
        window.location.href = d.data.redirect
      },2000)
      console.log(d)
    },
    error : function(x) {
      console.log(x)
      var d = x[0].responseJSON

      $('.message',con).text(d.message)
      _st.modal.toggle(function() {
        _st.modal.loader()
      })
    }
  })
} */