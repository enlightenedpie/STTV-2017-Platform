import React, { Component } from 'react'
import { SubBtnGroup } from './index'

class Practice extends Component {
  render() {
    const { practices, removeSecAndPrac, addSubComponent, handleOnChange, removeSubComponent } = this.props

    return (practices.map((practice,pindex) => {
      return (
        <div key={pindex+1}>
          <button data-click-type='practices' onClick={(e) => removeSecAndPrac(e,pindex)}>Remove</button>
          <SubBtnGroup
          handleClick={addSubComponent}
          handleOnChange={handleOnChange}
          practices={practices}
          removeSubComponent={removeSubComponent}
          pindex={pindex}
          />
         </div>
        )
      })
    )
  }
}

export { Practice }
