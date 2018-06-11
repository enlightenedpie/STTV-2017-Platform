var modal = {
  init : function(obj,cb) {
    var o = (typeof obj === 'object') ? obj : {};
    if ($('#course_modal').length == 0) {
      $('<div/>',{id:"course_modal","class":"modal"})
        .append($('<div/>',{"class":"modal-loading-overlay"}).html('<div style="text-align:center"><img src="'+stajax.contentURL+'/i/sttv-spinner.gif" /><h3 class="modal-message" style="text-transform:uppercase;font-weight:700"></h3></div>'))
        .append($('<div/>',{"class":"modal-content"}))
        .prependTo('body');
    }
    $('#course_modal').modal({
      dismissible : (typeof o.dismissible === 'boolean' && !o.dismissible)?false:true,
      opacity : o.opacity || .5,
      inDuration : o.in || 500,
      outDuration : o.out || 500,
      ready : o.ready || _st.fn,
      complete : o.complete || function(){
        $('.modal-content',this).empty();
      }
    });
    typeof cb === 'function' && cb();
  },
  destroy : function(cb) {
    $('#course_modal').remove();
    typeof cb === 'function' && cb();
  },
  alert : function(scb,ecb) {
    _st.request(
      {
        method : 'GET',
        route : stajax.rest.url+'/course_data/'+stajax.rest.ID+'?alert',
        headers : {'X-WP-Nonce' : stajax.rest.nonce},
        success : function(e){
          typeof scb === 'function' && scb(e);
        },
        error: function(z){
          console.log(z);
        }
      }
    );
  }
}

export {modal}
