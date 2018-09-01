export default function pay() {
    this.overlay()

    if (!this.state.valid) return false

    var cus = this.state.customer,
        t = this

    t.state.stripe.createToken(t.state.card.obj, cus.billing).then(function(result){
        if (result.error) return this.printError(result.error.message) && this.overlay()

        cus.token = result.token.id
        cus.shipping.name = cus.firstname+' '+cus.lastname
        delete cus.shipping.copy

        return t.post('/signup/pay', t.state, (d) => {
            if (d.code === 'stripe_error') {
                var ecode = d.data.decline_code || d.data.code
                t.printError('<span class="col s12">We\'re sorry. '+d.data.message+'</span><span class="col s12">err code: '+ecode+'</span>')
                t.overlay()
                return false
            }

            var resp = d.response
            _st.analytics({
                type : 'ec:setAction',
                action : 'purchase',
                data : {
                    'id' : resp.metadata.checkout_id,
                    'revenue' : (resp.total/100).toFixed(2),
                    'tax' : t.state.pricing.tax.val,
                    'shipping' : t.state.pricing.shipping,
                    'coupon' : t.state.pricing.coupon.id,
                    'affiliation' : 'SupertutorTV Online Store'
                },
                pageview : true,
                page : '/signup'
            })

            setTimeout(function(){
                window.location.href = _st.resources.app
            },1000)
        })
    })
}