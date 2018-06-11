import {init, setup, data, hash, version, settings,
	error404, student, log, modal, defaultReq, render,
	shutdown, preloader, downloads} from './modules/setup.js'

var courses = {
	version : version,
	hash : hash,
	salesPage : '',
	settings : settings,
	defaultReq : defaultReq,
	data : data,
	preloader : preloader,
	init : init,
	setup : setup,
	shutdown : shutdown,
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
	error404 : error404,
	modal : modal,
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
	downloads : downloads,
	log : log
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

export {courses}
