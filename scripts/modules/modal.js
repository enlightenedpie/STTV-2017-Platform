var modal = (function() {
  $('.loading-spinner').each(function(i){
    $(this).attr('src',_st.resources.content+'/i/sttv-spinner.gif')
  })
  return {
    action : '',
    element : $('#sttvmodal'),
    inner : $('.sttvmodal_inner'),
    init : function( act ){
      if (typeof act === 'undefined'){
        return false
      }
      if (this.action === act) {
        return this.toggle()
      }

      var cb;
      if (act !== 'close') {
        this.action = act
        _st.modal.loader(function() {
          _st.modal.inner.empty()
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
    },
    toggle : function(cb) {
      $('body').toggleClass('modal-open')
      typeof cb === 'function' && cb(_st.modal.inner)
    },
    loader : function(cb) {
      _st.modal.element.toggleClass('loader-active')
      typeof cb === 'function' && cb(_st.modal.inner)
    }
  }
})()

export default modal
