/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./single-courses.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./modules/data.js":
/*!*************************!*\
  !*** ./modules/data.js ***!
  \*************************/
/*! exports provided: data, error404, hash, preloader, settings, version */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"data\", function() { return data; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"error404\", function() { return error404; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"hash\", function() { return hash; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"preloader\", function() { return preloader; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"settings\", function() { return settings; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"version\", function() { return version; });\nvar reqKeys = ['section', 'subsec', 'video', 'question']\nvar reqValues = location.pathname.split('/').filter(String)\n\nvar version = '1.4'\nvar hash = ''\nvar settings = {\n\tautoplay : 0,\n\tactiveColor : $('body').css('color')\n}\nvar error404 = function() {\n\ttry {\n\t\tthrow new Error('Video not found. Please use the course links to navigate to your desired content.');\n\t} catch (e) {\n\t\tcourses.render.title(e);\n\t}\n}\n\n\nvar data = {\n  activeVid : '188703514',\n  object : null,\n  objectify : function(x){\n    this.object = JSON.parse(x);\n  },\n  get : function() {return localStorage.getItem('course_data')},\n  set : function(data) {return localStorage.setItem('course_data',JSON.stringify(data));},\n  update : {\n    get : function() {return localStorage.getItem('__c-update')},\n    set : function() {return localStorage.setItem('__c-update',Math.floor(Date.now()/1000));}\n  },\n  request : function(cdata,method) {\n    $.ajax({\n      url: stajax.rest.url+'/course_data/'+stajax.rest.ID+'/',\n      data: cdata || null,\n      type: method || 'GET',\n      headers: {'X-WP-Nonce' : stajax.rest.nonce},\n      success: function(r) {\n        data.update.set();\n        data.set(r);\n       },\n      error: function(x,s,e) {\n        console.log(x,s,e);\n      }\n    })\n  },\n  reset : function(cb) {\n    localStorage.removeItem('course_data');\n    localStorage.removeItem('__c-update');\n    return typeof cb === 'function' && cb()\n  }\n}\n\nvar preloader = {\n  html : '<div class=\"course-preloader\"><div style=\"text-align:center\"><img src=\"'+stajax.contentURL+'/i/sttv-spinner.gif\" /><h3 style=\"text-transform:uppercase;font-weight:700\">Loading</h3></div></div>',\n  fade : function() {\n    $('.course-preloader').fadeToggle(500);\n  }\n}\n\n\n\n\n//# sourceURL=webpack:///./modules/data.js?");

/***/ }),

/***/ "./modules/downloads.js":
/*!******************************!*\
  !*** ./modules/downloads.js ***!
  \******************************/
/*! exports provided: downloads */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"downloads\", function() { return downloads; });\nvar downloads = {\n  container : '<div class=\"modal-downloads-container row\"></div>',\n  get : function(s,cb){\n    var cont = $(this.container);\n    $('.modal-loading-overlay').fadeIn(250);\n    $('#course_modal').modal('open');\n    cont.append('<h1><span>'+s+'</span> Downloads</h1>')\n\n    var obj = courses.data.object,\n      res = (typeof obj.sections[s] === 'undefined') ? obj.practice.resources : obj.sections[s].resources;\n\n    var inner = $('<div/>',{\n      \"class\" : \"dls-inner\"\n    });\n\n    if (res.length === 0) {\n      inner.append($('<div/>',{\"class\":\"col s12\",text:\"No downloads found\"}))\n    } else {\n      $.each(res,function(k,v){\n        inner.append($('<a/>',{\n          \"class\" : \"dl-link col s6 m4\",\n          text : k,\n          href : stajax.dlURL+\"?res=\"+k+\"&section=\"+s+\"&test=\"+obj.test+\"&checksum=\"+v\n        }))\n      })\n    }\n    inner.appendTo(cont)\n    cont.appendTo($('.modal-content','#course_modal'))\n\n    typeof cb === 'function' && cb();\n  }\n}\n\n\n\n\n//# sourceURL=webpack:///./modules/downloads.js?");

/***/ }),

/***/ "./modules/feedback.js":
/*!*****************************!*\
  !*** ./modules/feedback.js ***!
  \*****************************/
/*! exports provided: feedback */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"feedback\", function() { return feedback; });\nvar feedback = {\n  run : function() {\n    $('.modal-loading-overlay').fadeIn(250);\n    $('#course_modal').modal('open');\n    this.req('GET',null,function(e){\n        $('#course_modal .modal-content').append(e.templateHtml)\n        $('.modal-loading-overlay').fadeOut(250)\n      }\n    )\n  },\n  req : function(m,d,suc,err) {\n    _st.request(\n      {\n        method : m,\n        route : stajax.rest.url+'/feedback',\n        cdata : d || {},\n        headers : {'X-WP-Nonce' : stajax.rest.nonce,'Content-Type':'application/json'},\n        success : function(e){\n          typeof suc === 'function' && suc(e);\n        },\n        error: function(z){\n          console.log(z);\n          typeof err === 'function' && err(z);\n        }\n      }\n    );\n  }\n}\n\n\n\n\n//# sourceURL=webpack:///./modules/feedback.js?");

/***/ }),

/***/ "./modules/history.js":
/*!****************************!*\
  !*** ./modules/history.js ***!
  \****************************/
/*! exports provided: backHist, pushHist */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"backHist\", function() { return backHist; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"pushHist\", function() { return pushHist; });\nvar backHist = function(r) {\n\tcourses.setup.newRequest(r);\n}\n\nvar pushHist = function(obj,url,cb) {\n\t\twindow.history.pushState(obj, document.title, url);\n\t\ttypeof cb === 'function' && cb();\n}\n\n\n\n\n//# sourceURL=webpack:///./modules/history.js?");

/***/ }),

/***/ "./modules/loader.js":
/*!***************************!*\
  !*** ./modules/loader.js ***!
  \***************************/
/*! exports provided: data, defaultReq, downloads, error404, hash, init, log, modal, preloader, render, settings, setup, shutdown, student, version */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"defaultReq\", function() { return defaultReq; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"init\", function() { return init; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"setup\", function() { return setup; });\n/* harmony import */ var _data_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./data.js */ \"./modules/data.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"data\", function() { return _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"error404\", function() { return _data_js__WEBPACK_IMPORTED_MODULE_0__[\"error404\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"hash\", function() { return _data_js__WEBPACK_IMPORTED_MODULE_0__[\"hash\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"settings\", function() { return _data_js__WEBPACK_IMPORTED_MODULE_0__[\"settings\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"version\", function() { return _data_js__WEBPACK_IMPORTED_MODULE_0__[\"version\"]; });\n\n/* harmony import */ var _downloads_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./downloads.js */ \"./modules/downloads.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"downloads\", function() { return _downloads_js__WEBPACK_IMPORTED_MODULE_1__[\"downloads\"]; });\n\n/* harmony import */ var _log_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./log.js */ \"./modules/log.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"log\", function() { return _log_js__WEBPACK_IMPORTED_MODULE_2__[\"log\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"student\", function() { return _log_js__WEBPACK_IMPORTED_MODULE_2__[\"student\"]; });\n\n/* harmony import */ var _modal_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./modal.js */ \"./modules/modal.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"modal\", function() { return _modal_js__WEBPACK_IMPORTED_MODULE_3__[\"modal\"]; });\n\n/* harmony import */ var _render_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./render.js */ \"./modules/render.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _render_js__WEBPACK_IMPORTED_MODULE_4__[\"render\"]; });\n\n/* harmony import */ var _shutdown_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./shutdown.js */ \"./modules/shutdown.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"preloader\", function() { return _shutdown_js__WEBPACK_IMPORTED_MODULE_5__[\"preloader\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"shutdown\", function() { return _shutdown_js__WEBPACK_IMPORTED_MODULE_5__[\"shutdown\"]; });\n\n\n\n\n\n\n\n\nvar defaultReq = {}\nvar reqKeys = ['content', 'coursename', 'section', 'subsec', 'video', 'question']\nvar reqValues = location.pathname.split('/').filter(String)\nwhile (reqKeys.length > 0 && reqValues.length > 0){\n\tdefaultReq[reqKeys.shift()] = reqValues.shift()\n}\n\nvar init = function(){\n  var ctrl = parseInt(localStorage.getItem('__c-update'));\n  var objd = _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].get();\n\n  $(document).queue('heartbeat',()=>{\n    console.log('first heartbeat')\n  })\n  _log_js__WEBPACK_IMPORTED_MODULE_2__[\"log\"].access()\n\n  if (_log_js__WEBPACK_IMPORTED_MODULE_2__[\"student\"].alerts.dismissed() === null){\n    localStorage.setItem('alertsDismissed',JSON.stringify([]));\n  }\n  if (JSON.parse(_log_js__WEBPACK_IMPORTED_MODULE_2__[\"student\"].alerts.dismissed()).indexOf(_data_js__WEBPACK_IMPORTED_MODULE_0__[\"hash\"]) === -1) {\n    _modal_js__WEBPACK_IMPORTED_MODULE_3__[\"modal\"].init({\n      dismissible : false,\n      complete : function(){\n        var al = JSON.parse(_log_js__WEBPACK_IMPORTED_MODULE_2__[\"student\"].alerts.dismissed())\n        al.push(_data_js__WEBPACK_IMPORTED_MODULE_0__[\"hash\"])\n        localStorage.setItem('alertsDismissed',JSON.stringify(al))\n        _modal_js__WEBPACK_IMPORTED_MODULE_3__[\"modal\"].destroy()\n        _modal_js__WEBPACK_IMPORTED_MODULE_3__[\"modal\"].init()\n      }\n    },function() {\n      $(document).queue('afterload',function(){\n        $('.modal-loading-overlay').fadeIn(250);\n        $('#course_modal').modal('open');\n        _modal_js__WEBPACK_IMPORTED_MODULE_3__[\"modal\"].alert(function(d) {\n          $('#course_modal .modal-content').append(d.html);\n          $('.modal-loading-overlay').fadeOut(250);\n        });\n      })\n    })\n  } else {\n    _modal_js__WEBPACK_IMPORTED_MODULE_3__[\"modal\"].init();\n  }\n\n  if (objd === null || Math.floor(Date.now()/1000) > ctrl+86400) { //86400\n    _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].reset(\n      _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].request()\n    );\n  } else if (objd !== null && objd['version'] !== _data_js__WEBPACK_IMPORTED_MODULE_0__[\"version\"]) {\n    _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].reset(window.location.reload())\n  }\n\n  function finish_init() {\n    clearInterval(checker);\n    _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].objectify(_data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].get());\n\n    if (typeof _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].object.version === 'undefined' || _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].object.version !== _data_js__WEBPACK_IMPORTED_MODULE_0__[\"version\"]) {\n      _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].reset(\n        window.location.reload()\n      );\n    }\n\n    console.log('Initialized!');\n\n    setup.run();\n  }\n  var checker = setInterval(function(){\n    if (_data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].get() == null) {\n      console.log('localStorage not set');\n      return;\n    }\n\n    finish_init();\n  },100);\n}\n\nvar setup = {\n  validateRequest : function(request) {\n    if (request.hasOwnProperty('type')) {\n      return request;\n    } else {\n      var r = request;\n      defaultReq = {\n        section : r.section,\n        subsec : r.subsec,\n        video : r.video,\n        question : r.question\n      }\n    }\n    var obj = _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].object;\n    var req;\n\n    var q = r.question,\n      v = r.video,\n      b = r.subsec,\n      s = r.section;\n\n    if ((obj.sections[s] != null && obj.sections[s].restricted)) {\n      req = {type:'restricted'}\n      return req;\n    }\n\n\n    if (q) {\n      try {\n        req = {type:'video',object:obj.practice.tests[b].subsec[v].videos[q]};\n      } catch (e) {\n        console.log(e);\n      }\n    } else if (!q && v) {\n      try {\n        req = {type:'video',object:obj.sections[s].subsec[b].videos[v]};\n      } catch (e) {\n        req = {type:'section',object:obj.practice.tests[b].subsec[v]};\n        console.log(e);\n      }\n    } else if (!v && b) {\n      try {\n        req = {type:'video',object:obj.sections[s].subsec[b]};\n      } catch (e) {\n        req = {type:'section',object:obj.practice.tests[b].subsec};\n        console.log(e);\n      }\n    } else if (!b && s) {\n      try {\n        if (s === 'practice'){\n          req = {type:'practice',object:obj.practice};\n        } else if (typeof obj.sections[s] === 'undefined'){\n          req = {type:'video',object:obj.tl_content[s]};\n        } else {\n          req = {type:'section',object:obj.sections[s]};\n        }\n      } catch (e) {\n        console.log(e);\n      }\n    } else {\n      req = {type:'root'}\n    }\n    return req;\n  },\n  processRequest : function(req) {\n    var r = this.validateRequest(req);\n    var obj = _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].object;\n    _data_js__WEBPACK_IMPORTED_MODULE_0__[\"settings\"].activeColor = typeof obj.sections[defaultReq.section] !== 'undefined' ? obj.sections[defaultReq.section].color : 'SlateGray';\n\n    switch (r.type) {\n      case 'root':\n        _render_js__WEBPACK_IMPORTED_MODULE_4__[\"render\"].stage.changeActiveVid(obj.intro,'Intro');\n        break;\n      case 'practice':\n        _render_js__WEBPACK_IMPORTED_MODULE_4__[\"render\"].courseSidebar();\n        break;\n      case 'section':\n        //console.log(r);\n        _render_js__WEBPACK_IMPORTED_MODULE_4__[\"render\"].stage.changeActiveVid(r.object.intro,'Intro');\n        _render_js__WEBPACK_IMPORTED_MODULE_4__[\"render\"].courseSidebar();\n        break;\n      case 'video':\n        _render_js__WEBPACK_IMPORTED_MODULE_4__[\"render\"].singleVid(r);\n        break;\n      case 'restricted':\n        var sec = '#'+defaultReq.section+' .video-text';\n        $(sec).text(_data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].object.sections[defaultReq.section].restricted);\n        break;\n      default:\n        Object(_data_js__WEBPACK_IMPORTED_MODULE_0__[\"error404\"])();\n        return false;\n    }\n\n    return r;\n  },\n  newRequest : function(l) {\n    return this.processRequest(JSON.parse(l));\n  },\n  run : function() {\n    try {\n      this.processRequest(defaultReq);\n      _render_js__WEBPACK_IMPORTED_MODULE_4__[\"render\"].courseNav(defaultReq);\n      _render_js__WEBPACK_IMPORTED_MODULE_4__[\"render\"].courseSidebar(defaultReq);\n    } catch (err) {\n      console.log(err);\n    }\n    console.log('Setup complete');\n    if (typeof _log_js__WEBPACK_IMPORTED_MODULE_2__[\"student\"].firstName !== 'undefined') {\n      $('div.user-bug > div > span').text('Hi '+_log_js__WEBPACK_IMPORTED_MODULE_2__[\"student\"].firstName+'!')\n    }\n    setTimeout(function() {Object(_shutdown_js__WEBPACK_IMPORTED_MODULE_5__[\"shutdown\"])()},1000);\n  },\n}\n\n\n\n\n//# sourceURL=webpack:///./modules/loader.js?");

/***/ }),

/***/ "./modules/log.js":
/*!************************!*\
  !*** ./modules/log.js ***!
  \************************/
/*! exports provided: log, student */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"log\", function() { return log; });\n/* harmony import */ var _student_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./student.js */ \"./modules/student.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"student\", function() { return _student_js__WEBPACK_IMPORTED_MODULE_0__[\"student\"]; });\n\n\n\nvar log = {\n  access : function() {\n    _st.request({\n      route : stajax.rest.url+'/course_log',\n      method : 'POST',\n      headers : {'X-WP-Nonce' : stajax.rest.nonce},\n      cdata : {\n        user : _student_js__WEBPACK_IMPORTED_MODULE_0__[\"student\"].userName,\n        UA : navigator.userAgent,\n        uri : location.href\n      },\n      success : function(d){\n        return this\n      },\n      error : function(x) {\n        console.log(x)\n        return this\n      }\n    })\n  }\n}\n\n\n\n\n//# sourceURL=webpack:///./modules/log.js?");

/***/ }),

/***/ "./modules/modal.js":
/*!**************************!*\
  !*** ./modules/modal.js ***!
  \**************************/
/*! exports provided: modal */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"modal\", function() { return modal; });\nvar modal = {\n  init : function(obj,cb) {\n    var o = (typeof obj === 'object') ? obj : {};\n    if ($('#course_modal').length == 0) {\n      $('<div/>',{id:\"course_modal\",\"class\":\"modal\"})\n        .append($('<div/>',{\"class\":\"modal-loading-overlay\"}).html('<div style=\"text-align:center\"><img src=\"'+stajax.contentURL+'/i/sttv-spinner.gif\" /><h3 class=\"modal-message\" style=\"text-transform:uppercase;font-weight:700\"></h3></div>'))\n        .append($('<div/>',{\"class\":\"modal-content\"}))\n        .prependTo('body');\n    }\n    $('#course_modal').modal({\n      dismissible : (typeof o.dismissible === 'boolean' && !o.dismissible)?false:true,\n      opacity : o.opacity || .5,\n      inDuration : o.in || 500,\n      outDuration : o.out || 500,\n      ready : o.ready || _st.fn,\n      complete : o.complete || function(){\n        $('.modal-content',this).empty();\n      }\n    });\n    typeof cb === 'function' && cb();\n  },\n  destroy : function(cb) {\n    $('#course_modal').remove();\n    typeof cb === 'function' && cb();\n  },\n  alert : function(scb,ecb) {\n    _st.request(\n      {\n        method : 'GET',\n        route : stajax.rest.url+'/course_data/'+stajax.rest.ID+'?alert',\n        headers : {'X-WP-Nonce' : stajax.rest.nonce},\n        success : function(e){\n          typeof scb === 'function' && scb(e);\n        },\n        error: function(z){\n          console.log(z);\n        }\n      }\n    );\n  }\n}\n\n\n\n\n//# sourceURL=webpack:///./modules/modal.js?");

/***/ }),

/***/ "./modules/preloader.js":
/*!******************************!*\
  !*** ./modules/preloader.js ***!
  \******************************/
/*! exports provided: preloader */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"preloader\", function() { return preloader; });\nvar preloader = {\n  html : '<div class=\"course-preloader\"><div style=\"text-align:center\"><img src=\"'+stajax.contentURL+'/i/sttv-spinner.gif\" /><h3 style=\"text-transform:uppercase;font-weight:700\">Loading</h3></div></div>',\n  fade : function() {\n    $('.course-preloader').fadeToggle(500);\n  }\n}\n\n\n\n\n//# sourceURL=webpack:///./modules/preloader.js?");

/***/ }),

/***/ "./modules/ratings.js":
/*!****************************!*\
  !*** ./modules/ratings.js ***!
  \****************************/
/*! exports provided: ratings */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"ratings\", function() { return ratings; });\nvar ratings = {\n  value : 5,\n  content : 'This course is fantastic! Thx Brooke!',\n  run : function() {\n    $('.modal-loading-overlay').fadeIn(250);\n    $('#course_modal').modal('open');\n    courses.ratings.verify(function(){\n      $('.modal-loading-overlay').fadeOut(250)\n    });\n  },\n  verify : function(cb) {\n    _st.request(\n      {\n        method : 'POST',\n        route : stajax.rest.url+'/reviews/',\n        cdata : {'user_id':course.student.id,'post':courses.salesPage},\n        headers : {'X-WP-Nonce' : stajax.rest.nonce,'Content-Type':'application/json'},\n        success : function(e){\n          $('#course_modal .modal-content').append(e.templateHtml);\n          typeof cb === 'function' && cb();\n        },\n        error: function(z){\n          console.log(z);\n        }\n      }\n    );\n  },\n  submit : function(cb) {\n    $('.modal-loading-overlay').fadeToggle(250);\n    _st.request(\n      {\n        method : 'PUT',\n        route : stajax.rest.url+'/reviews/',\n        cdata : {\n          'user_id':course.student.id,\n          'post':courses.salesPage,\n          'rating':courses.ratings.value,\n          'UA':'STTV REST/' + '--browser: ' + navigator.userAgent,\n          'comment_content':courses.ratings.content\n        },\n        headers : {'X-WP-Nonce' : stajax.rest.nonce,'Content-Type':'application/json'},\n        success : function(e){\n          typeof cb === 'function' && cb(e)\n        },\n        error: function(z){\n          console.log(z);\n        }\n      }\n    );\n  }\n}\n\n\n\n\n//# sourceURL=webpack:///./modules/ratings.js?");

/***/ }),

/***/ "./modules/render.js":
/*!***************************!*\
  !*** ./modules/render.js ***!
  \***************************/
/*! exports provided: render */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony import */ var _data_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./data.js */ \"./modules/data.js\");\n/* harmony import */ var _loader_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./loader.js */ \"./modules/loader.js\");\n\n\n\nvar render = {\n  stage : {\n    iframe : function() {\n      $('.sttv-embed-video>iframe').replaceWith(_data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].activeVid);\n    },\n    setActiveVid : function(id,title) {\n      var html = '<iframe class=\"sttv-course-player\" src=\"https://player.vimeo.com/video/'+id+'?title=0&byline=0&portrait=0&badge=0&autopause=0&player_id=0&autoplay='+_data_js__WEBPACK_IMPORTED_MODULE_0__[\"settings\"].autoplay+'\" width=\"1920\" height=\"1080\" frameborder=\"0\" title=\"'+title+'\" webkitallowfullscreen=\"\" mozallowfullscreen=\"\" allowfullscreen=\"\"></iframe>';\n      _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].activeVid = html;\n    },\n    changeActiveVid : function(id,title) {\n      this.setActiveVid(id,title);\n      this.iframe();\n    }\n  },\n  title : function(txt) {\n    $('#course-after-title h2').css(\"color\",_data_js__WEBPACK_IMPORTED_MODULE_0__[\"settings\"].activeColor).html(txt);\n  },\n  content : function() {\n    //$('.tabs a').css(\"color\",settings.activeColor);\n    //$('.tabs .indicator').css(\"background-color\",settings.activeColor);\n  },\n  courseNav : function() {\n    var obj = _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].object;\n    var nav = $('<ul/>',{\n      \"class\": \"collapsible\",\n      \"data-collapsible\": \"accordion\",\n      id: \"coursenav\"\n    });\n\n    $.each(obj.sections,function(k,v){\n      var active = (k === _loader_js__WEBPACK_IMPORTED_MODULE_1__[\"defaultReq\"].section) ? ' active' : '' ;\n      var item = $('<li/>').append($('<div/>',{\n        text: v.name,\n        style: \"color: \"+v.color,\n        \"class\": \"section-link collapsible-header\"+active,\n        \"data-req\" : JSON.stringify({section:k})\n      })).append($('<div/>',{\n        \"class\": \"collapsible-body\",\n        html: '<span>'+v.description+'</span>'\n      }).append($('<div/>',{\n        \"class\":\"collapsible-footer\"\n      })));\n\n      $.each(v.subsec,function(a,b){\n        var sub = $('<a/>',{\n          \"class\":\"cfooter-subsec-link\",\n          text: b.title,\n          href: \"\",\n          style: \"color:\"+v.color\n        }).prepend('<i class=\"material-icons\">web</i>&nbsp;')\n        $('.collapsible-footer',item).append(sub)\n      });\n\n      $('.collapsible-footer',item).append(\n        $('<a/>',{\n          \"class\": \"cfooter-dl-link\",\n          \"data-sec\":k,\n          href: \"\",\n          text: \"downloads\",\n          style: \"color:\"+v.color\n        }).prepend('<i class=\"material-icons\">cloud_download</i>&nbsp;')\n      )\n\n      item.appendTo(nav);\n    });\n\n    var prac = $('<li/>').append($('<a/>',{\n      text: 'Practice Tests',\n      href: '#practice',\n      \"class\": \"section-link practice-section-link collapsible-header\",\n      \"data-req\" : JSON.stringify({section:'practice'})\n    })).append($('<div/>',{\n      \"class\": \"collapsible-body\",\n      html: '<span>'+obj.practice.description+'</span>'\n    }).append($('<div/>',{\n      \"class\":\"collapsible-footer\"\n    })));\n\n    $('.collapsible-footer',prac).append(\n      $('<a/>',{\n        \"class\": \"cfooter-dl-link\",\n        \"data-sec\":\"practice\",\n        href: \"\",\n        text: \"downloads\",\n        style: \"color:gray\"\n      }).prepend('<i class=\"material-icons\">cloud_download</i>&nbsp;')\n    )\n\n    prac.appendTo(nav);\n\n    nav.appendTo($('#course-nav-container'));\n\n    $(document).queue('shutdown',function(){\n      $('.collapsible').collapsible();\n    })\n  },\n  courseSidebar : function() {\n    var wrap = $('<div/>',{\n      \"class\" : \"col s12 course-right-sidebar-inner\"\n    });\n    var a;\n    var div;\n\n    if (!_loader_js__WEBPACK_IMPORTED_MODULE_1__[\"defaultReq\"].section) {\n      return false;\n    } else if (_loader_js__WEBPACK_IMPORTED_MODULE_1__[\"defaultReq\"].section === 'practice') {\n      var sec = _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].object.practice.tests,\n        sub = _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].object.practice.tests[_loader_js__WEBPACK_IMPORTED_MODULE_1__[\"defaultReq\"].subsec];\n\n        switch (sub) {\n          case undefined:\n            $.each(sec,function(k,v){\n              var d = $('<div/>',{\n                \"class\" : \"row course-subsection-container\",\n                \"style\" : \"background-color:white\"\n              }).append('<h3><p>'+v.name+'</p></h3>');\n\n              switch (v.subsec) {\n                case undefined:\n                  $('<div/>',{\n                    \"class\":\"sidebar-sub-link row valign-wrapper\",\n                    text: v.restricted\n                  }).appendTo(d);\n                  break;\n                default:\n                  $.each(v.subsec,function(key,val){\n                    var aReq = {section:'practice',subsec:k,video:key};\n                    $('<a/>',{\n                      \"class\" : 'course-click',\n                      href : _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].object.link+'/practice/'+k+'/'+key,\n                      \"data-req\" : JSON.stringify(aReq),\n                      text : val.title,\n                      style : \"display:block;padding:1em;margin-left:1em\"\n                    }).append('').appendTo(d);\n                  });\n                  break;\n              }\n\n              d.appendTo(wrap);\n            });\n            break;\n          default:\n            var pracSec = sub.subsec[_loader_js__WEBPACK_IMPORTED_MODULE_1__[\"defaultReq\"].video];\n\n            var h = $('<div/>',{\n              \"class\" : \"row course-subsection-container\",\n              \"style\" : \"background-color:white\"\n            });\n            h.append('<h3><p>'+pracSec.title+'</p></h3>');\n\n            $.each(pracSec.videos,function(k,v){\n              var slug = v.slug,\n                y = {section:_loader_js__WEBPACK_IMPORTED_MODULE_1__[\"defaultReq\"].section,subsec:_loader_js__WEBPACK_IMPORTED_MODULE_1__[\"defaultReq\"].subsec,video:_loader_js__WEBPACK_IMPORTED_MODULE_1__[\"defaultReq\"].video,question:slug},\n                dur = Math.floor(v.time / 60) + 'm '+ (v.time % 60) + 's';\n\n              a = $('<a/>',{\n                \"class\" : 'course-click',\n                href : _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].object.link+'/'+y.section+'/'+y.subsec+'/'+y.video+'/'+slug,\n                \"data-req\" : JSON.stringify(y)\n              });\n              div = $('<div/>',{\n                \"class\":\"sidebar-sub-link row valign-wrapper\"\n              });\n              if (!v){\n                div.text(\"No videos found in this section\");\n              } else {\n\n                $('<div/>',{\n                  \"class\":\"col s4\",\n                  style: \"padding:0px\"\n                }).append($('<img/>',{\n                  src : v.thumb,\n                  style : \"width:100%;height:auto;display:block\"\n                })).appendTo(div);\n\n                $('<div/>',{\n                  \"class\":\"col s8\"\n                }).append($('<span/>',{\n                  \"class\" : 'course-video-title',\n                  text : v.name\n                })).append($('<span/>',{\n                  \"class\":\"course-video-duration\",\n                  text : dur\n                })).appendTo(div);\n\n                div.appendTo(a);\n                a.appendTo(h);\n              }\n            });\n\n            h.appendTo(wrap);\n            break;\n        }\n    } else {\n\n      $.each(_data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].object.sections[_loader_js__WEBPACK_IMPORTED_MODULE_1__[\"defaultReq\"].section].subsec, function(key, value){\n\n        var h = $('<div/>',{\n          \"class\" : \"row course-subsection-container\",\n          \"style\" : \"background-color:white\"\n        });\n        h.append('<h3><p>'+key+'</p></h3>');\n        if (!value.videos){\n          h.append(\"<span>No videos found in this section</span>\");\n        } else {\n          $.each(value.videos,function(k,v){\n            var z = {section:_loader_js__WEBPACK_IMPORTED_MODULE_1__[\"defaultReq\"].section,subsec:key,video:v.slug},\n              dur = Math.floor(v.time / 60) + 'm '+ (v.time % 60) + 's';\n            a = $('<a/>',{\n                \"class\" : 'course-click',\n                href : _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].object.link+'/'+z.section+'/'+key+'/'+v.slug,\n                \"data-req\" : JSON.stringify(z)\n              });\n            div = $('<div/>',{\n              \"class\":\"sidebar-sub-link row valign-wrapper\"\n            });\n            if (!v){\n              div.text(\"No videos found in this section\");\n            } else {\n\n              $('<div/>',{\n                \"class\":\"col s4\",\n                style: \"padding:0px\"\n              }).append($('<img/>',{\n                src : v.thumb,\n                style : \"width:100%;height:auto;display:block\"\n              })).appendTo(div);\n\n              $('<div/>',{\n                \"class\":\"col s8\"\n              }).append($('<span/>',{\n                \"class\" : 'course-video-title',\n                text : v.name\n              })).append($('<span/>',{\n                \"class\":\"course-video-duration\",\n                text : dur\n              })).appendTo(div);\n\n              /*$('<div/>',{\n                \"class\":\"col s2 m1\"\n              }).append('<div class=\"valign-wrapper\"><span>W</span></div>').appendTo(div);*/\n\n            }\n            div.appendTo(a);\n            a.appendTo(h);\n          });\n        }\n        h.appendTo(wrap);\n      });\n    }\n    $('#courses-right-sidebar').empty().append(wrap);\n  },\n  singleVid : function(req) {\n    render.stage.changeActiveVid(req.object.ID,req.object.name);\n    var txt = '';\n    var obj = _data_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"].object;\n    if (_loader_js__WEBPACK_IMPORTED_MODULE_1__[\"defaultReq\"].section === 'practice') {\n      txt = _loader_js__WEBPACK_IMPORTED_MODULE_1__[\"defaultReq\"].section+' &raquo; '+obj.practice.tests[_loader_js__WEBPACK_IMPORTED_MODULE_1__[\"defaultReq\"].subsec].name+' &raquo; '+obj.practice.tests[_loader_js__WEBPACK_IMPORTED_MODULE_1__[\"defaultReq\"].subsec].subsec[_loader_js__WEBPACK_IMPORTED_MODULE_1__[\"defaultReq\"].video].title+' &raquo; '+req.object.name;\n    } else {\n      txt = _loader_js__WEBPACK_IMPORTED_MODULE_1__[\"defaultReq\"].section+' &raquo; '+_loader_js__WEBPACK_IMPORTED_MODULE_1__[\"defaultReq\"].subsec+' &raquo; '+req.object.name;\n    }\n    render.title(txt);\n  }\n}\n\n\n\n\n//# sourceURL=webpack:///./modules/render.js?");

/***/ }),

/***/ "./modules/shutdown.js":
/*!*****************************!*\
  !*** ./modules/shutdown.js ***!
  \*****************************/
/*! exports provided: shutdown, preloader */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"shutdown\", function() { return shutdown; });\n/* harmony import */ var _preloader_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./preloader.js */ \"./modules/preloader.js\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"preloader\", function() { return _preloader_js__WEBPACK_IMPORTED_MODULE_0__[\"preloader\"]; });\n\n\n\nvar shutdown = function(){\n  var hb = setInterval(()=>{\n    if ($(document).queue('heartbeat').length >= 1){\n      _st.heartBeat()\n    }\n  }\n  ,3000);\n  _preloader_js__WEBPACK_IMPORTED_MODULE_0__[\"preloader\"].fade()\n  $(document).dequeue('shutdown')\n  console.log('Shutdown complete')\n  $(document).dequeue('afterload')\n}\n\n\n\n\n//# sourceURL=webpack:///./modules/shutdown.js?");

/***/ }),

/***/ "./modules/student.js":
/*!****************************!*\
  !*** ./modules/student.js ***!
  \****************************/
/*! exports provided: student */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"student\", function() { return student; });\nvar student = {\n\tid : '',\n\tuserName : '',\n\tfirstName : '',\n\tlastName : '',\n\talerts : {\n\t\tdismissed : function() {return localStorage.getItem('alertsDismissed')}\n\t}\n}\n\n\n\n\n//# sourceURL=webpack:///./modules/student.js?");

/***/ }),

/***/ "./single-courses.js":
/*!***************************!*\
  !*** ./single-courses.js ***!
  \***************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _modules_loader_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modules/loader.js */ \"./modules/loader.js\");\n/* harmony import */ var _modules_feedback_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modules/feedback.js */ \"./modules/feedback.js\");\n/* harmony import */ var _modules_history_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./modules/history.js */ \"./modules/history.js\");\n/* harmony import */ var _modules_ratings__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./modules/ratings */ \"./modules/ratings.js\");\n\n\n\n\n\n\n\n\nvar courses = {\n\tdata : _modules_loader_js__WEBPACK_IMPORTED_MODULE_0__[\"data\"],\n\tdefaultReq : _modules_loader_js__WEBPACK_IMPORTED_MODULE_0__[\"defaultReq\"],\n\tdownloads : _modules_loader_js__WEBPACK_IMPORTED_MODULE_0__[\"downloads\"],\n\terror404 : _modules_loader_js__WEBPACK_IMPORTED_MODULE_0__[\"error404\"],\n\thash : _modules_loader_js__WEBPACK_IMPORTED_MODULE_0__[\"hash\"],\n\tinit : _modules_loader_js__WEBPACK_IMPORTED_MODULE_0__[\"init\"],\n\tlog : _modules_loader_js__WEBPACK_IMPORTED_MODULE_0__[\"log\"],\n\tmodal : _modules_loader_js__WEBPACK_IMPORTED_MODULE_0__[\"modal\"],\n\tpreloader : _modules_loader_js__WEBPACK_IMPORTED_MODULE_0__[\"preloader\"],\n\tsalesPage : '',\n\trender : _modules_loader_js__WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n\tsettings : _modules_loader_js__WEBPACK_IMPORTED_MODULE_0__[\"settings\"],\n\tsetup : _modules_loader_js__WEBPACK_IMPORTED_MODULE_0__[\"setup\"],\n\tshutdown : _modules_loader_js__WEBPACK_IMPORTED_MODULE_0__[\"shutdown\"],\n\tstudent: _modules_loader_js__WEBPACK_IMPORTED_MODULE_0__[\"student\"],\n\tversion : _modules_loader_js__WEBPACK_IMPORTED_MODULE_0__[\"version\"],\n\tfeedback : _modules_feedback_js__WEBPACK_IMPORTED_MODULE_1__[\"feedback\"],\n\tbackHist : _modules_history_js__WEBPACK_IMPORTED_MODULE_2__[\"backHist\"],\n\tpushHist : _modules_history_js__WEBPACK_IMPORTED_MODULE_2__[\"pushHist\"],\n\tratings : _modules_ratings__WEBPACK_IMPORTED_MODULE_3__[\"ratings\"]\n}; //end courses object\n\n\nwindow.onpopstate = function(e){\n\tif (e.state == null) {window.location.reload();}\n\tcourses.backHist(JSON.stringify(e.state));\n};\n\nvar handlers = '.section-link, .course-rating, .course-feedback, .ratings-submit-button, .feedback-submit-button, .course-updater, .cfooter-dl-link, .cfooter-subsec-link, .alert-dismiss';\n\n$(document).on('click',handlers,function(e){\n\te.preventDefault();\n\tvar t = $(this);\n\tvar s = e.handleObj.selector.split(/,\\s+/);\n\tvar c = t.attr('class').split(/\\s+/);\n\n\tvar f = {\n\t\t'alert-dismiss' : function() {\n\t\t\tt.closest('#course_modal').modal('close')\n\t\t},\n\t\t'cfooter-dl-link' : function() {\n\t\t\tcourses.downloads.get(t.attr('data-sec'),function(){\n\t\t\t\t$('.modal-loading-overlay').fadeOut(250)\n\t\t\t})\n\t\t},\n\t\t'cfooter-subsec-link' : function() {\n\t\t\treturn false;\n\t\t},\n\t\t'course-rating' : function() {\n\t\t\tcourses.ratings.run()\n\t\t},\n\t\t'course-feedback' : function() {\n\t\t\tcourses.feedback.run()\n\t\t},\n\t\t'course-updater' : function() {\n\t\t\tif (confirm('Only do this if advised by a technician at SupertutorTV, as access to your course could be broken or interrupted. Are you sure you want to proceed?')){\n\t\t\t\tcourses.data.reset(window.location.reload());\n\t\t\t}\n\t\t},\n\t\t'ratings-submit-button' : function() {\n\t\t\tif (!$('#review-content').val()) {\n\t\t\t\t$('#review-content')\n\t\t\t\t\t.focus()\n\t\t\t\t\t.attr('placeholder','You must enter a review')\n\t\t\t\treturn false\n\t\t\t} else {\n\t\t\t\tcourses.ratings.content = $('#review-content').val()\n\t\t\t}\n\n\t\t\tcourses.ratings.submit(function(data){\n\t\t\t\tif (data.error){\n\t\t\t\t\t$('.modal-error').text(data.error);\n\t\t\t\t} else {\n\t\t\t\t\t$('#course_modal .modal-content').html(data.templateHtml);\n\t\t\t\t}\n\t\t\t\t$('.modal-loading-overlay').fadeToggle(250);\n\t\t\t});\n\t\t},\n\t\t'feedback-submit-button' : function() {\n\t\t\tif (t.hasClass('disabled')){return;}\n\t\t\tvar content = $('#feedback-post-form>textarea').val();\n\t\t\tif (!content){\n\t\t\t\t$('#feedback-post-form>textarea')\n\t\t\t\t\t.focus()\n\t\t\t\t\t.attr('placeholder','You must enter some feedback before you submit')\n\t\t\t\treturn false\n\t\t\t}\n\n\t\t\t$('.modal-loading-overlay').fadeToggle(250);\n\t\t\t_st.request(\n\t\t\t\t{\n\t\t\t\t\tmethod : 'POST',\n\t\t\t\t \troute : stajax.rest.url+'/feedback',\n\t\t\t\t\tcdata : {\n\t\t\t\t\t\tstudent : course.student.id,\n\t\t\t\t\t\tpostID : courses.data.object.id,\n\t\t\t\t\t\tcontent : content\n\t\t\t\t\t},\n\t\t\t\t\theaders : {'X-WP-Nonce' : stajax.rest.nonce,'Content-Type':'application/json'},\n\t\t\t\t \tsuccess : function(e){\n\t\t\t\t\t\t$('.modal-loading-overlay').fadeToggle(250);\n\t\t\t\t\t\tif (e) {\n\t\t\t\t\t\t\t$('#course_modal').modal('close')\n\t\t\t\t\t\t\tMaterialize.toast('Feedback sent. Thanks!', 5000)\n\t\t\t\t\t\t} else {\n\t\t\t\t\t\t\t$('.modal-content').empty().append($('<pre/>',{\n\t\t\t\t\t\t\t\ttext : 'Something went wrong. Please email techsupport@supertutortv.com with your issue.'\n\t\t\t\t\t\t\t}))\n\t\t\t\t\t\t\t$('.modal-loading-overlay').fadeToggle(250);\n\t\t\t\t\t\t}\n\t\t\t\t\t},\n\t\t\t\t\terror: function(x){\n\t\t\t\t\t\tconsole.log('error')\n\t\t\t\t\t\t$('.modal-content').empty().append($('<pre/>',{\n\t\t\t\t\t\t\ttext : JSON.stringify(x[0]['responseJSON'])\n\t\t\t\t\t\t}))\n\t\t\t\t\t\t$('.modal-loading-overlay').fadeToggle(250);\n\t\t\t\t\t}\n\t\t\t\t}\n\t\t\t);\n\t\t},\n\t\t'section-link' : function() {\n\t\t\tvar d = JSON.parse(t.attr('data-req'));\n\t\t\tvar a = courses.setup.newRequest(t.attr('data-req'));\n\t\t\tvar b = courses.data.object.link+'/'+d.section;\n\t\t\tcourses.pushHist(a,b,function(){\n\t\t\t\t$('.indicator').css('background-color',t.css('color'));\n\t\t\t});\n\t\t}\n\t}\n\tc.some(function(v){typeof f[v] !== 'undefined' && f[v]()});\n});\n\n// Reads Vimeo Player\n$(window).on('load',function(){\n\n\tvar video = document.querySelector('iframe.sttv-course-player');\n\tvar player = new Vimeo.Player(video);\n\tplayer.on('timeupdate',function(d){\n\t\tif (d.percent<0.5){\n\t\t\treturn false;\n\t\t} else {\n\t\t\tconsole.log(d)\n\t\t}\n\t});\n})\n\n$(document).on('click','.course-click',function(e) {\n\t\te.preventDefault();\n\t\tvar t = this,\n\t\t\to = $(t).attr('data-req'),\n\t\t\tg = $(t).attr('href'),\n\t\t\ta = courses.setup.newRequest(o)\n\n\t\tcourses.pushHist(a,g);\n\t\t$('.course-click .sidebar-sub-link').css({\"color\":\"\",\"background-color\":\"\"}).removeClass('z-depth-1 course-active');\n\t\t$('.sidebar-sub-link',this).css(\n\t\t\t{\n\t\t\t\tcolor: \"white\",\n\t\t\t\t\"background-color\": courses.settings.activeColor\n\t\t\t}\n\t\t).addClass('z-depth-1 course-active');\n\t}\n);\n\n$(document).on({\n\tclick : function(){\n\t\tvar onStar = parseInt($(this).data('value'), 10); // The star currently selected\n\t\tvar stars = $(this).parent().children('li.star');\n\t\tfor (i = 0; i < stars.length; i++) {\n\t\t  $(stars[i]).removeClass('selected');\n\t\t}\n\n\t\tfor (i = 0; i < onStar; i++) {\n\t\t  $(stars[i]).addClass('selected');\n\t\t}\n\n\t\tcourses.ratings.value = onStar;\n\t},\n\tmouseover: function(){\n\t\tvar onStar = parseInt($(this).data('value'), 10); // The star currently mouse on\n\n\t\t// Now highlight all the stars that's not after the current hovered star\n\t\t$(this).parent().children('li.star').each(function(e){\n\t\t  if (e < onStar) {\n\t\t\t$(this).addClass('hover');\n\t\t  }\n\t\t  else {\n\t\t\t$(this).removeClass('hover');\n\t\t  }\n\t\t});\n\t},\n\tmouseout: function(){\n\t\t$(this).parent().children('li.star').each(function(e){\n\t\t  $(this).removeClass('hover');\n\t\t});\n\t}\n},'#stars li');\n\n$(document).ready(function(){\n\tcourses.init();\n\t$('.sttv-vid-clicker').click(function(e){\n\t\te.preventDefault();\n\t\tvar id = $(this).attr('data-vid');\n\t\tcourses.render.stage.setActiveVid(id);\n\t\tcourses.render.stage.iframe();\n\t\treturn false;\n\t});\n});\n\n\n//# sourceURL=webpack:///./single-courses.js?");

/***/ })

/******/ });