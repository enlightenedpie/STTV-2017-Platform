export default function submit() {
    _st.modal.loader()
    var cus = this.state.customer,
        t = this

    t.stripe.createToken(t.card, cus.billing).then(function(result){
        if (result.error) {
            console.log(result.error)
            return _st.modal.loader(function(el){
                $('.error',el).text(result.error.message)
            })
        } else {
            cus.token = result.token.id
            cus.shipping.name = cus.firstname+' '+cus.lastname
            delete cus.shipping.copy

            _st.request({
                route : '/checkout',
                method : 'POST',
                cdata : t.state,
                success : function(d) {

                    if ( 'stripe_error' === d.code ) {
                        var ecode = d.error.decline_code || d.error.code
                        _st.modal.loader(function(el){
                            $('p.error',el).html('<span class="col s12">We\'re sorry. '+d.error.message+'</span><span class="col s12">err code: '+ecode+'</span>')
                        })
                        return false
                    }
                    
                    var resp = d.response
                    _st.analytics({
                        type : 'ec:setAction',
                        action : 'purchase',
                        data : {
                            'id' : resp.metadata.checkout_id,
                            'revenue' : (resp.total/100).toFixed(2),
                            'tax' : t.state.tax.val,
                            'shipping' : t.state.shipping,
                            'coupon' : t.state.coupon.id,
                            'affiliation' : 'SupertutorTV Online Store'
                        },
                        pageview : true,
                        page : location.pathname
                    })

                    setTimeout(function(){
                        window.location.href = _st.resources.app
                    },1000)
                },
                error : function(x) {
                    var d = x[0].responseJSON
                    console.log(x,d)
                }
            })
        }
    })
}