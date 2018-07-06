export default function contact($) {
    $('form#sttv_contact').on('submit',function(e) {
        e.preventDefault();
        var loading = $('.loading_overlay',$(this).parent()).html('<img src="'+_st.resources.content+'/i/sttv-spinner.gif" alt="Loading..." />')
    
        loading.fadeIn(250)
    
        _st.request({
            route : '/contact',
            method : 'POST',
            cdata : {
                g_recaptcha_response : grecaptcha.getResponse(),
                sttv_contact_name: this.sttv_contact_name.value,
                sttv_contact_email: this.sttv_contact_email.value,
                sttv_contact_subject: this.sttv_contact_subject.value,
                sttv_contact_message: this.sttv_contact_message.value
            },
            success : function(data) {
                console.log(data)
                if ( data.sent ) {
                    loading.empty().html('<p class="sblock"><strong><i class="material-icons">done</i></strong></p>').fadeIn(250)
                    var s = $('.sblock');
                    var p = $('<p/>',{"class":"smessage"});
                    p.appendTo(s).append(data.message);
                    $('.sblock').hide().fadeIn(250)
                } else {
                    $('.message').html(data.message)
                    loading.fadeOut(250)
                }
            },
            error : function(x) {
                $('.message').html('Something went wrong. Please refresh the page and try again.')
                loading.fadeOut(250)
                console.log(x)
            }
        })
    })
}