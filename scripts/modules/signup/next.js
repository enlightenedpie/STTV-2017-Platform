export default function next(action,cb) {
    this.clearError()
    if (typeof action === 'undefined') return false
    this.overlay()
    action = action.replace('stBtn_','')
    return this.update(this.state.customer[action],action,cb)
}