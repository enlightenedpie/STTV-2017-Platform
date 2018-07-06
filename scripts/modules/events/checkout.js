export default function checkout($) {
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
                route : '/checkout?zip='+val,
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
                route : '/checkout?'+qstring+val,
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
}