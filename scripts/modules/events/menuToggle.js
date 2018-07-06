export default function menuToggle($) {
    $(document).on('click','.slide-bar',function(e) {
        e.preventDefault()
        _st.menuToggle()
    })
}