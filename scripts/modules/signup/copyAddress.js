export default function copyAddress(el) {
    var billing = this.state.customer.billing,
        elems = document.querySelectorAll('input.shipping, select.shipping')
    elems.forEach(function(v){
        if (el.checked) {
            var classes = v.className.split(/\s+/)
            classes.some(function(a){
                return v.value = billing[a] || ''
            })
        } else {
            if (v.selectedIndex) {
                v.selectedIndex = 0
            } else {
                v.value = ''
            }
        }
        v.focus()
        v.blur()
    })
}