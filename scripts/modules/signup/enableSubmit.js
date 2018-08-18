export default function enableSubmit() {
    if (this.valid) {
        document.getElementById('stSignupSubmit').removeAttribute( 'disabled' )
    }
}