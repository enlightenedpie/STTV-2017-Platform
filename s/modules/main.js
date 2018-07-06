import analytics from './analytics'
import request from './request'
import form from './form'
import modal from './modal'
import cart from './cart'
import checkout from './checkout/checkout'
import mu from './mu'
import {parseParams, menu, closer, login, scroll} from './functions'

/*
 *
 * MAIN SITE OBJECT
 *
 */

export function init() {
    return {
        analytics : analytics,
        parseParams : parseParams,
        request : request,
        menu : menu,
        checkout : checkout,
        closer : closer,
        form : form,
        login : login,
        modal : modal,
        cart : cart,
        mu : mu,
        scroll : scroll
    }
 }
