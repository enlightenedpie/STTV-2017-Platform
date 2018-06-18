import {data, hash, version, settings, error404} from './data.js'
import {downloads} from './downloads.js'
import {log, student} from './log.js'
import {modal} from './modal.js'
import {render} from './render.js'
import {shutdown, preloader} from './shutdown.js'

var defaultReq = {}
var reqKeys = ['content', 'coursename', 'section', 'subsec', 'video', 'question']
var reqValues = location.pathname.split('/').filter(String)
while (reqKeys.length > 0 && reqValues.length > 0){
	defaultReq[reqKeys.shift()] = reqValues.shift()
}

var init = function(){
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
    data.reset(window.location.reload())
  }

  function finish_init() {
		render.title('')
    clearInterval(checker);
		data.objectify(data.get())
    if (data.object == null || typeof data.object.version === 'undefined' || data.object.version !== version) {
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
  processRequest : function(request) {
		render.title('')
    if (request.hasOwnProperty('type')) {
      return request;
    }
    var r = request;
		for (var i in defaultReq) {
			defaultReq[i] = r[i]
		}
    var obj = data.object;
    var req;

		var q = r.question,
      v = r.video,
      b = r.subsec,
      s = r.section

    if (obj.sections[s] != null && obj.sections[s].restricted) {
			var sec = '#'+courses.defaultReq.section+' .video-text';
			$(sec).text(courses.data.object.sections[courses.defaultReq.section].restricted);
      return
    }
		try {
				if (s === 'practice') {
				 if (q && obj.practice.tests[b].subsec[v].videos[q]) {
					 	render.singleVid(obj.practice.tests[b].subsec[v].videos[q]);
			    } else if (v && obj.practice.tests[b].subsec[v]) {
						render.courseSidebar();
			    } else {
						render.courseSidebar();
					}
				} else if (s) {
					if (q) {
						error404()
						return
					} else if (v && obj.sections[s].subsec[b].videos[v]) {
							render.singleVid(obj.sections[s].subsec[b].videos[v]);
			    } else if (b && obj.sections[s].subsec[b]) {
							render.courseSidebar();
							render.stage.changeActiveVid(obj.sections[s].subsec[b],'Intro');
			    } else {
			        if (typeof obj.sections[s] === 'undefined') {
								render.singleVid(obj.sections[s]);
			        } else {
								render.stage.changeActiveVid(obj.sections[s].intro,'Intro');
								render.courseSidebar();
			        }
						}
				} else {
					render.stage.changeActiveVid(obj.intro,'Intro');
		    }
	   } catch (e) {
			 console.log(e)
			 error404()
			 return
		}
	return req;
	},
  newRequest : function(l) {
    return this.processRequest(JSON.parse(l));
  },
  run : function() {
    try {
      render.courseNav(defaultReq);
      render.courseSidebar(defaultReq);
			this.processRequest(defaultReq)
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
