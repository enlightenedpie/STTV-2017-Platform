import {student} from './student.js'

var log = {
  access : function() {
    _st.request({
      route : stajax.rest.url+'/course_log',
      method : 'POST',
      headers : {'X-WP-Nonce' : stajax.rest.nonce},
      cdata : {
        user : student.userName,
        UA : navigator.userAgent,
        uri : location.href
      },
      success : function(d){
        return this
      },
      error : function(x) {
        console.log(x)
        return this
      }
    })
  }
}

export {log, student}
