<?php

global $post;

$cpp = get_post_meta($post->ID,'course_product_page',true);

/**
 * Let's check that our current user is logged in. If not, we redirect to the sales page with a query variable to be used on the sales page for alerts.
**/
if (!is_user_logged_in()) :
	wp_redirect( esc_url( add_query_arg( 'access', time(), get_permalink($cpp) ) ) );
	exit;
endif;

$section = get_query_var('section');
$subsec = get_query_var('subsection');
$video = get_query_var('video');
$q = get_query_var('q');

/**
 * Let's output all the course JS; this function prints the code in the head
**/
function sttv_course_js_object() {
	global $section, $subsec, $video, $q, $post;
	
?><script>
	/* ©2017 Supertutor Media, Inc. This code is not for distribution or use on any other website or platform without express written consent from Supertutor Media, Inc., SupertutorTV, or a subsidiary */
var courses = {
	version : '1.0.4',
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

		remove : localStorage.removeItem('course_data'),
		update : {
			get : function() {return localStorage.getItem('__c-update')},
			set : function() {return localStorage.setItem('__c-update',Math.floor(Date.now()/1000));},
			remove : localStorage.removeItem('__c-update')
		},
		request : function(method,cdata) {
			var method = method || 'GET';
			var cdata = cdata || null;

			$.ajax({
				url: stajax.rest.url,
				data: cdata,
				type: method,
				headers: {'X-WP-Nonce' : stajax.rest.nonce},
				success: function(r) {
					courses.data.update.set();
					courses.data.set(r);
				 }
			});
		},
		reset : function() {
			courses.data.remove;
			courses.data.update.remove;
		}
	},
	preloader : {
		html : '<div class="course-preloader"><div style="text-align:center"><img src="'+stajax.contentURL+'/i/sttv-spinner.gif" /><h3 style="text-transform:uppercase;font-weight:700">Loading</h3></div></div>',
		fade : function() {
			$('.course-preloader').fadeToggle(500);
		}
	},
	init : function(){
		courses.data.reset();
		$('body').prepend(this.preloader.html);
		
		var ctrl = courses.data.update.get();
	
		if (!courses.data.get() || !ctrl || Math.floor(Date.now()/1000) >= ctrl+3600) { //86400
			courses.data.request();
		}
		
		function finish_init() {
			clearInterval(checker);
			courses.data.objectify(courses.data.get());
		
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
					req = {type:'video',object:obj.practice[b].subsec[v].videos[q]};
					console.log(req);
				} catch (e) {
					console.log(e);
				}
			} else if (!q && v) {
				try {
					req = {type:'video',object:obj.sections[s].subsec[b].videos[v]};
				} catch (e) {
					req = {type:'section',object:obj.practice[b].subsec[v]};
					console.log(e);
				}
			} else if (!v && b) {
				try {
					req = {type:'video',object:obj.sections[s].subsec[b]};
				} catch (e) {
					req = {type:'section',object:obj.practice[b].subsec};
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
			//console.log(r);
			switch (r.type) {
				case 'root':
					courses.render.stage.changeActiveVid(obj.intro,'Intro');
					break;
				case 'practice':
					//courses.render.courseSidebar();
					break;
				case 'section':
					//courses.render.courseSidebar();
					courses.settings.activeColor = r.object.color;
					//console.log(r);
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
			
			//courses.settings.activeColor = obj.sections[].color;
			courses.render.courseSidebar();
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
				var tb = $('ul.tabs');
				tb.tabs({'onShow':function(x){
					
					var tThis = $(this);
					$('li a.active',tThis).html(x.html());
					//console.log(x);
				}});
				tb.tabs('select_tab',courses.defaultReq.section);
				//$('a[href="#'+courses.defaultReq.section+'"]').addClass('active');
			} catch (err) {
				console.log(err);
			}
			console.log('Setup complete');
			setTimeout(function() {courses.shutdown()},1000);
		},
	},
	shutdown : function() {
		this.preloader.fade();
		console.log('Shutdown complete')
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
				"class": "tabs",
				id: "coursenav"
			});
			var container = $('<div/>',{class:"row"});
			var inner = $('<div/>',{class:"col s12"});
			
			var c = $('<div/>',{"class":'tab-content-inner'}).append($('<div/>',{"class":'inner-col inner-col-left col s12 m3'})).append($('<div/>',{"class":'inner-col inner-col-mid col s12 m6'})).append($('<div/>',{"class":'inner-col inner-col-right col s12 m3'}));
			
			$.each(obj.sections,function(k,v){
				$('<li/>',{
					"class": "tab col s2"
				}).append($('<a/>',{
					href: '#'+k,
					text: v.name,
					style: "color: "+v.color,
					"class": "section-link",
					"data-req" : JSON.stringify({section:k})
				})).appendTo(nav);
				
				var tabbed = $('<div/>',{
					id: k,
					"class": "col s12 tab-content"
				}).append(c.clone());
				
				$('.inner-col-mid',tabbed).append('<h3 style="line-height:100%;margin-top:0px;text-transform:uppercase;font-weight:700">Text</h3>').append($('<span/>',{
					class: "video-text"
				}));
				
				var resc = $('<div/>');
				$.each(v.resources,function(i,l){
					$('<p/>').append($('<a/>',{"class":"resources-download",href:'/course-dl.php?res='+i+'&section='+k+'&test=act&checksum='+l,text:i,'download':true})).appendTo(resc);

				});
				$('.inner-col-right',tabbed).append($('<div/>',{
					class: "section-resources"
				}).append('<h3 style="line-height:100%;margin-top:0px;text-transform:uppercase;font-weight:700">Resources</h3><br/><span style="display:block;margin-bottom:1em"><i>Please stand by, more downloads available soon.</i></span>')
				.append(resc));
				
				tabbed.appendTo(container);
			});
			
			$('<li/>',{
				"class": "tab col s2",
			}).append($('<a/>',{
				text: 'Practice',
				href: '#practice',
				"class": "section-link practice-section-link",
				"data-req" : JSON.stringify({section:'practice'})
			})).appendTo(nav);
			$('<div/>',{
				id: 'practice',
				"class": "col s12 tab-content",
				text: 'practice'
			}).append(c.clone()).appendTo(container);
			
			nav.appendTo(inner);
			inner.prependTo(container);
			container.appendTo($('#course-nav-container'));
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
				var sec = courses.data.object.practice,
					sub = courses.data.object.practice[courses.defaultReq.subsec];
					//console.log(sub)
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
								var slug = v.slug;
								var y = {section:courses.defaultReq.section,subsec:courses.defaultReq.subsec,video:courses.defaultReq.video,question:slug};
								
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
										text : v.duration
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
							var z = {section:courses.defaultReq.section,subsec:key,video:v.slug};
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
									text : v.duration
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
			//console.log(req);
			courses.render.stage.changeActiveVid(req.object.ID,req.object.name);
			var txt = '';
			var obj = courses.data.object;
			if (courses.defaultReq.section === 'practice') {
				txt = courses.defaultReq.section+' &raquo; '+obj.practice[courses.defaultReq.subsec].name+' &raquo; '+obj.practice[courses.defaultReq.subsec].subsec[courses.defaultReq.video].title+' &raquo; '+req.object.name;
			} else {
				txt = courses.defaultReq.section+' &raquo; '+courses.defaultReq.subsec+' &raquo; '+req.object.name;
			}
			courses.render.title(txt);
			courses.render.content();
			var vidtxt = (req.object.text || '<i>Text temporarily unavailable for this video.</i>');
			var secRef = $('#'+courses.defaultReq.section);
			$('.video-text',secRef).empty().append(vidtxt);
		}
	},
	pushHist : function(obj,url) {
		window.history.pushState(obj, document.title, url);
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
	resourceDownload : function(res){
		var qstring = '?res='+res.attr('data-res')+'&section='+res.closest('.tab-content').attr('id')+'&test=act';
		
	}
}; //end courses object
	
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

window.onpopstate = function(e){
	if (e.state == null) {window.location.reload();}
	courses.backHist(JSON.stringify(e.state));
	//console.log(e);
};
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

/**
 * Okay, let's do this thang! Start outputting the page.
**/
get_header();

get_template_part('templates/title'); ?>
<noscript>
	All SupertutorTV courses require Javascript to be enabled. Please enable Javascript in your browser to use this course properly.
	<style type="text/css">.course-contentarea { display: none; } </style>
</noscript>
<div id="course-after-title"><h2>&nbsp;</h2></div>
<section class="course-contentarea course-<?php the_ID(); ?> row" id="content-wrapper-full">
	<div id="course-nav-container"></div>
	<div id="course-content-hitbox-container" class="row"></div>
</section>
<script>
	$(document).on('click','.course-click, .section-link',function(e){
		e.preventDefault();
		var a = courses.setup.newRequest($(this).attr('data-req'));
		var b = ($(this).hasClass('section-link')) ? courses.data.object.link+'/'+$(this).attr('href').split('#')[1] : $(this).attr('href');
		courses.pushHist(a,b);
		if ($(this).hasClass('section-link')) {
			$('.indicator').css('background-color',$(this).css('color'));
		}
		//if ($(this).hasClass('course-click')) {window.scroll(0,0);}
	});
	
	$(document).on('click','.resources-download',function(e){
		//e.preventDefault();
		//courses.resourceDownload($(this));
	});
</script>
<?php get_footer(); ?>