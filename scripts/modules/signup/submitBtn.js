export default function submitBtn() {
    var btn = document.getElementById('stSignupSubmit') || document.createElement('template')
    return (this.state.valid) ? btn.removeAttribute('disabled') : btn.setAttribute('disabled',true)
}