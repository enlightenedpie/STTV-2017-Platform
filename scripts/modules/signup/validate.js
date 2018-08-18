export default function validate( inputs, cb ) {
    this.disableSubmit()
    var context = '#st-modal-inner',
        ctrl = false,
        inp = inputs.toArray(),
        msg = ' is invalid'
    inp.some( function( v, i ) {
        var t = $(v)
        if ( t.is(':required') && ( !t.val() || t.hasClass('invalid') ) ) {
            if (!t.val()) msg = t.attr('placeholder')+' is required'
            t.addClass('invalid')
            return ctrl = true
        } else if ( t.hasClass('invalid') ) {
            msg = t.attr('placeholder')+msg
            return ctrl = true
        }
        return false
    })
    
    if (ctrl) {
        $( 'p.error', context ).text( msg )
        return !ctrl
    }

    $( 'p.error', context ).text('')
    typeof cb === 'function' && cb(inputs)
}