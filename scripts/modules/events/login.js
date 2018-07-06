export default function login($) {
    $(document).on('submit','form#sttv_login_form',function(e) {
        e.preventDefault();
        if (0 === $('#sttv_user').val().length){
            $('.message').html('Username is required')
            return;
        }
    
        var loader = '<img src="'+_st.resources.content+'/i/sttv-spinner.gif" alt="Loading..." />',
            ld = $('.loading_overlay');
        ld.append(loader).promise().done(function(){
            $(this).fadeIn(250);
        });
    
        _st.request({
            route : '/auth?action=login',
            method : 'POST',
            headers : {
                'X-STTV-Auth' : btoa(this.sttv_user.value+':'+this.sttv_pass.value)
            },
            success : function(data) {
                if ( data.code == 'login_success' ) {
                    ld.empty().html('<p class="sblock"><strong><i class="material-icons">done</i></strong></p>').fadeIn(250)
                    $('.sblock').hide().fadeIn(250)
                    setTimeout(function(){
                        window.location.href = data.redirect
                    },250);
                }
            },
            error : function(x) {
                var data = x[0].responseJSON,
                    msg = ( typeof data.errors.too_many_retries !== 'undefined') ? data.errors.too_many_retries[0] : data.message;
    
                $('.message').html(msg)
                ld.fadeOut(250)
                console.log(data)
            }
        })
    })
}