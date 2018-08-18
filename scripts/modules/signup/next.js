export default function next(action) {
    this.clearError()
    if (typeof action === 'undefined') return false
    this.overlay()
    action = action.replace('stBtn_','')
    if (action === 'void') return this.step(() => {this.overlay()})
    typeof this[action] === 'function' && this[action](action)
}