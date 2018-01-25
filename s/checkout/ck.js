//let setToken = require('setToken')

var checkout = (function(){
    return {
        init: function(){
            console.log( 'initialized' )
        },
        data : function( method, t, cb ){
            var data = $(t).attr('data-bind'),
                method = method || 'get'
                
            if (method === 'get') {

            } else if (method === 'set') {
                try {
                    localStorage.setItem('checkout',data)
                } catch (e) {
                    document.cookie = 'checkout='+data
                } finally {
                    return typeof cb === 'function' && cb()
                }
            } else {
                return {
                    error : "Invalid use of 'method'. 'get' and 'set' are the only allowed methods."
                }
            }
           
        }
    }
})()

module.exports = checkout