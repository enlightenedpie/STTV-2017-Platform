export default function scroller($) {
    $(document).on('click','.st-scroll',function(e) {
        e.preventDefault()
        _st.scroll(e.target.getAttribute('href'))
    })
}