const dismiss = (cb) => {
    document.querySelector('body').classList.remove('nav-sidebar-open','modal-open')
    typeof cb === 'function' && cb()
}
export default dismiss