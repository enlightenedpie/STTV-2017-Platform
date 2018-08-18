export default function cardSetup() {
    this.stripe = Stripe(_st.stripe.publicKey)
    this.elements = this.stripe.elements()
    this.card = this.elements.create('card',{
        hidePostalCode: true
    })
    
    this.card.mount('#st-checkout-card-element')

    var t = this
    this.card.on( 'change', function( event ) {
        t.setOutcome( event, '#st-checkout-wrapper' )
    })
}