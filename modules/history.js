var backHist = function(r) {
	courses.setup.newRequest(r);
}

var pushHist = function(obj,url,cb) {
		window.history.pushState(obj, document.title, url);
		typeof cb === 'function' && cb();
}

export {backHist, pushHist}
