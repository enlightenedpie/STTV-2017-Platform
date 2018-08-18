export default function setOutcome( result, con = document ) {
    var t = this
    if ( typeof result.error !== 'undefined' ) {
        return $( '.error', con ).text( result.error.message );
    } else {
        $( '.error', con ).text( '' );
    }

    if ( !result.empty && result.complete ) {
        var inputs = $( 'input, select', con )
        _st.checkout.validate( inputs, function(inp) {
            t.update(inp.serializeArray())
            t.valid = true
            t.enableSubmit()
        })
    }
}