export default function setChecker(el) {
    var clear = (el,reset) => {
        this.clearError()
        el.classList.remove('valid', 'invalid')
        if (typeof reset !== 'undefined' && reset) this.state.pricing[p] = {'id':'','val':''}
        //this.renderItemsTable()
        return true
    }

    if (el.value === '') return clear(el,true)

    var p = el.classList.contains('tax') ? 'tax' : 'coupon'

    if (el.value === this.state.pricing[p].val) return false

    this.get('/signup/check?'+p+'='+el.value+'&sig='+this.state.signature, (d) => {
        clear(el,true)
        if (d.code === 'signup_error') return this.printError(d.message) && this.overlay()
        Object.assign(this.state.pricing[p],d.update)
    });
}