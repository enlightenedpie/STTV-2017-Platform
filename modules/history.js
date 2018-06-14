import {setup} from './loader.js'

var backHist = function(r) {
	setup.newRequest(r);
}

var pushHist = function(obj,url,cb) {
		url = url.replace('https://api.supertutortv.com/', 'http://localhost:8888/')
		window.history.pushState(obj, document.title, url);
		typeof cb === 'function' && cb();
}

export {backHist, pushHist}
