export default class Form {
    constructor() {
        this.state = {}
    }

    prepare(el) {
        return this.setState([{
            name: el.name,
            value: el.value
        }])
    }

    printError(msg) {
        return document.querySelector('#stFormErrors p.error').innerHTML = msg
    }

    setIndex(itm,ind,val) {
		var nInd = ind[0]
		if (typeof ind == 'string')
			return this.setIndex(itm,ind.split('-'), val)
		else if (ind.length==1 && typeof val !== 'undefined') {
			return itm[nInd] =  val
		} else if (ind.length==0)
			return itm;
		else
			if (typeof itm[nInd] === 'undefined') itm[nInd] = {}
			return this.setIndex(itm[nInd],ind.slice(1), val)
    }
    
    setState(arr) {
		var t = this
		if (typeof arr !== 'undefined'){
			for (let a = 0; a < arr.length; a++) {
				let v = arr[a].name.split('|')
				for (let i = 0; i < v.length; i++) {
					t.setIndex(t.state,v[i].replace('st-','').split('-'),arr[a].value)
				}
			}
		}
		return this
	}

    send(rt,cb) {
        for (var val in this.state) {
          if (this.state[val].length == 0) return val
        }
        _st.modal.loader()
    
        _st.request({
            route : rt,
            method : 'POST',
            cdata : this.state,
            success : (d) => {
                typeof cb === 'function' && cb(d)
            },
            error : (x) => {
                console.log(x)
            }
        })
      }
}