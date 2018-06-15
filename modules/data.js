import {render} from './render.js'

var reqKeys = ['section', 'subsec', 'video', 'question']
var reqValues = location.pathname.split('/').filter(String)

var version = '2.0.0'
var hash = ''
var settings = {
	autoplay : 0,
	activeColor : $('body').css('color')
}
var error404 = function() {
	try {
		throw new Error('Resource not found. Please use the course links to navigate to your desired content.');
	} catch (e) {
		render.title(e);
	}
}


var data = {
  activeVid : '188703514',
  object : null,
  objectify : function(x){
    this.object = JSON.parse(x);
		this.object.link.replace('api.supertutortv.com', 'localhost:8888/')
  },
  get : function() {return localStorage.getItem('course_data')},
  set : function(data) {return localStorage.setItem('course_data',JSON.stringify(data));},
  update : {
    get : function() {return localStorage.getItem('__c-update')},
    set : function() {return localStorage.setItem('__c-update',Math.floor(Date.now()/1000));}
  },
  request : function(cdata,method) {
    $.ajax({
      url: 'https://api.supertutortv.com/json/courses/8',
      data: cdata || null,
      type: method || 'GET',
      headers: {},
      success: function(r) {
        data.update.set();
        data.set(r);
       },
      error: function(x,s,e) {
        console.log(x,s,e);
      }
    })
  },
  reset : function(cb) {
    localStorage.removeItem('course_data');
    localStorage.removeItem('__c-update');
    return typeof cb === 'function' && cb()
  }
}

var preloader = {
  html : '<div class="course-preloader"><div style="text-align:center"><img src="'+stajax.contentURL+'/i/sttv-spinner.gif" /><h3 style="text-transform:uppercase;font-weight:700">Loading</h3></div></div>',
  fade : function() {
    $('.course-preloader').fadeToggle(500);
  }
}

export {
data,
error404,
hash,
preloader,
settings,
version
}