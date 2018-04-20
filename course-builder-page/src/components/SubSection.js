import React, { Component } from 'react'

class SubSection extends Component{
  renderSubSection = () => {
    const { handleOnChange, removeSubComponent, sections, practices, si, pi } = this.props
    if(si !== undefined && sections[si].subArray) {
      return(
        sections[si].subArray.map((sub, _si) => {
          let subsection = `sSubsection[${_si+1}]`
          return (
            <div key={_si}>
              <button data-click-type={subsection} onClick={(e)=>removeSubComponent(e,_si,si)}>Delete</button>
              <label>Subsection Name:</label>
              <input type='text' name={subsection} onChange={(e)=>handleOnChange(e,_si,si)} />
            </div>
          )
        })
      )
    }
    else if(pi !== undefined && practices[pi].subArray) {
      return(
        practices[pi].subArray.map((sub, _pi) => {
          let subsection = `pSubsection[${_pi+1}]`
          return (
            <div key={_pi}>
              <button onClick={(e)=>removeSubComponent(e,_pi,pi)} data-click-type={subsection}>Delete</button>
              <label>Subsection Name:</label>
              <input type='text' name={subsection} onChange={(e)=>handleOnChange(e,_pi,pi)} />
            </div>
          )
        })
      )
    }
    return null
  }
  render(){
    return (
      <div>
        {this.renderSubSection()}
      </div>
    )
  }

}

export  { SubSection }
