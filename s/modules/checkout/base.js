var checkout = require('ck');

(function(c){
    if ( window.location.href.indexOf( 'checkout' ) !== -1 ){
        c.init()
    }
    console.log(c);
    // "Sign Up Now" button action
    $('.payment-launcher').on('click',function(e){
        e.preventDefault()
        var href = $(this).attr('href')
        c.data('set',this,function(){
            window.location = href
        })
    });

    // test clicker
    /* $(document).on('click',function(e){
        e.preventDefault()
        console.log(c)
    }) */
}(checkout));