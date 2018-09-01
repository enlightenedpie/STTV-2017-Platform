export default function next(action,cb) {
    if (typeof action === 'undefined') return false
    return !this.validate() || ((action,cb) => {
        this.clearError()
        this.overlay()
        action = action.replace('stBtn_','')
        return this.update(this.state.customer[action],action,cb)
    })(action,cb)
}