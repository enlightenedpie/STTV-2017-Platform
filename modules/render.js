import {data, settings} from './data.js'
import {defaultReq} from './loader.js'

var render = {
  stage : {
    iframe : function() {
      $('.sttv-embed-video>iframe').replaceWith(data.activeVid);
    },
    setActiveVid : function(id,title) {
      var html = '<iframe class="sttv-course-player" src="https://player.vimeo.com/video/'+id+'?title=0&byline=0&portrait=0&badge=0&autopause=0&player_id=0&autoplay='+settings.autoplay+'" width="1920" height="1080" frameborder="0" title="'+title+'" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""></iframe>';
      data.activeVid = html;
    },
    changeActiveVid : function(id,title) {
      this.setActiveVid(id,title);
      this.iframe();
    }
  },
  title : function(txt) {
    $('#course-after-title h2').css("color",settings.activeColor).html(txt);
  },
  content : function() {
    //$('.tabs a').css("color",settings.activeColor);
    //$('.tabs .indicator').css("background-color",settings.activeColor);
  },
  courseNav : function() {
    var obj = data.object;
    var nav = $('<ul/>',{
      "class": "collapsible",
      "data-collapsible": "accordion",
      id: "coursenav"
    });

    $.each(obj.sections,function(k,v){
      var active = (k === defaultReq.section) ? ' active' : '' ;
      var item = $('<li/>').append($('<div/>',{
        text: v.name,
        href : data.object.link + '/' + k,
        style: "color: "+v.color,
        "data-req" : JSON.stringify({section:k}),
        "class": "section-link collapsible-header"+active,
      })).append($('<div/>',{
        "class": "collapsible-body",
        html: '<span>'+v.description+'</span>'
      }).append($('<div/>',{
        "class" : "collapsible-footer"
      })));

      $.each(v.subsec,function(a,b){
        var sub = $('<a/>',{
          "class" : "cfooter-subsec-link",
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
      "data-req" : JSON.stringify({section:'practice'}),
    })).append($('<div/>',{
      "class": "collapsible-body",
      html: '<span>'+obj.practice.description+'</span>'
    }).append($('<div/>',{
      "class" : "collapsible-footer"
    })));

    $('.collapsible-footer',prac).append(
      $('<a/>',{
        "class": "cfooter-dl-link",
        "data-sec" : "practice",
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
      var obj = data.object

			if (!defaultReq.section) {
				return false;
			} else if (defaultReq.section === 'practice') {
				var sec = obj.practice.tests,
					sub = obj.practice.tests[defaultReq.subsec];

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
												href : obj.link+'/practice/'+k+'/'+key,
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
							var pracSec = sub.subsec[defaultReq.video];

							var h = $('<div/>',{
								"class" : "row course-subsection-container",
								"style" : "background-color:white"
							});
							h.append('<h3><p>'+pracSec.title+'</p></h3>');

							$.each(pracSec.videos,function(k,v){
									var y = {section:defaultReq.section,subsec:defaultReq.subsec,video:defaultReq.video,question:v.slug},
									dur = Math.floor(v.time / 60) + 'm '+ (v.time % 60) + 's';

								a = $('<a/>',{
									"class" : 'course-click',
                  id : v.slug,
									href : obj.link+'/'+y.section+'/'+y.subsec+'/'+y.video+'/'+v.slug,
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

				$.each(obj.sections[defaultReq.section].subsec, function(key, value){

					var h = $('<div/>',{
						"class" : "row course-subsection-container",
						"style" : "background-color:white"
					});
					h.append('<h3><p>'+key+'</p></h3>');
					if (!value.videos){
						h.append("<span>No videos found in this section</span>");
					} else {
						$.each(value.videos,function(k,v){
							var z = {section:defaultReq.section,subsec:key,video:v.slug},
								dur = Math.floor(v.time / 60) + 'm '+ (v.time % 60) + 's';
							a = $('<a/>',{
									"class" : 'course-click',
                  id : v.slug,
									href : obj.link+'/'+z.section+'/'+key+'/'+v.slug,
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
    render.stage.changeActiveVid(req.ID,req.title);
    var txt = '';
    var obj = data.object;
    var color = settings.activeColor
    if (defaultReq.section === 'practice') {
      txt = defaultReq.section+' &raquo; ' + obj.practice.tests[defaultReq.subsec].name + ' &raquo; ' + obj.practice.tests[defaultReq.subsec].subsec[defaultReq.video].title + ' &raquo; ' + req.name;
      color = obj.practice.color
    } else {
      txt = defaultReq.section+' &raquo; ' + defaultReq.subsec+' &raquo; ' + req.name;
      color = obj.sections[defaultReq.section].color
    }
    render.title(txt)
    this.highlightVid(req.slug, color)
  },
  highlightVid : function(slug, color) {
    $('.course-click .sidebar-sub-link').css({"color" : "","background-color" : ""}).removeClass('z-depth-1 course-active');
    $('#' + slug).children('.sidebar-sub-link',this).css(
      {
        color: "white",
        "background-color": color
      }
    ).addClass('z-depth-1 course-active');
    $('#course-after-title').css('color', color)
  }
}

export {render}
