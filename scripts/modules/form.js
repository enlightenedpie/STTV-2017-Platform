var form = {
  valid : false,
  disableForm : function(c) {
    $('.signup-submit',c).prop('disabled',!this.valid)
  },
  validate : function(con) {
    var inputs = $('input,select',con)
    inputs.each(function(k,v){
      if ( $(this).is(':required') && ( ( $(this).val() && !$(this).hasClass('invalid') ) || $(this).hasClass('valid') ) ) {
        _st.form.valid = true
      } else {
        _st.form.valid = false
        _st.form.disableForm(con)
        return false
      }
    })
    this.disableForm(con)
  },
}

export default form
