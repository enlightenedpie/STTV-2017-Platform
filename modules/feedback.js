var feedback = {
  run : function() {
    $('.modal-loading-overlay').fadeIn(250);
    $('#course_modal').modal('open');
    this.req('GET',null,function(e){
        $('#course_modal .modal-content').append(e.templateHtml)
        $('.modal-loading-overlay').fadeOut(250)
      }
    )
  },
  req : function(m,d,suc,err) {
    _st.request(
      {
        method : m,
        route : stajax.rest.url+'/feedback',
        cdata : d || {},
        headers : {'X-WP-Nonce' : stajax.rest.nonce,'Content-Type':'application/json'},
        success : function(e){
          typeof suc === 'function' && suc(e);
        },
        error: function(z){
          console.log(z);
          typeof err === 'function' && err(z);
        }
      }
    );
  }
}

export {feedback}
