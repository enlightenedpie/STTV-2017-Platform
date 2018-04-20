import React from 'react';
// import { Row, Icon, Col } from 'react-materialize';

const MetaData = ({ handleOnChange }) => (
    <div>
        <label>
          Course Name:
          <input type='text' name='courseName' onChange={(e,name) => handleOnChange(e, name)}/>
        </label>
        <label>
          Test Abbreviation:
          <input type="text" name='testAbbr' onChange={(e,name) => handleOnChange(e, name)}/>
        </label>
        <button>Intro Video</button>
        <label>
          Sales Page:
          <input type='text' name='salesPage' onChange={(e,name) => handleOnChange(e, name)}/>
        </label>
        <label>
          Course Description:
          <textarea type='text' name='courseDesc' onChange={(e,name) => handleOnChange(e, name)}/>
        </label>
  </div>
)

export { MetaData }
