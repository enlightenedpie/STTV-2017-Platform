const stDom = (type, stuff, ...children) => {
    var appendText = (el, text) => {
        const textNode = document.createTextNode(text)
        el.appendChild(textNode)
    }

    var appendArray = (el, children) => {
        children.forEach((child) => {
            if (Array.isArray(child))
                appendArray(el,child)
            else if (child instanceof window.Element)
                el.appendChild(child)
            else if (typeof child === 'string')
                appendText(el,child)
        })
    }

    const el = document.createElement(type)
    appendText(el,text)
    return el
}

export const div = (...args) => stDom('div',...args)
export const section = (...args) => stDom('section',...args)