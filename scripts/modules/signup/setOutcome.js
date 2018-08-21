export default function setOutcome( result, con = document ) {
    var t = this
    if (typeof result.error !== 'undefined') return this.printError(result.error.message)
    this.clearError()

    if ( !result.empty && result.complete ) {
        this.validate( '', () => {
            this.enableSubmit()
        })
    }
}