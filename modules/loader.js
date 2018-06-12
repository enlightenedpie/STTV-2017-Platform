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
  var objd = data.get();

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

  if (objd === null || Math.floor(Date.now()/1000) > ctrl+86400) { //86400
    data.reset(
      data.request()
    );
  } else if (objd !== null && objd['version'] !== version) {
    data.reset(window.location.reload())
  }

  function finish_init() {
    clearInterval(checker);
    data.objectify(data.get());

    if (typeof data.object.version === 'undefined' || data.object.version !== version) {
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
    } else {
      var r = request;
      defaultReq = {
        section : r.section,
        subsec : r.subsec,
        video : r.video,
        question : r.question
      }
    }
    var obj = data.object;
    var req;

    var q = r.question,
      v = r.video,
      b = r.subsec,
      s = r.section;

    if ((obj.sections[s] != null && obj.sections[s].restricted)) {
      req = {type:'restricted'}
      return req;
    }


    if (q) {
      try {
        req = {type:'video',object:obj.practice.tests[b].subsec[v].videos[q]};
      } catch (e) {
        console.log(e);
      }
    } else if (!q && v) {
      try {
        req = {type:'video',object:obj.sections[s].subsec[b].videos[v]};
      } catch (e) {
        req = {type:'section',object:obj.practice.tests[b].subsec[v]};
        console.log(e);
      }
    } else if (!v && b) {
      try {
        req = {type:'video',object:obj.sections[s].subsec[b]};
      } catch (e) {
        req = {type:'section',object:obj.practice.tests[b].subsec};
        console.log(e);
      }
    } else if (!b && s) {
      try {
        if (s === 'practice'){
          req = {type:'practice',object:obj.practice};
        } else if (typeof obj.sections[s] === 'undefined'){
          req = {type:'video',object:obj.tl_content[s]};
        } else {
          req = {type:'section',object:obj.sections[s]};
        }
      } catch (e) {
        console.log(e);
      }
    } else {
      req = {type:'root'}
    }
    return req;
  },
  processRequest : function(req) {
    var r = this.validateRequest(req);
    var obj = data.object;
    settings.activeColor = typeof obj.sections[defaultReq.section] !== 'undefined' ? obj.sections[defaultReq.section].color : 'SlateGray';

    switch (r.type) {
      case 'root':
        render.stage.changeActiveVid(obj.intro,'Intro');
        break;
      case 'practice':
        render.courseSidebar();
        break;
      case 'section':
        //console.log(r);
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
      this.processRequest(defaultReq);
      render.courseNav(defaultReq);
      render.courseSidebar(defaultReq);
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
