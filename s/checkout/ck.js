//let setToken = require('setToken')

var checkout = (function(){
    return {
        init: function(){
            console.log( 'initialized' )
        },
        check : function(){
            console.log('it works')
        },
        dataBind : function(t,cb){
            try {
                localStorage.setItem('checkout',$(t).attr('data-bind'))
            } catch (e) {
                document.cookie = 'checkout='+$(t).attr('data-bind')
            } finally {
                return typeof cb === 'function' && cb()
            }
        }
    }
})()

module.exports = checkout