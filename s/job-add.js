var _stadd = {
    request : function(cb) {
        $('#job_modal').load(stajax.contentURL+'/templates/jobs/partials/add_job_form.html');
        return typeof cb === 'function' && cb()
    },
    jobRow : function() {
        return $('<div/>',{
            "class" : "single-job row"
        })
        .append($('<div/>',{
            "class" : "single-job-title col s9"
        }).html('<h3><a></a></h3>'))
        .append(
            $('<div/>',{
                "class" : "single-job-button col s3"
            })
            .append(
                $('<a/>',{
                    "class" : "job-deleter",
                    href : "#!delete"
                })
                .html('<i class="material-icons">delete</i>')
            )
            .append(
                $('<a/>',{
                    "class" : "jobs-action-button btn",
                    text : "Apply"
                })
            )
        )
        .append($('<div/>',{
            "class" : "single-jobs-meta col s12"
        }))
    },
    submit : function(form,cb){
        $.ajax({
			url: stajax.rest.url+'/jobs',
			data: JSON.stringify(form),
			method: 'POST',
			headers : {'X-WP-Nonce' : stajax.rest.nonce,'Content-Type':'application/json'},
			processData : false,
			dataType : 'json',
			success: function(data){
                typeof cb === 'function' && cb(data)
			},
			error: function(x,s,r){
                $('.job-add-error').text('Error: '+x.responseJSON.message+' || code: '+x.responseJSON.code)
				console.log([x,s,r]);
			}
		})
    },
    delete : function(id,rowEl){
        $.ajax({
			url: stajax.rest.url+'/jobs?id='+id,
			method: 'DELETE',
			headers : {'X-WP-Nonce' : stajax.rest.nonce,'Content-Type':'application/json'},
			processData : false,
			dataType : 'json',
			success: function(data){
                rowEl.fadeOut(250, function() {
                    $(this).remove()
                    if (!$('.single-job','#jobs-inner').length) {
                        $('#jobs-inner').text('There are currently no open positions.')
                    }
                })
			},
			error: function(x,s,r){
				console.log([x,s,r]);
			}
		})
    }
}

$(document).ready(function(){
    $('<div/>',{
        "class" : "modal",
        id : "job_modal",
        text : "smile"
    }).prependTo('body');

    $('.modal').modal({
        dismissible: true, // Modal can be dismissed by clicking outside of the modal
        opacity: .5, // Opacity of modal background
        inDuration: 250, // Transition in duration
        outDuration: 250, // Transition out duration
        startingTop: '4%', // Starting top style attribute
        endingTop: '10%', // Ending top style attribute
        ready: function(modal, trigger) {},
        complete: function() {}
      }
    );
})
$('.add-job-link').on('click',function(e){
    e.preventDefault();
    if (!$('#job_add_form').length) {
        _stadd.request(
            $('#job_modal').modal('open')
        );
    }
});
$(document).on('click','.job-deleter',function(e){
    e.preventDefault();
    if (confirm("Are you sure you want to delete this job?")) {
        _stadd.delete($(this).attr('data-id'),$(this).parent().parent())
    }
});
$(document).on('click','.add-job-submit',function(e){
    e.preventDefault()
    var obj = {}

    $('#job_add_form').serializeArray().forEach(function(el){
        obj[el.name] = el.value
    });
    _stadd.submit(obj,function(d){
        $('#job_modal').modal('close')
        var newjob = null;
        if ($('.single-job','#jobs').length) {
            newjob = $('.single-job','#jobs').last().clone()
        } else {
            newjob = _stadd.jobRow()
            $('#jobs-inner').empty()
        }

        $('.single-job-title > h3 > a',newjob).text(d.title).attr('href',stajax.rootURL+d.url)
        $('.single-job-button > a.jobs-action-button',newjob).attr('href',stajax.rootURL+d.url)
        $('.single-job-button > a.job-deleter',newjob).attr('data-id',d.id)
        $('.single-jobs-meta',newjob).empty()
        .append($('<span/>',{
            "class" : "meta",
            text : d.location
        }))
        .append($('<span/>',{
            "class" : "meta",
            text : d.category
        }))
        .append($('<span/>',{
            "class" : "meta",
            text : d.job_type
        }))

        newjob.fadeOut(250,function() {
            $(this).appendTo('#jobs-inner')
        })
        newjob.fadeIn(250)

        console.log(d)
    })
})
$(document).on('submit','#job_add_form',function(e){
    e.preventDefault();
})