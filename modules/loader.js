import {data, hash, version, settings, error404} from './data.js'
import {downloads} from './downloads.js'
import {log, student} from './log.js'
import {modal} from './modal.js'
import {render} from './render.js'
import {shutdown, preloader} from './shutdown.js'

var defaultReq = {}
var reqKeys = ['content', 'coursename', 'section', 'subsec', 'video', 'question', 'param']
var reqValues = location.pathname.split('/').filter(String)
while (reqKeys.length > 0 && reqValues.length > 0){
	defaultReq[reqKeys.shift()] = reqValues.shift()
}

var init = function(){
	render.title('')
	data.objectify(data.get());

  var ctrl = parseInt(localStorage.getItem('__c-update'));

  $(document).queue('heartbeat',()=>{
    console.log('first heartbeat')
  })
  log.access()

  if (student.alerts.dismissed() === null){
    localStorage.setItem('alertsDismissed',JSON.stringify([]));
  }
  if (JSON.parse(student.alerts.dismissed()).indexOf(hash) === -1) {
    modal.init({
      dismissible : false,
      complete : function(){
        var al = JSON.parse(student.alerts.dismissed())
        al.push(hash)
        localStorage.setItem('alertsDismissed',JSON.stringify(al))
        modal.destroy()
        modal.init()
      }
    },function() {
      $(document).queue('afterload',function(){
        $('.modal-loading-overlay').fadeIn(250);
        $('#course_modal').modal('open');
        modal.alert(function(d) {
          $('#course_modal .modal-content').append(d.html);
          $('.modal-loading-overlay').fadeOut(250);
        });
      })
    })
  } else {
    modal.init();
  }

  if (data.object === null || Date.now()/1000 - ctrl > 86400) { //86400
    data.reset(
      data.request()
    );
  } else if (data.object !== null && data.object['version'] !== version) {
		console.log('b')
    data.reset(window.location.reload())
  }

  function finish_init() {
    clearInterval(checker);

    if (typeof data.object == 'undefined' || typeof data.object.version === 'undefined' || data.object.version !== version) {
			console.log('c')
      data.reset(
        window.location.reload()
      );
    }

    console.log('Initialized!');

    setup.run();
  }
  var checker = setInterval(function(){
    if (data.get() == null) {
      console.log('localStorage not set');
      return;
    }

    finish_init();
  },100);
}

var setup = {
  validateRequest : function(request) {
    if (request.hasOwnProperty('type')) {
      return request;
    }
    var r = request;
    defaultReq = {
      section : r.section,
      subsec : r.subsec,
      video : r.video,
      question : r.question,
			param : r.param
    }
    var obj = data.object;
    var req;

    var p = r.param,
			q = r.question,
      v = r.video,
      b = r.subsec,
      s = r.section

    if (obj.sections[s] != null && obj.sections[s].restricted) {
      req = {type:'restricted'}
      return req;
    }
		try {
			if (s) {
				if (s === 'practice') {
					if (p) {
			        req = {type:'video',object:obj.practice.books[b].tests[v].sections[q].videos[p]};
			    } else if (q) {
			        req = {type:'section',object:obj.practice.books[b].tests[v].sections[q]};
			    } else if (b) {
			        req = {type:'section',object:obj.practice.books[b]};
			    } else {
						req = {type:'section',object:obj.practice.books};
					}
				} else if (s) {
					if (p || q) {
						req = {type:'error'}
					} else if (v) {
			        req = {type:'video',object:obj.sections[s].subsec[b].videos[v]};
			    } else if (b) {
			        req = {type:'section',object:obj.sections[s].subsec[b]};
			    } else {
			        if (typeof obj.sections[s] === 'undefined') {
			          req = {type:'video',object:obj.tl_content[s]};
			        } else {
			          req = {type:'section',object:obj.sections[s]};
			        }
						}
					}
				} else {
	      	req = {type:'root', object:{}}
		    }
	   } catch (e) {
			 req = {type:'error'}
		}
	if (typeof req.object === 'undefined'){
		error404()
		req = {type:'error'}
	}
	return req;
	},
  processRequest : function(req) {
		render.title('')
    var r = this.validateRequest(req);
    var obj = data.object;
    settings.activeColor = typeof obj.sections[defaultReq.section] !== 'undefined' ? obj.sections[defaultReq.section].color : 'SlateGray';
		switch (r.type) {
      case 'root':
        render.stage.changeActiveVid(obj.intro,'Intro');
        break;
      case 'section':
        render.stage.changeActiveVid(r.object.intro,'Intro');
        render.courseSidebar();
        break;
      case 'video':
        render.singleVid(r);
        break;
      case 'restricted':
        var sec = '#'+defaultReq.section+' .video-text';
        $(sec).text(data.object.sections[defaultReq.section].restricted);
        break;
      default:
			render.stage.changeActiveVid(obj.intro,'Intro');
        error404();
        return false;
    }

    return r;
  },
  newRequest : function(l) {
    return this.processRequest(JSON.parse(l));
  },
  run : function() {
    try {
			var req = this.validateRequest(defaultReq)
      render.courseNav(req);
      render.courseSidebar(req);
			this.processRequest(req);
    } catch (err) {
      console.log(err);
    }
    console.log('Setup complete');
    if (typeof student.firstName !== 'undefined') {
      $('div.user-bug > div > span').text('Hi '+student.firstName+'!')
    }
    setTimeout(function() {shutdown()},1000);
  },
}

export {data,
defaultReq,
downloads,
error404,
hash,
init,
log,
modal,
preloader,
render,
settings,
setup,
shutdown,
student,
version}
