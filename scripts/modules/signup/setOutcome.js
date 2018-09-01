export default function setOutcome( result ) {
    if (typeof result !== 'undefined') {
        if (typeof result.error !== 'undefined') return !(this.state.card.valid = false) && this.printError(result.error.message)
        this.state.card.valid = !result.empty && result.complete
    }
    this.state.valid = this.state.card.valid && document.getElementById('stTermsBox').checked && this.validate()
    return this.submitBtn()
}