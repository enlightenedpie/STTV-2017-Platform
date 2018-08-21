export default function cardSetup() {
    this.state.stripe = Stripe(_st.stripe.publicKey)
    this.state.elements = this.state.stripe.elements()
    this.state.card = this.state.elements.create('card',{
        hidePostalCode: true
    })
    this.state.card.mount('#stSignupCardElement')
    this.state.card.on( 'change', ( event ) => {
        this.setOutcome( event, this.state.el )
    })
}