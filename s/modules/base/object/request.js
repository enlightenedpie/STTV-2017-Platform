var request = function(obj) {
  var ajaxp = {
    url: obj.route || '',
    method: obj.method || 'GET',
    headers: obj.headers || {},
    processData : false,
    dataType : obj.dataType || 'json',
    success: function(data){
      typeof obj.success !== 'undefined' && obj.success(data);
    },
    error: function(x,s,r){
      typeof obj.error !== 'undefined' && obj.error([x,s,r]);
    }
  }
  if (ajaxp.method !== 'GET') {
    ajaxp['data'] = JSON.stringify(obj.cdata || {})
  }
  if (typeof obj.accepts !== 'undefined'){
    ajaxp['accepts'] = obj.accepts
  }
  $.ajax(ajaxp)
}

export {request}
