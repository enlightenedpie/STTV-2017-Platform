const analytics = ( obj ) => {
  if ( typeof obj === 'undefined' ) {
    return false
  }

  var page = obj.page || false,
    pageview = obj.pageview || false,
    event = obj.event || false,
    action = obj.action || obj.data,
    data = obj.data || false

  if (typeof action === 'string' && typeof data === 'object') {
    //console.log( "ga( "+obj.type+", "+action+", "+data+" )" )
    ga( obj.type, action, data )
  } else if (typeof obj.type !== 'undefined') {
    //console.log( "ga( "+obj.type+", "+action+" )" )
    ga( obj.type, action )
  }

  if ( event ) {
    //console.log( "ga( 'send', 'event', "+event+" )" )
    ga( 'send', 'event', event.name )
  }

  return (pageview) ? (page ? ga( 'send', 'pageview', page ) : ga( 'send', 'pageview' ) ) : pageview
}

export default analytics
