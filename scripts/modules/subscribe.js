import Form from '../core/classes/form'
import el from '../core/classes/stDom'

export default class Subscribe extends Form {
    constructor() {
        super({
            firstname: '',
            lastname: '',
            email: ''
        })
    }
    
    submit() {
        this.post('/forms/subscribe',(d) => {
            if (d.code === 'sub_error')
                return this.printError(d.message)
            else
                return this.success(d.message)
        })
    }

    success(msg) {
        var el = document.querySelector('#stMailinglistOverlay'),
            form = document.querySelector('#stMailinglistWrapperInner')
        el.innerHTML = '<span>'+msg+'</span>'
        return el.classList.add('active') || form.classList.add('inactive')
    }
}