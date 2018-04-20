import React from 'react'
import ReactDOM from 'react-dom'
import registerServiceWorker from './registerServiceWorker'

import Main from './Main';

const App = () => (
  <div>
    <Main />
  </div>
)

ReactDOM.render(<App />, document.getElementById('root'));
registerServiceWorker()
