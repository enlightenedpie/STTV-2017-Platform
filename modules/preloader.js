var preloader = {
  html : '<div class="course-preloader"><div style="text-align:center"><img src="'+stajax.contentURL+'/i/sttv-spinner.gif" /><h3 style="text-transform:uppercase;font-weight:700">Loading</h3></div></div>',
  fade : function() {
    $('.course-preloader').fadeToggle(500);
  }
}

export {preloader}
