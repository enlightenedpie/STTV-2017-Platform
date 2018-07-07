const Modal = class {
  constructor() {
    this.action = '',
    this.element = $('#sttvmodal')
    this.inner = $('.sttvmodal_inner')
  }

  init( act ){
    if (typeof act === 'undefined'){
      return false
    }
    if (this.action === act) {
      return this.toggle()
    }

    var cb;
    if (act !== 'close') {
      this.action = act
      this.loader(function() {
        this.inner.empty()
      })
    }

    switch (act) {
      case 'close':
        break
      case 'login':
        cb = function(el) {
          _st.login(el)
        }
        break
      case 'account':
        cb = function(el) {

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
        cb = function(el) {
          _st.cart.submit(true,el)
        }
        break
    }
    this.toggle(cb)
  }

  toggle(cb) {
    $('body').toggleClass('modal-open')
    typeof cb === 'function' && cb(this.inner)
  }

  loader(cb) {
    this.element.toggleClass('loader-active')
    typeof cb === 'function' && cb(this.inner)
  }

}

export default Modal
