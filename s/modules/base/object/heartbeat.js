var heartBeat = function() {
  _st.request({
    route : stajax.rootURL+"/ping.php",
    success : function(d){
      try {
        if (!d) {
          throw new Exception('Invalid response from _st.heartBeat.');
        } else {
          do {
            $(document).dequeue('heartbeat')
          } while ($(document).queue('heartbeat').length)
        }
      } catch (e) {
        console.log(e);
      }
    },
    error : function(x,s,r){
      Materialize.toast('Offline', 6000);
      console.log(x,s,r);
    }
  });
}

export {heartBeat}
