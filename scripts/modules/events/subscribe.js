export default function subscribe($) {
    $('#subscribe_page_mc').on('submit',function(e){
        e.preventDefault();
        var form = $(this)
    
        var loading = $('.loading_overlay',$(this).parent()).html('<img src="'+_st.resources.content+'/i/sttv-spinner.gif" alt="Loading..." />')
    
        loading.fadeIn(250)
    
        var fields = {
            fname : $('#sttv_mc_fname',form).val(),
            lname : $('#sttv_mc_lname',form).val(),
            email : $('#sttv_mc_email',form).val(),
            g_recaptcha_response : grecaptcha.getResponse()
        }
    
        _st.request({
            route : '/subscribe',
            method : 'POST',
            cdata : fields,
            success : function(d){
                $('input, button',form).prop('disabled',true)
                grecaptcha.reset()
                loading.empty().html('<p class="sblock"><strong><i class="material-icons">done</i></strong></p>').fadeIn(250)
                var s = $('.sblock')
                var p = $('<p/>',{"class":"smessage"})
                p.appendTo(s).append(d.message);
                $('.sblock').hide().fadeIn(250)
                console.log(d)
            },
            error : function(x){
                $('.message',form).html('Something went wrong. Please refresh the page and try again.')
                loading.fadeOut(250)
                console.log(x)
            }
        })
    })
}