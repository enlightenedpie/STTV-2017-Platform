import React, { Component } from 'react';
import {
  BrowserRouter as Router,
  Route,
  Link
} from 'react-router-dom';

class Main extends Component{
  render() {
    return(
      <div>
        <h1>I am Main Page</h1>
        {/* <div>
          <Link to='/english'>Get Started</Link>
        </div> */}
      </div>
    )
  }
}
// import English from './components/content-display/English';
//
// const Main = () => (
// <div>
//   <Router>
//     <div>
//       <Route path="/english" component={English}/>
//     </div>
//   </Router>
// </div>
// )

export default Main;
