export default function renderPayment() {
    var inner = document.querySelector('#stSignupItemsTable .itemsRow'),
        temp = document.createElement('template'),
        plan = this.state.customer.plan,
        table = this.state.table = [],
        pricing = this.state.pricing,
        stTotal = document.getElementById('stTotal')

    pricing.total = parseInt(plan.price)
    pricing.taxable = parseInt(plan.taxable)
    temp.innerHTML = '<div class="row"><div class="col s8">'+plan.name+'</div><div class="col s4 right-align">'+this.pricer(plan.price)+'</div></div>'
    table.push(temp.content.firstChild)

    var disc = pricing.coupon.value.match(/\\$([0-9]+)/) || ['0','0'],
        discp = pricing.coupon.value.match(/([0-9]+)%/) || ['0','0'],
        discprice = pricing.total*(parseInt(discp[1])/100) || parseInt(disc[1])

    if (0 < discprice) {
        pricing.total -= discprice
        temp.innerHTML = '<div class="row"><div class="col s8">Discount ('+pricing.coupon.id+')</div><div class="col s4 right-align">-'+this.pricer(discprice)+'</div></div>'
        table.push(temp.content.firstChild)
    }

    if ( pricing.tax.value > 0 ) {
        let taxxx = (pricing.taxable*pricing.tax.value)/100
        pricing.total += taxxx
        temp.innerHTML = '<div class="row"><div class="col s8">'+pricing.tax.id+'</div><div class="col s4 right-align">+'+this.pricer(taxxx)+'</div></div>'
        table.push(temp.content.firstChild)
    }

    if ( pricing.shipping > 0 ) {
        pricing.total += pricing.shipping
        temp.innerHTML = '<div class="row"><div class="col s8">Priority Shipping</div><div class="col s4 right-align">+'+this.pricer(pricing.shipping)+'</div></div>'
        table.push(temp.content.firstChild)
    }

    if (stTotal) stTotal.innerText = this.pricer(pricing.total)

    if (inner) {
        while (inner.firstChild) inner.removeChild(inner.firstChild)
        table.forEach((val) => {
            inner.appendChild(val)
        })
        this.setOutcome()
        return this.state.card.obj || this.cardSetup()
    }    
}