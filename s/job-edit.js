var _sted = {
    currentVal : '',
    handler : function(e) {
        var el = $(e.target).hasClass('job-input') ? $(e.target).text() : tinymce.activeEditor.getContent()
        var obj = {'id':$('input#id').val()}

        switch (e.type) {
            case 'change':
                var check = $(e.target)
                obj[$(e.target).parent().attr('id')] = check.is(':checked')
                _sted.request(obj)
                break
            case 'focus':
                if (_sted.currentVal !== el){
                    _sted.currentVal = el
                }
                break
            case 'blur':
                let newVal = el
                if (newVal !== _sted.currentVal) {
                    obj[$(e.target).attr('id')] = newVal
                    _sted.request(obj)
                }
                break
        }
    },
    request : function(obj) {
        $('.job-edit-saved').text('Saving...')
		$.ajax({
			url: stajax.rest.url+'/jobs',
			data: JSON.stringify(obj || {}),
			method: 'PUT',
			headers : {'X-WP-Nonce' : stajax.rest.nonce,'Content-Type':'application/json'},
			processData : false,
			dataType : 'json',
			success: function(data){
                console.log(data)
                $('.job-edit-saved').text('Saved')
			},
			error: function(x,s,r){
				console.log([x,s,r]);
			}
		})
	}
}

$('.job-input').on('focus blur change',function(e){
    _sted.handler(e)
});