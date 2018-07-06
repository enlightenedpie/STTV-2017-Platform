var parseParams = function(str,regex) {
  return (str || document.location.search).replace(/(^\?)/,'').replace(regex,'').split("&").map(function(n){return n = n.split("="),this[n[0]] = n[1],this}.bind({}))[0];
}

var menu = function(cb) {
  $('body').toggleClass('nav-sidebar-open')
  typeof cb === 'function' && cb();
}

var closer = function(cb) {
  jQuery('body').removeClass('nav-sidebar-open modal-open');
  typeof cb === 'function' && cb();
}

var login = function(el) {
  _st.request({
    route : stajax.rest.url+'/auth',
    headers : {
      //'X-WP-Nonce' : stajax.rest.nonce,
    },
    success : function(d) {
      el.append(d)
      _st.modal.loader()
    },
    error : function(x) {
      console.log(x)
    }
  })
}

var scroll = function(a) {
  $('html, body').stop().animate({
    scrollTop: $(a).offset().top-100
  },1250,"swing")
}

export {parseParams, menu, closer, login, scroll}
