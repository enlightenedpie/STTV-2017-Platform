export default function setChecker(el) {
    function clear(el,reset) {
        $('p.error').text('')
        el.classList.remove('valid', 'invalid')
        if (typeof reset !== 'undefined' && reset) t.state[p] = {'id':'','val':''}
        t.update().renderItemsTable()
        return true
    }
    var t = this,
        params = ['coupon','email','tax'],
        p = false
        params.some(function(v){
            if (el.classList.contains(v)) p = v
            return el.classList.contains(v)
        })
    
    if (el.value === '') return clear(el,true)

    if (el.value === t.state[p].val) return false

    _st.request({
        route : '/checkout?'+p+'='+el.value+'&sig='+t.state.signature,
        success : function(d) {
            clear(el)
            switch (d.code) {
                case 'coupon_valid':
                case 'email_available':
                case 'checkout_tax':
                    el.classList.add('valid')
                    break;
                case 'coupon_invalid':
                case 'coupon_expired':
                case 'email_taken':
                    el.classList.add('invalid')
                    $('p.error').text(d.message)
                    break;
                default:
                    return 'What\'re you even doing here?'
            }
            t.state[p] = {
                id: d.id,
                val: d.value
            }
            t.update().renderItemsTable()
        },
        error : function(x){
            console.log(x)
        }
    })
    return this
}