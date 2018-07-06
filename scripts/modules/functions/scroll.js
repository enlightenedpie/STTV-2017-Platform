const scroll = (a) => {
    $('html, body').stop().animate({
      scrollTop: $(a).offset().top-100
    },1250,"swing")
}
export default scroll