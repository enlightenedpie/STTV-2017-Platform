var ratings = {
  value : 5,
  content : 'This course is fantastic! Thx Brooke!',
  run : function() {
    $('.modal-loading-overlay').fadeIn(250);
    $('#course_modal').modal('open');
    courses.ratings.verify(function(){
      $('.modal-loading-overlay').fadeOut(250)
    });
  },
  verify : function(cb) {
    _st.request(
      {
        method : 'POST',
        route : stajax.rest.url+'/reviews/',
        cdata : {'user_id':course.student.id,'post':courses.salesPage},
        headers : {'X-WP-Nonce' : stajax.rest.nonce,'Content-Type':'application/json'},
        success : function(e){
          $('#course_modal .modal-content').append(e.templateHtml);
          typeof cb === 'function' && cb();
        },
        error: function(z){
          console.log(z);
        }
      }
    );
  },
  submit : function(cb) {
    $('.modal-loading-overlay').fadeToggle(250);
    _st.request(
      {
        method : 'PUT',
        route : stajax.rest.url+'/reviews/',
        cdata : {
          'user_id':course.student.id,
          'post':courses.salesPage,
          'rating':courses.ratings.value,
          'UA':'STTV REST/' + '--browser: ' + navigator.userAgent,
          'comment_content':courses.ratings.content
        },
        headers : {'X-WP-Nonce' : stajax.rest.nonce,'Content-Type':'application/json'},
        success : function(e){
          typeof cb === 'function' && cb(e)
        },
        error: function(z){
          console.log(z);
        }
      }
    );
  }
}

export {ratings}
