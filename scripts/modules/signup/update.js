export default function update(obj,action,cb) {
    return !this.state.submitted[action] && ((obj,action,cb) => {
        this.post('/signup/'+action, obj, (d) => {
            if (d.code === 'signup_error') return this.printError(d.message) && this.overlay()
            
            Object.assign(obj,d.update)
            this.state.submitted[action] = true
            this.render(d.html)
            this.step(() => {
                typeof cb !== 'undefined' && this[cb]()
                this.overlay()
            })
        })
    })(obj,action,cb)

    /* if (typeof obj !== 'undefined') this.setState(obj)
    var state = this.state,
        t = this
    state.total = state.taxable = 0
    t.table = []

    for ( var i = 0; i < state.items.length; i++ ) {
        var item = state.items[i]
        state.total += item.price
        state.taxable += item.taxable_amt
        t.table.push('<div class="row"><div class="col s8">'+item.name+'</div><div class="col s4 right-align">'+t.pricer(item.price)+'</div></div>')
    }

    var disc = state.coupon.val.match(/\\$([0-9]+)/) || ['0','0'],
        discp = state.coupon.val.match(/([0-9]+)%/) || ['0','0'],
        discprice = state.total*(parseInt(discp[1])/100) || parseInt(disc[1])

    if (0 < discprice) {
        state.total -= discprice
        t.table.push('<div class="row"><div class="col s8">Discount ('+state.coupon.id+')</div><div class="col s4 right-align">-'+t.pricer(discprice)+'</div></div>')
    }

    if ( parseFloat(state.tax.val) > 0 ) {
        let taxxx = (state.taxable*parseFloat(state.tax.val))/100
        state.total += taxxx
        t.table.push('<div class="row"><div class="col s8">'+state.tax.id+'</div><div class="col s4 right-align">+'+t.pricer(taxxx)+'</div></div>')
    }

    if ( state.shipping > 0 ) {
        state.total += state.shipping
        t.table.push('<div class="row"><div class="col s8">Priority Shipping</div><div class="col s4 right-align">+'+t.pricer(state.shipping)+'</div></div>')
    }
    return this */
}