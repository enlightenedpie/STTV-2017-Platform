const menuToggle = (cb) => {
    $('body').toggleClass('nav-sidebar-open')
    typeof cb === 'function' && cb()
}
export default menuToggle