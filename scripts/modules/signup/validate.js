export default function validate(cb) {
    var err = false,
        inp = Array.from(document.getElementById('step-'+this.state.step).querySelectorAll('input, select'))

    inp.some((el) => {
        if ( (el.hasAttribute('required') && ( !el.value || el.classList.contains('invalid') )) || el.classList.contains('invalid') ) {
            el.classList.add('invalid')
            this.printError('invalid')
            return err = true
        }
        return false
    })
    this.clearError()
    typeof cb === 'function' && cb()
    return !err
}