const checkout = require('ck');

(function(c){
    if ( window.location.href.indexOf( 'checkout' ) !== -1 ){
        c.init()
    }
    $('.payment-launcher').on('click',function(e){
        e.preventDefault()
        var href = $(this).attr('href')
        c.dataBind(this,function(){
            window.location = href
        })
    });
}(checkout));