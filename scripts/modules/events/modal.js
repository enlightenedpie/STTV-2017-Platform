export default function modal($) {// modal handler
    var selectors = '.modal-toggle, .mu-signup, .mu-submitter, .cart-fab, .payment-launcher'
    $(document).on('click',selectors,function(e) {
        e.preventDefault();
        var t = $(this),
            c = t.attr('class').split(/\s+/),
            tda = t.attr('data-action')

        var f = {
            'mu-signup' : 'mu-signup',
            'payment-launcher' : 'checkout',
            'modal-toggle' : tda,
            'mu-submitter' : 'mu-checkout',
            'cart-fab' : 'sttv-cart'
        }

        c.some(function(v){typeof f[v] !== 'undefined' && _st.modal.init(f[v])});
    })
}