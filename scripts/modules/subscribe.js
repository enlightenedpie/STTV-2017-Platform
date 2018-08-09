import Form from '../core/classes/form'

export default class Subscribe extends Form {
    submit() {
        this.send('/list/subscribe',(d) => {
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