import $ from 'jquery'
import events from './events/loader'

while (events.length){
    events.shift().call(this,$)
}