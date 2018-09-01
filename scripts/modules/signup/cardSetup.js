export default function cardSetup() {
    this.state.stripe = Stripe(_st.stripe.publicKey)
    this.state.elements = this.state.stripe.elements()
    this.state.card.obj = this.state.elements.create('card',{
        hidePostalCode: true
    })
    this.state.card.obj.mount('#stSignupCardElement')
    this.state.card.obj.on( 'change', ( event ) => {
        this.setOutcome( event )
    })
}