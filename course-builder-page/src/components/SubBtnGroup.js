import React from 'react';
import { SubSection, Download } from './index'

const SubBtnGroup = ({ handleClick, handleOnChange, removeSubComponent, sections, practices, sindex, pindex }) => (
<div>
  <SubSection
    handleOnChange = {handleOnChange}
    removeSubComponent = {removeSubComponent}
    sections={sections}
    practices={practices}
    si={sindex}
    pi={pindex}
  />
  {/* <Download /> */}
  <ul>
    <button data-click-type='subsection' onClick={(e)=>handleClick(e,sindex,pindex)}>Add Subsection</button>
    <button data-clcik-type='download'>Download</button>
    <button data-click-type='test'>Test</button>
  </ul>
</div>
)

export { SubBtnGroup };
