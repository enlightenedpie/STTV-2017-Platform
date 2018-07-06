import $ from 'jquery'
/*
 *
 * SITE EVENTS
 *
 */

// Opener functions
$('input, select','#mu_form_wrapper').on('change',function(e){
    _st.form.validate('#mu_form_wrapper')
    _st.modal.action = ''
})
// modal handler
var selectors = '.slide-bar, .modal-toggle, .mu-signup, .read-more, .mu-submitter, .cart-fab, .payment-launcher'
$(document).on('click',selectors,function(e) {
    e.preventDefault();
    var t = $(this),
        c = t.attr('class').split(/\s+/),
        tda = t.attr('data-action')

    var f = {
        'mu-signup' : function() {
            _st.modal.init('mu-signup')
        },
        'payment-launcher' : function() {
            _st.modal.init( 'checkout' );
        },
        'modal-toggle' : function() {
            if ( 'account' == tda ) { // remove this for 2.0
                window.location.href = t.attr('href')
            } else {
                _st.modal.init( tda );
            }
        },
        'slide-bar' : function() {
            _st.menu()
        },
        'read-more' : function() {
            t.parent().css({'display':'none'});
            $('#content-wrapper').css({'max-height':'none'});
        },
        'mu-submitter' : function() {
            _st.modal.init('mu-checkout')
        },
        'cart-fab' : function() {
            _st.modal.init('sttv-cart')
        }
    }

    c.some(function(v){typeof f[v] !== 'undefined' && f[v]()});
})

// scroller
$(document).on('click','.st-scroll',function(e) {
    e.preventDefault()
    _st.scroll(e.target.getAttribute('href'))
})

var thenav = $('body.nav-sidebar-open #main-nav');
thenav.on('click touchstart',function(e) {
    if (e.offsetX > thenav.offsetWidth) {
        alert('Clicked!')
        e.preventDefault()
        _st.closer()
    }
})

$('li.menu-item-has-children>a').click(function(e) {
    e.preventDefault()
    $(this).siblings('ul.sub-menu').toggleClass('active').promise().done(function(){
        $('ul.sub-menu').not(this).removeClass('active')
    })
})

$(document).on('submit','form#sttv_login_form',function(e) {
    e.preventDefault();
    if (0 === $('#sttv_user').val().length){
        $('.message').html('Username is required')
        return;
    }

    var loader = '<img src="'+stajax.contentURL+'/i/sttv-spinner.gif" alt="Loading..." />',
        ld = $('.loading_overlay');
    ld.append(loader).promise().done(function(){
        $(this).fadeIn(250);
    });

    _st.request({
        route : stajax.rest.url+'/auth?action=login',
        method : 'POST',
        headers : {
            'X-WP-Nonce' : stajax.rest.nonce,
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
});

$('form#sttv_contact').on('submit',function(e) {
    e.preventDefault();
    var loading = $('.loading_overlay',$(this).parent()).html('<img src="'+stajax.contentURL+'/i/sttv-spinner.gif" alt="Loading..." />')

    loading.fadeIn(250)

    _st.request({
        route : stajax.rest.url+'/contact',
        method : 'POST',
        headers : {
            'X-WP-Nonce' : stajax.rest.nonce
        },
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

$('#subscribe_page_mc').on('submit',function(e){
    e.preventDefault();
    var form = $(this)

    var loading = $('.loading_overlay',$(this).parent()).html('<img src="'+stajax.contentURL+'/i/sttv-spinner.gif" alt="Loading..." />')

    loading.fadeIn(250)

    var fields = {
        fname : $('#sttv_mc_fname',form).val(),
        lname : $('#sttv_mc_lname',form).val(),
        email : $('#sttv_mc_email',form).val(),
        g_recaptcha_response : grecaptcha.getResponse()
    }

    _st.request({
        route : stajax.rest.url+'/subscribe',
        method : 'POST',
        cdata : fields,
        headers : {'X-WP-Nonce' : stajax.rest.nonce},
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

$('#same_as_billing').on('change',function() {
    if ($(this).is(":checked")) {

        $('input[name=sttv_shipping_address1]').val($('input[name=sttv_billing_address1]').val());
        $('input[name=sttv_shipping_address2]').val($('input[name=sttv_billing_address2]').val());
        $('input[name=sttv_shipping_city]').val($('input[name=sttv_billing_city]').val());
        $('input[name=sttv_shipping_state]').val($('input[name=sttv_billing_state]').val());
        $('input[name=sttv_shipping_pcode]').val($('input[name=sttv_billing_pcode]').val());
        $('select[name=sttv_shipping_country]').val($('select[name=sttv_billing_country]').val());

        $('select').formSelect()

    } else {
        $("#shipping_fields :input").each(function(){
            $(this).val('')
        });
        $("select[name=sttv_shipping_country]").prop("selectedIndex", -1)
    }

    M.updateTextFields()
    $( 'input, select', '#shipping_fields' ).blur()
})

$('[name=shipping_options]').on({
    change : function(e) {
        e.preventDefault()
        _st.checkout.update({
            shipping : parseInt($(this).filter(':checked').val())
        })
    }
})

$('[name=sttv_billing_pcode]').on({
    focusin : function() {
        $(this).data('val',$(this).val())
    },
    change : function(e) {
        e.preventDefault()
        var val = $(this).val()
        if ( $(this).data('val') === val ) {
            return false
        } else if ( !val ) {
            return _st.checkout.update({
                tax : {
                    rate : 0
                }
            })
        }

        _st.request({
            route : stajax.rest.url+'/checkout?zip='+val,
            headers : {
                'X-WP-Nonce' : stajax.rest.nonce,
            },
            success : function(d) {
                _st.checkout.update({
                    tax : {
                        rate : d.tax,
                        msg : d.message
                    }
                })
            },
            error : function(x) {
                console.log(x)
            }
        })
    }
})

$('[name=sttv_email],input[name=sttv_coupon]').on({
    focusout : function(e) {
        if ( !$(this).val() ) {
            tThis.removeClass('valid invalid')
        }
    },
    change : function(e) {
        e.preventDefault();
        var tThis = $(this),
            val = tThis.val(),
            qstring = ''

        switch (tThis.attr('name')) {
            case 'sttv_coupon':
                if (!val) {
                    _st.checkout.update({
                        disc : 0,
                        discp : 0
                    })
                    return false
                }
                qstring = 'coupon='
                break
            case 'sttv_email':
                if (!val) {
                    return false
                }
                qstring = 'email='
                break
            default:
                return false
        }

        _st.request({
            route : stajax.rest.url+'/checkout?'+qstring+val,
            headers : {
                //'X-WP-Nonce' : stajax.rest.nonce,
            },
            success : function(d) {
                tThis.removeClass('valid invalid')
                console.log(d)

                var msg = {},
                    cls = ''

                switch (d.code) {
                    case 'coupon_valid':
                    case 'email_available':
                        cls = 'valid'
                        msg = { 'data-success' : d.message }
                        break
                    case 'coupon_invalid':
                        cls = 'invalid'
                        msg = { 'data-error' : d.error.message }
                        break
                    case 'coupon_expired':
                    case 'email_taken':
                        cls = 'invalid'
                        msg = { 'data-error' : d.message }
                        break
                }

                tThis
                    .addClass( cls )
                    .siblings( 'label' )
                    .attr( msg )

                _st.checkout.update({
                    discp : d.percent_off,
                    disc : d.amount_off,
                    coupon : d.id
                })
            },
            error : function(x) {
                console.log(x)
            }
        })
    }
});

$('.signup-submit').on('click',function(e) {
    e.preventDefault();
    var inputs = $( 'input, select', '#checkout-wrapper' ),
        valid = _st.checkout.validate( inputs, '#checkout-wrapper' )
    if ( valid ) {
        _st.checkout.submit( _st.parseParams( decodeURIComponent( inputs.serialize() ), /sttv_/gi ) )
    }
})
