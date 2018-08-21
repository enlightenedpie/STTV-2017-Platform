export default function render(html) {
    var temp = document.createElement('template')
    temp.innerHTML = html
    var blurs = temp.content.firstChild.querySelectorAll('input, select')
    blurs.forEach((el) => {
        el.addEventListener('blur', () => {
            this.setState([el])
        })
    })
    return this.state.html.push(temp.content.firstChild)
}