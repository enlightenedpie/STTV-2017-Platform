var downloads = {
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
}

export {downloads}
