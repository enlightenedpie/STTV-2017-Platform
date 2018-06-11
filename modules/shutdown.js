import {preloader} from './preloader.js'

var shutdown = function(){
  var hb = setInterval(()=>{
    if ($(document).queue('heartbeat').length >= 1){
      _st.heartBeat()
    }
  }
  ,3000);
  preloader.fade()
  $(document).dequeue('shutdown')
  console.log('Shutdown complete')
  $(document).dequeue('afterload')
}

export {shutdown, preloader}
