import {data, defaultReq, downloads, error404, hash,
	init, log, modal, preloader, render, settings, setup,
	shutdown, student, version} from './modules/loader.js'

import {feedback} from './modules/feedback.js'

import {backHist, pushHist} from './modules/history.js'

import {ratings} from './modules/ratings'

var courses = {
	data : data,
	defaultReq : defaultReq,
	downloads : downloads,
	error404 : error404,
	hash : hash,
	init : init,
	log : log,
	modal : modal,
	preloader : preloader,
	salesPage : '',
	render : render,
	settings : settings,
	setup : setup,
	shutdown : shutdown,
	student: student,
	version : version,
	feedback : feedback,
	backHist : backHist,
	pushHist : pushHist,
	ratings : ratings
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
						student : course.student.id,
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
