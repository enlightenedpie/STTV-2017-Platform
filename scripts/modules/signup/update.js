export default function update(obj,action,cb) {
    var fn = (cb) => {
        this.step(() => {
            typeof cb !== 'undefined' && this[cb]()
            this.overlay()
        })
    }
    return (this.state.submitted[action]) ? fn() : ((obj,action,cb) => {
            this.post('/signup/'+action, obj, (d) => {
                if (d.code === 'signup_error') return this.printError(d.message) && this.overlay()
                
                Object.assign(obj,d.update)
                this.state.submitted[action] = true
                this.render(d.html)
                fn(cb)
            })
        })(obj,action,cb)
}