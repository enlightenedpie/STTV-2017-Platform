import React, { Component } from 'react'
import {MetaData, Section, Practice, BtnGroup } from './components/index'

export default class Main extends Component{
  constructor() {
    super();
    this.state = {
        sectionVisibility: false,
        practiceVisibility: false,
          sections: [{}],
          practices:[{}]
  }
}

  handleOnChange = (event) => {
    const {name, value} = event.target;
    this.setState({...this.state, [name]: value})
  }

  handleClick = (event) => {
    const type = event.target.dataset.clickType
    switch (type) {
      case 'section':
        this.setState({
          sectionVisibility: !this.state.sectionVisibility
        })
        break
      case 'practice':
        this.setState({
          practiceVisibility: !this.state.practiceVisibility
        })
        break
        default:
    }
  }

  handleSecOnChange = (event, i) => {
   const { name, value } = event.target
   const sections = this.state.sections
   const newSection = sections.map((section,si) => {
     if(si === i) return {...section, [name]: value}
     return section
   })
     this.setState({
       ...this.state,
       sections: newSection
     })
  }

  handleSubsecOnChange = (event, index, i) => {
    // console.log(this.state.sections)
    const { name, value } = event.target
    const subArray = this.state.sections[i].subArray
    const pSubArray = this.state.practices[i].subArray
    // console.log(this.state.sections[i])
    if (subArray !== undefined) {
      // replaceing subArray[i] as newSubArray
      const newSections = this.state.sections.map((sec, _idx) => {
        var newSubArray
        var newSec
        if(_idx === i) {
           newSubArray = subArray.map((sub, sindex) => {
             if(sindex === index) return {...sub, [name]: value}
             return sub
            })
           newSec = {...this.state.sections[i], subArray: newSubArray}
          return newSec
          }
          return { ...sec }
      })
      this.setState({
        ...this.state,
        sections: newSections
        })
    }
    else if(pSubArray !== undefined) {
      // console.log("practice")
      const newPractices = this.state.practices.map((prac, _pidx) => {
        var newSubArray
        var newPrac
        if (_pidx === i) {
          newSubArray = pSubArray.map((psub, pindex) => {
            if(pindex === index) return {...psub, [name]:value}
            return psub
          })
          newPrac = {...this.state.practices[i],subArray:newSubArray}
          return newPrac
        }
        return { ...prac }
      })
      this.setState({
        ...this.state,
        practices: newPractices
      })
    }
    return null
  }

  addSectionAndPractice = (event) => {
    event.preventDefault()
    const type = event.target.dataset.clickType
    switch(type) {
      case 'sections':
      this.setState((prevState) => {
        return {
            ...prevState,
            sections:this.state.sections.concat({})
          }
      })
        break
      case 'practices':
      this.setState((prevState) => {
        return {
            ...prevState,
            practices:this.state.practices.concat({})
          }
      })
        break
      default:
    }
  }

  addSubComponent = (event, si, pi) => {
    event.preventDefault()
    const sections = this.state.sections
    const practices = this.state.practices
    const type = event.target.dataset.clickType

    let newSecObj
    let newPracObj
    if(si !== undefined){
        if(!sections[si].subArray) {
          newSecObj = {
            ...sections[si],
            subArray:[{}]
          }
          this.setState({
              ...this.state,
              sections:[
                ...sections.slice(0,si),
                Object.assign({}, sections[si], newSecObj),
                ...sections.slice(si+1)
              ]
          })
        }
        else {
          const newSubArray = sections[si].subArray.concat({})
          newSecObj= {
            ...sections[si],
            subArray:newSubArray
          }
          this.setState({
              ...this.state,
              sections:[
                ...sections.slice(0,si),
                Object.assign({}, sections[si], newSecObj),
                ...sections.slice(si+1)
              ]
          })
        }
    }
    else if(pi !== undefined) {
      if(!practices[pi].subArray) {
         newPracObj= {
           ...practices[pi],
           subArray: [{}]
         }
         this.setState({
             ...this.state,
             practices:[
               ...practices.slice(0,pi),
               Object.assign({}, practices[pi], newPracObj),
               ...practices.slice(pi+1)
             ]
         })
     }
     else {
       const newSubArray = practices[pi].subArray.concat({})
       newPracObj= {
         ...practices[pi],
         subArray:newSubArray
       }
       this.setState({
           ...this.state,
           practices:[
             ...practices.slice(0,pi),
             Object.assign({}, practices[si], newPracObj),
             ...practices.slice(pi+1)
           ]
       })
     }
    } else {
      return null
    }
}

  removeSecAndPrac = (event,i) => {
    event.preventDefault()
    const type = event.target.dataset.clickType
    switch (type) {
      case 'sections':
      let newSection = this.state.sections
      if(i === 0) return
      newSection.splice(i,1)
      this.setState((prevState) => {
        return {
            ...prevState,
            sections:newSection
          }
      })
        break
      case 'practices':
      let newPractice = this.state.practices
      if(i === 0) return
      newPractice.splice(i,1)
      this.setState((prevState) => {
        return {
            ...prevState,
            sections:newPractice
          }
      })
        break
      default:
    }
  }

  removeSubComponent = (event, index, i) => {
    event.preventDefault()
    const sections = this.state.sections
    const practices = this.state.practices
    const type = event.target.dataset.clickType
    const _index = index +1
    const sectionsSub = 'sSubsection[' + _index + ']'
    const practicesSub = 'pSubsection[' + _index + ']'

    if(type === sectionsSub) {
      let newSubArray= sections[i].subArray.filter((el, sindex) => index !== sindex)
      const newSecObj = {...this.state.sections[i], subArray: newSubArray}
      this.setState({
        ...this.state,
        sections:[
          ...sections.slice(0,i),
          Object.assign({}, sections[i], newSecObj),
          ...sections.slice(i+1)
        ]
       })
    } else if(type === practicesSub) {
      let newSubArray= practices[i].subArray.filter((prac, pindex) => index !== pindex)
      const newPracObj = {...this.state.practices[i], subArray: newSubArray}
      this.setState({
        ...this.state,
        practices:[
          ...practices.slice(0,i),
          Object.assign({}, practices[i], newPracObj),
          ...practices.slice(i+1)
        ]
       })
    }
    return null
  }

  handleSubmit = () => {
    alert(JSON.stringify(this.state))
  }

  render() {
    return (
      <form onSubmit={this.handleSubmit}>
        <div>
           <MetaData handleOnChange={this.handleOnChange} />
        </div>

        <div>
          <BtnGroup
            handleClick = {this.handleClick}
          />
        </div>

        <div>
          {this.state.sectionVisibility ?
            <div style= {{ border: '2px solid black'}}>
              <Section
                handleSecOnChange={this.handleSecOnChange}
                addSubComponent={this.addSubComponent}
                handleClick={this.handleClick}
                sections={this.state.sections}
                removeSecAndPrac={this.removeSecAndPrac}
                handleOnChange={this.handleSubsecOnChange}
                removeSubComponent={this.removeSubComponent}
               />
                <button data-click-type='sections' onClick={(e)=>this.addSectionAndPractice(e)}>Add Section</button>
            </div>
             :
            null
          }
           <div>
           {this.state.practiceVisibility ?
             <div style= {{ border: '2px dotted red'}}>
             <Practice
               practices = {this.state.practices}
               addSubComponent={this.addSubComponent}
               handleClick={this.handleClick}
               handleOnChange={this.handleSubsecOnChange}
               removeSubComponent={this.removeSubComponent}
               removeSecAndPrac={this.removeSecAndPrac}
             />
             <button data-click-type='practices' onClick={(e)=>this.addSectionAndPractice(e)}>Add Practice</button>
             </div>
             :
             null
          }
          </div>
        </div>
      <button>Submit</button>
    </form>
  )
  }
}
