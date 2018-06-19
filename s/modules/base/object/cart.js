var cart = (function(){
  if ( stajax.type === 'courses' ) {
    return false
  }
  var cartObj = JSON.parse(localStorage.getItem('_stcart_'))
  var initDate = Date.now()
  if ( cartObj === null || (cartObj.ID / 1000 | 0) + (86400) < initDate / 1000 | 0 ) {
    cartObj = {
      ID : initDate,
      signature : btoa(navigator.userAgent+'|'+navigator.platform+'|'+navigator.product).replace(/=/g,''),
      items : {}
    }
  }

  for ( var key in cartObj.items ) {
    var obj = cartObj.items[key]
    if ( obj.type === 'multi-user' ) {
      delete cartObj.items[key]
    }
  }

  var currentCount = Object.keys(cartObj.items).length

  var fabWrap = $('<div/>',{id:'cart-FAB'}),
    fab = $('<a/>',{"class":'cart-fab btn-floating btn-large z-depth-5'}),
    fabCon = $('<i/>',{"class":'material-icons',text:'shopping_cart'}),
    fabAlert = $('<div/>',{"class":'cart-alert circle z-depth-2'})

  $('body').addClass('sttv-jscart')

  fabWrap.append(
    fabAlert.text(currentCount)
  ).append(
    fab.append(fabCon)
  ).appendTo(document.body)

  if ( currentCount > 0 ) {
    fabAlert.addClass('show').siblings('.cart-fab').addClass('pulse')
  }

  localStorage.setItem('_stcart_',JSON.stringify(cartObj))

  return {
    cartObj : cartObj,
    changed : [],
    add : function add(item,skipUpdate) {
      skipUpdate = skipUpdate || false
      if ( typeof item !== 'object' ) {
        throw 'Item must be an object'
      }
      var cart = this.cartObj.items,
        msg = ''

      if ( typeof cart[item.id] === 'undefined' ) {
        cart[item.id] = item
        msg = 'Item added'
      } else {
        if ( item.type !== 'subscription') {
          cart[item.id].qty += item.qty
          msg = 'Quantity updated'
        }
      }

      this.changed.push(item.id)
      this.save(skipUpdate)

      _st.analytics({
        type : 'ec:addProduct',
        data : {
          'id' : item.id,
          'name' : item.name,
          'brand' : 'SupertutorTV',
          'category' : item.type,
          'quantity' : item.qty,
          'price' : (item.price/100).toFixed(2)
        }
      })
      _st.analytics({
        type : 'ec:setAction',
        action : 'add'
      })
      return msg
    },
    remove : function(item,skipUpdate) {
      skipUpdate = skipUpdate || false
      if (typeof item !== 'string' ){
        return false
      }
      delete this.cartObj.items[item]
      return this.save(skipUpdate)
    },
    empty : function(cb) {
      this.cartObj.items = {}
      this.save()
      return typeof cb === 'function' && cb(this)
    },
    unset : function(cb) {
      localStorage.removeItem('_stcart_')
      return typeof cb === 'function' && cb(this)
    },
    save : function(skip) {
      localStorage.setItem('_stcart_',JSON.stringify(this.cartObj))
      return !skip && this.notifications.update()
    },
    get : function() {
      return this.cartObj.items
    },
    notifications : {
      count : currentCount,
      element : fabAlert,
      update : function() {
        this.count = Object.keys(_st.cart.cartObj.items).length
        if ( this.count <= 0 ) {
          $('.cart-alert').removeClass('show').siblings('.cart-fab').removeClass('pulse')
        } else {
          $('.cart-alert').addClass('show').siblings('.cart-fab').addClass('pulse')
        }
        $('.cart-alert').text(this.count)
        return this.count
      }
    },
    submit : function(init,el) {
      var data = {
        init : init || false,
        cart : this.get()
      }

      _st.analytics({
        type : 'ec:setAction',
        action : 'click',
        pageview : true
      })

      _st.request({
        route : stajax.rest.url+'/checkout',
        method : 'POST',
        cdata : data,
        headers : {
          //'X-WP-Nonce' : stajax.rest.nonce,
        },
        success : function(d) {
          _st.checkout = 'subscription'
          el.append(d.html)
          _st.modal.loader()

          for (var itemID in data.cart) {
            var item = data.cart[itemID]
            _st.analytics({
              type : 'ec:addProduct',
              data : {
                'id' : item.id,
                'name' : item.name,
                'brand' : 'SupertutorTV',
                'category' : item.type,
                'quantity' : item.qty,
                'price' : (item.price/100).toFixed(2)
              }
            })
          }
          _st.analytics({
            type : 'ec:setAction',
            action : 'checkout',
            data : {
              'step' : 1
            },
            pageview : true,
            page : '/checkout'
          })
        },
        error : function(x) {
          console.log(x)
          var d = x[0].responseJSON

          //$('.message',el).text(d.message)
          _st.modal.toggle(function() {
            _st.modal.loader()
          })
        }
      })
    }
  }
})()

export {cart}
