<?php
if ( ! defined( 'ABSPATH' ) ) exit;

global $post;

/**
 * Let's check that our current user is logged in and has access capabilities. If not, we redirect to the sales page with a query variable to be used on the sales page for alerts.
**/
// !current_user_can(get_post_meta($post->ID,'course_primary_cap',true))
if (!is_user_logged_in() || !current_user_can(get_post_meta($post->ID,'course_primary_cap',true))) :
	wp_redirect( esc_url( add_query_arg( 'access', time(), get_permalink($cpp) ) ) );
	exit;
endif;

$cpp = get_post_meta($post->ID,'course_product_page',true);

$section = get_query_var('section');
$subsec = get_query_var('subsection');
$video = get_query_var('video');
$q = get_query_var('q');

$student = get_user_by('id',get_current_user_id());

/**
 * Let's output all the course JS; this function prints the code in the head
**/
function sttv_course_js_object() {
	global $section, $subsec, $video, $q, $post, $student, $cpp;
	
?><script>
	/* ©2017 Supertutor Media, Inc. This code is not for distribution or use on any other website or platform without express written consent from Supertutor Media, Inc., SupertutorTV, or a subsidiary */
var student = {
	id : <?php echo $student->ID; ?>,
	userName : '<?php echo $student->user_login; ?>',
	firstName : '<?php echo addslashes($student->first_name); ?>',
	lastName : '<?php echo addslashes($student->last_name); ?>',
	alerts : {
		dismissed : function() {return localStorage.getItem('alertsDismissed')}
	}
}

var courses = {
	version : '<?php echo STTV_VERSION; ?>',
	hash : '<?php echo sttvhashit($post->post_title.'/'.STTV_VERSION.'/'.$post->ID); ?>',
	salesPage : <?php echo $cpp; ?>,
	settings : {
		autoplay : 0,
		activeColor : $('body').css('color')
	},
	defaultReq : {
		section : <?php echo $section ? "'$section'" : "null"; ?>,
		subsec : <?php echo $subsec ? "'$subsec'" : "null"; ?>,
		video : <?php echo $video ? "'$video'" : "null"; ?>,
		question : <?php echo $q ? "'$q'" : "null"; ?>
	},
	data : {
		activeVid : '188703514',
		object : null,
		objectify : function(x){
			this.object = JSON.parse(x);
		},
		get : function() {return localStorage.getItem('course_data')},
		set : function(data) {return localStorage.setItem('course_data',JSON.stringify(data));},
		update : {
			get : function() {return localStorage.getItem('__c-update')},
			set : function() {return localStorage.setItem('__c-update',Math.floor(Date.now()/1000));}
		},
		request : function(cdata,method) {
			$.ajax({
				url: stajax.rest.url+'/course_data/'+stajax.rest.ID+'/',
				data: cdata || null,
				type: method || 'GET',
				headers: {'X-WP-Nonce' : stajax.rest.nonce},
				success: function(r) {
					courses.data.update.set();
					courses.data.set(r);
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
	},
	preloader : {
		html : '<div class="course-preloader"><div style="text-align:center"><img src="'+stajax.contentURL+'/i/sttv-spinner.gif" /><h3 style="text-transform:uppercase;font-weight:700">Loading</h3></div></div>',
		fade : function() {
			$('.course-preloader').fadeToggle(500);
		}
	},
	init : function(){
		var ctrl = parseInt(localStorage.getItem('__c-update'));
		var objd = courses.data.get();
		
		$(document).queue('heartbeat',()=>{
			console.log('first heartbeat')
		})
		courses.log.access()

		if (student.alerts.dismissed() === null){
			localStorage.setItem('alertsDismissed',JSON.stringify([]));
		}
		if (JSON.parse(student.alerts.dismissed()).indexOf(courses.hash) === -1) {
			courses.modal.init({
				dismissible : false,
				complete : function(){
					var al = JSON.parse(student.alerts.dismissed())
					al.push(courses.hash)
					localStorage.setItem('alertsDismissed',JSON.stringify(al))
					courses.modal.destroy()
					courses.modal.init()
				}
			},function() {
				$(document).queue('afterload',function(){
					$('.modal-loading-overlay').fadeIn(250);
					$('#course_modal').modal('open');
					courses.modal.alert(function(d) {
						$('#course_modal .modal-content').append(d.html);
						$('.modal-loading-overlay').fadeOut(250);
					});
				})
			})
		} else {
			courses.modal.init();
		}
	
		if (objd === null || Math.floor(Date.now()/1000) > ctrl+86400) { //86400
			courses.data.reset(
				courses.data.request()
			);
		} else if (objd !== null && objd['version'] !== courses.version) {
			courses.data.reset(window.location.reload())
		}
		
		function finish_init() {
			clearInterval(checker);
			courses.data.objectify(courses.data.get());

			if (typeof courses.data.object.version === 'undefined' || courses.data.object.version !== courses.version) {
				courses.data.reset(
					window.location.reload()
				);
			}
		
			console.log('Initialized!');
			
			courses.setup.run();
		}
		var checker = setInterval(function(){
			if (courses.data.get() == null) {
				console.log('localStorage not set');
				return;
			}
			
			finish_init();
		},100);
	
	},
	setup : {
		validateRequest : function(request) {
			if (typeof request === 'undefined') {
				var r = courses.defaultReq;
			} else if (request.hasOwnProperty('type')) {
				return request;
			} else {
				var r = request;
				courses.defaultReq = {
					section : r.section,
					subsec : r.subsec,
					video : r.video,
					question : r.question
				}
			}
			var obj = courses.data.object;
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
						//console.log('here');
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
			//console.log(req);
			return req;
		},
		processRequest : function(req) {
			var r = this.validateRequest(req);
			var obj = courses.data.object;
			courses.settings.activeColor = typeof obj.sections[courses.defaultReq.section] !== 'undefined' ? obj.sections[courses.defaultReq.section].color : 'SlateGray';

			switch (r.type) {
				case 'root':
					courses.render.stage.changeActiveVid(obj.intro,'Intro');
					break;
				case 'practice':
					courses.render.courseSidebar();
					break;
				case 'section':
					//console.log(r);
					courses.render.stage.changeActiveVid(r.object.intro,'Intro');
					courses.render.courseSidebar();
					break;
				case 'video':
					courses.render.singleVid(r);
					break;
				case 'restricted':
					var sec = '#'+courses.defaultReq.section+' .video-text';
					$(sec).text(courses.data.object.sections[courses.defaultReq.section].restricted);
					break;
				default:
					courses.error404();
					return false;
			}

			return r;
		},
		newRequest : function(l) {
			return this.processRequest(JSON.parse(l));
		},
		run : function() {
			try {
				this.processRequest();
				courses.render.courseNav();
				courses.render.courseSidebar();
			} catch (err) {
				console.log(err);
			}
			console.log('Setup complete');
			if (typeof student.firstName !== 'undefined') {
				$('div.user-bug > div > span').text('Hi '+student.firstName+'!')
			}
			setTimeout(function() {courses.shutdown()},1000);
		},
	},
	shutdown : function() {
		var hb = setInterval(()=>{
			if ($(document).queue('heartbeat').length >= 1){
				_st.heartBeat()
			}
		}
		,3000);
		this.preloader.fade()
		$(document).dequeue('shutdown')
		console.log('Shutdown complete')
		$(document).dequeue('afterload')
	},
	render : {
		stage : {
			iframe : function() {
				$('.sttv-embed-video>iframe').replaceWith(courses.data.activeVid);
			},
			setActiveVid : function(id,title) {
				var html = '<iframe class="sttv-course-player" src="https://player.vimeo.com/video/'+id+'?title=0&byline=0&portrait=0&badge=0&autopause=0&player_id=0&autoplay='+courses.settings.autoplay+'" width="1920" height="1080" frameborder="0" title="'+title+'" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe>';
				courses.data.activeVid = html;
			},
			changeActiveVid : function(id,title) {
				this.setActiveVid(id,title);
				this.iframe();
			}
		},
		title : function(txt) {
			$('#course-after-title h2').css("color",courses.settings.activeColor).html(txt);
		},
		content : function() {
			//$('.tabs a').css("color",courses.settings.activeColor);
			//$('.tabs .indicator').css("background-color",courses.settings.activeColor);
		},
		courseNav : function() {
			var obj = courses.data.object;
			var nav = $('<ul/>',{
				"class": "collapsible",
				"data-collapsible": "accordion",
				id: "coursenav"
			});
						
			$.each(obj.sections,function(k,v){
				var active = (k === courses.defaultReq.section) ? ' active' : '' ;
				var item = $('<li/>').append($('<div/>',{
					text: v.name,
					style: "color: "+v.color,
					"class": "section-link collapsible-header"+active,
					"data-req" : JSON.stringify({section:k})
				})).append($('<div/>',{
					"class": "collapsible-body",
					html: '<span>'+v.description+'</span>'
				}).append($('<div/>',{
					"class":"collapsible-footer"
				})));

				$.each(v.subsec,function(a,b){
					var sub = $('<a/>',{
						"class":"cfooter-subsec-link",
						text: b.title,
						href: "",
						style: "color:"+v.color
					}).prepend('<i class="material-icons">web</i>&nbsp;')
					$('.collapsible-footer',item).append(sub)
				});

				$('.collapsible-footer',item).append(
					$('<a/>',{
						"class": "cfooter-dl-link",
						"data-sec":k,
						href: "",
						text: "downloads",
						style: "color:"+v.color
					}).prepend('<i class="material-icons">cloud_download</i>&nbsp;')
				)

				item.appendTo(nav);
			});
			
			var prac = $('<li/>').append($('<a/>',{
				text: 'Practice Tests',
				href: '#practice',
				"class": "section-link practice-section-link collapsible-header",
				"data-req" : JSON.stringify({section:'practice'})
			})).append($('<div/>',{
				"class": "collapsible-body",
				html: '<span>'+obj.practice.description+'</span>'
			}).append($('<div/>',{
				"class":"collapsible-footer"
			})));

			$('.collapsible-footer',prac).append(
				$('<a/>',{
					"class": "cfooter-dl-link",
					"data-sec":"practice",
					href: "",
					text: "downloads",
					style: "color:gray"
				}).prepend('<i class="material-icons">cloud_download</i>&nbsp;')
			)
			
			prac.appendTo(nav);
			
			nav.appendTo($('#course-nav-container'));

			$(document).queue('shutdown',function(){
				$('.collapsible').collapsible();
			})
		},
		courseSidebar : function() {
			var wrap = $('<div/>',{
				"class" : "col s12 course-right-sidebar-inner"
			});
			var a;
			var div;
			
			if (!courses.defaultReq.section) {
				return false;
			} else if (courses.defaultReq.section === 'practice') {
				var sec = courses.data.object.practice.tests,
					sub = courses.data.object.practice.tests[courses.defaultReq.subsec];
					
					switch (sub) {
						case undefined:
							$.each(sec,function(k,v){
								var d = $('<div/>',{
									"class" : "row course-subsection-container",
									"style" : "background-color:white"
								}).append('<h3><p>'+v.name+'</p></h3>');
								
								switch (v.subsec) {
									case undefined:
										$('<div/>',{
											"class":"sidebar-sub-link row valign-wrapper",
											text: v.restricted
										}).appendTo(d);
										break;
									default:
										$.each(v.subsec,function(key,val){
											var aReq = {section:'practice',subsec:k,video:key};
											$('<a/>',{
												"class" : 'course-click',
												href : courses.data.object.link+'/practice/'+k+'/'+key,
												"data-req" : JSON.stringify(aReq),
												text : val.title,
												style : "display:block;padding:1em;margin-left:1em"
											}).append('').appendTo(d);
										});
										break;
								}
								
								d.appendTo(wrap);
							});
							break;
						default:
							var pracSec = sub.subsec[courses.defaultReq.video];
							
							var h = $('<div/>',{
								"class" : "row course-subsection-container",
								"style" : "background-color:white"
							});
							h.append('<h3><p>'+pracSec.title+'</p></h3>');
							
							$.each(pracSec.videos,function(k,v){
								var slug = v.slug,
									y = {section:courses.defaultReq.section,subsec:courses.defaultReq.subsec,video:courses.defaultReq.video,question:slug},
									dur = Math.floor(v.time / 60) + 'm '+ (v.time % 60) + 's';
								
								a = $('<a/>',{
									"class" : 'course-click',
									href : courses.data.object.link+'/'+y.section+'/'+y.subsec+'/'+y.video+'/'+slug,
									"data-req" : JSON.stringify(y)
								});
								div = $('<div/>',{
									"class":"sidebar-sub-link row valign-wrapper"
								});
								if (!v){
									div.text("No videos found in this section");
								} else {

									$('<div/>',{
										"class":"col s4",
										style: "padding:0px"
									}).append($('<img/>',{
										src : v.thumb,
										style : "width:100%;height:auto;display:block"
									})).appendTo(div);

									$('<div/>',{
										"class":"col s8"
									}).append($('<span/>',{
										"class" : 'course-video-title',
										text : v.name
									})).append($('<span/>',{
										"class":"course-video-duration",
										text : dur
									})).appendTo(div);

									div.appendTo(a);
									a.appendTo(h);
								}
							});
							
							h.appendTo(wrap);
							break;
					}
			} else {
				
				$.each(courses.data.object.sections[courses.defaultReq.section].subsec, function(key, value){
					
					var h = $('<div/>',{
						"class" : "row course-subsection-container",
						"style" : "background-color:white"
					});
					h.append('<h3><p>'+key+'</p></h3>');
					if (!value.videos){
						h.append("<span>No videos found in this section</span>");
					} else {
						$.each(value.videos,function(k,v){
							var z = {section:courses.defaultReq.section,subsec:key,video:v.slug},
								dur = Math.floor(v.time / 60) + 'm '+ (v.time % 60) + 's';
							a = $('<a/>',{
									"class" : 'course-click',
									href : courses.data.object.link+'/'+z.section+'/'+key+'/'+v.slug,
									"data-req" : JSON.stringify(z)
								});
							div = $('<div/>',{
								"class":"sidebar-sub-link row valign-wrapper"
							});
							if (!v){
								div.text("No videos found in this section");
							} else {

								$('<div/>',{
									"class":"col s4",
									style: "padding:0px"
								}).append($('<img/>',{
									src : v.thumb,
									style : "width:100%;height:auto;display:block"
								})).appendTo(div);
								
								$('<div/>',{
									"class":"col s8"
								}).append($('<span/>',{
									"class" : 'course-video-title',
									text : v.name
								})).append($('<span/>',{
									"class":"course-video-duration",
									text : dur
								})).appendTo(div);
								
								/*$('<div/>',{
									"class":"col s2 m1"
								}).append('<div class="valign-wrapper"><span>W</span></div>').appendTo(div);*/
								
							}
							div.appendTo(a);
							a.appendTo(h);
						});
					}
					h.appendTo(wrap);
				});
			}
			$('#courses-right-sidebar').empty().append(wrap);
		},
		singleVid : function(req) {
			courses.render.stage.changeActiveVid(req.object.ID,req.object.name);
			var txt = '';
			var obj = courses.data.object;
			if (courses.defaultReq.section === 'practice') {
				txt = courses.defaultReq.section+' &raquo; '+obj.practice.tests[courses.defaultReq.subsec].name+' &raquo; '+obj.practice.tests[courses.defaultReq.subsec].subsec[courses.defaultReq.video].title+' &raquo; '+req.object.name;
			} else {
				txt = courses.defaultReq.section+' &raquo; '+courses.defaultReq.subsec+' &raquo; '+req.object.name;
			}
			courses.render.title(txt);
		}
	},
	pushHist : function(obj,url,cb) {
		window.history.pushState(obj, document.title, url);
		typeof cb === 'function' && cb();
	},
	backHist : function(r) {
		courses.setup.newRequest(r);
	},
	error404 : function() {
		try {
			throw new Error('Video not found. Please use the course links to navigate to your desired content.');
		} catch (e) {
			courses.render.title(e);
		}
	},
	modal : {
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
	},
	ratings : {
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
				 	cdata : {'user_id':student.id,'post':courses.salesPage},
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
						'user_id':student.id,
						'post':courses.salesPage,
						'rating':courses.ratings.value,
						'UA':'STTV REST/<?php echo STTV_VERSION; ?>--browser: '+navigator.userAgent,
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
	},
	feedback : {
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
	},
	downloads : {
		container : '<div class="modal-downloads-container row"></div>',
		get : function(s,cb){
			var cont = $(this.container); 
			$('.modal-loading-overlay').fadeIn(250);
			$('#course_modal').modal('open');
			cont.append('<h1><span>'+s+'</span> Downloads</h1>')

			var obj = courses.data.object,
				res = (typeof obj.sections[s] === 'undefined') ? obj.practice.resources : obj.sections[s].resources;

			var inner = $('<div/>',{
				"class" : "dls-inner"
			});

			if (res.length === 0) {
				inner.append($('<div/>',{"class":"col s12",text:"No downloads found"}))
			} else {
				$.each(res,function(k,v){
					inner.append($('<a/>',{
						"class" : "dl-link col s6 m4",
						text : k,
						href : stajax.dlURL+"?res="+k+"&section="+s+"&test="+obj.test+"&checksum="+v
					}))
				})
			}
			inner.appendTo(cont)
			cont.appendTo($('.modal-content','#course_modal'))

			typeof cb === 'function' && cb();
		}
	},
	log : {
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
}; //end courses object

window.onpopstate = function(e){
	if (e.state == null) {window.location.reload();}
	courses.backHist(JSON.stringify(e.state));
};

var handlers = '.section-link, .course-rating, .course-feedback, .ratings-submit-button, .feedback-submit-button, .course-updater, .cfooter-dl-link, .cfooter-subsec-link, .alert-dismiss';

$(document).on('click',handlers,function(e){
	e.preventDefault();
	var t = $(this);
	var s = e.handleObj.selector.split(/,\s+/);
	var c = t.attr('class').split(/\s+/);

	var f = {
		'alert-dismiss' : function() {
			t.closest('#course_modal').modal('close')
		},
		'cfooter-dl-link' : function() {
			courses.downloads.get(t.attr('data-sec'),function(){
				$('.modal-loading-overlay').fadeOut(250)
			})
		},
		'cfooter-subsec-link' : function() {
			return false;
		},
		'course-rating' : function() {
			courses.ratings.run()
		},
		'course-feedback' : function() {
			courses.feedback.run()
		},
		'course-updater' : function() {
			if (confirm('Only do this if advised by a technician at SupertutorTV, as access to your course could be broken or interrupted. Are you sure you want to proceed?')){
				courses.data.reset(window.location.reload());
			}
		},
		'ratings-submit-button' : function() {
			if (!$('#review-content').val()) {
				$('#review-content')
					.focus()
					.attr('placeholder','You must enter a review')
				return false
			} else {
				courses.ratings.content = $('#review-content').val()
			}
			
			courses.ratings.submit(function(data){
				if (data.error){
					$('.modal-error').text(data.error);
				} else {
					$('#course_modal .modal-content').html(data.templateHtml);
				}
				$('.modal-loading-overlay').fadeToggle(250);
			});
		},
		'feedback-submit-button' : function() {
			if (t.hasClass('disabled')){return;}
			var content = $('#feedback-post-form>textarea').val();
			if (!content){
				$('#feedback-post-form>textarea')
					.focus()
					.attr('placeholder','You must enter some feedback before you submit')
				return false
			}

			$('.modal-loading-overlay').fadeToggle(250);
			_st.request(
				{
					method : 'POST',
				 	route : stajax.rest.url+'/feedback',
					cdata : {
						student : student.id,
						postID : courses.data.object.id,
						content : content
					},
					headers : {'X-WP-Nonce' : stajax.rest.nonce,'Content-Type':'application/json'},
				 	success : function(e){
						$('.modal-loading-overlay').fadeToggle(250);
						if (e) {
							$('#course_modal').modal('close')
							Materialize.toast('Feedback sent. Thanks!', 5000)
						} else {
							$('.modal-content').empty().append($('<pre/>',{
								text : 'Something went wrong. Please email techsupport@supertutortv.com with your issue.'
							}))
							$('.modal-loading-overlay').fadeToggle(250);
						}
					},
					error: function(x){
						console.log('error')
						$('.modal-content').empty().append($('<pre/>',{
							text : JSON.stringify(x[0]['responseJSON'])
						}))
						$('.modal-loading-overlay').fadeToggle(250);
					}
				}
			);
		},
		'section-link' : function() {
			var d = JSON.parse(t.attr('data-req'));
			var a = courses.setup.newRequest(t.attr('data-req'));
			var b = courses.data.object.link+'/'+d.section;
			courses.pushHist(a,b,function(){
				$('.indicator').css('background-color',t.css('color'));
			});
		}
	}
	c.some(function(v){typeof f[v] !== 'undefined' && f[v]()});
});

// Reads Vimeo Player
$(window).on('load',function(){

	var video = document.querySelector('iframe.sttv-course-player');
	var player = new Vimeo.Player(video);
	player.on('timeupdate',function(d){
		if (d.percent<0.5){
			return false;
		} else {
			console.log(d)
		}
	});
})

$(document).on('click','.course-click',function(e) {
		e.preventDefault();
		var t = this,
			o = $(t).attr('data-req'),
			g = $(t).attr('href'),
			a = courses.setup.newRequest(o)

		courses.pushHist(a,g);
		//console.log(e.isPropagationStopped());
		$('.course-click .sidebar-sub-link').css({"color":"","background-color":""}).removeClass('z-depth-1 course-active');
		$('.sidebar-sub-link',this).css(
			{
				color: "white",
				"background-color": courses.settings.activeColor
			}
		).addClass('z-depth-1 course-active');
	}
);
	
$(document).on({
	click : function(){
		var onStar = parseInt($(this).data('value'), 10); // The star currently selected
		var stars = $(this).parent().children('li.star');
		for (i = 0; i < stars.length; i++) {
		  $(stars[i]).removeClass('selected');
		}

		for (i = 0; i < onStar; i++) {
		  $(stars[i]).addClass('selected');
		}
		
		courses.ratings.value = onStar;
	},
	mouseover: function(){
		var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on
   
		// Now highlight all the stars that's not after the current hovered star
		$(this).parent().children('li.star').each(function(e){
		  if (e < onStar) {
			$(this).addClass('hover');
		  }
		  else {
			$(this).removeClass('hover');
		  }
		});
	},
	mouseout: function(){
		$(this).parent().children('li.star').each(function(e){
		  $(this).removeClass('hover');
		});
	}
},'#stars li');

$(document).ready(function(){
	courses.init();
	$('.sttv-vid-clicker').click(function(e){
		e.preventDefault();
		var id = $(this).attr('data-vid');
		courses.render.stage.setActiveVid(id);
		courses.render.stage.iframe();
		return false;
	});
});
</script>
<?php
	
}
add_action( 'wp_head', 'sttv_course_js_object', 11 );

/**
 * Let's set the stage, literally... Initializes our iframe in the stage area
**/
function courses_vid_setup() { ?>
<span class="sttv-embed-video">
	<iframe class="sttv-course-player" src="https://player.vimeo.com/video/188703514?title=0&byline=0&portrait=0&badge=0&autopause=0&player_id=0&autoplay=0" width="1920" height="1080" frameborder="0" title="" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe>
</span>
<?php }
add_action('sttv_stage_section','courses_vid_setup');
//add_action('sttv_stage_section','courses_apologies',999);
function courses_apologies() {
	print '<div class="azure-bg" style="width:100%;padding:1em;color:white;text-align:center">Thank you for your patience. We are still working out some bugs in the system. Apologies if the course acts wonky at any point.</div>';
}

add_action('sttv_after_body_tag','sttv_course_preloader');
function sttv_course_preloader() {
	print '<div class="course-preloader"><div style="text-align:center"><img src="'.get_stylesheet_directory_uri().'/i/sttv-spinner.gif"><h3 style="text-transform:uppercase;font-weight:700">Loading</h3></div></div>';
}

/**
 * Okay, let's do this thang! Start outputting the page.
**/
get_header();

get_template_part('templates/title'); ?>
<noscript>
	All SupertutorTV courses require Javascript to be enabled. Please enable Javascript in your browser to use this course properly.
	<style type="text/css">.course-contentarea, .course-preloader { display: none; } </style>
</noscript>
<div id="course-after-title"><h2>&nbsp;</h2></div>
<section class="course-contentarea course-<?php the_ID(); ?> row" id="content-wrapper-full">
<div id="course-content-hitbox-container" class="row">
	<div id="course-resource-bar" class="col s12 m3 z-depth-1">
		<div class="col s12 user-bug">
			<div class="col s12"><span>Hi there!</span></div>
		</div>
		<div class="resource-links">
			<div class="chevron"></div>
			<a href="#!" class="course-resource-link course-rating"><i class="material-icons">rate_review</i>Rate This Course</a>
			<a href="#!" class="course-resource-link course-feedback"><i class="material-icons">send</i>Leave Feedback</a>
			<a href="#!" class="course-resource-link course-updater"><i class="material-icons">refresh</i>Update Course</a>
			<a href="#!" class="course-resource-link course-version">Version <?php echo STTV_VERSION; ?></a>
		</div>
	</div>
	<div id="course-nav-container" class="col s12 m9"></div>
</div>
</section>
<a id="dwnld" style="display:block;height:1px;width:1px" title=""></a>
<?php get_footer(); ?>