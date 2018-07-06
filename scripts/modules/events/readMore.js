export default function readMore($) {
    $(document).on('click','.read-more',function(e) {
        e.preventDefault()
        $(this).parent().css({'display':'none'});
        $('#content-wrapper').css({'max-height':'none'});
    })
}