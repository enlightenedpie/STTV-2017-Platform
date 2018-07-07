const scroll = (a) => {
  a = a || 'footer'
    $('html, body').stop().animate({
      scrollTop: $(a).offset().top-100
    },1250,"swing")
}
export default scroll