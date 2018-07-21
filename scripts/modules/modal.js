const Modal = class {
  constructor() {
    this.action = '',
    this.element = $('#st-modal-inner-content')
    this.inner = $('#st-modal-inner-content-inner')
  }

  init( act, extra ){
    var t = this
    if (typeof act === 'undefined') return false
    if (t.action === act) return t.toggle()

    var cb;
    if (act !== 'close') {
      t.action = act
      t.loader(() => {
        t.inner.empty()
        t.element.removeClass('st-quiet-load')
      })
    }

    switch (act) {
      case 'close':
      cb = function() {
        setTimeout(function(){
          $('#st-modal').hide()
        },250)
      }
        break
      case 'login':
        cb = function(el) {
          _st.login = new _st.login(el)
        }
        break
      case 'mu-checkout':
        cb = function(el) {
          _st.mu.submit(el,'#mu_form_wrapper')
        }
        break
      case 'mu-signup':
        cb = function(el) {
          _st.mu.register(el,'#mu_form_wrapper')
        }
        break
      case 'sttv-cart':
      case 'checkout':
        let ex = extra.dataset.course
        cb = function(el) {
          _st.checkout = new _st.checkout(ex)
          _st.checkout.init((x) => {
            x.render(el)
            t.loader()
          })
        }
        break
    }
    t.toggle(cb)
  }

  toggle(cb) {
    $('#st-modal').show()
    $('body').toggleClass('modal-open')
    typeof cb === 'function' && cb(this.inner)
  }

  loader(cb) {
    this.element.toggleClass('loader-active')
    typeof cb === 'function' && cb(this.inner)
  }

}

export default new Modal
