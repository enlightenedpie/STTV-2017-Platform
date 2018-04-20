import React, { Component } from 'react'
import { SubBtnGroup } from './index'

class Section extends Component {
  constructor() {
    super()
    this.renderSection = this.renderSection.bind(this)
  }

  renderSection () {
    const { handleSecOnChange, removeSecAndPrac, sections,
      addSubComponent, handleOnChange, removeSubComponent } = this.props

    return (
      sections.map((section, sindex) => {
        let sectionName = `section[${sindex+1}]`
        return (
          <div key={sindex+1}>
            <button data-click-type='sections' onClick={(e) => removeSecAndPrac(e,sindex)}>Remove</button>
            <label>Section Name</label>
            <input type='text' name={sectionName} onChange={(e)=> handleSecOnChange(e,sindex)} />

            <button>Intro Video</button>

            <label>Description</label>
            <input type='text' name='sectionDesc' onChange={(e) =>  handleSecOnChange(e,sindex)}/>

            <label>Color</label>
            <input type='text' name='color'/>

            <SubBtnGroup
            handleClick={addSubComponent}
            handleOnChange={handleOnChange}
            sections={sections}
            removeSubComponent={removeSubComponent}
            sindex={sindex}
          />
        </div>
        )
    }))
  }

  render() {
        return (
          <div>{this.renderSection()}</div>
        )
    }

}

export { Section }
