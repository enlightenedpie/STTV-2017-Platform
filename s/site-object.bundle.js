/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./s/modules/base/_sss.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./s/modules/base/_sss.js":
/*!********************************!*\
  !*** ./s/modules/base/_sss.js ***!
  \********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _object_analytics_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./object/analytics.js */ \"./s/modules/base/object/analytics.js\");\n/* harmony import */ var _object_request_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./object/request.js */ \"./s/modules/base/object/request.js\");\n/* harmony import */ var _object_form_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./object/form.js */ \"./s/modules/base/object/form.js\");\n/* harmony import */ var _object_modal_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./object/modal.js */ \"./s/modules/base/object/modal.js\");\n/* harmony import */ var _object_cart_js__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./object/cart.js */ \"./s/modules/base/object/cart.js\");\n/* harmony import */ var _object_mu_js__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./object/mu.js */ \"./s/modules/base/object/mu.js\");\n/* harmony import */ var _object_functions_js__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./object/functions.js */ \"./s/modules/base/object/functions.js\");\n\n\n\n\n\n\n\n\n/*\n *\n * MAIN SITE OBJECT\n *\n */\nvar _st = {\n\tanalytics : _object_analytics_js__WEBPACK_IMPORTED_MODULE_0__[\"analytics\"],\n\tparseParams : _object_functions_js__WEBPACK_IMPORTED_MODULE_6__[\"parseParams\"],\n\trequest : _object_request_js__WEBPACK_IMPORTED_MODULE_1__[\"request\"],\n\tmenu : _object_functions_js__WEBPACK_IMPORTED_MODULE_6__[\"menu\"],\n\tcloser : _object_functions_js__WEBPACK_IMPORTED_MODULE_6__[\"closer\"],\n\tform : _object_form_js__WEBPACK_IMPORTED_MODULE_2__[\"form\"],\n\theartBeat : heartBeat,\n\tlogin : _object_functions_js__WEBPACK_IMPORTED_MODULE_6__[\"login\"],\n\tmodal : _object_modal_js__WEBPACK_IMPORTED_MODULE_3__[\"modal\"],\n\tcart : _object_cart_js__WEBPACK_IMPORTED_MODULE_4__[\"cart\"],\n\tmu : _object_mu_js__WEBPACK_IMPORTED_MODULE_5__[\"mu\"],\n\tscroll : _object_functions_js__WEBPACK_IMPORTED_MODULE_6__[\"scroll\"]\n};\n\n\n/*\n *\n * EVENT HANDLERS\n *\n */\n( function ( $ ) { //begin wrapper\n\t\"use strict\";\n\n// Opener functions\n$('input, select','#mu_form_wrapper').on('change',function(e){\n\t_st.form.validate('#mu_form_wrapper')\n\t_st.modal.action = ''\n})\n\n// modal handler\nvar selectors = '.slide-bar, .modal-toggle, .mu-signup, .read-more, .mu-submitter, .cart-fab, .payment-launcher'\n$(document).on('click',selectors,function(e) {\n\te.preventDefault();\n\tvar t = $(this),\n\t\tc = t.attr('class').split(/\\s+/),\n\t\ttda = t.attr('data-action')\n\n\tvar f = {\n\t\t'mu-signup' : function() {\n\t\t\t_st.modal.init('mu-signup')\n\t\t},\n\t\t'payment-launcher' : function() {\n\t\t\tmodalInit({'id':'10789','name':'The Best ACT Prep Course Ever','price':24900,'taxable':true,'taxableAmt':2500,'qty':1,'type':'subscription'})\n\t\t\t_st.modal.init( 'checkout' );\n\t\t},\n\t\t'modal-toggle' : function() {\n\t\t\tif ( 'account' == tda ) { // remove this for 2.0\n\t\t\t\twindow.location.href = t.attr('href')\n\t\t\t} else {\n\t\t\t\t_st.modal.init( tda );\n\t\t\t}\n\t\t},\n\t\t'slide-bar' : function() {\n\t\t\t_st.menu()\n\t\t},\n\t\t'read-more' : function() {\n\t\t\tt.parent().css({'display':'none'});\n\t\t\t$('#content-wrapper').css({'max-height':'none'});\n\t\t},\n\t\t'mu-submitter' : function() {\n\t\t\t_st.modal.init('mu-checkout')\n\t\t},\n\t\t'cart-fab' : function() {\n\t\t\t_st.modal.init('sttv-cart')\n\t\t}\n\t}\n\n\tc.some(function(v){typeof f[v] !== 'undefined' && f[v]()});\n});\n\nfunction modalInit(){\n \tvar modal = \"<section id='checkout-wrapper' class='row'> \\\n\t <div id='checkout-info' class='align-middle' style='display:block;'> \\\n\t\t\t\t <div id='wrapper_line-item' class='col s12'> \\\n\t\t\t\t\t\t <div id='customer_info' class='row'> \\\n\t\t\t\t\t\t\t\t <div id='account_info' class='col s12'> \\\n\t\t\t\t\t\t\t\t\t\t <div class='row'> \\\n\t\t\t\t\t\t\t\t\t\t\t\t <h4>Your Information</h4> \\\n\t\t\t\t\t\t\t\t\t\t\t\t <div class='input-field left-col col s6'> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t <input id='sttv_firstname' type='text' class='validate' name='sttv_firstname' value='' required/> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t <label for='sttv_firstname'>First Name</label> \\\n\t\t\t\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t\t\t\t\t <div class='input-field left-col col s6'> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t <input id='sttv_lastname' type='text' class='validate' name='sttv_lastname' value='' required/> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t <label for='sttv_lastname'>Last Name</label> \\\n\t\t\t\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t\t\t\t\t <div class='input-field col s12'> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t <input id='sttv_email' class='validate' name='sttv_email' type='email' value='' required/> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t <label for='sttv_email'>Email Address</label> \\\n\t\t\t\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t\t\t\t\t <div class='input-field col s12'> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t <input id='sttv_password' name='sttv_password' type='password' value='' required/> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t <label for='sttv_password'>Choose Password</label> \\\n\t\t\t\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t\t\t\t\t <div class='input-field col s12'> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t <input id='sttv_phone' class='validate' name='sttv_phone' type='tel' value='' required/> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t <label data-error='Invalid phone number' for='sttv_phone'>Phone Number</label> \\\n\t\t\t\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t <input type='button' class='next_button' onClick=changePanel('checkout-info','checkout-billing') value='next' style='float: right;'/> \\\n\t\t\t\t\t\t </div> \\\n\t\t\t\t\t </div> \\\n\t\t\t <div id='checkout-billing' class='align-middle' style='display:none;'> \\\n\t\t\t\t\t\t <div id='wrapper_line-item' class='col s12'> \\\n\t\t\t\t\t\t\t <div id='billing_info' class='col s12'> \\\n\t\t\t\t\t\t\t\t\t <div id='billing_fields' class='row'> \\\n\t\t\t\t\t\t\t\t\t\t\t <h4>Billing Address</h4> \\\n\t\t\t\t\t\t\t\t\t\t\t <div class='input-field col s12'> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t <input id='sttv_billing_address1' name='sttv_billing_address1' type='text' class='validate' required/> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t <label class='active' for='sttv_billing_address1' data-error='Invalid format' >Address Line 1</label> \\\n\t\t\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t\t\t\t <div class='input-field col s12'> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t <input id='sttv_billing_address2' name='sttv_billing_address2' type='text' /> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t <label class='active' for='sttv_billing_address2'>Address Line 2</label> \\\n\t\t\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t\t\t\t <div class='input-field col s12'> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t <input id='sttv_billing_city' name='sttv_billing_city' class='validate' type='text' required/> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t <label class='active' for='sttv_billing_city'>City</label> \\\n\t\t\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t\t\t\t <div class='input-field left-col col s6'> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t <input id='sttv_billing_state' name='sttv_billing_state' class='validate' type='text' required/> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t <label class='active' for='sttv_billing_state'>State</label> \\\n\t\t\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t\t\t\t <div class='input-field col s6'> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t <input id='sttv_billing_pcode' name='sttv_billing_pcode' class='validate' type='tel' required/> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t <label class='active' for='sttv_billing_pcode'>Postal Code</label> \\\n\t\t\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t\t\t\t <div class='input-field col s12'> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t <select class='country-dd' name='sttv_billing_country' required> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t <option value disabled selected>Country...</option> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t </select> \\\n\t\t\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t\t\t\t <div class='input-field col s12'> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t <input class='filled-in' type='checkbox' id='same_as_billing'/><label for='same_as_billing'>Use same shipping address</label> \\\n\t\t\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t\t <input type='button' class='next_button' onClick=changePanel(\\'checkout-billing\\',\\'checkout-info\\') value='prev'/> \\\n\t\t\t\t\t\t\t\t\t <input type='button' class='next_button' onClick=changePanel(\\'checkout-billing\\',\\'checkout-shipping\\') value='next' style='float: right;'/> \\\n\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t </div> \\\n\t\t\t\t </div> \\\n\t\t <div id='checkout-shipping' class='align-middle' style='display:none;'> \\\n\t\t\t\t <div id='shipping_info' class='col s12'> \\\n\t\t\t\t\t\t <div id='shipping_fields' class='row'> \\\n\t\t\t\t\t\t\t\t <h4>Shipping Address</h4> \\\n\t\t\t\t\t\t\t\t <div class='input-field col s12'> \\\n\t\t\t\t\t\t\t\t\t\t <input id='sttv_shipping_address1' name='sttv_shipping_address1' type='text' class='validate' required/> \\\n\t\t\t\t\t\t\t\t\t\t <label for='sttv_shipping_address1' data-error='Invalid format' >Address Line 1</label> \\\n\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t <div class='input-field col s12'> \\\n\t\t\t\t\t\t\t\t\t\t <input id='sttv_shipping_address2' name='sttv_shipping_address2' type='text' /> \\\n\t\t\t\t\t\t\t\t\t\t <label for='sttv_shipping_address2'>Address Line 2</label> \\\n\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t <div class='input-field col s12'> \\\n\t\t\t\t\t\t\t\t\t\t <input id='sttv_shipping_city' class='validate' name='sttv_shipping_city' type='text' required/> \\\n\t\t\t\t\t\t\t\t\t\t <label for='sttv_shipping_city'>City</label> \\\n\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t <div class='input-field left-col col s6'> \\\n\t\t\t\t\t\t\t\t\t\t <input id='sttv_shipping_state' class='validate' name='sttv_shipping_state' type='text' required/> \\\n\t\t\t\t\t\t\t\t\t\t <label for='sttv_shipping_state'>State</label> \\\n\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t <div class='input-field col s6'> \\\n\t\t\t\t\t\t\t\t\t\t <input id='sttv_shipping_pcode' class='validate' name='sttv_shipping_pcode' type='tel' required/> \\\n\t\t\t\t\t\t\t\t\t\t <label for='sttv_shipping_pcode'>Postal Code</label> \\\n\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t <div class='input-field col s12'> \\\n\t\t\t\t\t\t\t\t\t\t <select class='country-dd validate' name='sttv_shipping_country' required> \\\n\t\t\t\t\t\t\t\t\t\t\t\t <option value disabled selected>Country...</option> \\\n\t\t\t\t\t\t\t\t\t\t </select> \\\n\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t <input type='button' class='next_button' onClick=changePanel(\\'checkout-shipping\\',\\'checkout-billing\\') value='prev'/> \\\n\t\t\t\t\t\t <input type='button' class='next_button' onClick=changePanel(\\'checkout-shipping\\',\\'checkout-order\\') value='next' style='float: right;'/> \\\n\t\t\t\t </div> \\\n\t\t </div> \\\n\t\t <div id='checkout-order' class='align-middle' style='display:none;'> \\\n\t\t\t\t <div class='row'> \\\n\t\t\t\t\t\t <h4>Your Order</h4> \\\n\t\t\t\t\t\t <div class='col s12' style='margin-top:10px'> \\\n\t\t\t\t\t\t\t\t <div class='row'> \\\n\t\t\t\t\t\t\t\t\t\t <div id='shipping_options' class='col s12' style='margin-top:1%'> \\\n\t\t\t\t\t\t\t\t\t\t\t\t <div class='shp-msg'> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t <span>Shipping is for U.S. orders only.</span> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t <div class='input-field col s12' style='margin-top:0px'> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t <p> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t <input id='freeship' name='shipping_options' type='radio' value='0' checked /> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t <label for='freeship'>Free Shipping (1-3 weeks)</label> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t </p> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t <p> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t <input id='priship' name='shipping_options' type='radio' value='1285' /> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t <label for='priship'>Priority Shipping (3-4 days)</label> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t </p> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t <div id='cart-column' class='col s12'> \\\n\t\t\t\t\t\t\t\t <div class='row headings-row'> \\\n\t\t\t\t\t\t\t\t\t\t <div class='col s2'>Qty</div> \\\n\t\t\t\t\t\t\t\t\t\t <div class='col s8'>Item</div> \\\n\t\t\t\t\t\t\t\t\t\t <div class='col s2 right-align'>Price</div> \\\n\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t <div class='items-row'></div> \\\n\t\t\t\t\t\t\t\t <div class='row totals-row'> \\\n\t\t\t\t\t\t\t\t\t\t <div class='col s8'> \\\n\t\t\t\t\t\t\t\t\t\t\t\t <div class='input-field coupon col s12'> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t <input id='sttv_coupon' name='sttv_coupon' type='text'/> \\\n\t\t\t\t\t\t\t\t\t\t\t\t\t\t <label for='sttv_coupon'>Coupon Code</label> \\\n\t\t\t\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t\t\t <div id='total' class='col s4 right-align'><span id='ttltxt'>Total: $<span>0</span></span></div> \\\n\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t </div> \\\n\t\t\t\t </div> \\\n\t\t\t\t <div class='card-content row'> \\\n\t\t\t\t\t\t <h4>Payment</h4> \\\n\t\t\t\t\t\t <div class='input-field col s12'> \\\n\t\t\t\t\t\t\t\t <input name='sttv_cardname' type='text' required/> \\\n\t\t\t\t\t\t\t\t <label class='active' for='sttv_cardname'>Name On Card</label> \\\n\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t <div class='input-field col s12'> \\\n\t\t\t\t\t\t\t\t <div id='sttv_card_element'></div> \\\n\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t <div class='col s12'> \\\n\t\t\t\t\t\t\t\t <div class='input-field col s12' style='margin-top:0px'> \\\n\t\t\t\t\t\t\t\t\t\t <input class='filled-in' type='checkbox' name='sttv_mailinglist' id='sttv_mailinglist' checked/><label for='sttv_mailinglist'>Sign me up for promos, coupons, and giveaways from SupertutorTV</label><br/> \\\n\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t\t\t <div class='tandc col s12'> \\\n\t\t\t\t\t\t\t\t\t\t <span>By submitting this payment, you agree to SupertutorTV's <a class='azure' href='' target='blank'>Terms and Conditions</a>.</span> \\\n\t\t\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t <div class='col s12'> \\\n\t\t\t\t\t\t\t\t <button type='submit' class='signup-submit button-wide z-depth-1 waves-effect waves-light' disabled><span>Place Order</span></button> \\\n\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t <div class='col s12'> \\\n\t\t\t\t\t\t\t\t <p class='error token'>&nbsp;</p> \\\n\t\t\t\t\t\t </div> \\\n\t\t\t\t\t\t <input type='button' class='next_button' onClick=changePanel(\\'checkout-order\\',\\'checkout-shipping\\') value='prev'/> \\\n\t\t\t\t </div> \\\n\t\t </div> \\\n </section> \\\n <script type='text/javascript' src='/wp-content/themes/sttv_2017/s/checkout.js' id='sttv-checkout-js'></script>\"\n $('.sttvmodal_inner').append(modal)\n}\n\n// scroller\n$(document).on('click','.st-scroll',function(e) {\n\te.preventDefault()\n\t_st.scroll(e.target.getAttribute('href'))\n})\n\nvar thenav = $('body.nav-sidebar-open #main-nav');\nthenav.on('click touchstart',function(e) {\n\tif (e.offsetX > thenav.offsetWidth) {\n\t\talert('Clicked!');\n\t\te.preventDefault();\n\t\t_st.closer();\n\t}\n});\n\n$('li.menu-item-has-children>a').click(function(e) {\n\te.preventDefault();\n\t$(this).siblings('ul.sub-menu').toggleClass('active').promise().done(function(){\n\t\t$('ul.sub-menu').not(this).removeClass('active');\n\t});\n});\n\n$(document).on('submit','form#sttv_login_form',function(e) {\n\te.preventDefault();\n\tif (0 === $('#sttv_user').val().length){\n\t\t$('.message').html('Username is required')\n\t\treturn;\n\t}\n\n\tvar loader = '<img src=\"'+stajax.contentURL+'/i/sttv-spinner.gif\" alt=\"Loading...\" />',\n\t\tld = $('.loading_overlay');\n\tld.append(loader).promise().done(function(){\n\t\t$(this).fadeIn(250);\n\t});\n\n\t_st.request({\n\t\troute : stajax.rest.url+'/auth?action=login',\n\t\tmethod : 'POST',\n\t\theaders : {\n\t\t\t'X-WP-Nonce' : stajax.rest.nonce,\n\t\t\t'X-STTV-Auth' : btoa(this.sttv_user.value+':'+this.sttv_pass.value)\n\t\t},\n\t\tsuccess : function(data) {\n\t\t\tif ( data.code == 'login_success' ) {\n\t\t\t\tld.empty().html('<p class=\"sblock\"><strong><i class=\"material-icons\">done</i></strong></p>').fadeIn(250)\n\t\t\t\t$('.sblock').hide().fadeIn(250)\n\t\t\t\tsetTimeout(function(){\n\t\t\t\t\twindow.location.href = data.redirect\n\t\t\t\t},250);\n\t\t\t}\n\t\t},\n\t\terror : function(x) {\n\t\t\tvar data = x[0].responseJSON,\n\t\t\t\tmsg = ( typeof data.errors.too_many_retries !== 'undefined') ? data.errors.too_many_retries[0] : data.message;\n\n\t\t\t$('.message').html(msg)\n\t\t\tld.fadeOut(250)\n\t\t\tconsole.log(data)\n\t\t}\n\t})\n});\n\n$('form#sttv_contact').on('submit',function(e) {\n\te.preventDefault();\n\tvar loading = $('.loading_overlay',$(this).parent()).html('<img src=\"'+stajax.contentURL+'/i/sttv-spinner.gif\" alt=\"Loading...\" />')\n\n\tloading.fadeIn(250)\n\n\t_st.request({\n\t\troute : stajax.rest.url+'/contact',\n\t\tmethod : 'POST',\n\t\theaders : {\n\t\t\t'X-WP-Nonce' : stajax.rest.nonce\n\t\t},\n\t\tcdata : {\n\t\t\tg_recaptcha_response : grecaptcha.getResponse(),\n\t\t\tsttv_contact_name: this.sttv_contact_name.value,\n\t\t\tsttv_contact_email: this.sttv_contact_email.value,\n\t\t\tsttv_contact_subject: this.sttv_contact_subject.value,\n\t\t\tsttv_contact_message: this.sttv_contact_message.value\n\t\t},\n\t\tsuccess : function(data) {\n\t\t\tconsole.log(data)\n\t\t\tif ( data.sent ) {\n\t\t\t\tloading.empty().html('<p class=\"sblock\"><strong><i class=\"material-icons\">done</i></strong></p>').fadeIn(250)\n\t\t\t\tvar s = $('.sblock');\n\t\t\t\tvar p = $('<p/>',{\"class\":\"smessage\"});\n\t\t\t\tp.appendTo(s).append(data.message);\n\t\t\t\t$('.sblock').hide().fadeIn(250)\n\t\t\t} else {\n\t\t\t\t$('.message').html(data.message)\n\t\t\t\tloading.fadeOut(250)\n\t\t\t}\n\t\t},\n\t\terror : function(x) {\n\t\t\t$('.message').html('Something went wrong. Please refresh the page and try again.')\n\t\t\tloading.fadeOut(250)\n\t\t\tconsole.log(x)\n\t\t}\n\t})\n\n  });\n\n\t$('#subscribe_page_mc').on('submit',function(e){\n\t\te.preventDefault();\n\t\tvar form = $(this)\n\n\t\tvar loading = $('.loading_overlay',$(this).parent()).html('<img src=\"'+stajax.contentURL+'/i/sttv-spinner.gif\" alt=\"Loading...\" />')\n\n\t\tloading.fadeIn(250)\n\n\t\tvar fields = {\n\t\t\tfname : $('#sttv_mc_fname',form).val(),\n\t\t\tlname : $('#sttv_mc_lname',form).val(),\n\t\t\temail : $('#sttv_mc_email',form).val(),\n\t\t\tg_recaptcha_response : grecaptcha.getResponse()\n\t\t}\n\n\t\t_st.request({\n\t\t\troute : stajax.rest.url+'/subscribe',\n\t\t\tmethod : 'POST',\n\t\t\tcdata : fields,\n\t\t\theaders : {'X-WP-Nonce' : stajax.rest.nonce},\n\t\t\tsuccess : function(d){\n\t\t\t\t$('input, button',form).prop('disabled',true)\n\t\t\t\tgrecaptcha.reset()\n\t\t\t\tloading.empty().html('<p class=\"sblock\"><strong><i class=\"material-icons\">done</i></strong></p>').fadeIn(250)\n\t\t\t\tvar s = $('.sblock');\n\t\t\t\tvar p = $('<p/>',{\"class\":\"smessage\"});\n\t\t\t\tp.appendTo(s).append(d.message);\n\t\t\t\t$('.sblock').hide().fadeIn(250)\n\t\t\t\tconsole.log(d)\n\t\t\t},\n\t\t\terror : function(x){\n\t\t\t\t$('.message',form).html('Something went wrong. Please refresh the page and try again.')\n\t\t\t\tloading.fadeOut(250)\n\t\t\t\tconsole.log(x)\n\t\t\t}\n\t\t})\n\t});\n\n} ( jQuery ) ); //end wrapper\n\n\n//# sourceURL=webpack:///./s/modules/base/_sss.js?");

/***/ }),

/***/ "./s/modules/base/object/analytics.js":
/*!********************************************!*\
  !*** ./s/modules/base/object/analytics.js ***!
  \********************************************/
/*! exports provided: analytics */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"analytics\", function() { return analytics; });\nvar analytics = function( obj ) {\n  if ( typeof obj === 'undefined' ) {\n    return false\n  }\n\n  var page = obj.page || false,\n    pageview = obj.pageview || false,\n    event = obj.event || false,\n    action = obj.action || obj.data\n    data = obj.data || false\n\n  if (typeof action === 'string' && typeof data === 'object') {\n    //console.log( \"ga( \"+obj.type+\", \"+action+\", \"+data+\" )\" )\n    ga( obj.type, action, data )\n  } else if (typeof obj.type !== 'undefined') {\n    //console.log( \"ga( \"+obj.type+\", \"+action+\" )\" )\n    ga( obj.type, action )\n  }\n\n  if ( event ) {\n    //console.log( \"ga( 'send', 'event', \"+event+\" )\" )\n    ga( 'send', 'event', event.name )\n  }\n\n  return (pageview) ? (page ? ga( 'send', 'pageview', page ) : ga( 'send', 'pageview' ) ) : pageview\n}\n\n\n\n\n//# sourceURL=webpack:///./s/modules/base/object/analytics.js?");

/***/ }),

/***/ "./s/modules/base/object/cart.js":
/*!***************************************!*\
  !*** ./s/modules/base/object/cart.js ***!
  \***************************************/
/*! exports provided: cart */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"cart\", function() { return cart; });\nvar cart = (function(){\n  if ( stajax.type === 'courses' ) {\n    return false\n  }\n  var cartObj = JSON.parse(localStorage.getItem('_stcart_'))\n  var initDate = Date.now()\n  if ( cartObj === null || (cartObj.ID / 1000 | 0) + (86400) < initDate / 1000 | 0 ) {\n    cartObj = {\n      ID : initDate,\n      signature : btoa(navigator.userAgent+'|'+navigator.platform+'|'+navigator.product).replace(/=/g,''),\n      items : {}\n    }\n  }\n\n  for ( var key in cartObj.items ) {\n    var obj = cartObj.items[key]\n    if ( obj.type === 'multi-user' ) {\n      delete cartObj.items[key]\n    }\n  }\n\n  var currentCount = Object.keys(cartObj.items).length\n\n  var fabWrap = $('<div/>',{id:'cart-FAB'}),\n    fab = $('<a/>',{\"class\":'cart-fab btn-floating btn-large z-depth-5'}),\n    fabCon = $('<i/>',{\"class\":'material-icons',text:'shopping_cart'}),\n    fabAlert = $('<div/>',{\"class\":'cart-alert circle z-depth-2'})\n\n  $('body').addClass('sttv-jscart')\n\n  fabWrap.append(\n    fabAlert.text(currentCount)\n  ).append(\n    fab.append(fabCon)\n  ).appendTo(document.body)\n\n  if ( currentCount > 0 ) {\n    fabAlert.addClass('show').siblings('.cart-fab').addClass('pulse')\n  }\n\n  localStorage.setItem('_stcart_',JSON.stringify(cartObj))\n\n  return {\n    cartObj : cartObj,\n    changed : [],\n    add : function add(item,skipUpdate) {\n      skipUpdate = skipUpdate || false\n      if ( typeof item !== 'object' ) {\n        throw 'Item must be an object'\n      }\n      var cart = this.cartObj.items,\n        msg = ''\n\n      if ( typeof cart[item.id] === 'undefined' ) {\n        cart[item.id] = item\n        msg = 'Item added'\n      } else {\n        if ( item.type !== 'subscription') {\n          cart[item.id].qty += item.qty\n          msg = 'Quantity updated'\n        }\n      }\n\n      this.changed.push(item.id)\n      this.save(skipUpdate)\n\n      _st.analytics({\n        type : 'ec:addProduct',\n        data : {\n          'id' : item.id,\n          'name' : item.name,\n          'brand' : 'SupertutorTV',\n          'category' : item.type,\n          'quantity' : item.qty,\n          'price' : (item.price/100).toFixed(2)\n        }\n      })\n      _st.analytics({\n        type : 'ec:setAction',\n        action : 'add'\n      })\n      return msg\n    },\n    remove : function(item,skipUpdate) {\n      skipUpdate = skipUpdate || false\n      if (typeof item !== 'string' ){\n        return false\n      }\n      delete this.cartObj.items[item]\n      return this.save(skipUpdate)\n    },\n    empty : function(cb) {\n      this.cartObj.items = {}\n      this.save()\n      return typeof cb === 'function' && cb(this)\n    },\n    unset : function(cb) {\n      localStorage.removeItem('_stcart_')\n      return typeof cb === 'function' && cb(this)\n    },\n    save : function(skip) {\n      localStorage.setItem('_stcart_',JSON.stringify(this.cartObj))\n      return !skip && this.notifications.update()\n    },\n    get : function() {\n      return this.cartObj.items\n    },\n    notifications : {\n      count : currentCount,\n      element : fabAlert,\n      update : function() {\n        this.count = Object.keys(_st.cart.cartObj.items).length\n        if ( this.count <= 0 ) {\n          $('.cart-alert').removeClass('show').siblings('.cart-fab').removeClass('pulse')\n        } else {\n          $('.cart-alert').addClass('show').siblings('.cart-fab').addClass('pulse')\n        }\n        $('.cart-alert').text(this.count)\n        return this.count\n      }\n    },\n    submit : function(init,el) {\n      var data = {\n        init : init || false,\n        cart : this.get()\n      }\n\n      _st.analytics({\n        type : 'ec:setAction',\n        action : 'click',\n        pageview : true\n      })\n\n      _st.request({\n        route : stajax.rest.url+'/checkout',\n        method : 'POST',\n        cdata : data,\n        headers : {\n          //'X-WP-Nonce' : stajax.rest.nonce,\n        },\n        success : function(d) {\n          _st.checkout = 'subscription'\n          el.append(d.html)\n          _st.modal.loader()\n\n          for (var itemID in data.cart) {\n            var item = data.cart[itemID]\n            _st.analytics({\n              type : 'ec:addProduct',\n              data : {\n                'id' : item.id,\n                'name' : item.name,\n                'brand' : 'SupertutorTV',\n                'category' : item.type,\n                'quantity' : item.qty,\n                'price' : (item.price/100).toFixed(2)\n              }\n            })\n          }\n          _st.analytics({\n            type : 'ec:setAction',\n            action : 'checkout',\n            data : {\n              'step' : 1\n            },\n            pageview : true,\n            page : '/checkout'\n          })\n        },\n        error : function(x) {\n          console.log(x)\n          var d = x[0].responseJSON\n\n          //$('.message',el).text(d.message)\n          _st.modal.toggle(function() {\n            _st.modal.loader()\n          })\n        }\n      })\n    }\n  }\n})()\n\n\n\n\n//# sourceURL=webpack:///./s/modules/base/object/cart.js?");

/***/ }),

/***/ "./s/modules/base/object/form.js":
/*!***************************************!*\
  !*** ./s/modules/base/object/form.js ***!
  \***************************************/
/*! exports provided: form */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"form\", function() { return form; });\nvar form = {\n  valid : false,\n  disableForm : function(c) {\n    $('.signup-submit',c).prop('disabled',!this.valid)\n  },\n  validate : function(con) {\n    var inputs = $('input,select',con)\n    inputs.each(function(k,v){\n      if ( $(this).is(':required') && ( ( $(this).val() && !$(this).hasClass('invalid') ) || $(this).hasClass('valid') ) ) {\n        _st.form.valid = true\n      } else {\n        _st.form.valid = false\n        _st.form.disableForm(con)\n        return false\n      }\n    })\n    this.disableForm(con)\n  },\n}\n\n\n\n\n//# sourceURL=webpack:///./s/modules/base/object/form.js?");

/***/ }),

/***/ "./s/modules/base/object/functions.js":
/*!********************************************!*\
  !*** ./s/modules/base/object/functions.js ***!
  \********************************************/
/*! exports provided: parseParams, menu, closer, login, scroll */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"parseParams\", function() { return parseParams; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"menu\", function() { return menu; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"closer\", function() { return closer; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"login\", function() { return login; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"scroll\", function() { return scroll; });\nvar parseParams = function(str,regex) {\n  return (str || document.location.search).replace(/(^\\?)/,'').replace(regex,'').split(\"&\").map(function(n){return n = n.split(\"=\"),this[n[0]] = n[1],this}.bind({}))[0];\n}\n\nvar menu = function(cb) {\n  $('body').toggleClass('nav-sidebar-open')\n  typeof cb === 'function' && cb();\n}\n\nvar closer = function(cb) {\n  jQuery('body').removeClass('nav-sidebar-open modal-open');\n  typeof cb === 'function' && cb();\n}\n\nvar login = function(el) {\n  _st.request({\n    route : stajax.rest.url+'/auth',\n    headers : {\n      //'X-WP-Nonce' : stajax.rest.nonce,\n    },\n    success : function(d) {\n      el.append(d)\n      _st.modal.loader()\n    },\n    error : function(x) {\n      console.log(x)\n    }\n  })\n}\n\nvar scroll = function(a) {\n  $('html, body').stop().animate({\n    scrollTop: $(a).offset().top-100\n  },1250,\"swing\")\n}\n\n\n\n\n//# sourceURL=webpack:///./s/modules/base/object/functions.js?");

/***/ }),

/***/ "./s/modules/base/object/modal.js":
/*!****************************************!*\
  !*** ./s/modules/base/object/modal.js ***!
  \****************************************/
/*! exports provided: modal */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"modal\", function() { return modal; });\nvar modal = (function() {\n  $('.loading-spinner').each(function(i){\n    $(this).attr('src',stajax.contentURL+'/i/sttv-spinner.gif')\n  })\n  return {\n    action : '',\n    element : $('#sttvmodal'),\n    inner : $('.sttvmodal_inner'),\n    init : function( act ){\n      if (typeof act === 'undefined'){\n        return false\n      }\n      if (this.action === act) {\n        return this.toggle()\n      }\n\n      var cb;\n      if (act !== 'close') {\n        this.action = act\n        _st.modal.loader(function() {\n          _st.modal.inner.empty()\n        })\n      }\n\n      switch (act) {\n        case 'close':\n          break\n        case 'login':\n          cb = function(el) {\n            _st.login(el)\n          }\n          break\n        case 'account':\n          cb = function(el) {\n\n          }\n          break\n        case 'mu-checkout':\n          cb = function(el) {\n            _st.mu.submit(el,'#mu_form_wrapper')\n          }\n          break\n        case 'mu-signup':\n          cb = function(el) {\n            _st.mu.register(el,'#mu_form_wrapper')\n          }\n          break\n        case 'sttv-cart':\n        case 'checkout':\n          cb = function(el) {\n            _st.cart.submit(true,el)\n          }\n          break\n      }\n      this.toggle(cb)\n    },\n    toggle : function(cb) {\n      $('body').toggleClass('modal-open')\n      typeof cb === 'function' && cb(_st.modal.inner)\n    },\n    loader : function(cb) {\n      _st.modal.element.toggleClass('loader-active')\n      typeof cb === 'function' && cb(_st.modal.inner)\n    }\n  }\n})()\n\n\n\n\n//# sourceURL=webpack:///./s/modules/base/object/modal.js?");

/***/ }),

/***/ "./s/modules/base/object/mu.js":
/*!*************************************!*\
  !*** ./s/modules/base/object/mu.js ***!
  \*************************************/
/*! exports provided: mu */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"mu\", function() { return mu; });\nvar mu = {\n  submit : function(el,con) {\n    var data = {\n      mukey : $('input[name=mukey]',con).val(),\n      email : $('input[name=email]',con).val(),\n      license : {\n        id : $('select[name=sttv_course_id]',con).val(),\n        title : $('select[name=sttv_course_id] option:selected',con).text(),\n        qty : $('select[name=qty]',con).val()\n      }\n    },\n    type = 'multi-user'\n\n    _st.request({\n      route : stajax.rest.url+'/multi-user',\n      method : 'POST',\n      cdata : data,\n      headers : {\n        'X-WP-Nonce' : stajax.rest.nonce,\n      },\n      success : function(d) {\n        _st.checkout = type\n        _st.cart.empty(function(t) {\n          t.add(d.data,true)\n        })\n        el.append(d.html)\n        _st.modal.loader()\n        console.log(d)\n      },\n      error : function(x) {\n        var d = x[0].responseJSON\n\n        $('.message',con).text(d.message)\n        _st.modal.toggle(function() {\n          _st.modal.loader()\n        })\n        console.log(d)\n      }\n    })\n  },\n  register : function(el,con) {\n\n    var data = {\n      muid : $('input[name=mukey]',con).val(),\n      email : $('input[name=sttv_email]',con).val(),\n      password : $('input[name=sttv_password]',con).val(),\n      firstName : $('input[name=sttv_firstname]',con).val(),\n      lastName : $('input[name=sttv_lastname]',con).val()\n    }\n\n    _st.request({\n      route : stajax.rest.url+'/checkout',\n      method : 'POST',\n      cdata : data,\n      headers : {\n        'X-WP-Nonce' : stajax.rest.nonce,\n      },\n      success : function(d) {\n        el.append(d.html)\n        _st.modal.loader()\n        setTimeout(function(){\n          window.location.href = d.data.redirect\n        },2000)\n        console.log(d)\n      },\n      error : function(x) {\n        console.log(x)\n        var d = x[0].responseJSON\n\n        $('.message',con).text(d.message)\n        _st.modal.toggle(function() {\n          _st.modal.loader()\n        })\n      }\n    })\n  }\n}\n\n\n\n\n//# sourceURL=webpack:///./s/modules/base/object/mu.js?");

/***/ }),

/***/ "./s/modules/base/object/request.js":
/*!******************************************!*\
  !*** ./s/modules/base/object/request.js ***!
  \******************************************/
/*! exports provided: request */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"request\", function() { return request; });\nvar request = function(obj) {\n  var ajaxp = {\n    url: obj.route || '',\n    method: obj.method || 'GET',\n    headers: obj.headers || {},\n    processData : false,\n    dataType : obj.dataType || 'json',\n    success: function(data){\n      typeof obj.success !== 'undefined' && obj.success(data);\n    },\n    error: function(x,s,r){\n      typeof obj.error !== 'undefined' && obj.error([x,s,r]);\n    }\n  }\n  if (ajaxp.method !== 'GET') {\n    ajaxp['data'] = JSON.stringify(obj.cdata || {})\n  }\n  if (typeof obj.accepts !== 'undefined'){\n    ajaxp['accepts'] = obj.accepts\n  }\n  $.ajax(ajaxp)\n}\n\n\n\n\n//# sourceURL=webpack:///./s/modules/base/object/request.js?");

/***/ })

/******/ });