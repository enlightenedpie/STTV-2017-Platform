import React from 'react'
import {BrowserRouter as Router, Route, Link} from 'react-router-dom';

import Main from '../Main';
import English from '../components/content-display/English';
import Reading from '../components/content-display/Reading';
import Math from '../components/content-display/Math';
import Science from '../components/content-display/Science';
import Essay from '../components/content-display/Essay';
import Practice from '../components/content-display/Practice';

const routes = () => (
  <Router basename="/courses/the-best-act-prep-course-ever">
  <div>
    <ul>
      <li>
        <Link to="/english">English</Link>
      </li>
      <li>
        <Link to="/reading">Reading</Link>
      </li>
      <li>
        <Link to="/math">Math</Link>
      </li>
      <li>
        <Link to="/science">Science</Link>
      </li>
      <li>
        <Link to="/essay">Essay</Link>
      </li>
      <li>
        <Link to="/practice">Practice</Link>
      </li>
    </ul>

    <Route path="/english" component={English}/>
    <Route path="/reading" component={Reading}/>
    <Route path="/math" component={Math}/>
    <Route path="/science" component={Science}/>
    <Route path="/essay" component={Essay}/>
    <Route path="/practice" component={Practice}/>
    <Route path="*" component={Main} />
  </div>
</Router>)

export default routes;
