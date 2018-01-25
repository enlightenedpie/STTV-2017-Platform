//let setToken = require('setToken')

module.exports = (function(){
    return {
        init: function(){
            var plan = this.data('get','',function(){
                window.location = window.location.href.replace('checkout', '');
            })
        },
        getCookie : function(name){
            var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            if (match) {return match[2]} else {return null};
        },
        data : function( method, t, cb ){
            var t = t || '',
                method = method || 'get',
                data = $(t).attr('data-bind')

            switch (method) {
                case 'get':
                console.log(this)
                    return localStorage.getItem('checkout') || this.getCookie('checkout') || typeof cb === 'function' && cb()
                case 'set':
                    try {
                        localStorage.setItem('checkout',data)
                    } catch (e) {
                        document.cookie = 'checkout='+data
                    } finally {
                        return typeof cb === 'function' && cb()
                    }
                default:
                    return {
                        error : "Invalid use of 'method'. 'get' and 'set' are the only allowed methods."
                    }
            }
        }
    }
})()