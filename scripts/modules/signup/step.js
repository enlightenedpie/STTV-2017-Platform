export default function step(dir = 'forward',cb) {
    cb = (typeof dir === 'function') ? dir : cb
    dir = (typeof dir === 'function') ? '' : dir

    this.state.html[this.state.step] = this.state.el.firstChild
    while (this.state.el.firstChild) this.state.el.removeChild(this.state.el.firstChild)
    if (dir === 'back')
        this.state.step--
    else
        this.state.step++

    this.state.el.appendChild(this.state.html[this.state.step])
    this.report()
    typeof cb === 'function' && cb()
}